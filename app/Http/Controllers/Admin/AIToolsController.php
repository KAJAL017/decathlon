<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use App\Models\AIUsageLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;

class AIToolsController extends Controller
{
    public function index()
    {
        return view('admin.pages.ai-tools.index');
    }

    /**
     * Test AI provider connection
     */
    public function testConnection(Request $request)
    {
        $provider = Setting::get('ai_provider', '');
        $apiKey   = Setting::get('ai_api_key', '');
        $model    = Setting::get('ai_model') ?? '';

        if (!$provider || !$apiKey) {
            return response()->json(['success' => false, 'message' => 'AI provider not configured. Save your settings first.'], 422);
        }

        try {
            $result = $this->callAI($provider, $apiKey, $model, 'Say "Hello from Decathlon AI!" in one sentence.');

            AIUsageLog::record($provider, $model, 'test', 20, true);

            return response()->json([
                'success'  => true,
                'message'  => 'Connection successful!',
                'response' => $result,
                'provider' => $provider,
                'model'    => $model,
            ]);
        } catch (\Exception $e) {
            AIUsageLog::record($provider, $model, 'test', 0, false, $e->getMessage());
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Generate content using AI
     */
    public function generate(Request $request)
    {
        $request->validate([
            'type'   => 'required|in:description,seo,tags,alt_text',
            'prompt' => 'required|string|max:2000',
        ]);

        $provider = Setting::get('ai_provider', '');
        $apiKey   = Setting::get('ai_api_key', '');
        $model    = Setting::get('ai_model') ?? '';
        $language = Setting::get('ai_language', 'English') ?? 'English';

        if (!$provider || !$apiKey) {
            return response()->json(['success' => false, 'message' => 'AI not configured. Go to AI Setup tab first.'], 422);
        }

        $systemPrompts = [
            'description' => "You are an expert ecommerce copywriter for Decathlon India. Write compelling product descriptions in {$language}. Be concise, highlight key features, and use persuasive language.",
            'seo'         => "You are an SEO expert for Decathlon India. Generate optimized meta titles, descriptions and keywords in {$language}. Follow SEO best practices.",
            'tags'        => "You are a product tagging expert. Suggest relevant product tags in {$language}. Return only a comma-separated list of tags, no explanation.",
            'alt_text'    => "You are an accessibility and SEO expert. Generate descriptive image alt text in {$language}. Keep it concise and descriptive.",
        ];

        try {
            $result = $this->callAI(
                $provider,
                $apiKey,
                $model,
                $request->prompt,
                $systemPrompts[$request->type] ?? ''
            );

            // Estimate tokens (rough: 1 token ≈ 4 chars)
            $tokens = (int)((strlen($request->prompt) + strlen($result)) / 4);
            AIUsageLog::record($provider, $model, $request->type, $tokens, true);

            return response()->json(['success' => true, 'result' => $result]);
        } catch (\Exception $e) {
            AIUsageLog::record($provider, $model, $request->type, 0, false, $e->getMessage());
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Get usage stats
     */
    public function usage(Request $request)
    {
        $days = (int)($request->days ?? 30);
        $from = now()->subDays($days);

        $total      = AIUsageLog::where('created_at', '>=', $from)->count();
        $successful = AIUsageLog::where('created_at', '>=', $from)->where('success', true)->count();
        $failed     = AIUsageLog::where('created_at', '>=', $from)->where('success', false)->count();
        $tokens     = AIUsageLog::where('created_at', '>=', $from)->where('success', true)->sum('total_tokens');

        // By type
        $byType = AIUsageLog::where('created_at', '>=', $from)
            ->where('success', true)
            ->select('type', DB::raw('count(*) as count'), DB::raw('sum(total_tokens) as tokens'))
            ->groupBy('type')
            ->orderByDesc('count')
            ->get();

        // By provider
        $byProvider = AIUsageLog::where('created_at', '>=', $from)
            ->select('provider', 'model', DB::raw('count(*) as count'), DB::raw('sum(total_tokens) as tokens'))
            ->groupBy('provider', 'model')
            ->orderByDesc('count')
            ->get();

        // Daily usage (last 14 days)
        $daily = AIUsageLog::where('created_at', '>=', now()->subDays(14))
            ->select(DB::raw('DATE(created_at) as date'), DB::raw('count(*) as count'), DB::raw('sum(total_tokens) as tokens'))
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Recent logs
        $recent = AIUsageLog::latest()->limit(20)->get();

        return response()->json([
            'success' => true,
            'data' => [
                'total'       => $total,
                'successful'  => $successful,
                'failed'      => $failed,
                'total_tokens'=> $tokens,
                'by_type'     => $byType,
                'by_provider' => $byProvider,
                'daily'       => $daily,
                'recent'      => $recent,
            ],
        ]);
    }

    /**
     * Core AI call — supports OpenAI, Gemini, Claude, Custom
     */
    private function callAI(string $provider, string $apiKey, ?string $model, string $prompt, string $system = ''): string
    {
        $maxTokens = (int) Setting::get('ai_max_tokens', 1000);

        switch ($provider) {

            case 'openai':
            case 'custom':
                $endpoint = $provider === 'custom'
                    ? rtrim(Setting::get('ai_endpoint', 'https://api.openai.com/v1'), '/') . '/chat/completions'
                    : 'https://api.openai.com/v1/chat/completions';

                $messages = [];
                if ($system) $messages[] = ['role' => 'system', 'content' => $system];
                $messages[] = ['role' => 'user', 'content' => $prompt];

                $response = Http::withToken($apiKey)
                    ->timeout(30)
                    ->post($endpoint, [
                        'model'      => $model ?: 'gpt-4o-mini',
                        'messages'   => $messages,
                        'max_tokens' => $maxTokens,
                    ]);

                if (!$response->successful()) {
                    $err = $response->json('error.message') ?? $response->body();
                    throw new \Exception("OpenAI error: {$err}");
                }

                return $response->json('choices.0.message.content') ?? '';

            case 'gemini':
                $geminiModel = $model ?: 'gemini-2.5-flash';
                $endpoint    = "https://generativelanguage.googleapis.com/v1beta/models/{$geminiModel}:generateContent?key={$apiKey}";

                $parts = [];
                if ($system) $parts[] = ['text' => $system . "\n\n"];
                $parts[] = ['text' => $prompt];

                $response = Http::timeout(30)
                    ->post($endpoint, [
                        'contents' => [['parts' => $parts]],
                        'generationConfig' => [
                            'maxOutputTokens' => $maxTokens,
                            'temperature'     => 0.7,
                        ],
                    ]);

                if (!$response->successful()) {
                    $err = $response->json('error.message') ?? $response->body();
                    throw new \Exception("Gemini error: {$err}");
                }

                return $response->json('candidates.0.content.parts.0.text') ?? '';

            case 'claude':
                $response = Http::withHeaders([
                    'x-api-key'         => $apiKey,
                    'anthropic-version' => '2023-06-01',
                    'content-type'      => 'application/json',
                ])->timeout(30)->post('https://api.anthropic.com/v1/messages', [
                    'model'      => $model ?: 'claude-3-haiku-20240307',
                    'max_tokens' => $maxTokens,
                    'system'     => $system ?: 'You are a helpful assistant for Decathlon India ecommerce store.',
                    'messages'   => [['role' => 'user', 'content' => $prompt]],
                ]);

                if (!$response->successful()) {
                    $err = $response->json('error.message') ?? $response->body();
                    throw new \Exception("Claude error: {$err}");
                }

                return $response->json('content.0.text') ?? '';

            default:
                throw new \Exception("Unknown AI provider: {$provider}");
        }
    }
}
