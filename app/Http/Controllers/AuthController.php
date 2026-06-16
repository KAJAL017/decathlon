<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AuthController extends Controller
{
    /**
     * Display the admin login page
     */
    public function showLogin()
    {
        // If already logged in, redirect to dashboard
        if (session('admin_logged_in')) {
            return redirect()->route('admin.dashboard');
        }
        
        return view('auth.login');
    }

    /**
     * Handle admin login
     */
    public function login(Request $request)
    {
        // Validate credentials
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        // Try database authentication
        $user = \App\Models\User::where('email', $credentials['email'])->first();
        
        if ($user && \Hash::check($credentials['password'], $user->password)) {
            // Check if user is active
            if (!$user->is_active) {
                return response()->json([
                    'success' => false,
                    'message' => 'Your account has been deactivated. Please contact administrator.'
                ], 403);
            }

            // Regenerate session to prevent session fixation
            $request->session()->regenerate();

            // Update last login
            $user->last_login = now();
            $user->save();

            // Store session
            session([
                'admin_logged_in' => true, 
                'admin_id' => $user->id,
                'admin_email' => $user->email, 
                'admin_name' => $user->name,
                'admin_role' => $user->role?->display_name ?? $user->role?->name ?? 'Administrator',
            ]);

            // Log activity
            \App\Models\ActivityLog::log(
                'login',
                'auth',
                "{$user->name} logged in successfully",
                ['email' => $user->email]
            );

            return response()->json([
                'success' => true,
                'message' => 'Login successful! Redirecting...',
                'redirect' => route('admin.dashboard')
            ]);
        }

        // Log failed login attempt
        if ($user) {
            \App\Models\ActivityLog::create([
                'user_id' => $user->id,
                'action' => 'failed_login',
                'module' => 'auth',
                'description' => "Failed login attempt for {$user->email}",
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Invalid email or password. Please try again.'
        ], 401);
    }

    /**
     * Handle admin logout
     */
    public function logout()
    {
        // Log activity before clearing session
        if (session('admin_id')) {
            \App\Models\ActivityLog::log(
                'logout',
                'auth',
                session('admin_name') . ' logged out',
                ['email' => session('admin_email')]
            );
        }

        session()->forget(['admin_logged_in', 'admin_id', 'admin_email', 'admin_name', 'admin_role']);
        return redirect()->route('admin.login')->with('message', 'Logged out successfully');
    }

    /**
     * Dynamically override Mail config based on SMTP Settings in database.
     */
    private function configureMail()
    {
        $host       = \App\Models\Setting::get('smtp_host', '');
        $port       = \App\Models\Setting::get('smtp_port', '587');
        $encryption = \App\Models\Setting::get('smtp_encryption', 'tls');
        $username   = \App\Models\Setting::get('smtp_username', '');
        $password   = \App\Models\Setting::get('smtp_password', '');
        $fromName   = \App\Models\Setting::get('smtp_from_name', 'Decathlon');
        $fromEmail  = \App\Models\Setting::get('smtp_from_email', '');

        // If dynamic SMTP is configured in admin panel, temporarily override the mail config
        if ($host && $username && $password) {
            config([
                'mail.default'                 => 'smtp',
                'mail.mailers.smtp.host'       => $host,
                'mail.mailers.smtp.port'       => (int)$port,
                'mail.mailers.smtp.encryption' => $encryption === 'none' ? null : $encryption,
                'mail.mailers.smtp.username'   => $username,
                'mail.mailers.smtp.password'   => $password,
                'mail.from.address'            => $fromEmail ?: $username,
                'mail.from.name'               => $fromName,
            ]);
        }
    }

    /**
     * Show Forgot Password Page
     */
    public function showForgotPassword()
    {
        if (session('admin_logged_in')) {
            return redirect()->route('admin.dashboard');
        }
        return view('auth.forgot-password');
    }

    /**
     * Handle Forgot Password Email Sending
     */
    public function forgotPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email'
        ]);

        $user = \App\Models\User::where('email', $request->email)->first();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'This email address is not registered in our system.'
            ], 422);
        }

        if (!$user->is_active) {
            return response()->json([
                'success' => false,
                'message' => 'Your account is deactivated. Please contact support.'
            ], 403);
        }

        // Generate secure token
        $token = \Illuminate\Support\Str::random(64);

        // Store/Update in password_reset_tokens
        \Illuminate\Support\Facades\DB::table('password_reset_tokens')->updateOrInsert(
            ['email' => $request->email],
            [
                'token' => $token,
                'created_at' => now()
            ]
        );

        // Override dynamic SMTP if set
        $this->configureMail();

        // Send Password Reset email
        try {
            $resetUrl = route('admin.reset-password', ['token' => $token]);
            
            \Illuminate\Support\Facades\Mail::send([], [], function ($message) use ($request, $resetUrl) {
                $fromEmail = config('mail.from.address') ?: 'hello@decathlon.com';
                $fromName  = config('mail.from.name') ?: 'Decathlon';
                
                $message->to($request->email)
                        ->from($fromEmail, $fromName)
                        ->subject('Password Reset Request — Decathlon Admin')
                        ->html("
                            <div style='font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto; padding: 20px; border: 1px solid #e5e7eb; border-radius: 12px;'>
                                <div style='text-align: center; margin-bottom: 24px;'>
                                    <h2 style='color: #0082C3; font-weight: 900; margin: 0;'>DECATHLON</h2>
                                    <p style='color: #6b7280; font-size: 14px; margin-top: 4px;'>Admin Control Panel</p>
                                </div>
                                <h3 style='color: #111827; margin-bottom: 12px;'>Hello,</h3>
                                <p style='color: #4b5563; font-size: 15px; line-height: 1.6; margin-bottom: 20px;'>
                                    We received a request to reset your administrator password. Click the button below to change your password. This link is valid for 60 minutes.
                                </p>
                                <div style='text-align: center; margin: 32px 0;'>
                                    <a href='{$resetUrl}' style='background-color: #0082C3; color: white; text-decoration: none; padding: 12px 24px; border-radius: 8px; font-weight: 600; font-size: 15px; display: inline-block; box-shadow: 0 4px 6px -1px rgba(0, 130, 195, 0.2);'>
                                        Reset Password
                                    </a>
                                </div>
                                <p style='color: #9ca3af; font-size: 12px; margin-top: 32px; border-top: 1px solid #e5e7eb; padding-top: 16px;'>
                                    If you did not request a password reset, please ignore this email.
                                </p>
                            </div>
                        ");
            });

            // Log activity
            \App\Models\ActivityLog::log(
                'password_reset_request',
                'auth',
                "Password reset email sent to {$request->email}",
                ['email' => $request->email]
            );

            return response()->json([
                'success' => true,
                'message' => 'If this email address exists in our system, you will receive a password reset link shortly.'
            ]);
        } catch (\Exception $e) {
            \Log::error('SMTP Reset Password Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to send recovery email. Error: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Show Reset Password Page
     */
    public function showResetPassword(string $token)
    {
        if (session('admin_logged_in')) {
            return redirect()->route('admin.dashboard');
        }

        // Verify token exists and is valid (not older than 60 minutes)
        $reset = \Illuminate\Support\Facades\DB::table('password_reset_tokens')
            ->where('token', $token)
            ->first();

        if (!$reset || \Carbon\Carbon::parse($reset->created_at)->addMinutes(60)->isPast()) {
            return redirect()->route('admin.login')->with('error', 'This password reset link is invalid or has expired. Please request a new one.');
        }

        return view('auth.reset-password', compact('token'));
    }

    /**
     * Handle Password Reset Action
     */
    public function resetPassword(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:6|confirmed',
        ]);

        // Verify token matching email
        $reset = \Illuminate\Support\Facades\DB::table('password_reset_tokens')
            ->where('token', $request->token)
            ->where('email', $request->email)
            ->first();

        if (!$reset || \Carbon\Carbon::parse($reset->created_at)->addMinutes(60)->isPast()) {
            return response()->json([
                'success' => false,
                'message' => 'This password reset request is invalid or has expired. Please try again.'
            ], 422);
        }

        $user = \App\Models\User::where('email', $request->email)->first();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User not found in our database.'
            ], 404);
        }

        // Update password
        $user->password = \Illuminate\Support\Facades\Hash::make($request->password);
        $user->save();

        // Delete spent token
        \Illuminate\Support\Facades\DB::table('password_reset_tokens')
            ->where('email', $request->email)
            ->delete();

        // Log activity
        \App\Models\ActivityLog::create([
            'user_id' => $user->id,
            'action' => 'password_reset',
            'module' => 'auth',
            'description' => "Password reset successfully for {$user->email}",
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Your password has been reset successfully! Redirecting to login...'
        ]);
    }
}
