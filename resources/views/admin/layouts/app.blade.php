<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') - Decathlon Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest/dist/umd/lucide.min.js"></script>

    <!-- Sortable.js for Drag & Drop -->
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
    <!-- Custom Searchable Select (Pure Vanilla JS) -->
    <script src="/js/searchable-select.js"></script>
    <!-- Summernote Rich Text Editor (100% Free, No API Key, jQuery-based) -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.js"></script>
    <script src="/js/summernote-helper.js"></script>

    {{-- Google Analytics 4 — auto-inject if GA ID is set --}}
    @php $gaId = \App\Models\Setting::get('ga_measurement_id', ''); @endphp
    @if($gaId)
    <script async src="https://www.googletagmanager.com/gtag/js?id={{ $gaId }}"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());
        gtag('config', '{{ $gaId }}');
    </script>
    @endif

    {{-- Google Tag Manager --}}
    @php $gtmId = \App\Models\Setting::get('gtm_container_id', ''); @endphp
    @if($gtmId)
    <script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src='https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);})(window,document,'script','dataLayer','{{ $gtmId }}');</script>
    @endif

    {{-- Facebook Pixel --}}
    @php $fbPixel = \App\Models\Setting::get('fb_pixel_id', ''); @endphp
    @if($fbPixel)
    <script>!function(f,b,e,v,n,t,s){if(f.fbq)return;n=f.fbq=function(){n.callMethod?n.callMethod.apply(n,arguments):n.queue.push(arguments)};if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';n.queue=[];t=b.createElement(e);t.async=!0;t.src=v;s=b.getElementsByTagName(e)[0];s.parentNode.insertBefore(t,s)}(window,document,'script','https://connect.facebook.net/en_US/fbevents.js');fbq('init','{{ $fbPixel }}');fbq('track','PageView');</script>
    @endif
</head>
<body class="bg-gray-50">
    <!-- Fixed Sidebar -->
    @include('admin.partials.sidebar')

    <!-- Main wrapper — pushed right by sidebar width on lg screens -->
    <div class="lg:ml-64 flex flex-col min-h-screen">
        <!-- Topbar -->
        @include('admin.partials.topbar')

        <!-- Content Area -->
        <main id="main-content" class="flex-1 p-6 relative">
            <!-- Global Skeleton Loader Overlay -->
            <div id="global-skeleton-loader" class="absolute inset-0 bg-gray-50 z-40 p-6 transition-opacity duration-300 pointer-events-auto">
                <div id="skeleton-content" class="space-y-6"></div>
            </div>

            <!-- Actual Page Content -->
            <div id="actual-page-content" class="opacity-0 transition-opacity duration-300 pointer-events-none">
                @yield('content')
            </div>

            <script>
                (function() {
                    const path = window.location.pathname;
                    const skeletonContent = document.getElementById('skeleton-content');
                    
                    let html = '';
                    
                    // Determine type of skeleton based on path
                    if (path === '/admin' || path === '/admin/' || path.includes('/dashboard') || path.includes('/analytics') || path.includes('/reports')) {
                        // Dashboard layout
                        html = `
                            <div class="flex items-center justify-between">
                                <div class="space-y-2">
                                    <div class="h-8 w-48 bg-gray-200 rounded-lg animate-pulse"></div>
                                    <div class="h-4 w-64 bg-gray-200 rounded-lg animate-pulse"></div>
                                </div>
                            </div>
                            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                                ${[1, 2, 3, 4].map(() => `
                                    <div class="bg-white rounded-xl border border-gray-200 p-4 flex items-center gap-3 animate-pulse">
                                        <div class="w-10 h-10 rounded-lg bg-gray-100 flex-shrink-0"></div>
                                        <div class="space-y-2 flex-1">
                                            <div class="h-3 w-16 bg-gray-200 rounded"></div>
                                            <div class="h-6 w-12 bg-gray-200 rounded"></div>
                                        </div>
                                    </div>
                                `).join('')}
                            </div>
                            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                                <div class="lg:col-span-2 bg-white rounded-xl border border-gray-200 p-6 space-y-4 animate-pulse">
                                    <div class="h-5 w-32 bg-gray-200 rounded"></div>
                                    <div class="h-64 bg-gray-100 rounded-xl"></div>
                                </div>
                                <div class="bg-white rounded-xl border border-gray-200 p-6 space-y-4 animate-pulse">
                                    <div class="h-5 w-32 bg-gray-200 rounded"></div>
                                    <div class="space-y-3">
                                        ${[1, 2, 3, 4, 5].map(() => `
                                            <div class="flex items-center gap-3">
                                                <div class="w-8 h-8 rounded-full bg-gray-100"></div>
                                                <div class="space-y-1.5 flex-1">
                                                    <div class="h-3 w-24 bg-gray-200 rounded"></div>
                                                    <div class="h-2 w-16 bg-gray-200 rounded"></div>
                                                </div>
                                            </div>
                                        `).join('')}
                                    </div>
                                </div>
                            </div>
                        `;
                    } else if (path.includes('/create') || path.includes('/edit') || path.includes('/show') || path.includes('/profile') || path.includes('/settings')) {
                        // Form / Create / Edit / Profile layout
                        html = `
                            <div class="flex items-center justify-between">
                                <div class="space-y-2">
                                    <div class="h-8 w-40 bg-gray-200 rounded-lg animate-pulse"></div>
                                    <div class="h-4 w-56 bg-gray-200 rounded-lg animate-pulse"></div>
                                </div>
                                <div class="h-10 w-24 bg-gray-200 rounded-lg animate-pulse"></div>
                            </div>
                            <div class="bg-white rounded-xl border border-gray-200 p-6 space-y-6 animate-pulse">
                                <div class="h-5 w-36 bg-gray-200 rounded mb-4"></div>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    ${[1, 2, 3, 4, 5, 6].map(() => `
                                        <div class="space-y-2">
                                            <div class="h-4 w-20 bg-gray-200 rounded"></div>
                                            <div class="h-10 w-full bg-gray-100 rounded-lg border border-gray-200"></div>
                                        </div>
                                    `).join('')}
                                </div>
                                <div class="space-y-2 pt-2">
                                    <div class="h-4 w-24 bg-gray-200 rounded"></div>
                                    <div class="h-28 w-full bg-gray-100 rounded-lg border border-gray-200"></div>
                                </div>
                                <div class="flex justify-end gap-3 pt-4 border-t border-gray-100">
                                    <div class="h-10 w-20 bg-gray-200 rounded-lg"></div>
                                    <div class="h-10 w-28 bg-gray-200 rounded-lg"></div>
                                </div>
                            </div>
                        `;
                    } else {
                        // Default Table / Index / List page layout
                        html = `
                            <div class="flex items-center justify-between">
                                <div class="space-y-2">
                                    <div class="h-8 w-32 bg-gray-200 rounded-lg animate-pulse"></div>
                                    <div class="h-4 w-48 bg-gray-200 rounded-lg animate-pulse"></div>
                                </div>
                                <div class="h-10 w-28 bg-gray-200 rounded-lg animate-pulse"></div>
                            </div>
                            <div class="bg-white rounded-xl border border-gray-200 p-4 flex gap-3 animate-pulse">
                                <div class="h-10 flex-1 bg-gray-100 rounded-lg"></div>
                                <div class="h-10 w-28 bg-gray-100 rounded-lg"></div>
                                <div class="h-10 w-28 bg-gray-100 rounded-lg"></div>
                            </div>
                            <div class="bg-white rounded-xl border border-gray-200 overflow-hidden animate-pulse">
                                <div class="bg-gray-50 border-b border-gray-200 px-6 py-4 flex justify-between">
                                    <div class="h-4 w-4 bg-gray-200 rounded"></div>
                                    <div class="h-4 w-24 bg-gray-200 rounded"></div>
                                    <div class="h-4 w-16 bg-gray-200 rounded"></div>
                                    <div class="h-4 w-16 bg-gray-200 rounded"></div>
                                    <div class="h-4 w-16 bg-gray-200 rounded"></div>
                                    <div class="h-4 w-20 bg-gray-200 rounded"></div>
                                </div>
                                <div class="divide-y divide-gray-100">
                                    ${[1, 2, 3, 4, 5].map(() => `
                                        <div class="px-6 py-4.5 flex justify-between items-center">
                                            <div class="h-4 w-4 bg-gray-200 rounded"></div>
                                            <div class="flex-1 ml-6 space-y-2">
                                                <div class="h-4 w-48 bg-gray-200 rounded"></div>
                                                <div class="h-3 w-32 bg-gray-100 rounded"></div>
                                            </div>
                                            <div class="h-4 w-16 bg-gray-200 rounded"></div>
                                            <div class="h-6 w-16 bg-gray-100 rounded-full ml-12"></div>
                                            <div class="h-4 w-8 bg-gray-200 rounded ml-12"></div>
                                            <div class="flex gap-2 ml-12">
                                                <div class="h-8 w-8 bg-gray-100 rounded-lg"></div>
                                                <div class="h-8 w-8 bg-gray-100 rounded-lg"></div>
                                            </div>
                                        </div>
                                    `).join('')}
                                </div>
                                <div class="bg-gray-50 border-t border-gray-200 px-6 py-4 flex justify-between items-center">
                                    <div class="h-4 w-36 bg-gray-200 rounded"></div>
                                    <div class="flex gap-1">
                                        <div class="h-8 w-8 bg-gray-200 rounded-lg"></div>
                                        <div class="h-8 w-8 bg-gray-200 rounded-lg"></div>
                                        <div class="h-8 w-8 bg-gray-200 rounded-lg"></div>
                                    </div>
                                </div>
                            </div>
                        `;
                    }
                    
                    skeletonContent.innerHTML = html;
                })();

                // Global skeleton dismiss function — pages can call window.dismissSkeleton()
                // to hide the skeleton when their AJAX data is ready.
                window.dismissSkeleton = function() {
                    const loader = document.getElementById('global-skeleton-loader');
                    const content = document.getElementById('actual-page-content');
                    if (loader && content && loader.style.display !== 'none') {
                        loader.classList.add('opacity-0');
                        loader.style.pointerEvents = 'none';
                        content.classList.remove('opacity-0');
                        content.classList.remove('pointer-events-none');
                        setTimeout(() => { loader.style.display = 'none'; }, 300);
                    }
                };

                // Fallback: auto-dismiss on DOMContentLoaded for non-AJAX pages
                document.addEventListener('DOMContentLoaded', function() {
                    // If page hasn't called dismissSkeleton within 1.5s, dismiss automatically
                    setTimeout(function() {
                        window.dismissSkeleton();
                    }, 1500);
                });
            </script>
        </main>
    </div>

    <!-- Scripts -->
    @include('partials.dialog')
    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            sidebar.classList.toggle('-translate-x-full');
        }
    </script>
    @stack('scripts')
<script>
    window.initLucideIcons = function() {
        if (typeof lucide !== 'undefined') lucide.createIcons();
    };
    document.addEventListener('DOMContentLoaded', window.initLucideIcons);
</script>
</body>
</html>
