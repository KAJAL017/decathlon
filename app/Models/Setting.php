<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Crypt;

class Setting extends Model
{
    protected $fillable = ['group','key','value','type','label','description'];

    // Keys that should be encrypted in DB
    private static array $sensitiveKeys = [
        'razorpay_key_secret',
        'razorpay_webhook_secret',
        'shiprocket_password',
        'delhivery_token',
        'fb_pixel_id',
        'mailchimp_api_key',
        'msg91_auth_key',
        'twilio_auth_token',
        'ai_api_key',
        'google_client_secret',
    ];

    // Get a setting value
    public static function get(string $key, $default = null)
    {
        return Cache::remember("setting_{$key}", 3600, function () use ($key, $default) {
            $s = static::where('key', $key)->first();
            if (!$s) return $default;

            // Decrypt sensitive values
            if (in_array($key, static::$sensitiveKeys) && $s->value) {
                try {
                    return Crypt::decryptString($s->value);
                } catch (\Exception $e) {
                    // Already plain text (legacy) — return as-is
                    return $s->value;
                }
            }

            return $s->value;
        });
    }

    // Set a setting value
    public static function set(string $key, $value, string $group = 'general'): void
    {
        // Encrypt sensitive values before storing
        $storeValue = $value;
        if (in_array($key, static::$sensitiveKeys) && $value !== '') {
            $storeValue = Crypt::encryptString($value);
        }

        static::updateOrCreate(['key' => $key], ['value' => $storeValue, 'group' => $group]);
        Cache::forget("setting_{$key}");
    }

    // Get all settings for a group as key=>value array
    public static function group(string $group): array
    {
        $rows = static::where('group', $group)->get(['key', 'value']);
        $result = [];
        foreach ($rows as $row) {
            if (in_array($row->key, static::$sensitiveKeys) && $row->value) {
                try {
                    $result[$row->key] = Crypt::decryptString($row->value);
                } catch (\Exception $e) {
                    $result[$row->key] = $row->value;
                }
            } else {
                $result[$row->key] = $row->value;
            }
        }
        return $result;
    }

    // Save multiple settings at once
    public static function saveMany(array $data, string $group): void
    {
        foreach ($data as $key => $value) {
            static::set($key, $value, $group);
        }
    }
}
