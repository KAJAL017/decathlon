<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AIUsageLog extends Model
{
    protected $table = 'ai_usage_logs';

    protected $fillable = [
        'provider', 'model', 'type',
        'prompt_tokens', 'completion_tokens', 'total_tokens',
        'success', 'error_message', 'admin_id',
    ];

    protected $casts = [
        'success' => 'boolean',
    ];

    public static function record(string $provider, ?string $model, string $type, int $tokens = 0, bool $success = true, ?string $error = null): void
    {
        static::create([
            'provider'          => $provider,
            'model'             => $model,
            'type'              => $type,
            'total_tokens'      => $tokens,
            'prompt_tokens'     => (int)($tokens * 0.6),
            'completion_tokens' => (int)($tokens * 0.4),
            'success'           => $success,
            'error_message'     => $error,
            'admin_id'          => session('admin_id'),
        ]);
    }
}
