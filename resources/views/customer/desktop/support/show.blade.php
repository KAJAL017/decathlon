@extends('customer.layouts.desktop')

@section('title', 'Ticket #' . $ticket->ticket_number)
@section('page-title', 'Support Ticket')

@section('content')
<div class="max-w-4xl space-y-6">

    <a href="{{ route('customer.support') }}" class="inline-flex items-center gap-2 text-sm font-medium text-surface-500 hover:text-surface-800 transition-colors">
        <i data-lucide="arrow-left" class="w-4 h-4"></i> Back to Support
    </a>

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

    {{-- Ticket Header --}}
    <div class="bg-white rounded-2xl p-6 border border-surface-100 animate-slide-up">
        <div class="flex items-start justify-between gap-4">
            <div class="min-w-0">
                <div class="flex items-center gap-2 mb-3 flex-wrap">
                    <span class="px-2.5 py-1 rounded-lg text-[11px] font-semibold border {{ $statusColors[$ticket->status] ?? '' }}">{{ ucfirst(str_replace('_', ' ', $ticket->status)) }}</span>
                    <span class="px-2.5 py-1 rounded-lg text-[11px] font-semibold border {{ $priorityColors[$ticket->priority] ?? '' }}">{{ ucfirst($ticket->priority) }}</span>
                    <span class="px-2.5 py-1 rounded-lg text-[11px] font-semibold bg-surface-50 text-surface-600 border border-surface-200">{{ ucfirst($ticket->category) }}</span>
                </div>
                <h2 class="text-lg font-bold text-surface-900">{{ $ticket->subject }}</h2>
                <p class="text-xs text-surface-400 mt-1">#{{ $ticket->ticket_number }} · Created {{ $ticket->created_at->format('d M Y, g:i A') }}</p>
            </div>
        </div>
    </div>

    {{-- Messages --}}
    <div class="space-y-4">
        {{-- Original message --}}
        <div class="bg-white rounded-2xl p-6 border border-surface-100 animate-slide-up stagger-2 opacity-0">
            <div class="flex items-start gap-4">
                <div class="w-11 h-11 rounded-full bg-brand-100 text-brand-700 flex items-center justify-center text-sm font-bold shrink-0">
                    {{ $ticket->customer->initials ?? 'U' }}
                </div>
                <div class="flex-1 min-w-0">
                    <div class="flex items-center gap-2.5 flex-wrap">
                        <p class="text-sm font-semibold text-surface-900">{{ $ticket->customer->name ?? 'You' }}</p>
                        <p class="text-xs text-surface-400">{{ $ticket->created_at->format('d M, g:i A') }}</p>
                    </div>
                    <div class="mt-3 text-sm text-surface-600 leading-relaxed whitespace-pre-wrap">{{ $ticket->message }}</div>
                </div>
            </div>
        </div>

        {{-- Replies --}}
        @foreach($ticket->replies as $reply)
            <div class="bg-white rounded-2xl p-6 border border-surface-100 animate-fade-in {{ $reply->is_staff ? 'border-l-4 border-l-blue-400' : '' }}">
                <div class="flex items-start gap-4">
                    <div class="w-11 h-11 rounded-full flex items-center justify-center text-sm font-bold shrink-0 {{ $reply->is_staff ? 'bg-blue-100 text-blue-700' : 'bg-brand-100 text-brand-700' }}">
                        @if($reply->is_staff)
                            <i data-lucide="headphones" class="w-5 h-5"></i>
                        @else
                            {{ $reply->customer->initials ?? 'U' }}
                        @endif
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center gap-2.5 flex-wrap">
                            <p class="text-sm font-semibold text-surface-900">{{ $reply->is_staff ? 'Support Team' : ($reply->customer->name ?? 'You') }}</p>
                            @if($reply->is_staff)
                                <span class="px-2 py-0.5 bg-blue-50 text-blue-700 rounded-md text-[10px] font-semibold border border-blue-200">Staff</span>
                            @endif
                            <p class="text-xs text-surface-400">{{ $reply->created_at->format('d M, g:i A') }}</p>
                        </div>
                        <div class="mt-3 text-sm text-surface-600 leading-relaxed whitespace-pre-wrap">{{ $reply->message }}</div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    {{-- Reply Form --}}
    @if(!in_array($ticket->status, ['resolved', 'closed']))
        <div class="bg-white rounded-2xl p-6 border border-surface-100 animate-slide-up stagger-4 opacity-0">
            <h3 class="text-sm font-bold text-surface-900 mb-4 flex items-center gap-2">
                <i data-lucide="message-circle" class="w-4 h-4 text-brand-600"></i> Reply
            </h3>
            <form action="{{ route('customer.support.reply', $ticket->ticket_number) }}" method="POST">
                @csrf
                <textarea name="message" rows="4" required placeholder="Type your reply..."
                          class="w-full px-4 py-3 bg-surface-50 border border-surface-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 resize-none transition-all mb-4"></textarea>
                <button type="submit" class="btn-primary text-white px-6 py-2.5 rounded-xl text-sm font-semibold">Send Reply</button>
            </form>
        </div>
    @else
        <div class="bg-surface-50 rounded-2xl p-6 border border-surface-200 text-center">
            <p class="text-sm text-surface-500 font-medium">This ticket is {{ $ticket->status === 'resolved' ? 'resolved' : 'closed' }}. No further replies can be sent.</p>
        </div>
    @endif
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => lucide.createIcons());
</script>
@endpush
@endsection
