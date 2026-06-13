@if($data->count() > 0)
<section class="w-full px-4 lg:px-6 mb-8">
    <div class="w-full">
        <div class="relative w-full h-[300px] md:h-[400px] rounded-2xl overflow-hidden group">
            <div id="promo-track" class="flex h-full transition-transform duration-700 ease-in-out">
                @foreach($data as $banner)
                <div class="min-w-full h-full relative">
                    <img src="{{ $banner->image_url }}" alt="Promotion" class="w-full h-full object-cover">
                </div>
                @endforeach
            </div>
            
            @if($data->count() > 1)
            <div id="promo-pagination" class="absolute bottom-4 left-1/2 transform -translate-x-1/2 flex items-center gap-2 z-20 bg-black/20 backdrop-blur-sm px-3 py-1.5 rounded-full"></div>
            @endif
        </div>
    </div>
</section>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const track = document.getElementById('promo-track');
    if (!track) return;
    const slides = Array.from(track.children);
    const pagination = document.getElementById('promo-pagination');
    
    if (slides.length <= 1) return;

    let currentIndex = 0;

    slides.forEach((_, index) => {
        const dot = document.createElement('button');
        dot.className = `w-2 h-2 rounded-full transition-all duration-300 ${index === 0 ? 'bg-white w-4' : 'bg-white/50'}`;
        dot.addEventListener('click', () => {
            currentIndex = index;
            updatePromo();
        });
        pagination.appendChild(dot);
    });

    const dots = Array.from(pagination.children);

    function updatePromo() {
        track.style.transform = `translateX(-${currentIndex * 100}%)`;
        dots.forEach((dot, index) => {
            if (index === currentIndex) {
                dot.classList.add('bg-white', 'w-4');
                dot.classList.remove('bg-white/50', 'w-2');
            } else {
                dot.classList.remove('bg-white', 'w-4');
                dot.classList.add('bg-white/50', 'w-2');
            }
        });
    }

    setInterval(() => {
        currentIndex = (currentIndex + 1) % slides.length;
        updatePromo();
    }, 6000);
});
</script>
@endif
