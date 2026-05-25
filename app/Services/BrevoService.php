<?php

namespace App\Services;

use App\Models\Setting;
use Brevo\Client\Configuration;
use Brevo\Client\Api\TransactionalEmailsApi;
use Brevo\Client\Api\ContactsApi;
use Brevo\Client\Api\AccountApi;
use Brevo\Client\Api\SendersApi;
use Brevo\Client\Model\SendSmtpEmail;
use Brevo\Client\Model\SendSmtpEmailTo;
use Brevo\Client\Model\SendSmtpEmailSender;
use Brevo\Client\Model\CreateContact;
use Brevo\Client\Model\CreateSender;
use Brevo\Client\Model\UpdateSender;
use GuzzleHttp\Client;

class BrevoService
{
    protected ?Configuration $config = null;
    protected string $apiKey;
    protected string $fromEmail;
    protected string $fromName;

    public function __construct()
    {
        $this->apiKey    = Setting::get('brevo_api_key', '');
        $this->fromEmail = Setting::get('brevo_from_email', Setting::get('smtp_from_email', ''));
        $this->fromName  = Setting::get('brevo_from_name', Setting::get('smtp_from_name', 'Decathlon'));

        if ($this->apiKey) {
            $this->config = Configuration::getDefaultConfiguration()
                ->setApiKey('api-key', $this->apiKey);
        }
    }

    protected function isConfigured(): bool
    {
        return !empty($this->apiKey) && $this->config !== null;
    }

    // ── Account Info ──────────────────────────────────────────────
    public function getAccountInfo(): array
    {
        if (!$this->isConfigured()) throw new \Exception('Brevo API key not configured');

        $api  = new AccountApi(new Client(), $this->config);
        $info = $api->getAccount();

        $plans = $info->getPlan() ?? [];

        // Email send limit
        $emailCredits = 0;
        $smsCredits   = 0;
        foreach ($plans as $p) {
            if ($p->getCreditsType() === 'sendLimit') {
                if ($p->getType() === 'free' || $p->getType() === 'payAsYouGo') {
                    $emailCredits = $p->getCredits();
                }
                if ($p->getType() === 'sms') {
                    $smsCredits = $p->getCredits();
                }
            }
        }

        $plan = !empty($plans) ? $plans[0] : null;

        // Relay info
        $relay = $info->getRelay();
        $relayData = $relay ? $relay->getData() : null;

        // Address
        $addr = $info->getAddress();

        return [
            'email'            => $info->getEmail(),
            'first_name'       => $info->getFirstName(),
            'last_name'        => $info->getLastName(),
            'company_name'     => $info->getCompanyName(),
            'plan'             => $plan ? $plan->getType() : 'free',
            'plan_credits'     => $emailCredits,
            'sms_credits'      => $smsCredits,
            'plan_user_limit'  => $plan ? $plan->getUserLimit() : null,
            'plan_start_date'  => $plan ? ($plan->getStartDate() ? $plan->getStartDate()->format('Y-m-d') : null) : null,
            'plan_end_date'    => $plan ? ($plan->getEndDate() ? $plan->getEndDate()->format('Y-m-d') : null) : null,
            'smtp_host'        => $relayData ? $relayData->getRelay() : null,
            'smtp_port'        => $relayData ? $relayData->getPort() : null,
            'smtp_username'    => $relayData ? $relayData->getUserName() : null,
            'relay_enabled'    => $relay ? $relay->getEnabled() : false,
            'city'             => $addr ? $addr->getCity() : null,
            'country'          => $addr ? $addr->getCountry() : null,
        ];
    }

