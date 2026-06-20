<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'My Account') - Decathlon</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: { sans: ['Inter', 'sans-serif'] },
                    colors: {
                        brand: { 50:'#f0fdf4', 100:'#dcfce7', 200:'#bbf7d0', 300:'#86efac', 400:'#4ade80', 500:'#22c55e', 600:'#0082C3', 700:'#006699', 800:'#004d73', 900:'#00334d' },
                        surface: { 50:'#fafafa', 100:'#f5f5f5', 200:'#e5e5e5', 300:'#d4d4d4', 400:'#a3a3a3', 500:'#737373', 600:'#525252', 700:'#404040', 800:'#262626', 900:'#171717' },
                    },
                },
            },
        }
    </script>

    <style>
        * { box-sizing: border-box; }
        body { font-family: 'Inter', sans-serif; margin: 0; }
        .scrollbar-thin::-webkit-scrollbar { width: 4px; }
        .scrollbar-thin::-webkit-scrollbar-track { background: transparent; }
        .scrollbar-thin::-webkit-scrollbar-thumb { background: #d4d4d4; border-radius: 999px; }
        .scrollbar-thin::-webkit-scrollbar-thumb:hover { background: #a3a3a3; }
        @keyframes slideUp { from { opacity:0; transform:translateY(20px); } to { opacity:1; transform:translateY(0); } }
        @keyframes slideDown { from { opacity:0; transform:translateY(-10px); } to { opacity:1; transform:translateY(0); } }
        @keyframes fadeIn { from { opacity:0; } to { opacity:1; } }
        @keyframes scaleIn { from { opacity:0; transform:scale(0.95); } to { opacity:1; transform:scale(1); } }
        .animate-slide-up { animation: slideUp 0.4s cubic-bezier(0.16,1,0.3,1) forwards; }
        .animate-slide-down { animation: slideDown 0.3s cubic-bezier(0.16,1,0.3,1) forwards; }
        .animate-fade-in { animation: fadeIn 0.3s ease forwards; }
        .animate-scale-in { animation: scaleIn 0.2s cubic-bezier(0.16,1,0.3,1) forwards; }
        .stagger-1 { animation-delay: 0.05s; } .stagger-2 { animation-delay: 0.1s; }
        .stagger-3 { animation-delay: 0.15s; } .stagger-4 { animation-delay: 0.2s; }
        .stagger-5 { animation-delay: 0.25s; } .stagger-6 { animation-delay: 0.3s; }
        .card-hover { transition: all 0.2s cubic-bezier(0.16,1,0.3,1); }
        .card-hover:hover { transform: translateY(-2px); box-shadow: 0 8px 30px rgba(0,0,0,0.08); }
        .btn-primary { background: linear-gradient(135deg, #0082C3 0%, #006699 100%); transition: all 0.2s ease; }
        .btn-primary:hover { background: linear-gradient(135deg, #006699 0%, #004d73 100%); transform: translateY(-1px); box-shadow: 0 4px 12px rgba(0,130,195,0.3); }
        .btn-primary:active { transform: translateY(0); }
        .sidebar-link { transition: all 0.15s ease; position: relative; }
        .sidebar-link:hover { background: rgba(0,0,0,0.03); }
        .sidebar-link.active { background: rgba(0,130,195,0.08); color: #0082C3; font-weight: 600; }
        .sidebar-link.active::before { content:''; position:absolute; left:0; top:6px; bottom:6px; width:3px; background:#0082C3; border-radius:0 3px 3px 0; }
        .profile-dropdown { opacity:0; visibility:hidden; transform:translateY(8px) scale(0.97); transition:all 0.2s cubic-bezier(0.16,1,0.3,1); }
        .profile-trigger:hover .profile-dropdown { opacity:1; visibility:visible; transform:translateY(0) scale(1); }
        .toast-container { position:fixed; top:20px; right:20px; z-index:9999; display:flex; flex-direction:column; gap:8px; }
        .toast { padding:12px 20px; border-radius:12px; font-size:14px; font-weight:500; box-shadow:0 10px 40px rgba(0,0,0,0.15); transform:translateX(120%); transition:all 0.3s cubic-bezier(0.16,1,0.3,1); }
        .toast.show { transform:translateX(0); }
        .toast-success { background:#166534; color:white; }
        .toast-error { background:#dc2626; color:white; }
        .toast-info { background:#1e40af; color:white; }
    </style>
    @stack('styles')
</head>
<body class="bg-surface-50 text-surface-900 antialiased min-h-screen">
@php
    $customer = Auth::guard('customer')->user();
    $unreadCount = $customer->notifications_panel()->unread()->count() ?? 0;
    $wishlistCount = $customer->wishlist()->count() ?? 0;
@endphp

{{-- ═══════════ DESKTOP SIDEBAR ═══════════ --}}
<aside class="fixed top-0 left-0 h-full bg-white border-r border-surface-200 z-40 flex flex-col" style="width:250px;">
    <div class="px-5 h-16 flex items-center border-b border-surface-100">
        <a href="{{ route('home') }}" class="flex items-center gap-2.5">
            <svg width="28" height="28" viewBox="0 0 38 38" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M25.5 4C19.5 4 14.5 7.5 11.5 12.5L2 28h6.5l12-15c2-2.5 4-3.8 6.5-3.8 4.5 0 6.5 2.8 6.5 6.8 0 4.8-2.5 8.8-6.5 13h8c4.5-4.8 7.5-10 7.5-16 0-5.8-3.5-9-8.5-9-1.8 0-3.8.4-5.5.8z" fill="#0082C3"/>
            </svg>
            <div>
                <span style="font-family:'Arial Black',Arial,sans-serif; font-style:italic; font-weight:900; font-size:14px; letter-spacing:-0.5px; color:#1a1a1a; line-height:1;">DECATHLON</span>
                <p class="text-[9px] text-surface-400 font-semibold tracking-widest uppercase mt-0.5">My Account</p>
            </div>
        </a>
    </div>

    <nav class="flex-1 overflow-y-auto py-4 px-3 scrollbar-thin">
        @php
            $navItems = [
                ['route' => 'customer.dashboard', 'label' => 'Dashboard', 'icon' => 'layout-dashboard'],
                ['route' => 'customer.orders', 'label' => 'My Orders', 'icon' => 'package'],
                ['route' => 'customer.wishlist', 'label' => 'Wishlist', 'icon' => 'heart'],
                ['route' => 'customer.coupons', 'label' => 'Coupons', 'icon' => 'ticket'],
                ['route' => 'customer.rewards', 'label' => 'Rewards', 'icon' => 'star'],
                ['route' => 'customer.notifications', 'label' => 'Notifications', 'icon' => 'bell'],
                ['route' => 'customer.addresses', 'label' => 'Addresses', 'icon' => 'map-pin'],
                ['route' => 'customer.profile', 'label' => 'Profile', 'icon' => 'user'],
                ['route' => 'customer.settings', 'label' => 'Settings', 'icon' => 'settings'],
                ['route' => 'customer.support', 'label' => 'Support', 'icon' => 'headphones'],
            ];
        @endphp
        <div class="space-y-0.5">
            @foreach($navItems as $item)
                @php $isActive = request()->routeIs($item['route'] . '*'); @endphp
                <a href="{{ route($item['route']) }}" class="sidebar-link flex items-center gap-3 px-3 py-2.5 rounded-xl text-[13px] {{ $isActive ? 'active' : 'text-surface-500 hover:text-surface-800' }}">
                    <i data-lucide="{{ $item['icon'] }}" class="w-[18px] h-[18px] {{ $isActive ? 'text-brand-600' : '' }}"></i>
                    <span>{{ $item['label'] }}</span>
                    @if($item['route'] === 'customer.notifications' && $unreadCount > 0)
                        <span class="ml-auto bg-red-500 text-white text-[9px] font-bold px-1.5 py-0.5 rounded-full min-w-[18px] text-center">{{ $unreadCount }}</span>
                    @endif
                </a>
            @endforeach
        </div>
        <div class="my-3 mx-3 border-t border-surface-100"></div>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="w-full flex items-center gap-3 px-3 py-2.5 rounded-xl text-[13px] text-surface-500 hover:text-red-600 hover:bg-red-50 transition-colors">
                <i data-lucide="log-out" class="w-[18px] h-[18px]"></i>
                <span>Logout</span>
            </button>
        </form>
    </nav>

    <div class="p-3 border-t border-surface-100">
        <a href="{{ route('customer.profile') }}" class="flex items-center gap-3 p-2 rounded-xl hover:bg-surface-50 transition-colors">
            <div class="w-9 h-9 rounded-full bg-brand-100 text-brand-700 flex items-center justify-center text-xs font-bold shrink-0">{{ $customer->initials }}</div>
            <div class="flex-1 min-w-0">
                <p class="text-sm font-semibold text-surface-900 truncate leading-tight">{{ $customer->name }}</p>
                <p class="text-[10px] text-surface-400 truncate leading-tight mt-0.5">{{ $customer->email }}</p>
            </div>
        </a>
    </div>
</aside>

{{-- ═══════════ DESKTOP HEADER ═══════════ --}}
<header class="fixed top-0 left-0 right-0 z-50 bg-white border-b border-surface-200" style="margin-left:250px;">
    <div class="flex items-center justify-between h-16 px-6">
        <div class="flex items-center gap-4">
            <h1 class="text-lg font-bold text-surface-900">@yield('page-title', 'Dashboard')</h1>
            @hasSection('page-subtitle')
                <span class="text-sm text-surface-400 hidden xl:inline">·</span>
                <p class="text-sm text-surface-400 hidden xl:inline">@yield('page-subtitle')</p>
            @endif
        </div>
        <div class="flex items-center gap-2">
            <div class="relative hidden xl:block">
                <input type="text" placeholder="Search orders, products..." class="w-72 pl-10 pr-4 py-2 bg-surface-50 border border-surface-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition-all">
                <i data-lucide="search" class="absolute left-3.5 top-1/2 -translate-y-1/2 w-4 h-4 text-surface-400"></i>
            </div>
            <a href="{{ route('customer.wishlist') }}" class="relative p-2.5 text-surface-500 hover:text-brand-600 hover:bg-brand-50 rounded-xl transition-all">
                <i data-lucide="heart" class="w-5 h-5"></i>
                @if($wishlistCount > 0)
                    <span class="absolute -top-0.5 -right-0.5 bg-red-500 text-white text-[9px] font-bold w-4 h-4 rounded-full flex items-center justify-center border-2 border-white">{{ $wishlistCount > 9 ? '9+' : $wishlistCount }}</span>
                @endif
            </a>
            <a href="{{ route('cart') }}" class="relative p-2.5 text-surface-500 hover:text-brand-600 hover:bg-brand-50 rounded-xl transition-all">
                <i data-lucide="shopping-cart" class="w-5 h-5"></i>
            </a>
            <a href="{{ route('customer.notifications') }}" class="relative p-2.5 text-surface-500 hover:text-brand-600 hover:bg-brand-50 rounded-xl transition-all">
                <i data-lucide="bell" class="w-5 h-5"></i>
                @if($unreadCount > 0)
                    <span class="absolute -top-0.5 -right-0.5 bg-red-500 text-white text-[9px] font-bold w-4 h-4 rounded-full flex items-center justify-center border-2 border-white">{{ $unreadCount > 9 ? '9+' : $unreadCount }}</span>
                @endif
            </a>
            <div class="w-px h-8 bg-surface-200 mx-1"></div>
            <div class="relative profile-trigger">
                <button class="flex items-center gap-3 py-1.5 px-2 rounded-xl hover:bg-surface-50 transition-colors">
                    <div class="w-8 h-8 rounded-full bg-brand-100 text-brand-700 flex items-center justify-center text-xs font-bold">{{ $customer->initials }}</div>
                    <div class="text-left hidden xl:block">
                        <p class="text-sm font-semibold text-surface-900 leading-tight">{{ $customer->first_name }}</p>
                        <p class="text-[10px] text-surface-400 leading-tight">{{ $customer->email }}</p>
                    </div>
                    <i data-lucide="chevron-down" class="w-4 h-4 text-surface-400 hidden xl:block"></i>
                </button>
                <div class="profile-dropdown absolute top-full right-0 mt-2 w-56 bg-white border border-surface-200 rounded-2xl shadow-2xl overflow-hidden">
                    <div class="p-4 bg-surface-50 border-b border-surface-100">
                        <p class="text-sm font-bold text-surface-900">{{ $customer->name }}</p>
                        <p class="text-xs text-surface-400 truncate">{{ $customer->email }}</p>
                    </div>
                    <div class="p-2">
                        <a href="{{ route('customer.dashboard') }}" class="flex items-center gap-3 px-3 py-2.5 text-sm text-surface-600 hover:bg-surface-50 rounded-xl transition-colors"><i data-lucide="layout-dashboard" class="w-4 h-4"></i> Dashboard</a>
                        <a href="{{ route('customer.orders') }}" class="flex items-center gap-3 px-3 py-2.5 text-sm text-surface-600 hover:bg-surface-50 rounded-xl transition-colors"><i data-lucide="package" class="w-4 h-4"></i> My Orders</a>
                        <a href="{{ route('customer.profile') }}" class="flex items-center gap-3 px-3 py-2.5 text-sm text-surface-600 hover:bg-surface-50 rounded-xl transition-colors"><i data-lucide="user" class="w-4 h-4"></i> Profile</a>
                        <a href="{{ route('customer.settings') }}" class="flex items-center gap-3 px-3 py-2.5 text-sm text-surface-600 hover:bg-surface-50 rounded-xl transition-colors"><i data-lucide="settings" class="w-4 h-4"></i> Settings</a>
                        <div class="my-1 border-t border-surface-100"></div>
                        <form method="POST" action="{{ route('logout') }}">@csrf
                            <button type="submit" class="w-full flex items-center gap-3 px-3 py-2.5 text-sm text-red-600 hover:bg-red-50 rounded-xl transition-colors"><i data-lucide="log-out" class="w-4 h-4"></i> Sign Out</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>

{{-- ═══════════ DESKTOP CONTENT ═══════════ --}}
<main style="margin-left:250px;" class="min-h-screen">
    <div class="h-16"></div>
    <div class="p-6 xl:p-8">
        @if(session('success'))
            <div class="mb-6 p-4 bg-green-50 border border-green-200 rounded-xl text-green-700 text-sm font-medium animate-slide-down flex items-center gap-2">
                <i data-lucide="check-circle" class="w-4 h-4 shrink-0"></i> {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-xl text-red-700 text-sm font-medium animate-slide-down flex items-center gap-2">
                <i data-lucide="alert-circle" class="w-4 h-4 shrink-0"></i> {{ session('error') }}
            </div>
        @endif
        @yield('content')
    </div>
</main>

<div class="toast-container" id="toastContainer"></div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    lucide.createIcons();
    const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
    window.showToast = function(message, type = 'success', duration = 3000) {
        const container = document.getElementById('toastContainer');
        const toast = document.createElement('div');
        toast.className = `toast toast-${type}`;
        toast.innerHTML = `<div class="flex items-center gap-2"><i data-lucide="${type === 'success' ? 'check-circle' : type === 'error' ? 'alert-circle' : 'info'}" class="w-4 h-4"></i><span>${message}</span></div>`;
        container.appendChild(toast);
        lucide.createIcons({ nodes: [toast] });
        requestAnimationFrame(() => toast.classList.add('show'));
        setTimeout(() => { toast.classList.remove('show'); setTimeout(() => toast.remove(), 300); }, duration);
    };
    window.ajax = async function(url, options = {}) {
        const defaults = { headers: { 'X-CSRF-TOKEN': csrfToken, 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' } };
        if (options.body && !(options.body instanceof FormData)) { defaults.headers['Content-Type'] = 'application/json'; defaults.body = JSON.stringify(options.body); }
        else if (options.body instanceof FormData) { defaults.body = options.body; }
        return fetch(url, { ...defaults, ...options });
    };
    window.confirmAction = function(message, onConfirm) {
        const overlay = document.createElement('div');
        overlay.className = 'fixed inset-0 bg-black/50 z-[9998] flex items-center justify-center p-4 animate-fade-in';
        overlay.innerHTML = `<div class="bg-white rounded-2xl p-6 max-w-sm w-full animate-scale-in shadow-2xl"><p class="text-surface-900 font-medium text-center mb-6">${message}</p><div class="flex gap-3"><button class="flex-1 py-2.5 px-4 border border-surface-200 rounded-xl text-sm font-medium text-surface-600 hover:bg-surface-50 transition-colors cancel-btn">Cancel</button><button class="flex-1 py-2.5 px-4 bg-brand-600 text-white rounded-xl text-sm font-medium hover:bg-brand-700 transition-colors confirm-btn">Confirm</button></div></div>`;
        document.body.appendChild(overlay);
        overlay.querySelector('.cancel-btn').onclick = () => overlay.remove();
        overlay.querySelector('.confirm-btn').onclick = () => { overlay.remove(); onConfirm(); };
        overlay.onclick = (e) => { if (e.target === overlay) overlay.remove(); };
    };
});
</script>
@stack('scripts')
</body>
</html>
