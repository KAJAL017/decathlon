@if($data->count() > 0)
<section class="w-full px-4 lg:px-6 mb-8">
    <div class="w-full">
        <div class="relative w-full h-[450px] md:h-[280px] rounded-2xl overflow-hidden">
            <div id="banner-track" class="flex h-full transition-transform duration-700 ease-in-out">
                @foreach($data as $banner)
                <div class="min-w-full h-full bg-gradient-to-r from-gray-700 via-gray-800 to-gray-900 relative flex flex-col md:flex-row items-center justify-center md:justify-start px-6 md:px-12 py-8 md:py-0" style="background: linear-gradient(to right, {{ $banner->background_color }}, {{ $banner->accent_color }})">
                    <div class="w-full md:w-1/2 z-10 text-center md:text-left mb-6 md:mb-0 mt-8 md:mt-0">
                        <h2 class="text-5xl font-bold text-white mb-2 leading-tight">{{ $banner->title }}</h2>
                        <p class="text-lg text-white/80 font-medium mb-6">{{ $banner->subtitle }}</p>
                        <a href="{{ $banner->button_link }}" class="bg-white text-gray-900 px-8 py-3 rounded-full font-bold text-sm hover:bg-gray-100 transition-all inline-block">
                            {{ $banner->button_text ?? 'Shop Now' }}
                        </a>
                    </div>
                    <div class="w-full md:w-1/2 h-48 md:h-full relative flex items-center justify-center md:justify-end">
                        <img src="{{ $banner->image_url }}" alt="{{ $banner->title }}" class="h-64 w-auto object-contain drop-shadow-2xl">
                    </div>
                </div>
                @endforeach
            </div>
            <div id="banner-pagination" class="absolute bottom-4 left-1/2 transform -translate-x-1/2 flex items-center gap-2 z-20"></div>
        </div>
    </div>
</section>
@endif
