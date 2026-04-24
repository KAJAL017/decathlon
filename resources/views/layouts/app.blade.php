<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Decathlon - Sports Equipment & Sportswear')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            margin: 0;
            padding: 0;
        }
        .scrollbar-hide::-webkit-scrollbar {
            display: none;
        }
        .scrollbar-hide {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }
    </style>
    @stack('styles')
</head>

<body class="bg-gray-50 pb-16 lg:pb-0">
    <!-- Header -->
    @include('partials.header')

    <!-- Main Content -->
    <main>
        @yield('content')
    </main>

    <!-- Footer -->
    @include('partials.footer')

    @stack('scripts')

    <!-- Mobile Bottom Navigation (App-like) -->
    <nav class="lg:hidden fixed bottom-0 left-0 right-0 bg-white border-t border-gray-200 z-50 px-2 py-2 flex justify-between items-center safe-area-bottom">
        <!-- Home (Active) -->
        <a href="{{ route('home') }}" class="flex flex-col items-center gap-1 w-[20%] text-[#0082C3]">
            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                <path d="M11.47 3.841a.75.75 0 011.06 0l8.99 8.99a.75.75 0 11-1.06 1.06L20 13.43V21a1 1 0 01-1 1h-4.5a1 1 0 01-1-1v-4.5h-3V21a1 1 0 01-1 1H4a1 1 0 01-1-1v-7.57l-.46.46a.75.75 0 01-1.06-1.06l8.99-8.99z" />
            </svg>
            <span class="text-[10px] font-semibold">Home</span>
        </a>

        <!-- Categories -->
        <a href="#" class="flex flex-col items-center gap-1 w-[20%] text-gray-500 hover:text-[#0082C3] transition-colors">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6A2.25 2.25 0 016 3.75h2.25A2.25 2.25 0 0110.5 6v2.25a2.25 2.25 0 01-2.25 2.25H6a2.25 2.25 0 01-2.25-2.25V6zM3.75 15.75A2.25 2.25 0 016 13.5h2.25a2.25 2.25 0 012.25 2.25V18a2.25 2.25 0 01-2.25 2.25H6A2.25 2.25 0 013.75 18v-2.25zM13.5 6a2.25 2.25 0 012.25-2.25H18A2.25 2.25 0 0120.25 6v2.25A2.25 2.25 0 0118 10.5h-2.25a2.25 2.25 0 01-2.25-2.25V6zM13.5 15.75a2.25 2.25 0 012.25-2.25H18a2.25 2.25 0 012.25 2.25V18A2.25 2.25 0 0118 20.25h-2.25A2.25 2.25 0 0113.5 18v-2.25z" />
            </svg>
            <span class="text-[10px] font-semibold">Categories</span>
        </a>

        <!-- Search -->
        <a href="#" class="flex flex-col items-center justify-center w-[20%] -mt-6">
            <div class="bg-[#0082C3] text-white p-3 rounded-full shadow-lg border-4 border-gray-50 flex items-center justify-center relative">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
            </div>
            <span class="text-[10px] font-semibold text-gray-500 mt-1">Search</span>
        </a>

        <!-- Cart -->
        <a href="#" class="flex flex-col items-center gap-1 w-[20%] text-gray-500 hover:text-[#0082C3] transition-colors relative">
            <span class="absolute top-0 right-2 bg-yellow-400 text-gray-900 text-[9px] font-bold px-1 rounded-full border-2 border-white">2</span>
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 00-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 00-16.536-1.84M7.5 14.25L5.106 5.272M6 20.25a.75.75 0 11-1.5 0 .75.75 0 011.5 0zm12.75 0a.75.75 0 11-1.5 0 .75.75 0 011.5 0z" />
            </svg>
            <span class="text-[10px] font-semibold">Cart</span>
        </a>

        <!-- Account -->
        <a href="#" class="flex flex-col items-center gap-1 w-[20%] text-gray-500 hover:text-[#0082C3] transition-colors relative">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
            </svg>
            <span class="text-[10px] font-semibold text-center leading-tight mt-0.5">Account</span>
        </a>
    </nav>
</body>

</html>
