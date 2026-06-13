@if($data->count() > 0)
<section class="w-full px-4 lg:px-6 mb-8">
    <div class="w-full">
        @if($section->title)
        <h2 class="text-xl font-black text-gray-900 mb-6 uppercase tracking-tight">{{ $section->title }}</h2>
        @endif
        
        <div class="flex md:grid overflow-x-auto md:overflow-visible snap-x snap-mandatory scrollbar-hide pb-4 md:pb-0 md:grid-cols-2 lg:grid-cols-4 gap-4">
            @foreach($data as $banner)
                <div class="flex-shrink-0 w-[75vw] md:w-auto snap-start relative rounded-2xl overflow-hidden group h-[180px] md:h-[250px] shadow-sm hover:shadow-md transition-shadow">
                    <img src="{{ $banner->image_url }}" alt="Banner" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                </div>
            @endforeach
        </div>
    </div>
</section>
@endif
