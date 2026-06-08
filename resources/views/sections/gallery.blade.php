@php $d = $data; @endphp
<section class="py-16 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        @if($section->title || $section->subtitle)
            <div class="text-center mb-12">
                @if($section->title)
                    <h2 class="text-3xl font-black text-gray-950 uppercase tracking-tight">{{ $section->title }}</h2>
                @endif
                @if($section->subtitle)
                    <p class="mt-2 text-sm text-gray-500 font-bold uppercase tracking-widest">{{ $section->subtitle }}</p>
                @endif
            </div>
        @endif

        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
            @foreach($d as $image)
                <div class="group relative aspect-square rounded-[30px] overflow-hidden bg-gray-100 shadow-sm hover:shadow-2xl transition-all duration-500">
                    <img src="{{ $image['url'] }}" alt="Gallery Item" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110">
                    
                    @if(!empty($image['link']))
                        <a href="{{ $image['link'] }}" class="absolute inset-0 z-10 flex items-center justify-center bg-gray-950/40 opacity-0 group-hover:opacity-100 transition-opacity">
                            <span class="px-6 py-2 bg-white text-gray-950 text-[10px] font-black uppercase tracking-[0.2em] rounded-full">View Details</span>
                        </a>
                    @endif
                </div>
            @endforeach
        </div>
    </div>
</section>
