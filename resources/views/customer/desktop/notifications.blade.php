@extends('customer.layouts.desktop')

@section('title', 'Notifications')
@section('page-title', 'Notifications')

@section('content')
<div class="max-w-4xl">

    @if($notifications->isEmpty())
        <div class="bg-white rounded-2xl p-16 border border-surface-100 text-center animate-fade-in">
            <div class="w-20 h-20 bg-surface-50 rounded-2xl flex items-center justify-center mx-auto mb-5">
                <i data-lucide="bell" class="w-10 h-10 text-surface-300"></i>
            </div>
            <h3 class="text-xl font-bold text-surface-900 mb-2">No notifications</h3>
            <p class="text-sm text-surface-400">You're all caught up!</p>
        </div>
    @else
        <div class="flex items-center justify-between mb-6">
            <p class="text-sm text-surface-400">{{ $notifications->total() }} notification{{ $notifications->total() !== 1 ? 's' : '' }}</p>
            <form action="{{ route('customer.notifications.read-all') }}" method="POST">
                @csrf
                <button type="submit" class="text-sm font-medium text-brand-600 hover:text-brand-700 transition-colors flex items-center gap-1.5">
                    <i data-lucide="check-check" class="w-4 h-4"></i> Mark all as read
                </button>
            </form>
        </div>

        <div class="bg-white rounded-2xl border border-surface-100 divide-y divide-surface-100 overflow-hidden">
            @foreach($notifications as $index => $notification)
                @php
                    $typeColors = match($notification->type) {
                        'order_update' => 'bg-blue-50 text-blue-600',
                        'offer' => 'bg-emerald-50 text-emerald-600',
                        'price_drop' => 'bg-amber-50 text-amber-600',
                        'wishlist_alert' => 'bg-rose-50 text-rose-500',
                        'shipping' => 'bg-violet-50 text-violet-600',
                        'payment' => 'bg-teal-50 text-teal-600',
                        default => 'bg-surface-100 text-surface-500',
                    };
                @endphp
                <div class="px-6 py-5 flex items-start gap-4 hover:bg-surface-50/50 transition-colors group {{ !$notification->is_read ? 'bg-brand-50/30' : '' }}" id="notification-{{ $notification->id }}">
                    <div class="w-11 h-11 rounded-xl flex items-center justify-center shrink-0 {{ $typeColors }}">
                        <i data-lucide="{{ $notification->icon ?? 'bell' }}" class="w-5 h-5"></i>
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="flex items-start justify-between gap-3">
                            <p class="text-sm font-semibold text-surface-900 leading-snug">{{ $notification->title }}</p>
                            <div class="flex items-center gap-2 shrink-0">
                                @if(!$notification->is_read)
                                    <span class="w-2.5 h-2.5 bg-brand-500 rounded-full"></span>
                                @endif
                            </div>
                        </div>
                        @if($notification->message)
                            <p class="text-sm text-surface-500 mt-1.5 leading-relaxed">{{ $notification->message }}</p>
                        @endif
                        <div class="flex items-center gap-4 mt-2.5">
                            <p class="text-xs text-surface-400">{{ $notification->created_at->diffForHumans() }}</p>
                            @if($notification->link)
                                <a href="{{ $notification->link }}" class="text-xs font-medium text-brand-600 hover:text-brand-700 transition-colors">View</a>
                            @endif
                            @if(!$notification->is_read)
                                <button onclick="markRead({{ $notification->id }})" class="text-xs font-medium text-surface-400 hover:text-surface-600 transition-colors">Mark as read</button>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="mt-6">{{ $notifications->links('pagination::tailwind') }}</div>
    @endif
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => lucide.createIcons());
async function markRead(id) {
    const res = await ajax(`{{ url('account/notifications') }}/${id}/read`, { method: 'POST' });
    const data = await res.json();
    if (data.success) {
        const el = document.getElementById(`notification-${id}`);
        el.classList.remove('bg-brand-50/30');
        el.style.backgroundColor = 'transparent';
        const dot = el.querySelector('.bg-brand-500');
        if (dot) dot.remove();
        showToast('Notification marked as read');
    }
}
</script>
@endpush
@endsection
