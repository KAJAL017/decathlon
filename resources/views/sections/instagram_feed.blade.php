@php $d = $data; @endphp
<section class="py-16 bg-white overflow-hidden">
    <div class="w-full px-4 md:px-10 lg:px-16">
        <div class="flex items-center justify-between mb-12">
            <div>
                <h2 class="text-3xl font-black text-gray-950 uppercase tracking-tighter">{{ $d['title'] }}</h2>
                @if($d['username'])
                    <a href="https://instagram.com/{{ ltrim($d['username'], '@') }}" target="_blank" class="text-[#0082C3] font-bold uppercase tracking-widest text-xs mt-2 inline-block hover:underline">
                        {{ $d['username'] }}
                    </a>
                @endif
            </div>
            <div class="hidden sm:block">
                <a href="https://instagram.com/{{ ltrim($d['username'], '@') }}" target="_blank" class="px-8 py-3 border-2 border-gray-950 text-gray-950 text-[10px] font-black uppercase tracking-[0.2em] rounded-full hover:bg-gray-950 hover:text-white transition-all">
                    {{ $d['cta_text'] ?? 'Follow Our Journey' }}
                </a>
            </div>
        </div>

        <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-5 gap-4">
            @foreach(array_slice($d['images'], 0, 10) as $image)
                <div class="group relative aspect-square rounded-[30px] overflow-hidden bg-gray-100 shadow-sm transition-all duration-500 hover:shadow-2xl hover:-translate-y-1">
                    <img src="{{ $image }}" alt="Social Post" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110">
                    <div class="absolute inset-0 bg-gray-950/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                        <i data-lucide="heart" class="w-8 h-8 text-white"></i>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>
