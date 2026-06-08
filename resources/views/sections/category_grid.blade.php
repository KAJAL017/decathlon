<section class="w-full px-4 md:px-10 lg:px-16 mb-12">
    <div class="w-full flex items-start gap-3 md:gap-5 lg:justify-between overflow-x-auto pb-6 scrollbar-hide">
        @foreach($data as $category)
        <a href="{{ route('shop') }}?category={{ $category->slug }}" class="flex-shrink-0 w-[130px] cursor-pointer group flex flex-col">
            <div class="w-full h-[130px] rounded-t-md overflow-hidden bg-white">
                <img src="{{ $category->image_url ?? 'https://images.unsplash.com/photo-1551028719-00167b16eac5?w=500&auto=format&fit=crop&q=60' }}"
                    alt="{{ $category->name }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
            </div>
            <div class="w-full bg-[#525252] py-1.5 rounded-b-md flex items-center justify-center group-hover:bg-[#0082C3] transition-colors duration-300">
                <span class="text-white text-[12px] font-bold tracking-wide">{{ $category->name }}</span>
            </div>
        </a>
        @endforeach
    </div>
</section>
