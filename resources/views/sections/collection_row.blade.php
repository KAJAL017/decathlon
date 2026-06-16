@php $col = $data['collection']; $prods = $data['products']; @endphp
@if($col)
<section class="py-16 bg-white overflow-hidden">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col lg:flex-row gap-8">
            <!-- Featured Collection Card -->
            <div class="w-full lg:w-1/3 h-[500px] lg:h-auto rounded-[40px] overflow-hidden relative group">
                @if($col->image_url)
                    <img src="{{ $col->image_url }}" alt="{{ $col->name }}" class="w-full h-full object-cover transform scale-100 group-hover:scale-110 transition-transform duration-1000">
                @else
                    <div class="w-full h-full bg-[#0082C3]"></div>
                @endif
                <div class="absolute inset-0 bg-gradient-to-t from-gray-950/80 via-transparent to-transparent"></div>
                <div class="absolute inset-x-8 bottom-8">
                    <h3 class="text-3xl font-black text-white uppercase tracking-tighter mb-4">{{ $col->name }}</h3>
                    <p class="text-white/70 text-sm font-medium mb-6 line-clamp-2">{{ $col->description }}</p>
                    <a href="{{ route('shop', ['collection' => $col->slug]) }}" class="inline-flex items-center gap-3 px-8 py-3 bg-white text-gray-950 text-[10px] font-black uppercase tracking-[0.2em] rounded-full hover:bg-[#0082C3] hover:text-white transition-all shadow-xl">
                        {{ $section->settings['cta_text'] ?? 'Explore Collection' }}
                        <i data-lucide="arrow-right" class="w-4 h-4"></i>
                    </a>
                </div>
            </div>

            <!-- Product Grid -->
            <div class="w-full lg:w-2/3 grid grid-cols-2 gap-6">
                @foreach($prods as $product)
                    @include('partials.product-card', ['product' => $product])
                @endforeach
            </div>
        </div>
    </div>
</section>
@endif
