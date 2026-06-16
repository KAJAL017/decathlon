<!-- Topbar -->
<header class="h-16 bg-white border-b border-gray-200 flex items-center justify-between px-6 shadow-sm flex-shrink-0">

    <!-- Left: Mobile Menu + Breadcrumb -->
    <div class="flex items-center gap-4">
        <button onclick="toggleSidebar()" class="lg:hidden text-gray-500 hover:text-gray-700 p-2 hover:bg-gray-100 rounded-lg transition-colors">
            <i data-lucide="menu" class="w-6 h-6"></i>
        </button>
        <div class="hidden md:flex items-center gap-2 text-sm">
            <i data-lucide="home" class="w-4 h-4 text-gray-400"></i>
            <span class="text-gray-400">Admin</span>
            <span class="text-gray-300">/</span>
            <span class="text-gray-800 font-semibold">@yield('page-title', 'Dashboard')</span>
        </div>
    </div>

    <!-- Right: Notifications + Profile -->
    <div class="flex items-center gap-2">

        {{-- ── NOTIFICATIONS ── --}}
        <div class="relative" id="notifWrapper">
            <button onclick="toggleNotifications()" id="notifBtn"
                class="relative p-2.5 text-gray-500 hover:text-gray-700 hover:bg-gray-100 rounded-xl transition-colors">
                <i data-lucide="bell" class="w-5 h-5"></i>
                {{-- Badge --}}
                <span id="notifBadge"
                    class="absolute -top-0.5 -right-0.5 min-w-[18px] h-[18px] bg-red-500 text-white text-[10px] font-bold rounded-full flex items-center justify-center px-1 ring-2 ring-white hidden">
                    0
                </span>
            </button>

            {{-- Dropdown --}}
            <div id="notifDropdown"
                class="hidden absolute right-0 mt-2 w-96 bg-white rounded-2xl shadow-2xl border border-gray-100 z-50 overflow-hidden">

                {{-- Header --}}
                <div class="flex items-center justify-between px-5 py-3.5 border-b border-gray-100 bg-gray-50">
                    <div class="flex items-center gap-2">
                        <i data-lucide="bell" class="w-4 h-4 text-[#0082C3]"></i>
                        <h3 class="text-sm font-bold text-gray-900">Notifications</h3>
                        <span id="notifCount" class="text-xs bg-[#0082C3] text-white font-bold px-2 py-0.5 rounded-full hidden">0</span>
                    </div>
                    <button onclick="refreshNotifications()" id="notifRefreshBtn"
                        class="text-xs text-gray-400 hover:text-[#0082C3] transition-colors flex items-center gap-1">
                        <i id="notifRefreshIcon" data-lucide="refresh-cw" class="w-3.5 h-3.5"></i>
                        Refresh
                    </button>
                </div>

                {{-- Body --}}
                <div id="notifList" class="max-h-[420px] overflow-y-auto divide-y divide-gray-50">
                    {{-- Loading state --}}
                    <div id="notifLoading" class="flex items-center justify-center py-10 gap-2 text-gray-400">
                        <i data-lucide="loader" class="w-4 h-4 animate-spin"></i>
                        <span class="text-sm">Loading...</span>
                    </div>
                </div>

                {{-- Footer --}}
                <div class="px-5 py-3 border-t border-gray-100 bg-gray-50 flex items-center justify-between">
                    <span id="notifFooterText" class="text-xs text-gray-400">Loading...</span>
                    <span class="text-xs text-gray-300">Auto-refreshes every 60s</span>
                </div>
            </div>
        </div>

        {{-- ── PROFILE ── --}}
        <div class="relative" id="profileWrapper">
            <button onclick="toggleProfile()" id="profileBtn"
                class="flex items-center gap-2 px-2 py-1.5 hover:bg-gray-100 rounded-xl transition-colors">
                <div class="w-8 h-8 bg-gradient-to-br from-[#0082C3] to-[#005a8c] rounded-lg flex items-center justify-center text-white font-bold text-sm shadow">
                    {{ strtoupper(substr(session('admin_name', session('admin_email', 'A')), 0, 1)) }}
                </div>
                <div class="hidden md:block text-left">
                    <p class="text-xs font-bold text-gray-800 leading-tight">{{ session('admin_name', 'Admin') }}</p>
                    <p class="text-[10px] text-gray-400 leading-tight">{{ session('admin_role', 'Administrator') }}</p>
                </div>
                <i data-lucide="chevron-down" class="w-3.5 h-3.5 text-gray-400 hidden md:block"></i>
            </button>

            <div id="profileDropdown"
                class="hidden absolute right-0 mt-2 w-56 bg-white rounded-2xl shadow-2xl border border-gray-100 z-50 overflow-hidden">
                <div class="px-4 py-3.5 border-b border-gray-100 bg-gray-50">
                    <p class="text-sm font-bold text-gray-900">{{ session('admin_name', 'Admin User') }}</p>
                    <p class="text-xs text-gray-500 mt-0.5 truncate">{{ session('admin_email', 'admin@decathlon.com') }}</p>
                </div>
                <div class="p-2">
                    <a href="{{ route('admin.profile') }}"
                        class="flex items-center gap-3 px-3 py-2.5 text-sm text-gray-700 hover:bg-gray-50 rounded-xl transition-colors">
                        <i data-lucide="user" class="w-4 h-4 text-[#0082C3]"></i>
                        <span class="font-semibold">My Profile</span>
                    </a>
                    <a href="{{ route('admin.users.index') }}"
                        class="flex items-center gap-3 px-3 py-2.5 text-sm text-gray-700 hover:bg-gray-50 rounded-xl transition-colors">
                        <i data-lucide="user" class="w-4 h-4 text-gray-400"></i>
                        Admin Users
                    </a>
                    <a href="{{ route('admin.settings.index') }}"
                        class="flex items-center gap-3 px-3 py-2.5 text-sm text-gray-700 hover:bg-gray-50 rounded-xl transition-colors">
                        <i data-lucide="settings" class="w-4 h-4 text-gray-400"></i>
                        Settings
                    </a>
                    <a href="{{ route('admin.activity-logs.index') }}"
                        class="flex items-center gap-3 px-3 py-2.5 text-sm text-gray-700 hover:bg-gray-50 rounded-xl transition-colors">
                        <i data-lucide="clipboard" class="w-4 h-4 text-gray-400"></i>
                        Activity Logs
                    </a>
                    <a href="{{ route('admin.system-tools.index') }}"
                        class="flex items-center gap-3 px-3 py-2.5 text-sm text-gray-700 hover:bg-gray-50 rounded-xl transition-colors">
                        <i data-lucide="sliders-horizontal" class="w-4 h-4 text-gray-400"></i>
                        System Tools
                    </a>
                </div>
                <div class="p-2 border-t border-gray-100">
                    <form action="{{ route('admin.logout') }}" method="POST">
                        @csrf
                        <button type="submit"
                            class="flex items-center gap-3 px-3 py-2.5 text-sm text-red-600 hover:bg-red-50 rounded-xl w-full transition-colors font-medium">
                            <i data-lucide="log-out" class="w-4 h-4"></i>
                            Logout
                        </button>
                    </form>
                </div>
            </div>
        </div>

    </div>
