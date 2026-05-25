<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Services\BrevoService;
use App\Models\Setting;

class BrevoTemplateSeeder extends Seeder
{
    private string $fromEmail;
    private string $fromName;

    // ── Entry point ───────────────────────────────────────────────
    public function run(): void
    {
        $this->fromEmail = Setting::get('brevo_from_email', 'noreply@decathlon.com');
        $this->fromName  = Setting::get('brevo_from_name', 'Decathlon');

        $brevo     = new BrevoService();
        $templates = $this->getTemplates();
        $success   = 0;
        $failed    = 0;

        $this->command->info('');
        $this->command->info('=== Brevo Template Seeder ===');
        $this->command->info('Sender: ' . $this->fromName . ' <' . $this->fromEmail . '>');
        $this->command->info('Total templates to create: ' . count($templates));
        $this->command->info('');

        foreach ($templates as $tpl) {
            try {
                $result = $brevo->createTemplate([
                    'name'         => $tpl['name'],
                    'subject'      => $tpl['subject'],
                    'html_content' => $tpl['html'],
                    'sender_name'  => $this->fromName,
                    'sender_email' => $this->fromEmail,
                    'reply_to'     => $this->fromEmail,
                    'is_active'    => true,
                ]);
                $this->command->info("  ✓ [{$tpl['category']}] {$tpl['name']} — Brevo ID: {$result['id']}");
                $success++;
            } catch (\Exception $e) {
                $this->command->error("  ✗ [{$tpl['category']}] {$tpl['name']} — " . $e->getMessage());
                $failed++;
            }
        }

        $this->command->info('');
        $this->command->info("Done: {$success} created, {$failed} failed.");
    }

    // ── Master template list ──────────────────────────────────────
    private function getTemplates(): array
    {
        return array_merge(
            $this->orderTemplates(),
            $this->authTemplates(),
            $this->returnTemplates(),
            $this->marketingTemplates(),
            $this->adminTemplates()
        );
    }

    // ── Shared HTML layout ────────────────────────────────────────
    private function layout(string $preheader, string $bodyContent): string
    {
        return '<!DOCTYPE html><html lang="en"><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1.0"><title>Decathlon</title></head>'
            . '<body style="margin:0;padding:0;background-color:#f4f6f8;font-family:Arial,Helvetica,sans-serif;-webkit-font-smoothing:antialiased;">'
            . '<div style="display:none;max-height:0;overflow:hidden;mso-hide:all;">' . $preheader . '</div>'
            . '<table width="100%" cellpadding="0" cellspacing="0" border="0" style="background-color:#f4f6f8;padding:30px 0;">'
            . '<tr><td align="center">'
            . '<table width="600" cellpadding="0" cellspacing="0" border="0" style="max-width:600px;width:100%;">'
            . $this->header()
            . '<tr><td style="background:#ffffff;padding:40px 40px 30px 40px;border-left:1px solid #e0e4e8;border-right:1px solid #e0e4e8;">'
            . $bodyContent
            . '</td></tr>'
            . $this->footer()
            . '</table>'
            . '</td></tr></table>'
            . '</body></html>';
    }

    private function header(): string
    {
        return '<tr><td style="background-color:#0082C3;padding:24px 40px;border-radius:6px 6px 0 0;">'
            . '<table width="100%" cellpadding="0" cellspacing="0" border="0"><tr>'
            . '<td><span style="font-size:26px;font-weight:900;color:#ffffff;letter-spacing:-0.5px;text-decoration:none;">DECATHLON</span></td>'
            . '<td align="right"><span style="font-size:12px;color:rgba(255,255,255,0.75);letter-spacing:1px;text-transform:uppercase;">Sport for All</span></td>'
            . '</tr></table>'
            . '</td></tr>';
    }

    private function footer(): string
    {
        return '<tr><td style="background-color:#f4f6f8;padding:28px 40px;border-radius:0 0 6px 6px;border:1px solid #e0e4e8;border-top:none;">'
            . '<table width="100%" cellpadding="0" cellspacing="0" border="0"><tr>'
            . '<td style="font-size:12px;color:#888;line-height:1.6;">'
            . '<p style="margin:0 0 6px 0;">© ' . date('Y') . ' Decathlon. All rights reserved.</p>'
            . '<p style="margin:0 0 6px 0;">You received this email because you have an account with us.</p>'
            . '<p style="margin:0;"><a href="{{params.unsubscribe_url}}" style="color:#0082C3;text-decoration:none;">Unsubscribe</a>'
            . ' &nbsp;|&nbsp; <a href="{{params.privacy_url}}" style="color:#0082C3;text-decoration:none;">Privacy Policy</a>'
            . ' &nbsp;|&nbsp; <a href="{{params.help_url}}" style="color:#0082C3;text-decoration:none;">Help Center</a></p>'
            . '</td>'
            . '<td align="right" style="vertical-align:top;">'
            . '<a href="https://www.decathlon.in" style="font-size:11px;color:#0082C3;text-decoration:none;font-weight:bold;">decathlon.in</a>'
            . '</td>'
            . '</tr></table>'
            . '</td></tr>';
    }

    private function btn(string $label, string $url, string $color = '#0082C3'): string
    {
        return '<table cellpadding="0" cellspacing="0" border="0" style="margin:28px 0 8px 0;">'
            . '<tr><td style="background-color:' . $color . ';border-radius:4px;padding:0;">'
            . '<a href="' . $url . '" style="display:inline-block;padding:14px 32px;font-size:15px;font-weight:bold;color:#ffffff;text-decoration:none;letter-spacing:0.3px;">'
            . $label . '</a>'
            . '</td></tr></table>';
    }

