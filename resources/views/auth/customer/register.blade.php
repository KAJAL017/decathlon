@extends('layouts.app')

@section('title', 'Create Account - Decathlon')

@section('content')
<div class="min-h-[80vh] flex items-center justify-center px-4 py-12">
    <div class="max-w-md w-full space-y-8 bg-white p-8 rounded-2xl border border-gray-100 shadow-sm">
        <div>
            <h2 class="text-center text-3xl font-black text-gray-900 uppercase tracking-tight">
                Join Us
            </h2>
            <p class="mt-2 text-center text-sm text-gray-600">
                Already have an account?
                <a href="{{ route('login') }}" class="font-bold text-[#0082C3] hover:underline">
                    Sign in
                </a>
            </p>
        </div>

        @php
            $showEmail = $loginMethods['email'] || $loginMethods['email_otp'];
            $showPhone = $loginMethods['otp'];
            $needsOtp = $loginMethods['email_otp'] || $loginMethods['otp'];
        @endphp

        <form id="register-form" class="mt-8 space-y-6" action="{{ route('register.post') }}" method="POST">
            @csrf

            <div id="reg-error" class="hidden bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg text-sm"></div>

            <div class="space-y-4">
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label for="first_name" class="block text-xs font-black text-gray-500 uppercase tracking-widest mb-1">First Name</label>
                        <input id="first_name" name="first_name" type="text" required
                            class="appearance-none relative block w-full px-4 py-3 border border-gray-300 placeholder-gray-400 text-gray-900 rounded-lg focus:outline-none focus:ring-[#0082C3] focus:border-[#0082C3] focus:z-10 sm:text-sm"
                            placeholder="John">
                    </div>
                    <div>
                        <label for="last_name" class="block text-xs font-black text-gray-500 uppercase tracking-widest mb-1">Last Name</label>
                        <input id="last_name" name="last_name" type="text" required
                            class="appearance-none relative block w-full px-4 py-3 border border-gray-300 placeholder-gray-400 text-gray-900 rounded-lg focus:outline-none focus:ring-[#0082C3] focus:border-[#0082C3] focus:z-10 sm:text-sm"
                            placeholder="Doe">
                    </div>
                </div>

                @if($showEmail)
                <div id="reg-email-group">
                    <label for="reg-email" class="block text-xs font-black text-gray-500 uppercase tracking-widest mb-1">Email address</label>
                    <input id="reg-email" name="email" type="email" autocomplete="email" required
                        class="appearance-none relative block w-full px-4 py-3 border border-gray-300 placeholder-gray-400 text-gray-900 rounded-lg focus:outline-none focus:ring-[#0082C3] focus:border-[#0082C3] focus:z-10 sm:text-sm"
                        placeholder="john@example.com">
                </div>
                @endif

                @if($showPhone)
                <div id="reg-phone-group">
                    <label for="reg-phone" class="block text-xs font-black text-gray-500 uppercase tracking-widest mb-1">Phone Number</label>
                    <input id="reg-phone" name="phone" type="tel" autocomplete="tel"
                        class="appearance-none relative block w-full px-4 py-3 border border-gray-300 placeholder-gray-400 text-gray-900 rounded-lg focus:outline-none focus:ring-[#0082C3] focus:border-[#0082C3] focus:z-10 sm:text-sm"
                        placeholder="+91 98765 43210">
                </div>
                @endif

                <div>
                    <label for="reg-password" class="block text-xs font-black text-gray-500 uppercase tracking-widest mb-1">Password</label>
                    <input id="reg-password" name="password" type="password" autocomplete="new-password" required
                        class="appearance-none relative block w-full px-4 py-3 border border-gray-300 placeholder-gray-400 text-gray-900 rounded-lg focus:outline-none focus:ring-[#0082C3] focus:border-[#0082C3] focus:z-10 sm:text-sm"
                        placeholder="••••••••">
                </div>
                <div>
                    <label for="reg-password-confirmation" class="block text-xs font-black text-gray-500 uppercase tracking-widest mb-1">Confirm Password</label>
                    <input id="reg-password-confirmation" name="password_confirmation" type="password" autocomplete="new-password" required
                        class="appearance-none relative block w-full px-4 py-3 border border-gray-300 placeholder-gray-400 text-gray-900 rounded-lg focus:outline-none focus:ring-[#0082C3] focus:border-[#0082C3] focus:z-10 sm:text-sm"
                        placeholder="••••••••">
                </div>
            </div>

            {{-- OTP Section --}}
            @if($needsOtp)
            <div id="reg-otp-section" class="hidden space-y-4">
                <div class="bg-blue-50 border border-blue-100 rounded-lg p-4 text-center">
                    <p class="text-sm text-gray-600" id="reg-otp-info">Verify your contact to continue.</p>
                </div>
                <div>
                    <label for="reg-otp" class="block text-xs font-black text-gray-500 uppercase tracking-widest mb-1">Verification Code</label>
                    <input id="reg-otp" name="otp" type="text" maxlength="6" pattern="[0-9]{6}" inputmode="numeric"
                        class="appearance-none relative block w-full px-4 py-3 border border-gray-300 placeholder-gray-400 text-gray-900 rounded-lg focus:outline-none focus:ring-[#0082C3] focus:border-[#0082C3] focus:z-10 sm:text-sm text-center text-2xl tracking-[0.5em] font-bold"
                        placeholder="000000">
                </div>
                <div class="flex items-center justify-between">
                    <button type="button" onclick="changeRegIdentifier()" class="text-sm text-gray-500 hover:text-gray-700">&larr; Change</button>
                    <button type="button" id="reg-resend-btn" onclick="resendRegOtp()" class="text-sm font-bold text-[#0082C3] hover:underline disabled:text-gray-400 disabled:cursor-not-allowed" disabled>
                        Resend OTP <span id="reg-otp-timer" class="text-gray-400"></span>
                    </button>
                </div>
            </div>
            @endif

            <div class="flex items-start">
                <div class="flex items-center h-5">
                    <input id="accepts_marketing" name="accepts_marketing" type="checkbox" value="1"
                        class="h-4 w-4 text-[#0082C3] focus:ring-[#0082C3] border-gray-300 rounded">
                </div>
                <div class="ml-3 text-sm">
                    <label for="accepts_marketing" class="font-medium text-gray-700">Keep me updated with latest sports offers and events.</label>
                </div>
            </div>

            <div class="text-xs text-gray-500 text-center px-4">
                By clicking "Join Us", you agree to our <a href="#" class="underline">Terms of Service</a> and <a href="#" class="underline">Privacy Policy</a>.
            </div>

            <div>
                @if($needsOtp)
                {{-- Step 1: Send OTP --}}
                <button type="button" id="reg-send-otp-btn" onclick="sendRegOtp()"
                    class="group relative w-full flex justify-center py-4 px-4 border border-transparent text-sm font-black uppercase tracking-widest rounded-lg text-white bg-[#0082C3] hover:bg-[#006699] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#0082C3] transition-all">
                    <span class="submit-text">Send Verification Code</span>
                    <span class="loading-spinner hidden">
                        <svg class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path></svg>
                    </span>
                </button>
                {{-- Step 2: Submit with OTP --}}
                <button type="submit" id="reg-submit-btn" class="hidden group relative w-full flex justify-center py-4 px-4 border border-transparent text-sm font-black uppercase tracking-widest rounded-lg text-white bg-[#0082C3] hover:bg-[#006699] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#0082C3] transition-all">
                    <span class="submit-text">Join Us</span>
                    <span class="loading-spinner hidden">
                        <svg class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path></svg>
                    </span>
                </button>
                @else
                <button type="submit" id="reg-submit-btn"
                    class="group relative w-full flex justify-center py-4 px-4 border border-transparent text-sm font-black uppercase tracking-widest rounded-lg text-white bg-[#0082C3] hover:bg-[#006699] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#0082C3] transition-all">
                    <span class="submit-text">Join Us</span>
                    <span class="loading-spinner hidden">
                        <svg class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path></svg>
                    </span>
                </button>
                @endif
            </div>
        </form>
    </div>
