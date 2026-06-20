<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no, viewport-fit=cover">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="default">
    <meta name="theme-color" content="#ffffff">
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
        * { -webkit-tap-highlight-color: transparent; box-sizing: border-box; }
        body { font-family: 'Inter', sans-serif; margin: 0; -webkit-font-smoothing: antialiased; }
        .pb-safe { padding-bottom: max(env(safe-area-inset-bottom), 16px); }
        .pt-safe { padding-top: env(safe-area-inset-top, 0px); }
        @keyframes slideUp { from { opacity:0; transform:translateY(16px); } to { opacity:1; transform:translateY(0); } }
        @keyframes slideDown { from { opacity:0; transform:translateY(-10px); } to { opacity:1; transform:translateY(0); } }
        @keyframes fadeIn { from { opacity:0; } to { opacity:1; } }
        @keyframes scaleIn { from { opacity:0; transform:scale(0.95); } to { opacity:1; transform:scale(1); } }
        @keyframes slideUpFull { from { transform:translateY(100%); } to { transform:translateY(0); } }
        .animate-slide-up { animation: slideUp 0.35s cubic-bezier(0.16,1,0.3,1) forwards; }
        .animate-slide-down { animation: slideDown 0.25s cubic-bezier(0.16,1,0.3,1) forwards; }
        .animate-fade-in { animation: fadeIn 0.25s ease forwards; }
        .animate-scale-in { animation: scaleIn 0.2s cubic-bezier(0.16,1,0.3,1) forwards; }
        .animate-slide-up-full { animation: slideUpFull 0.3s cubic-bezier(0.16,1,0.3,1) forwards; }
        .stagger-1 { animation-delay: 0.03s; } .stagger-2 { animation-delay: 0.06s; }
        .stagger-3 { animation-delay: 0.09s; } .stagger-4 { animation-delay: 0.12s; }
        .stagger-5 { animation-delay: 0.15s; } .stagger-6 { animation-delay: 0.18s; }
        .btn-primary { background: linear-gradient(135deg, #0082C3 0%, #006699 100%); transition: all 0.15s ease; }
        .btn-primary:active { transform: scale(0.97); opacity: 0.9; }
        .mobile-nav-item.active .mobile-nav-icon { color: #0082C3; }
        .mobile-nav-item.active .mobile-nav-label { color: #0082C3; }
        .mobile-nav-item.active::after { content:''; position:absolute; top:-1px; left:50%; transform:translateX(-50%); width:20px; height:3px; background:#0082C3; border-radius:0 0 3px 3px; }
        .scrollbar-none::-webkit-scrollbar { display: none; }
        .scrollbar-none { -ms-overflow-style: none; scrollbar-width: none; }
        .toast-container { position:fixed; top:60px; left:16px; right:16px; z-index:9999; display:flex; flex-direction:column; gap:8px; pointer-events:none; }
        .toast { padding:12px 16px; border-radius:14px; font-size:13px; font-weight:600; box-shadow:0 8px 32px rgba(0,0,0,0.12); transform:translateY(-20px); opacity:0; transition:all 0.3s cubic-bezier(0.16,1,0.3,1); pointer-events:auto; }
        .toast.show { transform:translateY(0); opacity:1; }
        .toast-success { background:#166534; color:white; }
        .toast-error { background:#dc2626; color:white; }
        .toast-info { background:#1e40af; color:white; }
        input, textarea, select { font-size: 16px !important; }
    </style>
    @stack('styles')
</head>
<body class="bg-surface-50 text-surface-900 antialiased">
@php
    $customer = Auth::guard('customer')->user();
    $unreadCount = $customer->notifications_panel()->unread()->count() ?? 0;
@endphp

{{-- ═══════════ MOBILE HEADER ═══════════ --}}
<header class="fixed top-0 left-0 right-0 z-50 bg-white border-b border-surface-100 pt-safe" style="backdrop-filter:blur(20px); -webkit-backdrop-filter:blur(20px); background:rgba(255,255,255,0.92);">
    <div class="flex items-center justify-between h-[56px] px-4">
        <div class="flex items-center gap-2 min-w-0">
            @hasSection('mobile-back')
                @yield('mobile-back')
            @endif
            <h1 class="text-[15px] font-bold text-surface-900 truncate">@yield('page-title', 'Account')</h1>
        </div>
        <div class="flex items-center gap-1 shrink-0">
            <a href="{{ route('customer.notifications') }}" class="relative p-2.5 text-surface-500 active:text-brand-600 transition-colors">
                <i data-lucide="bell" class="w-[22px] h-[22px]"></i>
                @if($unreadCount > 0)
                    <span class="absolute top-1.5 right-1.5 w-2 h-2 bg-red-500 rounded-full"></span>
                @endif
            </a>
            <a href="{{ route('customer.profile') }}" class="p-1 active:scale-95 transition-transform">
                <div class="w-8 h-8 rounded-full bg-brand-100 text-brand-700 flex items-center justify-center text-[11px] font-bold border-2 border-white shadow-sm">{{ $customer->initials }}</div>
            </a>
        </div>
    </div>
</header>

{{-- ═══════════ MOBILE CONTENT ═══════════ --}}
<main class="pt-[56px] pb-[72px] min-h-screen" style="-webkit-overflow-scrolling:touch;">
    <div class="px-4 py-3">
        @if(session('success'))
            <div class="mb-3 p-3 bg-green-50 border border-green-200 rounded-xl text-green-700 text-sm font-medium animate-slide-down flex items-center gap-2">
                <i data-lucide="check-circle" class="w-4 h-4 shrink-0"></i> {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div class="mb-3 p-3 bg-red-50 border border-red-200 rounded-xl text-red-700 text-sm font-medium animate-slide-down flex items-center gap-2">
                <i data-lucide="alert-circle" class="w-4 h-4 shrink-0"></i> {{ session('error') }}
            </div>
        @endif
        @yield('content')
    </div>
</main>

{{-- ═══════════ MOBILE BOTTOM NAV ═══════════ --}}
<nav class="fixed bottom-0 left-0 right-0 z-50 bg-white/95 border-t border-surface-200/80 pb-safe" style="backdrop-filter:blur(20px); -webkit-backdrop-filter:blur(20px);">
    <div class="flex items-center justify-around h-[60px] px-2">
        @php
            $mobileNav = [
                ['route' => 'customer.dashboard', 'label' => 'Home', 'icon' => 'home'],
                ['route' => 'customer.orders', 'label' => 'Orders', 'icon' => 'package'],
                ['route' => 'customer.wishlist', 'label' => 'Wishlist', 'icon' => 'heart'],
                ['route' => 'customer.profile', 'label' => 'Account', 'icon' => 'user'],
            ];
        @endphp
        @foreach($mobileNav as $item)
            @php $isActive = request()->routeIs($item['route'] . '*'); @endphp
            <a href="{{ route($item['route']) }}" class="mobile-nav-item relative flex flex-col items-center justify-center gap-0.5 py-1 px-3 min-w-[56px] {{ $isActive ? 'active' : 'text-surface-400' }}">
                <i data-lucide="{{ $item['icon'] }}" class="mobile-nav-icon w-[22px] h-[22px] {{ $isActive ? 'text-brand-600' : '' }}"></i>
                <span class="mobile-nav-label text-[10px] font-semibold {{ $isActive ? 'text-brand-600' : '' }}">{{ $item['label'] }}</span>
            </a>
        @endforeach
    </div>
</nav>

<div class="toast-container" id="toastContainer"></div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    lucide.createIcons();
    const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
    window.showToast = function(message, type = 'success', duration = 2500) {
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
        overlay.className = 'fixed inset-0 bg-black/50 z-[9998] flex items-end justify-center animate-fade-in';
        overlay.innerHTML = `<div class="bg-white rounded-t-2xl p-5 w-full animate-slide-up-full shadow-2xl pb-safe"><p class="text-surface-900 font-semibold text-center mb-5">${message}</p><div class="flex gap-3"><button class="flex-1 py-3 border border-surface-200 rounded-xl text-sm font-medium text-surface-600 active:bg-surface-50 transition-colors cancel-btn">Cancel</button><button class="flex-1 py-3 bg-brand-600 text-white rounded-xl text-sm font-semibold active:bg-brand-700 transition-colors confirm-btn">Confirm</button></div></div>`;
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
