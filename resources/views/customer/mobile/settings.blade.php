@extends('customer.layouts.mobile')

@section('title', 'Settings')
@section('page-title', 'Settings')

@section('content')
<div class="space-y-3">

    {{-- Preferences --}}
    <div class="bg-white rounded-2xl p-4 border border-surface-100 animate-slide-up">
        <h3 class="text-[13px] font-bold text-surface-900 mb-4">Preferences</h3>
        <form action="{{ route('customer.settings.update') }}" method="POST">
            @csrf
            @method('PUT')
            <div class="space-y-3.5">
                <div>
                    <label class="block text-[11px] font-medium text-surface-500 mb-1.5">Timezone</label>
                    <select name="timezone" class="w-full px-3 py-3 bg-surface-50 border border-surface-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500">
                        <option value="Asia/Kolkata" {{ old('timezone', $customer->timezone) === 'Asia/Kolkata' ? 'selected' : '' }}>Asia/Kolkata (IST)</option>
                        <option value="America/New_York" {{ old('timezone', $customer->timezone) === 'America/New_York' ? 'selected' : '' }}>America/New_York (EST)</option>
                        <option value="America/Los_Angeles" {{ old('timezone', $customer->timezone) === 'America/Los_Angeles' ? 'selected' : '' }}>America/Los_Angeles (PST)</option>
                        <option value="Europe/London" {{ old('timezone', $customer->timezone) === 'Europe/London' ? 'selected' : '' }}>Europe/London (GMT)</option>
                        <option value="Asia/Dubai" {{ old('timezone', $customer->timezone) === 'Asia/Dubai' ? 'selected' : '' }}>Asia/Dubai (GST)</option>
                    </select>
                </div>

                <div>
                    <label class="block text-[11px] font-medium text-surface-500 mb-1.5">Language</label>
                    <select name="language" class="w-full px-3 py-3 bg-surface-50 border border-surface-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500">
                        <option value="en" {{ old('language', $customer->language) === 'en' ? 'selected' : '' }}>English</option>
                        <option value="hi" {{ old('language', $customer->language) === 'hi' ? 'selected' : '' }}>Hindi</option>
                    </select>
                </div>

                <div class="border-t border-surface-100 pt-4">
                    <label class="flex items-center gap-3 cursor-pointer active:bg-surface-50 -mx-2 px-2 py-2 rounded-xl transition-colors">
                        <input type="checkbox" name="accepts_marketing" value="1" {{ old('accepts_marketing', $customer->accepts_marketing) ? 'checked' : '' }}
                               class="w-5 h-5 text-brand-600 border-surface-300 rounded focus:ring-brand-500">
                        <div>
                            <p class="text-[13px] font-medium text-surface-800">Marketing Emails</p>
                            <p class="text-[10px] text-surface-400 mt-0.5">Receive emails about new products, offers, and promotions</p>
                        </div>
                    </label>
                </div>
            </div>
            <div class="mt-5">
                <button type="submit" class="w-full btn-primary text-white py-3.5 rounded-xl text-sm font-semibold active:scale-[0.98] transition-transform">
                    Save Preferences
                </button>
            </div>
        </form>
    </div>

    {{-- Danger Zone --}}
    <div class="bg-white rounded-2xl p-4 border border-red-200 animate-slide-up stagger-2 opacity-0">
        <h3 class="text-[13px] font-bold text-red-600 mb-1.5">Danger Zone</h3>
        <p class="text-[11px] text-surface-500 mb-4 leading-relaxed">Once you delete your account, there is no going back. Please be certain.</p>
        <button onclick="confirmDeleteAccount()" class="w-full py-3 text-sm font-medium text-red-600 border border-red-200 rounded-xl active:bg-red-50 active:scale-[0.98] transition-transform">
            Delete Account
        </button>
    </div>
</div>

@push('scripts')
<script>
function confirmDeleteAccount() {
    confirmAction('Are you sure you want to delete your account? This action cannot be undone.', () => {
        showToast('Account deletion requested', 'info');
    });
}
</script>
@endpush
@endsection