</div>

<script>
const CSRF = document.querySelector('meta[name="csrf-token"]').content;
const needsOtp = {{ $needsOtp ? 'true' : 'false' }};
let regOtpSent = false;
let regTimer = null;

function showRegError(msg) {
    const el = document.getElementById('reg-error');
    el.textContent = msg;
    el.classList.remove('hidden');
}
function hideRegError() { document.getElementById('reg-error').classList.add('hidden'); }

function setLoading(btn, loading) {
    const text = btn.querySelector('.submit-text');
    const spin = btn.querySelector('.loading-spinner');
    btn.disabled = loading;
    text.classList.toggle('hidden', loading);
    spin.classList.toggle('hidden', !loading);
}

function sendRegOtp() {
    hideRegError();
    const identifier = getRegIdentifier();
    if (!identifier) { showRegError('Please fill in the required fields.'); return; }

    const btn = document.getElementById('reg-send-otp-btn');
    setLoading(btn, true);

    fetch('{{ route("auth.otp.resend") }}', {
        method: 'POST',
        body: new URLSearchParams({ type: 'register', identifier: identifier }),
        headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json', 'X-CSRF-TOKEN': CSRF }
    })
    .then(r => r.json().then(d => ({ status: r.status, data: d })))
    .then(({ status, data }) => {
        if (data.success) {
            regOtpSent = true;
            document.getElementById('reg-otp-section').classList.remove('hidden');
            document.getElementById('reg-send-otp-btn').classList.add('hidden');
            document.getElementById('reg-submit-btn').classList.remove('hidden');
            const isEmail = identifier.includes('@');
            document.getElementById('reg-otp-info').textContent =
                `Enter the 6-digit code sent to your ${isEmail ? 'email' : 'phone'}.`;
            startRegTimer(data.expires_in || 600);
        } else {
            showRegError(data.message);
        }
        setLoading(btn, false);
    })
    .catch(() => { showRegError('Failed to send verification code.'); setLoading(btn, false); });
}

