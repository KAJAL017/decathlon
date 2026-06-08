@extends('layouts.app')

@section('title', 'Shop by Category - Decathlon')

@section('content')
<div class="w-full bg-white py-6 px-4 lg:px-6 space-y-10">

    <!-- Section 1: SHOP BY SPORTS -->
    <section class="space-y-4">
        <h2 class="text-xs md:text-sm font-black text-gray-950 uppercase tracking-widest">Shop By Sports</h2>
        
        <!-- Horizontally Scrollable Circles Row -->
        <div class="flex items-center gap-4 overflow-x-auto pb-4 scrollbar-hide snap-x snap-mandatory">
            @foreach($categories as $category)
            <a href="{{ route('shop') }}?category={{ $category->slug }}" class="flex-shrink-0 w-24 md:w-28 flex flex-col items-center snap-start group cursor-pointer">
                <div class="w-20 h-20 md:w-24 md:h-24 rounded-full overflow-hidden relative shadow-md border border-gray-200/50 flex items-center justify-center bg-gray-100">
                    <img src="{{ $category->image_url ?? 'https://ui-avatars.com/api/?name=' . urlencode($category->name) }}" alt="{{ $category->name }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                    <div class="absolute inset-0 bg-black/45 group-hover:bg-black/35 transition-colors flex items-center justify-center">
                        <span class="text-white text-[10px] md:text-xs font-black tracking-widest text-center px-1 uppercase">{{ $category->name }}</span>
                    </div>
                </div>
            </a>
            @endforeach
        </div>
    </section>

    <!-- Section 2: Banner 1 (60+ Sporty Bags) -->
    <section>
        <div class="w-full bg-gradient-to-r from-blue-700 via-indigo-600 to-[#2257d9] rounded-2xl md:rounded-3xl p-6 md:p-10 lg:p-12 text-white flex flex-col lg:flex-row justify-between items-center overflow-hidden relative gap-8 shadow-md">
            
            <!-- Banner Left Content -->
            <div class="flex flex-col space-y-3 z-10 text-center lg:text-left items-center lg:items-start max-w-lg">
                <h1 class="text-4xl md:text-5xl lg:text-6xl font-black text-yellow-400 leading-none tracking-tight">
                    60+ Sporty Bags
                </h1>
                <p class="text-lg md:text-2xl font-bold text-white/90">
                    Find the bag for <span class="italic font-serif text-yellow-300">you</span>!
                </p>
                <div class="pt-4 lg:pt-6">
                    <a href="{{ route('shop') }}?category=bag-backpacks" class="inline-block bg-white text-gray-900 font-black text-xs md:text-sm px-8 py-3 rounded-full hover:bg-gray-100 hover:scale-105 active:scale-95 transition-all shadow-md uppercase tracking-widest">
                        Explore All
                    </a>
                </div>
            </div>

            <!-- Banner Right Images Stack -->
            <div class="relative flex items-end justify-center w-full lg:w-[45%] h-64 lg:h-72 z-10 mt-6 lg:mt-0">
                <!-- Stacked Backpack cutouts (Unsplash) -->
                <div class="absolute bottom-0 left-[5%] md:left-[15%] w-24 md:w-32 hover:scale-105 transition-transform duration-300 rotate-[-12deg] z-0 origin-bottom">
                    <img src="https://images.unsplash.com/photo-1606220588913-b3aacb4d2f46?w=300&auto=format&fit=crop&q=80" alt="Roll mat" class="w-full h-auto drop-shadow-lg rounded-md">
                </div>
                <div class="absolute bottom-0 left-[35%] md:left-[40%] w-32 md:w-44 hover:scale-105 transition-transform duration-300 translate-y-1 z-20 origin-bottom">
                    <img src="https://images.unsplash.com/photo-1622560480605-d83c853bc5c3?w=300&auto=format&fit=crop&q=80" alt="Orange backpack" class="w-full h-auto drop-shadow-xl rounded-md">
                </div>
                <div class="absolute bottom-0 right-[5%] md:right-[15%] w-28 md:w-36 hover:scale-105 transition-transform duration-300 rotate-[8deg] z-10 origin-bottom">
                    <img src="https://images.unsplash.com/photo-1553062407-98eeb64c6a62?w=300&auto=format&fit=crop&q=80" alt="Black backpack" class="w-full h-auto drop-shadow-lg rounded-md">
                </div>
            </div>
            
            <div class="absolute -right-32 -bottom-32 w-80 h-80 bg-white/5 rounded-full blur-3xl pointer-events-none"></div>
            <div class="absolute -left-32 -top-32 w-80 h-80 bg-blue-900/40 rounded-full blur-3xl pointer-events-none"></div>
        </div>
    </section>

    <!-- Section 3: Shop by Bag Category -->
    <section class="space-y-4">
        <h2 class="text-xs md:text-sm font-black text-gray-950 uppercase tracking-widest">Shop by Bag Category</h2>
        
        <!-- Cards Grid -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 md:gap-6">
            @foreach($categories->whereIn('slug', ['gym-fitness', 'camera-bags', 'bag-backpacks', 'hiking-trekking'])->take(4) as $cat)
            <div class="bg-gray-100/90 rounded-2xl p-4 md:p-5 flex flex-col justify-between items-center text-center group hover:shadow-md transition-shadow">
                <div class="w-full flex flex-col items-start text-left mb-4">
                    <span class="text-[#0082C3] font-bold text-xs uppercase tracking-wide">Featured</span>
                    <span class="text-gray-900 font-extrabold text-sm md:text-base leading-tight">{{ $cat->name }}</span>
                </div>
                <div class="w-28 h-28 md:w-40 md:h-40 flex items-center justify-center mb-6">
                    <img src="{{ $cat->image_url ?? 'https://ui-avatars.com/api/?name=' . urlencode($cat->name) }}" alt="{{ $cat->name }}" class="max-w-full max-h-full object-contain drop-shadow-md group-hover:scale-105 transition-transform duration-300">
                </div>
                <a href="{{ route('shop') }}?category={{ $cat->slug }}" class="bg-[#1b4cbf] hover:bg-[#123696] text-white text-[9px] md:text-[10px] font-black px-6 py-2 rounded-full uppercase tracking-wider shadow-sm transition-colors mt-auto w-full md:w-auto">
                    Shop Now
                </a>
            </div>
            @endforeach
        </div>
    </section>

    <!-- Section 4: Explore Nature Fearlessly Ribbon -->
    <section>
        <div class="w-full bg-[#1b4cbf] rounded-xl py-4 px-6 md:px-8 text-white flex flex-col sm:flex-row justify-between items-center gap-3 shadow-sm border border-blue-800">
            <div class="flex flex-col items-center sm:items-start text-center sm:text-left">
                <h3 class="text-base md:text-lg font-black tracking-wide">Explore Nature Fearlessly</h3>
                <p class="text-[10px] md:text-[11px] font-bold text-white/70 uppercase tracking-widest mt-0.5">QUECHUA | FORCLAZ | SIMOND</p>
            </div>
            
            <div class="flex items-center gap-2 border border-white/20 rounded-lg px-3 py-1.5 bg-white/5">
                <svg class="w-6 h-6 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 2a1 1 0 00-1 1v1.17C7.06 4.65 5.5 6.64 5.5 9v3.83l-1.12 1.11A1 1 0 005 15.67h10a1 1 0 00.62-1.73L14.5 12.83V9c0-2.36-1.56-4.35-3.5-4.83V3a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                </svg>
                <div class="flex flex-col text-left">
                    <span class="text-[11px] font-black leading-none uppercase text-yellow-400">10 YEAR</span>
                    <span class="text-[9px] font-bold leading-none text-white/80 uppercase">WARRANTY</span>
                </div>
            </div>
        </div>
    </section>

    <!-- Section 5: Featured Sport Brands -->
    <section class="space-y-4">
        <h2 class="text-xs md:text-sm font-black text-gray-950 uppercase tracking-widest">Explore our Sports Brands</h2>
        
        <div class="flex items-center gap-4 overflow-x-auto pb-4 scrollbar-hide snap-x snap-mandatory">
            @foreach($brands as $brand)
            <div class="flex-shrink-0 flex flex-col items-center w-24 snap-start">
                <a href="{{ route('shop') }}?brand={{ $brand->slug }}" class="w-16 h-16 rounded-full bg-black text-white flex items-center justify-center text-[9px] font-black tracking-wider shadow-md hover:scale-105 transition-transform duration-200 cursor-pointer border border-white/10 overflow-hidden p-2">
                    @if($brand->logo_url)
                        <img src="{{ $brand->logo_url }}" alt="{{ $brand->name }}" class="w-full h-full object-contain filter invert brightness-0">
                    @else
                        <span class="text-[10px]">{{ $brand->name }}</span>
                    @endif
                </a>
                <span class="text-[9px] text-gray-500 font-bold text-center mt-2 whitespace-nowrap">{{ $brand->name }}</span>
            </div>
            @endforeach
        </div>
    </section>

    <!-- Section 6: Shop by Volume of Backpack -->
    <section class="space-y-4">
        <h2 class="text-xs md:text-sm font-black text-gray-950 uppercase tracking-widest">Shop by Volume of Backpack</h2>
        
        <div class="grid grid-cols-5 gap-2 md:gap-4 items-end pt-6 border-b border-gray-100 pb-2">
            @foreach(['10', '20', '40', '70', '100'] as $volume)
            <a href="{{ route('shop') }}?category=bag-backpacks&volume={{ $volume }}" class="flex flex-col items-center group cursor-pointer">
                <div class="flex flex-col items-center mb-2">
                    <span class="text-3xl md:text-5xl lg:text-6xl font-black text-gray-950 tracking-tighter leading-none">{{ $volume }}</span>
                    <span class="text-[10px] md:text-xs text-gray-600 font-medium italic mt-0.5">Litres</span>
                </div>
                <div class="w-full bg-[#f7c844] h-{{ 8 + ($loop->index * 10) }} md:h-{{ 12 + ($loop->index * 12) }} rounded-t group-hover:bg-[#efbb33] transition-colors shadow-sm" style="height: {{ 30 + ($loop->index * 30) }}px"></div>
                <span class="text-[8px] md:text-[10px] text-gray-500 font-bold text-center mt-2 leading-tight">
                    @if($volume == '10') Daypacks @elseif($volume == '20') Daily @elseif($volume == '40') Travel @elseif($volume == '70') Trekking @else Duffle @endif
                </span>
            </a>
            @endforeach
        </div>
    </section>

</div>
@endsection
