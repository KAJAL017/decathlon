<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $defaults = [
            ['key' => 'login_method_email',     'value' => '1', 'group' => 'login_methods', 'type' => 'boolean', 'label' => 'Email & Password',     'description' => 'Standard email + password login',                'created_at' => now(), 'updated_at' => now()],
            ['key' => 'login_method_email_otp', 'value' => '0', 'group' => 'login_methods', 'type' => 'boolean', 'label' => 'Email + OTP',            'description' => 'Login via OTP sent to email (no password needed)', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'login_method_google',    'value' => '0', 'group' => 'login_methods', 'type' => 'boolean', 'label' => 'Google OAuth',           'description' => 'Sign in with Google account',                    'created_at' => now(), 'updated_at' => now()],
            ['key' => 'login_method_otp',       'value' => '0', 'group' => 'login_methods', 'type' => 'boolean', 'label' => 'Mobile OTP',             'description' => 'Login via SMS OTP (requires MSG91)',             'created_at' => now(), 'updated_at' => now()],
            ['key' => 'login_method_guest',     'value' => '1', 'group' => 'login_methods', 'type' => 'boolean', 'label' => 'Guest Checkout',         'description' => 'Allow checkout without account',                 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'registration_enabled',   'value' => '1', 'group' => 'login_methods', 'type' => 'radio',   'label' => 'Registration Enabled',   'description' => 'Allow new customer registrations',               'created_at' => now(), 'updated_at' => now()],
            ['key' => 'google_client_id',       'value' => '',  'group' => 'login_methods', 'type' => 'text',    'label' => 'Google Client ID',       'description' => 'Google OAuth Client ID',                         'created_at' => now(), 'updated_at' => now()],
            ['key' => 'google_client_secret',   'value' => '',  'group' => 'login_methods', 'type' => 'text',    'label' => 'Google Client Secret',   'description' => 'Google OAuth Client Secret',                     'created_at' => now(), 'updated_at' => now()],
        ];

        foreach ($defaults as $row) {
            DB::table('settings')->updateOrInsert(
                ['key' => $row['key']],
                $row
            );
        }
    }

    public function down(): void
    {
        DB::table('settings')->whereIn('key', [
            'login_method_email', 'login_method_email_otp', 'login_method_google',
            'login_method_otp', 'login_method_guest', 'registration_enabled',
            'google_client_id', 'google_client_secret',
        ])->delete();
    }
};
