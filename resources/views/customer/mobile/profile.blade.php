@extends('customer.layouts.mobile')

@section('title', 'My Profile')
@section('page-title', 'Profile')

@section('content')
<div class="space-y-3">

    {{-- Profile Picture --}}
    <div class="bg-white rounded-2xl p-5 border border-surface-100 animate-slide-up text-center">
        <div class="relative inline-block mb-3">
            <div class="w-20 h-20 rounded-2xl bg-brand-100 text-brand-700 flex items-center justify-center text-2xl font-bold overflow-hidden">
                @if($customer->avatar)
                    <img src="{{ asset('uploads/customers/' . $customer->avatar) }}" class="w-full h-full object-cover" alt="">
                @else
                    {{ $customer->initials }}
                @endif
            </div>
            <form action="{{ route('customer.profile.avatar') }}" method="POST" enctype="multipart/form-data" id="avatarForm">
                @csrf
                <label class="absolute -bottom-1 -right-1 w-9 h-9 bg-brand-600 text-white rounded-full flex items-center justify-center cursor-pointer shadow-lg active:scale-95 transition-transform">
                    <i data-lucide="camera" class="w-4 h-4"></i>
                    <input type="file" name="avatar" accept="image/*" class="hidden" onchange="document.getElementById('avatarForm').submit()">
                </label>
            </form>
        </div>
        <h3 class="text-base font-bold text-surface-900">{{ $customer->name }}</h3>
        <p class="text-xs text-surface-400 mt-0.5">{{ $customer->email }}</p>
        <p class="text-[11px] text-surface-300 mt-1">Member since {{ $customer->created_at->format('M Y') }}</p>
    </div>

    {{-- Personal Information --}}
    <div class="bg-white rounded-2xl p-4 border border-surface-100 animate-slide-up stagger-2 opacity-0">
        <h3 class="text-xs font-bold text-surface-900 mb-3 flex items-center gap-2">
            <i data-lucide="user" class="w-4 h-4 text-brand-600"></i> Personal Information
        </h3>
        <form action="{{ route('customer.profile.update') }}" method="POST" id="profileForm">
            @csrf
            @method('PUT')
            <div class="space-y-3">
                <div>
                    <label class="block text-[11px] font-medium text-surface-500 mb-1.5">First Name</label>
                    <input type="text" name="first_name" value="{{ old('first_name', $customer->first_name) }}" required
                           class="w-full px-4 py-3 bg-surface-50 border border-surface-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition-all">
                </div>
                <div>
                    <label class="block text-[11px] font-medium text-surface-500 mb-1.5">Last Name</label>
                    <input type="text" name="last_name" value="{{ old('last_name', $customer->last_name) }}"
                           class="w-full px-4 py-3 bg-surface-50 border border-surface-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition-all">
                </div>
                <div>
                    <label class="block text-[11px] font-medium text-surface-500 mb-1.5">Email</label>
                    <input type="email" name="email" value="{{ old('email', $customer->email) }}" required
                           class="w-full px-4 py-3 bg-surface-50 border border-surface-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition-all">
                </div>
                <div>
                    <label class="block text-[11px] font-medium text-surface-500 mb-1.5">Phone</label>
                    <input type="tel" name="phone" value="{{ old('phone', $customer->phone) }}"
                           class="w-full px-4 py-3 bg-surface-50 border border-surface-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition-all">
                </div>
                <div>
                    <label class="block text-[11px] font-medium text-surface-500 mb-1.5">Date of Birth</label>
                    <input type="date" name="date_of_birth" value="{{ old('date_of_birth', $customer->date_of_birth?->format('Y-m-d')) }}"
                           class="w-full px-4 py-3 bg-surface-50 border border-surface-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition-all">
                </div>
                <div>
                    <label class="block text-[11px] font-medium text-surface-500 mb-1.5">Gender</label>
                    <select name="gender" class="w-full px-4 py-3 bg-surface-50 border border-surface-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition-all">
                        <option value="">Select</option>
                        <option value="male" {{ old('gender', $customer->gender) === 'male' ? 'selected' : '' }}>Male</option>
                        <option value="female" {{ old('gender', $customer->gender) === 'female' ? 'selected' : '' }}>Female</option>
                        <option value="other" {{ old('gender', $customer->gender) === 'other' ? 'selected' : '' }}>Other</option>
                    </select>
                </div>
            </div>
            <button type="submit" class="w-full mt-4 btn-primary py-3.5 text-white text-sm font-semibold rounded-xl active:scale-[0.98] transition-transform">
                Save Changes
            </button>
        </form>
    </div>

    {{-- Change Password --}}
    <div class="bg-white rounded-2xl p-4 border border-surface-100 animate-slide-up stagger-3 opacity-0">
        <h3 class="text-xs font-bold text-surface-900 mb-3 flex items-center gap-2">
            <i data-lucide="lock" class="w-4 h-4 text-brand-600"></i> Change Password
        </h3>
        <form action="{{ route('customer.profile.password') }}" method="POST">
            @csrf
            <div class="space-y-3">
                <div>
                    <label class="block text-[11px] font-medium text-surface-500 mb-1.5">Current Password</label>
                    <input type="password" name="current_password" required
                           class="w-full px-4 py-3 bg-surface-50 border border-surface-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition-all">
                </div>
                <div>
                    <label class="block text-[11px] font-medium text-surface-500 mb-1.5">New Password</label>
                    <input type="password" name="password" required minlength="8"
                           class="w-full px-4 py-3 bg-surface-50 border border-surface-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition-all">
                </div>
                <div>
                    <label class="block text-[11px] font-medium text-surface-500 mb-1.5">Confirm New Password</label>
                    <input type="password" name="password_confirmation" required
                           class="w-full px-4 py-3 bg-surface-50 border border-surface-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition-all">
                </div>
            </div>
            <button type="submit" class="w-full mt-4 py-3.5 border border-surface-200 text-sm font-semibold rounded-xl active:bg-surface-50 transition-colors active:scale-[0.98]">
                Update Password
            </button>
        </form>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => lucide.createIcons());
</script>
@endpush
@endsection