    private function divider(): string
    {
        return '<hr style="border:none;border-top:1px solid #e8ecf0;margin:28px 0;">';
    }

    private function infoRow(string $label, string $value): string
    {
        return '<tr>'
            . '<td style="padding:10px 14px;font-size:13px;color:#666;background:#f9fafb;border-bottom:1px solid #eee;width:40%;">' . $label . '</td>'
            . '<td style="padding:10px 14px;font-size:13px;color:#1a1a1a;font-weight:600;background:#f9fafb;border-bottom:1px solid #eee;">' . $value . '</td>'
            . '</tr>';
    }

    private function infoTable(array $rows): string
    {
        $html = '<table width="100%" cellpadding="0" cellspacing="0" border="0" style="border:1px solid #e8ecf0;border-radius:4px;overflow:hidden;margin:20px 0;">';
        foreach ($rows as $label => $value) {
            $html .= $this->infoRow($label, $value);
        }
        return $html . '</table>';
    }

    private function otpBox(string $otpVar): string
    {
        return '<div style="background:#f0f7ff;border:2px dashed #0082C3;border-radius:8px;padding:24px;text-align:center;margin:28px 0;">'
            . '<p style="margin:0 0 8px 0;font-size:13px;color:#666;text-transform:uppercase;letter-spacing:1px;">Your One-Time Password</p>'
            . '<p style="margin:0;font-size:42px;font-weight:900;color:#0082C3;letter-spacing:12px;font-family:\'Courier New\',monospace;">' . $otpVar . '</p>'
            . '<p style="margin:10px 0 0 0;font-size:12px;color:#999;">Valid for 10 minutes. Do not share this code.</p>'
            . '</div>';
    }

    private function alertBox(string $text, string $type = 'info'): string
    {
        $colors = [
            'info'    => ['bg' => '#e8f4fd', 'border' => '#0082C3', 'text' => '#005f8e'],
            'success' => ['bg' => '#e8f8f0', 'border' => '#28a745', 'text' => '#1a6b30'],
            'warning' => ['bg' => '#fff8e1', 'border' => '#ffc107', 'text' => '#856404'],
            'danger'  => ['bg' => '#fdecea', 'border' => '#dc3545', 'text' => '#8b1a1a'],
        ];
        $c = $colors[$type] ?? $colors['info'];
        return '<div style="background:' . $c['bg'] . ';border-left:4px solid ' . $c['border'] . ';border-radius:0 4px 4px 0;padding:14px 18px;margin:20px 0;">'
            . '<p style="margin:0;font-size:14px;color:' . $c['text'] . ';">' . $text . '</p>'
            . '</div>';
    }

