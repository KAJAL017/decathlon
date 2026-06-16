@extends('admin.layouts.app')
@section('title', 'My Profile')

@section('content')
<div class="space-y-6">

{{-- Header --}}
<div>
    <h1 class="text-2xl font-bold text-gray-900">My Profile</h1>
    <p class="text-sm text-gray-500 mt-0.5">Manage your account details and password</p>
</div>

{{-- 3-col grid: left = profile card + details, right = forms --}}
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 items-start">

{{-- ── LEFT COL ── --}}
<div class="lg:col-span-1 space-y-5">

    {{-- Profile Card --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="h-28 bg-gradient-to-r from-[#0082C3] to-[#005a8c]"></div>
        <div class="px-6 pb-6">
            <div class="-mt-12 mb-4 flex items-end justify-between">
                <div id="avatarInitial"
                    class="w-20 h-20 bg-gradient-to-br from-[#0082C3] to-[#005a8c] rounded-2xl flex items-center justify-center text-white text-3xl font-black shadow-lg border-4 border-white">
                    {{ strtoupper(substr($user->name, 0, 1)) }}
                </div>
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold
                    {{ $user->is_active ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                    <span class="w-1.5 h-1.5 rounded-full {{ $user->is_active ? 'bg-green-500' : 'bg-red-500' }}"></span>
                    {{ $user->is_active ? 'Active' : 'Inactive' }}
                </span>
            </div>
            <h2 class="text-lg font-bold text-gray-900" id="displayName">{{ $user->name }}</h2>
            <p class="text-sm text-gray-500" id="displayEmail">{{ $user->email }}</p>
            <div class="flex flex-wrap items-center gap-2 mt-3">
                @if($user->role)
                <span class="px-2.5 py-1 bg-blue-50 text-blue-700 text-xs font-semibold rounded-lg">
                    {{ $user->role->display_name ?? $user->role->name }}
                </span>
                @endif
                <span class="text-xs text-gray-400 font-mono">ID #{{ $user->id }}</span>
            </div>
        </div>
    </div>

    {{-- Account Details --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
        <h3 class="text-sm font-bold text-gray-900 mb-4 flex items-center gap-2">
            <i data-lucide="info" class="w-4 h-4 text-gray-400"></i>
            Account Details
        </h3>
        <div class="space-y-0">
            @foreach([
                ['label'=>'Role',         'value'=> $user->role?->display_name ?? $user->role?->name ?? '—'],
                ['label'=>'Status',       'value'=> $user->is_active ? 'Active' : 'Inactive'],
                ['label'=>'Member Since', 'value'=> $user->created_at?->format('d M Y') ?? '—'],
                ['label'=>'Last Login',   'value'=> $user->last_login?->format('d M Y, h:i A') ?? 'Never'],
                ['label'=>'Last Updated', 'value'=> $user->updated_at?->diffForHumans() ?? '—'],
            ] as $row)
            <div class="flex items-center justify-between py-2.5 border-b border-gray-50 last:border-0">
                <span class="text-xs text-gray-400 font-medium">{{ $row['label'] }}</span>
                <span class="text-xs font-semibold text-gray-800">{{ $row['value'] }}</span>
            </div>
            @endforeach
        </div>
    </div>

</div>{{-- end left --}}

{{-- ── RIGHT COL ── --}}
<div class="lg:col-span-2 space-y-5">

    {{-- Account Information --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 bg-gray-50 flex items-center gap-2">
            <i data-lucide="user" class="w-4 h-4 text-[#0082C3]"></i>
            <h3 class="text-sm font-bold text-gray-900">Account Information</h3>
        </div>
        <form id="profileForm" onsubmit="saveProfile(event)" class="p-6 space-y-5">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1.5">Full Name *</label>
                    <input type="text" name="name" id="inputName" value="{{ $user->name }}" required
                        class="w-full px-3.5 py-2.5 text-sm border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#0082C3] focus:border-transparent transition-all"
                        placeholder="Your full name">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1.5">Email Address *</label>
                    <input type="email" name="email" id="inputEmail" value="{{ $user->email }}" required
                        class="w-full px-3.5 py-2.5 text-sm border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#0082C3] focus:border-transparent transition-all"
                        placeholder="your@email.com">
                </div>
            </div>
            <div class="flex justify-end">
                <button type="submit" id="saveInfoBtn"
                    class="inline-flex items-center gap-2 px-5 py-2.5 bg-[#0082C3] text-white text-sm font-semibold rounded-xl hover:bg-[#006ba3] transition-colors shadow-sm">
                    <i data-lucide="check" class="w-4 h-4"></i>
                    Save Changes
                </button>
            </div>
        </form>
    </div>

    {{-- Change Password --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 bg-gray-50 flex items-center gap-2">
            <i data-lucide="lock" class="w-4 h-4 text-orange-500"></i>
            <h3 class="text-sm font-bold text-gray-900">Change Password</h3>
        </div>
        <form id="passwordForm" onsubmit="savePassword(event)" class="p-6 space-y-5">
            <div>
                <label class="block text-xs font-semibold text-gray-600 mb-1.5">Current Password *</label>
                <div class="relative">
                    <input type="password" name="current_password" id="currentPwd" required
                        class="w-full px-3.5 py-2.5 text-sm border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#0082C3] focus:border-transparent pr-10 transition-all"
                        placeholder="Enter your current password">
                    <button type="button" onclick="togglePwd('currentPwd')" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600">
                        <i data-lucide="eye" class="w-4 h-4"></i>
                    </button>
                </div>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1.5">New Password *</label>
                    <div class="relative">
                        <input type="password" name="new_password" id="newPwd" required minlength="6"
                            class="w-full px-3.5 py-2.5 text-sm border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#0082C3] focus:border-transparent pr-10 transition-all"
                            placeholder="Min. 6 characters" oninput="checkStrength(this.value)">
                        <button type="button" onclick="togglePwd('newPwd')" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600">
                            <i data-lucide="eye" class="w-4 h-4"></i>
                        </button>
                    </div>
                    <div class="mt-2 flex gap-1">
                        <div id="s1" class="h-1.5 flex-1 rounded-full bg-gray-200 transition-colors"></div>
                        <div id="s2" class="h-1.5 flex-1 rounded-full bg-gray-200 transition-colors"></div>
                        <div id="s3" class="h-1.5 flex-1 rounded-full bg-gray-200 transition-colors"></div>
                        <div id="s4" class="h-1.5 flex-1 rounded-full bg-gray-200 transition-colors"></div>
                    </div>
                    <p id="strengthLabel" class="text-[10px] text-gray-400 mt-1"></p>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1.5">Confirm New Password *</label>
                    <div class="relative">
                        <input type="password" name="new_password_confirmation" id="confirmPwd" required
                            class="w-full px-3.5 py-2.5 text-sm border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#0082C3] focus:border-transparent pr-10 transition-all"
                            placeholder="Repeat new password" oninput="checkMatch()">
                        <button type="button" onclick="togglePwd('confirmPwd')" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600">
                            <i data-lucide="eye" class="w-4 h-4"></i>
                        </button>
                    </div>
                    <p id="matchLabel" class="text-[10px] mt-1"></p>
                </div>
            </div>
            <div class="flex justify-end">
                <button type="submit" id="savePwdBtn"
                    class="inline-flex items-center gap-2 px-5 py-2.5 bg-orange-500 text-white text-sm font-semibold rounded-xl hover:bg-orange-600 transition-colors shadow-sm">
                    <i data-lucide="lock" class="w-4 h-4"></i>
                    Update Password
                </button>
            </div>
        </form>
    </div>

</div>{{-- end right --}}

</div>{{-- end grid --}}
</div>{{-- end space-y-6 --}}

{{-- Toast --}}
<div id="toast" class="fixed bottom-5 right-5 z-50 hidden">
    <div id="toastInner" class="flex items-center gap-3 px-4 py-3 rounded-xl shadow-lg text-sm font-medium text-white min-w-[240px]">
        <span id="toastIcon"></span><span id="toastMsg"></span>
    </div>
</div>
@endsection

@push('scripts')
<script>
const CSRF = document.querySelector('meta[name="csrf-token"]').content;

function showToast(type, msg) {
    const t = document.getElementById('toast');
    document.getElementById('toastInner').className = 'flex items-center gap-3 px-4 py-3 rounded-xl shadow-lg text-sm font-medium text-white min-w-[240px] ' + (type === 'success' ? 'bg-green-600' : 'bg-red-600');
    document.getElementById('toastIcon').textContent = type === 'success' ? '✓' : '✕';
    document.getElementById('toastMsg').textContent = msg;
    t.classList.remove('hidden');
    setTimeout(() => t.classList.add('hidden'), 3500);
}

function saveProfile(e) {
    e.preventDefault();
    const btn = document.getElementById('saveInfoBtn');
    btn.disabled = true;
    btn.textContent = 'Saving...';

    fetch('{{ route("admin.profile.update") }}', {
        method: 'PUT',
        credentials: 'same-origin',
        headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': CSRF },
        body: JSON.stringify({
            name:  document.getElementById('inputName').value,
            email: document.getElementById('inputEmail').value,
        })
    })
    .then(r => r.json())
    .then(res => {
        btn.disabled = false;
        btn.innerHTML = '<i data-lucide="check" class="w-4 h-4"></i> Save Changes';
        if (res.success) {
            showToast('success', 'Profile updated!');
            document.getElementById('avatarInitial').textContent = (res.data.name || 'A').charAt(0).toUpperCase();
            document.getElementById('displayName').textContent  = res.data.name;
            document.getElementById('displayEmail').textContent = res.data.email;
        } else {
            showToast('error', res.message || 'Failed');
        }
    })
    .catch(err => { btn.disabled = false; btn.textContent = 'Save Changes'; showToast('error', err.message); });
}

function savePassword(e) {
    e.preventDefault();
    const newPwd = document.getElementById('newPwd').value;
    const confPwd = document.getElementById('confirmPwd').value;
    if (newPwd !== confPwd) { showToast('error', 'Passwords do not match'); return; }

    const btn = document.getElementById('savePwdBtn');
    btn.disabled = true;
    btn.textContent = 'Updating...';

    fetch('{{ route("admin.profile.update") }}', {
        method: 'PUT',
        credentials: 'same-origin',
        headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': CSRF },
        body: JSON.stringify({
            current_password:          document.getElementById('currentPwd').value,
            new_password:              newPwd,
            new_password_confirmation: confPwd,
        })
    })
    .then(r => r.json())
    .then(res => {
        btn.disabled = false;
        btn.innerHTML = '<i data-lucide="lock" class="w-4 h-4"></i> Update Password';
        if (res.success) {
            showToast('success', 'Password updated!');
            document.getElementById('passwordForm').reset();
            resetStrength();
        } else {
            showToast('error', res.message || 'Failed');
        }
    })
    .catch(err => { btn.disabled = false; btn.textContent = 'Update Password'; showToast('error', err.message); });
}

function checkStrength(val) {
    let score = 0;
    if (val.length >= 6) score++;
    if (val.length >= 10) score++;
    if (/[A-Z]/.test(val) && /[a-z]/.test(val)) score++;
    if (/[0-9]/.test(val) && /[^A-Za-z0-9]/.test(val)) score++;
    const colors = ['','bg-red-400','bg-orange-400','bg-yellow-400','bg-green-500'];
    const labels = ['','Weak','Fair','Good','Strong'];
    const tc     = ['','text-red-500','text-orange-500','text-yellow-600','text-green-600'];
    for (let i = 1; i <= 4; i++) {
        document.getElementById('s'+i).className = 'h-1.5 flex-1 rounded-full transition-colors ' + (i <= score ? colors[score] : 'bg-gray-200');
    }
    const lbl = document.getElementById('strengthLabel');
    lbl.textContent = score > 0 ? labels[score] : '';
    lbl.className = 'text-[10px] mt-1 ' + (tc[score] || 'text-gray-400');
}

function resetStrength() {
    for (let i = 1; i <= 4; i++) document.getElementById('s'+i).className = 'h-1.5 flex-1 rounded-full bg-gray-200 transition-colors';
    document.getElementById('strengthLabel').textContent = '';
    document.getElementById('matchLabel').textContent = '';
}

function checkMatch() {
    const n = document.getElementById('newPwd').value;
    const c = document.getElementById('confirmPwd').value;
    const l = document.getElementById('matchLabel');
    if (!c) { l.textContent = ''; return; }
    l.textContent = n === c ? '✓ Passwords match' : '✕ Do not match';
    l.className = 'text-[10px] mt-1 ' + (n === c ? 'text-green-600' : 'text-red-500');
}

function togglePwd(id) {
    const inp = document.getElementById(id);
    inp.type = inp.type === 'password' ? 'text' : 'password';
}
</script>
@endpush