    // ── Send Transactional Email ──────────────────────────────────
    public function sendEmail(string $toEmail, string $toName, string $subject, string $htmlContent, string $textContent = ''): array
    {
        if (!$this->isConfigured()) throw new \Exception('Brevo API key not configured. Save settings first.');
        if (!$this->fromEmail) throw new \Exception('From Email not configured. Set it in Brevo settings.');

        $api = new TransactionalEmailsApi(new Client(), $this->config);

        $email = new SendSmtpEmail([
            'subject' => $subject,
            'sender'  => new SendSmtpEmailSender([
                'email' => $this->fromEmail,
                'name'  => $this->fromName,
            ]),
            'to' => [
                new SendSmtpEmailTo([
                    'email' => $toEmail,
                    'name'  => $toName ?: $toEmail,
                ]),
            ],
            'htmlContent' => $htmlContent,
            'textContent' => $textContent ?: strip_tags($htmlContent),
        ]);

        try {
            $result = $api->sendTransacEmail($email);
            return ['message_id' => $result->getMessageId()];
        } catch (\Brevo\Client\ApiException $e) {
            $body = json_decode($e->getResponseBody(), true);
            $msg  = $body['message'] ?? $e->getMessage();
            // Common errors
            if (str_contains($msg, 'sender')) {
                throw new \Exception('Sender email "' . $this->fromEmail . '" is not verified in Brevo. Go to Brevo → Senders & Domains → Add & verify your sender email.');
            }
            if (str_contains($msg, 'Unauthorized') || str_contains($msg, 'IP')) {
                throw new \Exception('IP not authorized. Go to Brevo → Profile → SMTP & API → Authorized IPs and add your server IP, or disable IP restriction.');
            }
            throw new \Exception('Brevo API error: ' . $msg);
        }
    }

    // ── Send Test Email ───────────────────────────────────────────
    public function sendTestEmail(string $toEmail): array
    {
        return $this->sendEmail(
            $toEmail,
            'Test Recipient',
            '✓ Brevo Test — Decathlon Admin',
            '<h2>Brevo is working!</h2><p>This is a test email from your Decathlon admin panel.</p><p>Your Brevo integration is configured correctly.</p>',
            'Brevo is working! This is a test email from your Decathlon admin panel.'
        );
    }

    // ── Email Templates CRUD ──────────────────────────────────────
    public function getTemplates(int $limit = 50, int $offset = 0): array
    {
        if (!$this->isConfigured()) throw new \Exception('Brevo API key not configured');

        // Use direct HTTP to avoid SDK sort parameter issue
        $response = \Illuminate\Support\Facades\Http::withHeaders([
            'api-key' => $this->apiKey,
        ])->get('https://api.brevo.com/v3/smtp/templates', [
            'limit'  => $limit,
            'offset' => $offset,
            'sort'   => 'desc',
        ]);

        if (!$response->successful()) {
            $body = $response->json();
            throw new \Exception($body['message'] ?? 'Failed to fetch templates');
        }

        $data      = $response->json();
        $templates = [];
        foreach ($data['templates'] ?? [] as $t) {
            $templates[] = [
                'id'          => $t['id'],
                'name'        => $t['name'],
                'subject'     => $t['subject'] ?? '',
                'is_active'   => $t['isActive'] ?? false,
                'created_at'  => isset($t['createdAt']) ? date('Y-m-d H:i', strtotime($t['createdAt'])) : null,
                'modified_at' => isset($t['modifiedAt']) ? date('Y-m-d H:i', strtotime($t['modifiedAt'])) : null,
                'sender_name' => $t['sender']['name'] ?? null,
                'sender_email'=> $t['sender']['email'] ?? null,
                'tag'         => $t['tag'] ?? null,
            ];
        }
        return ['templates' => $templates, 'count' => $data['count'] ?? count($templates)];
    }

