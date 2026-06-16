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
            <i data-lucide="x" class="w-6 h-6"></i>
        </button>
    </div>

    <nav class="p-4 pb-20">

        {{-- OVERVIEW --}}
        <div class="mb-5">
            <h3 class="px-4 mb-2 text-[10px] font-bold text-gray-500 uppercase tracking-wider">Overview</h3>
            <a href="{{ route('admin.dashboard') }}" id="dashboardLink" class="sidebar-link flex items-center gap-3 px-4 py-2.5 text-gray-300 hover:text-white hover:bg-gray-700/50 rounded-xl transition-all mb-1">
                <i data-lucide="home" class="w-5 h-5"></i>
                <span class="text-sm font-semibold">Dashboard</span>
            </a>
            <a href="{{ route('admin.reports.index') }}" class="sidebar-link flex items-center gap-3 px-4 py-2.5 text-gray-300 hover:text-white hover:bg-gray-700/50 rounded-xl transition-all mb-1">
                <i data-lucide="bar-chart-2" class="w-5 h-5"></i>
                <span class="text-sm font-medium">Analytics & Reports</span>
            </a>
        </div>

        {{-- CONTENT --}}
        <div class="mb-5">
            <h3 class="px-4 mb-2 text-[10px] font-bold text-gray-500 uppercase tracking-wider">Content</h3>
            <a href="{{ route('admin.home-sections.index') }}" class="sidebar-link flex items-center gap-3 px-4 py-2.5 text-gray-300 hover:text-white hover:bg-gray-700/50 rounded-xl transition-all mb-1">
                <i data-lucide="layout-grid" class="w-5 h-5"></i>
                <span class="text-sm font-medium">Homepage Builder</span>
            </a>
            <a href="{{ route('admin.banners.index') }}" class="sidebar-link flex items-center gap-3 px-4 py-2.5 text-gray-300 hover:text-white hover:bg-gray-700/50 rounded-xl transition-all mb-1">
                <i data-lucide="layout-grid" class="w-5 h-5"></i>
                <span class="text-sm font-medium">Banners</span>
            </a>
        </div>

        {{-- SALES & ORDERS --}}
        <div class="mb-5">
            <h3 class="px-4 mb-2 text-[10px] font-bold text-gray-500 uppercase tracking-wider">Sales & Orders</h3>
            <a href="{{ route('admin.orders.index') }}" class="sidebar-link flex items-center gap-3 px-4 py-2.5 text-gray-300 hover:text-white hover:bg-gray-700/50 rounded-xl transition-all mb-1">
                <i data-lucide="shopping-bag" class="w-5 h-5"></i>
                <span class="text-sm font-medium">Orders</span>
            </a>
            <a href="{{ route('admin.order-tracking.index') }}" class="sidebar-link flex items-center gap-3 px-4 py-2.5 text-gray-300 hover:text-white hover:bg-gray-700/50 rounded-xl transition-all mb-1">
                <i data-lucide="map-pin" class="w-5 h-5"></i>
                <span class="text-sm font-medium">Order Tracking</span>
            </a>
            <a href="{{ route('admin.returns.index') }}" class="sidebar-link flex items-center gap-3 px-4 py-2.5 text-gray-300 hover:text-white hover:bg-gray-700/50 rounded-xl transition-all mb-1">
                <i data-lucide="rotate-ccw" class="w-5 h-5"></i>
                <span class="text-sm font-medium">Returns & Refunds</span>
            </a>
            <a href="{{ route('admin.invoices.index') }}" class="sidebar-link flex items-center gap-3 px-4 py-2.5 text-gray-300 hover:text-white hover:bg-gray-700/50 rounded-xl transition-all mb-1">
                <i data-lucide="file-text" class="w-5 h-5"></i>
                <span class="text-sm font-medium">Invoices</span>
            </a>
            <a href="javascript:void(0)" class="sidebar-link flex items-center gap-3 px-4 py-2.5 text-gray-300 hover:text-white hover:bg-gray-700/50 rounded-xl transition-all mb-1 opacity-50 cursor-not-allowed" title="Requires storefront integration">
                <i data-lucide="shopping-cart" class="w-5 h-5"></i>
                <span class="text-sm font-medium">Abandoned Cart</span>
                <span class="ml-auto text-[9px] bg-gray-600 text-gray-400 px-1.5 py-0.5 rounded font-medium">Soon</span>
            </a>
        </div>

        {{-- CATALOG --}}
        <div class="mb-5">
            <h3 class="px-4 mb-2 text-[10px] font-bold text-gray-500 uppercase tracking-wider">Catalog</h3>
            <a href="{{ route('admin.products.index') }}" class="sidebar-link flex items-center gap-3 px-4 py-2.5 text-gray-300 hover:text-white hover:bg-gray-700/50 rounded-xl transition-all mb-1">
                <i data-lucide="package" class="w-5 h-5"></i>
                <span class="text-sm font-medium">Products</span>
            </a>
            <a href="{{ route('admin.categories.index') }}" class="sidebar-link flex items-center gap-3 px-4 py-2.5 text-gray-300 hover:text-white hover:bg-gray-700/50 rounded-xl transition-all mb-1">
                <i data-lucide="grid-3x3" class="w-5 h-5"></i>
                <span class="text-sm font-medium">Categories</span>
            </a>
            <a href="{{ route('admin.brands.index') }}" class="sidebar-link flex items-center gap-3 px-4 py-2.5 text-gray-300 hover:text-white hover:bg-gray-700/50 rounded-xl transition-all mb-1">
                <i data-lucide="tag" class="w-5 h-5"></i>
                <span class="text-sm font-medium">Brands</span>
            </a>
            <a href="{{ route('admin.collections.index') }}" class="sidebar-link flex items-center gap-3 px-4 py-2.5 text-gray-300 hover:text-white hover:bg-gray-700/50 rounded-xl transition-all mb-1">
                <i data-lucide="layout-grid" class="w-5 h-5"></i>
                <span class="text-sm font-medium">Collections</span>
            </a>
            <a href="{{ route('admin.tags.index') }}" class="sidebar-link flex items-center gap-3 px-4 py-2.5 text-gray-300 hover:text-white hover:bg-gray-700/50 rounded-xl transition-all mb-1">
                <i data-lucide="tag" class="w-5 h-5"></i>
                <span class="text-sm font-medium">Tags</span>
            </a>

            {{-- Attributes with submenu (3 real pages) --}}
            <div class="mb-1">
                <button onclick="toggleSubmenu('attributes')" class="w-full flex items-center justify-between px-4 py-2.5 text-gray-300 hover:text-white hover:bg-gray-700/50 rounded-xl transition-all">
                    <div class="flex items-center gap-3">
                        <i data-lucide="palette" class="w-5 h-5"></i>
                        <span class="text-sm font-medium">Attributes</span>
                    </div>
                    <i data-lucide="chevron-down" class="w-4 h-4 transition-transform" id="attributes-icon"></i>
                </button>
                <div id="attributes-submenu" class="hidden ml-4 mt-1 space-y-1">
                    <a href="{{ route('admin.attributes.index') }}" class="sidebar-link block px-4 py-2 text-sm text-gray-400 hover:text-white hover:bg-gray-700/30 rounded-lg">All Attributes</a>
                    <a href="{{ route('admin.attribute-groups.index') }}" class="sidebar-link block px-4 py-2 text-sm text-gray-400 hover:text-white hover:bg-gray-700/30 rounded-lg">Attribute Groups</a>
                    <a href="{{ route('admin.attribute-values.index') }}" class="sidebar-link block px-4 py-2 text-sm text-gray-400 hover:text-white hover:bg-gray-700/30 rounded-lg">Attribute Values</a>
                </div>
            </div>

            <a href="{{ route('admin.reviews.index') }}" class="sidebar-link flex items-center gap-3 px-4 py-2.5 text-gray-300 hover:text-white hover:bg-gray-700/50 rounded-xl transition-all mb-1">
                <i data-lucide="star" class="w-5 h-5"></i>
                <span class="text-sm font-medium">Reviews</span>
            </a>
        </div>

        {{-- INVENTORY --}}
        <div class="mb-5">
            <h3 class="px-4 mb-2 text-[10px] font-bold text-gray-500 uppercase tracking-wider">Inventory</h3>
            <a href="{{ route('admin.stock.index') }}" class="sidebar-link flex items-center gap-3 px-4 py-2.5 text-gray-300 hover:text-white hover:bg-gray-700/50 rounded-xl transition-all mb-1">
                <i data-lucide="clipboard" class="w-5 h-5"></i>
                <span class="text-sm font-medium">Stock Management</span>
            </a>
            <a href="{{ route('admin.warehouses.index') }}" class="sidebar-link flex items-center gap-3 px-4 py-2.5 text-gray-300 hover:text-white hover:bg-gray-700/50 rounded-xl transition-all mb-1">
                <i data-lucide="warehouse" class="w-5 h-5"></i>
                <span class="text-sm font-medium">Warehouses</span>
            </a>
        </div>

        {{-- CUSTOMERS --}}
        <div class="mb-5">
            <h3 class="px-4 mb-2 text-[10px] font-bold text-gray-500 uppercase tracking-wider">Customers</h3>
            <a href="{{ route('admin.customers.index') }}" class="sidebar-link flex items-center gap-3 px-4 py-2.5 text-gray-300 hover:text-white hover:bg-gray-700/50 rounded-xl transition-all mb-1">
                <i data-lucide="users" class="w-5 h-5"></i>
                <span class="text-sm font-medium">Customers</span>
            </a>
        </div>

        {{-- MARKETING --}}
        <div class="mb-5">
            <h3 class="px-4 mb-2 text-[10px] font-bold text-gray-500 uppercase tracking-wider">Marketing</h3>
            <a href="{{ route('admin.promotions.index') }}" class="sidebar-link flex items-center gap-3 px-4 py-2.5 text-gray-300 hover:text-white hover:bg-gray-700/50 rounded-xl transition-all mb-1">
                <i data-lucide="gift" class="w-5 h-5"></i>
                <span class="text-sm font-medium">Promotions</span>
            </a>
            <a href="{{ route('admin.coupons.index') }}" class="sidebar-link flex items-center gap-3 px-4 py-2.5 text-gray-300 hover:text-white hover:bg-gray-700/50 rounded-xl transition-all mb-1">
                <i data-lucide="ticket" class="w-5 h-5"></i>
                <span class="text-sm font-medium">Coupons</span>
            </a>
            <a href="{{ route('admin.email-campaigns.index') }}" class="sidebar-link flex items-center gap-3 px-4 py-2.5 text-gray-300 hover:text-white hover:bg-gray-700/50 rounded-xl transition-all mb-1">
                <i data-lucide="mail" class="w-5 h-5"></i>
                <span class="text-sm font-medium">Email Campaigns</span>
            </a>
        </div>

        {{-- SETTINGS --}}
        <div class="mb-5">
            <h3 class="px-4 mb-2 text-[10px] font-bold text-gray-500 uppercase tracking-wider">Settings</h3>
            <a href="{{ route('admin.settings.index') }}" class="sidebar-link flex items-center gap-3 px-4 py-2.5 text-gray-300 hover:text-white hover:bg-gray-700/50 rounded-xl transition-all mb-1">
                <i data-lucide="settings" class="w-5 h-5"></i>
                <span class="text-sm font-medium">General Settings</span>
            </a>
            <a href="{{ route('admin.settings.media') }}" class="sidebar-link flex items-center gap-3 px-4 py-2.5 text-gray-300 hover:text-white hover:bg-gray-700/50 rounded-xl transition-all mb-1">
                <i data-lucide="image" class="w-5 h-5"></i>
                <span class="text-sm font-medium">Image & Media</span>
            </a>
            <a href="{{ route('admin.integrations.index') }}" class="sidebar-link flex items-center gap-3 px-4 py-2.5 text-gray-300 hover:text-white hover:bg-gray-700/50 rounded-xl transition-all mb-1">
                <i data-lucide="terminal" class="w-5 h-5"></i>
                <span class="text-sm font-medium">Integrations</span>
            </a>
            <a href="{{ route('admin.localization.index') }}" class="sidebar-link flex items-center gap-3 px-4 py-2.5 text-gray-300 hover:text-white hover:bg-gray-700/50 rounded-xl transition-all mb-1">
                <i data-lucide="globe" class="w-5 h-5"></i>
                <span class="text-sm font-medium">Localization</span>
            </a>
            <a href="{{ route('admin.ai-tools.index') }}" class="sidebar-link flex items-center gap-3 px-4 py-2.5 text-gray-300 hover:text-white hover:bg-gray-700/50 rounded-xl transition-all mb-1">
                <i data-lucide="lightbulb" class="w-5 h-5"></i>
                <span class="text-sm font-medium">AI Tools</span>
            </a>
            <a href="{{ route('admin.system-tools.index') }}" class="sidebar-link flex items-center gap-3 px-4 py-2.5 text-gray-300 hover:text-white hover:bg-gray-700/50 rounded-xl transition-all mb-1">
                <i data-lucide="sliders-horizontal" class="w-5 h-5"></i>
                <span class="text-sm font-medium">System Tools</span>
            </a>
        </div>

        {{-- ADMIN --}}
        <div class="mb-5">
            <h3 class="px-4 mb-2 text-[10px] font-bold text-gray-500 uppercase tracking-wider">Admin</h3>
            <div class="mb-1">
                <button onclick="toggleSubmenu('admin')" class="w-full flex items-center justify-between px-4 py-2.5 text-gray-300 hover:text-white hover:bg-gray-700/50 rounded-xl transition-all">
                    <div class="flex items-center gap-3">
                        <i data-lucide="users" class="w-5 h-5"></i>
                        <span class="text-sm font-medium">Admin Users</span>
                    </div>
                    <i data-lucide="chevron-down" class="w-4 h-4 transition-transform" id="admin-icon"></i>
                </button>
                <div id="admin-submenu" class="hidden ml-4 mt-1 space-y-1">
                    <a href="{{ route('admin.users.index') }}" class="sidebar-link block px-4 py-2 text-sm text-gray-400 hover:text-white hover:bg-gray-700/30 rounded-lg">All Users</a>
                    <a href="{{ route('admin.roles.index') }}" class="sidebar-link block px-4 py-2 text-sm text-gray-400 hover:text-white hover:bg-gray-700/30 rounded-lg">Roles</a>
                    <a href="{{ route('admin.permissions.index') }}" class="sidebar-link block px-4 py-2 text-sm text-gray-400 hover:text-white hover:bg-gray-700/30 rounded-lg">Permissions</a>
                </div>
            </div>
            <a href="{{ route('admin.activity-logs.index') }}" class="sidebar-link flex items-center gap-3 px-4 py-2.5 text-gray-300 hover:text-white hover:bg-gray-700/50 rounded-xl transition-all mb-1">
                <i data-lucide="clock" class="w-5 h-5"></i>
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
