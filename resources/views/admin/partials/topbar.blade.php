<!-- Topbar -->
<header class="h-16 bg-white border-b border-gray-200 flex items-center justify-between px-6 shadow-sm flex-shrink-0">

    <!-- Left: Mobile Menu + Breadcrumb -->
    <div class="flex items-center gap-4">
        <button onclick="toggleSidebar()" class="lg:hidden text-gray-500 hover:text-gray-700 p-2 hover:bg-gray-100 rounded-lg transition-colors">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
            </svg>
        </button>
        <div class="hidden md:flex items-center gap-2 text-sm">
            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
            </svg>
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
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                </svg>
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
                        <svg class="w-4 h-4 text-[#0082C3]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                        </svg>
                        <h3 class="text-sm font-bold text-gray-900">Notifications</h3>
                        <span id="notifCount" class="text-xs bg-[#0082C3] text-white font-bold px-2 py-0.5 rounded-full hidden">0</span>
                    </div>
                    <button onclick="refreshNotifications()" id="notifRefreshBtn"
                        class="text-xs text-gray-400 hover:text-[#0082C3] transition-colors flex items-center gap-1">
                        <svg id="notifRefreshIcon" class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                        </svg>
                        Refresh
                    </button>
                </div>

                {{-- Body --}}
                <div id="notifList" class="max-h-[420px] overflow-y-auto divide-y divide-gray-50">
                    {{-- Loading state --}}
                    <div id="notifLoading" class="flex items-center justify-center py-10 gap-2 text-gray-400">
                        <svg class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/>
                        </svg>
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
                <svg class="w-3.5 h-3.5 text-gray-400 hidden md:block" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                </svg>
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
                        <svg class="w-4 h-4 text-[#0082C3]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                        <span class="font-semibold">My Profile</span>
                    </a>
                    <a href="{{ route('admin.users.index') }}"
                        class="flex items-center gap-3 px-3 py-2.5 text-sm text-gray-700 hover:bg-gray-50 rounded-xl transition-colors">
                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                        Admin Users
                    </a>
                    <a href="{{ route('admin.settings.index') }}"
                        class="flex items-center gap-3 px-3 py-2.5 text-sm text-gray-700 hover:bg-gray-50 rounded-xl transition-colors">
                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                        Settings
                    </a>
                    <a href="{{ route('admin.activity-logs.index') }}"
                        class="flex items-center gap-3 px-3 py-2.5 text-sm text-gray-700 hover:bg-gray-50 rounded-xl transition-colors">
                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                        </svg>
                        Activity Logs
                    </a>
                    <a href="{{ route('admin.system-tools.index') }}"
                        class="flex items-center gap-3 px-3 py-2.5 text-sm text-gray-700 hover:bg-gray-50 rounded-xl transition-colors">
                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"/>
                        </svg>
                        System Tools
                    </a>
                </div>
                <div class="p-2 border-t border-gray-100">
                    <form action="{{ route('admin.logout') }}" method="POST">
                        @csrf
                        <button type="submit"
                            class="flex items-center gap-3 px-3 py-2.5 text-sm text-red-600 hover:bg-red-50 rounded-xl w-full transition-colors font-medium">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                            </svg>
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
    stock: `<svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>`,
    review: `<svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/></svg>`,
    order: `<svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>`,
    return: `<svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"/></svg>`,
    invoice: `<svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>`,
    coupon: `<svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"/></svg>`,
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
                    <svg class="w-7 h-7 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <p class="text-sm font-semibold text-gray-700">All good!</p>
                <p class="text-xs text-gray-400">No pending alerts right now</p>
            </div>`;
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