    // ══════════════════════════════════════════════════════════════
    // ORDER TEMPLATES (5)
    // ══════════════════════════════════════════════════════════════
    private function orderTemplates(): array
    {
        return [
            // 1. Order Confirmation
            [
                'category' => 'Orders',
                'name'     => 'Order Confirmation',
                'subject'  => 'Order Confirmed! #{{params.order_id}} — Thank you, {{params.name}}',
                'html'     => $this->layout(
                    'Your order #{{params.order_id}} has been confirmed.',
                    '<h1 style="margin:0 0 6px 0;font-size:24px;color:#1a1a1a;font-weight:800;">Order Confirmed! 🎉</h1>'
                    . '<p style="margin:0 0 20px 0;font-size:15px;color:#555;">Hi {{params.name}}, thank you for your purchase. We\'ve received your order and it\'s being processed.</p>'
                    . $this->infoTable([
                        'Order Number'   => '#{{params.order_id}}',
                        'Order Date'     => '{{params.order_date}}',
                        'Payment Method' => '{{params.payment_method}}',
                        'Order Total'    => '<strong style="color:#0082C3;">₹{{params.amount}}</strong>',
                    ])
                    . '<p style="font-size:14px;color:#555;margin:20px 0 6px 0;font-weight:600;">Delivery Address</p>'
                    . '<p style="font-size:14px;color:#666;margin:0;line-height:1.7;">{{params.delivery_address}}</p>'
                    . $this->divider()
                    . '<p style="font-size:14px;color:#555;margin:0 0 16px 0;">We\'ll send you another email once your order ships. Expected delivery: <strong>{{params.estimated_delivery}}</strong></p>'
                    . $this->btn('View My Order', '{{params.order_url}}')
                    . $this->alertBox('Need help? Reply to this email or visit our <a href="{{params.help_url}}" style="color:#005f8e;">Help Center</a>.', 'info')
                ),
            ],

            // 2. Order Shipped
            [
                'category' => 'Orders',
                'name'     => 'Order Shipped',
                'subject'  => 'Your order #{{params.order_id}} is on its way! 🚚',
                'html'     => $this->layout(
                    'Your order has been shipped and is on its way.',
                    '<h1 style="margin:0 0 6px 0;font-size:24px;color:#1a1a1a;font-weight:800;">Your Order is Shipped! 🚚</h1>'
                    . '<p style="margin:0 0 20px 0;font-size:15px;color:#555;">Hi {{params.name}}, great news — your order is on its way to you!</p>'
                    . $this->infoTable([
                        'Order Number'       => '#{{params.order_id}}',
                        'Courier Partner'    => '{{params.courier_name}}',
                        'Tracking Number'    => '<strong>{{params.tracking_number}}</strong>',
                        'Estimated Delivery' => '{{params.estimated_delivery}}',
                    ])
                    . $this->btn('Track My Order', '{{params.tracking_url}}')
                    . $this->divider()
                    . '<p style="font-size:13px;color:#888;margin:0;">Tracking may take up to 24 hours to update after dispatch. If you have any issues, contact our support team.</p>'
                ),
            ],

            // 3. Order Delivered
            [
                'category' => 'Orders',
                'name'     => 'Order Delivered',
                'subject'  => 'Your order #{{params.order_id}} has been delivered ✅',
                'html'     => $this->layout(
                    'Your order has been delivered. We hope you love it!',
                    '<h1 style="margin:0 0 6px 0;font-size:24px;color:#1a1a1a;font-weight:800;">Order Delivered! ✅</h1>'
                    . '<p style="margin:0 0 20px 0;font-size:15px;color:#555;">Hi {{params.name}}, your order #<strong>{{params.order_id}}</strong> has been delivered. We hope you love your new gear!</p>'
                    . $this->alertBox('Delivered on <strong>{{params.delivered_date}}</strong> to {{params.delivery_address}}', 'success')
                    . '<p style="font-size:15px;color:#333;margin:24px 0 8px 0;font-weight:700;">How was your experience?</p>'
                    . '<p style="font-size:14px;color:#555;margin:0 0 16px 0;">Your feedback helps us improve. Take 30 seconds to rate your order.</p>'
                    . $this->btn('Rate My Order', '{{params.review_url}}')
                    . $this->divider()
                    . '<p style="font-size:13px;color:#888;margin:0;">Not satisfied? You can initiate a return within 30 days. <a href="{{params.return_url}}" style="color:#0082C3;">Start a return</a></p>'
                ),
            ],

            // 4. Order Cancelled
            [
                'category' => 'Orders',
                'name'     => 'Order Cancelled',
                'subject'  => 'Your order #{{params.order_id}} has been cancelled',
                'html'     => $this->layout(
                    'Your order has been cancelled. Refund details inside.',
                    '<h1 style="margin:0 0 6px 0;font-size:24px;color:#1a1a1a;font-weight:800;">Order Cancelled</h1>'
                    . '<p style="margin:0 0 20px 0;font-size:15px;color:#555;">Hi {{params.name}}, your order #<strong>{{params.order_id}}</strong> has been cancelled as requested.</p>'
                    . $this->alertBox('Cancellation reason: <strong>{{params.cancel_reason}}</strong>', 'warning')
                    . $this->infoTable([
                        'Order Number'    => '#{{params.order_id}}',
                        'Cancelled On'    => '{{params.cancel_date}}',
                        'Refund Amount'   => '₹{{params.refund_amount}}',
                        'Refund Method'   => '{{params.refund_method}}',
                        'Refund Timeline' => '5–7 business days',
                    ])
                    . '<p style="font-size:14px;color:#555;margin:20px 0 16px 0;">Your refund of <strong>₹{{params.refund_amount}}</strong> will be credited to your {{params.refund_method}} within 5–7 business days.</p>'
                    . $this->btn('Continue Shopping', '{{params.shop_url}}')
                    . $this->divider()
                    . '<p style="font-size:13px;color:#888;margin:0;">Questions about your cancellation? <a href="{{params.help_url}}" style="color:#0082C3;">Contact Support</a></p>'
                ),
            ],

            // 5. Invoice Email
            [
                'category' => 'Orders',
                'name'     => 'Invoice Email',
                'subject'  => 'Invoice #{{params.invoice_number}} for Order #{{params.order_id}}',
                'html'     => $this->layout(
                    'Your invoice for order #{{params.order_id}} is attached.',
                    '<h1 style="margin:0 0 6px 0;font-size:24px;color:#1a1a1a;font-weight:800;">Your Invoice</h1>'
                    . '<p style="margin:0 0 20px 0;font-size:15px;color:#555;">Hi {{params.name}}, please find your invoice details below for order #<strong>{{params.order_id}}</strong>.</p>'
                    . $this->infoTable([
                        'Invoice Number' => '#{{params.invoice_number}}',
                        'Invoice Date'   => '{{params.invoice_date}}',
                        'Order Number'   => '#{{params.order_id}}',
                        'Subtotal'       => '₹{{params.subtotal}}',
                        'Discount'       => '₹{{params.discount}}',
                        'Tax (GST)'      => '₹{{params.tax}}',
                        'Total Amount'   => '<strong style="color:#0082C3;font-size:15px;">₹{{params.amount}}</strong>',
                    ])
                    . '<p style="font-size:14px;color:#555;margin:20px 0 6px 0;font-weight:600;">Billing Address</p>'
                    . '<p style="font-size:14px;color:#666;margin:0 0 20px 0;line-height:1.7;">{{params.billing_address}}</p>'
                    . $this->btn('Download Invoice PDF', '{{params.invoice_url}}')
                    . $this->alertBox('This is a computer-generated invoice. No signature required.', 'info')
                ),
            ],
        ];
    }

