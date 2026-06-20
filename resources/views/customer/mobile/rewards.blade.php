@extends('customer.layouts.mobile')

@section('title', 'Reward Points')
@section('page-title', 'Rewards')

@section('content')
<div class="space-y-3">

    {{-- Points Card --}}
    <div class="bg-gradient-to-br from-purple-600 via-purple-700 to-indigo-700 rounded-2xl p-5 text-white relative overflow-hidden animate-slide-up">
        <div class="absolute -right-10 -top-10 w-32 h-32 bg-white/10 rounded-full"></div>
        <div class="relative z-10">
            <p class="text-purple-200 text-[11px] font-medium mb-1">Your Points Balance</p>
            <p class="text-3xl font-bold mb-0.5">{{ number_format($rewards->points_balance) }}</p>
            <p class="text-purple-200 text-[11px]">≈ ₹{{ number_format($rewards->points_balance * 0.10, 2) }} value</p>
        </div>
    </div>

    {{-- Stats --}}
    <div class="grid grid-cols-3 gap-2">
        <div class="bg-white rounded-2xl p-3 border border-surface-100 text-center animate-slide-up stagger-2 opacity-0">
            <p class="text-[9px] text-surface-400 font-medium">Earned</p>
            <p class="text-sm font-bold text-green-600 mt-0.5">{{ number_format($rewards->total_earned) }}</p>
        </div>
        <div class="bg-white rounded-2xl p-3 border border-surface-100 text-center animate-slide-up stagger-3 opacity-0">
            <p class="text-[9px] text-surface-400 font-medium">Redeemed</p>
            <p class="text-sm font-bold text-purple-600 mt-0.5">{{ number_format($rewards->total_redeemed) }}</p>
        </div>
        <div class="bg-white rounded-2xl p-3 border border-surface-100 text-center animate-slide-up stagger-4 opacity-0">
            <p class="text-[9px] text-surface-400 font-medium">Expired</p>
            <p class="text-sm font-bold text-surface-500 mt-0.5">{{ number_format($rewards->total_expired) }}</p>
        </div>
    </div>

    {{-- How It Works --}}
    <div class="bg-white rounded-2xl p-4 border border-surface-100 animate-slide-up stagger-3 opacity-0">
        <h3 class="text-xs font-bold text-surface-900 mb-3">How It Works</h3>
        <div class="grid grid-cols-3 gap-2.5">
            <div class="text-center">
                <div class="w-10 h-10 bg-green-50 rounded-xl flex items-center justify-center mx-auto mb-1.5">
                    <i data-lucide="shopping-bag" class="w-5 h-5 text-green-600"></i>
                </div>
                <p class="text-[10px] font-semibold text-surface-800">Shop & Earn</p>
                <p class="text-[9px] text-surface-400 mt-0.5">1 pt / ₹10</p>
            </div>
            <div class="text-center">
                <div class="w-10 h-10 bg-purple-50 rounded-xl flex items-center justify-center mx-auto mb-1.5">
                    <i data-lucide="gift" class="w-5 h-5 text-purple-600"></i>
                </div>
                <p class="text-[10px] font-semibold text-surface-800">Accumulate</p>
                <p class="text-[9px] text-surface-400 mt-0.5">Valid 1 year</p>
            </div>
            <div class="text-center">
                <div class="w-10 h-10 bg-amber-50 rounded-xl flex items-center justify-center mx-auto mb-1.5">
                    <i data-lucide="zap" class="w-5 h-5 text-amber-600"></i>
                </div>
                <p class="text-[10px] font-semibold text-surface-800">Redeem</p>
                <p class="text-[9px] text-surface-400 mt-0.5">100 pts = ₹10</p>
            </div>
        </div>
    </div>

    {{-- Redeem --}}
    <div class="bg-white rounded-2xl p-4 border border-surface-100 animate-slide-up stagger-4 opacity-0">
        <h3 class="text-xs font-bold text-surface-900 mb-3 flex items-center gap-2">
            <i data-lucide="ticket" class="w-4 h-4 text-purple-600"></i> Redeem Points
        </h3>
        <form action="{{ route('customer.rewards.redeem') }}" method="POST" class="space-y-3">
            @csrf
            <div>
                <label class="block text-[11px] font-medium text-surface-500 mb-1.5">Points to Redeem</label>
                <input type="number" name="points" min="1" max="{{ $rewards->points_balance }}" required placeholder="e.g. 100"
                       class="w-full px-4 py-3 bg-surface-50 border border-surface-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500">
            </div>
            <div>
                <label class="block text-[11px] font-medium text-surface-500 mb-1.5">Purpose</label>
                <input type="text" name="purpose" required placeholder="e.g. Discount on order"
                       class="w-full px-4 py-3 bg-surface-50 border border-surface-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500">
            </div>
            <button type="submit" class="w-full btn-primary py-3.5 text-white rounded-xl text-sm font-semibold active:scale-[0.98] transition-transform">
                Redeem Points
            </button>
        </form>
    </div>

    {{-- History --}}
    <div class="animate-slide-up stagger-5 opacity-0">
        <h3 class="text-xs font-bold text-surface-900 mb-2.5">Points History</h3>
        @if($transactions->isEmpty())
            <div class="bg-white rounded-2xl p-8 border border-surface-100 text-center">
                <div class="w-12 h-12 bg-surface-50 rounded-xl flex items-center justify-center mx-auto mb-2">
                    <i data-lucide="history" class="w-6 h-6 text-surface-300"></i>
                </div>
                <p class="text-surface-400 text-xs">No transactions yet</p>
            </div>
        @else
            <div class="bg-white rounded-2xl border border-surface-100 divide-y divide-surface-50">
                @foreach($transactions as $tx)
                    <div class="p-3.5 flex items-center gap-3">
                        <div class="w-9 h-9 rounded-xl flex items-center justify-center {{ $tx->type === 'earned' ? 'bg-green-50 text-green-600' : 'bg-purple-50 text-purple-600' }}">
                            <i data-lucide="{{ $tx->type === 'earned' ? 'plus' : 'minus' }}" class="w-4 h-4"></i>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-xs font-medium text-surface-800 truncate">{{ $tx->description ?? ucfirst($tx->type) }}</p>
                            <p class="text-[10px] text-surface-400">{{ $tx->created_at->format('d M Y') }}</p>
                        </div>
                        <p class="text-xs font-bold {{ $tx->type === 'earned' ? 'text-green-600' : 'text-purple-600' }} shrink-0">
                            {{ $tx->type === 'earned' ? '+' : '-' }}{{ number_format($tx->points) }}
                        </p>
                    </div>
                @endforeach
            </div>
            <div class="mt-3">{{ $transactions->links('pagination::tailwind') }}</div>
        @endif
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => lucide.createIcons());
</script>
@endpush
@endsection
