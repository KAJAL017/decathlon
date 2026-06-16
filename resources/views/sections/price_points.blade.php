<section class="w-full px-4 lg:px-6 mb-8">
    <div class="w-full">
        @if($section->title)
        <h2 class="text-lg font-bold text-gray-950 mb-6 tracking-tight">{{ $section->title }}</h2>
        @endif
        
        @php
            $pricePrefix = $section->settings['price_prefix'] ?? 'UNDER';
            $ctaText = $section->settings['cta_text'] ?? 'Explore Now';
            $bgColor = $section->settings['bg_color'] ?? '#2f5cc4';
        @endphp
        
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 md:gap-6">
            @foreach($data as $price)
            <a href="{{ route('shop') }}?price={{ $price }}" class="relative rounded-[24px] aspect-[3/2] flex flex-col justify-center items-center overflow-hidden p-6 group cursor-pointer shadow-sm hover:shadow-md transition-shadow" style="background-color: {{ $bgColor }}">
                <div class="absolute inset-0 flex items-center justify-center pointer-events-none select-none opacity-[0.15]">
                    <svg class="w-full h-full scale-125 text-white fill-current" viewBox="0 0 100 100"><path d="M20,50 C20,30 40,15 60,15 C80,15 85,35 75,50 C65,65 35,80 20,50 Z M40,50 C40,60 55,55 60,45 C65,35 55,30 50,30 C45,30 40,40 40,50 Z" /></svg>
                </div>
                <div class="relative z-10 flex flex-col items-center text-center space-y-2">
                    <span class="text-white text-xs md:text-sm font-extrabold uppercase tracking-widest opacity-95">{{ $pricePrefix }}</span>
                    <h3 class="text-white text-3xl md:text-5xl font-black leading-none pb-2">₹{{ number_format($price) }}</h3>
                    <button class="bg-white text-gray-900 text-[11px] font-bold px-6 py-2 rounded-full shadow-sm hover:scale-105 transition-transform duration-200">{{ $ctaText }}</button>
                </div>
            </a>
            @endforeach
        </div>
    </div>
</section>
