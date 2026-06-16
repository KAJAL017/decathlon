<section id="hero" class="w-full mt-6 mb-12 px-4 lg:px-6">
    <div class="w-full grid grid-cols-2 sm:grid-cols-4 lg:grid-cols-8 gap-4 md:gap-5">
        @foreach($data as $category)
        <a href="{{ route('shop') }}?category={{ $category->slug }}" class="group flex flex-col w-full">
            <div class="w-full aspect-square rounded-t-xl overflow-hidden bg-white shadow-sm group-hover:shadow-md transition-shadow">
                <img src="{{ $category->image_url ?? asset('images/placeholder-category.svg') }}"
                    alt="{{ $category->name }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700">
            </div>
            <div class="w-full bg-[#333333] py-2.5 rounded-b-xl flex items-center justify-center group-hover:bg-[#0082C3] transition-colors duration-300">
                <span class="text-white text-[13px] md:text-[14px] font-extrabold tracking-wide text-center px-2">{{ $category->name }}</span>
            </div>
        </a>
        @endforeach
    </div>
</section>
