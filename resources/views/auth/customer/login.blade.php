@extends('layouts.app')

@section('title', 'Sign In - Decathlon')

@section('content')
<div class="min-h-[80vh] flex items-center justify-center px-4 py-12">
    <div class="max-w-md w-full space-y-8 bg-white p-8 rounded-2xl border border-gray-100 shadow-sm">
        <div>
            <h2 class="text-center text-3xl font-black text-gray-900 uppercase tracking-tight">
                Sign In
            </h2>
            @if($loginMethods['registration'])
            <p class="mt-2 text-center text-sm text-gray-600">
                New to Decathlon?
                <a href="{{ route('register') }}" class="font-bold text-[#0082C3] hover:underline">
                    Create an account
                </a>
            </p>
            @endif
        </div>

        {{-- Login Method Tabs --}}
        @php
            $activeMethods = [];
            if($loginMethods['email']) $activeMethods[] = 'email';
            if($loginMethods['email_otp']) $activeMethods[] = 'email_otp';
            if($loginMethods['otp']) $activeMethods[] = 'otp';
            $defaultMethod = !empty($activeMethods) ? $activeMethods[0] : 'none';
        @endphp

        @if(empty($activeMethods))
        <div class="text-center py-8">
            <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-gray-100 mb-4">
                <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
            </div>
            <h3 class="text-lg font-bold text-gray-900 mb-1">Login Temporarily Unavailable</h3>
            <p class="text-sm text-gray-500">No login methods are currently enabled. Please try again later or contact support.</p>
        </div>
        @else

        @if(count($activeMethods) > 1)
        <div class="flex border-b border-gray-200">
            @if($loginMethods['email'])
            <button type="button" class="login-tab flex-1 py-3 text-sm font-bold uppercase tracking-wider text-center border-b-2 transition-colors {{ $defaultMethod === 'email' ? 'border-[#0082C3] text-[#0082C3]' : 'border-transparent text-gray-400 hover:text-gray-600' }}" data-method="email" onclick="switchLoginTab('email')">
                Password
            </button>
            @endif
            @if($loginMethods['email_otp'])
            <button type="button" class="login-tab flex-1 py-3 text-sm font-bold uppercase tracking-wider text-center border-b-2 border-transparent text-gray-400 hover:text-gray-600 transition-colors" data-method="email_otp" onclick="switchLoginTab('email_otp')">
                Email OTP
            </button>
            @endif
            @if($loginMethods['otp'])
            <button type="button" class="login-tab flex-1 py-3 text-sm font-bold uppercase tracking-wider text-center border-b-2 border-transparent text-gray-400 hover:text-gray-600 transition-colors" data-method="otp" onclick="switchLoginTab('otp')">
                Mobile OTP
            </button>
            @endif
        </div>
        @endif

        {{-- Error Display --}}
        <div id="login-error" class="hidden bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg text-sm"></div>

        {{-- Email + Password Login --}}
        <form id="login-email-form" class="mt-6 space-y-5 {{ $defaultMethod !== 'email' ? 'hidden' : '' }}" action="{{ route('login.post') }}" method="POST">
            @csrf
            <input type="hidden" name="login_method" value="email">
            <div class="space-y-4">
                <div>
                    <label for="email" class="block text-xs font-black text-gray-500 uppercase tracking-widest mb-1">Email address</label>
                    <input id="email" name="email" type="email" autocomplete="email" required
                        class="appearance-none relative block w-full px-4 py-3 border border-gray-300 placeholder-gray-400 text-gray-900 rounded-lg focus:outline-none focus:ring-[#0082C3] focus:border-[#0082C3] focus:z-10 sm:text-sm"
                        placeholder="john@example.com">
                </div>
                <div>
                    <label for="password" class="block text-xs font-black text-gray-500 uppercase tracking-widest mb-1">Password</label>
                    <input id="password" name="password" type="password" autocomplete="current-password" required
                        class="appearance-none relative block w-full px-4 py-3 border border-gray-300 placeholder-gray-400 text-gray-900 rounded-lg focus:outline-none focus:ring-[#0082C3] focus:border-[#0082C3] focus:z-10 sm:text-sm"
                        placeholder="••••••••">
                </div>
            </div>

            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <input id="remember" name="remember" type="checkbox"
                        class="h-4 w-4 text-[#0082C3] focus:ring-[#0082C3] border-gray-300 rounded">
                    <label for="remember" class="ml-2 block text-sm text-gray-900">
                        Remember me
                    </label>
                </div>
                <div class="text-sm">
                    <button type="button" onclick="showForgotPassword()" class="font-bold text-[#0082C3] hover:underline">
                        Forgot your password?
                    </button>
                </div>
            </div>

            <div>
                <button type="submit" class="login-submit-btn group relative w-full flex justify-center py-4 px-4 border border-transparent text-sm font-black uppercase tracking-widest rounded-lg text-white bg-[#0082C3] hover:bg-[#006699] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#0082C3] transition-all">
                    <span class="submit-text">Sign In</span>
                    <span class="loading-spinner hidden">
                        <svg class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path></svg>
                    </span>
                </button>
            </div>
        </form>

        {{-- Email OTP Login --}}
        <form id="login-email-otp-form" class="mt-6 space-y-5 {{ $defaultMethod !== 'email_otp' ? 'hidden' : '' }}" action="{{ route('login.post') }}" method="POST">
            @csrf
            <input type="hidden" name="login_method" value="email_otp">

            {{-- Step 1: Enter Email --}}
            <div id="email-otp-step1">
                <div class="space-y-4">
                    <div>
                        <label for="email_otp_email" class="block text-xs font-black text-gray-500 uppercase tracking-widest mb-1">Email address</label>
                        <input id="email_otp_email" name="email" type="email" autocomplete="email" required
                            class="appearance-none relative block w-full px-4 py-3 border border-gray-300 placeholder-gray-400 text-gray-900 rounded-lg focus:outline-none focus:ring-[#0082C3] focus:border-[#0082C3] focus:z-10 sm:text-sm"
                            placeholder="john@example.com">
                    </div>
                </div>
                <div class="mt-5">
                    <button type="button" onclick="sendEmailOtp()" id="email-otp-send-btn" class="group relative w-full flex justify-center py-4 px-4 border border-transparent text-sm font-black uppercase tracking-widest rounded-lg text-white bg-[#0082C3] hover:bg-[#006699] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#0082C3] transition-all">
                        <span class="submit-text">Send OTP</span>
                        <span class="loading-spinner hidden">
                            <svg class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path></svg>
                        </span>
                    </button>
                </div>
            </div>

            {{-- Step 2: Enter OTP --}}
            <div id="email-otp-step2" class="hidden">
                <div class="text-center mb-4">
                    <div class="inline-flex items-center justify-center w-14 h-14 rounded-full bg-blue-50 mb-3">
                        <svg class="w-7 h-7 text-[#0082C3]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                    </div>
                    <p class="text-sm text-gray-600">Enter the 6-digit code sent to</p>
                    <p id="email-otp-identifier" class="text-sm font-bold text-gray-900"></p>
                </div>
                <div class="space-y-4">
                    <div>
                        <label for="email_otp_code" class="block text-xs font-black text-gray-500 uppercase tracking-widest mb-1">OTP Code</label>
                        <input id="email_otp_code" name="otp" type="text" maxlength="6" pattern="[0-9]{6}" inputmode="numeric" autocomplete="one-time-code" required
                            class="appearance-none relative block w-full px-4 py-3 border border-gray-300 placeholder-gray-400 text-gray-900 rounded-lg focus:outline-none focus:ring-[#0082C3] focus:border-[#0082C3] focus:z-10 sm:text-sm text-center text-2xl tracking-[0.5em] font-bold"
                            placeholder="000000">
                    </div>
                </div>
                <div class="flex items-center justify-between mt-3">
                    <button type="button" onclick="switchEmailOtpStep(1)" class="text-sm text-gray-500 hover:text-gray-700">
                        &larr; Change email
                    </button>
                    <button type="button" id="email-otp-resend-btn" onclick="resendOtp('email_otp')" class="text-sm font-bold text-[#0082C3] hover:underline disabled:text-gray-400 disabled:cursor-not-allowed" disabled>
                        Resend OTP <span id="email-otp-timer" class="text-gray-400"></span>
                    </button>
                </div>
                <div class="mt-5">
                    <button type="button" onclick="verifyEmailOtp()" id="email-otp-verify-btn" class="group relative w-full flex justify-center py-4 px-4 border border-transparent text-sm font-black uppercase tracking-widest rounded-lg text-white bg-[#0082C3] hover:bg-[#006699] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#0082C3] transition-all">
                        <span class="submit-text">Verify & Sign In</span>
                        <span class="loading-spinner hidden">
                            <svg class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path></svg>
                        </span>
                    </button>
                </div>
            </div>
        </form>

        {{-- Mobile OTP Login --}}
        <form id="login-otp-form" class="mt-6 space-y-5 {{ $defaultMethod !== 'otp' ? 'hidden' : '' }}" action="{{ route('login.post') }}" method="POST">
            @csrf
            <input type="hidden" name="login_method" value="otp">

            {{-- Step 1: Enter Phone --}}
            <div id="otp-step1">
                <div class="space-y-4">
                    <div>
                        <label for="otp_phone" class="block text-xs font-black text-gray-500 uppercase tracking-widest mb-1">Phone Number</label>
                        <input id="otp_phone" name="phone" type="tel" autocomplete="tel" required
                            class="appearance-none relative block w-full px-4 py-3 border border-gray-300 placeholder-gray-400 text-gray-900 rounded-lg focus:outline-none focus:ring-[#0082C3] focus:border-[#0082C3] focus:z-10 sm:text-sm"
                            placeholder="+91 98765 43210">
                    </div>
                </div>
                <div class="mt-5">
                    <button type="button" onclick="sendMobileOtp()" id="otp-send-btn" class="group relative w-full flex justify-center py-4 px-4 border border-transparent text-sm font-black uppercase tracking-widest rounded-lg text-white bg-[#0082C3] hover:bg-[#006699] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#0082C3] transition-all">
                        <span class="submit-text">Send OTP</span>
                        <span class="loading-spinner hidden">
                            <svg class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path></svg>
                        </span>
                    </button>
                </div>
            </div>

            {{-- Step 2: Enter OTP --}}
            <div id="otp-step2" class="hidden">
                <div class="text-center mb-4">
                    <div class="inline-flex items-center justify-center w-14 h-14 rounded-full bg-blue-50 mb-3">
                        <svg class="w-7 h-7 text-[#0082C3]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
                    </div>
                    <p class="text-sm text-gray-600">Enter the 6-digit code sent to</p>
                    <p id="otp-identifier" class="text-sm font-bold text-gray-900"></p>
                </div>
                <div class="space-y-4">
                    <div>
                        <label for="otp_code" class="block text-xs font-black text-gray-500 uppercase tracking-widest mb-1">OTP Code</label>
                        <input id="otp_code" name="otp" type="text" maxlength="6" pattern="[0-9]{6}" inputmode="numeric" autocomplete="one-time-code" required
                            class="appearance-none relative block w-full px-4 py-3 border border-gray-300 placeholder-gray-400 text-gray-900 rounded-lg focus:outline-none focus:ring-[#0082C3] focus:border-[#0082C3] focus:z-10 sm:text-sm text-center text-2xl tracking-[0.5em] font-bold"
                            placeholder="000000">
                    </div>
                </div>
                <div class="flex items-center justify-between mt-3">
                    <button type="button" onclick="switchOtpStep(1)" class="text-sm text-gray-500 hover:text-gray-700">
                        &larr; Change number
                    </button>
                    <button type="button" id="otp-resend-btn" onclick="resendOtp('otp')" class="text-sm font-bold text-[#0082C3] hover:underline disabled:text-gray-400 disabled:cursor-not-allowed" disabled>
                        Resend OTP <span id="otp-timer" class="text-gray-400"></span>
                    </button>
                </div>
                <div class="mt-5">
                    <button type="button" onclick="verifyMobileOtp()" id="otp-verify-btn" class="group relative w-full flex justify-center py-4 px-4 border border-transparent text-sm font-black uppercase tracking-widest rounded-lg text-white bg-[#0082C3] hover:bg-[#006699] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#0082C3] transition-all">
                        <span class="submit-text">Verify & Sign In</span>
                        <span class="loading-spinner hidden">
                            <svg class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path></svg>
                        </span>
                    </button>
                </div>
            </div>
        </form>

        {{-- Social Login --}}
        @if($loginMethods['google'] && $loginMethods['google_client_id'])
        <div class="mt-6">
            <div class="relative">
                <div class="absolute inset-0 flex items-center">
                    <div class="w-full border-t border-gray-200"></div>
                </div>
                <div class="relative flex justify-center text-sm">
                    <span class="px-3 bg-white text-gray-500 uppercase text-xs font-bold tracking-wider">or continue with</span>
                </div>
            </div>
            <div class="mt-4">
                <a href="{{ url('/auth/google/redirect') }}"
                    class="w-full flex items-center justify-center gap-3 py-3 px-4 border border-gray-300 rounded-lg text-sm font-bold text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#0082C3] transition-all">
                    <svg class="w-5 h-5" viewBox="0 0 24 24"><path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92a5.06 5.06 0 01-2.2 3.32v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.1z" fill="#4285F4"/><path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853"/><path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z" fill="#FBBC05"/><path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" fill="#EA4335"/></svg>
                    Sign in with Google
                </a>
            </div>
        </div>
        @endif

        {{-- Forgot Password Modal --}}
        <div id="forgot-password-modal" class="hidden fixed inset-0 bg-black/50 flex items-center justify-center z-50 p-4">
            <div class="bg-white rounded-2xl max-w-md w-full p-8 relative">
                <button type="button" onclick="closeForgotPassword()" class="absolute top-4 right-4 text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>

                {{-- Step 1: Enter Email --}}
                <div id="fp-step1">
                    <h3 class="text-xl font-black text-gray-900 uppercase tracking-tight mb-2">Reset Password</h3>
                    <p class="text-sm text-gray-600 mb-6">Enter your email and we'll send you a reset code.</p>
                    <div id="fp-error" class="hidden bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg text-sm mb-4"></div>
                    <div class="space-y-4">
                        <input id="fp-email" type="email" required
                            class="appearance-none relative block w-full px-4 py-3 border border-gray-300 placeholder-gray-400 text-gray-900 rounded-lg focus:outline-none focus:ring-[#0082C3] focus:border-[#0082C3] sm:text-sm"
                            placeholder="Enter your email address">
                    </div>
                    <button type="button" onclick="sendForgotPasswordOtp()" id="fp-send-btn" class="mt-5 w-full py-4 px-4 border border-transparent text-sm font-black uppercase tracking-widest rounded-lg text-white bg-[#0082C3] hover:bg-[#006699] transition-all">
                        <span class="submit-text">Send Reset Code</span>
                        <span class="loading-spinner hidden">
                            <svg class="animate-spin h-5 w-5 text-white inline" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path></svg>
                        </span>
                    </button>
                </div>

                {{-- Step 2: Enter OTP --}}
                <div id="fp-step2" class="hidden">
                    <h3 class="text-xl font-black text-gray-900 uppercase tracking-tight mb-2">Enter Code</h3>
                    <p class="text-sm text-gray-600 mb-1">We sent a 6-digit code to</p>
                    <p id="fp-identifier" class="text-sm font-bold text-gray-900 mb-6"></p>
                    <div id="fp-otp-error" class="hidden bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg text-sm mb-4"></div>
                    <div class="space-y-4">
                        <input id="fp-otp" type="text" maxlength="6" pattern="[0-9]{6}" inputmode="numeric" required
                            class="appearance-none relative block w-full px-4 py-3 border border-gray-300 placeholder-gray-400 text-gray-900 rounded-lg focus:outline-none focus:ring-[#0082C3] focus:border-[#0082C3] sm:text-sm text-center text-2xl tracking-[0.5em] font-bold"
                            placeholder="000000">
                    </div>
                    <button type="button" onclick="verifyForgotPasswordOtp()" id="fp-verify-btn" class="mt-5 w-full py-4 px-4 border border-transparent text-sm font-black uppercase tracking-widest rounded-lg text-white bg-[#0082C3] hover:bg-[#006699] transition-all">
                        <span class="submit-text">Verify Code</span>
                        <span class="loading-spinner hidden">
                            <svg class="animate-spin h-5 w-5 text-white inline" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path></svg>
                        </span>
                    </button>
                    <button type="button" onclick="switchFpStep(1)" class="mt-3 w-full text-sm text-gray-500 hover:text-gray-700">&larr; Back to email</button>
                </div>

                {{-- Step 3: New Password --}}
                <div id="fp-step3" class="hidden">
                    <h3 class="text-xl font-black text-gray-900 uppercase tracking-tight mb-2">New Password</h3>
                    <p class="text-sm text-gray-600 mb-6">Create a strong password for your account.</p>
                    <div id="fp-pw-error" class="hidden bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg text-sm mb-4"></div>
                    <div class="space-y-4">
                        <input id="fp-new-password" type="password" required
                            class="appearance-none relative block w-full px-4 py-3 border border-gray-300 placeholder-gray-400 text-gray-900 rounded-lg focus:outline-none focus:ring-[#0082C3] focus:border-[#0082C3] sm:text-sm"
                            placeholder="New password (min 8 characters)">
                        <input id="fp-new-password-confirm" type="password" required
                            class="appearance-none relative block w-full px-4 py-3 border border-gray-300 placeholder-gray-400 text-gray-900 rounded-lg focus:outline-none focus:ring-[#0082C3] focus:border-[#0082C3] sm:text-sm"
                            placeholder="Confirm new password">
                    </div>
                    <button type="button" onclick="resetPassword()" id="fp-reset-btn" class="mt-5 w-full py-4 px-4 border border-transparent text-sm font-black uppercase tracking-widest rounded-lg text-white bg-[#0082C3] hover:bg-[#006699] transition-all">
                        <span class="submit-text">Reset Password</span>
                        <span class="loading-spinner hidden">
                            <svg class="animate-spin h-5 w-5 text-white inline" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path></svg>
                        </span>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
