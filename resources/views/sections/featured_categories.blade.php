<section class="w-full px-4 lg:px-6 mb-8">
    <div class="w-full">
        @if($section->title || $section->subtitle)
        <div class="mb-6">
            @if($section->title)
                <h2 class="text-2xl font-black text-gray-950 uppercase tracking-tighter">{{ $section->title }}</h2>
            @endif
            @if($section->subtitle)
                <p class="text-sm text-gray-500 font-bold uppercase tracking-widest mt-1">{{ $section->subtitle }}</p>
            @endif
        </div>
        @endif
        <div class="flex items-center gap-3 overflow-x-auto snap-x snap-mandatory scrollbar-hide pb-4 w-full">
            @foreach($data as $category)
            <a href="{{ route('shop') }}?category={{ $category->slug }}" class="flex-shrink-0 w-[100px] md:w-[120px] snap-start cursor-pointer group">
                <div class="relative w-[100px] h-[100px] md:w-[120px] md:h-[120px] rounded-2xl overflow-hidden bg-gray-200">
                    <img src="{{ $category->image_url ?? asset('images/placeholder-category.svg') }}" 
                         alt="{{ $category->name }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-300">
                </div>
            </a>
            @endforeach
            @if(!($section->settings['hide_all_sports_card'] ?? false))
            <a href="{{ route('shop') }}" class="flex-shrink-0 w-[100px] md:w-[120px] snap-start cursor-pointer group">
                <div class="relative w-[100px] h-[100px] md:w-[120px] md:h-[120px] rounded-2xl overflow-hidden bg-gray-200 flex items-center justify-center">
                    @if($section->settings['all_sports_image'] ?? null)
                        <img src="{{ $section->settings['all_sports_image'] }}" alt="{{ $section->settings['all_sports_label'] ?? 'All Sports' }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-300">
                    @else
                        <div class="flex flex-col items-center justify-center">
                            <i data-lucide="layout-grid" class="w-8 h-8 text-gray-400"></i>
                            <span class="text-[10px] font-black text-gray-400 uppercase tracking-widest mt-1">{{ $section->settings['all_sports_label'] ?? 'All Sports' }}</span>
                        </div>
                    @endif
                </div>
            </a>
            @endif
        </div>
    </div>
</section>
