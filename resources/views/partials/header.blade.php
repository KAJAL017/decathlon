{{-- ═══════════════════════════════════════════════════════════════
     HEADER — Pixel-perfect Decathlon.in clone
     • <header> has position:relative so mega-dropdowns anchor to it
     • Dropdowns use position:absolute left:0 right:0 top:100%
     ═══════════════════════════════════════════════════════════════ --}}
<header id="site-header" class="bg-white border-b border-gray-200" style="position:relative; z-index:9999;">

    {{-- ──────────────────────────────────────────────────────────
         TOP BAR  ·  Logo · Search · Action Icons
         ────────────────────────────────────────────────────────── --}}
    <div class="hidden lg:flex items-center h-[64px] px-5 xl:px-10 gap-5">

        {{-- Logo --}}
        <a href="{{ route('home') }}" class="flex items-center gap-0 flex-shrink-0">
            {{-- Blue "D" swoosh --}}
            <svg width="28" height="28" viewBox="0 0 38 38" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M25.5 4C19.5 4 14.5 7.5 11.5 12.5L2 28h6.5l12-15c2-2.5 4-3.8 6.5-3.8 4.5 0 6.5 2.8 6.5 6.8 0 4.8-2.5 8.8-6.5 13h8c4.5-4.8 7.5-10 7.5-16 0-5.8-3.5-9-8.5-9-1.8 0-3.8.4-5.5.8z" fill="#0082C3"/>
            </svg>
            <span style="font-family:'Arial Black',Arial,sans-serif; font-style:italic; font-weight:900; font-size:20px; letter-spacing:-0.5px; color:#1a1a1a; line-height:1;">DECATHLON</span>
        </a>

        {{-- Search (click opens overlay) --}}
        <div class="flex-1 relative mx-4">
            <svg class="absolute left-4 top-1/2 -translate-y-1/2 w-[18px] h-[18px] text-gray-400 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
            </svg>
            <input id="header-search-input"
                   type="text"
                   placeholder='Search for 60+ sports and 6,000+ products'
                   readonly
                   onclick="openSearchOverlay()"
                   class="w-full bg-gray-100 rounded-[4px] border border-gray-200 pl-11 pr-4 py-[9px] text-[14px] text-gray-800 placeholder-gray-400 outline-none cursor-pointer transition-all hover:border-gray-300">
        </div>

        {{-- Action Icons --}}
        <div class="flex items-center gap-7 flex-shrink-0">

            @if(Auth::guard('customer')->check())
                <div class="relative group" id="customer-nav-item">
                    <a href="#" class="flex flex-col items-center gap-[4px] group">
                        <svg class="w-[24px] h-[24px] text-[#0082C3]" fill="currentColor" viewBox="0 0 24 24">
                            <path fill-rule="evenodd" d="M18.685 19.097A9.723 9.723 0 0021.75 12c0-5.385-4.365-9.75-9.75-9.75S2.25 6.615 2.25 12c0 2.694 1.087 5.13 2.85 6.903a.75.75 0 001.121-1.004 8.225 8.225 0 01-2.421-5.899 8.25 8.25 0 1116.5 0 8.225 8.225 0 01-2.421 5.899.75.75 0 001.121 1.004zM12 7a2.5 2.5 0 100 5 2.5 2.5 0 000-5z" clip-rule="evenodd" />
                        </svg>
                        <span class="text-[11px] font-black text-gray-900 group-hover:text-[#0082C3] leading-none transition-colors uppercase tracking-tight">Hi, {{ Auth::guard('customer')->user()->first_name }}</span>
                    </a>
                    
                    {{-- Customer Menu Dropdown --}}
                    <div class="absolute top-full right-0 mt-1 w-[200px] bg-white border border-gray-200 rounded-xl shadow-2xl z-[9999] opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-300 transform origin-top-right group-hover:translate-y-0 translate-y-2">
                        <div class="p-4 border-b border-gray-100 bg-gray-50 rounded-t-xl">
                            <p class="text-xs font-black text-gray-900 uppercase tracking-widest">{{ Auth::guard('customer')->user()->name }}</p>
                            <p class="text-[10px] text-gray-500 truncate">{{ Auth::guard('customer')->user()->email }}</p>
                        </div>
                        <div class="p-2">
                            <a href="#" class="block px-4 py-2 text-[12px] font-bold text-gray-700 hover:bg-gray-50 hover:text-[#0082C3] rounded-lg transition-colors uppercase tracking-wider">My Profile</a>
                            <a href="#" class="block px-4 py-2 text-[12px] font-bold text-gray-700 hover:bg-gray-50 hover:text-[#0082C3] rounded-lg transition-colors uppercase tracking-wider">My Orders</a>
                            <a href="#" class="block px-4 py-2 text-[12px] font-bold text-gray-700 hover:bg-gray-50 hover:text-[#0082C3] rounded-lg transition-colors uppercase tracking-wider">Wishlist</a>
                            <div class="my-1 border-t border-gray-100"></div>
                            <form action="{{ route('logout') }}" method="POST">
                                @csrf
                                <button type="submit" class="w-full text-left px-4 py-2 text-[12px] font-black text-red-600 hover:bg-red-50 rounded-lg transition-colors uppercase tracking-widest">Sign Out</button>
                            </form>
                        </div>
                    </div>
                </div>
            @else
                <a href="{{ route('login') }}" class="flex flex-col items-center gap-[4px] group">
                    <svg class="w-[24px] h-[24px] text-gray-700 group-hover:text-[#0082C3] transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z"/>
                    </svg>
                    <span class="text-[11px] font-medium text-gray-600 group-hover:text-[#0082C3] leading-none transition-colors">Sign In</span>
                </a>
            @endif

            <a href="#" class="flex flex-col items-center gap-[4px] group">
                <svg class="w-[24px] h-[24px] text-gray-700 group-hover:text-[#0082C3] transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12z"/>
                </svg>
                <span class="text-[11px] font-medium text-gray-600 group-hover:text-[#0082C3] leading-none transition-colors">Wishlist</span>
            </a>

            {{-- Dynamic Cart --}}
            <div class="relative group" id="cart-nav-item">
                <a href="{{ route('cart') }}" class="flex flex-col items-center gap-[4px] group relative py-2">
                    <span class="cart-count absolute -top-0 -right-1.5 bg-[#F7C844] text-gray-900 text-[10px] font-black w-5 h-5 rounded-full flex items-center justify-center border-2 border-white hidden">0</span>
                    <svg class="w-[24px] h-[24px] text-gray-700 group-hover:text-[#0082C3] transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 00-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 00-16.536-1.84M7.5 14.25L5.106 5.272M6 20.25a.75.75 0 11-1.5 0 .75.75 0 011.5 0zm12.75 0a.75.75 0 11-1.5 0 .75.75 0 011.5 0z"/>
                    </svg>
                    <span class="text-[11px] font-medium text-gray-600 group-hover:text-[#0082C3] leading-none transition-colors">Cart</span>
                </a>

                {{-- Mini Cart Dropdown --}}
                <div id="mini-cart-dropdown" class="absolute top-full right-0 mt-1 w-[320px] bg-white border border-gray-200 rounded-xl shadow-2xl z-[9999] opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-300 transform origin-top-right group-hover:translate-y-0 translate-y-2 cart-dropdown-panel">
                    <div class="p-4 border-b border-gray-100 flex items-center justify-between">
                        <h3 class="text-xs font-black text-gray-900 uppercase tracking-widest">My Cart (<span class="cart-count">0</span>)</h3>
                        <a href="{{ route('cart') }}" class="text-[10px] font-bold text-[#0082C3] hover:underline uppercase">View Full Cart</a>
                    </div>
                    
                    <div id="mini-cart-items" class="max-h-[380px] overflow-y-auto px-4 scrollbar-hide">
                        {{-- Items injected via AJAX (partials.mini-cart-items) --}}
                        <div class="py-10 flex items-center justify-center">
                            <div class="w-5 h-5 border-2 border-[#0082C3] border-t-transparent rounded-full animate-spin"></div>
                        </div>
                    </div>

                    <div class="p-4 bg-gray-50 border-t border-gray-100 rounded-b-xl">
                        <div class="flex items-center justify-between mb-4">
                            <span class="text-xs font-bold text-gray-500 uppercase tracking-wider">Subtotal</span>
                            <span class="cart-subtotal text-base font-black text-gray-900">₹0.00</span>
                        </div>
                        <a href="{{ route('checkout') }}" class="block w-full bg-[#183a9e] hover:bg-[#0c246b] text-white text-center py-3.5 rounded-lg text-xs font-black uppercase tracking-widest transition-all shadow-md">
                            Checkout Now
                        </a>
                    </div>
                </div>
            </div>

        </div>
    </div>

    {{-- ──────────────────────────────────────────────────────────
         NAV BAR  ·  Category links + Mega Dropdowns
         ────────────────────────────────────────────────────────── --}}
    <div class="hidden lg:block border-t border-gray-100">
        <div class="flex items-center justify-between px-5 xl:px-10">

            {{-- ── Nav links ── --}}
            <nav class="flex items-center" style="overflow:visible;">

                {{-- All Sports --}}
                <a href="{{ route('categories') }}"
                   class="flex-shrink-0 text-[13px] font-semibold text-gray-800 hover:text-[#0082C3] whitespace-nowrap px-3 py-[11px] border-b-2 border-transparent hover:border-[#0082C3] transition-colors">
                    All Sports
                </a>

                @if(isset($headerCategories))
                    @foreach($headerCategories as $category)

                        {{-- Nav item — intentionally NO position:relative so dropdown anchors to <header> --}}
                        <div class="mega-nav-item flex-shrink-0" data-cat="{{ $category->id }}">

                            {{-- Trigger --}}
                            <button type="button"
                                    class="nav-trigger flex items-center gap-[3px] text-[13px] font-semibold text-gray-800 whitespace-nowrap px-3 py-[11px] border-b-2 border-transparent transition-colors bg-transparent cursor-pointer">
                                {{ $category->name }}
                                @if($category->children && $category->children->count() > 0)
                                    <svg class="nav-chevron w-[11px] h-[11px] flex-shrink-0 mt-px transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="3">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                                    </svg>
                                @endif
                            </button>

                            @if($category->children && $category->children->count() > 0)
                                {{-- ══ MEGA DROPDOWN PANEL ══
                                     Anchored to <header> via position:absolute top:100% left:0 right:0 --}}
                                <div class="mega-dropdown"
                                     style="display:none; position:absolute; top:100%; left:0; right:0; z-index:9998;">
                                    <div style="background:#fff; border-top:2px solid #0082C3; border-bottom:1px solid #e5e7eb; box-shadow:0 8px 24px rgba(0,0,0,.10);">
                                        <div class="px-5 xl:px-10 pt-5 pb-7">

                                            {{-- Dropdown header --}}
                                            <div class="flex items-center justify-between mb-4 pb-3 border-b border-gray-100">
                                                <span class="text-[13px] font-black uppercase tracking-widest text-gray-900">
                                                    {{ $category->name }}
                                                </span>
                                                <a href="{{ route('shop') }}?category={{ $category->slug }}"
                                                   class="flex items-center gap-1 text-[12px] font-semibold text-[#0082C3] hover:underline">
                                                    View all {{ $category->name }}
                                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                                                    </svg>
                                                </a>
                                            </div>

                                            {{-- Columns grid: L2 = column header, L3 = items --}}
                                            <div class="grid gap-x-8"
                                                 style="grid-template-columns: repeat({{ min($category->children->count(), 7) }}, minmax(0,1fr));">
                                                @foreach($category->children as $child)
                                                    <div>
                                                        {{-- Column header --}}
                                                        <a href="{{ route('shop') }}?category={{ $child->slug }}"
                                                           class="block text-[11px] font-black uppercase tracking-wider text-gray-900 mb-3 hover:text-[#0082C3] transition-colors">
                                                            {{ $child->name }}
                                                        </a>

                                                        {{-- Item links --}}
                                                        @if($child->children && $child->children->count() > 0)
                                                            <ul class="space-y-[6px]">
                                                                @foreach($child->children as $gc)
                                                                    <li>
                                                                        <a href="{{ route('shop') }}?category={{ $gc->slug }}"
                                                                           class="block text-[13px] text-gray-600 hover:text-[#0082C3] transition-colors leading-snug">
                                                                            {{ $gc->name }}
                                                                        </a>
                                                                    </li>
                                                                @endforeach
                                                            </ul>
                                                        @endif
                                                    </div>
                                                @endforeach
                                            </div>

                                        </div>
                                    </div>
                                </div>
                            @endif

                        </div>
                    @endforeach
                @endif

            </nav>

            {{-- Delivery --}}
            <p class="text-[12px] text-gray-500 whitespace-nowrap flex-shrink-0 py-[11px]">
                Delivery to
                <a href="#" class="text-[#0082C3] font-semibold underline ml-1 hover:text-[#006699] transition-colors">
                    Bangalore Central, 560001
                </a>
            </p>

        </div>
    </div>

    {{-- ──────────────────────────────────────────────────────────
         MOBILE HEADER
         ────────────────────────────────────────────────────────── --}}
    <div class="flex lg:hidden items-center justify-between h-[52px] px-3 border-t border-gray-100">
        <div class="flex items-center gap-2">
            <button class="p-2 -ml-1.5">
                <div class="space-y-[5px]">
                    <span class="block w-[20px] h-[2px] bg-gray-800 rounded"></span>
                    <span class="block w-[20px] h-[2px] bg-gray-800 rounded"></span>
                    <span class="block w-[20px] h-[2px] bg-gray-800 rounded"></span>
                </div>
            </button>
            <a href="{{ route('home') }}" class="flex items-center gap-1">
                <svg width="22" height="22" viewBox="0 0 38 38" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M25.5 4C19.5 4 14.5 7.5 11.5 12.5L2 28h6.5l12-15c2-2.5 4-3.8 6.5-3.8 4.5 0 6.5 2.8 6.5 6.8 0 4.8-2.5 8.8-6.5 13h8c4.5-4.8 7.5-10 7.5-16 0-5.8-3.5-9-8.5-9-1.8 0-3.8.4-5.5.8z" fill="#0082C3"/>
                </svg>
                <span style="font-family:'Arial Black',Arial,sans-serif;font-style:italic;font-weight:900;font-size:16px;color:#1a1a1a;letter-spacing:-0.5px;">DECATHLON</span>
            </a>
        </div>
        <div class="flex items-center gap-4">
            <button onclick="openSearchOverlay()">
                <svg class="w-6 h-6 text-gray-800" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
            </button>
            <a href="{{ route('cart') }}" class="relative group">
                <span class="cart-count absolute -top-1.5 -right-1.5 bg-[#F7C844] text-gray-900 text-[9px] font-black w-4.5 h-4.5 rounded-full flex items-center justify-center border-2 border-white hidden">0</span>
                <svg class="w-7 h-7 text-gray-800" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 00-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 00-16.536-1.84M7.5 14.25L5.106 5.272M6 20.25a.75.75 0 11-1.5 0 .75.75 0 011.5 0zm12.75 0a.75.75 0 11-1.5 0 .75.75 0 011.5 0z"/>
                </svg>
            </a>
        </div>
    </div>

