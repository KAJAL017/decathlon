@extends('customer.layouts.desktop')

@section('title', 'My Profile')
@section('page-title', 'Profile')

@section('content')
<div class="max-w-5xl">
    <div class="grid grid-cols-3 gap-8">

        {{-- Left Column: Avatar & Quick Info --}}
        <div class="col-span-1 space-y-6">
            <div class="bg-white rounded-2xl p-6 border border-surface-100 text-center animate-slide-up">
                <div class="relative inline-block mb-4">
                    <div class="w-28 h-28 rounded-2xl bg-brand-100 text-brand-700 flex items-center justify-center text-3xl font-bold overflow-hidden mx-auto">
                        @if($customer->avatar)
                            <img src="{{ asset('uploads/customers/' . $customer->avatar) }}" class="w-full h-full object-cover" alt="">
                        @else
                            {{ $customer->initials }}
                        @endif
                    </div>
                    <form action="{{ route('customer.profile.avatar') }}" method="POST" enctype="multipart/form-data" id="avatarForm">
                        @csrf
                        <label class="absolute -bottom-2 -right-2 w-10 h-10 bg-brand-600 text-white rounded-full flex items-center justify-center cursor-pointer shadow-lg hover:bg-brand-700 transition-colors">
                            <i data-lucide="camera" class="w-5 h-5"></i>
                            <input type="file" name="avatar" accept="image/*" class="hidden" onchange="document.getElementById('avatarForm').submit()">
                        </label>
                    </form>
                </div>
                <h3 class="text-lg font-bold text-surface-900">{{ $customer->name }}</h3>
                <p class="text-sm text-surface-400 mt-0.5">{{ $customer->email }}</p>
                <div class="mt-4 pt-4 border-t border-surface-100">
                    <p class="text-xs text-surface-400">Member since</p>
                    <p class="text-sm font-semibold text-surface-700 mt-0.5">{{ $customer->created_at->format('F Y') }}</p>
                </div>
            </div>

            <div class="bg-white rounded-2xl p-6 border border-surface-100 animate-slide-up stagger-2 opacity-0">
                <h4 class="text-sm font-bold text-surface-900 mb-4">Quick Stats</h4>
                <div class="space-y-3">
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-surface-500">Orders</span>
                        <span class="text-sm font-bold text-surface-900">{{ $customer->orders->count() ?? 0 }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-surface-500">Total Spent</span>
                        <span class="text-sm font-bold text-surface-900">₹{{ number_format($customer->orders->sum('total_amount') ?? 0, 0) }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-surface-500">Reward Points</span>
                        <span class="text-sm font-bold text-purple-600">{{ number_format($customer->rewards->points_balance ?? 0) }}</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Right Column: Forms --}}
        <div class="col-span-2 space-y-6">
            <div class="bg-white rounded-2xl p-8 border border-surface-100 animate-slide-up stagger-2 opacity-0">
                <div class="flex items-center gap-3 mb-6">
                    <div class="w-10 h-10 bg-brand-50 rounded-xl flex items-center justify-center">
                        <i data-lucide="user" class="w-5 h-5 text-brand-600"></i>
                    </div>
                    <div>
                        <h3 class="text-base font-bold text-surface-900">Personal Information</h3>
                        <p class="text-xs text-surface-400">Update your personal details</p>
                    </div>
                </div>
                <form action="{{ route('customer.profile.update') }}" method="POST" id="profileForm">
                    @csrf
                    @method('PUT')
                    <div class="grid grid-cols-2 gap-5">
                        <div>
                            <label class="block text-xs font-medium text-surface-500 mb-1.5">First Name</label>
                            <input type="text" name="first_name" value="{{ old('first_name', $customer->first_name) }}" required
                                   class="w-full px-4 py-2.5 bg-surface-50 border border-surface-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition-all">
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-surface-500 mb-1.5">Last Name</label>
                            <input type="text" name="last_name" value="{{ old('last_name', $customer->last_name) }}"
                                   class="w-full px-4 py-2.5 bg-surface-50 border border-surface-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition-all">
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-surface-500 mb-1.5">Email</label>
                            <input type="email" name="email" value="{{ old('email', $customer->email) }}" required
                                   class="w-full px-4 py-2.5 bg-surface-50 border border-surface-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition-all">
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-surface-500 mb-1.5">Phone</label>
                            <input type="tel" name="phone" value="{{ old('phone', $customer->phone) }}"
                                   class="w-full px-4 py-2.5 bg-surface-50 border border-surface-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition-all">
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-surface-500 mb-1.5">Date of Birth</label>
                            <input type="date" name="date_of_birth" value="{{ old('date_of_birth', $customer->date_of_birth?->format('Y-m-d')) }}"
                                   class="w-full px-4 py-2.5 bg-surface-50 border border-surface-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition-all">
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-surface-500 mb-1.5">Gender</label>
                            <select name="gender" class="w-full px-4 py-2.5 bg-surface-50 border border-surface-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition-all">
                                <option value="">Select</option>
                                <option value="male" {{ old('gender', $customer->gender) === 'male' ? 'selected' : '' }}>Male</option>
                                <option value="female" {{ old('gender', $customer->gender) === 'female' ? 'selected' : '' }}>Female</option>
                                <option value="other" {{ old('gender', $customer->gender) === 'other' ? 'selected' : '' }}>Other</option>
                            </select>
                        </div>
                    </div>
                    <div class="mt-6 flex justify-end">
                        <button type="submit" class="btn-primary px-8 py-2.5 text-white text-sm font-semibold rounded-xl">Save Changes</button>
                    </div>
                </form>
            </div>

            <div class="bg-white rounded-2xl p-8 border border-surface-100 animate-slide-up stagger-3 opacity-0">
                <div class="flex items-center gap-3 mb-6">
                    <div class="w-10 h-10 bg-amber-50 rounded-xl flex items-center justify-center">
                        <i data-lucide="shield" class="w-5 h-5 text-amber-600"></i>
                    </div>
                    <div>
                        <h3 class="text-base font-bold text-surface-900">Change Password</h3>
                        <p class="text-xs text-surface-400">Keep your account secure</p>
                    </div>
                </div>
                <form action="{{ route('customer.profile.password') }}" method="POST">
                    @csrf
                    <div class="grid grid-cols-2 gap-5 max-w-lg">
                        <div class="col-span-2">
                            <label class="block text-xs font-medium text-surface-500 mb-1.5">Current Password</label>
                            <input type="password" name="current_password" required
                                   class="w-full px-4 py-2.5 bg-surface-50 border border-surface-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition-all">
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-surface-500 mb-1.5">New Password</label>
                            <input type="password" name="password" required minlength="8"
                                   class="w-full px-4 py-2.5 bg-surface-50 border border-surface-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition-all">
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-surface-500 mb-1.5">Confirm New Password</label>
                            <input type="password" name="password_confirmation" required
                                   class="w-full px-4 py-2.5 bg-surface-50 border border-surface-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition-all">
                        </div>
                    </div>
                    <div class="mt-6">
                        <button type="submit" class="px-6 py-2.5 border border-surface-200 text-sm font-semibold rounded-xl hover:bg-surface-50 transition-colors">Update Password</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => lucide.createIcons());
</script>
@endpush
@endsection