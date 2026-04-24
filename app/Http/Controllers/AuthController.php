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

            // Update last login
            $user->last_login = now();
            $user->save();

            // Store session
            session([
                'admin_logged_in' => true, 
                'admin_id' => $user->id,
                'admin_email' => $user->email, 
                'admin_name' => $user->name
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

        session()->forget(['admin_logged_in', 'admin_id', 'admin_email', 'admin_name']);
        return redirect()->route('admin.login')->with('message', 'Logged out successfully');
    }
}
