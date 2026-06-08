<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Decathlon - Sports Equipment & Sportswear')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    
    <!-- Toastify.js -->
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/toastify-js"></script>

    <script>
        window.appConfig = {
            routes: {
                searchSuggestions: "{{ route('search.suggestions') }}",
                quickView: "/api/quick-view",
                shop: "{{ route('shop') }}"
            }
        };
    </script>

    @vite(['resources/js/app.js'])

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

        /* Loading Spinner */
        .spinner {
            border: 2px solid rgba(255, 255, 255, 0.3);
            border-top: 2px solid #ffffff;
            border-radius: 50%;
            width: 16px;
            height: 16px;
            animation: spin 0.8s linear infinite;
            display: inline-block;
        }
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        /* Toast Overrides */
        .toastify {
            font-family: 'Roboto', sans-serif !important;
            font-weight: 700 !important;
            text-transform: uppercase !important;
            letter-spacing: 0.5px !important;
            font-size: 12px !important;
            border-radius: 8px !important;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1) !important;
        }
    </style>
    @stack('styles')
</head>

<body class="bg-gray-50 pb-16 lg:pb-0">
    <!-- Header -->
    @include('partials.header')

    <!-- Main Content -->
    <main class="relative min-h-screen">
        <!-- Global Storefront Skeleton Loader Overlay -->
        <div id="global-skeleton-loader" class="absolute inset-0 bg-gray-50 z-40 p-4 lg:p-8 transition-opacity duration-300 pointer-events-auto">
            <div id="skeleton-content" class="max-w-7xl mx-auto space-y-8"></div>
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
                
                // Determine layout: Storefront Home / Shop or generic storefront details (cart, checkout)
                if (path === '/' || path === '/home' || path.includes('/shop') || path.includes('/products')) {
                    // E-commerce Home / Shop Grid Layout
                    html = `
                        <!-- Large Hero Banner Shimmer -->
                        <div class="w-full h-48 md:h-80 bg-gray-200 rounded-2xl animate-pulse"></div>
                        
                        <!-- Categories Circle List -->
                        <div class="space-y-3">
                            <div class="h-6 w-36 bg-gray-200 rounded animate-pulse"></div>
                            <div class="flex gap-4 overflow-hidden py-2">
                                ${[1, 2, 3, 4, 5, 6, 7, 8].map(() => `
                                    <div class="flex flex-col items-center gap-2 flex-shrink-0 animate-pulse">
                                        <div class="w-16 h-16 md:w-20 md:h-20 rounded-full bg-gray-200"></div>
                                        <div class="h-3 w-12 bg-gray-200 rounded"></div>
                                    </div>
                                `).join('')}
                            </div>
                        </div>
                        
                        <!-- Products Grid -->
                        <div class="space-y-4">
                            <div class="flex items-center justify-between">
                                <div class="h-6 w-48 bg-gray-200 rounded animate-pulse"></div>
                                <div class="h-4 w-16 bg-gray-200 rounded animate-pulse"></div>
                            </div>
                            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 md:gap-6">
                                ${[1, 2, 3, 4, 5, 6, 7, 8].map(() => `
                                    <div class="bg-white rounded-xl border border-gray-100 p-3 space-y-3 animate-pulse">
                                        <div class="w-full aspect-square bg-gray-100 rounded-lg"></div>
                                        <div class="space-y-2">
                                            <div class="h-4 w-full bg-gray-200 rounded"></div>
                                            <div class="h-3 w-2/3 bg-gray-100 rounded"></div>
                                            <div class="h-4 w-12 bg-gray-200 rounded"></div>
                                            <div class="h-8 w-full bg-[#0082C3]/10 rounded-lg"></div>
                                        </div>
                                    </div>
                                `).join('')}
                            </div>
                        </div>
                    `;
                } else {
                    // Storefront Details / Cart / Checkout / Account details
                    html = `
                        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                            <!-- Left: Cart / Checkout items -->
                            <div class="lg:col-span-2 space-y-6">
                                <div class="h-8 w-48 bg-gray-200 rounded animate-pulse"></div>
                                <div class="bg-white border border-gray-100 rounded-2xl p-6 space-y-4 animate-pulse">
                                    ${[1, 2, 3].map(() => `
                                        <div class="flex items-center gap-4 py-4 border-b border-gray-50 last:border-0">
                                            <div class="w-20 h-20 bg-gray-100 rounded-lg flex-shrink-0"></div>
                                            <div class="space-y-2 flex-1">
                                                <div class="h-4 w-40 bg-gray-200 rounded"></div>
                                                <div class="h-3 w-24 bg-gray-100 rounded"></div>
                                            </div>
                                            <div class="h-5 w-16 bg-gray-200 rounded"></div>
                                        </div>
                                    `).join('')}
                                </div>
                            </div>
                            
                            <!-- Right: Order Summary Sidebar -->
                            <div class="bg-white border border-gray-100 rounded-2xl p-6 space-y-6 animate-pulse">
                                <div class="h-6 w-32 bg-gray-200 rounded mb-4"></div>
                                <div class="space-y-3">
                                    <div class="flex justify-between"><div class="h-3 w-16 bg-gray-200 rounded"></div><div class="h-3 w-12 bg-gray-200 rounded"></div></div>
                                    <div class="flex justify-between"><div class="h-3 w-20 bg-gray-200 rounded"></div><div class="h-3 w-8 bg-gray-200 rounded"></div></div>
                                    <div class="border-t border-gray-100 pt-3 flex justify-between"><div class="h-5 w-16 bg-gray-200 rounded"></div><div class="h-5 w-16 bg-gray-200 rounded"></div></div>
                                </div>
                                <div class="h-12 w-full bg-gray-200 rounded-lg"></div>
                            </div>
                        </div>
                    `;
                }
                
                skeletonContent.innerHTML = html;
            })();

            // Hide loader and display actual storefront content once page finishes loading
            document.addEventListener('DOMContentLoaded', function() {
                const loader = document.getElementById('global-skeleton-loader');
                const content = document.getElementById('actual-page-content');
                if (loader && content) {
                    loader.classList.add('opacity-0');
                    loader.style.pointerEvents = 'none';
                    content.classList.remove('opacity-0');
                    content.classList.remove('pointer-events-none');
                    setTimeout(() => {
                        loader.style.display = 'none';
                    }, 300);
                }
            });
        </script>
    </main>

    <!-- Footer -->
    @include('partials.footer')

    <!-- Dialog -->
    @include('partials.dialog')

    <!-- Quick View -->
    @include('partials.quick-view')

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
        <a href="{{ route('categories') }}" class="flex flex-col items-center gap-1 w-[20%] text-gray-500 hover:text-[#0082C3] transition-colors">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6A2.25 2.25 0 016 3.75h2.25A2.25 2.25 0 0110.5 6v2.25a2.25 2.25 0 01-2.25 2.25H6a2.25 2.25 0 01-2.25-2.25V6zM3.75 15.75A2.25 2.25 0 016 13.5h2.25a2.25 2.25 0 012.25 2.25V18a2.25 2.25 0 01-2.25 2.25H6A2.25 2.25 0 013.75 18v-2.25zM13.5 6a2.25 2.25 0 012.25-2.25H18A2.25 2.25 0 0120.25 6v2.25A2.25 2.25 0 0118 10.5h-2.25a2.25 2.25 0 01-2.25-2.25V6zM13.5 15.75a2.25 2.25 0 012.25-2.25H18a2.25 2.25 0 012.25 2.25V18A2.25 2.25 0 0118 20.25h-2.25A2.25 2.25 0 0113.5 18v-2.25z" />
            </svg>
            <span class="text-[10px] font-semibold">Categories</span>
        </a>

        <!-- Search -->
        <a href="javascript:void(0)" onclick="openSearchOverlay()" class="flex flex-col items-center justify-center w-[20%] -mt-6">
            <div class="bg-[#0082C3] text-white p-3 rounded-full shadow-lg border-4 border-gray-100 flex items-center justify-center relative">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
            </div>
            <span class="text-[10px] font-semibold text-gray-500 mt-1">Search</span>
        </a>

        <!-- Cart -->
        <a href="{{ route('cart') }}" class="flex flex-col items-center gap-1 w-[20%] text-gray-500 hover:text-[#0082C3] transition-colors relative">
            <span class="cart-count absolute top-0 right-2 bg-[#F7C844] text-gray-900 text-[9px] font-black w-4.5 h-4.5 rounded-full flex items-center justify-center border-2 border-white hidden">0</span>
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
