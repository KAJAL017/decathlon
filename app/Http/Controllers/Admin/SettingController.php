<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    private array $groups = [
        'general','store','payment','shipping','tax','notifications','seo','advanced','integrations','localization'
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
            'store'         => ['store_maintenance_mode','store_guest_checkout','store_reviews_enabled','store_wishlist_enabled','store_compare_enabled'],
            'payment'       => ['payment_cod_enabled','payment_online_enabled','payment_wallet_enabled'],
            'shipping'      => ['shipping_free_enabled','shipping_express_enabled'],
            'tax'           => ['tax_inclusive','tax_enabled'],
            'notifications' => ['notif_new_order','notif_low_stock','notif_new_review','notif_new_customer'],
            'seo'           => ['seo_sitemap_enabled','seo_robots_noindex'],
            'advanced'      => ['advanced_debug_mode','advanced_cache_enabled'],
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
}
