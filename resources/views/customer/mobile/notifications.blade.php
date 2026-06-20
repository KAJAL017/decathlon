@extends('customer.layouts.mobile')

@section('title', 'Notifications')
@section('page-title', 'Notifications')

@section('content')
<div class="space-y-3">

    @if($notifications->isEmpty())
        <div class="bg-white rounded-2xl p-8 text-center animate-fade-in">
            <div class="w-14 h-14 bg-surface-50 rounded-2xl flex items-center justify-center mx-auto mb-3">
                <i data-lucide="bell" class="w-7 h-7 text-surface-300"></i>
            </div>
            <h3 class="text-base font-bold text-surface-900 mb-1">No notifications</h3>
            <p class="text-xs text-surface-400">You're all caught up!</p>
        </div>
    @else
        @if($notifications->where('is_read', false)->isNotEmpty())
            <form action="{{ route('customer.notifications.read-all') }}" method="POST">
                @csrf
                <button type="submit" class="w-full py-2.5 text-xs font-semibold text-brand-600 active:text-brand-700 active:scale-[0.98] transition-transform text-right">
                    Mark all as read
                </button>
            </form>
        @endif

        <div class="bg-white rounded-2xl border border-surface-100 divide-y divide-surface-50 overflow-hidden">
            @foreach($notifications as $index => $notification)
                <div class="p-4 flex items-start gap-3 {{ !$notification->is_read ? 'bg-brand-50/30' : '' }} animate-slide-up opacity-0 stagger-{{ min($index + 1, 6) }}" id="notification-{{ $notification->id }}">
                    <div class="w-9 h-9 rounded-xl flex items-center justify-center shrink-0
                        {{ $notification->type === 'order_update' ? 'bg-blue-50 text-blue-600' :
                           ($notification->type === 'offer' ? 'bg-green-50 text-green-600' :
                           ($notification->type === 'price_drop' ? 'bg-amber-50 text-amber-600' :
                           ($notification->type === 'wishlist_alert' ? 'bg-red-50 text-red-500' :
                           'bg-surface-100 text-surface-600'))) }}">
                        <i data-lucide="{{ $notification->icon ?? 'bell' }}" class="w-4 h-4"></i>
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="flex items-start justify-between gap-2">
                            <p class="text-[13px] font-semibold text-surface-900 leading-snug">{{ $notification->title }}</p>
                            @if(!$notification->is_read)
                                <span class="w-2 h-2 bg-brand-500 rounded-full mt-1.5 shrink-0"></span>
                            @endif
                        </div>
                        @if($notification->message)
                            <p class="text-xs text-surface-500 mt-1 line-clamp-2 leading-relaxed">{{ $notification->message }}</p>
                        @endif
                        <div class="flex items-center gap-3 mt-2">
                            <p class="text-[10px] text-surface-400">{{ $notification->created_at->diffForHumans() }}</p>
                            @if($notification->link)
                                <a href="{{ $notification->link }}" class="text-[10px] font-semibold text-brand-600 active:text-brand-700 active:scale-[0.98] transition-transform">View</a>
                            @endif
                            @if(!$notification->is_read)
                                <button onclick="markRead({{ $notification->id }})" class="text-[10px] font-semibold text-surface-400 active:text-surface-600 active:scale-[0.98] transition-transform">Mark read</button>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="mt-2">{{ $notifications->links('pagination::tailwind') }}</div>
    @endif
</div>

@push('scripts')
<script>
async function markRead(id) {
    try {
        const res = await ajax(`{{ url('account/notifications') }}/${id}/read`, { method: 'POST' });
        const data = await res.json();
        if (data.success) {
            const el = document.getElementById(`notification-${id}`);
            el.classList.remove('bg-brand-50/30');
            const dot = el.querySelector('.bg-brand-500');
            if (dot) dot.remove();
            showToast('Marked as read');
        }
    } catch (e) {
        showToast('Something went wrong', 'error');
    }
}
</script>
@endpush
@endsection
