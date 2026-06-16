@php $d = $data; @endphp
<section class="py-16 bg-gray-50 overflow-hidden relative group/section">
    <div class="w-full px-4 md:px-10 lg:px-16">
        <div class="flex flex-col md:flex-row md:items-end justify-between mb-12 gap-6">
            @if($section->title || $section->subtitle)
                <div class="text-left">
                    @if($section->title)
                        <h2 class="text-4xl md:text-5xl font-black text-gray-950 uppercase tracking-tight">{{ $section->title }}</h2>
                    @endif
                    @if($section->subtitle)
                        <p class="mt-3 text-sm md:text-base text-gray-500 font-bold uppercase tracking-[0.2em]">{{ $section->subtitle }}</p>
                    @endif
                </div>
            @endif

            <!-- Navigation Buttons -->
            <div class="flex items-center gap-3">
                <button id="testimonial-prev-{{ $section->id }}" class="w-14 h-14 rounded-full border-2 border-gray-200 flex items-center justify-center text-gray-950 hover:bg-gray-950 hover:text-white hover:border-gray-950 transition-all duration-300">
                    <i data-lucide="chevron-left" class="w-6 h-6"></i>
                </button>
                <button id="testimonial-next-{{ $section->id }}" class="w-14 h-14 rounded-full border-2 border-gray-200 flex items-center justify-center text-gray-950 hover:bg-gray-950 hover:text-white hover:border-gray-950 transition-all duration-300">
                    <i data-lucide="chevron-right" class="w-6 h-6"></i>
                </button>
            </div>
        </div>

        <div class="relative overflow-hidden -mx-4">
            <div id="testimonials-track-{{ $section->id }}" class="flex transition-transform duration-700 ease-out">
                @foreach($d as $item)
                    <div class="min-w-full md:min-w-[50%] lg:min-w-[33.333%] px-4">
                        <div class="bg-white p-10 rounded-[48px] shadow-sm hover:shadow-2xl hover:shadow-gray-200/50 transition-all duration-500 h-full flex flex-col border border-gray-100">
                            <div class="flex gap-1 mb-8">
                                @for($i=0; $i<5; $i++)
                                    <i data-lucide="star" class="w-5 h-5 text-yellow-400 fill-current"></i>
                                @endfor
                            </div>
                            <blockquote class="text-xl text-gray-800 font-medium leading-relaxed flex-1">
                                "{{ $item['quote'] }}"
                            </blockquote>
                            <div class="mt-10 flex items-center gap-5">
                                <div class="w-14 h-14 rounded-2xl bg-gray-100 overflow-hidden ring-4 ring-gray-50">
                                    <img src="{{ $item['avatar'] ?? asset('images/placeholder-avatar.svg') }}" alt="{{ $item['author'] }}" class="w-full h-full object-cover">
                                </div>
                                <div>
                                    <div class="text-base font-black text-gray-950 uppercase tracking-widest leading-none">{{ $item['author'] }}</div>
                                    <div class="text-[10px] text-[#0082C3] font-black uppercase tracking-[0.2em] mt-2">{{ $item['role'] ?? 'Verified Athlete' }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</section>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const track = document.getElementById('testimonials-track-{{ $section->id }}');
    const prevBtn = document.getElementById('testimonial-prev-{{ $section->id }}');
    const nextBtn = document.getElementById('testimonial-next-{{ $section->id }}');
    
    if (!track || !prevBtn || !nextBtn) return;

    let currentIndex = 0;
    const items = track.children;
    const totalItems = items.length;
    
    function getItemsPerView() {
        if (window.innerWidth >= 1024) return 3;
        if (window.innerWidth >= 768) return 2;
        return 1;
    }

    function updateSlider() {
        const itemsPerView = getItemsPerView();
        const maxIndex = Math.max(0, totalItems - itemsPerView);
        
        if (currentIndex > maxIndex) currentIndex = maxIndex;
        
        const offset = currentIndex * (100 / itemsPerView);
        track.style.transform = `translateX(-${offset}%)`;
        
        // Update button states
        prevBtn.style.opacity = currentIndex === 0 ? '0.3' : '1';
        prevBtn.style.pointerEvents = currentIndex === 0 ? 'none' : 'auto';
        
        nextBtn.style.opacity = currentIndex === maxIndex ? '0.3' : '1';
        nextBtn.style.pointerEvents = currentIndex === maxIndex ? 'none' : 'auto';
    }

    nextBtn.addEventListener('click', () => {
        const itemsPerView = getItemsPerView();
        if (currentIndex < totalItems - itemsPerView) {
            currentIndex++;
            updateSlider();
        }
    });

    prevBtn.addEventListener('click', () => {
        if (currentIndex > 0) {
            currentIndex--;
            updateSlider();
        }
    });

    // Auto-slide every 5 seconds
    let interval = setInterval(() => {
        const itemsPerView = getItemsPerView();
        if (currentIndex < totalItems - itemsPerView) {
            currentIndex++;
        } else {
            currentIndex = 0;
        }
        updateSlider();
    }, 5000);

    // Pause on hover
    track.parentElement.addEventListener('mouseenter', () => clearInterval(interval));
    track.parentElement.addEventListener('mouseleave', () => {
        interval = setInterval(() => {
            const itemsPerView = getItemsPerView();
            if (currentIndex < totalItems - itemsPerView) {
                currentIndex++;
            } else {
                currentIndex = 0;
            }
            updateSlider();
        }, 5000);
    });

    window.addEventListener('resize', updateSlider);
    updateSlider();
});
</script>
