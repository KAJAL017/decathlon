<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Http;

class Webhook extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name', 'url', 'events', 'secret', 'method', 'headers',
        'is_active', 'timeout', 'retry_count',
        'last_triggered_at', 'last_status', 'total_calls', 'failed_calls',
        'last_response', 'created_by',
    ];

    protected $casts = [
        'events'             => 'array',
        'headers'            => 'array',
        'is_active'          => 'boolean',
        'last_triggered_at'  => 'datetime',
    ];

    /**
     * Dispatch this webhook with a payload
     */
    public function dispatch(array $payload): bool
    {
        $this->increment('total_calls');
        $this->update(['last_triggered_at' => now()]);

        try {
            $headers = array_merge(
                ['Content-Type' => 'application/json', 'X-Webhook-Source' => 'Decathlon'],
                $this->headers ?? []
            );

            // Add HMAC signature if secret is set
            if ($this->secret) {
                $body = json_encode($payload);
                $headers['X-Webhook-Signature'] = 'sha256=' . hash_hmac('sha256', $body, $this->secret);
            }

            $response = Http::withHeaders($headers)
                ->timeout($this->timeout ?? 10)
                ->send($this->method, $this->url, ['json' => $payload]);

            $success = $response->successful();
            $this->update([
                'last_status'   => $success ? 'success' : 'failed',
                'last_response' => substr($response->body(), 0, 500),
            ]);

            if (!$success) $this->increment('failed_calls');
            return $success;

        } catch (\Exception $e) {
            $this->increment('failed_calls');
            $this->update([
                'last_status'   => 'failed',
                'last_response' => $e->getMessage(),
            ]);
            return false;
        }
    }

    /**
     * Dispatch all active webhooks for a given event
     */
    public static function fire(string $event, array $payload = []): void
    {
        $webhooks = static::where('is_active', true)
            ->whereJsonContains('events', $event)
            ->get();

        foreach ($webhooks as $webhook) {
            $webhook->dispatch(array_merge($payload, [
                'event'     => $event,
                'timestamp' => now()->toISOString(),
                'source'    => 'decathlon-admin',
            ]));
        }
    }
}
