<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminAuth
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!session('admin_logged_in')) {
            if ($request->expectsJson()) {
                return response()->json(['success' => false, 'message' => 'Unauthenticated.'], 401);
            }
            return redirect()->route('admin.login')->with('error', 'Please login to access the admin panel.');
        }

        // Check if admin user still exists and is active
        $adminId = session('admin_id');
        if ($adminId) {
            $user = \App\Models\User::find($adminId);
            if (!$user || !$user->is_active) {
                session()->forget(['admin_logged_in', 'admin_id', 'admin_email', 'admin_name', 'admin_role']);
                if ($request->expectsJson()) {
                    return response()->json(['success' => false, 'message' => 'Account deactivated.'], 401);
                }
                return redirect()->route('admin.login')->with('error', 'Your account has been deactivated.');
            }
        }

        return $next($request);
    }
}