    public function getTemplate(int $id): array
    {
        if (!$this->isConfigured()) throw new \Exception('Brevo API key not configured');

        $response = \Illuminate\Support\Facades\Http::withHeaders([
            'api-key' => $this->apiKey,
        ])->get("https://api.brevo.com/v3/smtp/templates/{$id}");

        if (!$response->successful()) {
            $body = $response->json();
            throw new \Exception($body['message'] ?? 'Failed to fetch template');
        }

        $t = $response->json();
        return [
            'id'          => $t['id'],
            'name'        => $t['name'],
            'subject'     => $t['subject'] ?? '',
            'html_content'=> $t['htmlContent'] ?? '',
            'is_active'   => $t['isActive'] ?? false,
            'sender_name' => $t['sender']['name'] ?? null,
            'sender_email'=> $t['sender']['email'] ?? null,
            'reply_to'    => $t['replyTo'] ?? null,
            'tag'         => $t['tag'] ?? null,
            'created_at'  => isset($t['createdAt']) ? date('Y-m-d H:i', strtotime($t['createdAt'])) : null,
            'modified_at' => isset($t['modifiedAt']) ? date('Y-m-d H:i', strtotime($t['modifiedAt'])) : null,
        ];
    }

    public function createTemplate(array $data): array
    {
        if (!$this->isConfigured()) throw new \Exception('Brevo API key not configured');
        $api = new TransactionalEmailsApi(new Client(), $this->config);

        $sender = new \Brevo\Client\Model\CreateSmtpTemplateSender([
            'name'  => $data['sender_name']  ?? $this->fromName,
            'email' => $data['sender_email'] ?? $this->fromEmail,
        ]);

        $tpl = new \Brevo\Client\Model\CreateSmtpTemplate([
            'templateName' => $data['name'],
            'subject'      => $data['subject'],
            'htmlContent'  => $data['html_content'],
            'sender'       => $sender,
            'replyTo'      => $data['reply_to'] ?? null,
            'isActive'     => $data['is_active'] ?? true,
        ]);

        $result = $api->createSmtpTemplate($tpl);
        return ['id' => $result->getId()];
    }

    public function updateTemplate(int $id, array $data): void
    {
        if (!$this->isConfigured()) throw new \Exception('Brevo API key not configured');
        $api = new TransactionalEmailsApi(new Client(), $this->config);

        $payload = new \Brevo\Client\Model\UpdateSmtpTemplate([]);
        if (isset($data['name']))         $payload->setTemplateName($data['name']);
        if (isset($data['subject']))      $payload->setSubject($data['subject']);
        if (isset($data['html_content'])) $payload->setHtmlContent($data['html_content']);
        if (isset($data['is_active']))    $payload->setIsActive($data['is_active']);
        if (isset($data['reply_to']))     $payload->setReplyTo($data['reply_to']);

        if (isset($data['sender_name']) || isset($data['sender_email'])) {
            $sender = new \Brevo\Client\Model\UpdateSmtpTemplateSender([
                'name'  => $data['sender_name']  ?? $this->fromName,
                'email' => $data['sender_email'] ?? $this->fromEmail,
            ]);
            $payload->setSender($sender);
        }

        $api->updateSmtpTemplate($id, $payload);
    }

    public function deleteTemplate(int $id): void
    {
        if (!$this->isConfigured()) throw new \Exception('Brevo API key not configured');
        $api = new TransactionalEmailsApi(new Client(), $this->config);
        $api->deleteSmtpTemplate($id);
    }

    public function sendTestTemplate(int $id, string $toEmail): void
    {
        if (!$this->isConfigured()) throw new \Exception('Brevo API key not configured');
        $api     = new TransactionalEmailsApi(new Client(), $this->config);
        $payload = new \Brevo\Client\Model\SendTestEmail(['emailTo' => [$toEmail]]);
        $api->sendTestTemplate($id, $payload);
    }

    // ── Senders CRUD ──────────────────────────────────────────────
    public function getSenders(): array
    {
        if (!$this->isConfigured()) throw new \Exception('Brevo API key not configured');
        $api    = new SendersApi(new Client(), $this->config);
        $result = $api->getSenders();
        $senders = [];
        foreach ($result->getSenders() ?? [] as $s) {
            $senders[] = [
                'id'      => $s->getId(),
                'name'    => $s->getName(),
                'email'   => $s->getEmail(),
                'active'  => $s->getActive(),
            ];
        }
        return $senders;
    }

