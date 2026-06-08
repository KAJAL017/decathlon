<!-- Sidebar -->
<aside id="sidebar" class="w-64 bg-gradient-to-b from-gray-900 to-gray-800 flex-shrink-0 transition-transform duration-300 lg:translate-x-0 -translate-x-full fixed left-0 top-0 z-30 h-screen shadow-2xl overflow-y-auto">
    <!-- Logo -->
    <div class="h-16 flex items-center justify-between px-6 border-b border-gray-700/50 sticky top-0 bg-gray-900 z-10">
        <div class="flex items-center">
            @if($adminLogo = \App\Models\Setting::get('admin_logo'))
                <img src="{{ $adminLogo }}" class="max-h-10 max-w-[170px] object-contain rounded transition-all duration-300 hover:scale-105 filter drop-shadow-md" alt="Admin Logo">
            @else
                <div class="flex items-center gap-3">
                    <div class="bg-gradient-to-br from-[#0082C3] to-[#005a8c] p-2 rounded-lg shadow-lg">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.5 21v-7.5a.75.75 0 01.75-.75h3a.75.75 0 01.75.75V21m-4.5 0H2.36m11.14 0H18m0 0h3.64m-1.39 0V9.349m-16.5 11.65V9.35m0 0a3.001 3.001 0 003.75-.615A2.993 2.993 0 009.75 9.75c.896 0 1.7-.393 2.25-1.016a2.993 2.993 0 002.25 1.016c.896 0 1.7-.393 2.25-1.016a3.001 3.001 0 003.75.614m-16.5 0a3.004 3.004 0 01-.621-4.72L4.318 3.44A1.5 1.5 0 015.378 3h13.243a1.5 1.5 0 011.06.44l1.19 1.189a3 3 0 01-.621 4.72m-13.5 8.65h3.75a.75.75 0 00.75-.75V13.5a.75.75 0 00-.75-.75H6.75a.75.75 0 00-.75.75v3.75c0 .415.336.75.75.75z"></path>
                        </svg>
                    </div>
                </div>
            @endif
        </div>
        <button onclick="toggleSidebar()" class="lg:hidden text-gray-400 hover:text-white transition-colors">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
        </button>
    </div>

    <nav class="p-4 pb-20">

        {{-- OVERVIEW --}}
        <div class="mb-5">
            <h3 class="px-4 mb-2 text-[10px] font-bold text-gray-500 uppercase tracking-wider">Overview</h3>
            <a href="{{ route('admin.dashboard') }}" id="dashboardLink" class="sidebar-link flex items-center gap-3 px-4 py-2.5 text-gray-300 hover:text-white hover:bg-gray-700/50 rounded-xl transition-all mb-1">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                <span class="text-sm font-semibold">Dashboard</span>
            </a>
            <a href="{{ route('admin.reports.index') }}" class="sidebar-link flex items-center gap-3 px-4 py-2.5 text-gray-300 hover:text-white hover:bg-gray-700/50 rounded-xl transition-all mb-1">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                <span class="text-sm font-medium">Analytics & Reports</span>
            </a>
        </div>

        {{-- CONTENT --}}
        <div class="mb-5">
            <h3 class="px-4 mb-2 text-[10px] font-bold text-gray-500 uppercase tracking-wider">Content</h3>
            <a href="{{ route('admin.home-sections.index') }}" class="sidebar-link flex items-center gap-3 px-4 py-2.5 text-gray-300 hover:text-white hover:bg-gray-700/50 rounded-xl transition-all mb-1">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                <span class="text-sm font-medium">Homepage Builder</span>
            </a>
            <a href="{{ route('admin.banners.index') }}" class="sidebar-link flex items-center gap-3 px-4 py-2.5 text-gray-300 hover:text-white hover:bg-gray-700/50 rounded-xl transition-all mb-1">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 5a1 1 0 011-1h14a1 1 0 011 1v2a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM4 13a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H5a1 1 0 01-1-1v-6zM16 13a1 1 0 011-1h2a1 1 0 011 1v6a1 1 0 01-1 1h-2a1 1 0 01-1-1v-6z"/></svg>
                <span class="text-sm font-medium">Hero Banners</span>
            </a>
        </div>

        {{-- SALES & ORDERS --}}
        <div class="mb-5">
            <h3 class="px-4 mb-2 text-[10px] font-bold text-gray-500 uppercase tracking-wider">Sales & Orders</h3>
            <a href="{{ route('admin.orders.index') }}" class="sidebar-link flex items-center gap-3 px-4 py-2.5 text-gray-300 hover:text-white hover:bg-gray-700/50 rounded-xl transition-all mb-1">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                <span class="text-sm font-medium">Orders</span>
            </a>
            <a href="{{ route('admin.returns.index') }}" class="sidebar-link flex items-center gap-3 px-4 py-2.5 text-gray-300 hover:text-white hover:bg-gray-700/50 rounded-xl transition-all mb-1">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"/></svg>
                <span class="text-sm font-medium">Returns & Refunds</span>
            </a>
            <a href="{{ route('admin.invoices.index') }}" class="sidebar-link flex items-center gap-3 px-4 py-2.5 text-gray-300 hover:text-white hover:bg-gray-700/50 rounded-xl transition-all mb-1">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                <span class="text-sm font-medium">Invoices</span>
            </a>
            <a href="javascript:void(0)" class="sidebar-link flex items-center gap-3 px-4 py-2.5 text-gray-300 hover:text-white hover:bg-gray-700/50 rounded-xl transition-all mb-1 opacity-50 cursor-not-allowed" title="Requires storefront integration">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                <span class="text-sm font-medium">Abandoned Cart</span>
                <span class="ml-auto text-[9px] bg-gray-600 text-gray-400 px-1.5 py-0.5 rounded font-medium">Soon</span>
            </a>
        </div>

        {{-- CATALOG --}}
        <div class="mb-5">
            <h3 class="px-4 mb-2 text-[10px] font-bold text-gray-500 uppercase tracking-wider">Catalog</h3>
            <a href="{{ route('admin.products.index') }}" class="sidebar-link flex items-center gap-3 px-4 py-2.5 text-gray-300 hover:text-white hover:bg-gray-700/50 rounded-xl transition-all mb-1">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                <span class="text-sm font-medium">Products</span>
            </a>
            <a href="{{ route('admin.categories.index') }}" class="sidebar-link flex items-center gap-3 px-4 py-2.5 text-gray-300 hover:text-white hover:bg-gray-700/50 rounded-xl transition-all mb-1">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/></svg>
                <span class="text-sm font-medium">Categories</span>
            </a>
            <a href="{{ route('admin.brands.index') }}" class="sidebar-link flex items-center gap-3 px-4 py-2.5 text-gray-300 hover:text-white hover:bg-gray-700/50 rounded-xl transition-all mb-1">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/></svg>
                <span class="text-sm font-medium">Brands</span>
            </a>
            <a href="{{ route('admin.collections.index') }}" class="sidebar-link flex items-center gap-3 px-4 py-2.5 text-gray-300 hover:text-white hover:bg-gray-700/50 rounded-xl transition-all mb-1">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                <span class="text-sm font-medium">Collections</span>
            </a>
            <a href="{{ route('admin.tags.index') }}" class="sidebar-link flex items-center gap-3 px-4 py-2.5 text-gray-300 hover:text-white hover:bg-gray-700/50 rounded-xl transition-all mb-1">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/></svg>
                <span class="text-sm font-medium">Tags</span>
            </a>

            {{-- Attributes with submenu (3 real pages) --}}
            <div class="mb-1">
                <button onclick="toggleSubmenu('attributes')" class="w-full flex items-center justify-between px-4 py-2.5 text-gray-300 hover:text-white hover:bg-gray-700/50 rounded-xl transition-all">
                    <div class="flex items-center gap-3">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01"/></svg>
                        <span class="text-sm font-medium">Attributes</span>
                    </div>
                    <svg class="w-4 h-4 transition-transform" id="attributes-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                </button>
                <div id="attributes-submenu" class="hidden ml-4 mt-1 space-y-1">
                    <a href="{{ route('admin.attributes.index') }}" class="sidebar-link block px-4 py-2 text-sm text-gray-400 hover:text-white hover:bg-gray-700/30 rounded-lg">All Attributes</a>
                    <a href="{{ route('admin.attribute-groups.index') }}" class="sidebar-link block px-4 py-2 text-sm text-gray-400 hover:text-white hover:bg-gray-700/30 rounded-lg">Attribute Groups</a>
                    <a href="{{ route('admin.attribute-values.index') }}" class="sidebar-link block px-4 py-2 text-sm text-gray-400 hover:text-white hover:bg-gray-700/30 rounded-lg">Attribute Values</a>
                </div>
            </div>

            <a href="{{ route('admin.reviews.index') }}" class="sidebar-link flex items-center gap-3 px-4 py-2.5 text-gray-300 hover:text-white hover:bg-gray-700/50 rounded-xl transition-all mb-1">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/></svg>
                <span class="text-sm font-medium">Reviews</span>
            </a>
        </div>

        {{-- INVENTORY --}}
        <div class="mb-5">
            <h3 class="px-4 mb-2 text-[10px] font-bold text-gray-500 uppercase tracking-wider">Inventory</h3>
            <a href="{{ route('admin.stock.index') }}" class="sidebar-link flex items-center gap-3 px-4 py-2.5 text-gray-300 hover:text-white hover:bg-gray-700/50 rounded-xl transition-all mb-1">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                <span class="text-sm font-medium">Stock Management</span>
            </a>
            <a href="{{ route('admin.warehouses.index') }}" class="sidebar-link flex items-center gap-3 px-4 py-2.5 text-gray-300 hover:text-white hover:bg-gray-700/50 rounded-xl transition-all mb-1">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                <span class="text-sm font-medium">Warehouses</span>
            </a>
        </div>

        {{-- CUSTOMERS --}}
        <div class="mb-5">
            <h3 class="px-4 mb-2 text-[10px] font-bold text-gray-500 uppercase tracking-wider">Customers</h3>
            <a href="{{ route('admin.customers.index') }}" class="sidebar-link flex items-center gap-3 px-4 py-2.5 text-gray-300 hover:text-white hover:bg-gray-700/50 rounded-xl transition-all mb-1">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                <span class="text-sm font-medium">Customers</span>
            </a>
        </div>

        {{-- MARKETING --}}
        <div class="mb-5">
            <h3 class="px-4 mb-2 text-[10px] font-bold text-gray-500 uppercase tracking-wider">Marketing</h3>
            <a href="{{ route('admin.promotions.index') }}" class="sidebar-link flex items-center gap-3 px-4 py-2.5 text-gray-300 hover:text-white hover:bg-gray-700/50 rounded-xl transition-all mb-1">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V5.5A2.5 2.5 0 109.5 8H12zm-7 4h14M5 12a2 2 0 110-4h14a2 2 0 110 4M5 12v7a2 2 0 002 2h10a2 2 0 002-2v-7"/></svg>
                <span class="text-sm font-medium">Promotions</span>
            </a>
            <a href="{{ route('admin.coupons.index') }}" class="sidebar-link flex items-center gap-3 px-4 py-2.5 text-gray-300 hover:text-white hover:bg-gray-700/50 rounded-xl transition-all mb-1">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"/></svg>
                <span class="text-sm font-medium">Coupons</span>
            </a>
            <a href="{{ route('admin.email-campaigns.index') }}" class="sidebar-link flex items-center gap-3 px-4 py-2.5 text-gray-300 hover:text-white hover:bg-gray-700/50 rounded-xl transition-all mb-1">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                <span class="text-sm font-medium">Email Campaigns</span>
            </a>
        </div>

        {{-- SETTINGS --}}
        <div class="mb-5">
            <h3 class="px-4 mb-2 text-[10px] font-bold text-gray-500 uppercase tracking-wider">Settings</h3>
            <a href="{{ route('admin.settings.index') }}" class="sidebar-link flex items-center gap-3 px-4 py-2.5 text-gray-300 hover:text-white hover:bg-gray-700/50 rounded-xl transition-all mb-1">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                <span class="text-sm font-medium">Settings</span>
            </a>
            <a href="{{ route('admin.integrations.index') }}" class="sidebar-link flex items-center gap-3 px-4 py-2.5 text-gray-300 hover:text-white hover:bg-gray-700/50 rounded-xl transition-all mb-1">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 9l3 3-3 3m5 0h3M5 20h14a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                <span class="text-sm font-medium">Integrations</span>
            </a>
            <a href="{{ route('admin.localization.index') }}" class="sidebar-link flex items-center gap-3 px-4 py-2.5 text-gray-300 hover:text-white hover:bg-gray-700/50 rounded-xl transition-all mb-1">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <span class="text-sm font-medium">Localization</span>
            </a>
            <a href="{{ route('admin.ai-tools.index') }}" class="sidebar-link flex items-center gap-3 px-4 py-2.5 text-gray-300 hover:text-white hover:bg-gray-700/50 rounded-xl transition-all mb-1">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/></svg>
                <span class="text-sm font-medium">AI Tools</span>
            </a>
            <a href="{{ route('admin.system-tools.index') }}" class="sidebar-link flex items-center gap-3 px-4 py-2.5 text-gray-300 hover:text-white hover:bg-gray-700/50 rounded-xl transition-all mb-1">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"/></svg>
                <span class="text-sm font-medium">System Tools</span>
            </a>
        </div>

        {{-- ADMIN --}}
        <div class="mb-5">
            <h3 class="px-4 mb-2 text-[10px] font-bold text-gray-500 uppercase tracking-wider">Admin</h3>
            <div class="mb-1">
                <button onclick="toggleSubmenu('admin')" class="w-full flex items-center justify-between px-4 py-2.5 text-gray-300 hover:text-white hover:bg-gray-700/50 rounded-xl transition-all">
                    <div class="flex items-center gap-3">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                        <span class="text-sm font-medium">Admin Users</span>
                    </div>
                    <svg class="w-4 h-4 transition-transform" id="admin-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                </button>
                <div id="admin-submenu" class="hidden ml-4 mt-1 space-y-1">
                    <a href="{{ route('admin.users.index') }}" class="sidebar-link block px-4 py-2 text-sm text-gray-400 hover:text-white hover:bg-gray-700/30 rounded-lg">All Users</a>
                    <a href="{{ route('admin.roles.index') }}" class="sidebar-link block px-4 py-2 text-sm text-gray-400 hover:text-white hover:bg-gray-700/30 rounded-lg">Roles</a>
                    <a href="{{ route('admin.permissions.index') }}" class="sidebar-link block px-4 py-2 text-sm text-gray-400 hover:text-white hover:bg-gray-700/30 rounded-lg">Permissions</a>
                </div>
            </div>
            <a href="{{ route('admin.activity-logs.index') }}" class="sidebar-link flex items-center gap-3 px-4 py-2.5 text-gray-300 hover:text-white hover:bg-gray-700/50 rounded-xl transition-all mb-1">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                <span class="text-sm font-medium">Activity Logs</span>
            </a>
        </div>

    </nav>
