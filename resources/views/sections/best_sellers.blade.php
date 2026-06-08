@php $prods = $data; @endphp
<section class="py-16 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between mb-12 border-b border-gray-100 pb-8">
            <div>
                <h2 class="text-3xl font-black text-gray-950 uppercase tracking-tighter">{{ $section->title ?? 'Best Sellers' }}</h2>
                @if($section->subtitle)
                    <p class="text-sm text-gray-500 font-bold uppercase tracking-widest mt-2">{{ $section->subtitle }}</p>
                @endif
            </div>
            <a href="{{ route('shop') }}" class="group flex items-center gap-2 text-xs font-black text-gray-950 uppercase tracking-widest hover:text-[#0082C3] transition-colors">
                View All Collection
                <svg class="w-4 h-4 transform group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
            </a>
        </div>

        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-8">
            @foreach($prods as $product)
                <x-frontend.product-card :product="$product" />
            @endforeach
        </div>
    </div>
</section>
