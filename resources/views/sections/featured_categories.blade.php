<section class="w-full px-4 lg:px-6 mb-8">
    <div class="w-full">
        <div class="flex items-center gap-3 overflow-x-auto snap-x snap-mandatory scrollbar-hide pb-4 w-full">
            @foreach($data as $category)
            <a href="{{ route('shop') }}?category={{ $category->slug }}" class="flex-shrink-0 w-[100px] md:w-[120px] snap-start cursor-pointer group">
                <div class="relative w-[100px] h-[100px] md:w-[120px] md:h-[120px] rounded-2xl overflow-hidden bg-gray-200">
                    <img src="{{ $category->image_url ?? 'https://ui-avatars.com/api/?name=' . urlencode($category->name) }}" 
                         alt="{{ $category->name }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-300">
                </div>
            </a>
            @endforeach
            <a href="{{ route('shop') }}" class="flex-shrink-0 w-[100px] md:w-[120px] snap-start cursor-pointer group">
                <div class="relative w-[100px] h-[100px] md:w-[120px] md:h-[120px] rounded-2xl overflow-hidden bg-gray-200 flex items-center justify-center">
                    <img src="https://images.unsplash.com/photo-1461896836934-ffe607ba8211?w=400&auto=format&fit=crop&q=80" alt="All Sports" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-300">
                </div>
            </a>
        </div>
    </div>
</section>
