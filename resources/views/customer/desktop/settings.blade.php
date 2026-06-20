@extends('customer.layouts.desktop')

@section('title', 'Settings')
@section('page-title', 'Settings')

@section('content')
<div class="max-w-3xl space-y-8">

    {{-- Preferences --}}
    <div class="bg-white rounded-2xl p-8 border border-surface-100 animate-slide-up">
        <div class="flex items-center gap-3 mb-6">
            <div class="w-10 h-10 bg-brand-50 rounded-xl flex items-center justify-center">
                <i data-lucide="sliders-horizontal" class="w-5 h-5 text-brand-600"></i>
            </div>
            <div>
                <h3 class="text-base font-bold text-surface-900">Preferences</h3>
                <p class="text-xs text-surface-400">Manage your language, timezone and notification settings</p>
            </div>
        </div>

        <form action="{{ route('customer.settings.update') }}" method="POST">
            @csrf
            @method('PUT')
            <div class="space-y-5">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div>
                        <label class="block text-sm font-medium text-surface-600 mb-2">Timezone</label>
                        <select name="timezone" class="w-full px-4 py-3 bg-surface-50 border border-surface-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition-all">
                            <option value="Asia/Kolkata" {{ old('timezone', $customer->timezone) === 'Asia/Kolkata' ? 'selected' : '' }}>Asia/Kolkata (IST)</option>
                            <option value="America/New_York" {{ old('timezone', $customer->timezone) === 'America/New_York' ? 'selected' : '' }}>America/New_York (EST)</option>
                            <option value="America/Los_Angeles" {{ old('timezone', $customer->timezone) === 'America/Los_Angeles' ? 'selected' : '' }}>America/Los_Angeles (PST)</option>
                            <option value="Europe/London" {{ old('timezone', $customer->timezone) === 'Europe/London' ? 'selected' : '' }}>Europe/London (GMT)</option>
                            <option value="Asia/Dubai" {{ old('timezone', $customer->timezone) === 'Asia/Dubai' ? 'selected' : '' }}>Asia/Dubai (GST)</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-surface-600 mb-2">Language</label>
                        <select name="language" class="w-full px-4 py-3 bg-surface-50 border border-surface-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition-all">
                            <option value="en" {{ old('language', $customer->language) === 'en' ? 'selected' : '' }}>English</option>
                            <option value="hi" {{ old('language', $customer->language) === 'hi' ? 'selected' : '' }}>Hindi</option>
                        </select>
                    </div>
                </div>

                <div class="border-t border-surface-100 pt-5">
                    <label class="flex items-start gap-4 cursor-pointer group">
                        <input type="checkbox" name="accepts_marketing" value="1" {{ old('accepts_marketing', $customer->accepts_marketing) ? 'checked' : '' }}
                               class="w-5 h-5 text-brand-600 border-surface-300 rounded focus:ring-brand-500 mt-0.5">
                        <div>
                            <p class="text-sm font-semibold text-surface-800 group-hover:text-surface-900 transition-colors">Marketing Emails</p>
                            <p class="text-xs text-surface-400 mt-0.5">Receive emails about new products, offers, and promotions</p>
                        </div>
                    </label>
                </div>
            </div>

            <div class="mt-6 pt-5 border-t border-surface-100">
                <button type="submit" class="btn-primary text-white px-8 py-3 rounded-xl text-sm font-semibold">Save Preferences</button>
            </div>
        </form>
    </div>

    {{-- Danger Zone --}}
    <div class="bg-white rounded-2xl p-8 border-2 border-red-200 animate-slide-up stagger-2 opacity-0">
        <div class="flex items-center gap-3 mb-4">
            <div class="w-10 h-10 bg-red-50 rounded-xl flex items-center justify-center">
                <i data-lucide="alert-triangle" class="w-5 h-5 text-red-500"></i>
            </div>
            <div>
                <h3 class="text-base font-bold text-red-600">Danger Zone</h3>
                <p class="text-xs text-surface-400">Irreversible account actions</p>
            </div>
        </div>
        <p class="text-sm text-surface-500 mb-5 leading-relaxed">Once you delete your account, there is no going back. All your data, orders, and history will be permanently removed. Please be certain.</p>
        <button onclick="confirmAction('Are you sure you want to delete your account? This action cannot be undone.', () => { showToast('Account deletion requested', 'info'); })" class="px-6 py-3 text-sm font-semibold text-red-600 border-2 border-red-200 rounded-xl hover:bg-red-50 transition-colors">
            Delete Account
        </button>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => lucide.createIcons());
</script>
@endpush
@endsection