    // ══════════════════════════════════════════════════════════════
    // AUTH TEMPLATES (5)
    // ══════════════════════════════════════════════════════════════
    private function authTemplates(): array
    {
        return [
            // 1. Welcome Email
            [
                'category' => 'Auth',
                'name'     => 'Welcome Email',
                'subject'  => 'Welcome to Decathlon, {{params.name}}! 🎽',
                'html'     => $this->layout(
                    'Welcome! Your account is ready. Here\'s your exclusive welcome offer.',
                    '<h1 style="margin:0 0 6px 0;font-size:24px;color:#1a1a1a;font-weight:800;">Welcome to Decathlon! 🎽</h1>'
                    . '<p style="margin:0 0 20px 0;font-size:15px;color:#555;">Hi {{params.name}}, your account has been created successfully. We\'re thrilled to have you join the Decathlon community!</p>'
                    . '<div style="background:linear-gradient(135deg,#0082C3 0%,#005f8e 100%);border-radius:8px;padding:28px;text-align:center;margin:24px 0;">'
                    . '<p style="margin:0 0 6px 0;font-size:13px;color:rgba(255,255,255,0.8);text-transform:uppercase;letter-spacing:1px;">Your Welcome Gift</p>'
                    . '<p style="margin:0 0 4px 0;font-size:36px;font-weight:900;color:#ffffff;">10% OFF</p>'
                    . '<p style="margin:0 0 16px 0;font-size:13px;color:rgba(255,255,255,0.9);">on your first order</p>'
                    . '<div style="background:#ffffff;border-radius:4px;padding:10px 20px;display:inline-block;">'
                    . '<span style="font-size:20px;font-weight:900;color:#0082C3;letter-spacing:3px;">WELCOME10</span>'
                    . '</div>'
                    . '</div>'
                    . '<p style="font-size:14px;color:#555;margin:20px 0 8px 0;font-weight:700;">What you can do with your account:</p>'
                    . '<ul style="margin:0 0 20px 0;padding-left:20px;color:#555;font-size:14px;line-height:2;">'
                    . '<li>Track your orders in real time</li>'
                    . '<li>Save your favourite products</li>'
                    . '<li>Earn loyalty points on every purchase</li>'
                    . '<li>Get exclusive member-only deals</li>'
                    . '</ul>'
                    . $this->btn('Start Shopping', '{{params.shop_url}}')
                ),
            ],

            // 2. Password Reset OTP
            [
                'category' => 'Auth',
                'name'     => 'Password Reset OTP',
                'subject'  => 'Reset your Decathlon password — OTP inside',
                'html'     => $this->layout(
                    'Use the OTP below to reset your password. Valid for 10 minutes.',
                    '<h1 style="margin:0 0 6px 0;font-size:24px;color:#1a1a1a;font-weight:800;">Reset Your Password</h1>'
                    . '<p style="margin:0 0 20px 0;font-size:15px;color:#555;">Hi {{params.name}}, we received a request to reset the password for your Decathlon account.</p>'
                    . $this->otpBox('{{params.otp}}')
                    . '<p style="font-size:14px;color:#555;margin:20px 0;">Enter this OTP on the password reset page to create a new password. This code expires in <strong>10 minutes</strong>.</p>'
                    . $this->alertBox('If you did not request a password reset, please ignore this email. Your account is safe.', 'warning')
                    . $this->divider()
                    . '<p style="font-size:13px;color:#888;margin:0;">For security, never share this OTP with anyone. Decathlon staff will never ask for your OTP.</p>'
                ),
            ],

            // 3. Login OTP
            [
                'category' => 'Auth',
                'name'     => 'Login OTP',
                'subject'  => '{{params.otp}} is your Decathlon login code',
                'html'     => $this->layout(
                    'Your login verification code is inside. Valid for 10 minutes.',
                    '<h1 style="margin:0 0 6px 0;font-size:24px;color:#1a1a1a;font-weight:800;">Login Verification Code</h1>'
                    . '<p style="margin:0 0 20px 0;font-size:15px;color:#555;">Hi {{params.name}}, use the code below to complete your sign-in to Decathlon.</p>'
                    . $this->otpBox('{{params.otp}}')
                    . $this->infoTable([
                        'Login Time'     => '{{params.login_time}}',
                        'Device / Browser' => '{{params.device}}',
                        'IP Address'     => '{{params.ip_address}}',
                    ])
                    . $this->alertBox('Didn\'t try to log in? <a href="{{params.secure_url}}" style="color:#8b1a1a;font-weight:bold;">Secure your account immediately</a>.', 'danger')
                ),
            ],

            // 4. Email Verification
            [
                'category' => 'Auth',
                'name'     => 'Email Verification',
                'subject'  => 'Verify your email address — Decathlon',
                'html'     => $this->layout(
                    'Please verify your email address to activate your account.',
                    '<h1 style="margin:0 0 6px 0;font-size:24px;color:#1a1a1a;font-weight:800;">Verify Your Email</h1>'
                    . '<p style="margin:0 0 20px 0;font-size:15px;color:#555;">Hi {{params.name}}, thanks for signing up! Please verify your email address to activate your Decathlon account.</p>'
                    . '<div style="text-align:center;margin:32px 0;">'
                    . '<div style="width:72px;height:72px;background:#e8f4fd;border-radius:50%;margin:0 auto 16px auto;display:flex;align-items:center;justify-content:center;">'
                    . '<span style="font-size:36px;">✉️</span>'
                    . '</div>'
                    . '<p style="font-size:14px;color:#555;margin:0 0 20px 0;">Click the button below to verify your email address.</p>'
                    . '</div>'
                    . $this->btn('Verify Email Address', '{{params.verify_url}}')
                    . $this->divider()
                    . '<p style="font-size:13px;color:#888;margin:0;">This link expires in <strong>24 hours</strong>. If you didn\'t create an account, you can safely ignore this email.</p>'
                    . '<p style="font-size:12px;color:#aaa;margin:10px 0 0 0;word-break:break-all;">Or copy this link: {{params.verify_url}}</p>'
                ),
            ],

            // 5. Account Deactivated
            [
                'category' => 'Auth',
                'name'     => 'Account Deactivated',
                'subject'  => 'Your Decathlon account has been deactivated',
                'html'     => $this->layout(
                    'Your account has been deactivated. Contact support to reactivate.',
                    '<h1 style="margin:0 0 6px 0;font-size:24px;color:#1a1a1a;font-weight:800;">Account Deactivated</h1>'
                    . '<p style="margin:0 0 20px 0;font-size:15px;color:#555;">Hi {{params.name}}, your Decathlon account has been deactivated.</p>'
                    . $this->alertBox('Your account was deactivated on <strong>{{params.deactivated_date}}</strong>. Reason: {{params.reason}}', 'danger')
                    . '<p style="font-size:14px;color:#555;margin:20px 0;">While your account is deactivated:</p>'
                    . '<ul style="margin:0 0 20px 0;padding-left:20px;color:#555;font-size:14px;line-height:2;">'
                    . '<li>You cannot log in to your account</li>'
                    . '<li>Your saved data and order history are preserved</li>'
                    . '<li>Active subscriptions have been paused</li>'
                    . '</ul>'
                    . '<p style="font-size:14px;color:#555;margin:0 0 20px 0;">If you believe this is a mistake or wish to reactivate your account, please contact our support team.</p>'
                    . $this->btn('Contact Support', '{{params.support_url}}', '#dc3545')
                    . $this->divider()
                    . '<p style="font-size:13px;color:#888;margin:0;">Account ID: {{params.account_id}} &nbsp;|&nbsp; Deactivated: {{params.deactivated_date}}</p>'
                ),
            ],
        ];
    }

