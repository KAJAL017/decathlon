<section class="w-full px-4 lg:px-6 mb-8">
    <div class="w-full flex flex-col lg:flex-row gap-6 items-stretch">
        <div class="w-full lg:w-[18%] flex flex-col justify-center items-center text-center py-3 gap-8">
            <div>
                @if($section->subtitle)
                <p class="text-gray-500 text-lg font-normal leading-none tracking-tight">{{ $section->subtitle }}</p>
                @endif
                <h2 class="text-gray-900 text-4xl font-extrabold tracking-tight mt-1 leading-none">
                    {{ str_replace($section->subtitle ?? '', '', $section->title) }}
                </h2>
            </div>
            <div class="flex items-center gap-3 mt-4">
                <button id="prev-{{ $section->id }}" class="w-10 h-10 rounded-full border border-gray-300 flex items-center justify-center text-gray-500 hover:bg-gray-100 hover:border-gray-900 transition-all shadow-sm">
                    <i data-lucide="chevron-left" class="w-5 h-5"></i>
                </button>
                <button id="next-{{ $section->id }}" class="w-10 h-10 rounded-full border border-gray-300 flex items-center justify-center text-gray-500 hover:bg-gray-100 hover:border-gray-900 transition-all shadow-sm">
                    <i data-lucide="chevron-right" class="w-5 h-5"></i>
                </button>
            </div>
        </div>
        <div class="w-full lg:w-[82%] overflow-hidden relative">
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
