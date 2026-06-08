<section class="w-full bg-white border-t border-b border-gray-200 py-8 md:py-12 mt-8 mb-8 px-4 md:px-12 lg:px-16">
    <div class="w-full">
        <div class="flex items-center gap-4 cursor-pointer select-none group" id="sports-collections-header" onclick="toggleSportsCollectionsMain()">
            <div class="flex items-center gap-2 text-gray-950 font-bold text-base md:text-lg uppercase tracking-wider">
                <span id="main-toggle-icon" class="text-xl font-light w-5 text-center transition-transform duration-300">—</span>
                <span>{{ $section->title }}</span>
            </div>
            <div class="flex-grow h-[1px] bg-gray-200"></div>
        </div>

        <div id="sports-collections-content" class="transition-all duration-300 ease-in-out mt-6 space-y-4">
            @foreach($data as $parent)
            <div class="border-b border-gray-100 pb-3 last:border-0 last:pb-0">
                <div class="flex items-center gap-4 cursor-pointer select-none py-2 group" onclick="toggleSportsAccordion('{{ $parent->slug }}')">
                    <div class="flex items-center gap-2 text-gray-900 font-medium text-sm md:text-base hover:text-[#0082C3] transition-colors">
                        <span id="icon-{{ $parent->slug }}" class="text-lg font-light w-5 text-center transition-transform duration-300 text-gray-500 group-hover:text-[#0082C3]">+</span>
                        <span class="tracking-wide">{{ $parent->name }}</span>
                    </div>
                    <div class="flex-grow h-[1px] bg-gray-100 group-hover:bg-gray-200 transition-colors"></div>
                </div>
                <div id="content-{{ $parent->slug }}" class="max-h-0 overflow-hidden transition-all duration-300 ease-in-out">
                    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-4 pt-4 pb-2 px-7">
                        @foreach($parent->children as $child)
                        <a href="{{ route('shop') }}?category={{ $child->slug }}" class="flex flex-col items-center p-3 rounded-xl bg-gray-50 hover:bg-gray-100 transition-all hover:scale-[1.03] group/card border border-gray-100/50">
                            <div class="w-14 h-14 rounded-full overflow-hidden bg-white flex items-center justify-center mb-2 shadow-sm">
                                <img src="{{ $child->image_url ?? 'https://ui-avatars.com/api/?name=' . urlencode($child->name) }}" alt="{{ $child->name }}" class="w-full h-full object-cover group-hover/card:scale-110 transition-transform">
                            </div>
                            <span class="text-xs font-semibold text-gray-800 text-center">{{ $child->name }}</span>
                        </a>
                        @endforeach
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>

<script>
    function toggleSportsCollectionsMain() {
        const content = document.getElementById('sports-collections-content');
        const icon = document.getElementById('main-toggle-icon');
        if (content.style.maxHeight === '0px' || content.classList.contains('collapsed')) {
            content.style.maxHeight = content.scrollHeight + 'px';
            content.classList.remove('collapsed');
            icon.textContent = '—';
        } else {
            content.style.maxHeight = '0px';
            content.classList.add('collapsed');
            icon.textContent = '+';
        }
    }

    function toggleSportsAccordion(slug) {
        const content = document.getElementById('content-' + slug);
        const icon = document.getElementById('icon-' + slug);
        if (content.style.maxHeight && content.style.maxHeight !== '0px') {
            content.style.maxHeight = '0px';
            icon.textContent = '+';
        } else {
            content.style.maxHeight = content.scrollHeight + 'px';
            icon.textContent = '—';
        }
    }
</script>
