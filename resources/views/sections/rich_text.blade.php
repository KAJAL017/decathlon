@php $d = $data; @endphp
<section class="py-16 bg-white overflow-hidden">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-{{ $d['alignment'] ?? 'center' }} space-y-8">
            <div class="space-y-4">
                @if($d['title'])
                    <h2 class="text-4xl md:text-5xl font-black text-gray-950 uppercase tracking-tight leading-tight">
                        {{ $d['title'] }}
                    </h2>
                @endif
                
                @if($d['content'])
                    <div class="text-xl text-gray-600 font-medium leading-relaxed prose prose-lg max-w-none">
                        {!! $d['content'] !!}
                    </div>
                @endif
            </div>

            @if($d['button_text'] && $d['button_link'])
                <div class="pt-4">
                    <a href="{{ $d['button_link'] }}" class="inline-flex items-center gap-3 px-10 py-4 bg-gray-950 text-white text-xs font-black uppercase tracking-[0.2em] rounded-2xl shadow-xl shadow-gray-200 hover:bg-[#0082C3] hover:scale-105 active:scale-95 transition-all duration-300 group">
                        {{ $d['button_text'] }}
                        <i data-lucide="arrow-right" class="w-5 h-5 group-hover:translate-x-1 transition-transform"></i>
                    </a>
                </div>
            @endif
        </div>
    </div>
</section>