</header>

<script>
// ── Icon SVGs per type ────────────────────────────────────────────
const NOTIF_ICONS = {
    stock: `<i data-lucide="clipboard" class="w-5 h-5 text-white"></i>`,
    review: `<i data-lucide="star" class="w-5 h-5 text-white"></i>`,
    order: `<i data-lucide="shopping-bag" class="w-5 h-5 text-white"></i>`,
    return: `<i data-lucide="rotate-ccw" class="w-5 h-5 text-white"></i>`,
    invoice: `<i data-lucide="file-text" class="w-5 h-5 text-white"></i>`,
    coupon: `<i data-lucide="ticket" class="w-5 h-5 text-white"></i>`,
};

const NOTIF_BG = {
    red:    'bg-red-500',
    orange: 'bg-orange-500',
    yellow: 'bg-yellow-500',
    blue:   'bg-blue-500',
    green:  'bg-green-500',
    purple: 'bg-purple-500',
};

let notifOpen = false;
let notifLoaded = false;
let notifAutoTimer = null;

function toggleNotifications() {
    notifOpen = !notifOpen;
    document.getElementById('notifDropdown').classList.toggle('hidden', !notifOpen);
    document.getElementById('profileDropdown').classList.add('hidden');

    if (notifOpen && !notifLoaded) {
        loadNotifications();
    }
}

function toggleProfile() {
    const pd = document.getElementById('profileDropdown');
    pd.classList.toggle('hidden');
    document.getElementById('notifDropdown').classList.add('hidden');
    notifOpen = false;
}

function refreshNotifications() {
    notifLoaded = false;
    const icon = document.getElementById('notifRefreshIcon');
    icon.classList.add('animate-spin');
    loadNotifications(() => {
        setTimeout(() => icon.classList.remove('animate-spin'), 500);
    });
}

function loadNotifications(cb) {
    fetch('/admin/notifications', {
        credentials: 'same-origin',
        headers: { 'Accept': 'application/json' }
    })
    .then(r => r.json())
    .then(res => {
        notifLoaded = true;
        renderNotifications(res);
        if (cb) cb();
    })
    .catch(() => {
        document.getElementById('notifLoading').innerHTML =
            '<p class="text-xs text-gray-400 text-center py-6">Could not load notifications</p>';
        if (cb) cb();
    });
}

