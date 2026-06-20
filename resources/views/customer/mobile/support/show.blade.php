@extends('customer.layouts.mobile')

@section('title', 'Ticket #' . $ticket->ticket_number)
@section('page-title', 'Ticket #' . $ticket->ticket_number)

@section('mobile-back')
    <a href="{{ route('customer.support') }}" class="p-2 -ml-2 text-surface-500 active:text-surface-800 active:scale-95 transition-transform">
        <i data-lucide="arrow-left" class="w-5 h-5"></i>
    </a>
@endsection

@section('content')
<div class="space-y-3">

    {{-- Ticket Header --}}
    @php
        $statusColors = [
            'open' => 'bg-blue-50 text-blue-700',
            'in_progress' => 'bg-amber-50 text-amber-700',
            'waiting_customer' => 'bg-orange-50 text-orange-700',
            'resolved' => 'bg-green-50 text-green-700',
            'closed' => 'bg-surface-50 text-surface-500',
        ];
    @endphp

    <div class="bg-white rounded-2xl p-4 border border-surface-100 animate-slide-up">
        <div class="flex items-center gap-1.5 mb-2 flex-wrap">
            <span class="px-2 py-0.5 rounded-md text-[10px] font-semibold {{ $statusColors[$ticket->status] ?? '' }}">{{ ucfirst(str_replace('_', ' ', $ticket->status)) }}</span>
            <span class="px-2 py-0.5 rounded-md text-[10px] font-semibold bg-surface-50 text-surface-600">{{ ucfirst($ticket->priority) }}</span>
            <span class="text-[10px] text-surface-400">{{ ucfirst($ticket->category) }}</span>
        </div>
        <h2 class="text-[15px] font-bold text-surface-900 leading-snug">{{ $ticket->subject }}</h2>
        <p class="text-[10px] text-surface-400 mt-1">Created {{ $ticket->created_at->format('d M Y, g:i A') }}</p>
    </div>

    {{-- Messages --}}
    <div class="space-y-2.5">
        {{-- Original message --}}
        <div class="bg-white rounded-2xl p-4 border border-surface-100 animate-slide-up stagger-2 opacity-0">
            <div class="flex items-start gap-3">
                <div class="w-8 h-8 rounded-full bg-brand-100 text-brand-700 flex items-center justify-center text-[10px] font-bold shrink-0">
                    {{ $ticket->customer->initials ?? 'U' }}
                </div>
                <div class="flex-1 min-w-0">
                    <div class="flex items-center gap-2 flex-wrap">
                        <p class="text-[13px] font-semibold text-surface-900">{{ $ticket->customer->name ?? 'You' }}</p>
                        <p class="text-[10px] text-surface-400">{{ $ticket->created_at->format('d M, g:i A') }}</p>
                    </div>
                    <p class="text-[13px] text-surface-600 mt-2 whitespace-pre-wrap leading-relaxed">{{ $ticket->message }}</p>
                </div>
            </div>
        </div>

        {{-- Replies --}}
        @foreach($ticket->replies as $reply)
            <div class="bg-white rounded-2xl p-4 border border-surface-100 animate-fade-in {{ $reply->is_staff ? 'border-l-[3px] border-l-blue-400' : '' }}">
                <div class="flex items-start gap-3">
                    <div class="w-8 h-8 rounded-full flex items-center justify-center text-[10px] font-bold shrink-0 {{ $reply->is_staff ? 'bg-blue-100 text-blue-700' : 'bg-brand-100 text-brand-700' }}">
                        {{ $reply->is_staff ? 'S' : ($reply->customer->initials ?? 'U') }}
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center gap-2 flex-wrap">
                            <p class="text-[13px] font-semibold text-surface-900">{{ $reply->is_staff ? 'Support Team' : ($reply->customer->name ?? 'You') }}</p>
                            @if($reply->is_staff)
                                <span class="px-1.5 py-0.5 bg-blue-50 text-blue-700 rounded text-[9px] font-semibold">Staff</span>
                            @endif
                            <p class="text-[10px] text-surface-400">{{ $reply->created_at->format('d M, g:i A') }}</p>
                        </div>
                        <p class="text-[13px] text-surface-600 mt-2 whitespace-pre-wrap leading-relaxed">{{ $reply->message }}</p>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    {{-- Reply Form --}}
    @if(!in_array($ticket->status, ['resolved', 'closed']))
        <div class="bg-white rounded-2xl p-4 border border-surface-100 animate-slide-up stagger-4 opacity-0">
            <h3 class="text-[13px] font-bold text-surface-900 mb-3">Reply</h3>
            <form action="{{ route('customer.support.reply', $ticket->ticket_number) }}" method="POST">
                @csrf
                <textarea name="message" rows="3" required placeholder="Type your reply..."
                          class="w-full px-3 py-3 bg-surface-50 border border-surface-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 resize-none mb-3"></textarea>
                <button type="submit" class="w-full btn-primary text-white py-3.5 rounded-xl text-sm font-semibold active:scale-[0.98] transition-transform">
                    Send Reply
                </button>
            </form>
        </div>
    @endif
</div>
@endsection