</header>

{{-- ════════════════════════════════════════════════════════
     SEARCH OVERLAY
     Opens when user clicks the header search bar.
     ════════════════════════════════════════════════════════ --}}
<div id="search-overlay" class="fixed inset-0 z-[99999] hidden" aria-modal="true" role="dialog">

    {{-- Dimmed backdrop --}}
    <div id="search-backdrop"
         onclick="closeSearchOverlay()"
         class="absolute inset-0 bg-black/40 backdrop-blur-[2px]"></div>

    {{-- Panel --}}
    <div class="relative z-10 w-full max-w-3xl mx-auto mt-6 bg-white rounded-2xl shadow-2xl overflow-hidden"
         style="max-height:90vh; overflow-y:auto;">

        {{-- Search Input Row --}}
        <div class="flex items-center gap-3 px-5 py-4 border-b border-gray-100">
            <svg class="w-5 h-5 text-gray-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
            </svg>
            <input id="overlay-search-input"
                   type="text"
                   placeholder="Search for 60+ sports and 6,000+ products"
                   autocomplete="off"
                   class="flex-1 text-[15px] text-gray-900 bg-transparent outline-none placeholder-gray-400">
            <button onclick="closeSearchOverlay()" class="text-gray-400 hover:text-gray-700 transition-colors flex-shrink-0">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        {{-- Body --}}
        <div id="search-overlay-body" class="px-5 py-5">

            {{-- Default state: Trending + Recently Viewed --}}
            <div id="search-default-state">

                {{-- Trending Searches --}}
                <div class="mb-6 hidden" id="trending-section">
                    <h3 class="text-[13px] font-bold text-gray-900 mb-3">Trending searches</h3>
                    <div class="flex flex-wrap gap-2" id="trending-chips">
                        {{-- Injected via AJAX --}}
                    </div>
                </div>

                {{-- Recently Viewed (handled by JS localStorage) --}}
                <div class="mb-8 hidden" id="recently-viewed-section">
                    <h3 class="text-[13px] font-bold text-gray-900 mb-3">Recently Viewed</h3>
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-3" id="recently-viewed-grid">
                        {{-- Injected via JS --}}
                    </div>
                </div>

                {{-- Most Popular (Injected via AJAX) --}}
                <div class="mb-2 hidden" id="popular-section">
                    <div class="flex items-center justify-between mb-3">
                        <h3 class="text-[13px] font-bold text-gray-900">Most Popular</h3>
                        <a href="{{ route('shop') }}" class="text-[12px] text-[#0082C3] font-semibold hover:underline">View all &rarr;</a>
                    </div>
                    <div class="flex gap-3 overflow-x-auto pb-2 scrollbar-hide scroll-smooth" id="popular-grid">
                        {{-- Injected via AJAX --}}
                    </div>
                </div>

            </div>

            {{-- Live search results state --}}
            <div id="search-results-state" class="hidden">

                {{-- Category matches --}}
                <div id="category-results" class="hidden mb-4">
                    <h3 class="text-[11px] font-black text-gray-500 uppercase tracking-widest mb-2">Categories</h3>
                    <div id="category-results-list" class="flex flex-wrap gap-2"></div>
                </div>

                {{-- Product matches --}}
                <div id="product-results">
                    <h3 class="text-[11px] font-black text-gray-500 uppercase tracking-widest mb-3" id="products-heading">Products</h3>
                    <div id="product-results-grid" class="grid grid-cols-3 gap-4"></div>
                    <p id="no-results" class="hidden text-center text-gray-500 py-8 text-[14px]">No results found for your search.</p>
                </div>

            </div>

        </div>
    </div>
