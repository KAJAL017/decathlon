@php $d = $data; @endphp
<section class="py-12 bg-white">
    <div class="w-full px-4 md:px-10 lg:px-16">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
            @foreach($d as $item)
                <div class="flex items-center gap-5 p-6 rounded-[30px] bg-gray-50 hover:bg-white hover:shadow-xl hover:shadow-gray-100 transition-all duration-500 group">
                    <div class="w-14 h-14 rounded-2xl bg-[#0082C3] flex items-center justify-center text-white shadow-lg shadow-blue-100 group-hover:scale-110 transition-transform">
                        @switch($item['icon'] ?? 'star')
                            @case('truck')
                                <i data-lucide="truck" class="w-7 h-7"></i>
                                @break
                            @case('shield')
                                <i data-lucide="shield-check" class="w-7 h-7"></i>
                                @break
                            @case('refresh')
                                <i data-lucide="refresh-cw" class="w-7 h-7"></i>
                                @break
                            @case('lock')
                                <i data-lucide="lock" class="w-7 h-7"></i>
                                @break
                            @default
                                <i data-lucide="star" class="w-7 h-7"></i>
                        @endswitch
                    </div>
                    <div>
                        <h4 class="text-sm font-black text-gray-950 uppercase tracking-widest">{{ $item['title'] }}</h4>
                        <p class="text-xs text-gray-500 font-bold uppercase tracking-tight mt-0.5">{{ $item['text'] }}</p>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>
