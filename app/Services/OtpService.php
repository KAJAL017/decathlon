<?php

namespace App\Services;

use App\Models\Setting;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class OtpService
{
    protected int $expiryMinutes;
    protected int $maxAttempts;
    protected int $baseCooldown;
    protected int $maxCooldown;

    public function __construct()
    {
        $this->expiryMinutes = 10;
        $this->maxAttempts = 5;
        $this->baseCooldown = 30;
        $this->maxCooldown = 300;
    }

    public static function detectEmailProvider(): string
    {
        $explicit = Setting::get('email_provider', '');
        if ($explicit === 'brevo' && Setting::get('brevo_api_key', '')) return 'brevo';
        if ($explicit === 'smtp' && Setting::get('smtp_host', ''))      return 'smtp';

        if (Setting::get('brevo_api_key', '')) return 'brevo';
        if (Setting::get('smtp_host', ''))      return 'smtp';
        return 'none';
    }

    public static function getActiveEmailProviderLabel(): string
    {
        return match(static::detectEmailProvider()) {
            'brevo' => 'Brevo',
            'smtp'  => 'SMTP (' . Setting::get('smtp_host', '') . ')',
            default => 'Not Configured',
        };
    }

    public function generate(string $type, ?string $email = null, ?string $phone = null, ?string $ip = null): array
    {
        $otp = str_pad(random_int(100000, 999999), 6, '0', STR_PAD_LEFT);

        DB::table('otp_tokens')->where('type', $type)
            ->where(function ($q) use ($email, $phone) {
                if ($email) $q->where('email', $email);
                if ($phone) $q->orWhere('phone', $phone);
            })
            ->update(['is_verified' => true]);

        DB::table('otp_tokens')->insert([
            'email'      => $email,
            'phone'      => $phone,
            'otp'        => $otp,
            'type'       => $type,
            'is_verified'=> false,
            'expires_at' => now()->addMinutes($this->expiryMinutes),
            'ip_address' => $ip,
            'attempts'   => 0,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return ['otp' => $otp, 'expires_in' => $this->expiryMinutes * 60];
    }

    public function verify(string $type, ?string $email, ?string $phone, string $otp): array
    {
        $query = DB::table('otp_tokens')
            ->where('type', $type)
            ->where('is_verified', false)
            ->where('expires_at', '>', now())
            ->orderByDesc('id');

        if ($email) $query->where('email', $email);
        if ($phone) $query->where('phone', $phone);

        $token = $query->first();

        if (!$token) {
            return ['success' => false, 'message' => 'OTP expired or not found. Please request a new one.'];
        }

        if ($token->attempts >= $this->maxAttempts) {
            return ['success' => false, 'message' => 'Maximum OTP attempts exceeded. Please request a new one.'];
        }

        DB::table('otp_tokens')->where('id', $token->id)->increment('attempts');

        if ($token->otp !== $otp) {
            $remaining = $this->maxAttempts - ($token->attempts + 1);
            return ['success' => false, 'message' => "Invalid OTP. {$remaining} attempts remaining."];
        }

        DB::table('otp_tokens')->where('id', $token->id)->update([
            'is_verified' => true,
            'verified_at' => now(),
        ]);

        return ['success' => true, 'message' => 'OTP verified successfully.'];
    }

    public function sendEmailOtp(string $email, string $otp, string $type = 'login'): bool
    {
        $labels = [
            'login'            => ['heading' => 'Your Login OTP',          'subject' => 'Your Decathlon Login OTP',          'desc' => 'Use the code below to sign in.'],
            'register'         => ['heading' => 'Verify Your Email',       'subject' => 'Verify Your Email — Decathlon',     'desc' => 'Use the code below to verify your email address.'],
            'forgot_password'  => ['heading' => 'Password Reset OTP',      'subject' => 'Password Reset — Decathlon',        'desc' => 'Use the code below to reset your password.'],
            'email_verification' => ['heading' => 'Email Verification',    'subject' => 'Email Verification — Decathlon',   'desc' => 'Use the code below to verify your email.'],
        ];
        $label = $labels[$type] ?? $labels['login'];

        $html = '<!DOCTYPE html><html lang="en"><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1.0">'
            . '<title>' . e($label['subject']) . '</title></head>'
            . '<body style="margin:0;padding:0;background:#f0f2f5;font-family:\'Segoe UI\',Arial,Helvetica,sans-serif;">'
            . '<table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background:#f0f2f5;">'
            . '<tr><td align="center" style="padding:40px 16px;">'
            . '<table role="presentation" width="480" cellpadding="0" cellspacing="0" style="max-width:480px;width:100%;background:#ffffff;border-radius:16px;overflow:hidden;box-shadow:0 4px 24px rgba(0,0,0,0.06);">'

            // Header
            . '<tr><td style="background:linear-gradient(135deg,#0082C3 0%,#006fa3 100%);padding:32px 40px;text-align:center;">'
            . '<h1 style="color:#ffffff;margin:0;font-size:26px;font-weight:800;letter-spacing:2px;">DECATHLON</h1>'
            . '<p style="color:rgba(255,255,255,0.85);margin:6px 0 0;font-size:13px;letter-spacing:0.5px;">SPORTS FOR ALL</p>'
            . '</td></tr>'

            // Body
            . '<tr><td style="padding:40px 40px 20px;text-align:center;">'
            . '<div style="width:56px;height:56px;background:#e8f4fd;border-radius:50%;margin:0 auto 20px;line-height:56px;font-size:24px;">&#128274;</div>'
            . '<h2 style="color:#1a1a1a;font-size:22px;font-weight:700;margin:0 0 10px;">' . e($label['heading']) . '</h2>'
            . '<p style="color:#6b7280;font-size:15px;line-height:1.6;margin:0 0 28px;">' . e($label['desc']) . ' It expires in <strong style="color:#374151;">' . $this->expiryMinutes . ' minutes</strong>.</p>'

            // OTP Code Box
            . '<table role="presentation" cellpadding="0" cellspacing="0" width="100%"><tr><td align="center">'
            . '<div style="background:#f8fbff;border:2px dashed #0082C3;border-radius:12px;padding:20px 0;margin:0 20px;">'
            . '<span style="font-size:36px;font-weight:900;color:#0082C3;letter-spacing:10px;font-family:\'Courier New\',monospace;">' . e($otp) . '</span>'
            . '</div></td></tr></table>'

            . '</td></tr>'

            // Divider
            . '<tr><td style="padding:0 40px;"><hr style="border:none;border-top:1px solid #e5e7eb;margin:0;"></td></tr>'

            // Footer
            . '<tr><td style="padding:20px 40px 32px;text-align:center;">'
            . '<p style="color:#9ca3af;font-size:12px;line-height:1.6;margin:0;">'
            . 'If you did not request this, please ignore this email. Do not share this OTP with anyone.'
            . '<br><span style="color:#b0b8c4;">&copy; ' . date('Y') . ' Decathlon. All rights reserved.</span></p>'
            . '</td></tr>'

            . '</table></td></tr></table></body></html>';

        $provider = static::detectEmailProvider();

        if ($provider === 'brevo') {
            $brevo = new BrevoService();
            $brevo->sendEmail($email, '', $label['subject'], $html);
            return true;
        }

        if ($provider === 'smtp') {
            return $this->sendEmailViaSmtp($email, $label['subject'], $html);
        }

        throw new \Exception('No email provider configured. Set up Brevo or SMTP in Admin → Integrations.');
    }

    protected function sendEmailViaSmtp(string $toEmail, string $subject, string $htmlContent): bool
    {
        $host       = Setting::get('smtp_host', '');
        $port       = Setting::get('smtp_port', '587');
        $encryption = Setting::get('smtp_encryption', 'tls');
        $username   = Setting::get('smtp_username', '');
        $password   = Setting::get('smtp_password', '');
        $fromEmail  = Setting::get('smtp_from_email', $username);
        $fromName   = Setting::get('smtp_from_name', 'Decathlon');

        config([
            'mail.mailers.smtp.host'       => $host,
            'mail.mailers.smtp.port'       => (int) $port,
            'mail.mailers.smtp.encryption' => $encryption === 'none' ? null : $encryption,
            'mail.mailers.smtp.username'   => $username,
            'mail.mailers.smtp.password'   => $password,
            'mail.from.address'            => $fromEmail,
            'mail.from.name'               => $fromName,
        ]);

        Mail::send([], [], function ($message) use ($toEmail, $subject, $htmlContent, $fromEmail, $fromName) {
            $message->to($toEmail)
                    ->subject($subject)
                    ->html($htmlContent)
                    ->from($fromEmail, $fromName);
        });

        return true;
    }

    public function sendPhoneOtp(string $phone, string $otp): bool
    {
        $authKey = Setting::get('msg91_auth_key', '');
        if (!$authKey) throw new \Exception('SMS service not configured. Contact administrator.');

        $templateId = Setting::get('msg91_tpl_otp', '');
        $senderId   = Setting::get('msg91_sender_id', 'DECATH');
        $entityId   = Setting::get('msg91_entity_id', '');

        $payload = [
            'authorization' => $authKey,
            'message'       => str_replace('{otp}', $otp, $templateId ?: 'Your OTP is {otp}. Valid 10 mins. -DECATH'),
            'sender'        => $senderId,
            'countries'     => 'IN',
            'flash'         => 0,
        ];

        if ($entityId) $payload['entity_id'] = $entityId;

        $response = \Illuminate\Support\Facades\Http::withHeaders([
            'authkey' => $authKey,
            'Content-Type' => 'application/json',
        ])->post('https://api.msg91.com/api/v5/otp', $payload);

        if ($response->failed()) {
            throw new \Exception('Failed to send OTP. Please try again.');
        }

        return true;
    }

    public function cleanup(): int
    {
        return DB::table('otp_tokens')
            ->where('expires_at', '<', now()->subHour())
            ->delete();
    }

    public function getRemainingCooldown(string $type, ?string $email = null, ?string $phone = null): int
    {
        $query = DB::table('otp_tokens')
            ->where('type', $type)
            ->orderByDesc('id')
            ->limit(1);

        if ($email) $query->where('email', $email);
        if ($phone) $query->where('phone', $phone);

        $last = $query->first();
        if (!$last) return 0;

        $now = Carbon::now(config('app.timezone', 'UTC'));
        $createdAt = Carbon::parse($last->created_at, config('app.timezone', 'UTC'));

        $elapsed = max(0, (int) $now->diffInSeconds($createdAt, false));

        $recentCount = DB::table('otp_tokens')
            ->where('type', $type)
            ->where('created_at', '>', now()->subMinutes(30))
            ->count();

        $cooldown = $this->baseCooldown * pow(2, max(0, $recentCount - 1));
        $cooldown = min($cooldown, $this->maxCooldown);

        $remaining = max(0, $cooldown - $elapsed);

        return $remaining;
    }

    public function formatCooldown(int $seconds): string
    {
        if ($seconds <= 0) return '';

        if ($seconds < 60) {
            return $seconds . ' second' . ($seconds !== 1 ? 's' : '');
        }

        $minutes = (int) ceil($seconds / 60);
        return $minutes . ' minute' . ($minutes !== 1 ? 's' : '');
    }

    public function getRecentResendCount(string $type, ?string $email = null, ?string $phone = null): int
    {
        $query = DB::table('otp_tokens')
            ->where('type', $type)
            ->where('created_at', '>', now()->subMinutes(30));

        if ($email) $query->where('email', $email);
        if ($phone) $query->where('phone', $phone);

        return $query->count();
    }

    public function purgeExpiredOtpRecords(string $type, ?string $email = null, ?string $phone = null): int
    {
        $query = DB::table('otp_tokens')
            ->where('type', $type)
            ->where(function ($q) {
                $q->where('expires_at', '<', now())
                  ->orWhere('is_verified', true);
            });

        if ($email) $query->where('email', $email);
        if ($phone) $query->where('phone', $phone);

        return $query->delete();
    }
}