</div>

<script>
/* ════════════════════════════════════
   HEADER NAVIGATION & UI
   ════════════════════════════════════ */
document.addEventListener('DOMContentLoaded', function () {
    /* ── Mega dropdown hover ── */
    const items = document.querySelectorAll('.mega-nav-item');
    let closeTimer = null;

    function closeAll(except) {
        items.forEach(item => {
            if (item === except) return;
            const d = item.querySelector('.mega-dropdown');
            const t = item.querySelector('.nav-trigger');
            const c = item.querySelector('.nav-chevron');
            if (d) d.style.display = 'none';
            if (t) { t.style.color = ''; t.style.borderBottomColor = 'transparent'; }
            if (c) c.style.transform = '';
        });
    }

    items.forEach(item => {
        const trigger  = item.querySelector('.nav-trigger');
        const dropdown = item.querySelector('.mega-dropdown');
        const chevron  = item.querySelector('.nav-chevron');
        if (!dropdown) return;

        const open = () => {
            clearTimeout(closeTimer);
            closeAll(item);
            dropdown.style.display = 'block';
            if (trigger) { trigger.style.color = '#0082C3'; trigger.style.borderBottomColor = '#0082C3'; }
            if (chevron) chevron.style.transform = 'rotate(180deg)';
        };

        const close = () => {
            closeTimer = setTimeout(() => {
                dropdown.style.display = 'none';
                if (trigger) { trigger.style.color = ''; trigger.style.borderBottomColor = 'transparent'; }
                if (chevron) chevron.style.transform = '';
            }, 100);
        };

        item.addEventListener('mouseenter', open);
        item.addEventListener('mouseleave', close);
        dropdown.addEventListener('mouseenter', () => clearTimeout(closeTimer));
        dropdown.addEventListener('mouseleave', close);
    });

    /* Close when clicking outside */
    document.addEventListener('click', e => {
        if (!e.target.closest('.mega-nav-item')) closeAll(null);
    });
});
</script>
