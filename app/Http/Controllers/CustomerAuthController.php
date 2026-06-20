<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Setting;
use App\Services\CartService;
use App\Services\WishlistService;
use App\Services\OtpService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class CustomerAuthController extends Controller
{
    protected $cartService;
    protected $wishlistService;
    protected $otpService;

    public function __construct(CartService $cartService, WishlistService $wishlistService, OtpService $otpService)
    {
        $this->cartService = $cartService;
        $this->wishlistService = $wishlistService;
        $this->otpService = $otpService;
    }

    protected function getLoginMethods(): array
    {
        return [
            'email'           => (bool) Setting::get('login_method_email', '1'),
            'email_otp'       => (bool) Setting::get('login_method_email_otp', '0'),
            'google'          => (bool) Setting::get('login_method_google', '0'),
            'otp'             => (bool) Setting::get('login_method_otp', '0'),
            'guest'           => (bool) Setting::get('login_method_guest', '1'),
            'registration'    => (bool) Setting::get('registration_enabled', '1'),
            'google_client_id'=> Setting::get('google_client_id', ''),
        ];
    }

    protected function loginCustomer(Request $request, Customer $customer): array
    {
        Auth::guard('customer')->login($customer, $request->boolean('remember'));

        $customer->update([
            'last_login_at' => now(),
            'last_login_ip' => $request->ip(),
            'login_count'   => $customer->login_count + 1,
        ]);

        $this->cartService->mergeGuestCart($customer);
        $this->wishlistService->mergeGuestWishlist($customer);

        return [
            'success'  => true,
            'message'  => 'Welcome back, ' . $customer->first_name . '!',
            'redirect' => redirect()->intended(route('home'))->getTargetUrl(),
        ];
    }

    public function showLogin()
    {
        if (Auth::guard('customer')->check()) {
            return redirect()->route('home');
        }

        return view('auth.customer.login', [
            'loginMethods' => $this->getLoginMethods(),
        ]);
    }

    public function login(Request $request)
    {
        $loginMethod = $request->input('login_method', 'email');
        $settings = $this->getLoginMethods();

        if ($loginMethod === 'email' && !$settings['email']) {
            return response()->json(['success' => false, 'message' => 'Email login is not enabled.'], 422);
        }
        if ($loginMethod === 'otp' && !$settings['otp']) {
            return response()->json(['success' => false, 'message' => 'Mobile OTP login is not enabled.'], 422);
        }
        if ($loginMethod === 'email_otp' && !$settings['email_otp']) {
            return response()->json(['success' => false, 'message' => 'Email OTP login is not enabled.'], 422);
        }

        if ($loginMethod === 'email') {
            return $this->loginWithEmail($request);
        }

        if ($loginMethod === 'otp') {
            return $this->loginWithMobileOtp($request);
        }

        if ($loginMethod === 'email_otp') {
            return $this->loginWithEmailOtp($request);
        }

        return response()->json(['success' => false, 'message' => 'Invalid login method.'], 422);
    }

    protected function loginWithEmail(Request $request)
    {
        $credentials = $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::guard('customer')->attempt($credentials, $request->boolean('remember'))) {
            $customer = Auth::guard('customer')->user();

            if (!$customer->is_active) {
                Auth::guard('customer')->logout();
                return response()->json([
                    'success' => false,
                    'message' => 'Your account is deactivated. Please contact support.',
                ], 403);
            }

            $customer->update([
                'last_login_at' => now(),
                'last_login_ip' => $request->ip(),
                'login_count'   => $customer->login_count + 1,
            ]);

            $this->cartService->mergeGuestCart($customer);
            $this->wishlistService->mergeGuestWishlist($customer);

            return response()->json([
                'success'  => true,
                'message'  => 'Welcome back, ' . $customer->first_name . '!',
                'redirect' => redirect()->intended(route('home'))->getTargetUrl(),
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Invalid email or password.',
        ], 401);
    }

    protected function loginWithEmailOtp(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $customer = Customer::where('email', $request->email)->first();
        if (!$customer) {
            return response()->json(['success' => false, 'message' => 'No account found with this email.'], 404);
        }
        if (!$customer->is_active) {
            return response()->json(['success' => false, 'message' => 'Your account is deactivated. Please contact support.'], 403);
        }

        $otpData = $this->otpService->generate('login', email: $request->email, ip: $request->ip());
        $this->otpService->sendEmailOtp($request->email, $otpData['otp'], 'login');

        return response()->json([
            'success'     => true,
            'message'     => 'OTP sent to your email address.',
            'otp_sent'    => true,
            'identifier'  => $request->email,
            'expires_in'  => $otpData['expires_in'],
        ]);
    }

    protected function loginWithMobileOtp(Request $request)
    {
        $request->validate(['phone' => 'required']);

        $phone = $request->input('phone');
        $normalized = preg_replace('/[^0-9]/', '', $phone);
        if (strlen($normalized) > 10) {
            $normalized = substr($normalized, -10);
        }

        $customer = Customer::where('phone', 'LIKE', "%{$normalized}")->first();
        if (!$customer) {
            return response()->json(['success' => false, 'message' => 'No account found with this phone number.'], 404);
        }
        if (!$customer->is_active) {
            return response()->json(['success' => false, 'message' => 'Your account is deactivated. Please contact support.'], 403);
        }

        $otpData = $this->otpService->generate('login', phone: $normalized, ip: $request->ip());
        $this->otpService->sendPhoneOtp($normalized, $otpData['otp']);

        return response()->json([
            'success'     => true,
            'message'     => 'OTP sent to your phone number.',
            'otp_sent'    => true,
            'identifier'  => $normalized,
            'expires_in'  => $otpData['expires_in'],
        ]);
    }

    public function verifyOtp(Request $request)
    {
        $request->validate([
            'type'       => 'required|in:login,register,forgot_password',
            'identifier' => 'required',
            'otp'        => 'required|string|size:6',
        ]);

        $type       = $request->input('type');
        $identifier = $request->input('identifier');
        $otp        = $request->input('otp');

        $isEmail = filter_var($identifier, FILTER_VALIDATE_EMAIL);
        $result  = $this->otpService->verify(
            $type,
            $isEmail ? $identifier : null,
            $isEmail ? null : $identifier,
            $otp
        );

        if (!$result['success']) {
            return response()->json(['success' => false, 'message' => $result['message']], 422);
        }

        if ($type === 'login') {
            $customer = $isEmail
                ? Customer::where('email', $identifier)->first()
                : Customer::where('phone', 'LIKE', "%{$identifier}")->first();

            if (!$customer) {
                return response()->json(['success' => false, 'message' => 'Account not found.'], 404);
            }

            return $this->loginCustomer($request, $customer);
        }

        if ($type === 'register') {
            return response()->json([
                'success'   => true,
                'message'   => 'OTP verified. Proceed with registration.',
                'verified'  => true,
                'identifier'=> $identifier,
            ]);
        }

        if ($type === 'forgot_password') {
            return response()->json([
                'success'   => true,
                'message'   => 'OTP verified. You can now reset your password.',
                'verified'  => true,
                'identifier'=> $identifier,
            ]);
        }

        return response()->json(['success' => false, 'message' => 'Invalid OTP type.'], 422);
    }

    public function resendOtp(Request $request)
    {
        $request->validate([
            'type'       => 'required|in:login,register,forgot_password',
            'identifier' => 'required',
        ]);

        $type       = $request->input('type');
        $identifier = $request->input('identifier');
        $isEmail    = filter_var($identifier, FILTER_VALIDATE_EMAIL);

        $this->otpService->purgeExpiredOtpRecords(
            $type,
            $isEmail ? $identifier : null,
            $isEmail ? null : $identifier
        );

        $cooldown = $this->otpService->getRemainingCooldown(
            $type,
            $isEmail ? $identifier : null,
            $isEmail ? null : $identifier
        );

        if ($cooldown > 0) {
            $human = $this->otpService->formatCooldown($cooldown);
            return response()->json([
                'success'  => false,
                'message'  => "Please wait {$human} before requesting a new OTP.",
                'cooldown' => $cooldown,
            ], 422);
        }

        $otpData = $this->otpService->generate(
            $type,
            $isEmail ? $identifier : null,
            $isEmail ? null : $identifier,
            $request->ip()
        );

        if ($isEmail) {
            $this->otpService->sendEmailOtp($identifier, $otpData['otp'], $type);
        } else {
            $this->otpService->sendPhoneOtp($identifier, $otpData['otp']);
        }

        return response()->json([
            'success'    => true,
            'message'    => 'OTP resent successfully.',
            'expires_in' => $otpData['expires_in'],
        ]);
    }

    public function showRegister()
    {
        if (Auth::guard('customer')->check()) {
            return redirect()->route('home');
        }

        $settings = $this->getLoginMethods();
        if (!$settings['registration']) {
            return redirect()->route('login')->with('message', 'Registration is currently closed.');
        }

        return view('auth.customer.register', [
            'loginMethods' => $settings,
        ]);
    }

    public function register(Request $request)
    {
        $settings = $this->getLoginMethods();

        if (!$settings['registration']) {
            return response()->json(['success' => false, 'message' => 'Registration is currently closed.'], 403);
        }

        $rules = [
            'first_name' => 'required|string|max:255',
            'last_name'  => 'required|string|max:255',
            'password'   => 'required|string|min:8|confirmed',
        ];

        if ($settings['email'] || $settings['email_otp']) {
            $rules['email'] = 'required|string|email|max:255|unique:customers';
        }
        if ($settings['otp']) {
            $rules['phone'] = 'required|string|max:20|unique:customers';
        }
        if ($settings['email_otp'] || $settings['otp']) {
            $rules['otp'] = 'required|string|size:6';
        }

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first(),
                'errors'  => $validator->errors(),
            ], 422);
        }

        if (isset($rules['otp'])) {
            $identifier = $request->input('email') ?: $request->input('phone');
            $isEmail = filter_var($identifier, FILTER_VALIDATE_EMAIL);

            $otpResult = $this->otpService->verify(
                'register',
                $isEmail ? $identifier : null,
                $isEmail ? null : $identifier,
                $request->input('otp')
            );

            if (!$otpResult['success']) {
                return response()->json(['success' => false, 'message' => $otpResult['message']], 422);
            }
        }

        $customer = Customer::create([
            'first_name'             => $request->first_name,
            'last_name'              => $request->last_name,
            'email'                  => $request->input('email', ''),
            'phone'                  => $request->input('phone'),
            'password'               => Hash::make($request->password),
            'accepts_marketing'      => $request->boolean('accepts_marketing'),
            'is_active'              => true,
            'email_verified'         => true,
            'email_verification_token' => Str::random(60),
        ]);

        Auth::guard('customer')->login($customer);
        $this->cartService->mergeGuestCart($customer);
        $this->wishlistService->mergeGuestWishlist($customer);

        return response()->json([
            'success'  => true,
            'message'  => 'Registration successful! Welcome to Decathlon.',
            'redirect' => route('home'),
        ]);
    }

    public function forgotPassword(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $customer = Customer::where('email', $request->email)->first();
        if (!$customer) {
            return response()->json(['success' => false, 'message' => 'No account found with this email.'], 404);
        }
        if (!$customer->is_active) {
            return response()->json(['success' => false, 'message' => 'Your account is deactivated. Please contact support.'], 403);
        }

        $otpData = $this->otpService->generate('forgot_password', email: $request->email, ip: $request->ip());
        $this->otpService->sendEmailOtp($request->email, $otpData['otp'], 'forgot_password');

        return response()->json([
            'success'    => true,
            'message'    => 'Password reset OTP sent to your email.',
            'otp_sent'   => true,
            'identifier' => $request->email,
            'expires_in' => $otpData['expires_in'],
        ]);
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'identifier'         => 'required|email',
            'otp'                => 'required|string|size:6',
            'password'           => 'required|string|min:8|confirmed',
        ]);

        $otpResult = $this->otpService->verify(
            'forgot_password',
            $request->identifier,
            null,
            $request->otp
        );

        if (!$otpResult['success']) {
            return response()->json(['success' => false, 'message' => $otpResult['message']], 422);
        }

        $customer = Customer::where('email', $request->identifier)->first();
        if (!$customer) {
            return response()->json(['success' => false, 'message' => 'Account not found.'], 404);
        }

        $customer->update(['password' => Hash::make($request->password)]);

        Auth::guard('customer')->login($customer);

        return response()->json([
            'success'  => true,
            'message'  => 'Password reset successful!',
            'redirect' => route('home'),
        ]);
    }

    public function getLoginSettings()
    {
        return response()->json($this->getLoginMethods());
    }

    public function logout(Request $request)
    {
        Auth::guard('customer')->logout();
        $request->session()->forget('customer_id');
        $request->session()->regenerateToken();

        return redirect()->route('home')->with('message', 'Logged out successfully');
    }

    public function googleRedirect()
    {
        $settings = $this->getLoginMethods();
        if (!$settings['google'] || empty($settings['google_client_id'])) {
            return redirect()->route('login')->with('error', 'Google login is not enabled.');
        }

        $clientId = $settings['google_client_id'];
        $redirectUri = url('/auth/google/callback');
        $scope = 'email profile';

        $url = 'https://accounts.google.com/o/oauth2/v2/auth?' . http_build_query([
            'client_id'     => $clientId,
            'redirect_uri'  => $redirectUri,
            'response_type' => 'code',
            'scope'         => $scope,
            'access_type'   => 'offline',
            'prompt'        => 'consent',
        ]);

        return redirect($url);
    }

    public function googleCallback(Request $request)
    {
        $settings = $this->getLoginMethods();

        if (!$settings['google'] || empty($settings['google_client_id']) || empty($settings['google_client_secret'])) {
            return redirect()->route('login')->with('error', 'Google login is not configured.');
        }

        if ($request->has('error')) {
            return redirect()->route('login')->with('error', 'Google login was cancelled.');
        }

        $code = $request->input('code');
        if (!$code) {
            return redirect()->route('login')->with('error', 'Google login failed. No code received.');
        }

        try {
            $tokenResponse = Http::post('https://oauth2.googleapis.com/token', [
                'code'          => $code,
                'client_id'     => $settings['google_client_id'],
                'client_secret' => $settings['google_client_secret'],
                'redirect_uri'  => url('/auth/google/callback'),
                'grant_type'    => 'authorization_code',
            ]);

            if (!$tokenResponse->successful()) {
                return redirect()->route('login')->with('error', 'Google login failed. Could not obtain token.');
            }

            $accessToken = $tokenResponse->json('access_token');

            $userInfo = Http::withToken($accessToken)->get('https://www.googleapis.com/oauth2/v2/userinfo');

            if (!$userInfo->successful()) {
                return redirect()->route('login')->with('error', 'Google login failed. Could not get user info.');
            }

            $googleUser = $userInfo->json();
            $email = $googleUser['email'] ?? null;
            $name = $googleUser['name'] ?? '';
            $googleId = $googleUser['id'] ?? null;

            if (!$email) {
                return redirect()->route('login')->with('error', 'Google login failed. No email provided.');
            }

            $customer = Customer::where('email', $email)->first();

            if ($customer) {
                if (!$customer->is_active) {
                    return redirect()->route('login')->with('error', 'Your account is deactivated. Please contact support.');
                }
                $customer->update([
                    'google_id'     => $googleId,
                    'last_login_at' => now(),
                    'last_login_ip' => $request->ip(),
                    'login_count'   => $customer->login_count + 1,
                ]);
            } else {
                if (!$settings['registration']) {
                    return redirect()->route('login')->with('error', 'Registration is currently closed.');
                }

                $nameParts = explode(' ', $name, 2);
                $customer = Customer::create([
                    'first_name'        => $nameParts[0] ?? $email,
                    'last_name'         => $nameParts[1] ?? '',
                    'email'             => $email,
                    'phone'             => '',
                    'password'          => \Illuminate\Support\Str::random(60),
                    'google_id'         => $googleId,
                    'is_active'         => true,
                    'email_verified'    => true,
                    'accepts_marketing' => false,
                ]);
            }

            Auth::guard('customer')->login($customer);
            $this->cartService->mergeGuestCart($customer);
            $this->wishlistService->mergeGuestWishlist($customer);

            return redirect()->route('home')->with('message', 'Welcome, ' . $customer->first_name . '!');

        } catch (\Exception $e) {
            return redirect()->route('login')->with('error', 'Google login failed. Please try again.');
        }
    }
}
