<header class="bg-white sticky top-0 z-50 border-b border-gray-200">
    <div class="w-full px-4 lg:px-6">
        <!-- Desktop Header -->
        <div class="hidden lg:flex items-center justify-between h-[70px] gap-8">
            <!-- Left: Hamburger + Logo -->
            <div class="flex items-center gap-6 flex-shrink-0">
                <button class="flex items-center gap-2 group hover:opacity-80 transition-opacity">
                    <div class="flex flex-col gap-[3px]">
                        <span class="w-5 h-[2px] bg-gray-900 transition-colors"></span>
                        <span class="w-5 h-[2px] bg-gray-900 transition-colors"></span>
                        <span class="w-5 h-[2px] bg-gray-900 transition-colors"></span>
                    </div>
                    <div class="text-[11px] font-extrabold text-gray-900 leading-[13px] tracking-tight uppercase">
                        <div>All</div>
                        <div>Sports</div>
                    </div>
                </button>

                <a href="{{ route('home') }}" class="flex items-center gap-1.5 mr-2 xl:mr-6">
                    <svg class="h-[24px] w-auto text-[#0082C3]" viewBox="0 0 200 24" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                        <path d="M19.16 2.62c-2.48 0-4.53 1.35-6.04 3.03L3.89 16.5h4.15l8.1-9.52c1.26-1.45 2.55-2.26 4.09-2.26 2.87 0 4.14 1.8 4.14 4.34 0 3-1.61 5.56-4 8.04H24c3-2.93 4.81-6.19 4.81-9.83 0-3.69-2.28-6.15-6.15-6.15-1.12 0-2.35.25-3.5.5z"/>
                        <text x="31" y="19" font-family="Arial, sans-serif" font-style="italic" font-size="22" font-weight="900" letter-spacing="-0.5">DECATHLON</text>
                    </svg>
                </a>
            </div>

            <!-- Center: Search Bar -->
            <div class="flex-1 w-full mx-2 xl:mx-8">
                <div class="relative flex items-center w-full">
                    <svg class="absolute left-4 w-[18px] h-[18px] text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                    <input type="text" placeholder="Search for &quot;Skating&quot;" class="w-full bg-[#f3f4f6] text-gray-900 text-[14px] rounded-full py-[11px] pl-[44px] pr-4 border-none focus:outline-none focus:ring-1 focus:ring-gray-300 placeholder-gray-500 transition-all">
                </div>
            </div>

            <!-- Right: Location + Icons -->
            <nav class="flex items-center gap-5 flex-shrink-0">
                <!-- Delivery Location -->
                <div class="flex flex-col items-start cursor-pointer group">
                    <span class="text-[11px] text-gray-600 font-normal">Delivery Location</span>
                    <div class="flex items-baseline gap-1 mt-0.5">
                        <span class="text-[13px] font-bold text-[#0082C3]">560001</span>
                        <span class="text-[11px] font-bold text-[#0082C3] underline hover:text-[#006699] transition-colors">CHANGE</span>
                    </div>
                </div>

                <!-- Sign In -->
                <a href="#" class="flex flex-col items-center gap-0.5 group min-w-[50px]">
                    <svg class="w-[22px] h-[22px] text-gray-900 group-hover:text-[#0082C3] transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z"></path>
                    </svg>
                    <span class="text-[10px] font-semibold text-gray-900 group-hover:text-[#0082C3] transition-colors">Sign In</span>
                </a>

                <!-- My Store -->
                <a href="#" class="flex flex-col items-center gap-0.5 group min-w-[50px]">
                    <svg class="w-[22px] h-[22px] text-gray-900 group-hover:text-[#0082C3] transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 21v-7.5a.75.75 0 01.75-.75h3a.75.75 0 01.75.75V21m-4.5 0H2.36m11.14 0H18m0 0h3.64m-1.39 0V9.349m-16.5 11.65V9.35m0 0a3.001 3.001 0 003.75-.615A2.993 2.993 0 009.75 9.75c.896 0 1.7-.393 2.25-1.016a2.993 2.993 0 002.25 1.016c.896 0 1.7-.393 2.25-1.016a3.001 3.001 0 003.75.614m-16.5 0a3.004 3.004 0 01-.621-4.72L4.318 3.44A1.5 1.5 0 015.378 3h13.243a1.5 1.5 0 011.06.44l1.19 1.189a3 3 0 01-.621 4.72m-13.5 8.65h3.75a.75.75 0 00.75-.75V13.5a.75.75 0 00-.75-.75H6.75a.75.75 0 00-.75.75v3.75c0 .415.336.75.75.75z"></path>
                    </svg>
                    <span class="text-[10px] font-semibold text-gray-900 group-hover:text-[#0082C3] transition-colors">My Store</span>
                </a>

                <!-- Support -->
                <a href="#" class="flex flex-col items-center gap-0.5 group min-w-[50px]">
                    <svg class="w-[22px] h-[22px] text-gray-900 group-hover:text-[#0082C3] transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M7.5 8.25h9m-9 3H12m-9.75 1.51c0 1.6 1.123 2.994 2.707 3.227 1.129.166 2.27.293 3.423.379.35.026.67.21.865.501L12 21l2.755-4.133a1.14 1.14 0 01.865-.501 48.172 48.172 0 003.423-.379c1.584-.233 2.707-1.626 2.707-3.228V6.741c0-1.602-1.123-2.995-2.707-3.228A48.394 48.394 0 0012 3c-2.392 0-4.744.175-7.043.513C3.373 3.746 2.25 5.14 2.25 6.741v6.018z" />
                    </svg>
                    <span class="text-[10px] font-semibold text-gray-900 group-hover:text-[#0082C3] transition-colors">Support</span>
                </a>

                <!-- Wishlist -->
                <a href="#" class="flex flex-col items-center gap-0.5 group min-w-[50px]">
                    <svg class="w-[22px] h-[22px] text-gray-900 group-hover:text-[#0082C3] transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12z"></path>
                    </svg>
                    <span class="text-[10px] font-semibold text-gray-900 group-hover:text-[#0082C3] transition-colors">Wishlist</span>
                </a>

                <!-- Cart -->
                <a href="#" class="flex flex-col items-center gap-0.5 group min-w-[50px] relative">
                    <svg class="w-[22px] h-[22px] text-gray-900 group-hover:text-[#0082C3] transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 00-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 00-16.536-1.84M7.5 14.25L5.106 5.272M6 20.25a.75.75 0 11-1.5 0 .75.75 0 011.5 0zm12.75 0a.75.75 0 11-1.5 0 .75.75 0 011.5 0z"></path>
                    </svg>
                    <span class="text-[10px] font-semibold text-gray-900 group-hover:text-[#0082C3] transition-colors">Cart</span>
                </a>
            </nav>
        </div>

        <!-- Mobile Header -->
        <div class="flex lg:hidden items-center justify-between h-16">
            <!-- Left: Hamburger + Logo -->
            <div class="flex items-center gap-3">
                <button class="p-2 -ml-2">
                    <div class="flex flex-col gap-1">
                        <span class="w-5 h-0.5 bg-gray-800"></span>
                        <span class="w-5 h-0.5 bg-gray-800"></span>
                        <span class="w-5 h-0.5 bg-gray-800"></span>
                    </div>
                </button>
                <a href="{{ route('home') }}" class="flex items-center pt-1">
                    <svg class="h-[18px] w-auto text-[#0082C3]" viewBox="0 0 200 24" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                        <path d="M19.16 2.62c-2.48 0-4.53 1.35-6.04 3.03L3.89 16.5h4.15l8.1-9.52c1.26-1.45 2.55-2.26 4.09-2.26 2.87 0 4.14 1.8 4.14 4.34 0 3-1.61 5.56-4 8.04H24c3-2.93 4.81-6.19 4.81-9.83 0-3.69-2.28-6.15-6.15-6.15-1.12 0-2.35.25-3.5.5z"/>
                        <text x="31" y="19" font-family="Arial, sans-serif" font-style="italic" font-size="22" font-weight="900" letter-spacing="-0.5">DECATHLON</text>
                    </svg>
                </a>
            </div>

            <!-- Right: Search + Cart -->
            <div class="flex items-center gap-4">
                <button class="p-2">
                    <svg class="w-6 h-6 text-gray-800" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </button>
                <button class="p-2 -mr-2 relative">
                    <svg class="w-6 h-6 text-gray-800" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 00-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 00-16.536-1.84M7.5 14.25L5.106 5.272M6 20.25a.75.75 0 11-1.5 0 .75.75 0 011.5 0zm12.75 0a.75.75 0 11-1.5 0 .75.75 0 011.5 0z"></path>
                    </svg>
                </button>
            </div>
        </div>
    </div>
</header>