    // ══════════════════════════════════════════════════════════════
    // RETURN TEMPLATES (3)
    // ══════════════════════════════════════════════════════════════
    private function returnTemplates(): array
    {
        return [
            // 1. Return Request Received
            [
                'category' => 'Returns',
                'name'     => 'Return Request Received',
                'subject'  => 'Return request received for Order #{{params.order_id}}',
                'html'     => $this->layout(
                    'We\'ve received your return request. Here\'s what happens next.',
                    '<h1 style="margin:0 0 6px 0;font-size:24px;color:#1a1a1a;font-weight:800;">Return Request Received</h1>'
                    . '<p style="margin:0 0 20px 0;font-size:15px;color:#555;">Hi {{params.name}}, we\'ve received your return request and our team is reviewing it.</p>'
                    . $this->infoTable([
                        'Return ID'      => '#{{params.return_id}}',
                        'Order Number'   => '#{{params.order_id}}',
                        'Return Reason'  => '{{params.return_reason}}',
                        'Items'          => '{{params.return_items}}',
                        'Refund Amount'  => '₹{{params.refund_amount}}',
                        'Status'         => '<span style="color:#856404;font-weight:600;">Under Review</span>',
                    ])
                    . '<p style="font-size:14px;color:#555;font-weight:700;margin:24px 0 10px 0;">What happens next?</p>'
                    . '<ol style="margin:0 0 20px 0;padding-left:20px;color:#555;font-size:14px;line-height:2.2;">'
                    . '<li>Our team reviews your request (1–2 business days)</li>'
                    . '<li>You\'ll receive a pickup confirmation email</li>'
                    . '<li>Our courier will collect the item from your address</li>'
                    . '<li>Refund is processed within 5–7 business days after pickup</li>'
                    . '</ol>'
                    . $this->btn('Track Return Status', '{{params.return_url}}')
                ),
            ],

            // 2. Return Approved
            [
                'category' => 'Returns',
                'name'     => 'Return Approved',
                'subject'  => 'Return #{{params.return_id}} approved — Pickup scheduled',
                'html'     => $this->layout(
                    'Your return has been approved. Pickup is scheduled.',
                    '<h1 style="margin:0 0 6px 0;font-size:24px;color:#1a1a1a;font-weight:800;">Return Approved ✅</h1>'
                    . '<p style="margin:0 0 20px 0;font-size:15px;color:#555;">Hi {{params.name}}, your return request has been approved. Please keep the item ready for pickup.</p>'
                    . $this->alertBox('Pickup scheduled for <strong>{{params.pickup_date}}</strong> between {{params.pickup_slot}}.', 'success')
                    . $this->infoTable([
                        'Return ID'      => '#{{params.return_id}}',
                        'Pickup Date'    => '{{params.pickup_date}}',
                        'Pickup Slot'    => '{{params.pickup_slot}}',
                        'Courier'        => '{{params.courier_name}}',
                        'Refund Amount'  => '₹{{params.refund_amount}}',
                        'Refund Method'  => '{{params.refund_method}}',
                    ])
                    . '<p style="font-size:14px;color:#555;font-weight:700;margin:24px 0 10px 0;">Packaging Instructions</p>'
                    . '<ul style="margin:0 0 20px 0;padding-left:20px;color:#555;font-size:14px;line-height:2;">'
                    . '<li>Pack the item securely in its original packaging if possible</li>'
                    . '<li>Include all accessories, tags, and original invoice</li>'
                    . '<li>Keep the return slip handy for the courier</li>'
                    . '</ul>'
                    . $this->btn('View Return Details', '{{params.return_url}}')
                ),
            ],

            // 3. Refund Processed
            [
                'category' => 'Returns',
                'name'     => 'Refund Processed',
                'subject'  => 'Refund of ₹{{params.refund_amount}} processed for Order #{{params.order_id}}',
                'html'     => $this->layout(
                    'Your refund has been processed. It will reflect in 5–7 business days.',
                    '<h1 style="margin:0 0 6px 0;font-size:24px;color:#1a1a1a;font-weight:800;">Refund Processed 💰</h1>'
                    . '<p style="margin:0 0 20px 0;font-size:15px;color:#555;">Hi {{params.name}}, your refund has been successfully processed. Here are the details:</p>'
                    . '<div style="background:#e8f8f0;border:1px solid #c3e6cb;border-radius:8px;padding:24px;text-align:center;margin:24px 0;">'
                    . '<p style="margin:0 0 4px 0;font-size:13px;color:#1a6b30;text-transform:uppercase;letter-spacing:1px;">Refund Amount</p>'
                    . '<p style="margin:0;font-size:40px;font-weight:900;color:#1a6b30;">₹{{params.refund_amount}}</p>'
                    . '</div>'
                    . $this->infoTable([
                        'Return ID'          => '#{{params.return_id}}',
                        'Order Number'       => '#{{params.order_id}}',
                        'Refund Date'        => '{{params.refund_date}}',
                        'Refund Method'      => '{{params.refund_method}}',
                        'Transaction ID'     => '{{params.transaction_id}}',
                        'Expected Credit By' => '{{params.credit_by_date}}',
                    ])
                    . $this->alertBox('Refunds to bank accounts typically take 5–7 business days. UPI/Wallet refunds are usually instant.', 'info')
                    . $this->btn('Continue Shopping', '{{params.shop_url}}')
                ),
            ],
        ];
    }

