<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use App\Services\BrevoService;
use Illuminate\Http\Request;

class BrevoController extends Controller
{
    // ── Save Brevo Settings ───────────────────────────────────────
    public function save(Request $request)
    {
        $request->validate([
            'brevo_api_key'    => 'required|string',
            'brevo_from_email' => 'required|email',
            'brevo_from_name'  => 'required|string|max:100',
        ]);

        Setting::saveMany([
            'brevo_api_key'    => $request->brevo_api_key,
            'brevo_from_email' => $request->brevo_from_email,
            'brevo_from_name'  => $request->brevo_from_name,
            'brevo_list_id'    => $request->brevo_list_id ?? '',
            'email_provider'   => 'brevo',
        ], 'integrations');

        \App\Models\ActivityLog::log('updated', 'integrations', 'Brevo settings updated');

        return response()->json(['success' => true, 'message' => 'Brevo settings saved!']);
    }

    // ── Test Connection ───────────────────────────────────────────
    public function test(Request $request)
    {
        $request->validate(['to' => 'required|email']);

        try {
            $brevo  = new BrevoService();
            $result = $brevo->sendTestEmail($request->to);

            return response()->json([
                'success'    => true,
                'message'    => 'Test email sent to ' . $request->to,
                'message_id' => $result['message_id'] ?? null,
            ]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    // ── Get Account Info ──────────────────────────────────────────
    public function account()
    {
        try {
            $brevo = new BrevoService();
            $info  = $brevo->getAccountInfo();
            return response()->json(['success' => true, 'data' => $info]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    // ── Get Lists ─────────────────────────────────────────────────
    public function lists()
    {
        try {
            $brevo = new BrevoService();
            $lists = $brevo->getLists();
            return response()->json(['success' => true, 'data' => $lists]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    // ── Get Stats ─────────────────────────────────────────────────
    public function stats()
    {
        try {
            $brevo = new BrevoService();
            $stats = $brevo->getEmailStats();
            return response()->json(['success' => true, 'data' => $stats]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    // ── Email Templates CRUD ─────────────────────────────────────
    public function getTemplates(Request $request)
    {
        try {
            $brevo  = new BrevoService();
            $result = $brevo->getTemplates(50, (int)$request->get('offset', 0));
            return response()->json(['success' => true, 'data' => $result]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function getTemplate($id)
    {
        try {
            $brevo = new BrevoService();
            $tpl   = $brevo->getTemplate((int)$id);
            return response()->json(['success' => true, 'data' => $tpl]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function createTemplate(Request $request)
    {
        $request->validate([
            'name'         => 'required|string|max:255',
            'subject'      => 'required|string|max:255',
            'html_content' => 'required|string',
        ]);
        try {
            $brevo  = new BrevoService();
            $result = $brevo->createTemplate($request->all());
            return response()->json(['success' => true, 'message' => 'Template created!', 'data' => $result]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function updateTemplate(Request $request, $id)
    {
        try {
            $brevo = new BrevoService();
            $brevo->updateTemplate((int)$id, $request->all());
            return response()->json(['success' => true, 'message' => 'Template updated']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function deleteTemplate($id)
    {
        try {
            $brevo = new BrevoService();
            $brevo->deleteTemplate((int)$id);
            return response()->json(['success' => true, 'message' => 'Template deleted']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function sendTestTemplate(Request $request, $id)
    {
        $request->validate(['to' => 'required|email']);
        try {
            $brevo = new BrevoService();
            $brevo->sendTestTemplate((int)$id, $request->to);
            return response()->json(['success' => true, 'message' => 'Test email sent to ' . $request->to]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    // ── Senders CRUD ─────────────────────────────────────────────
    public function getSenders()
    {
        try {
            $brevo   = new BrevoService();
            $senders = $brevo->getSenders();
            return response()->json(['success' => true, 'data' => $senders]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function createSender(Request $request)
    {
        $request->validate(['name' => 'required|string|max:100', 'email' => 'required|email']);
        try {
            $brevo  = new BrevoService();
            $result = $brevo->createSender($request->name, $request->email);
            return response()->json([
                'success' => true,
                'message' => 'Sender created! Check your email to verify it.',
                'data'    => $result,
            ]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function updateSender(Request $request, $id)
    {
        $request->validate(['name' => 'required|string|max:100', 'email' => 'required|email']);
        try {
            $brevo = new BrevoService();
            $brevo->updateSender((int)$id, $request->name, $request->email);
            return response()->json(['success' => true, 'message' => 'Sender updated']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function deleteSender($id)
    {
        try {
            $brevo = new BrevoService();
            $brevo->deleteSender((int)$id);
            return response()->json(['success' => true, 'message' => 'Sender deleted']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function validateSenderOTP(Request $request, $id)
    {
        $request->validate(['otp' => 'required|string|min:4|max:8']);
        try {
            $brevo = new BrevoService();
            $brevo->validateSenderOTP((int)$id, $request->otp);
            return response()->json(['success' => true, 'message' => 'Sender verified successfully!']);
        } catch (\Exception $e) {
            $raw = $e->getMessage();
            // Extract actual Brevo error message from JSON response
            if (preg_match('/\{.*\}/s', $raw, $m)) {
                $body = json_decode($m[0], true);
                $msg  = $body['message'] ?? $raw;
            } else {
                $msg = $raw;
            }
            return response()->json(['success' => false, 'message' => 'Brevo: ' . $msg], 422);
        }
    }

    // ── Seed Templates ───────────────────────────────────────────
    public function seedTemplates()
    {
        try {
            $seeder = new \Database\Seeders\BrevoTemplateSeeder();

            // Capture output from the seeder's command->info() calls
            $log     = [];
            $success = 0;
            $failed  = 0;

            $brevo     = new BrevoService();
            $fromEmail = Setting::get('brevo_from_email', 'noreply@decathlon.com');
            $fromName  = Setting::get('brevo_from_name', 'Decathlon');

            // Reflect to call getTemplates (private), so we inline the logic here
            $reflection = new \ReflectionClass($seeder);
            $prop1 = $reflection->getProperty('fromEmail');
            $prop1->setAccessible(true);
            $prop1->setValue($seeder, $fromEmail);
            $prop2 = $reflection->getProperty('fromName');
            $prop2->setAccessible(true);
            $prop2->setValue($seeder, $fromName);

            $method = $reflection->getMethod('getTemplates');
            $method->setAccessible(true);
            $templates = $method->invoke($seeder);

            foreach ($templates as $tpl) {
                try {
                    $result = $brevo->createTemplate([
                        'name'         => $tpl['name'],
                        'subject'      => $tpl['subject'],
                        'html_content' => $tpl['html'],
                        'sender_name'  => $fromName,
                        'sender_email' => $fromEmail,
                        'reply_to'     => $fromEmail,
                        'is_active'    => true,
                    ]);
                    $log[] = ['status' => 'success', 'category' => $tpl['category'], 'name' => $tpl['name'], 'id' => $result['id']];
                    $success++;
                } catch (\Exception $e) {
                    $log[] = ['status' => 'failed', 'category' => $tpl['category'], 'name' => $tpl['name'], 'error' => $e->getMessage()];
                    $failed++;
                }
            }

            \App\Models\ActivityLog::log('created', 'brevo_templates', "Seeded {$success} Brevo templates ({$failed} failed)");

            return response()->json([
                'success' => true,
                'message' => "Seeded {$success} templates successfully, {$failed} failed.",
                'data'    => ['created' => $success, 'failed' => $failed, 'log' => $log],
            ]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    // ── Disconnect ────────────────────────────────────────────────
    public function disconnect()
    {
        $currentProvider = Setting::get('email_provider', '');

        Setting::saveMany([
            'brevo_api_key'    => '',
            'brevo_from_email' => '',
            'brevo_from_name'  => '',
            'brevo_list_id'    => '',
            'email_provider'   => $currentProvider === 'brevo' ? '' : $currentProvider,
        ], 'integrations');

        return response()->json(['success' => true, 'message' => 'Brevo disconnected']);
    }
}