    public function createSender(string $name, string $email): array
    {
        if (!$this->isConfigured()) throw new \Exception('Brevo API key not configured');
        $api    = new SendersApi(new Client(), $this->config);
        $sender = new CreateSender(['name' => $name, 'email' => $email]);
        $result = $api->createSender($sender);
        return ['id' => $result->getId(), 'spf_error' => $result->getSpfError()];
    }

    public function updateSender(int $id, string $name, string $email): void
    {
        if (!$this->isConfigured()) throw new \Exception('Brevo API key not configured');
        $api    = new SendersApi(new Client(), $this->config);
        $sender = new UpdateSender(['name' => $name, 'email' => $email]);
        $api->updateSender($id, $sender);
    }

    public function deleteSender(int $id): void
    {
        if (!$this->isConfigured()) throw new \Exception('Brevo API key not configured');
        $api = new SendersApi(new Client(), $this->config);
        $api->deleteSender($id);
    }

    public function validateSenderOTP(int $id, string $otp): void
    {
        if (!$this->isConfigured()) throw new \Exception('Brevo API key not configured');

        // Use direct HTTP — SDK Otp model doesn't have otp field
        $response = \Illuminate\Support\Facades\Http::withHeaders([
            'api-key' => $this->apiKey,
            'Content-Type' => 'application/json',
        ])->put("https://api.brevo.com/v3/senders/{$id}/validate", [
            'otp' => (int) preg_replace('/\D/', '', $otp),
        ]);

        if (!$response->successful()) {
            $body = $response->json();
            throw new \Exception($body['message'] ?? 'OTP validation failed');
        }
    }

    // ── Contacts ──────────────────────────────────────────────────
    public function addContact(string $email, string $firstName = '', string $lastName = '', array $listIds = []): array
    {
        if (!$this->isConfigured()) throw new \Exception('Brevo API key not configured');

        $api = new ContactsApi(new Client(), $this->config);

        $contact = new CreateContact([
            'email'      => $email,
            'attributes' => array_filter([
                'FIRSTNAME' => $firstName,
                'LASTNAME'  => $lastName,
            ]),
            'listIds'    => $listIds ?: [],
            'updateEnabled' => true,
        ]);

        $result = $api->createContact($contact);
        return ['id' => $result->getId()];
    }

    // ── Get Lists ─────────────────────────────────────────────────
    public function getLists(): array
    {
        if (!$this->isConfigured()) throw new \Exception('Brevo API key not configured');

        $api    = new ContactsApi(new Client(), $this->config);
        $result = $api->getLists(50, 0);
        $lists  = [];

        foreach ($result->getLists() ?? [] as $list) {
            $lists[] = [
                'id'             => $list->getId(),
                'name'           => $list->getName(),
                'total_contacts' => $list->getTotalBlacklisted() + $list->getTotalSubscribers(),
            ];
        }

        return $lists;
    }

    // ── Get Stats ─────────────────────────────────────────────────
    public function getEmailStats(): array
    {
        if (!$this->isConfigured()) throw new \Exception('Brevo API key not configured');

        $api    = new TransactionalEmailsApi(new Client(), $this->config);
        $result = $api->getEmailEventReport(100, 0);
        $events = $result->getEvents() ?? [];

        $stats = ['sent' => 0, 'delivered' => 0, 'opened' => 0, 'clicked' => 0, 'bounced' => 0, 'spam' => 0];

        foreach ($events as $event) {
            $type  = $event->getEvent() ?? '';
            $lower = strtolower($type);
            match($lower) {
                'requests',
                'request'     => $stats['sent']++,
                'delivered'   => $stats['delivered']++,
                'opened'      => $stats['opened']++,
                'clicks',
                'click'       => $stats['clicked']++,
                'hardbounces',
                'softbounces',
                'hardbounce',
                'softbounce'  => $stats['bounced']++,
                'spam'        => $stats['spam']++,
                default       => null,
            };
        }

        // remove debug key in production
        unset($stats['_raw_event_types']);
        return $stats;
    }
}