function renderNotifications(res) {
    const list    = document.getElementById('notifList');
    const badge   = document.getElementById('notifBadge');
    const count   = document.getElementById('notifCount');
    const footer  = document.getElementById('notifFooterText');
    const loading = document.getElementById('notifLoading');

    const notifs  = res.notifications || [];
    const unread  = res.unread || 0;

    // Update bell badge
    if (unread > 0) {
        badge.textContent = unread > 99 ? '99+' : unread;
        badge.classList.remove('hidden');
    } else {
        badge.classList.add('hidden');
    }

    // Update count pill in header
    if (notifs.length > 0) {
        count.textContent = notifs.length;
        count.classList.remove('hidden');
    } else {
        count.classList.add('hidden');
    }

    // Footer text
    footer.textContent = notifs.length > 0
        ? notifs.length + ' alert' + (notifs.length > 1 ? 's' : '') + ' require attention'
        : 'All clear — no alerts';

    // Empty state
    if (!notifs.length) {
        list.innerHTML = `
            <div class="flex flex-col items-center justify-center py-10 gap-3">
                <div class="w-14 h-14 bg-green-50 rounded-2xl flex items-center justify-center">
                    <i data-lucide="check" class="w-7 h-7 text-green-500"></i>
                </div>
                <p class="text-sm font-semibold text-gray-700">All good!</p>
                <p class="text-xs text-gray-400">No pending alerts right now</p>
            </div>`;
        if (typeof lucide !== 'undefined') lucide.createIcons();
        return;
    }

    // Render notifications
    list.innerHTML = notifs.map(n => `
        <a href="${n.link || '#'}" onclick="closeNotifications()"
            class="flex items-start gap-3 px-4 py-3.5 hover:bg-gray-50 transition-colors group">
            <div class="w-9 h-9 ${NOTIF_BG[n.color] || 'bg-gray-500'} rounded-xl flex items-center justify-center flex-shrink-0 shadow-sm group-hover:scale-105 transition-transform">
                ${NOTIF_ICONS[n.icon] || NOTIF_ICONS.order}
            </div>
            <div class="flex-1 min-w-0">
                <p class="text-sm font-semibold text-gray-900 leading-tight">${escHtml(n.title)}</p>
                <p class="text-xs text-gray-500 mt-0.5">${escHtml(n.sub || '')}</p>
            </div>
            <div class="flex flex-col items-end gap-1 flex-shrink-0">
                <span class="text-[10px] text-gray-400 font-medium">${escHtml(n.time || '')}</span>
                ${n.badge > 0 ? `<span class="text-[10px] font-bold px-1.5 py-0.5 rounded-full ${getBadgeClass(n.color)}">${n.badge}</span>` : ''}
            </div>
        </a>
    `).join('');
    if (typeof lucide !== 'undefined') lucide.createIcons();
}

function getBadgeClass(color) {
    const map = {
        red:    'bg-red-100 text-red-700',
        orange: 'bg-orange-100 text-orange-700',
        yellow: 'bg-yellow-100 text-yellow-700',
        blue:   'bg-blue-100 text-blue-700',
        green:  'bg-green-100 text-green-700',
        purple: 'bg-purple-100 text-purple-700',
    };
    return map[color] || 'bg-gray-100 text-gray-600';
}

function escHtml(s) {
    return String(s ?? '').replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;');
}

function closeNotifications() {
    notifOpen = false;
    document.getElementById('notifDropdown').classList.add('hidden');
    // Reset loaded flag so next open fetches fresh data
    notifLoaded = false;
}

// Close on outside click
document.addEventListener('click', function(e) {
    if (!e.target.closest('#notifWrapper')) {
        document.getElementById('notifDropdown').classList.add('hidden');
        notifOpen = false;
    }
    if (!e.target.closest('#profileWrapper')) {
        document.getElementById('profileDropdown').classList.add('hidden');
    }
});

// Auto-load badge count on page load (silent — no dropdown open)
document.addEventListener('DOMContentLoaded', function() {
    // Load badge count silently
    fetch('/admin/notifications', {
        credentials: 'same-origin',
        headers: { 'Accept': 'application/json' }
    })
    .then(r => r.json())
    .then(res => {
        const badge  = document.getElementById('notifBadge');
        const unread = res.unread || 0;
        if (unread > 0) {
            badge.textContent = unread > 99 ? '99+' : unread;
            badge.classList.remove('hidden');
        }
    })
    .catch(() => {});

    // Auto-refresh every 60 seconds
    notifAutoTimer = setInterval(() => {
        silentRefreshBadge();
    }, 60000);

    // Refresh badge when user comes back to this tab/page
    document.addEventListener('visibilitychange', () => {
        if (document.visibilityState === 'visible') {
            silentRefreshBadge();
        }
    });

    // Also refresh when window gets focus (user switches back from another tab)
    window.addEventListener('focus', () => {
        silentRefreshBadge();
    });
});

function silentRefreshBadge() {
    fetch('/admin/notifications', {
        credentials: 'same-origin',
        headers: { 'Accept': 'application/json' }
    })
    .then(r => r.json())
    .then(res => {
        const badge  = document.getElementById('notifBadge');
        const unread = res.unread || 0;
        if (unread > 0) {
            badge.textContent = unread > 99 ? '99+' : unread;
            badge.classList.remove('hidden');
        } else {
            badge.classList.add('hidden');
        }
        // If dropdown is open, refresh content too
        if (notifOpen) {
            renderNotifications(res);
            notifLoaded = true;
        }
    })
    .catch(() => {});
}
</script>
