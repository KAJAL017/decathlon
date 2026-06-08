@php $collections = $data; @endphp
<section class="py-16 bg-white overflow-hidden">
    <div class="w-full px-4 md:px-10 lg:px-16">
        @if($section->title || $section->subtitle)
            <div class="mb-12">
                @if($section->title)
                    <h2 class="text-4xl md:text-5xl font-black text-gray-950 uppercase tracking-tight">{{ $section->title }}</h2>
                @endif
                @if($section->subtitle)
                    <p class="mt-3 text-sm md:text-base text-gray-500 font-bold uppercase tracking-[0.2em]">{{ $section->subtitle }}</p>
                @endif
            </div>
        @endif

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 md:gap-10">
            @foreach($collections as $collection)
                <a href="{{ route('shop', ['collection' => $collection->slug]) }}" class="group relative h-[500px] md:h-[650px] rounded-[48px] overflow-hidden bg-gray-200 block">
                    @if($collection->image_url)
                        <img src="{{ $collection->image_url }}" alt="{{ $collection->name }}" class="w-full h-full object-cover transform group-hover:scale-105 transition-transform duration-[2000ms]">
                    @endif
                    <div class="absolute inset-0 bg-gradient-to-t from-gray-950/90 via-gray-950/20 to-transparent"></div>
                    <div class="absolute bottom-12 left-12 right-12">
                        <h3 class="text-4xl md:text-5xl font-black text-white uppercase tracking-tighter mb-6 leading-tight">{{ $collection->name }}</h3>
                        <span class="inline-flex items-center gap-4 px-10 py-4 bg-white text-gray-950 text-[11px] font-black uppercase tracking-[0.3em] rounded-full group-hover:bg-[#0082C3] group-hover:text-white transition-all shadow-xl">
                            Explore Collection
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                        </span>
                    </div>
                </a>
            @endforeach
        </div>
    </div>
</section>
