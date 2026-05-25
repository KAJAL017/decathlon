<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    private array $groups = [
        'general','store','payment','shipping','tax','notifications','seo','advanced','integrations','localization','login_methods'
    ];

    public function index()
    {
        $settings = [];
        foreach ($this->groups as $group) {
            $settings[$group] = Setting::group($group);
        }
        return view('admin.pages.settings.index', compact('settings'));
    }

    public function save(Request $request, string $group)
    {
        if (!in_array($group, $this->groups)) {
            return response()->json(['success' => false, 'message' => 'Invalid group'], 422);
        }

        $data = $request->except(['_token','_method']);

        // Boolean fields per group — only set to '0' if not present in request
        $booleanFieldsByGroup = [
            'store'         => ['store_maintenance_mode','store_reviews_enabled','store_wishlist_enabled','store_compare_enabled'],
            'payment'       => [],
            'shipping'      => ['shipping_free_enabled','shipping_express_enabled'],
            'tax'           => ['tax_inclusive','tax_enabled'],
            'notifications' => ['notif_new_order','notif_low_stock','notif_new_review','notif_new_customer'],
            'seo'           => ['seo_sitemap_enabled','seo_robots_noindex'],
            'advanced'      => ['advanced_debug_mode','advanced_cache_enabled'],
            'login_methods' => ['login_method_email','login_method_email_otp','login_method_google','login_method_otp','login_method_guest','registration_enabled'],
            'integrations'  => [
                'cod_enabled',
                'mailchimp_sync_on_register','mailchimp_sync_on_order','mailchimp_double_optin',
                'msg91_on_order','msg91_on_shipped','msg91_on_delivered','msg91_on_otp',
                'twilio_sms_enabled','twilio_whatsapp_enabled','twilio_voice_enabled',
                'twilio_on_order','twilio_on_shipped','twilio_on_delivered','twilio_on_otp',
            ],
        ];

        $groupBooleans = $booleanFieldsByGroup[$group] ?? [];
        foreach ($groupBooleans as $field) {
            if (!isset($data[$field])) {
                $data[$field] = '0';
            }
        }

        Setting::saveMany($data, $group);

        \App\Models\ActivityLog::log('updated', 'settings', "Updated {$group} settings", ['group' => $group]);

        return response()->json(['success' => true, 'message' => ucfirst($group) . ' settings saved successfully']);
    }

    public function get(string $group)
    {
        if (!in_array($group, $this->groups)) {
            return response()->json(['success' => false, 'message' => 'Invalid group'], 422);
        }
        return response()->json(['success' => true, 'data' => Setting::group($group)]);
    }

    // ── Test SMTP ─────────────────────────────────────────────────
    public function testSmtp(\Illuminate\Http\Request $request)
    {
        $request->validate(['to' => 'required|email']);

        $host       = Setting::get('smtp_host', '');
        $port       = Setting::get('smtp_port', '587');
        $encryption = Setting::get('smtp_encryption', 'tls');
        $username   = Setting::get('smtp_username', '');
        $password   = Setting::get('smtp_password', '');
        $fromName   = Setting::get('smtp_from_name', 'Decathlon');
        $fromEmail  = Setting::get('smtp_from_email', '');

        if (!$host || !$username || !$password) {
            return response()->json(['success' => false, 'message' => 'SMTP not configured. Save settings first.'], 422);
        }

        try {
            // Temporarily override mail config
            config([
                'mail.mailers.smtp.host'       => $host,
                'mail.mailers.smtp.port'       => (int)$port,
                'mail.mailers.smtp.encryption' => $encryption === 'none' ? null : $encryption,
                'mail.mailers.smtp.username'   => $username,
                'mail.mailers.smtp.password'   => $password,
                'mail.from.address'            => $fromEmail ?: $username,
                'mail.from.name'               => $fromName,
            ]);

            \Illuminate\Support\Facades\Mail::raw(
                "This is a test email from your Decathlon admin panel.\n\nSMTP is working correctly!\n\nHost: {$host}:{$port}\nEncryption: {$encryption}",
                function ($message) use ($request, $fromEmail, $fromName, $username) {
                    $message->to($request->to)
                            ->from($fromEmail ?: $username, $fromName)
                            ->subject('✓ SMTP Test — Decathlon Admin');
                }
            );

            return response()->json(['success' => true, 'message' => 'Test email sent to ' . $request->to]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'SMTP Error: ' . $e->getMessage()], 500);
        }
    }
}
