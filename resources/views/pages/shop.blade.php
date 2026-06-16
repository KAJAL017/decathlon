@extends('layouts.app')

@section('title', 'Shop Sports Products - Decathlon')

@section('content')
<div class="w-full bg-gray-50 py-6 px-4 lg:px-6">
    <div class="w-full space-y-6">
        
        <!-- Welcome Offer Banner Section -->
        <div class="w-full">
            <p class="text-gray-950 font-black text-xs md:text-sm mb-2 uppercase tracking-wider">Product of the Month</p>
            
            <!-- Perforated Coupon Banner -->
            <div class="w-full bg-[#1b4cbf] rounded-2xl flex flex-col lg:flex-row text-white overflow-hidden shadow-sm border border-blue-800">
                <!-- Left Branding Panel -->
                <div class="p-6 md:p-8 flex flex-col justify-center items-center lg:items-start lg:w-[35%] bg-gradient-to-br from-[#1945b0] to-[#2556d6] text-center lg:text-left z-10 relative">
                    <h3 class="text-xl md:text-2xl font-black leading-tight uppercase tracking-tight">
                        Exclusive<br>Welcome Offers For Your<br><span class="text-yellow-400">First Order</span>
                    </h3>
                    <button class="mt-4 bg-white text-[#1b4cbf] text-xs font-black px-6 py-2.5 rounded-full hover:bg-gray-100 hover:scale-105 transition-transform duration-200 shadow-md">
                        Shop Now
                    </button>
                    <!-- Wave details inside panel background -->
                    <div class="absolute inset-0 opacity-10 pointer-events-none bg-[radial-gradient(circle_at_bottom_left,rgba(255,255,255,0.4),transparent_60%)]"></div>
                </div>
                
                <!-- Right Coupon Tickets Group (Dotted perforated separators) -->
                <div class="flex-grow flex flex-col sm:flex-row bg-[#1b4cbf] lg:w-[65%] border-t lg:border-t-0 lg:border-l border-dashed border-white/30 z-10">
                    
                    <!-- Ticket 1 -->
                    <div class="flex-1 p-6 flex flex-col justify-center items-center text-center relative border-b sm:border-b-0 sm:border-r border-dashed border-white/20">
                        <span class="absolute top-2 right-3 text-[9px] uppercase font-bold text-white/40 tracking-widest">COUPON</span>
                        <p class="text-[10px] font-bold text-white/60 uppercase tracking-wider">UP TO</p>
                        <h4 class="text-3xl font-black text-white leading-none mt-1">₹100</h4>
                        <p class="text-[10px] text-white/70 mt-1.5 leading-snug">On purchase above ₹1,499<br><span class="font-medium text-white/50">USE CODE</span></p>
                        <span class="mt-3 inline-block bg-[#123696] text-white text-[11px] font-black px-5 py-1.5 rounded uppercase tracking-widest border border-white/10 hover:bg-[#0a256e] cursor-pointer transition-colors">NC100</span>
                    </div>
                    
                    <!-- Ticket 2 -->
                    <div class="flex-1 p-6 flex flex-col justify-center items-center text-center relative border-b sm:border-b-0 sm:border-r border-dashed border-white/20">
                        <span class="absolute top-2 right-3 text-[9px] uppercase font-bold text-white/40 tracking-widest">COUPON</span>
                        <p class="text-[10px] font-bold text-white/60 uppercase tracking-wider">UP TO</p>
                        <h4 class="text-3xl font-black text-white leading-none mt-1">₹200</h4>
                        <p class="text-[10px] text-white/70 mt-1.5 leading-snug">On purchase above ₹2,499<br><span class="font-medium text-white/50">USE CODE</span></p>
                        <span class="mt-3 inline-block bg-[#123696] text-white text-[11px] font-black px-5 py-1.5 rounded uppercase tracking-widest border border-white/10 hover:bg-[#0a256e] cursor-pointer transition-colors">NC200</span>
                    </div>
                    
                    <!-- Ticket 3 -->
                    <div class="flex-1 p-6 flex flex-col justify-center items-center text-center relative">
                        <span class="absolute top-2 right-3 text-[9px] uppercase font-bold text-white/40 tracking-widest">COUPON</span>
                        <p class="text-[10px] font-bold text-white/60 uppercase tracking-wider">UP TO</p>
                        <h4 class="text-3xl font-black text-white leading-none mt-1">₹300</h4>
                        <p class="text-[10px] text-white/70 mt-1.5 leading-snug">On purchase above ₹3,499<br><span class="font-medium text-white/50">USE CODE</span></p>
                        <span class="mt-3 inline-block bg-[#123696] text-white text-[11px] font-black px-5 py-1.5 rounded uppercase tracking-widest border border-white/10 hover:bg-[#0a256e] cursor-pointer transition-colors">NC300</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Workspace Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-4 gap-8 items-start">
            
            <!-- Left Filter Sidebar -->
            <aside class="bg-white rounded-2xl border border-gray-200/80 p-5 shadow-sm space-y-6">
                <!-- Sidebar Header -->
                <div class="flex items-center justify-between pb-3 border-b border-gray-100">
                    <h4 class="font-bold text-gray-900 text-sm tracking-wide">Filters</h4>
                    <a href="{{ route('shop') }}" class="text-xs font-bold text-[#0082C3] hover:text-[#006699] flex items-center gap-1 transition-colors">
                        Clear all
                    </a>
                </div>

                <!-- Accordion Filter: Price -->
                <div class="space-y-3 pb-3 border-b border-gray-100">
                    <div class="flex items-center justify-between cursor-pointer filter-trigger" data-target="price-panel">
                        <span class="text-xs font-bold text-gray-900 uppercase tracking-wider">Price Range</span>
                        <span id="price-arrow" class="text-gray-400 text-sm font-semibold">—</span>
                    </div>
                    <div id="price-panel" class="space-y-4 pt-1 transition-all duration-300 overflow-hidden">
                        <form action="{{ route('shop') }}" method="GET" class="space-y-4">
                            @if(request('category')) <input type="hidden" name="category" value="{{ request('category') }}"> @endif
                            @if(request('brand')) <input type="hidden" name="brand" value="{{ request('brand') }}"> @endif
                            @if(request('search')) <input type="hidden" name="search" value="{{ request('search') }}"> @endif
                            
                            <div class="flex items-center justify-between gap-3 text-xs">
                                <div class="flex-1 space-y-1">
                                    <label class="text-[10px] text-gray-500 font-bold uppercase">Minimum</label>
                                    <div class="flex items-center border border-gray-200 rounded-lg px-2.5 py-1.5 bg-gray-50">
                                        <span class="text-gray-400 mr-1">₹</span>
                                        <input type="number" name="min_price" value="{{ request('min_price') }}" placeholder="0" class="w-full bg-transparent font-bold text-gray-800 outline-none text-right">
                                    </div>
                                </div>
                                <div class="flex-1 space-y-1">
                                    <label class="text-[10px] text-gray-500 font-bold uppercase">Maximum</label>
                                    <div class="flex items-center border border-gray-200 rounded-lg px-2.5 py-1.5 bg-gray-50">
                                        <span class="text-gray-400 mr-1">₹</span>
                                        <input type="number" name="max_price" value="{{ request('max_price') }}" placeholder="10000" class="w-full bg-transparent font-bold text-gray-800 outline-none text-right">
                                    </div>
                                </div>
                            </div>
                            <button type="submit" class="w-full bg-[#0082C3] text-white text-[10px] font-bold py-1.5 rounded hover:bg-[#006699] transition-colors uppercase tracking-wider">
                                Apply Price
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Category Filter -->
                <div class="pb-3 border-b border-gray-100">
                    <div class="flex items-center justify-between cursor-pointer group py-0.5 filter-trigger" data-target="category-panel">
                        <span class="text-xs font-bold text-gray-800 uppercase tracking-wider group-hover:text-[#0082C3] transition-colors">Category</span>
                        <span id="category-arrow" class="text-gray-400 text-sm font-semibold group-hover:text-gray-700 transition-colors">+</span>
                    </div>
                    <div id="category-panel" class="max-h-0 overflow-hidden transition-all duration-300 ease-in-out pl-2 text-xs text-gray-600 space-y-2 mt-2">
                        @foreach($categories as $cat)
                        <div class="space-y-1">
                            <a href="{{ route('shop', array_merge(request()->query(), ['category' => $cat->slug])) }}" 
                               class="flex items-center gap-2 cursor-pointer py-1 hover:text-[#0082C3] {{ request('category') == $cat->slug ? 'text-[#0082C3] font-bold' : '' }}">
                                <span>{{ $cat->name }}</span>
                            </a>
                            @if($cat->children->count() > 0)
                                <div class="pl-4 space-y-1">
                                    @foreach($cat->children as $child)
                                    <a href="{{ route('shop', array_merge(request()->query(), ['category' => $child->slug])) }}" 
                                       class="flex items-center gap-2 cursor-pointer py-1 hover:text-[#0082C3] {{ request('category') == $child->slug ? 'text-[#0082C3] font-bold' : '' }}">
                                        <span>— {{ $child->name }}</span>
                                    </a>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                        @endforeach
                    </div>
                </div>

                <!-- Brand Filter -->
                <div class="pb-3 border-b border-gray-100">
                    <div class="flex items-center justify-between cursor-pointer group py-0.5 filter-trigger" data-target="brand-panel">
                        <span class="text-xs font-bold text-gray-800 uppercase tracking-wider group-hover:text-[#0082C3] transition-colors">Brand</span>
                        <span id="brand-arrow" class="text-gray-400 text-sm font-semibold group-hover:text-gray-700 transition-colors">+</span>
                    </div>
                    <div id="brand-panel" class="max-h-0 overflow-hidden transition-all duration-300 ease-in-out pl-2 text-xs text-gray-600 space-y-2 mt-2">
                        @foreach($brands as $brand)
                        <a href="{{ route('shop', array_merge(request()->query(), ['brand' => $brand->slug])) }}" 
                           class="flex items-center gap-2 cursor-pointer py-1 hover:text-[#0082C3] {{ request('brand') == $brand->slug ? 'text-[#0082C3] font-bold' : '' }}">
                            <span>{{ $brand->name }}</span>
                        </a>
                        @endforeach
                    </div>
                </div>

            </aside>
            
            <!-- Right Product Grid Section -->
            <main class="lg:col-span-3 space-y-4">
                
                <!-- Toolbar Area -->
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3">
                    <!-- Left: Applied badges & counter -->
                    <div class="flex flex-wrap items-center gap-2 text-xs">
                        <span class="font-bold text-gray-800">{{ $products->total() }} items found</span>
                        @if(request('search'))
                            <span class="bg-[#0082C3]/10 text-[#0082C3] px-3 py-1 rounded-full font-bold">Search: {{ request('search') }}</span>
                        @endif
                        @if(request('category'))
                            <span class="bg-[#0082C3]/10 text-[#0082C3] px-3 py-1 rounded-full font-bold">Category: {{ request('category') }}</span>
                        @endif
                        @if(request('brand'))
                            <span class="bg-[#0082C3]/10 text-[#0082C3] px-3 py-1 rounded-full font-bold">Brand: {{ request('brand') }}</span>
                        @endif
                    </div>
                    <!-- Right: Sort options -->
                    <div class="flex items-center gap-3 text-xs text-gray-600 self-end relative group">
                        <span class="font-semibold text-gray-700">Sort by:</span>
                        <div class="bg-white border border-gray-200 rounded-lg px-3 py-1.5 cursor-pointer hover:border-gray-300 shadow-sm flex items-center gap-1">
                            <span class="font-bold text-gray-900">
                                @switch(request('sort'))
                                    @case('price_low') Price: Low to High @break
                                    @case('price_high') Price: High to Low @break
                                    @case('popular') Most Popular @break
                                    @case('rating') Best Rated @break
                                    @default Newest Arrivals
                                @endswitch
                            </span>
                            <i data-lucide="chevron-down" class="w-3.5 h-3.5 text-gray-500"></i>
                        </div>
                        <!-- Sort Dropdown -->
                        <div class="absolute top-full right-0 mt-1 w-48 bg-white border border-gray-200 rounded-lg shadow-lg hidden group-hover:block z-50 overflow-hidden">
                            <a href="{{ route('shop', array_merge(request()->query(), ['sort' => 'latest'])) }}" class="block px-4 py-2 hover:bg-gray-50 text-gray-800 {{ request('sort') == 'latest' ? 'bg-gray-100 font-bold' : '' }}">Newest Arrivals</a>
                            <a href="{{ route('shop', array_merge(request()->query(), ['sort' => 'price_low'])) }}" class="block px-4 py-2 hover:bg-gray-50 text-gray-800 {{ request('sort') == 'price_low' ? 'bg-gray-100 font-bold' : '' }}">Price: Low to High</a>
                            <a href="{{ route('shop', array_merge(request()->query(), ['sort' => 'price_high'])) }}" class="block px-4 py-2 hover:bg-gray-50 text-gray-800 {{ request('sort') == 'price_high' ? 'bg-gray-100 font-bold' : '' }}">Price: High to Low</a>
                            <a href="{{ route('shop', array_merge(request()->query(), ['sort' => 'popular'])) }}" class="block px-4 py-2 hover:bg-gray-50 text-gray-800 {{ request('sort') == 'popular' ? 'bg-gray-100 font-bold' : '' }}">Most Popular</a>
                            <a href="{{ route('shop', array_merge(request()->query(), ['sort' => 'rating'])) }}" class="block px-4 py-2 hover:bg-gray-50 text-gray-800 {{ request('sort') == 'rating' ? 'bg-gray-100 font-bold' : '' }}">Best Rated</a>
                        </div>
                    </div>
                </div>

                <!-- Product Grid -->
                <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                    @forelse($products as $product)
                        @include('partials.product-card', ['product' => $product])
                    @empty
                        <div class="col-span-full py-20 flex flex-col items-center justify-center text-center space-y-4">
                            <div class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center">
                                <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-lg font-bold text-gray-900">No products found</h3>
                                <p class="text-gray-500 text-sm">Try adjusting your filters or search terms</p>
                            </div>
                            <a href="{{ route('shop') }}" class="bg-[#0082C3] text-white px-6 py-2 rounded-full font-bold text-sm shadow-md hover:bg-[#006699] transition-all">
                                Clear all filters
                            </a>
                        </div>
                    @endforelse
                </div>

                <!-- Pagination -->
                <div class="pt-8">
                    {{ $products->links() }}
                </div>

            </main>
        </div>
    </div>
</div>

@endsection
