@if($data->count() > 0)
<section class="w-full px-4 lg:px-6 mb-8">
    <div class="w-full">
        @if($section->title)
        <h2 class="text-xl font-black text-gray-900 mb-6 uppercase tracking-tight">{{ $section->title }}</h2>
        @endif
        
        <div class="flex md:grid overflow-x-auto md:overflow-visible snap-x snap-mandatory scrollbar-hide pb-4 md:pb-0 md:grid-cols-2 lg:grid-cols-4 gap-4">
            @foreach($data as $banner)
                @if(($section->settings['style'] ?? '') === 'overlay')
                <a href="{{ $banner->button_link }}" class="flex-shrink-0 w-[75vw] md:w-auto snap-start relative rounded-2xl overflow-hidden group cursor-pointer h-[180px] md:h-[280px]">
                    <img src="{{ $banner->image_url }}" alt="{{ $banner->title }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-300">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/40 to-transparent"></div>
                    <div class="absolute bottom-0 left-0 right-0 p-6 text-white">
                        <h3 class="text-2xl font-bold mb-2 {{ $section->settings['italic'] ?? false ? 'italic' : '' }}">{{ $banner->title }}</h3>
                        <div class="flex items-baseline gap-1">
                            <span class="text-3xl font-bold">{{ $banner->price_text }}</span>
                            <span class="text-sm">Onwards</span>
                        </div>
                    </div>
                </a>
                @else
                <a href="{{ $banner->button_link }}" class="flex-shrink-0 w-[75vw] md:w-auto snap-start bg-gray-200 rounded-2xl overflow-hidden group cursor-pointer hover:shadow-lg transition-shadow">
                    <div class="bg-gray-200 p-8 flex items-center justify-center h-[200px]">
                        <img src="{{ $banner->image_url }}" alt="{{ $banner->title }}" class="w-full h-full object-contain group-hover:scale-110 transition-transform">
                    </div>
                    <div class="bg-gray-200 p-4">
                        <h3 class="text-xl font-bold text-gray-900 mb-1">{{ $banner->title }}</h3>
                        <div class="flex items-center justify-between">
                            <div class="flex items-baseline gap-1">
                                <span class="text-3xl font-bold text-gray-900">{{ $banner->price_text }}</span>
                                <span class="text-xs text-gray-600">Onwards</span>
                            </div>
                            <button class="w-8 h-8 rounded-full bg-white flex items-center justify-center hover:bg-gray-100 transition-colors">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-5 h-5"><path d="M8.25 4.5l7.5 7.5-7.5 7.5" /></svg>
                            </button>
                        </div>
                    </div>
                </a>
                @endif
            @endforeach
        </div>
    </div>
</section>
@endif