    // ══════════════════════════════════════════════════════════════
    // MARKETING TEMPLATES (3)
    // ══════════════════════════════════════════════════════════════
    private function marketingTemplates(): array
    {
        return [
            // 1. Newsletter Welcome
            [
                'category' => 'Marketing',
                'name'     => 'Newsletter Welcome',
                'subject'  => 'You\'re in! Welcome to the Decathlon newsletter 🏅',
                'html'     => $this->layout(
                    'Welcome to the Decathlon newsletter. Exclusive deals and sports tips await!',
                    '<h1 style="margin:0 0 6px 0;font-size:24px;color:#1a1a1a;font-weight:800;">You\'re In! 🏅</h1>'
                    . '<p style="margin:0 0 20px 0;font-size:15px;color:#555;">Hi {{params.name}}, welcome to the Decathlon newsletter! You\'ve just joined thousands of sports enthusiasts who get the best deals first.</p>'
                    . '<div style="background:#f0f7ff;border-radius:8px;padding:24px;margin:24px 0;">'
                    . '<p style="margin:0 0 14px 0;font-size:15px;font-weight:700;color:#1a1a1a;">What to expect in your inbox:</p>'
                    . '<table width="100%" cellpadding="0" cellspacing="0" border="0">'
                    . '<tr><td style="padding:8px 0;font-size:14px;color:#555;"><span style="color:#0082C3;font-weight:bold;margin-right:8px;">🏷️</span> Exclusive subscriber-only discounts</td></tr>'
                    . '<tr><td style="padding:8px 0;font-size:14px;color:#555;"><span style="color:#0082C3;font-weight:bold;margin-right:8px;">🆕</span> New product launches before anyone else</td></tr>'
                    . '<tr><td style="padding:8px 0;font-size:14px;color:#555;"><span style="color:#0082C3;font-weight:bold;margin-right:8px;">💡</span> Training tips from professional athletes</td></tr>'
                    . '<tr><td style="padding:8px 0;font-size:14px;color:#555;"><span style="color:#0082C3;font-weight:bold;margin-right:8px;">⚡</span> Flash sale alerts before they go public</td></tr>'
                    . '</table>'
                    . '</div>'
                    . '<p style="font-size:14px;color:#555;margin:0 0 20px 0;">As a welcome gift, here\'s <strong>₹200 off</strong> your next order over ₹1,500:</p>'
                    . '<div style="background:#0082C3;border-radius:4px;padding:14px;text-align:center;margin:0 0 24px 0;">'
                    . '<span style="font-size:22px;font-weight:900;color:#ffffff;letter-spacing:4px;">NEWSLETTER200</span>'
                    . '</div>'
                    . $this->btn('Shop Now', '{{params.shop_url}}')
                ),
            ],

            // 2. Flash Sale Alert
            [
                'category' => 'Marketing',
                'name'     => 'Flash Sale Alert',
                'subject'  => '⚡ FLASH SALE — {{params.discount}}% off ends in {{params.hours_left}} hours!',
                'html'     => $this->layout(
                    'Flash sale is LIVE! Up to {{params.discount}}% off. Hurry — limited time only.',
                    '<div style="background:#dc3545;border-radius:6px;padding:20px;text-align:center;margin:0 0 28px 0;">'
                    . '<p style="margin:0 0 4px 0;font-size:13px;color:rgba(255,255,255,0.85);text-transform:uppercase;letter-spacing:2px;font-weight:600;">Limited Time Offer</p>'
                    . '<p style="margin:0 0 4px 0;font-size:38px;font-weight:900;color:#ffffff;">⚡ FLASH SALE</p>'
                    . '<p style="margin:0;font-size:16px;color:rgba(255,255,255,0.9);">Ends in <strong>{{params.hours_left}} hours</strong></p>'
                    . '</div>'
                    . '<h1 style="margin:0 0 6px 0;font-size:24px;color:#1a1a1a;font-weight:800;">Up to {{params.discount}}% Off!</h1>'
                    . '<p style="margin:0 0 20px 0;font-size:15px;color:#555;">Hi {{params.name}}, our flash sale is live right now. Grab your favourite sports gear before it\'s gone!</p>'
                    . '<table width="100%" cellpadding="0" cellspacing="0" border="0" style="margin:20px 0;">'
                    . '<tr>'
                    . '<td style="width:48%;background:#f0f7ff;border-radius:6px;padding:18px;text-align:center;vertical-align:top;">'
                    . '<p style="margin:0 0 4px 0;font-size:28px;font-weight:900;color:#0082C3;">{{params.discount}}%</p>'
                    . '<p style="margin:0;font-size:13px;color:#555;">Max Discount</p>'
                    . '</td>'
                    . '<td style="width:4%;"></td>'
                    . '<td style="width:48%;background:#fff8e1;border-radius:6px;padding:18px;text-align:center;vertical-align:top;">'
                    . '<p style="margin:0 0 4px 0;font-size:28px;font-weight:900;color:#856404;">{{params.hours_left}}h</p>'
                    . '<p style="margin:0;font-size:13px;color:#555;">Time Left</p>'
                    . '</td>'
                    . '</tr>'
                    . '</table>'
                    . '<p style="font-size:14px;color:#555;margin:20px 0;">Featured categories: <strong>{{params.categories}}</strong></p>'
                    . $this->btn('Shop the Flash Sale →', '{{params.sale_url}}', '#dc3545')
                    . $this->alertBox('Use code <strong>{{params.coupon_code}}</strong> at checkout for an extra {{params.extra_discount}}% off.', 'warning')
                    . '<p style="font-size:12px;color:#aaa;margin:16px 0 0 0;text-align:center;">*Offer valid till {{params.sale_end_time}}. While stocks last. T&C apply.</p>'
                ),
            ],

            // 3. Back in Stock
            [
                'category' => 'Marketing',
                'name'     => 'Back in Stock',
                'subject'  => '🔔 {{params.product_name}} is back in stock!',
                'html'     => $this->layout(
                    '{{params.product_name}} is back! Grab it before it sells out again.',
                    '<h1 style="margin:0 0 6px 0;font-size:24px;color:#1a1a1a;font-weight:800;">It\'s Back! 🔔</h1>'
                    . '<p style="margin:0 0 20px 0;font-size:15px;color:#555;">Hi {{params.name}}, great news — the item you were waiting for is back in stock!</p>'
                    . '<div style="border:2px solid #0082C3;border-radius:8px;padding:24px;margin:24px 0;">'
                    . '<table width="100%" cellpadding="0" cellspacing="0" border="0"><tr>'
                    . '<td style="width:100px;vertical-align:top;">'
                    . '<img src="{{params.product_image}}" alt="{{params.product_name}}" width="90" style="border-radius:4px;display:block;">'
                    . '</td>'
                    . '<td style="padding-left:16px;vertical-align:top;">'
                    . '<p style="margin:0 0 4px 0;font-size:16px;font-weight:800;color:#1a1a1a;">{{params.product_name}}</p>'
                    . '<p style="margin:0 0 8px 0;font-size:13px;color:#888;">{{params.product_sku}} &nbsp;|&nbsp; {{params.product_variant}}</p>'
                    . '<p style="margin:0 0 4px 0;font-size:20px;font-weight:900;color:#0082C3;">₹{{params.product_price}}</p>'
                    . '<p style="margin:0;font-size:12px;color:#28a745;font-weight:600;">✓ In Stock — {{params.stock_count}} units available</p>'
                    . '</td>'
                    . '</tr></table>'
                    . '</div>'
                    . $this->alertBox('This item sells out fast. We can\'t guarantee availability — order now to secure yours!', 'warning')
                    . $this->btn('Buy Now Before It Sells Out', '{{params.product_url}}')
                    . $this->divider()
                    . '<p style="font-size:13px;color:#888;margin:0;">You received this because you requested a back-in-stock notification. <a href="{{params.unsubscribe_url}}" style="color:#0082C3;">Manage notifications</a></p>'
                ),
            ],
        ];
    }

