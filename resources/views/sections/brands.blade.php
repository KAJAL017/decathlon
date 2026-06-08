@if($data->count() > 0)
<section class="w-full px-4 lg:px-6 mb-12">
    <div class="w-full">
        <h2 class="text-xl font-black text-gray-900 mb-6 uppercase tracking-tight">{{ $section->title }}</h2>
        <div class="flex items-center gap-6 overflow-x-auto pb-4 scrollbar-hide">
            @foreach($data as $brand)
            <a href="{{ route('shop') }}?brand={{ $brand->slug }}" class="flex-shrink-0 group">
                <div class="w-32 h-20 bg-white border border-gray-100 rounded-xl flex items-center justify-center p-4 hover:border-[#0082C3] hover:shadow-md transition-all">
                    @if($brand->logo_url)
                        <img src="{{ $brand->logo_url }}" alt="{{ $brand->name }}" class="max-w-full max-h-full object-contain filter grayscale group-hover:grayscale-0 transition-all">
                    @else
                        <span class="text-gray-400 font-bold group-hover:text-[#0082C3] text-center text-xs px-2">{{ $brand->name }}</span>
                    @endif
                </div>
            </a>
            @endforeach
        </div>
    </div>
</section>
@endif
