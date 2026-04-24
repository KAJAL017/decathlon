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
    <!-- Custom Searchable Select (Pure Vanilla JS) -->
    <script src="/js/searchable-select.js"></script>
    <!-- Summernote Rich Text Editor (100% Free, No API Key, jQuery-based) -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.js"></script>
    <script src="/js/summernote-helper.js"></script>
</head>
<body class="bg-gray-50">
    <div class="flex h-screen overflow-hidden">
        <!-- Sidebar -->
        @include('admin.partials.sidebar')

        <!-- Main Content -->
        <div class="flex-1 flex flex-col overflow-hidden">
            <!-- Topbar -->
            @include('admin.partials.topbar')

            <!-- Content Area -->
            <main id="main-content" class="flex-1 overflow-y-auto p-6">
                @yield('content')
            </main>
        </div>
    </div>

    <!-- Scripts -->
    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            sidebar.classList.toggle('-translate-x-full');
        }

        function toggleDropdown(id) {
            const dropdown = document.getElementById(id);
            dropdown.classList.toggle('hidden');
        }

        document.addEventListener('click', function(event) {
            const dropdowns = document.querySelectorAll('[id$="Dropdown"]');
            dropdowns.forEach(dropdown => {
                if (!event.target.closest('.dropdown-trigger')) {
                    dropdown.classList.add('hidden');
                }
            });
        });
    </script>
    @stack('scripts')
</body>
</html>
