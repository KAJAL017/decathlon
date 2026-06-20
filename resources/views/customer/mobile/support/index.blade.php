@extends('customer.layouts.mobile')

@section('title', 'Support')
@section('page-title', 'Support')

@section('content')
<div class="space-y-3">

    {{-- New Ticket Button --}}
    <button onclick="showTicketSheet()" class="w-full btn-primary inline-flex items-center justify-center gap-2 text-white py-3.5 rounded-xl text-sm font-semibold active:scale-[0.98] transition-transform">
        <i data-lucide="plus" class="w-4 h-4"></i> New Ticket
    </button>

    @if($tickets->isEmpty())
        <div class="bg-white rounded-2xl p-8 text-center animate-fade-in">
            <div class="w-14 h-14 bg-surface-50 rounded-2xl flex items-center justify-center mx-auto mb-3">
                <i data-lucide="headphones" class="w-7 h-7 text-surface-300"></i>
            </div>
            <h3 class="text-base font-bold text-surface-900 mb-1">No support tickets</h3>
            <p class="text-xs text-surface-400 mb-5">Need help? Create a support ticket and we'll get back to you.</p>
            <button onclick="showTicketSheet()" class="btn-primary inline-flex items-center gap-2 text-white px-5 py-3 rounded-xl text-sm font-semibold active:scale-[0.98] transition-transform">
                <i data-lucide="plus" class="w-4 h-4"></i> Create Ticket
            </button>
        </div>
    @else
        <div class="space-y-2">
            @foreach($tickets as $index => $ticket)
                @php
                    $statusColors = [
                        'open' => 'bg-blue-50 text-blue-700',
                        'in_progress' => 'bg-amber-50 text-amber-700',
                        'waiting_customer' => 'bg-orange-50 text-orange-700',
                        'resolved' => 'bg-green-50 text-green-700',
                        'closed' => 'bg-surface-50 text-surface-500',
                    ];
                @endphp
                <a href="{{ route('customer.support.show', $ticket->ticket_number) }}"
                   class="block bg-white rounded-2xl p-4 border border-surface-100 active:scale-[0.98] transition-transform animate-slide-up opacity-0 stagger-{{ min($index + 1, 6) }}">
                    <div class="flex items-start justify-between gap-3">
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center gap-1.5 mb-1.5 flex-wrap">
                                <span class="px-2 py-0.5 rounded-md text-[10px] font-semibold {{ $statusColors[$ticket->status] ?? 'bg-surface-50 text-surface-500' }}">
                                    {{ ucfirst(str_replace('_', ' ', $ticket->status)) }}
                                </span>
                                <span class="text-[10px] text-surface-400">#{{ $ticket->ticket_number }}</span>
                            </div>
                            <p class="text-[13px] font-semibold text-surface-900 truncate">{{ $ticket->subject }}</p>
                            <p class="text-[10px] text-surface-400 mt-0.5">{{ $ticket->created_at->format('d M Y') }} · {{ ucfirst($ticket->category) }}</p>
                        </div>
                        <div class="text-right shrink-0 flex flex-col items-end gap-1">
                            <span class="text-[10px] font-medium text-surface-400">{{ $ticket->replies->count() }} repl{{ $ticket->replies->count() !== 1 ? 'ies' : 'y' }}</span>
                            <i data-lucide="chevron-right" class="w-4 h-4 text-surface-300"></i>
                        </div>
                    </div>
                </a>
            @endforeach
        </div>

        <div class="mt-2">{{ $tickets->links('pagination::tailwind') }}</div>
    @endif
</div>

{{-- New Ticket Bottom Sheet --}}
<div id="ticketSheet" class="fixed inset-0 bg-black/50 z-[9998] hidden items-end justify-center p-0">
    <div class="bg-white rounded-t-2xl w-full animate-slide-up-full shadow-2xl pb-safe max-h-[85vh] overflow-y-auto">
        <div class="sticky top-0 bg-white px-5 pt-5 pb-3 border-b border-surface-100 z-10">
            <div class="flex items-center justify-between mb-1">
                <h3 class="text-[15px] font-bold text-surface-900">New Support Ticket</h3>
                <button onclick="hideTicketSheet()" class="p-2 -mr-2 text-surface-400 active:text-surface-600 rounded-lg active:scale-[0.95] transition-transform">
                    <i data-lucide="x" class="w-5 h-5"></i>
                </button>
            </div>
        </div>
        <form action="{{ route('customer.support.store') }}" method="POST" class="p-5 space-y-3.5">
            @csrf
            <div>
                <label class="block text-[11px] font-medium text-surface-500 mb-1.5">Subject</label>
                <input type="text" name="subject" required placeholder="Brief description of your issue"
                       class="w-full px-3 py-3 bg-surface-50 border border-surface-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500">
            </div>
            <div>
                <label class="block text-[11px] font-medium text-surface-500 mb-1.5">Category</label>
                <select name="category" required class="w-full px-3 py-3 bg-surface-50 border border-surface-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500">
                    <option value="order_issue">Order Issue</option>
                    <option value="return_refund">Return/Refund</option>
                    <option value="payment">Payment</option>
                    <option value="delivery">Delivery</option>
                    <option value="product">Product</option>
                    <option value="account">Account</option>
                    <option value="other">Other</option>
                </select>
            </div>
            <div>
                <label class="block text-[11px] font-medium text-surface-500 mb-1.5">Priority</label>
                <select name="priority" required class="w-full px-3 py-3 bg-surface-50 border border-surface-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500">
                    <option value="low">Low</option>
                    <option value="medium" selected>Medium</option>
                    <option value="high">High</option>
                    <option value="urgent">Urgent</option>
                </select>
            </div>
            <div>
                <label class="block text-[11px] font-medium text-surface-500 mb-1.5">Message</label>
                <textarea name="message" rows="4" required placeholder="Describe your issue in detail..."
                          class="w-full px-3 py-3 bg-surface-50 border border-surface-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 resize-none"></textarea>
            </div>
            <div class="flex gap-3 pt-2 pb-4">
                <button type="button" onclick="hideTicketSheet()" class="flex-1 py-3.5 border border-surface-200 rounded-xl text-sm font-medium active:bg-surface-50 active:scale-[0.98] transition-transform">Cancel</button>
                <button type="submit" class="flex-1 btn-primary text-white py-3.5 rounded-xl text-sm font-semibold active:scale-[0.98] transition-transform">Submit</button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
function showTicketSheet() {
    const sheet = document.getElementById('ticketSheet');
    sheet.classList.remove('hidden');
    sheet.classList.add('flex');
}
function hideTicketSheet() {
    const sheet = document.getElementById('ticketSheet');
    sheet.classList.add('hidden');
    sheet.classList.remove('flex');
}
document.getElementById('ticketSheet').addEventListener('click', function(e) {
    if (e.target === this) hideTicketSheet();
});
</script>
@endpush
@endsection