    // ══════════════════════════════════════════════════════════════
    // ADMIN TEMPLATES (3)
    // ══════════════════════════════════════════════════════════════
    private function adminTemplates(): array
    {
        return [
            // 1. New Order Alert (Admin)
            [
                'category' => 'Admin',
                'name'     => 'Admin — New Order Alert',
                'subject'  => '[Admin] New Order #{{params.order_id}} — ₹{{params.amount}}',
                'html'     => $this->layout(
                    'New order received: #{{params.order_id}} for ₹{{params.amount}}.',
                    '<div style="background:#0082C3;border-radius:6px;padding:16px 20px;margin:0 0 24px 0;">'
                    . '<p style="margin:0;font-size:16px;font-weight:700;color:#ffffff;">🛒 New Order Received</p>'
                    . '</div>'
                    . '<p style="margin:0 0 20px 0;font-size:15px;color:#555;">A new order has been placed on your store. Review and process it promptly.</p>'
                    . $this->infoTable([
                        'Order Number'   => '<strong>#{{params.order_id}}</strong>',
                        'Customer Name'  => '{{params.customer_name}}',
                        'Customer Email' => '{{params.customer_email}}',
                        'Customer Phone' => '{{params.customer_phone}}',
                        'Order Total'    => '<strong style="color:#0082C3;font-size:15px;">₹{{params.amount}}</strong>',
                        'Payment Method' => '{{params.payment_method}}',
                        'Payment Status' => '{{params.payment_status}}',
                        'Order Date'     => '{{params.order_date}}',
                        'Items Count'    => '{{params.items_count}} item(s)',
                    ])
                    . '<p style="font-size:14px;color:#555;font-weight:700;margin:24px 0 10px 0;">Delivery Address</p>'
                    . '<p style="font-size:14px;color:#666;margin:0 0 24px 0;line-height:1.7;background:#f9fafb;padding:12px;border-radius:4px;">{{params.delivery_address}}</p>'
                    . $this->btn('View Order in Admin Panel', '{{params.admin_order_url}}')
                    . $this->alertBox('This is an automated notification sent to admin. Do not reply to this email.', 'info')
                ),
            ],

            // 2. Low Stock Alert (Admin)
            [
                'category' => 'Admin',
                'name'     => 'Admin — Low Stock Alert',
                'subject'  => '[Admin] ⚠️ Low Stock Alert — {{params.product_count}} product(s) need attention',
                'html'     => $this->layout(
                    'Low stock alert: {{params.product_count}} products are running low.',
                    '<div style="background:#ffc107;border-radius:6px;padding:16px 20px;margin:0 0 24px 0;">'
                    . '<p style="margin:0;font-size:16px;font-weight:700;color:#1a1a1a;">⚠️ Low Stock Alert</p>'
                    . '</div>'
                    . '<p style="margin:0 0 20px 0;font-size:15px;color:#555;">The following products have fallen below the minimum stock threshold and require immediate restocking.</p>'
                    . '<table width="100%" cellpadding="0" cellspacing="0" border="0" style="border:1px solid #e8ecf0;border-radius:4px;overflow:hidden;margin:20px 0;">'
                    . '<tr style="background:#0082C3;">'
                    . '<th style="padding:12px 14px;font-size:12px;color:#fff;text-align:left;font-weight:600;text-transform:uppercase;letter-spacing:0.5px;">Product</th>'
                    . '<th style="padding:12px 14px;font-size:12px;color:#fff;text-align:left;font-weight:600;text-transform:uppercase;letter-spacing:0.5px;">SKU</th>'
                    . '<th style="padding:12px 14px;font-size:12px;color:#fff;text-align:center;font-weight:600;text-transform:uppercase;letter-spacing:0.5px;">Stock</th>'
                    . '<th style="padding:12px 14px;font-size:12px;color:#fff;text-align:center;font-weight:600;text-transform:uppercase;letter-spacing:0.5px;">Min. Level</th>'
                    . '</tr>'
                    . '<tr style="background:#fff8e1;">'
                    . '<td style="padding:12px 14px;font-size:13px;color:#1a1a1a;">{{params.product_name}}</td>'
                    . '<td style="padding:12px 14px;font-size:13px;color:#666;">{{params.product_sku}}</td>'
                    . '<td style="padding:12px 14px;font-size:13px;color:#dc3545;font-weight:700;text-align:center;">{{params.current_stock}}</td>'
                    . '<td style="padding:12px 14px;font-size:13px;color:#856404;text-align:center;">{{params.min_stock}}</td>'
                    . '</tr>'
                    . '</table>'
                    . '<p style="font-size:14px;color:#555;margin:20px 0;">Total products with low stock: <strong>{{params.product_count}}</strong></p>'
                    . $this->btn('Manage Stock in Admin Panel', '{{params.admin_stock_url}}', '#856404')
                    . $this->alertBox('This is an automated stock alert. Configure thresholds in Admin → Stock Management → Settings.', 'warning')
                ),
            ],

            // 3. New Customer Registered (Admin)
            [
                'category' => 'Admin',
                'name'     => 'Admin — New Customer Registered',
                'subject'  => '[Admin] New Customer Registered — {{params.customer_name}}',
                'html'     => $this->layout(
                    'A new customer has registered on your store.',
                    '<div style="background:#28a745;border-radius:6px;padding:16px 20px;margin:0 0 24px 0;">'
                    . '<p style="margin:0;font-size:16px;font-weight:700;color:#ffffff;">👤 New Customer Registered</p>'
                    . '</div>'
                    . '<p style="margin:0 0 20px 0;font-size:15px;color:#555;">A new customer has created an account on your Decathlon store.</p>'
                    . $this->infoTable([
                        'Customer Name'    => '<strong>{{params.customer_name}}</strong>',
                        'Email Address'    => '{{params.customer_email}}',
                        'Phone Number'     => '{{params.customer_phone}}',
                        'Registration Date'=> '{{params.registered_at}}',
                        'Registration IP'  => '{{params.ip_address}}',
                        'Source'           => '{{params.registration_source}}',
                        'Email Verified'   => '{{params.email_verified}}',
                    ])
                    . $this->btn('View Customer Profile', '{{params.admin_customer_url}}')
                    . $this->alertBox('This is an automated notification. Customer data is stored securely in your admin panel.', 'info')
                ),
            ],
        ];
    }
} // end class