</aside>

<script>
function toggleSubmenu(id) {
    const submenu = document.getElementById(id + '-submenu');
    const icon    = document.getElementById(id + '-icon');
    submenu.classList.toggle('hidden');
    if (icon) icon.classList.toggle('rotate-180');
}

function toggleSidebar() {
    document.getElementById('sidebar').classList.toggle('-translate-x-full');
}

document.addEventListener('DOMContentLoaded', function () {
    const path = window.location.pathname.replace(/\/$/, '');

    // Dashboard exact match
    if (path === '/admin/dashboard' || path === '/admin') {
        const dl = document.getElementById('dashboardLink');
        if (dl) {
            dl.classList.remove('text-gray-300');
            dl.classList.add('text-white', 'bg-gradient-to-r', 'from-[#0082C3]', 'to-[#005a8c]', 'shadow-lg');
        }
        return;
    }

    // Highlight active link
    document.querySelectorAll('.sidebar-link').forEach(link => {
        const href = link.getAttribute('href');
        if (!href || href === '#') return;
        let linkPath = href;
        try { if (href.startsWith('http')) linkPath = new URL(href).pathname; } catch(e) {}
        linkPath = linkPath.replace(/\/$/, '').split('?')[0];

        if (path === linkPath || (linkPath.length > 10 && path.startsWith(linkPath))) {
            link.classList.remove('text-gray-300', 'text-gray-400');
            link.classList.add('text-white', 'bg-white/10', 'font-semibold');

            // Open parent submenu if inside one
            const parent = link.closest('[id$="-submenu"]');
            if (parent) {
                parent.classList.remove('hidden');
                const icon = document.getElementById(parent.id.replace('-submenu', '-icon'));
                if (icon) icon.classList.add('rotate-180');
            }

            // Scroll active link into view inside sidebar (not window)
            setTimeout(() => {
                const sidebar = document.getElementById('sidebar');
                if (sidebar) {
                    const linkTop    = link.getBoundingClientRect().top;
                    const sidebarTop = sidebar.getBoundingClientRect().top;
                    const offset     = linkTop - sidebarTop - (sidebar.clientHeight / 2) + (link.clientHeight / 2);
                    sidebar.scrollBy({ top: offset, behavior: 'smooth' });
                }
            }, 150);
        }
    });
});
</script>
