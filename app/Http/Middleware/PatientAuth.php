<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PatientAuth
{
    public function handle(Request $request, Closure $next)
    {
        if (!Auth::guard('customer')->check()) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['success' => false, 'message' => 'Unauthorized.'], 401);
            }
            return redirect()->route('login');
        }

        $customer = Auth::guard('customer')->user();
        if (!$customer->is_active) {
            Auth::guard('customer')->logout();
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['success' => false, 'message' => 'Account deactivated.'], 403);
            }
            return redirect()->route('login')->with('error', 'Your account is deactivated. Please contact support.');
        }

        return $next($request);
    }
}
