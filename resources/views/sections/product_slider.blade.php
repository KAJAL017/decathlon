<section class="w-full px-4 md:px-10 lg:px-16 mb-16">
    <div class="w-full">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-lg font-bold text-gray-900">{{ $section->title }}</h2>
            <div class="flex items-center gap-2">
                <button id="prev-{{ $section->id }}" class="w-8 h-8 rounded-full border border-gray-300 flex items-center justify-center hover:bg-gray-100 transition-colors">
                    <i data-lucide="chevron-left" class="w-4 h-4"></i>
                </button>
                <button id="next-{{ $section->id }}" class="w-8 h-8 rounded-full border border-gray-300 flex items-center justify-center hover:bg-gray-100 transition-colors">
                    <i data-lucide="chevron-right" class="w-4 h-4"></i>
                </button>
            </div>
        </div>

        <div class="relative w-full">
            <div id="track-{{ $section->id }}" class="flex gap-4 overflow-x-auto snap-x snap-mandatory scrollbar-hide pb-4 scroll-smooth">
                @foreach($data as $product)
                    @include('partials.product-card', ['product' => $product])
                @endforeach
            </div>
        </div>
    </div>
</section>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const track = document.getElementById('track-{{ $section->id }}');
        const prev = document.getElementById('prev-{{ $section->id }}');
        const next = document.getElementById('next-{{ $section->id }}');
        if (!track || !prev || !next) return;

        prev.addEventListener('click', () => track.scrollBy({ left: -300, behavior: 'smooth' }));
        next.addEventListener('click', () => track.scrollBy({ left: 300, behavior: 'smooth' }));
    });
</script>
