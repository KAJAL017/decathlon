@php $d = $data; @endphp
<section class="py-16 bg-white relative overflow-hidden min-h-[500px] flex items-center">
    @if($d['background_image'])
        <img src="{{ $d['background_image'] }}" class="absolute inset-0 w-full h-full object-cover" alt="Sale Background">
        <div class="absolute inset-0 bg-gray-950/60 backdrop-blur-[2px]"></div>
    @else
        <div class="absolute inset-0 bg-[#0082C3]"></div>
        <div class="absolute inset-0 opacity-10" style="background-image: url('data:image/svg+xml,%3Csvg width=\"60\" height=\"60\" viewBox=\"0 0 60 60\" xmlns=\"http://www.w3.org/2000/svg\"%3E%3Cg fill=\"none\" fill-rule=\"evenodd\"%3E%3Cg fill=\"%23ffffff\" fill-opacity=\"1\"%3E%3Cpath d=\"M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z\"/%3E%3C/g%3E%3C/g%3E%3C/svg%3E');"></div>
    @endif

    <div class="w-full px-4 md:px-10 lg:px-16 relative z-10 text-center">
        <div class="w-full max-w-6xl mx-auto space-y-12">
            <div class="space-y-4">
                <h2 class="text-4xl md:text-6xl font-black text-white uppercase tracking-tighter leading-tight drop-shadow-2xl">
                    {{ $d['title'] }}
                </h2>
                <p class="text-lg md:text-xl text-white/90 font-bold uppercase tracking-[0.2em]">
                    {{ $d['subtitle'] }}
                </p>
            </div>

            <div class="flex justify-center gap-4 md:gap-8" data-sale-countdown="{{ $d['end_date'] }}">
                @foreach(['Days', 'Hours', 'Minutes', 'Seconds'] as $unit)
                    <div class="bg-white/10 backdrop-blur-md border border-white/20 rounded-3xl p-4 md:p-6 min-w-[80px] md:min-w-[120px]">
                        <div class="text-3xl md:text-5xl font-black text-white mb-1" id="timer-{{ strtolower($unit) }}">00</div>
                        <div class="text-[10px] md:text-xs font-black text-white/60 uppercase tracking-widest">{{ $unit }}</div>
                    </div>
                @endforeach
            </div>

            @if($d['button_text'] && $d['button_link'])
                <div class="pt-4">
                    <a href="{{ $d['button_link'] }}" class="inline-flex items-center gap-4 px-12 py-5 bg-white text-gray-950 text-xs font-black uppercase tracking-[0.3em] rounded-full hover:bg-gray-100 transition-all shadow-2xl">
                        {{ $d['button_text'] }}
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                    </a>
                </div>
            @endif
        </div>
    </div>
</section>

<script>
    (function() {
        const el = document.querySelector('[data-sale-countdown]');
        if (!el) return;
        
        const target = new Date(el.dataset.saleCountdown).getTime();
        
        const update = () => {
            const now = new Date().getTime();
            const dist = target - now;
            
            if (dist < 0) return;
            
            const d = Math.floor(dist / (1000 * 60 * 60 * 24));
            const h = Math.floor((dist % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            const m = Math.floor((dist % (1000 * 60 * 60)) / (1000 * 60));
            const s = Math.floor((dist % (1000 * 60)) / 1000);
            
            document.getElementById('timer-days').textContent = String(d).padStart(2, '0');
            document.getElementById('timer-hours').textContent = String(h).padStart(2, '0');
            document.getElementById('timer-minutes').textContent = String(m).padStart(2, '0');
            document.getElementById('timer-seconds').textContent = String(s).padStart(2, '0');
        };
        
        update();
        setInterval(update, 1000);
    })();
</script>
