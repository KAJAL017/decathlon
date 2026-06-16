<section class="w-full px-4 lg:px-6 mb-8">
    @if($data->count() > 0)
    <div class="w-full relative rounded-[20px] md:rounded-[30px] overflow-hidden group shadow-sm hover:shadow-md transition-shadow duration-500">
        <!-- Slider Track -->
        <div id="slider-track" class="flex transition-transform duration-700 cubic-bezier(0.4, 0, 0.2, 1)">
            @foreach($data as $banner)
            <div class="min-w-full relative aspect-[21/9] md:aspect-[25/9] lg:aspect-[28/9]">
                @if($banner->banner_link)
                <a href="{{ $banner->banner_link }}" class="block w-full h-full">
                    <img src="{{ $banner->image_url }}" alt="Banner" class="absolute inset-0 w-full h-full object-cover">
                </a>
                @else
                <img src="{{ $banner->image_url }}" alt="Banner" class="absolute inset-0 w-full h-full object-cover">
                @endif
            </div>
            @endforeach
        </div>

        <!-- Navigation Buttons -->
        @if($data->count() > 1)
        <button id="prev-btn" class="absolute left-4 top-1/2 -translate-y-1/2 bg-white/90 hover:bg-white text-gray-800 w-10 h-10 rounded-full flex items-center justify-center shadow-lg transition-all opacity-0 group-hover:opacity-100 z-20 hover:scale-110 active:scale-95 border border-gray-100">
            <i data-lucide="chevron-left" class="w-5 h-5"></i>
        </button>
        <button id="next-btn" class="absolute right-4 top-1/2 -translate-y-1/2 bg-white/90 hover:bg-white text-gray-800 w-10 h-10 rounded-full flex items-center justify-center shadow-lg transition-all opacity-0 group-hover:opacity-100 z-20 hover:scale-110 active:scale-95 border border-gray-100">
            <i data-lucide="chevron-right" class="w-5 h-5"></i>
        </button>
        
        <!-- Pagination Dots -->
        <div id="pagination" class="absolute bottom-6 left-1/2 -translate-x-1/2 flex items-center gap-2.5 z-20 bg-black/10 backdrop-blur-md px-3 py-2 rounded-full"></div>
        @endif
    </div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const track = document.getElementById('slider-track');
    const slides = Array.from(track?.children || []);
    const nextBtn = document.getElementById('next-btn');
    const prevBtn = document.getElementById('prev-btn');
    const pagination = document.getElementById('pagination');
    
    if (slides.length <= 1) return;

    let currentIndex = 0;
    let autoPlayInterval;

    // Create dots
    slides.forEach((_, index) => {
        const dot = document.createElement('button');
        dot.className = `w-2 h-2 rounded-full transition-all duration-300 ${index === 0 ? 'bg-white w-6' : 'bg-white/50 hover:bg-white/80'}`;
        dot.addEventListener('click', () => goToSlide(index));
        pagination.appendChild(dot);
    });

    const dots = Array.from(pagination.children);

    function updateSlider() {
        track.style.transform = `translateX(-${currentIndex * 100}%)`;
        dots.forEach((dot, index) => {
            if (index === currentIndex) {
                dot.classList.add('bg-white', 'w-6');
                dot.classList.remove('bg-white/50', 'w-2');
            } else {
                dot.classList.remove('bg-white', 'w-6');
                dot.classList.add('bg-white/50', 'w-2');
            }
        });
    }

    function goToSlide(index) {
        currentIndex = index;
        updateSlider();
        resetAutoPlay();
    }

    function nextSlide() {
        currentIndex = (currentIndex + 1) % slides.length;
        updateSlider();
    }

    function prevSlide() {
        currentIndex = (currentIndex - 1 + slides.length) % slides.length;
        updateSlider();
    }

    function startAutoPlay() {
        autoPlayInterval = setInterval(nextSlide, 5000);
    }

    function resetAutoPlay() {
        clearInterval(autoPlayInterval);
        startAutoPlay();
    }

    if (nextBtn) nextBtn.addEventListener('click', () => {
        nextSlide();
        resetAutoPlay();
    });

    if (prevBtn) prevBtn.addEventListener('click', () => {
        prevSlide();
        resetAutoPlay();
    });

    // Touch Support
    let touchStartX = 0;
    let touchEndX = 0;

    track.addEventListener('touchstart', e => {
        touchStartX = e.changedTouches[0].screenX;
        clearInterval(autoPlayInterval);
    }, { passive: true });

    track.addEventListener('touchend', e => {
        touchEndX = e.changedTouches[0].screenX;
        handleSwipe();
        startAutoPlay();
    }, { passive: true });

    function handleSwipe() {
        const swipeThreshold = 50;
        if (touchStartX - touchEndX > swipeThreshold) nextSlide();
        if (touchEndX - touchStartX > swipeThreshold) prevSlide();
    }

    startAutoPlay();
});
</script>
    @else
    <div class="w-full min-h-[220px] md:min-h-[320px] rounded-[20px] md:rounded-[30px] bg-gray-100 flex items-center justify-center text-center px-6">
        <div>
            <h2 class="text-2xl md:text-4xl font-black text-gray-900 uppercase tracking-tight">{{ $section->settings['fallback_title'] ?? 'Welcome to Decathlon' }}</h2>
            <p class="mt-3 text-gray-600 font-medium">{{ $section->settings['fallback_subtitle'] ?? 'New offers and sports gear are coming soon.' }}</p>
        </div>
    </div>
    @endif
</section>
