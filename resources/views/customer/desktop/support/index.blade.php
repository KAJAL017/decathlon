@extends('customer.layouts.desktop')

@section('title', 'Support')
@section('page-title', 'Support')

@section('content')
<div class="max-w-4xl">

    <div class="flex items-center justify-between mb-6">
        <p class="text-sm text-surface-400">{{ $tickets->total() }} ticket{{ $tickets->total() !== 1 ? 's' : '' }}</p>
        <button onclick="showTicketModal()" class="btn-primary inline-flex items-center gap-2 text-white px-5 py-2.5 rounded-xl text-sm font-semibold">
            <i data-lucide="plus" class="w-4 h-4"></i> New Ticket
        </button>
    </div>

    @if($tickets->isEmpty())
        <div class="bg-white rounded-2xl p-16 border border-surface-100 text-center animate-fade-in">
            <div class="w-20 h-20 bg-surface-50 rounded-2xl flex items-center justify-center mx-auto mb-5">
                <i data-lucide="headphones" class="w-10 h-10 text-surface-300"></i>
            </div>
            <h3 class="text-xl font-bold text-surface-900 mb-2">No support tickets</h3>
            <p class="text-sm text-surface-400 mb-6 max-w-sm mx-auto">Need help? Create a support ticket and our team will get back to you.</p>
            <button onclick="showTicketModal()" class="btn-primary inline-flex items-center gap-2 text-white px-6 py-3 rounded-xl text-sm font-semibold">
                <i data-lucide="plus" class="w-4 h-4"></i> Create Ticket
            </button>
        </div>
    @else
        <div class="space-y-3">
            @foreach($tickets as $index => $ticket)
                @php
                    $statusColors = [
                        'open' => 'bg-blue-50 text-blue-700 border-blue-200',
                        'in_progress' => 'bg-amber-50 text-amber-700 border-amber-200',
                        'waiting_customer' => 'bg-orange-50 text-orange-700 border-orange-200',
                        'resolved' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                        'closed' => 'bg-surface-100 text-surface-500 border-surface-200',
                    ];
                    $priorityColors = [
                        'low' => 'bg-surface-50 text-surface-500 border-surface-200',
                        'medium' => 'bg-blue-50 text-blue-600 border-blue-200',
                        'high' => 'bg-amber-50 text-amber-600 border-amber-200',
                        'urgent' => 'bg-red-50 text-red-600 border-red-200',
                    ];
                @endphp
                <a href="{{ route('customer.support.show', $ticket->ticket_number) }}"
                   class="block bg-white rounded-2xl p-5 border border-surface-100 hover:border-surface-200 hover:shadow-md transition-all group animate-slide-up opacity-0 stagger-{{ min($index + 1, 6) }}">
                    <div class="flex items-center gap-4">
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center gap-2 mb-2">
                                <span class="px-2.5 py-1 rounded-lg text-[11px] font-semibold border {{ $statusColors[$ticket->status] ?? 'bg-surface-50 text-surface-500 border-surface-200' }}">
                                    {{ ucfirst(str_replace('_', ' ', $ticket->status)) }}
                                </span>
                                <span class="px-2.5 py-1 rounded-lg text-[11px] font-semibold border {{ $priorityColors[$ticket->priority] ?? 'bg-surface-50 text-surface-500 border-surface-200' }}">
                                    {{ ucfirst($ticket->priority) }}
                                </span>
                                <span class="text-xs text-surface-400">#{{ $ticket->ticket_number }}</span>
                            </div>
                            <p class="text-sm font-semibold text-surface-900 group-hover:text-brand-700 transition-colors truncate">{{ $ticket->subject }}</p>
                            <p class="text-xs text-surface-400 mt-1">{{ $ticket->created_at->format('d M Y') }} · {{ ucfirst($ticket->category) }}</p>
                        </div>
                        <div class="text-right shrink-0 flex flex-col items-end gap-1">
                            <div class="flex items-center gap-1.5 text-xs text-surface-400">
                                <i data-lucide="message-circle" class="w-3.5 h-3.5"></i>
                                <span>{{ $ticket->replies->count() }}</span>
                            </div>
                            <i data-lucide="chevron-right" class="w-5 h-5 text-surface-300 group-hover:text-brand-500 transition-colors"></i>
                        </div>
                    </div>
                </a>
            @endforeach
        </div>

        <div class="mt-6">{{ $tickets->links('pagination::tailwind') }}</div>
    @endif
</div>

{{-- New Ticket Modal --}}
<div id="ticketModal" class="fixed inset-0 bg-black/50 z-[9998] hidden items-center justify-center p-4">
    <div class="bg-white rounded-2xl p-8 w-full max-w-lg animate-scale-in shadow-2xl max-h-[85vh] overflow-y-auto">
        <div class="flex items-center justify-between mb-6">
            <h3 class="text-lg font-bold text-surface-900">New Support Ticket</h3>
            <button onclick="hideTicketModal()" class="p-2 text-surface-400 hover:text-surface-600 rounded-xl hover:bg-surface-50 transition-colors"><i data-lucide="x" class="w-5 h-5"></i></button>
        </div>
        <form action="{{ route('customer.support.store') }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label class="block text-sm font-medium text-surface-600 mb-2">Subject</label>
                <input type="text" name="subject" required placeholder="Brief description of your issue"
                       class="w-full px-4 py-3 bg-surface-50 border border-surface-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition-all">
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-surface-600 mb-2">Category</label>
                    <select name="category" required class="w-full px-4 py-3 bg-surface-50 border border-surface-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition-all">
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
                    <label class="block text-sm font-medium text-surface-600 mb-2">Priority</label>
                    <select name="priority" required class="w-full px-4 py-3 bg-surface-50 border border-surface-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition-all">
                        <option value="low">Low</option>
                        <option value="medium" selected>Medium</option>
                        <option value="high">High</option>
                        <option value="urgent">Urgent</option>
                    </select>
                </div>
            </div>
            <div>
                <label class="block text-sm font-medium text-surface-600 mb-2">Message</label>
                <textarea name="message" rows="5" required placeholder="Describe your issue in detail..."
                          class="w-full px-4 py-3 bg-surface-50 border border-surface-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 resize-none transition-all"></textarea>
            </div>
            <div class="flex gap-3 pt-2">
                <button type="button" onclick="hideTicketModal()" class="flex-1 py-3 border border-surface-200 rounded-xl text-sm font-medium hover:bg-surface-50 transition-colors">Cancel</button>
                <button type="submit" class="flex-1 btn-primary text-white py-3 rounded-xl text-sm font-semibold">Submit Ticket</button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => lucide.createIcons());
function showTicketModal() {
    document.getElementById('ticketModal').classList.remove('hidden');
    document.getElementById('ticketModal').classList.add('flex');
}
function hideTicketModal() {
    document.getElementById('ticketModal').classList.add('hidden');
    document.getElementById('ticketModal').classList.remove('flex');
}
</script>
@endpush
@endsection