function resendRegOtp() {
    const identifier = getRegIdentifier();
    if (!identifier) return;

    fetch('{{ route("auth.otp.resend") }}', {
        method: 'POST',
        body: new URLSearchParams({ type: 'register', identifier: identifier }),
        headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json', 'X-CSRF-TOKEN': CSRF }
    })
    .then(r => r.json().then(d => ({ status: r.status, data: d })))
    .then(({ status, data }) => {
        if (data.success) {
            Toastify({ text: data.message, duration: 3000, gravity: "top", position: "center", backgroundColor: "#10b981" }).showToast();
            startRegTimer(data.expires_in || 600);
        } else {
            Toastify({ text: data.message, duration: 3000, gravity: "top", position: "center", backgroundColor: "#ef4444" }).showToast();
        }
    })
    .catch(() => {
        Toastify({ text: "Failed to resend OTP.", duration: 3000, gravity: "top", position: "center", backgroundColor: "#ef4444" }).showToast();
    });
}

function changeRegIdentifier() {
    regOtpSent = false;
    document.getElementById('reg-otp-section').classList.add('hidden');
    document.getElementById('reg-send-otp-btn').classList.remove('hidden');
    document.getElementById('reg-submit-btn').classList.add('hidden');
    hideRegError();
}

function getRegIdentifier() {
    const email = document.getElementById('reg-email')?.value || '';
    const phone = document.getElementById('reg-phone')?.value || '';
    return email || phone || null;
}

function startRegTimer(seconds) {
    const btn = document.getElementById('reg-resend-btn');
    const timerEl = document.getElementById('reg-otp-timer');
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

document.getElementById('register-form').addEventListener('submit', function(e) {
    e.preventDefault();
    hideRegError();

    if (needsOtp && !regOtpSent) {
        showRegError('Please verify your contact first.');
        return;
    }

    const btn = document.getElementById('reg-submit-btn');
    setLoading(btn, true);

    const formData = new FormData(this);

    fetch(this.action, {
        method: 'POST',
        body: formData,
        headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
    })
    .then(r => r.json().then(d => ({ status: r.status, data: d })))
    .then(({ status, data }) => {
        if (data.success) {
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
        } else {
            showRegError(data.message);
        }
        setLoading(btn, false);
    })
    .catch(() => { showRegError('An error occurred. Please try again.'); setLoading(btn, false); });
});
</script>
@endsection