@endif

<script>
const CSRF = document.querySelector('meta[name="csrf-token"]').content;
const HEADERS = { 'X-CSRF-TOKEN': CSRF, 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' };

let emailOtpTimer = null;
let otpTimer = null;

// ── Tab Switching ──────────────────────────────────────────────
function switchLoginTab(method) {
    document.querySelectorAll('.login-tab').forEach(tab => {
        tab.classList.remove('border-[#0082C3]', 'text-[#0082C3]');
        tab.classList.add('border-transparent', 'text-gray-400');
    });
    document.querySelector(`.login-tab[data-method="${method}"]`).classList.add('border-[#0082C3]', 'text-[#0082C3]');
    document.querySelector(`.login-tab[data-method="${method}"]`).classList.remove('border-transparent', 'text-gray-400');

    document.getElementById('login-email-form').classList.add('hidden');
    document.getElementById('login-email-otp-form').classList.add('hidden');
    document.getElementById('login-otp-form').classList.add('hidden');
    hideError();

    if (method === 'email') document.getElementById('login-email-form').classList.remove('hidden');
    if (method === 'email_otp') document.getElementById('login-email-otp-form').classList.remove('hidden');
    if (method === 'otp') document.getElementById('login-otp-form').classList.remove('hidden');
}

// ── Error Helpers ──────────────────────────────────────────────
function showError(msg) {
    const el = document.getElementById('login-error');
    el.textContent = msg;
    el.classList.remove('hidden');
}
function hideError() { document.getElementById('login-error').classList.add('hidden'); }

function setLoading(btn, loading) {
    const text = btn.querySelector('.submit-text');
    const spin = btn.querySelector('.loading-spinner');
    btn.disabled = loading;
    text.classList.toggle('hidden', loading);
    spin.classList.toggle('hidden', !loading);
}

// ── Email + Password Login ─────────────────────────────────────
document.getElementById('login-email-form').addEventListener('submit', function(e) {
    e.preventDefault();
    hideError();
    const btn = this.querySelector('.login-submit-btn');
    setLoading(btn, true);

    fetch(this.action, { method: 'POST', body: new FormData(this), headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' } })
    .then(r => r.json().then(d => ({ status: r.status, data: d })))
    .then(({ status, data }) => {
        if (data.success) {
            syncWishlistAndRedirect(data);
        } else {
            showError(data.message);
            setLoading(btn, false);
        }
    })
    .catch(() => { showError('An error occurred. Please try again.'); setLoading(btn, false); });
});

// ── Email OTP: Send ────────────────────────────────────────────
function sendEmailOtp() {
    hideError();
    const email = document.getElementById('email_otp_email').value;
    if (!email) { showError('Please enter your email address.'); return; }

    const btn = document.getElementById('email-otp-send-btn');
    setLoading(btn, true);

    fetch('{{ route("login.post") }}', {
        method: 'POST',
        body: new URLSearchParams({ login_method: 'email_otp', email: email }),
        headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json', 'X-CSRF-TOKEN': CSRF }
    })
    .then(r => r.json().then(d => ({ status: r.status, data: d })))
    .then(({ status, data }) => {
        if (data.success) {
            document.getElementById('email-otp-identifier').textContent = email;
            switchEmailOtpStep(2);
            startTimer('email-otp', data.expires_in || 600);
        } else {
            showError(data.message);
        }
        setLoading(btn, false);
    })
    .catch(() => { showError('Failed to send OTP. Please try again.'); setLoading(btn, false); });
}

function switchEmailOtpStep(step) {
    document.getElementById('email-otp-step1').classList.toggle('hidden', step !== 1);
    document.getElementById('email-otp-step2').classList.toggle('hidden', step !== 2);
    hideError();
}

function verifyEmailOtp() {
    hideError();
    const otp = document.getElementById('email_otp_code').value;
    const email = document.getElementById('email_otp_email').value;
    if (!otp || otp.length !== 6) { showError('Please enter a valid 6-digit OTP.'); return; }

    const btn = document.getElementById('email-otp-verify-btn');
    setLoading(btn, true);

    fetch('{{ route("auth.otp.verify") }}', {
        method: 'POST',
        body: new URLSearchParams({ type: 'login', identifier: email, otp: otp }),
        headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json', 'X-CSRF-TOKEN': CSRF }
    })
    .then(r => r.json().then(d => ({ status: r.status, data: d })))
    .then(({ status, data }) => {
        if (data.success) {
            syncWishlistAndRedirect(data);
        } else {
            showError(data.message);
            setLoading(btn, false);
        }
    })
    .catch(() => { showError('Verification failed. Please try again.'); setLoading(btn, false); });
}

// ── Mobile OTP: Send ───────────────────────────────────────────
function sendMobileOtp() {
    hideError();
    const phone = document.getElementById('otp_phone').value;
    if (!phone) { showError('Please enter your phone number.'); return; }

    const btn = document.getElementById('otp-send-btn');
    setLoading(btn, true);

    fetch('{{ route("login.post") }}', {
        method: 'POST',
        body: new URLSearchParams({ login_method: 'otp', phone: phone }),
        headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json', 'X-CSRF-TOKEN': CSRF }
    })
    .then(r => r.json().then(d => ({ status: r.status, data: d })))
    .then(({ status, data }) => {
        if (data.success) {
            document.getElementById('otp-identifier').textContent = data.identifier || phone;
            switchOtpStep(2);
            startTimer('otp', data.expires_in || 600);
        } else {
            showError(data.message);
        }
        setLoading(btn, false);
    })
    .catch(() => { showError('Failed to send OTP. Please try again.'); setLoading(btn, false); });
}

function switchOtpStep(step) {
    document.getElementById('otp-step1').classList.toggle('hidden', step !== 1);
    document.getElementById('otp-step2').classList.toggle('hidden', step !== 2);
    hideError();
}

function verifyMobileOtp() {
    hideError();
    const otp = document.getElementById('otp_code').value;
    const phone = document.getElementById('otp_phone').value;
    if (!otp || otp.length !== 6) { showError('Please enter a valid 6-digit OTP.'); return; }

    const btn = document.getElementById('otp-verify-btn');
    setLoading(btn, true);

    fetch('{{ route("auth.otp.verify") }}', {
        method: 'POST',
        body: new URLSearchParams({ type: 'login', identifier: phone, otp: otp }),
        headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json', 'X-CSRF-TOKEN': CSRF }
    })
    .then(r => r.json().then(d => ({ status: r.status, data: d })))
    .then(({ status, data }) => {
        if (data.success) {
            syncWishlistAndRedirect(data);
        } else {
            showError(data.message);
            setLoading(btn, false);
        }
    })
    .catch(() => { showError('Verification failed. Please try again.'); setLoading(btn, false); });
}

// ── Resend OTP ─────────────────────────────────────────────────
function resendOtp(type) {
    const identifier = type === 'email_otp'
        ? document.getElementById('email_otp_email').value
        : document.getElementById('otp_phone').value;

    fetch('{{ route("auth.otp.resend") }}', {
        method: 'POST',
        body: new URLSearchParams({ type: 'login', identifier: identifier }),
        headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json', 'X-CSRF-TOKEN': CSRF }
    })
    .then(r => r.json().then(d => ({ status: r.status, data: d })))
    .then(({ status, data }) => {
        if (data.success) {
            Toastify({ text: data.message, duration: 3000, gravity: "top", position: "center", backgroundColor: "#10b981" }).showToast();
            startTimer(type, data.expires_in || 600);
        } else {
            Toastify({ text: data.message, duration: 3000, gravity: "top", position: "center", backgroundColor: "#ef4444" }).showToast();
        }
    })
    .catch(() => {
        Toastify({ text: "Failed to resend OTP.", duration: 3000, gravity: "top", position: "center", backgroundColor: "#ef4444" }).showToast();
    });
}

// ── Timer ──────────────────────────────────────────────────────
function startTimer(type, seconds) {
    const btnId = type === 'email_otp' ? 'email-otp-resend-btn' : 'otp-resend-btn';
    const timerId = type === 'email_otp' ? 'email-otp-timer' : 'otp-timer';
    const btn = document.getElementById(btnId);
    const timerEl = document.getElementById(timerId);

    btn.disabled = true;
    let remaining = seconds;

    const interval = setInterval(() => {
        remaining--;
        const m = Math.floor(remaining / 60);
        const s = remaining % 60;
        timerEl.textContent = `(${m}:${s.toString().padStart(2, '0')})`;

        if (remaining <= 0) {
            clearInterval(interval);
            btn.disabled = false;
            timerEl.textContent = '';
        }
    }, 1000);

    timerEl.textContent = `(${Math.floor(seconds / 60)}:${(seconds % 60).toString().padStart(2, '0')})`;
}

// ── Forgot Password ────────────────────────────────────────────
function showForgotPassword() {
    document.getElementById('forgot-password-modal').classList.remove('hidden');
    document.getElementById('fp-step1').classList.remove('hidden');
    document.getElementById('fp-step2').classList.add('hidden');
    document.getElementById('fp-step3').classList.add('hidden');
    ['fp-error', 'fp-otp-error', 'fp-pw-error'].forEach(id => document.getElementById(id).classList.add('hidden'));
}
function closeForgotPassword() { document.getElementById('forgot-password-modal').classList.add('hidden'); }

function switchFpStep(step) {
    document.getElementById('fp-step1').classList.toggle('hidden', step !== 1);
    document.getElementById('fp-step2').classList.toggle('hidden', step !== 2);
    document.getElementById('fp-step3').classList.toggle('hidden', step !== 3);
    ['fp-error', 'fp-otp-error', 'fp-pw-error'].forEach(id => document.getElementById(id).classList.add('hidden'));
}

function showFpError(id, msg) { const el = document.getElementById(id); el.textContent = msg; el.classList.remove('hidden'); }

function sendForgotPasswordOtp() {
    const email = document.getElementById('fp-email').value;
    if (!email) { showFpError('fp-error', 'Please enter your email.'); return; }

    const btn = document.getElementById('fp-send-btn');
    setLoading(btn, true);

    fetch('{{ route("auth.forgot-password") }}', {
        method: 'POST',
        body: new URLSearchParams({ email: email }),
        headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json', 'X-CSRF-TOKEN': CSRF }
    })
    .then(r => r.json().then(d => ({ status: r.status, data: d })))
    .then(({ status, data }) => {
        if (data.success) {
            document.getElementById('fp-identifier').textContent = email;
            switchFpStep(2);
        } else {
            showFpError('fp-error', data.message);
        }
        setLoading(btn, false);
    })
    .catch(() => { showFpError('fp-error', 'Failed to send OTP.'); setLoading(btn, false); });
}

function verifyForgotPasswordOtp() {
    const otp = document.getElementById('fp-otp').value;
    const email = document.getElementById('fp-email').value;
    if (!otp || otp.length !== 6) { showFpError('fp-otp-error', 'Please enter a valid 6-digit code.'); return; }

    const btn = document.getElementById('fp-verify-btn');
    setLoading(btn, true);

    fetch('{{ route("auth.otp.verify") }}', {
        method: 'POST',
        body: new URLSearchParams({ type: 'forgot_password', identifier: email, otp: otp }),
        headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json', 'X-CSRF-TOKEN': CSRF }
    })
    .then(r => r.json().then(d => ({ status: r.status, data: d })))
    .then(({ status, data }) => {
        if (data.success) {
            switchFpStep(3);
        } else {
            showFpError('fp-otp-error', data.message);
        }
        setLoading(btn, false);
    })
    .catch(() => { showFpError('fp-otp-error', 'Verification failed.'); setLoading(btn, false); });
}

function resetPassword() {
    const password = document.getElementById('fp-new-password').value;
    const confirm = document.getElementById('fp-new-password-confirm').value;
    const email = document.getElementById('fp-email').value;
    const otp = document.getElementById('fp-otp').value;

    if (password.length < 8) { showFpError('fp-pw-error', 'Password must be at least 8 characters.'); return; }
    if (password !== confirm) { showFpError('fp-pw-error', 'Passwords do not match.'); return; }

    const btn = document.getElementById('fp-reset-btn');
    setLoading(btn, true);

    fetch('{{ route("auth.reset-password") }}', {
        method: 'POST',
        body: new URLSearchParams({ identifier: email, otp: otp, password: password, password_confirmation: confirm }),
        headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json', 'X-CSRF-TOKEN': CSRF }
    })
    .then(r => r.json().then(d => ({ status: r.status, data: d })))
    .then(({ status, data }) => {
        if (data.success) {
            Toastify({ text: data.message, duration: 3000, gravity: "top", position: "center", backgroundColor: "#10b981" }).showToast();
            setTimeout(() => { window.location.href = data.redirect || '{{ route("home") }}'; }, 1000);
        } else {
            showFpError('fp-pw-error', data.message);
        }
        setLoading(btn, false);
    })
    .catch(() => { showFpError('fp-pw-error', 'Failed to reset password.'); setLoading(btn, false); });
}

// ── Shared: Wishlist Sync + Redirect ───────────────────────────
function syncWishlistAndRedirect(data) {
    const guestWishlist = JSON.parse(localStorage.getItem('decathlon_wishlist') || '[]');
    const doRedirect = () => {
        Toastify({ text: data.message, duration: 3000, gravity: "top", position: "center", backgroundColor: "#10b981" }).showToast();
        setTimeout(() => { window.location.href = data.redirect || '{{ route("home") }}'; }, 1000);
    };
    if (guestWishlist.length > 0) {
        fetch('{{ route("wishlist.sync") }}', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json' },
            body: JSON.stringify({ product_ids: guestWishlist })
        })
        .then(() => { localStorage.removeItem('decathlon_wishlist'); doRedirect(); })
        .catch(() => { localStorage.removeItem('decathlon_wishlist'); doRedirect(); });
    } else {
        doRedirect();
    }
}
</script>
@endsection
