@extends('customer.layouts.desktop')

@section('title', 'Reward Points')
@section('page-title', 'Rewards')

@section('content')
<div class="max-w-5xl">

    <div class="grid grid-cols-3 gap-6 mb-8">

        {{-- Points Card --}}
        <div class="col-span-2 bg-gradient-to-br from-purple-600 via-purple-700 to-indigo-700 rounded-2xl p-8 text-white relative overflow-hidden animate-slide-up">
            <div class="absolute -right-12 -top-12 w-44 h-44 bg-white/10 rounded-full"></div>
            <div class="absolute right-32 -bottom-8 w-28 h-28 bg-white/5 rounded-full"></div>
            <div class="relative z-10">
                <p class="text-purple-200 text-sm font-medium mb-1">Your Points Balance</p>
                <p class="text-5xl font-bold mb-1">{{ number_format($rewards->points_balance) }}</p>
                <p class="text-purple-200 text-sm">≈ ₹{{ number_format($rewards->points_balance * 0.10, 2) }} value</p>
            </div>
        </div>

        {{-- Quick Stats --}}
        <div class="space-y-4">
            <div class="bg-white rounded-2xl p-5 border border-surface-100 animate-slide-up stagger-2 opacity-0">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-green-50 flex items-center justify-center">
                        <i data-lucide="trending-up" class="w-5 h-5 text-green-600"></i>
                    </div>
                    <div>
                        <p class="text-[11px] text-surface-400 font-medium">Earned</p>
                        <p class="text-lg font-bold text-green-600">{{ number_format($rewards->total_earned) }}</p>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-2xl p-5 border border-surface-100 animate-slide-up stagger-3 opacity-0">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-purple-50 flex items-center justify-center">
                        <i data-lucide="gift" class="w-5 h-5 text-purple-600"></i>
                    </div>
                    <div>
                        <p class="text-[11px] text-surface-400 font-medium">Redeemed</p>
                        <p class="text-lg font-bold text-purple-600">{{ number_format($rewards->total_redeemed) }}</p>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-2xl p-5 border border-surface-100 animate-slide-up stagger-4 opacity-0">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-surface-50 flex items-center justify-center">
                        <i data-lucide="clock" class="w-5 h-5 text-surface-500"></i>
                    </div>
                    <div>
                        <p class="text-[11px] text-surface-400 font-medium">Expired</p>
                        <p class="text-lg font-bold text-surface-500">{{ number_format($rewards->total_expired) }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- How It Works --}}
    <div class="bg-white rounded-2xl p-8 border border-surface-100 mb-8 animate-slide-up stagger-3 opacity-0">
        <h3 class="text-base font-bold text-surface-900 mb-6">How It Works</h3>
        <div class="grid grid-cols-3 gap-8">
            <div class="text-center relative">
                <div class="w-14 h-14 bg-green-50 rounded-2xl flex items-center justify-center mx-auto mb-3">
                    <i data-lucide="shopping-bag" class="w-7 h-7 text-green-600"></i>
                </div>
                <p class="text-sm font-bold text-surface-800">Shop & Earn</p>
                <p class="text-xs text-surface-400 mt-1">Earn 1 point for every ₹10 spent</p>
                <div class="absolute top-7 -right-4 w-8 border-t border-dashed border-surface-200 hidden lg:block"></div>
            </div>
            <div class="text-center relative">
                <div class="w-14 h-14 bg-purple-50 rounded-2xl flex items-center justify-center mx-auto mb-3">
                    <i data-lucide="layers" class="w-7 h-7 text-purple-600"></i>
                </div>
                <p class="text-sm font-bold text-surface-800">Accumulate</p>
                <p class="text-xs text-surface-400 mt-1">Points valid for 1 year</p>
                <div class="absolute top-7 -right-4 w-8 border-t border-dashed border-surface-200 hidden lg:block"></div>
            </div>
            <div class="text-center">
                <div class="w-14 h-14 bg-amber-50 rounded-2xl flex items-center justify-center mx-auto mb-3">
                    <i data-lucide="zap" class="w-7 h-7 text-amber-600"></i>
                </div>
                <p class="text-sm font-bold text-surface-800">Redeem</p>
                <p class="text-xs text-surface-400 mt-1">100 points = ₹10 discount</p>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-3 gap-6">

        {{-- Redeem Form --}}
        <div class="col-span-1">
            <div class="bg-white rounded-2xl p-6 border border-surface-100 sticky top-24 animate-slide-up stagger-4 opacity-0">
                <div class="flex items-center gap-3 mb-5">
                    <div class="w-10 h-10 bg-amber-50 rounded-xl flex items-center justify-center">
                        <i data-lucide="ticket" class="w-5 h-5 text-amber-600"></i>
                    </div>
                    <h3 class="text-sm font-bold text-surface-900">Redeem Points</h3>
                </div>
                <form action="{{ route('customer.rewards.redeem') }}" method="POST" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-xs font-medium text-surface-500 mb-1.5">Points to Redeem</label>
                        <input type="number" name="points" min="1" max="{{ $rewards->points_balance }}" required placeholder="e.g. 100"
                               class="w-full px-4 py-2.5 bg-surface-50 border border-surface-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition-all">
                        <p class="text-[11px] text-surface-400 mt-1">Available: {{ number_format($rewards->points_balance) }} pts</p>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-surface-500 mb-1.5">Purpose</label>
                        <input type="text" name="purpose" required placeholder="e.g. Discount on order"
                               class="w-full px-4 py-2.5 bg-surface-50 border border-surface-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition-all">
                    </div>
                    <button type="submit" class="btn-primary text-white px-6 py-2.5 rounded-xl text-sm font-semibold w-full">Redeem Points</button>
                </form>
            </div>
        </div>

        {{-- Points History --}}
        <div class="col-span-2 animate-slide-up stagger-5 opacity-0">
            <h3 class="text-base font-bold text-surface-900 mb-4">Points History</h3>
            @if($transactions->isEmpty())
                <div class="bg-white rounded-2xl p-12 border border-surface-100 text-center">
                    <div class="w-16 h-16 bg-surface-50 rounded-2xl flex items-center justify-center mx-auto mb-4">
                        <i data-lucide="history" class="w-8 h-8 text-surface-300"></i>
                    </div>
                    <p class="text-surface-400 text-sm">No transactions yet</p>
                </div>
            @else
                <div class="bg-white rounded-2xl border border-surface-100 overflow-hidden">
                    <table class="w-full">
                        <thead>
                            <tr class="border-b border-surface-100">
                                <th class="text-left px-6 py-3 text-[11px] font-semibold text-surface-400 uppercase tracking-wider">Transaction</th>
                                <th class="text-left px-6 py-3 text-[11px] font-semibold text-surface-400 uppercase tracking-wider">Date</th>
                                <th class="text-right px-6 py-3 text-[11px] font-semibold text-surface-400 uppercase tracking-wider">Points</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-surface-50">
                            @foreach($transactions as $tx)
                                <tr class="hover:bg-surface-50/50 transition-colors">
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-3">
                                            <div class="w-10 h-10 rounded-xl flex items-center justify-center {{ $tx->type === 'earned' ? 'bg-green-50 text-green-600' : 'bg-purple-50 text-purple-600' }}">
                                                <i data-lucide="{{ $tx->type === 'earned' ? 'plus' : 'minus' }}" class="w-5 h-5"></i>
                                            </div>
                                            <div>
                                                <p class="text-sm font-medium text-surface-800">{{ $tx->description ?? ucfirst($tx->type) }}</p>
                                                <p class="text-[11px] text-surface-400 capitalize">{{ $tx->type }}</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <p class="text-sm text-surface-500">{{ $tx->created_at->format('d M Y') }}</p>
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        <span class="text-sm font-bold {{ $tx->type === 'earned' ? 'text-green-600' : 'text-purple-600' }}">
                                            {{ $tx->type === 'earned' ? '+' : '-' }}{{ number_format($tx->points) }}
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="mt-6">{{ $transactions->links('pagination::tailwind') }}</div>
            @endif
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => lucide.createIcons());
</script>
@endpush
@endsection