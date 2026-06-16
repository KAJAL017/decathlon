<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Services\CartService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class CustomerAuthController extends Controller
{
    protected $cartService;

    public function __construct(CartService $cartService)
    {
        $this->cartService = $cartService;
    }

    /**
     * Show the customer login page.
     */
    public function showLogin()
    {
        if (Auth::guard('customer')->check()) {
            return redirect()->route('home');
        }
        return view('auth.customer.login');
    }

    /**
     * Handle customer login.
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::guard('customer')->attempt($credentials, $request->has('remember'))) {
            $customer = Auth::guard('customer')->user();

            if (!$customer->is_active) {
                Auth::guard('customer')->logout();
                return response()->json([
                    'success' => false,
                    'message' => 'Your account is deactivated. Please contact support.'
                ], 403);
            }

            // Update login info
            $customer->update([
                'last_login_at' => now(),
                'last_login_ip' => $request->ip(),
                'login_count' => $customer->login_count + 1,
            ]);

            // Merge guest cart to customer cart
            $this->cartService->mergeGuestCart($customer);

            return response()->json([
                'success' => true,
                'message' => 'Welcome back, ' . $customer->first_name . '!',
                'redirect' => redirect()->intended(route('home'))->getTargetUrl()
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Invalid email or password.'
        ], 401);
    }

    /**
     * Show the customer registration page.
     */
    public function showRegister()
    {
        if (Auth::guard('customer')->check()) {
            return redirect()->route('home');
        }
        return view('auth.customer.register');
    }

    /**
     * Handle customer registration.
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:customers',
            'password' => 'required|string|min:8|confirmed',
            'accepts_marketing' => 'boolean',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first(),
                'errors' => $validator->errors()
            ], 422);
        }

        $customer = Customer::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'accepts_marketing' => $request->boolean('accepts_marketing'),
            'is_active' => true,
            'email_verification_token' => Str::random(60),
        ]);

        // Login the customer
        Auth::guard('customer')->login($customer);

        // Merge guest cart
        $this->cartService->mergeGuestCart($customer);

        return response()->json([
            'success' => true,
            'message' => 'Registration successful!',
            'redirect' => route('home')
        ]);
    }

    /**
     * Handle customer logout.
     */
    public function logout(Request $request)
    {
        Auth::guard('customer')->logout();

        $request->session()->forget('customer_id');
        $request->session()->regenerateToken();

        return redirect()->route('home')->with('message', 'Logged out successfully');
    }
}
