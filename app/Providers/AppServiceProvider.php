<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->configureDynamicMail();

        if (\Illuminate\Support\Facades\Schema::hasTable('categories')) {
            \Illuminate\Support\Facades\View::composer(['pages.*', 'layouts.app', 'partials.*'], \App\Http\ViewComposers\FrontendComposer::class);
        }
    }

    /**
     * Dynamically override Mail config from database SMTP settings.
     * This ensures ALL application emails use the admin-configured SMTP.
     */
    private function configureDynamicMail(): void
    {
        if (!\Illuminate\Support\Facades\Schema::hasTable('settings')) {
            return;
        }

        $host       = \App\Models\Setting::get('smtp_host', '');
        $port       = \App\Models\Setting::get('smtp_port', '587');
        $encryption = \App\Models\Setting::get('smtp_encryption', 'tls');
        $username   = \App\Models\Setting::get('smtp_username', '');
        $password   = \App\Models\Setting::get('smtp_password', '');
        $fromName   = \App\Models\Setting::get('smtp_from_name', 'Decathlon');
        $fromEmail  = \App\Models\Setting::get('smtp_from_email', '');

        if ($host && $username && $password) {
            config([
                'mail.default'                 => 'smtp',
                'mail.mailers.smtp.host'       => $host,
                'mail.mailers.smtp.port'       => (int) $port,
                'mail.mailers.smtp.encryption' => $encryption === 'none' ? null : $encryption,
                'mail.mailers.smtp.username'   => $username,
                'mail.mailers.smtp.password'   => $password,
                'mail.from.address'            => $fromEmail ?: $username,
                'mail.from.name'               => $fromName,
            ]);
        }
    }
}
