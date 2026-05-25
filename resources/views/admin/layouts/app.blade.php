<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') - Decathlon Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- ImageKit SDK -->
    <script src="https://unpkg.com/imagekit-javascript/dist/imagekit.min.js"></script>
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
        <main id="main-content" class="flex-1 p-6">
            @yield('content')
        </main>
    </div>

    <!-- Scripts -->
    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            sidebar.classList.toggle('-translate-x-full');
        }
    </script>
    @stack('scripts')
</body>
</html>
