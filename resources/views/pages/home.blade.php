@extends('layouts.app')

@section('title', 'Decathlon - Sports Equipment & Sportswear')

@section('content')
 <!-- Hero Section (Categories) -->
    <section id="hero" class="w-full mt-4 mb-8">
        <div class="w-full flex items-start gap-3 md:gap-4 lg:gap-6 overflow-x-auto snap-x snap-mandatory pb-4 scrollbar-hide px-4 lg:px-6">
            <!-- Winter Ready -->
            <div class="flex-shrink-0 w-[40vw] md:w-[130px] snap-start cursor-pointer group flex flex-col">
                <div class="w-full h-[130px] rounded-t-md overflow-hidden bg-white">
                    <img src="https://images.unsplash.com/photo-1551524559-8af4e6624178?w=500&auto=format&fit=crop&q=60"
                        alt="Winter Ready" class="w-full h-full object-cover">
                </div>
                <div class="w-full bg-[#525252] py-1.5 rounded-b-md flex items-center justify-center">
                    <span class="text-white text-[12px] font-bold tracking-wide">Winter Ready</span>
                </div>
            </div>

            <!-- Activewear -->
            <div class="flex-shrink-0 w-[40vw] md:w-[130px] snap-start cursor-pointer group flex flex-col">
                <div class="w-full h-[130px] rounded-t-md overflow-hidden bg-white">
                    <img src="https://images.unsplash.com/photo-1483721310020-03333e577078?w=500&auto=format&fit=crop&q=60"
                        alt="Activewear" class="w-full h-full object-cover">
                </div>
                <div class="w-full bg-[#525252] py-1.5 rounded-b-md flex items-center justify-center">
                    <span class="text-white text-[12px] font-bold tracking-wide">Activewear</span>
                </div>
            </div>

            <!-- Gym & Fitness -->
            <div class="flex-shrink-0 w-[40vw] md:w-[130px] snap-start cursor-pointer group flex flex-col">
                <div class="w-full h-[130px] rounded-t-md overflow-hidden bg-white">
                    <img src="https://images.unsplash.com/photo-1517836357463-d25dfeac3438?w=500&auto=format&fit=crop&q=60"
                        alt="Gym & Fitness" class="w-full h-full object-cover icon-center">
                </div>
                <div class="w-full bg-[#525252] py-1.5 rounded-b-md flex items-center justify-center">
                    <span class="text-white text-[12px] font-bold tracking-wide">Gym & Fitness</span>
                </div>
            </div>

            <!-- Cycles -->
            <div class="flex-shrink-0 w-[40vw] md:w-[130px] snap-start cursor-pointer group flex flex-col">
                <div class="w-full h-[130px] rounded-t-md overflow-hidden bg-white">
                    <img src="https://images.unsplash.com/photo-1485965120184-e220f721d03e?w=500&auto=format&fit=crop&q=60"
                        alt="Cycles" class="w-full h-full object-cover">
                </div>
                <div class="w-full bg-[#525252] py-1.5 rounded-b-md flex items-center justify-center">
                    <span class="text-white text-[12px] font-bold tracking-wide">Cycles</span>
                </div>
            </div>

            <!-- Hiking & Trekking -->
            <div class="flex-shrink-0 w-[40vw] md:w-[130px] snap-start cursor-pointer group flex flex-col">
                <div class="w-full h-[130px] rounded-t-md overflow-hidden bg-white">
                    <img src="https://images.unsplash.com/photo-1551632811-561732d1e306?w=500&auto=format&fit=crop&q=60"
                        alt="Hiking & Trekking" class="w-full h-full object-cover">
                </div>
                <div class="w-full bg-[#525252] py-1.5 rounded-b-md flex items-center justify-center">
                    <span class="text-white text-[12px] font-bold tracking-wide">Hiking & Trekking</span>
                </div>
            </div>

            <!-- Shoes -->
            <div class="flex-shrink-0 w-[40vw] md:w-[130px] snap-start cursor-pointer group flex flex-col">
                <div class="w-full h-[130px] rounded-t-md overflow-hidden bg-white">
                    <img src="https://images.unsplash.com/photo-1542291026-7eec264c27ff?w=500&auto=format&fit=crop&q=60"
                        alt="Shoes" class="w-full h-full object-cover">
                </div>
                <div class="w-full bg-[#525252] py-1.5 rounded-b-md flex items-center justify-center">
                    <span class="text-white text-[12px] font-bold tracking-wide">Shoes</span>
                </div>
            </div>

            <!-- Bag & Backpacks -->
            <div class="flex-shrink-0 w-[40vw] md:w-[130px] snap-start cursor-pointer group flex flex-col">
                <div class="w-full h-[130px] rounded-t-md overflow-hidden bg-white">
                    <img src="https://images.unsplash.com/photo-1553062407-98eeb64c6a62?w=500&auto=format&fit=crop&q=60"
                        alt="Bag & Backpacks" class="w-full h-full object-cover">
                </div>
                <div class="w-full bg-[#525252] py-1.5 rounded-b-md flex items-center justify-center">
                    <span class="text-white text-[12px] font-bold tracking-wide">Bag & Backpacks</span>
                </div>
            </div>

            <!-- Sports Accessories -->
            <div class="flex-shrink-0 w-[40vw] md:w-[130px] snap-start cursor-pointer group flex flex-col">
                <div class="w-full h-[130px] rounded-t-md overflow-hidden bg-white">
                    <img src="https://images.unsplash.com/photo-1584735935682-2f2b69dff9d2?w=500&auto=format&fit=crop&q=60"
                        alt="Sports Accessories" class="w-full h-full object-cover">
                </div>
                <div class="w-full bg-[#525252] py-1.5 rounded-b-md flex items-center justify-center">
                    <span class="text-white text-[12px] font-bold tracking-wide">Sports Accessories</span>
                </div>
            </div>
        </div>
    </section>

    <!-- Hero Slider -->
    <section class="w-full px-4 lg:px-6 mb-8">
        <div class="w-full relative w-full h-[450px] md:h-[323px] rounded-[30px] overflow-hidden group">
            <!-- Slider Track -->
            <div id="slider-track" class="flex h-full transition-transform duration-500 ease-in-out">
                <!-- Slide 1 (Pro-Grade Rackets - Exact Reference Match) -->
                <div class="min-w-full h-full bg-[#f5e6d3] relative flex flex-col md:flex-row items-center overflow-hidden">
                    <!-- Left Section: Product Image with White Card Background -->
                    <div class="w-full md:w-[45%] h-1/2 md:h-full relative flex items-center justify-center">
                        <!-- White tilted card background -->
                        <div
                            class="absolute inset-0 bg-gradient-to-br from-gray-100 to-gray-200 transform -skew-y-2 origin-bottom-left">
                        </div>

                        <!-- Product Image (Tilted) -->
                        <div class="relative z-10 w-full h-full flex items-center justify-center">
                            <img src="https://images.unsplash.com/photo-1617083278968-e6ee43f9b0c0?w=800&auto=format&fit=crop&q=80"
                                alt="Pro-Grade Rackets"
                                class="max-h-[85%] max-w-[85%] object-contain drop-shadow-2xl transform -rotate-12">
                        </div>
                    </div>

                    <!-- Wavy Divider -->
                    <div class="hidden md:block absolute left-[45%] top-0 h-full w-[80px] z-10">
                        <svg viewBox="0 0 80 400" class="h-full w-full" preserveAspectRatio="none">
                            <path d="M 0 0 Q 40 50, 0 100 T 0 200 T 0 300 T 0 400 L 80 400 L 80 0 Z" fill="#f5e6d3"
                                stroke="none" />
                        </svg>
                    </div>

                    <!-- Right Section: Text Content -->
                    <div class="w-full md:w-[55%] h-1/2 md:h-full flex items-center justify-center md:justify-start px-4 md:pl-16 md:pr-8 relative text-center md:text-left">
                        <!-- Decorative Yellow Blob -->
                        <div
                            class="absolute -right-16 -top-16 w-48 h-48 bg-yellow-400 rounded-full blur-3xl opacity-70">
                        </div>

                        <!-- Decorative Dots Pattern -->
                        <div class="absolute top-8 right-24 flex gap-2 opacity-40">
                            <div class="w-2 h-2 rounded-full bg-gray-400"></div>
                            <div class="w-2 h-2 rounded-full bg-gray-400"></div>
                            <div class="w-2 h-2 rounded-full bg-gray-400"></div>
                        </div>
                        <div class="absolute top-16 right-20 flex gap-2 opacity-40">
                            <div class="w-2 h-2 rounded-full bg-gray-400"></div>
                            <div class="w-2 h-2 rounded-full bg-gray-400"></div>
                        </div>

                        <!-- Teal Accent Shape -->
                        <div class="absolute bottom-12 left-8">
                            <svg width="60" height="50" viewBox="0 0 60 50" fill="none">
                                <path d="M10 0 L50 10 L40 40 L0 30 Z" fill="#2dd4bf" opacity="0.8" />
                            </svg>
                        </div>

                        <div class="relative z-10 flex flex-col items-center md:items-start">
                            <h3 class="text-sm md:text-xl font-medium text-gray-800 mb-1">Engineered Performance For
                                Every Player</h3>
                            <h1 class="text-3xl md:text-5xl font-bold text-gray-900 leading-tight mb-3">
                                Pro-Grade <span class="italic font-serif">Rackets</span>
                            </h1>
                            <div class="flex items-baseline gap-2 mb-4">
                                <span class="text-base text-gray-600 font-medium">Starting from</span>
                                <span class="text-4xl font-bold text-gray-900">₹299</span>
                                <span class="text-base text-gray-600 font-medium">Onwards</span>
                            </div>
                            <button
                                class="bg-white text-gray-900 px-7 py-2.5 rounded-full font-bold text-sm shadow-md hover:shadow-lg hover:scale-105 transition-all duration-300">
                                Shop Now
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Slide 2 (Hiking Boots - Reference Style) -->
                <div class="min-w-full h-full bg-[#e8d9f5] relative flex flex-col md:flex-row items-center overflow-hidden">
                    <div class="w-full md:w-[45%] h-1/2 md:h-full relative flex items-center justify-center">
                        <div
                            class="absolute inset-0 bg-gradient-to-br from-blue-50 to-blue-100 transform -skew-y-2 origin-bottom-left">
                        </div>
                        <div class="relative z-10 w-full h-full flex items-center justify-center">
                            <img src="https://images.unsplash.com/photo-1551632811-561732d1e306?w=800&auto=format&fit=crop&q=80"
                                alt="Hiking Boots"
                                class="max-h-[85%] max-w-[85%] object-contain drop-shadow-2xl transform rotate-12">
                        </div>
                    </div>

                    <!-- Wavy Divider -->
                    <div class="hidden md:block absolute left-[45%] top-0 h-full w-[80px] z-10">
                        <svg viewBox="0 0 80 400" class="h-full w-full" preserveAspectRatio="none">
                            <path d="M 0 0 Q 40 50, 0 100 T 0 200 T 0 300 T 0 400 L 80 400 L 80 0 Z" fill="#e8d9f5"
                                stroke="none" />
                        </svg>
                    </div>

                    <!-- Right Section: Text Content -->
                    <div class="w-full md:w-[55%] h-1/2 md:h-full flex items-center justify-center md:justify-start px-4 md:pl-16 md:pr-8 relative text-center md:text-left">
                        <div class="absolute -right-16 -top-16 w-48 h-48 bg-blue-400 rounded-full blur-3xl opacity-70">
                        </div>

                        <div class="absolute top-8 right-24 flex gap-2 opacity-40">
                            <div class="w-2 h-2 rounded-full bg-gray-400"></div>
                            <div class="w-2 h-2 rounded-full bg-gray-400"></div>
                            <div class="w-2 h-2 rounded-full bg-gray-400"></div>
                        </div>

                        <div class="absolute bottom-12 left-8">
                            <svg width="60" height="50" viewBox="0 0 60 50" fill="none">
                                <path d="M10 0 L50 10 L40 40 L0 30 Z" fill="#10b981" opacity="0.8" />
                            </svg>
                        </div>

                        <div class="relative z-10 flex flex-col items-center md:items-start">
                            <h3 class="text-sm md:text-xl font-medium text-gray-800 mb-1">Adventure Awaits</h3>
                            <h1 class="text-3xl md:text-5xl font-bold text-gray-900 leading-tight mb-3">
                                Tough <span class="italic font-serif">Hiking Boots</span>
                            </h1>
                            <div class="flex items-baseline gap-2 mb-4">
                                <span class="text-base text-gray-600 font-medium">Flat</span>
                                <span class="text-4xl font-bold text-gray-900">20% OFF</span>
                            </div>
                            <button
                                class="bg-white text-gray-900 px-7 py-2.5 rounded-full font-bold text-sm shadow-md hover:shadow-lg hover:scale-105 transition-all duration-300">
                                View Collection
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Slide 3 (Running Shoes - Reference Style) -->
                <div class="min-w-full h-full bg-[#d3e6f5] relative flex flex-col md:flex-row items-center overflow-hidden">
                    <div class="w-full md:w-[45%] h-1/2 md:h-full relative flex items-center justify-center">
                        <div
                            class="absolute inset-0 bg-gradient-to-br from-gray-50 to-gray-100 transform -skew-y-2 origin-bottom-left">
                        </div>
                        <div class="relative z-10 w-full h-full flex items-center justify-center">
                            <img src="https://images.unsplash.com/photo-1542291026-7eec264c27ff?w=800&auto=format&fit=crop&q=80"
                                alt="Running Shoes"
                                class="max-h-[85%] max-w-[85%] object-contain drop-shadow-2xl transform -rotate-12">
                        </div>
                    </div>

                    <!-- Wavy Divider -->
                    <div class="hidden md:block absolute left-[45%] top-0 h-full w-[80px] z-10">
                        <svg viewBox="0 0 80 400" class="h-full w-full" preserveAspectRatio="none">
                            <path d="M 0 0 Q 40 50, 0 100 T 0 200 T 0 300 T 0 400 L 80 400 L 80 0 Z" fill="#d3e6f5"
                                stroke="none" />
                        </svg>
                    </div>

                    <!-- Right Section: Text Content -->
                    <div class="w-full md:w-[55%] h-1/2 md:h-full flex items-center justify-center md:justify-start px-4 md:pl-16 md:pr-8 relative text-center md:text-left">
                        <div class="absolute -right-16 -top-16 w-48 h-48 bg-red-400 rounded-full blur-3xl opacity-70">
                        </div>

                        <div class="absolute top-8 right-24 flex gap-2 opacity-40">
                            <div class="w-2 h-2 rounded-full bg-gray-400"></div>
                            <div class="w-2 h-2 rounded-full bg-gray-400"></div>
                            <div class="w-2 h-2 rounded-full bg-gray-400"></div>
                        </div>

                        <div class="absolute bottom-12 left-8">
                            <svg width="60" height="50" viewBox="0 0 60 50" fill="none">
                                <path d="M10 0 L50 10 L40 40 L0 30 Z" fill="#f59e0b" opacity="0.8" />
                            </svg>
                        </div>

                        <div class="relative z-10 flex flex-col items-center md:items-start">
                            <h3 class="text-sm md:text-xl font-medium text-gray-800 mb-1">Run Further</h3>
                            <h1 class="text-3xl md:text-5xl font-bold text-gray-900 leading-tight mb-3">
                                Premium <span class="italic font-serif">Running Gear</span>
                            </h1>
                            <div class="flex items-baseline gap-2 mb-4">
                                <span class="text-base text-gray-600 font-medium">Starting from</span>
                                <span class="text-4xl font-bold text-gray-900">₹1299</span>
                            </div>
                            <button
                                class="bg-white text-gray-900 px-7 py-2.5 rounded-full font-bold text-sm shadow-md hover:shadow-lg hover:scale-105 transition-all duration-300">
                                Explore
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Navigation Buttons -->
            <button id="prev-btn"
                class="absolute left-4 top-1/2 transform -translate-y-1/2 bg-white/80 hover:bg-white text-gray-800 w-10 h-10 rounded-full flex items-center justify-center shadow-md transition-all opacity-0 group-hover:opacity-100 z-20 border border-gray-200">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                    stroke="currentColor" class="w-5 h-5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5" />
                </svg>
            </button>
            <button id="next-btn"
                class="absolute right-4 top-1/2 transform -translate-y-1/2 bg-white/80 hover:bg-white text-gray-800 w-10 h-10 rounded-full flex items-center justify-center shadow-md transition-all opacity-0 group-hover:opacity-100 z-20 border border-gray-200">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                    stroke="currentColor" class="w-5 h-5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" />
                </svg>
            </button>

            <!-- Pagination Dots -->
            <div id="pagination"
                class="absolute bottom-4 left-1/2 transform -translate-x-1/2 flex items-center gap-2 z-20">
                <!-- Dots injected via JS -->
            </div>
        </div>
    </section>

    <!-- Product Category Grid -->
    <section class="w-full px-4 lg:px-6 mb-8">
        <div class="w-full flex items-start gap-3 md:gap-4 lg:justify-between overflow-x-auto pb-4 scrollbar-hide">
            <!-- Jackets -->
            <div class="flex-shrink-0 w-[130px] cursor-pointer group flex flex-col">
                <div class="w-full h-[130px] rounded-t-md overflow-hidden bg-white">
                    <img src="https://images.unsplash.com/photo-1551028719-00167b16eac5?w=500&auto=format&fit=crop&q=60"
                        alt="Jackets" class="w-full h-full object-cover">
                </div>
                <div class="w-full bg-[#525252] py-1.5 rounded-b-md flex items-center justify-center">
                    <span class="text-white text-[12px] font-bold tracking-wide">Jackets</span>
                </div>
            </div>

            <!-- Pants -->
            <div class="flex-shrink-0 w-[130px] cursor-pointer group flex flex-col">
                <div class="w-full h-[130px] rounded-t-md overflow-hidden bg-white">
                    <img src="https://images.unsplash.com/photo-1624378439575-d8705ad7ae80?w=500&auto=format&fit=crop&q=60"
                        alt="Pants" class="w-full h-full object-cover">
                </div>
                <div class="w-full bg-[#525252] py-1.5 rounded-b-md flex items-center justify-center">
                    <span class="text-white text-[12px] font-bold tracking-wide">Pants</span>
                </div>
            </div>

            <!-- Shorts -->
            <div class="flex-shrink-0 w-[130px] cursor-pointer group flex flex-col">
                <div class="w-full h-[130px] rounded-t-md overflow-hidden bg-white">
                    <img src="https://images.unsplash.com/photo-1591195853828-11db59a44f6b?w=500&auto=format&fit=crop&q=60"
                        alt="Shorts" class="w-full h-full object-cover">
                </div>
                <div class="w-full bg-[#525252] py-1.5 rounded-b-md flex items-center justify-center">
                    <span class="text-white text-[12px] font-bold tracking-wide">Shorts</span>
                </div>
            </div>

            <!-- Football -->
            <div class="flex-shrink-0 w-[130px] cursor-pointer group flex flex-col">
                <div class="w-full h-[130px] rounded-t-md overflow-hidden bg-white">
                    <img src="https://images.unsplash.com/photo-1614632537423-1e6c2e7e0aab?w=500&auto=format&fit=crop&q=60"
                        alt="Football" class="w-full h-full object-cover">
                </div>
                <div class="w-full bg-[#525252] py-1.5 rounded-b-md flex items-center justify-center">
                    <span class="text-white text-[12px] font-bold tracking-wide">Football</span>
                </div>
            </div>

            <!-- Tents -->
            <div class="flex-shrink-0 w-[130px] cursor-pointer group flex flex-col">
                <div class="w-full h-[130px] rounded-t-md overflow-hidden bg-white">
                    <img src="https://images.unsplash.com/photo-1478131143081-80f7f84ca84d?w=500&auto=format&fit=crop&q=60"
                        alt="Tents" class="w-full h-full object-cover">
                </div>
                <div class="w-full bg-[#525252] py-1.5 rounded-b-md flex items-center justify-center">
                    <span class="text-white text-[12px] font-bold tracking-wide">Tents</span>
                </div>
            </div>

            <!-- Towels -->
            <div class="flex-shrink-0 w-[130px] cursor-pointer group flex flex-col">
                <div class="w-full h-[130px] rounded-t-md overflow-hidden bg-white">
                    <img src="https://images.unsplash.com/photo-1582735689369-4fe89db7114c?w=500&auto=format&fit=crop&q=60"
                        alt="Towels" class="w-full h-full object-cover">
                </div>
                <div class="w-full bg-[#525252] py-1.5 rounded-b-md flex items-center justify-center">
                    <span class="text-white text-[12px] font-bold tracking-wide">Towels</span>
                </div>
            </div>

            <!-- Running Shoes -->
            <div class="flex-shrink-0 w-[130px] cursor-pointer group flex flex-col">
                <div class="w-full h-[130px] rounded-t-md overflow-hidden bg-white">
                    <img src="https://images.unsplash.com/photo-1542291026-7eec264c27ff?w=500&auto=format&fit=crop&q=60"
                        alt="Running Shoes" class="w-full h-full object-cover">
                </div>
                <div class="w-full bg-[#525252] py-1.5 rounded-b-md flex items-center justify-center">
                    <span class="text-white text-[12px] font-bold tracking-wide">Running Shoes</span>
                </div>
            </div>

            <!-- T-shirts -->
            <div class="flex-shrink-0 w-[130px] cursor-pointer group flex flex-col">
                <div class="w-full h-[130px] rounded-t-md overflow-hidden bg-white">
                    <img src="https://images.unsplash.com/photo-1521572163474-6864f9cf17ab?w=500&auto=format&fit=crop&q=60"
                        alt="T-shirts" class="w-full h-full object-cover">
                </div>
                <div class="w-full bg-[#525252] py-1.5 rounded-b-md flex items-center justify-center">
                    <span class="text-white text-[12px] font-bold tracking-wide">T-shirts</span>
                </div>
            </div>
        </div>
    </section>


    <!-- Slider Script -->
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            // Banner Auto-Slider (No Arrows)
            const bannerTrack = document.getElementById('banner-track');
            const bannerPagination = document.getElementById('banner-pagination');
            
            if (bannerTrack && bannerPagination) {
                const bannerSlides = bannerTrack.children;
                const bannerSlideCount = bannerSlides.length;
                let bannerCurrentIndex = 0;
                let bannerAutoSlideInterval;

                // Initialize Banner Pagination Dots
                for (let i = 0; i < bannerSlideCount; i++) {
                    const dot = document.createElement('button');
                    dot.className = `w-2 h-2 rounded-full transition-all duration-300 ${i === 0 ? 'bg-white w-6' : 'bg-white/50 hover:bg-white/80'}`;
                    dot.addEventListener('click', () => bannerGoToSlide(i));
                    bannerPagination.appendChild(dot);
                }

                const bannerDots = bannerPagination.children;

                function bannerUpdateDots() {
                    for (let i = 0; i < bannerSlideCount; i++) {
                        const dot = bannerDots[i];
                        if (i === bannerCurrentIndex) {
                            dot.classList.remove('bg-white/50', 'hover:bg-white/80', 'w-2');
                            dot.classList.add('bg-white', 'w-6');
                        } else {
                            dot.classList.add('bg-white/50', 'hover:bg-white/80', 'w-2');
                            dot.classList.remove('bg-white', 'w-6');
                        }
                    }
                }

                function bannerGoToSlide(index) {
                    if (index < 0) {
                        bannerCurrentIndex = bannerSlideCount - 1;
                    } else if (index >= bannerSlideCount) {
                        bannerCurrentIndex = 0;
                    } else {
                        bannerCurrentIndex = index;
                    }

                    bannerTrack.style.transform = `translateX(-${bannerCurrentIndex * 100}%)`;
                    bannerUpdateDots();
                    bannerResetAutoSlide();
                }

                function bannerNextSlide() {
                    bannerGoToSlide(bannerCurrentIndex + 1);
                }

                function bannerResetAutoSlide() {
                    clearInterval(bannerAutoSlideInterval);
                    bannerAutoSlideInterval = setInterval(bannerNextSlide, 4000); // Auto-slide every 4 seconds
                }

                // Start auto-sliding
                bannerResetAutoSlide();
            }

            // Hero Slider (With Arrows)
            const track = document.getElementById('slider-track');
            const prevBtn = document.getElementById('prev-btn');
            const nextBtn = document.getElementById('next-btn');
            const pagination = document.getElementById('pagination');

            const slides = track.children;
            const slideCount = slides.length;
            let currentIndex = 0;
            let autoSlideInterval;

            // Initialize Pagination Dots
            for (let i = 0; i < slideCount; i++) {
                const dot = document.createElement('button');
                dot.className = `w-2 h-2 rounded-full transition-all duration-300 ${i === 0 ? 'bg-[#0082C3] w-6' : 'bg-gray-400 hover:bg-gray-600'}`;
                dot.addEventListener('click', () => goToSlide(i));
                pagination.appendChild(dot);
            }

            const dots = pagination.children;

            function updateDots() {
                for (let i = 0; i < slideCount; i++) {
                    const dot = dots[i];
                    if (i === currentIndex) {
                        dot.classList.remove('bg-gray-400', 'hover:bg-gray-600');
                        dot.classList.add('bg-[#0082C3]', 'w-6');
                        dot.classList.remove('w-2');
                    } else {
                        dot.classList.add('bg-gray-400', 'hover:bg-gray-600', 'w-2');
                        dot.classList.remove('bg-[#0082C3]', 'w-6');
                    }
                }
            }

            function goToSlide(index) {
                if (index < 0) {
                    currentIndex = slideCount - 1;
                } else if (index >= slideCount) {
                    currentIndex = 0;
                } else {
                    currentIndex = index;
                }

                track.style.transform = `translateX(-${currentIndex * 100}%)`;
                updateDots();
                resetAutoSlide();
            }

            function nextSlide() {
                goToSlide(currentIndex + 1);
            }

            function prevSlide() {
                goToSlide(currentIndex - 1);
            }

            // Auto Slide Logic
            function startAutoSlide() {
                autoSlideInterval = setInterval(nextSlide, 5000); // 5 seconds
            }

            function resetAutoSlide() {
                clearInterval(autoSlideInterval);
                startAutoSlide();
            }

            // Event Listeners
            prevBtn.addEventListener('click', prevSlide);
            nextBtn.addEventListener('click', nextSlide);

            // Drag/Swipe Support (Basic)
            let startX, moveX, isDragging = false;

            track.addEventListener('touchstart', (e) => {
                startX = e.touches[0].clientX;
                isDragging = true;
                resetAutoSlide();
            });

            track.addEventListener('touchmove', (e) => {
                if (!isDragging) return;
                moveX = e.touches[0].clientX;
            });

            track.addEventListener('touchend', () => {
                if (!isDragging || !moveX) return;
                const diff = startX - moveX;
                if (diff > 50) nextSlide();
                else if (diff < -50) prevSlide();

                isDragging = false;
                moveX = null;
            });

            // Start
            startAutoSlide();
        });
    </script>

    <!-- Most Popular Products Slider -->
    <section class="w-full px-4 lg:px-6 mb-8">
        <div class="w-full">
            <!-- Section Header -->
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-lg font-bold text-gray-900">Most Popular Products</h2>
                <div class="flex items-center gap-2">
                    <button id="products-prev"
                        class="w-8 h-8 rounded-full border border-gray-300 flex items-center justify-center hover:bg-gray-100 transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                            stroke="currentColor" class="w-4 h-4">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5" />
                        </svg>
                    </button>
                    <button id="products-next"
                        class="w-8 h-8 rounded-full border border-gray-300 flex items-center justify-center hover:bg-gray-100 transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                            stroke="currentColor" class="w-4 h-4">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" />
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Products Slider Container -->
            <div class="relative w-full">
                <div id="products-track" class="flex gap-4 overflow-x-auto snap-x snap-mandatory scrollbar-hide pb-4 transition-transform duration-500 ease-in-out">
                    <!-- Show only 5 products initially -->
                    <!-- Product 1 -->
                    <div
                        class="flex-shrink-0 w-[160px] md:w-[280px] snap-start bg-white rounded overflow-hidden group hover:shadow-md transition-shadow">
                        <div class="relative bg-gray-100">
                            <span
                                class="absolute top-2 left-2 bg-yellow-400 text-gray-900 text-[10px] font-bold px-2 py-0.5 rounded">Price
                                drop</span>
                            <button
                                class="absolute top-2 right-2 w-7 h-7 bg-white rounded-full flex items-center justify-center shadow-sm hover:bg-gray-50">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12z" />
                                </svg>
                            </button>
                            <img src="https://images.unsplash.com/photo-1552374196-1ab2a1c593e8?w=400&auto=format&fit=crop&q=80"
                                alt="Man in T-shirt" class="w-full h-[160px] md:h-[250px] object-cover">
                        </div>
                        <div class="p-2">
                            <p class="text-[10px] font-bold text-gray-900 mb-0.5">DOMYOS</p>
                            <p class="text-[11px] text-gray-700 mb-1.5 truncate leading-tight">Men Gym Sports
                                T-Shirt Polyester - Mottled...</p>
                            <div class="flex items-center gap-1 mb-1.5">
                                <span class="text-yellow-500 text-sm">★</span>
                                <span class="text-[11px] font-semibold">4.75</span>
                                <span class="text-[10px] text-gray-500">| 11k</span>
                            </div>
                            <div class="flex items-center gap-1.5 mb-2">
                                <span class="text-sm font-bold text-gray-900">₹299</span>
                                <span class="text-[10px] text-gray-400 line-through">₹499</span>
                                <span class="text-[10px] font-bold text-red-600">40% Off</span>
                            </div>
                            <button
                                class="w-full border border-gray-900 text-gray-900 text-[11px] font-bold py-1.5 rounded hover:bg-gray-900 hover:text-white transition-colors">
                                ADD TO CART
                            </button>
                        </div>
                    </div>

                    <!-- Product 2 -->
                    <div
                        class="flex-shrink-0 w-[160px] md:w-[280px] snap-start bg-white rounded overflow-hidden group hover:shadow-md transition-shadow">
                        <div class="relative bg-gray-100">
                            <button
                                class="absolute top-2 right-2 w-7 h-7 bg-white rounded-full flex items-center justify-center shadow-sm hover:bg-gray-50">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12z" />
                                </svg>
                            </button>
                            <img src="https://images.unsplash.com/photo-1595950653106-6c9ebd614d3a?w=400&auto=format&fit=crop&q=80"
                                alt="Running Shoes" class="w-full h-[160px] md:h-[250px] object-cover">
                        </div>
                        <div class="p-2">
                            <p class="text-[10px] font-bold text-gray-900 mb-0.5">KIPRUN</p>
                            <p class="text-[11px] text-gray-700 mb-1.5 truncate leading-tight">Men Running Shoes
                                Superior Grip Cushione...</p>
                            <div class="flex items-center gap-1 mb-1.5">
                                <span class="text-yellow-500 text-sm">★</span>
                                <span class="text-[11px] font-semibold">4.6</span>
                                <span class="text-[10px] text-gray-500">| 6.9k</span>
                            </div>
                            <div class="flex items-center gap-1.5 mb-2">
                                <span class="text-sm font-bold text-gray-900">₹2,499</span>
                                <span class="text-[10px] text-gray-400 line-through">₹3,299</span>
                            </div>
                            <button
                                class="w-full border border-gray-900 text-gray-900 text-[11px] font-bold py-1.5 rounded hover:bg-gray-900 hover:text-white transition-colors">
                                ADD TO CART
                            </button>
                        </div>
                    </div>

                    <!-- Product 3 -->
                    <div
                        class="flex-shrink-0 w-[160px] md:w-[280px] snap-start bg-white rounded overflow-hidden group hover:shadow-md transition-shadow">
                        <div class="relative bg-gray-100">
                            <button
                                class="absolute top-2 right-2 w-7 h-7 bg-white rounded-full flex items-center justify-center shadow-sm hover:bg-gray-50">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12z" />
                                </svg>
                            </button>
                            <img src="https://images.unsplash.com/photo-1622260614153-03223fb72052?w=400&auto=format&fit=crop&q=80"
                                alt="Backpack" class="w-full h-[160px] md:h-[250px] object-cover">
                        </div>
                        <div class="p-2">
                            <p class="text-[10px] font-bold text-gray-900 mb-0.5">QUECHUA</p>
                            <p class="text-[11px] text-gray-700 mb-1.5 truncate leading-tight">Hiking Backpack 10 L
                                - NH Arpenaz 50</p>
                            <div class="flex items-center gap-1 mb-1.5">
                                <span class="text-yellow-500 text-sm">★</span>
                                <span class="text-[11px] font-semibold">4.76</span>
                                <span class="text-[10px] text-gray-500">| 23k</span>
                            </div>
                            <div class="flex items-center gap-1.5 mb-2">
                                <span class="text-sm font-bold text-gray-900">₹199</span>
                                <span class="text-[10px] text-gray-400 line-through">₹499</span>
                            </div>
                            <button
                                class="w-full border border-gray-900 text-gray-900 text-[11px] font-bold py-1.5 rounded hover:bg-gray-900 hover:text-white transition-colors">
                                ADD TO CART
                            </button>
                        </div>
                    </div>

                    <!-- Product 4 -->
                    <div
                        class="flex-shrink-0 w-[160px] md:w-[280px] snap-start bg-white rounded overflow-hidden group hover:shadow-md transition-shadow">
                        <div class="relative bg-gray-100">
                            <button
                                class="absolute top-2 right-2 w-7 h-7 bg-white rounded-full flex items-center justify-center shadow-sm hover:bg-gray-50">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12z" />
                                </svg>
                            </button>
                            <img src="https://images.unsplash.com/photo-1434389677669-e08b4cac3105?w=400&auto=format&fit=crop&q=80"
                                alt="Man in Jacket" class="w-full h-[160px] md:h-[250px] object-cover">
                        </div>
                        <div class="p-2">
                            <p class="text-[10px] font-bold text-gray-900 mb-0.5">SOLOGNAC</p>
                            <p class="text-[11px] text-gray-700 mb-1.5 truncate leading-tight">FLEECE 100 GREEN</p>
                            <div class="flex items-center gap-1 mb-1.5">
                                <span class="text-yellow-500 text-sm">★</span>
                                <span class="text-[11px] font-semibold">4.78</span>
                                <span class="text-[10px] text-gray-500">| 1.2k</span>
                            </div>
                            <div class="flex items-center gap-1.5 mb-2">
                                <span class="text-sm font-bold text-gray-900">₹799</span>
                                <span class="text-[10px] text-gray-400 line-through">₹1,049</span>
                            </div>
                            <button
                                class="w-full border border-gray-900 text-gray-900 text-[11px] font-bold py-1.5 rounded hover:bg-gray-900 hover:text-white transition-colors">
                                ADD TO CART
                            </button>
                        </div>
                    </div>

                    <!-- Product 5 -->
                    <div
                        class="flex-shrink-0 w-[160px] md:w-[280px] snap-start bg-white rounded overflow-hidden group hover:shadow-md transition-shadow">
                        <div class="relative bg-gray-100">
                            <span
                                class="absolute top-2 left-2 bg-yellow-400 text-gray-900 text-[10px] font-bold px-2 py-0.5 rounded">Price
                                drop</span>
                            <button
                                class="absolute top-2 right-2 w-7 h-7 bg-white rounded-full flex items-center justify-center shadow-sm hover:bg-gray-50">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12z" />
                                </svg>
                            </button>
                            <img src="https://images.unsplash.com/photo-1591047139829-d91aecb6caea?w=400&auto=format&fit=crop&q=80"
                                alt="Man in Vest" class="w-full h-[160px] md:h-[250px] object-cover">
                        </div>
                        <div class="p-2">
                            <p class="text-[10px] font-bold text-gray-900 mb-0.5">QUECHUA</p>
                            <p class="text-[11px] text-gray-700 mb-1.5 truncate leading-tight">Men's Mountain
                                Walking Fleece Gilet MH10...</p>
                            <div class="flex items-center gap-1 mb-1.5">
                                <span class="text-yellow-500 text-sm">★</span>
                                <span class="text-[11px] font-semibold">4.70</span>
                                <span class="text-[10px] text-gray-500">| 4.8k</span>
                            </div>
                            <div class="flex items-center gap-1.5 mb-2">
                                <span class="text-sm font-bold text-gray-900">₹699</span>
                                <span class="text-[10px] text-gray-400 line-through">₹1,299</span>
                                <span class="text-[10px] font-bold text-red-600">46% Off</span>
                            </div>
                            <button
                                class="w-full border border-gray-900 text-gray-900 text-[11px] font-bold py-1.5 rounded hover:bg-gray-900 hover:text-white transition-colors">
                                ADD TO CART
                            </button>
                        </div>
                    </div>

                    <!-- Product 6 -->
                    <div
                        class="flex-shrink-0 w-[160px] md:w-[280px] snap-start bg-white rounded overflow-hidden group hover:shadow-md transition-shadow">
                        <div class="relative bg-gray-100">
                            <button
                                class="absolute top-2 right-2 w-7 h-7 bg-white rounded-full flex items-center justify-center shadow-sm hover:bg-gray-50">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12z" />
                                </svg>
                            </button>
                            <img src="https://images.unsplash.com/photo-1601925260368-ae2f83cf8b7f?w=400&auto=format&fit=crop&q=80"
                                alt="Yoga Mat" class="w-full h-[160px] md:h-[250px] object-cover">
                        </div>
                        <div class="p-2">
                            <p class="text-[10px] font-bold text-gray-900 mb-0.5">DOMYOS</p>
                            <p class="text-[11px] text-gray-700 mb-1.5 truncate leading-tight">Yoga Mat 8mm - Purple
                            </p>
                            <div class="flex items-center gap-1 mb-1.5">
                                <span class="text-yellow-500 text-sm">★</span>
                                <span class="text-[11px] font-semibold">4.65</span>
                                <span class="text-[10px] text-gray-500">| 3.2k</span>
                            </div>
                            <div class="flex items-center gap-1.5 mb-2">
                                <span class="text-sm font-bold text-gray-900">₹599</span>
                                <span class="text-[10px] text-gray-400 line-through">₹899</span>
                            </div>
                            <button
                                class="w-full border border-gray-900 text-gray-900 text-[11px] font-bold py-1.5 rounded hover:bg-gray-900 hover:text-white transition-colors">
                                ADD TO CART
                            </button>
                        </div>
                    </div>

                    <!-- Product 7 -->
                    <div
                        class="flex-shrink-0 w-[160px] md:w-[280px] snap-start bg-white rounded overflow-hidden group hover:shadow-md transition-shadow">
                        <div class="relative bg-gray-100">
                            <button
                                class="absolute top-2 right-2 w-7 h-7 bg-white rounded-full flex items-center justify-center shadow-sm hover:bg-gray-50">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12z" />
                                </svg>
                            </button>
                            <img src="https://images.unsplash.com/photo-1606107557195-0e29a4b5b4aa?w=400&auto=format&fit=crop&q=80"
                                alt="Sneakers" class="w-full h-[160px] md:h-[250px] object-cover">
                        </div>
                        <div class="p-2">
                            <p class="text-[10px] font-bold text-gray-900 mb-0.5">KALENJI</p>
                            <p class="text-[11px] text-gray-700 mb-1.5 truncate leading-tight">Men's Running Shoes
                                Run Active Grip</p>
                            <div class="flex items-center gap-1 mb-1.5">
                                <span class="text-yellow-500 text-sm">★</span>
                                <span class="text-[11px] font-semibold">4.72</span>
                                <span class="text-[10px] text-gray-500">| 8.5k</span>
                            </div>
                            <div class="flex items-center gap-1.5 mb-2">
                                <span class="text-sm font-bold text-gray-900">₹1,499</span>
                                <span class="text-[10px] text-gray-400 line-through">₹1,999</span>
                            </div>
                            <button
                                class="w-full border border-gray-900 text-gray-900 text-[11px] font-bold py-1.5 rounded hover:bg-gray-900 hover:text-white transition-colors">
                                ADD TO CART
                            </button>
                        </div>
                    </div>

                    <!-- Product 8 -->
                    <div
                        class="flex-shrink-0 w-[160px] md:w-[280px] snap-start bg-white rounded overflow-hidden group hover:shadow-md transition-shadow">
                        <div class="relative bg-gray-100">
                            <span
                                class="absolute top-2 left-2 bg-yellow-400 text-gray-900 text-[10px] font-bold px-2 py-0.5 rounded">Price
                                drop</span>
                            <button
                                class="absolute top-2 right-2 w-7 h-7 bg-white rounded-full flex items-center justify-center shadow-sm hover:bg-gray-50">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12z" />
                                </svg>
                            </button>
                            <img src="https://images.unsplash.com/photo-1544441892-794166f1e3be?w=400&auto=format&fit=crop&q=80"
                                alt="Cycling Shorts" class="w-full h-[160px] md:h-[250px] object-cover">
                        </div>
                        <div class="p-2">
                            <p class="text-[10px] font-bold text-gray-900 mb-0.5">BTWIN</p>
                            <p class="text-[11px] text-gray-700 mb-1.5 truncate leading-tight">Men's Cycling Shorts
                                - Black</p>
                            <div class="flex items-center gap-1 mb-1.5">
                                <span class="text-yellow-500 text-sm">★</span>
                                <span class="text-[11px] font-semibold">4.68</span>
                                <span class="text-[10px] text-gray-500">| 2.1k</span>
                            </div>
                            <div class="flex items-center gap-1.5 mb-2">
                                <span class="text-sm font-bold text-gray-900">₹399</span>
                                <span class="text-[10px] text-gray-400 line-through">₹799</span>
                                <span class="text-[10px] font-bold text-red-600">50% Off</span>
                            </div>
                            <button
                                class="w-full border border-gray-900 text-gray-900 text-[11px] font-bold py-1.5 rounded hover:bg-gray-900 hover:text-white transition-colors">
                                ADD TO CART
                            </button>
                        </div>
                    </div>

                    <!-- Product 9 -->
                    <div
                        class="flex-shrink-0 w-[160px] md:w-[280px] snap-start bg-white rounded overflow-hidden group hover:shadow-md transition-shadow">
                        <div class="relative bg-gray-100">
                            <button
                                class="absolute top-2 right-2 w-7 h-7 bg-white rounded-full flex items-center justify-center shadow-sm hover:bg-gray-50">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12z" />
                                </svg>
                            </button>
                            <img src="https://images.unsplash.com/photo-1571902943202-507ec2618e8f?w=400&auto=format&fit=crop&q=80"
                                alt="Gym Bag" class="w-full h-[160px] md:h-[250px] object-cover">
                        </div>
                        <div class="p-2">
                            <p class="text-[10px] font-bold text-gray-900 mb-0.5">KIPSTA</p>
                            <p class="text-[11px] text-gray-700 mb-1.5 truncate leading-tight">Sports Bag Kipocket
                                40L - Black</p>
                            <div class="flex items-center gap-1 mb-1.5">
                                <span class="text-yellow-500 text-sm">★</span>
                                <span class="text-[11px] font-semibold">4.81</span>
                                <span class="text-[10px] text-gray-500">| 15k</span>
                            </div>
                            <div class="flex items-center gap-1.5 mb-2">
                                <span class="text-sm font-bold text-gray-900">₹899</span>
                                <span class="text-[10px] text-gray-400 line-through">₹1,299</span>
                            </div>
                            <button
                                class="w-full border border-gray-900 text-gray-900 text-[11px] font-bold py-1.5 rounded hover:bg-gray-900 hover:text-white transition-colors">
                                ADD TO CART
                            </button>
                        </div>
                    </div>

                    <!-- Product 10 -->
                    <div
                        class="flex-shrink-0 w-[160px] md:w-[280px] snap-start bg-white rounded overflow-hidden group hover:shadow-md transition-shadow">
                        <div class="relative bg-gray-100">
                            <button
                                class="absolute top-2 right-2 w-7 h-7 bg-white rounded-full flex items-center justify-center shadow-sm hover:bg-gray-50">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12z" />
                                </svg>
                            </button>
                            <img src="https://images.unsplash.com/photo-1576678927484-cc907957088c?w=400&auto=format&fit=crop&q=80"
                                alt="Water Bottle" class="w-full h-[160px] md:h-[250px] object-cover">
                        </div>
                        <div class="p-2">
                            <p class="text-[10px] font-bold text-gray-900 mb-0.5">DOMYOS</p>
                            <p class="text-[11px] text-gray-700 mb-1.5 truncate leading-tight">Water Bottle 500ml -
                                Blue</p>
                            <div class="flex items-center gap-1 mb-1.5">
                                <span class="text-yellow-500 text-sm">★</span>
                                <span class="text-[11px] font-semibold">4.55</span>
                                <span class="text-[10px] text-gray-500">| 9.8k</span>
                            </div>
                            <div class="flex items-center gap-1.5 mb-2">
                                <span class="text-sm font-bold text-gray-900">₹149</span>
                                <span class="text-[10px] text-gray-400 line-through">₹249</span>
                            </div>
                            <button
                                class="w-full border border-gray-900 text-gray-900 text-[11px] font-bold py-1.5 rounded hover:bg-gray-900 hover:text-white transition-colors">
                                ADD TO CART
                            </button>
                        </div>
                    </div>

                    <!-- Product 11 -->
                    <div
                        class="flex-shrink-0 w-[160px] md:w-[280px] snap-start bg-white rounded overflow-hidden group hover:shadow-md transition-shadow">
                        <div class="relative bg-gray-100">
                            <span
                                class="absolute top-2 left-2 bg-yellow-400 text-gray-900 text-[10px] font-bold px-2 py-0.5 rounded">New</span>
                            <button
                                class="absolute top-2 right-2 w-7 h-7 bg-white rounded-full flex items-center justify-center shadow-sm hover:bg-gray-50">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12z" />
                                </svg>
                            </button>
                            <img src="https://images.unsplash.com/photo-1588099768523-f4e6a569933c?w=400&auto=format&fit=crop&q=80"
                                alt="Headphones" class="w-full h-[160px] md:h-[250px] object-cover">
                        </div>
                        <div class="p-2">
                            <p class="text-[10px] font-bold text-gray-900 mb-0.5">GEONAUTE</p>
                            <p class="text-[11px] text-gray-700 mb-1.5 truncate leading-tight">Wireless Sports Headphones</p>
                            <div class="flex items-center gap-1 mb-1.5">
                                <span class="text-yellow-500 text-sm">★</span>
                                <span class="text-[11px] font-semibold">4.5</span>
                                <span class="text-[10px] text-gray-500">| 623</span>
                            </div>
                            <div class="flex items-center gap-1.5 mb-2">
                                <span class="text-sm font-bold text-gray-900">₹1,299</span>
                                <span class="text-[10px] text-gray-400 line-through">₹1,999</span>
                                <span class="text-[10px] font-bold text-red-600">35% Off</span>
                            </div>
                            <button
                                class="w-full border border-gray-900 text-gray-900 text-[11px] font-bold py-1.5 rounded hover:bg-gray-900 hover:text-white transition-colors">
                                ADD TO CART
                            </button>
                        </div>
                    </div>

                    <!-- Product 12 -->
                    <div
                        class="flex-shrink-0 w-[160px] md:w-[280px] snap-start bg-white rounded overflow-hidden group hover:shadow-md transition-shadow">
                        <div class="relative bg-gray-100">
                            <button
                                class="absolute top-2 right-2 w-7 h-7 bg-white rounded-full flex items-center justify-center shadow-sm hover:bg-gray-50">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12z" />
                                </svg>
                            </button>
                            <img src="https://images.unsplash.com/photo-1542838132-92c53300491e?w=400&auto=format&fit=crop&q=80"
                                alt="Tennis Racket" class="w-full h-[160px] md:h-[250px] object-cover">
                        </div>
                        <div class="p-2">
                            <p class="text-[10px] font-bold text-gray-900 mb-0.5">ARTENGO</p>
                            <p class="text-[11px] text-gray-700 mb-1.5 truncate leading-tight">Tennis Racket TR960</p>
                            <div class="flex items-center gap-1 mb-1.5">
                                <span class="text-yellow-500 text-sm">★</span>
                                <span class="text-[11px] font-semibold">4.8</span>
                                <span class="text-[10px] text-gray-500">| 2.5k</span>
                            </div>
                            <div class="flex items-center gap-1.5 mb-2">
                                <span class="text-sm font-bold text-gray-900">₹2,999</span>
                                <span class="text-[10px] text-gray-400 line-through">₹4,999</span>
                                <span class="text-[10px] font-bold text-red-600">40% Off</span>
                            </div>
                            <button
                                class="w-full border border-gray-900 text-gray-900 text-[11px] font-bold py-1.5 rounded hover:bg-gray-900 hover:text-white transition-colors">
                                ADD TO CART
                            </button>
                        </div>
                    </div>

                    <!-- Product 13 -->
                    <div
                        class="flex-shrink-0 w-[160px] md:w-[280px] snap-start bg-white rounded overflow-hidden group hover:shadow-md transition-shadow">
                        <div class="relative bg-gray-100">
                            <button
                                class="absolute top-2 right-2 w-7 h-7 bg-white rounded-full flex items-center justify-center shadow-sm hover:bg-gray-50">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12z" />
                                </svg>
                            </button>
                            <img src="https://images.unsplash.com/photo-1515965385161-f87170b0f8c2?w=400&auto=format&fit=crop&q=80"
                                alt="Swimming Goggles" class="w-full h-[160px] md:h-[250px] object-cover">
                        </div>
                        <div class="p-2">
                            <p class="text-[10px] font-bold text-gray-900 mb-0.5">NABAIJI</p>
                            <p class="text-[11px] text-gray-700 mb-1.5 truncate leading-tight">Swimming Goggles X-Base</p>
                            <div class="flex items-center gap-1 mb-1.5">
                                <span class="text-yellow-500 text-sm">★</span>
                                <span class="text-[11px] font-semibold">4.6</span>
                                <span class="text-[10px] text-gray-500">| 1.8k</span>
                            </div>
                            <div class="flex items-center gap-1.5 mb-2">
                                <span class="text-sm font-bold text-gray-900">₹299</span>
                                <span class="text-[10px] text-gray-400 line-through">₹499</span>
                                <span class="text-[10px] font-bold text-red-600">40% Off</span>
                            </div>
                            <button
                                class="w-full border border-gray-900 text-gray-900 text-[11px] font-bold py-1.5 rounded hover:bg-gray-900 hover:text-white transition-colors">
                                ADD TO CART
                            </button>
                        </div>
                    </div>

                    <!-- Product 14 -->
                    <div
                        class="flex-shrink-0 w-[160px] md:w-[280px] snap-start bg-white rounded overflow-hidden group hover:shadow-md transition-shadow">
                        <div class="relative bg-gray-100">
                            <button
                                class="absolute top-2 right-2 w-7 h-7 bg-white rounded-full flex items-center justify-center shadow-sm hover:bg-gray-50">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12z" />
                                </svg>
                            </button>
                            <img src="https://images.unsplash.com/photo-1571019613454-1cb2f99b2d8b?w=400&auto=format&fit=crop&q=80"
                                alt="Basketball" class="w-full h-[160px] md:h-[250px] object-cover">
                        </div>
                        <div class="p-2">
                            <p class="text-[10px] font-bold text-gray-900 mb-0.5">KIPSTA</p>
                            <p class="text-[11px] text-gray-700 mb-1.5 truncate leading-tight">Basketball Official Size 7</p>
                            <div class="flex items-center gap-1 mb-1.5">
                                <span class="text-yellow-500 text-sm">★</span>
                                <span class="text-[11px] font-semibold">4.7</span>
                                <span class="text-[10px] text-gray-500">| 3.2k</span>
                            </div>
                            <div class="flex items-center gap-1.5 mb-2">
                                <span class="text-sm font-bold text-gray-900">₹899</span>
                                <span class="text-[10px] text-gray-400 line-through">₹1,299</span>
                                <span class="text-[10px] font-bold text-red-600">31% Off</span>
                            </div>
                            <button
                                class="w-full border border-gray-900 text-gray-900 text-[11px] font-bold py-1.5 rounded hover:bg-gray-900 hover:text-white transition-colors">
                                ADD TO CART
                            </button>
                        </div>
                    </div>

                    <!-- Product 15 -->
                    <div
                        class="flex-shrink-0 w-[160px] md:w-[280px] snap-start bg-white rounded overflow-hidden group hover:shadow-md transition-shadow">
                        <div class="relative bg-gray-100">
                            <span
                                class="absolute top-2 left-2 bg-yellow-400 text-gray-900 text-[10px] font-bold px-2 py-0.5 rounded">Limited</span>
                            <button
                                class="absolute top-2 right-2 w-7 h-7 bg-white rounded-full flex items-center justify-center shadow-sm hover:bg-gray-50">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12z" />
                                </svg>
                            </button>
                            <img src="https://images.unsplash.com/photo-1460925895917-afdab827c52f?w=400&auto=format&fit=crop&q=80"
                                alt="Fitness Watch" class="w-full h-[160px] md:h-[250px] object-cover">
                        </div>
                        <div class="p-2">
                            <p class="text-[10px] font-bold text-gray-900 mb-0.5">GEONAUTE</p>
                            <p class="text-[11px] text-gray-700 mb-1.5 truncate leading-tight">Fitness Watch Heart Rate Monitor</p>
                            <div class="flex items-center gap-1 mb-1.5">
                                <span class="text-yellow-500 text-sm">★</span>
                                <span class="text-[11px] font-semibold">4.4</span>
                                <span class="text-[10px] text-gray-500">| 892</span>
                            </div>
                            <div class="flex items-center gap-1.5 mb-2">
                                <span class="text-sm font-bold text-gray-900">₹3,999</span>
                                <span class="text-[10px] text-gray-400 line-through">₹5,999</span>
                                <span class="text-[10px] font-bold text-red-600">33% Off</span>
                            </div>
                            <button
                                class="w-full border border-gray-900 text-gray-900 text-[11px] font-bold py-1.5 rounded hover:bg-gray-900 hover:text-white transition-colors">
                                ADD TO CART
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Auto-Sliding Banner Section (3-in-1 Jacket Style) -->
    <section class="w-full px-4 lg:px-6 mb-8">
        <div class="w-full">
            <div class="relative w-full h-[450px] md:h-[280px] rounded-2xl overflow-hidden">
                <!-- Slider Track -->
                <div id="banner-track" class="flex h-full transition-transform duration-700 ease-in-out">
                    <!-- Banner 1: 3-in-1 Jacket -->
                    <div class="min-w-full h-full bg-gradient-to-r from-[#3a4a5c] via-[#4a5a6c] to-[#5a6a7c] relative flex flex-col md:flex-row items-center justify-center md:justify-start px-6 md:px-12 py-8 md:py-0">
                        <!-- Left Text Content -->
                        <div class="w-full md:w-1/2 z-10 text-center md:text-left mb-6 md:mb-0 mt-8 md:mt-0">
                            <h2 class="text-5xl font-bold text-white mb-2 leading-tight">3-in-1<br>Jacket</h2>
                            <p class="text-lg text-green-300 font-medium mb-6">Built for Every Plan</p>
                            <button class="bg-white text-gray-900 px-8 py-3 rounded-full font-bold text-sm hover:bg-gray-100 transition-all">
                                Shop Now
                            </button>
                        </div>
                        
                        <!-- Right Product Images -->
                        <div class="w-full md:w-1/2 h-48 md:h-full relative flex items-center justify-center md:justify-end gap-2 md:gap-4 pr-0 md:pr-8">
                            <!-- Small Image 1 -->
                            <div class="bg-white rounded-xl p-3 shadow-lg transform hover:scale-105 transition-transform">
                                <img src="https://images.unsplash.com/photo-1551028719-00167b16eac5?w=400&auto=format&fit=crop&q=80" 
                                     alt="Inner Layer" class="w-24 h-32 object-cover rounded-lg">
                                <p class="text-xs text-center mt-2 text-gray-700 font-medium">Warm inner layer for mild to<br>harsh winters, up to -10°C</p>
                            </div>
                            
                            <!-- Main Large Image -->
                            <div class="relative z-10">
                                <img src="https://images.unsplash.com/photo-1544923408-75c5cef46f14?w=600&auto=format&fit=crop&q=80" 
                                     alt="3-in-1 Jacket" class="h-64 w-auto object-contain drop-shadow-2xl">
                            </div>
                            
                            <!-- Small Image 2 -->
                            <div class="bg-white rounded-xl p-3 shadow-lg transform hover:scale-105 transition-transform">
                                <img src="https://images.unsplash.com/photo-1591047139829-d91aecb6caea?w=400&auto=format&fit=crop&q=80" 
                                     alt="Outer Layer" class="w-24 h-32 object-cover rounded-lg">
                                <p class="text-xs text-center mt-2 text-gray-700 font-medium">Add-on outer layer that<br>blocks wind & snow</p>
                            </div>
                            
                            <!-- Waterproof Badge -->
                            <div class="absolute bottom-2 md:bottom-8 right-2 md:right-12 bg-white/90 backdrop-blur-sm rounded-lg px-2 md:px-3 py-1.5 md:py-2 shadow-lg">
                                <div class="flex items-center gap-1 md:gap-2">
                                    <svg class="w-5 h-5 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M10 2a6 6 0 00-6 6c0 4.314 6 10 6 10s6-5.686 6-10a6 6 0 00-6-6z"/>
                                    </svg>
                                    <div>
                                        <p class="text-xs font-bold text-gray-900">Waterproof</p>
                                        <p class="text-[10px] text-gray-600">Ideal for extremely wear</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Mountain Background -->
                        <div class="absolute inset-0 opacity-20">
                            <img src="https://images.unsplash.com/photo-1506905925346-21bda4d32df4?w=1200&auto=format&fit=crop&q=60" 
                                 alt="Mountains" class="w-full h-full object-cover">
                        </div>
                    </div>

                    <!-- Banner 2: Winter Collection -->
                    <div class="min-w-full h-full bg-gradient-to-r from-[#2c3e50] via-[#34495e] to-[#3c5060] relative flex flex-col md:flex-row items-center justify-center md:justify-start px-6 md:px-12 py-8 md:py-0">
                        <div class="w-full md:w-1/2 z-10 text-center md:text-left mb-6 md:mb-0 mt-8 md:mt-0">
                            <h2 class="text-5xl font-bold text-white mb-2 leading-tight">Winter<br>Collection</h2>
                            <p class="text-lg text-blue-300 font-medium mb-6">Stay Warm & Stylish</p>
                            <button class="bg-white text-gray-900 px-8 py-3 rounded-full font-bold text-sm hover:bg-gray-100 transition-all">
                                Explore Now
                            </button>
                        </div>
                        
                        <div class="w-full md:w-1/2 h-48 md:h-full relative flex items-center justify-center">
                            <img src="https://images.unsplash.com/photo-1539533018447-63fcce2678e3?w=600&auto=format&fit=crop&q=80" 
                                 alt="Winter Jacket" class="h-64 w-auto object-contain drop-shadow-2xl">
                        </div>
                        
                        <div class="absolute inset-0 opacity-15">
                            <img src="https://images.unsplash.com/photo-1491002052546-bf38f186af56?w=1200&auto=format&fit=crop&q=60" 
                                 alt="Snow" class="w-full h-full object-cover">
                        </div>
                    </div>

                    <!-- Banner 3: Outdoor Gear -->
                    <div class="min-w-full h-full bg-gradient-to-r from-[#1a5f7a] via-[#2a6f8a] to-[#3a7f9a] relative flex flex-col md:flex-row items-center justify-center md:justify-start px-6 md:px-12 py-8 md:py-0">
                        <div class="w-full md:w-1/2 z-10 text-center md:text-left mb-6 md:mb-0 mt-8 md:mt-0">
                            <h2 class="text-5xl font-bold text-white mb-2 leading-tight">Outdoor<br>Adventure</h2>
                            <p class="text-lg text-yellow-300 font-medium mb-6">Gear Up for the Trail</p>
                            <button class="bg-white text-gray-900 px-8 py-3 rounded-full font-bold text-sm hover:bg-gray-100 transition-all">
                                Shop Collection
                            </button>
                        </div>
                        
                        <div class="w-full md:w-1/2 h-48 md:h-full relative flex items-center justify-center">
                            <img src="https://images.unsplash.com/photo-1578608712688-36b5be8823dc?w=600&auto=format&fit=crop&q=80" 
                                 alt="Outdoor Jacket" class="h-64 w-auto object-contain drop-shadow-2xl">
                        </div>
                        
                        <div class="absolute inset-0 opacity-15">
                            <img src="https://images.unsplash.com/photo-1464822759023-fed622ff2c3b?w=1200&auto=format&fit=crop&q=60" 
                                 alt="Mountains" class="w-full h-full object-cover">
                        </div>
                    </div>
                </div>

                <!-- Pagination Dots -->
                <div id="banner-pagination" class="absolute bottom-4 left-1/2 transform -translate-x-1/2 flex items-center gap-2 z-20">
                    <!-- Dots will be added by JavaScript -->
                </div>
            </div>
        </div>
    </section>

    <!-- Equipping CHAMPIONS Section -->
    <section class="w-full px-4 lg:px-6 mb-8">
        <div class="w-full">
            <!-- Section Header -->
            <h2 class="text-xl font-black text-gray-900 mb-6 uppercase tracking-tight">EQUIPPING CHAMPIONS</h2>
            
            <!-- Cards Grid -->
            <div class="flex md:grid overflow-x-auto md:overflow-visible snap-x snap-mandatory scrollbar-hide pb-4 md:pb-0 md:grid-cols-2 lg:grid-cols-4 gap-4">
                <!-- Card 1: Kick With Confidence -->
                <div class="flex-shrink-0 w-[75vw] md:w-auto snap-start bg-gray-200 rounded-2xl overflow-hidden group cursor-pointer hover:shadow-lg transition-shadow">
                    <!-- Product Image -->
                    <div class="bg-gray-200 p-8 flex items-center justify-center h-[200px]">
                        <img src="https://images.unsplash.com/photo-1579952363873-27f3bade9f55?w=400&auto=format&fit=crop&q=80" 
                             alt="Football Shoes" 
                             class="w-full h-full object-contain">
                    </div>
                    
                    <!-- Content -->
                    <div class="bg-gray-200 p-4">
                        <h3 class="text-xl font-bold text-gray-900 mb-1">Kick With Confidence</h3>
                        <div class="flex items-center justify-between">
                            <div class="flex items-baseline gap-1">
                                <span class="text-3xl font-bold text-gray-900">₹999</span>
                                <span class="text-xs text-gray-600">Onwards</span>
                            </div>
                            <button class="w-8 h-8 rounded-full bg-white flex items-center justify-center hover:bg-gray-100 transition-colors">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-5 h-5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Card 2: Made For The Wild -->
                <div class="flex-shrink-0 w-[75vw] md:w-auto snap-start bg-gray-200 rounded-2xl overflow-hidden group cursor-pointer hover:shadow-lg transition-shadow">
                    <!-- Product Image -->
                    <div class="bg-gray-200 p-8 flex items-center justify-center h-[200px]">
                        <img src="https://images.unsplash.com/photo-1485965120184-e220f721d03e?w=400&auto=format&fit=crop&q=80" 
                             alt="Bicycle" 
                             class="w-full h-full object-contain">
                    </div>
                    
                    <!-- Content -->
                    <div class="bg-gray-200 p-4">
                        <h3 class="text-xl font-bold text-gray-900 mb-1">Made For The Wild</h3>
                        <div class="flex items-center justify-between">
                            <div class="flex items-baseline gap-1">
                                <span class="text-3xl font-bold text-gray-900">₹6,499</span>
                                <span class="text-xs text-gray-600">Onwards</span>
                            </div>
                            <button class="w-8 h-8 rounded-full bg-white flex items-center justify-center hover:bg-gray-100 transition-colors">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-5 h-5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Card 3: Glide With Confidence -->
                <div class="flex-shrink-0 w-[75vw] md:w-auto snap-start bg-gray-200 rounded-2xl overflow-hidden group cursor-pointer hover:shadow-lg transition-shadow">
                    <!-- Product Image -->
                    <div class="bg-gray-200 p-8 flex items-center justify-center h-[200px]">
                        <img src="https://images.unsplash.com/photo-1564466809058-bf4114d55352?w=400&auto=format&fit=crop&q=80" 
                             alt="Roller Skates" 
                             class="w-full h-full object-contain">
                    </div>
                    
                    <!-- Content -->
                    <div class="bg-gray-200 p-4">
                        <h3 class="text-xl font-bold text-gray-900 mb-1">Glide With Confidence</h3>
                        <div class="flex items-center justify-between">
                            <div class="flex items-baseline gap-1">
                                <span class="text-3xl font-bold text-gray-900">₹1,099</span>
                                <span class="text-xs text-gray-600">Onwards</span>
                            </div>
                            <button class="w-8 h-8 rounded-full bg-white flex items-center justify-center hover:bg-gray-100 transition-colors">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-5 h-5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Card 4: Practice Makes Points -->
                <div class="flex-shrink-0 w-[75vw] md:w-auto snap-start bg-gray-200 rounded-2xl overflow-hidden group cursor-pointer hover:shadow-lg transition-shadow">
                    <!-- Product Image -->
                    <div class="bg-gray-200 p-8 flex items-center justify-center h-[200px]">
                        <img src="https://images.unsplash.com/photo-1546519638-68e109498ffc?w=400&auto=format&fit=crop&q=80" 
                             alt="Basketball Hoop" 
                             class="w-full h-full object-contain">
                    </div>
                    
                    <!-- Content -->
                    <div class="bg-gray-200 p-4">
                        <h3 class="text-xl font-bold text-gray-900 mb-1">Practice Makes Points</h3>
                        <div class="flex items-center justify-between">
                            <div class="flex items-baseline gap-1">
                                <span class="text-3xl font-bold text-gray-900">₹699</span>
                                <span class="text-xs text-gray-600">Onwards</span>
                            </div>
                            <button class="w-8 h-8 rounded-full bg-white flex items-center justify-center hover:bg-gray-100 transition-colors">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-5 h-5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Sports Categories Section -->
    <section class="w-full px-4 lg:px-6 mb-8">
        <div class="w-full">
            <!-- Sports Cards Grid -->
            <div class="flex items-center gap-3 overflow-x-auto snap-x snap-mandatory scrollbar-hide pb-4 w-full">
                <!-- Running -->
                <div class="flex-shrink-0 w-[100px] md:w-[120px] snap-start cursor-pointer group">
                    <div class="relative w-[100px] h-[100px] md:w-[120px] md:h-[120px] rounded-2xl overflow-hidden bg-gray-200">
                        <img src="https://images.unsplash.com/photo-1552674605-db6ffd4facb5?w=400&auto=format&fit=crop&q=80" 
                             alt="Running" 
                             class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-300">
                    </div>
                </div>

                <!-- Swimming -->
                <div class="flex-shrink-0 w-[100px] md:w-[120px] snap-start cursor-pointer group">
                    <div class="relative w-[100px] h-[100px] md:w-[120px] md:h-[120px] rounded-2xl overflow-hidden bg-gray-200">
                        <img src="https://images.unsplash.com/photo-1519315901367-f34ff9154487?w=400&auto=format&fit=crop&q=80" 
                             alt="Swimming" 
                             class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-300">
                    </div>
                </div>

                <!-- Football -->
                <div class="flex-shrink-0 w-[100px] md:w-[120px] snap-start cursor-pointer group">
                    <div class="relative w-[100px] h-[100px] md:w-[120px] md:h-[120px] rounded-2xl overflow-hidden bg-gray-200">
                        <img src="https://images.unsplash.com/photo-1579952363873-27f3bade9f55?w=400&auto=format&fit=crop&q=80" 
                             alt="Football" 
                             class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-300">
                    </div>
                </div>

                <!-- Badminton -->
                <div class="flex-shrink-0 w-[100px] md:w-[120px] snap-start cursor-pointer group">
                    <div class="relative w-[100px] h-[100px] md:w-[120px] md:h-[120px] rounded-2xl overflow-hidden bg-gray-200">
                        <img src="https://images.unsplash.com/photo-1626224583764-f87db24ac4ea?w=400&auto=format&fit=crop&q=80" 
                             alt="Badminton" 
                             class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-300">
                    </div>
                </div>

                <!-- Soccer -->
                <div class="flex-shrink-0 w-[100px] md:w-[120px] snap-start cursor-pointer group">
                    <div class="relative w-[100px] h-[100px] md:w-[120px] md:h-[120px] rounded-2xl overflow-hidden bg-gray-200">
                        <img src="https://images.unsplash.com/photo-1574629810360-7efbbe195018?w=400&auto=format&fit=crop&q=80" 
                             alt="Soccer" 
                             class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-300">
                    </div>
                </div>

                <!-- Tennis -->
                <div class="flex-shrink-0 w-[100px] md:w-[120px] snap-start cursor-pointer group">
                    <div class="relative w-[100px] h-[100px] md:w-[120px] md:h-[120px] rounded-2xl overflow-hidden bg-gray-200">
                        <img src="https://images.unsplash.com/photo-1622279457486-62dcc4a431d6?w=400&auto=format&fit=crop&q=80" 
                             alt="Tennis" 
                             class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-300">
                    </div>
                </div>

                <!-- Safari -->
                <div class="flex-shrink-0 w-[100px] md:w-[120px] snap-start cursor-pointer group">
                    <div class="relative w-[100px] h-[100px] md:w-[120px] md:h-[120px] rounded-2xl overflow-hidden bg-gray-200">
                        <img src="https://images.unsplash.com/photo-1523805009345-7448845a9e53?w=400&auto=format&fit=crop&q=80" 
                             alt="Safari" 
                             class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-300">
                    </div>
                </div>

                <!-- All Sports -->
                <div class="flex-shrink-0 w-[100px] md:w-[120px] snap-start cursor-pointer group">
                    <div class="relative w-[100px] h-[100px] md:w-[120px] md:h-[120px] rounded-2xl overflow-hidden bg-gray-200 flex items-center justify-center">
                        <img src="https://images.unsplash.com/photo-1461896836934-ffe607ba8211?w=400&auto=format&fit=crop&q=80" 
                             alt="All Sports" 
                             class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-300">
                    </div>
                </div>
            </div>

            <!-- Section Title -->
            <h2 class="text-xl font-black text-gray-900 mt-6 uppercase tracking-tight">MOST LOVED DEALS! TOO GOOD TO MISS.</h2>
            
            <!-- Deals Cards Grid -->
            <div class="flex md:grid overflow-x-auto md:overflow-visible snap-x snap-mandatory scrollbar-hide pb-4 md:pb-0 md:grid-cols-2 lg:grid-cols-4 gap-4 mt-6">
                <!-- Card 1: Trekking & Hiking Backpacks -->
                <div class="flex-shrink-0 w-[75vw] md:w-auto snap-start relative rounded-2xl overflow-hidden group cursor-pointer bg-gray-100 h-[180px] md:h-[280px]">
                    <!-- Product Image -->
                    <div class="absolute inset-0 flex items-center justify-center p-6">
                        <img src="https://images.unsplash.com/photo-1622260614153-03223fb72052?w=400&auto=format&fit=crop&q=80" 
                             alt="Backpack" 
                             class="w-full h-full object-contain group-hover:scale-110 transition-transform duration-300">
                    </div>
                    
                    <!-- Wave Background -->
                    <div class="absolute bottom-0 left-0 right-0 h-[120px]">
                        <svg viewBox="0 0 1200 120" preserveAspectRatio="none" class="absolute bottom-0 w-full h-full">
                            <path d="M0,0 Q300,40 600,20 T1200,0 L1200,120 L0,120 Z" fill="#4DD4D4" opacity="0.3"/>
                        </svg>
                        <svg viewBox="0 0 1200 120" preserveAspectRatio="none" class="absolute bottom-0 w-full h-full">
                            <path d="M0,20 Q300,60 600,40 T1200,20 L1200,120 L0,120 Z" fill="#4DD4D4" opacity="0.5"/>
                        </svg>
                        <svg viewBox="0 0 1200 120" preserveAspectRatio="none" class="absolute bottom-0 w-full h-full">
                            <path d="M0,40 Q300,80 600,60 T1200,40 L1200,120 L0,120 Z" fill="#4DD4D4"/>
                        </svg>
                    </div>
                    
                    <!-- Content -->
                    <div class="absolute bottom-0 left-0 right-0 p-4 z-10">
                        <h3 class="text-base font-bold text-gray-900 mb-2">Trekking & Hiking Backpacks</h3>
                        <div class="flex items-center justify-between">
                            <div class="flex items-baseline gap-1">
                                <span class="text-2xl font-bold text-gray-900">₹499</span>
                                <span class="text-xs text-gray-700">Onwards</span>
                            </div>
                            <button class="w-8 h-8 rounded-full bg-white flex items-center justify-center hover:bg-gray-100 transition-colors">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-5 h-5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Card 2: Swimming Accessories -->
                <div class="flex-shrink-0 w-[75vw] md:w-auto snap-start relative rounded-2xl overflow-hidden group cursor-pointer bg-gray-100 h-[180px] md:h-[280px]">
                    <div class="absolute inset-0 flex items-center justify-center p-6">
                        <img src="https://images.unsplash.com/photo-1519315901367-f34ff9154487?w=400&auto=format&fit=crop&q=80" 
                             alt="Swimming Accessories" 
                             class="w-full h-full object-contain group-hover:scale-110 transition-transform duration-300">
                    </div>
                    
                    <div class="absolute bottom-0 left-0 right-0 h-[120px]">
                        <svg viewBox="0 0 1200 120" preserveAspectRatio="none" class="absolute bottom-0 w-full h-full">
                            <path d="M0,0 Q300,40 600,20 T1200,0 L1200,120 L0,120 Z" fill="#4DD4D4" opacity="0.3"/>
                        </svg>
                        <svg viewBox="0 0 1200 120" preserveAspectRatio="none" class="absolute bottom-0 w-full h-full">
                            <path d="M0,20 Q300,60 600,40 T1200,20 L1200,120 L0,120 Z" fill="#4DD4D4" opacity="0.5"/>
                        </svg>
                        <svg viewBox="0 0 1200 120" preserveAspectRatio="none" class="absolute bottom-0 w-full h-full">
                            <path d="M0,40 Q300,80 600,60 T1200,40 L1200,120 L0,120 Z" fill="#4DD4D4"/>
                        </svg>
                    </div>
                    
                    <div class="absolute bottom-0 left-0 right-0 p-4 z-10">
                        <h3 class="text-base font-bold text-gray-900 mb-2">Swimming Accessories</h3>
                        <div class="flex items-center justify-between">
                            <div class="flex items-baseline gap-1">
                                <span class="text-2xl font-bold text-gray-900">₹199</span>
                                <span class="text-xs text-gray-700">Onwards</span>
                            </div>
                            <button class="w-8 h-8 rounded-full bg-white flex items-center justify-center hover:bg-gray-100 transition-colors">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-5 h-5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Card 3: Sunglasses -->
                <div class="flex-shrink-0 w-[75vw] md:w-auto snap-start relative rounded-2xl overflow-hidden group cursor-pointer bg-gray-100 h-[180px] md:h-[280px]">
                    <div class="absolute inset-0 flex items-center justify-center p-6">
                        <img src="https://images.unsplash.com/photo-1572635196237-14b3f281503f?w=400&auto=format&fit=crop&q=80" 
                             alt="Sunglasses" 
                             class="w-full h-full object-contain group-hover:scale-110 transition-transform duration-300">
                    </div>
                    
                    <div class="absolute bottom-0 left-0 right-0 h-[120px]">
                        <svg viewBox="0 0 1200 120" preserveAspectRatio="none" class="absolute bottom-0 w-full h-full">
                            <path d="M0,0 Q300,40 600,20 T1200,0 L1200,120 L0,120 Z" fill="#4DD4D4" opacity="0.3"/>
                        </svg>
                        <svg viewBox="0 0 1200 120" preserveAspectRatio="none" class="absolute bottom-0 w-full h-full">
                            <path d="M0,20 Q300,60 600,40 T1200,20 L1200,120 L0,120 Z" fill="#4DD4D4" opacity="0.5"/>
                        </svg>
                        <svg viewBox="0 0 1200 120" preserveAspectRatio="none" class="absolute bottom-0 w-full h-full">
                            <path d="M0,40 Q300,80 600,60 T1200,40 L1200,120 L0,120 Z" fill="#4DD4D4"/>
                        </svg>
                    </div>
                    
                    <div class="absolute bottom-0 left-0 right-0 p-4 z-10">
                        <h3 class="text-base font-bold text-gray-900 mb-2">Sunglasses</h3>
                        <div class="flex items-center justify-between">
                            <div class="flex items-baseline gap-1">
                                <span class="text-2xl font-bold text-gray-900">₹399</span>
                                <span class="text-xs text-gray-700">Onwards</span>
                            </div>
                            <button class="w-8 h-8 rounded-full bg-white flex items-center justify-center hover:bg-gray-100 transition-colors">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-5 h-5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Card 4: Basketball Shorts -->
                <div class="flex-shrink-0 w-[75vw] md:w-auto snap-start relative rounded-2xl overflow-hidden group cursor-pointer bg-gray-100 h-[180px] md:h-[280px]">
                    <div class="absolute inset-0 flex items-center justify-center p-6">
                        <img src="https://images.unsplash.com/photo-1591195853828-11db59a44f6b?w=400&auto=format&fit=crop&q=80" 
                             alt="Basketball Shorts" 
                             class="w-full h-full object-contain group-hover:scale-110 transition-transform duration-300">
                    </div>
                    
                    <div class="absolute bottom-0 left-0 right-0 h-[120px]">
                        <svg viewBox="0 0 1200 120" preserveAspectRatio="none" class="absolute bottom-0 w-full h-full">
                            <path d="M0,0 Q300,40 600,20 T1200,0 L1200,120 L0,120 Z" fill="#4DD4D4" opacity="0.3"/>
                        </svg>
                        <svg viewBox="0 0 1200 120" preserveAspectRatio="none" class="absolute bottom-0 w-full h-full">
                            <path d="M0,20 Q300,60 600,40 T1200,20 L1200,120 L0,120 Z" fill="#4DD4D4" opacity="0.5"/>
                        </svg>
                        <svg viewBox="0 0 1200 120" preserveAspectRatio="none" class="absolute bottom-0 w-full h-full">
                            <path d="M0,40 Q300,80 600,60 T1200,40 L1200,120 L0,120 Z" fill="#4DD4D4"/>
                        </svg>
                    </div>
                    
                    <div class="absolute bottom-0 left-0 right-0 p-4 z-10">
                        <h3 class="text-base font-bold text-gray-900 mb-2">Basketball Shorts</h3>
                        <div class="flex items-center justify-between">
                            <div class="flex items-baseline gap-1">
                                <span class="text-2xl font-bold text-gray-900">₹499</span>
                                <span class="text-xs text-gray-700">Onwards</span>
                            </div>
                            <button class="w-8 h-8 rounded-full bg-white flex items-center justify-center hover:bg-gray-100 transition-colors">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-5 h-5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Level Up Your Workout Section -->
    <section class="w-full px-4 lg:px-6 mb-8">
        <div class="w-full">
            <!-- Section Title -->
            <h2 class="text-xl font-black text-gray-900 mb-6 uppercase tracking-tight">LEVEL UP YOUR WORKOUT !</h2>
            
            <!-- Workout Cards Grid -->
            <div class="flex md:grid overflow-x-auto md:overflow-visible snap-x snap-mandatory scrollbar-hide pb-4 md:pb-0 md:grid-cols-2 lg:grid-cols-4 gap-4">
                <!-- Card 1: Gym Accessories -->
                <div class="flex-shrink-0 w-[75vw] md:w-auto snap-start relative rounded-2xl overflow-hidden group cursor-pointer h-[180px] md:h-[280px]">
                    <img src="https://images.unsplash.com/photo-1517836357463-d25dfeac3438?w=600&auto=format&fit=crop&q=80" 
                         alt="Gym Accessories" 
                         class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-300">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/40 to-transparent"></div>
                    
                    <div class="absolute bottom-0 left-0 right-0 p-6 text-white">
                        <h3 class="text-2xl font-bold mb-2 italic">Gym Accessories</h3>
                        <div class="flex items-baseline gap-1">
                            <span class="text-3xl font-bold">₹299</span>
                            <span class="text-sm">Onwards</span>
                        </div>
                    </div>
                </div>

                <!-- Card 2: Massagers & Rollers -->
                <div class="flex-shrink-0 w-[75vw] md:w-auto snap-start relative rounded-2xl overflow-hidden group cursor-pointer h-[180px] md:h-[280px]">
                    <img src="https://images.unsplash.com/photo-1544367567-0f2fcb009e0b?w=600&auto=format&fit=crop&q=80" 
                         alt="Massagers & Rollers" 
                         class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-300">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/40 to-transparent"></div>
                    
                    <div class="absolute bottom-0 left-0 right-0 p-6 text-white">
                        <h3 class="text-2xl font-bold mb-2 italic">Massagers & Rollers</h3>
                        <div class="flex items-baseline gap-1">
                            <span class="text-3xl font-bold">₹199</span>
                            <span class="text-sm">Onwards</span>
                        </div>
                    </div>
                </div>

                <!-- Card 3: Sport Nutrition -->
                <div class="flex-shrink-0 w-[75vw] md:w-auto snap-start relative rounded-2xl overflow-hidden group cursor-pointer h-[180px] md:h-[280px]">
                    <img src="https://images.unsplash.com/photo-1593095948071-474c5cc2989d?w=600&auto=format&fit=crop&q=80" 
                         alt="Sport Nutrition" 
                         class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-300">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/40 to-transparent"></div>
                    
                    <div class="absolute bottom-0 left-0 right-0 p-6 text-white">
                        <h3 class="text-2xl font-bold mb-2 italic">Sport Nutrition</h3>
                        <div class="flex items-baseline gap-1">
                            <span class="text-3xl font-bold">₹59</span>
                            <span class="text-sm">Onwards</span>
                        </div>
                    </div>
                </div>

                <!-- Card 4: Supports -->
                <div class="flex-shrink-0 w-[75vw] md:w-auto snap-start relative rounded-2xl overflow-hidden group cursor-pointer h-[180px] md:h-[280px]">
                    <img src="https://images.unsplash.com/photo-1530822847156-5df684ec5ee1?w=600&auto=format&fit=crop&q=80" 
                         alt="Supports" 
                         class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-300">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/40 to-transparent"></div>
                    
                    <div class="absolute bottom-0 left-0 right-0 p-6 text-white">
                        <h3 class="text-2xl font-bold mb-2 italic">Supports</h3>
                        <div class="flex items-baseline gap-1">
                            <span class="text-3xl font-bold">₹199</span>
                            <span class="text-sm">Onwards</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- From The Coast To The Club Section -->
    <section class="w-full px-4 lg:px-6 mb-8">
        <div class="w-full">
            <!-- Section Title -->
            <h2 class="text-xl font-black text-gray-900 mb-6 uppercase tracking-tight">FROM THE COAST TO THE CLUB !</h2>
            
            <!-- Coast Cards Grid -->
            <div class="flex md:grid overflow-x-auto md:overflow-visible snap-x snap-mandatory scrollbar-hide pb-4 md:pb-0 md:grid-cols-2 lg:grid-cols-4 gap-4">
                <!-- Card 1: Surfboards & UPF 50+ Suits -->
                <div class="flex-shrink-0 w-[75vw] md:w-auto snap-start relative rounded-2xl overflow-hidden group cursor-pointer h-[180px] md:h-[280px]">
                    <img src="https://images.unsplash.com/photo-1502680390469-be75c86b636f?w=600&auto=format&fit=crop&q=80" 
                         alt="Surfboards & UPF 50+ Suits" 
                         class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-300">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/40 to-transparent"></div>
                    
                    <div class="absolute bottom-0 left-0 right-0 p-6 text-white">
                        <h3 class="text-2xl font-bold mb-2">Surfboards &<br>UPF 50+ Suits</h3>
                        <div class="flex items-baseline gap-1">
                            <span class="text-3xl font-bold">₹299</span>
                            <span class="text-sm">Onwards</span>
                        </div>
                    </div>
                </div>

                <!-- Card 2: Table Tennis: Instant Rollnet -->
                <div class="flex-shrink-0 w-[75vw] md:w-auto snap-start relative rounded-2xl overflow-hidden group cursor-pointer h-[180px] md:h-[280px]">
                    <img src="https://images.unsplash.com/photo-1609710228159-0fa9bd7c0827?w=600&auto=format&fit=crop&q=80" 
                         alt="Table Tennis: Instant Rollnet" 
                         class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-300">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/40 to-transparent"></div>
                    
                    <div class="absolute bottom-0 left-0 right-0 p-6 text-white">
                        <h3 class="text-2xl font-bold mb-2">Table Tennis:<br>Instant Rollnet</h3>
                        <div class="flex items-baseline gap-1">
                            <span class="text-3xl font-bold">₹799</span>
                            <span class="text-sm">Onwards</span>
                        </div>
                    </div>
                </div>

                <!-- Card 3: Pickle & Padel -->
                <div class="flex-shrink-0 w-[75vw] md:w-auto snap-start relative rounded-2xl overflow-hidden group cursor-pointer h-[180px] md:h-[280px]">
                    <img src="https://images.unsplash.com/photo-1554068865-24cecd4e34b8?w=600&auto=format&fit=crop&q=80" 
                         alt="Pickle & Padel" 
                         class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-300">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/40 to-transparent"></div>
                    
                    <div class="absolute bottom-0 left-0 right-0 p-6 text-white">
                        <h3 class="text-2xl font-bold mb-2">Pickle &<br>Padel</h3>
                        <div class="flex items-baseline gap-1">
                            <span class="text-3xl font-bold">₹299</span>
                            <span class="text-sm">Onwards</span>
                        </div>
                    </div>
                </div>

                <!-- Card 4: Hit The Bullseye -->
                <div class="flex-shrink-0 w-[75vw] md:w-auto snap-start relative rounded-2xl overflow-hidden group cursor-pointer h-[180px] md:h-[280px]">
                    <img src="https://images.unsplash.com/photo-1587280501635-68a0e82cd5ff?w=600&auto=format&fit=crop&q=80" 
                         alt="Hit The Bullseye" 
                         class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-300">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/40 to-transparent"></div>
                    
                    <div class="absolute bottom-0 left-0 right-0 p-6 text-white">
                        <h3 class="text-2xl font-bold mb-2">Hit The<br>Bullseye</h3>
                        <div class="flex items-baseline gap-1">
                            <span class="text-3xl font-bold">₹199</span>
                            <span class="text-sm">Onwards</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Products Slider Script -->
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            // Most Popular Products Slider
            const productsTrack = document.getElementById('products-track');
            const prevBtn = document.getElementById('products-prev');
            const nextBtn = document.getElementById('products-next');
            const allProducts = productsTrack.children;

            let currentIndex = 0;
            const productsVisible = 5;
            const slideAmount = 1; // Slide 1 product at a time
            const productWidth = 280 + 16; // 280px + 16px gap
            const maxIndex = allProducts.length - productsVisible;

            function updateSlider() {
                // Calculate translateX for smooth sliding
                const translateX = currentIndex * productWidth;
                productsTrack.style.transform = `translateX(-${translateX}px)`;

                // Update button states
                const canGoPrev = currentIndex > 0;
                const canGoNext = currentIndex < maxIndex;

                prevBtn.disabled = !canGoPrev;
                nextBtn.disabled = !canGoNext;

                prevBtn.style.opacity = canGoPrev ? '1' : '0.5';
                prevBtn.style.cursor = canGoPrev ? 'pointer' : 'not-allowed';
                
                nextBtn.style.opacity = canGoNext ? '1' : '0.5';
                nextBtn.style.cursor = canGoNext ? 'pointer' : 'not-allowed';
            }

            function slideLeft() {
                if (currentIndex > 0) {
                    currentIndex = Math.max(0, currentIndex - slideAmount);
                    updateSlider();
                }
            }

            function slideRight() {
                if (currentIndex < maxIndex) {
                    currentIndex = Math.min(maxIndex, currentIndex + slideAmount);
                    updateSlider();
                }
            }

            prevBtn.addEventListener('click', slideLeft);
            nextBtn.addEventListener('click', slideRight);

            // Touch/Swipe support for mobile
            let touchStartX = 0;
            let touchEndX = 0;

            productsTrack.addEventListener('touchstart', (e) => {
                touchStartX = e.changedTouches[0].screenX;
            });

            productsTrack.addEventListener('touchend', (e) => {
                touchEndX = e.changedTouches[0].screenX;
                const diff = touchStartX - touchEndX;
                
                if (Math.abs(diff) > 50) { // 50px minimum swipe
                    if (diff > 0) {
                        slideRight(); // Swipe left = next
                    } else {
                        slideLeft(); // Swipe right = prev
                    }
                }
            });

            // Set initial state
            updateSlider();
        });
    </script>
@endsection
