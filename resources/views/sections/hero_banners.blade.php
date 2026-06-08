@if($data->count() > 0)
<section class="w-full px-4 lg:px-6 mb-8">
    <div class="w-full relative w-full h-[450px] md:h-[323px] rounded-[30px] overflow-hidden group">
        <!-- Slider Track -->
        <div id="slider-track" class="flex h-full transition-transform duration-500 ease-in-out">
            @foreach($data as $banner)
            <div class="min-w-full h-full bg-[{{ $banner->background_color }}] relative flex flex-col md:flex-row items-center overflow-hidden">
                <!-- Left Section: Product Image -->
                <div class="w-full md:w-[45%] h-1/2 md:h-full relative flex items-center justify-center">
                    <div class="absolute inset-0 bg-gradient-to-br from-gray-100 to-gray-200 transform -skew-y-2 origin-bottom-left"></div>
                    <div class="relative z-10 w-full h-full flex items-center justify-center">
                        <img src="{{ $banner->image_url }}" alt="{{ $banner->title }}" class="max-h-[85%] max-w-[85%] object-contain drop-shadow-2xl transform {{ $loop->index % 2 == 0 ? '-rotate-12' : 'rotate-12' }}">
                    </div>
                </div>

                <!-- Wavy Divider -->
                <div class="hidden md:block absolute left-[45%] top-0 h-full w-[80px] z-10">
                    <svg viewBox="0 0 80 400" class="h-full w-full" preserveAspectRatio="none">
                        <path d="M 0 0 Q 40 50, 0 100 T 0 200 T 0 300 T 0 400 L 80 400 L 80 0 Z" fill="{{ $banner->background_color }}" stroke="none" />
                    </svg>
                </div>

                <!-- Right Section: Text Content -->
                <div class="w-full md:w-[55%] h-1/2 md:h-full flex items-center justify-center md:justify-start px-4 md:pl-16 md:pr-8 relative text-center md:text-left">
                    <div class="absolute -right-16 -top-16 w-48 h-48 bg-yellow-400 rounded-full blur-3xl opacity-70"></div>
                    
                    <div class="relative z-10 flex flex-col items-center md:items-start">
                        <h3 class="text-sm md:text-xl font-medium text-gray-800 mb-1">{{ $banner->subtitle }}</h3>
                        <h1 class="text-3xl md:text-5xl font-bold text-gray-900 leading-tight mb-3">
                            {{ $banner->title }}
                        </h1>
                        <div class="flex items-baseline gap-2 mb-4">
                            <span class="text-base text-gray-600 font-medium">{{ $banner->price_text }}</span>
                        </div>
                        <a href="{{ $banner->button_link }}" class="bg-white text-gray-900 px-7 py-2.5 rounded-full font-bold text-sm shadow-md hover:shadow-lg hover:scale-105 transition-all duration-300 inline-block">
                            {{ $banner->button_text ?? 'Shop Now' }}
                        </a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <!-- Navigation Buttons -->
        @if($data->count() > 1)
        <button id="prev-btn" class="absolute left-4 top-1/2 transform -translate-y-1/2 bg-white/80 hover:bg-white text-gray-800 w-10 h-10 rounded-full flex items-center justify-center shadow-md transition-all opacity-0 group-hover:opacity-100 z-20 border border-gray-200">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5"><path d="M15.75 19.5L8.25 12l7.5-7.5" /></svg>
        </button>
        <button id="next-btn" class="absolute right-4 top-1/2 transform -translate-y-1/2 bg-white/80 hover:bg-white text-gray-800 w-10 h-10 rounded-full flex items-center justify-center shadow-md transition-all opacity-0 group-hover:opacity-100 z-20 border border-gray-200">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5"><path d="M8.25 4.5l7.5 7.5-7.5 7.5" /></svg>
        </button>
        <div id="pagination" class="absolute bottom-4 left-1/2 transform -translate-x-1/2 flex items-center gap-2 z-20"></div>
        @endif
    </div>
</section>
@endif
