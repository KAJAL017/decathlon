@if(isset($data) && $data->count() > 0)
<section class="py-12 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        @if($section->title || $section->subtitle)
            <div class="mb-8">
                @if($section->title)
                    <h2 class="text-2xl font-bold text-gray-900 tracking-tight uppercase">{{ $section->title }}</h2>
                @endif
                @if($section->subtitle)
                    <p class="mt-1 text-sm text-gray-500 font-medium">{{ $section->subtitle }}</p>
                @endif
            </div>
        @endif

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($data as $promotion)
                <div class="group relative bg-gray-50 rounded-2xl overflow-hidden border border-gray-100 hover:shadow-xl transition-all duration-300">
                    <div class="aspect-w-16 aspect-h-9 relative">
                        @if($promotion->banner_url)
                            <img src="{{ $promotion->banner_url }}" alt="{{ $promotion->name }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                        @else
                            <div class="w-full h-full bg-[#0082C3] flex items-center justify-center text-white">
                                <span class="text-4xl font-black opacity-20 uppercase tracking-tighter">{{ $promotion->name }}</span>
                            </div>
                        @endif
                        
                        @if($promotion->badge_text)
                            <div class="absolute top-4 left-4">
                                <span class="px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-widest text-white" style="background-color: {{ $promotion->badge_color ?? '#0082C3' }}">
                                    {{ $promotion->badge_text }}
                                </span>
                            </div>
                        @endif

                        @if($promotion->show_countdown && $promotion->ends_at)
                            <div class="absolute bottom-4 left-4 right-4 bg-white/90 backdrop-blur-sm rounded-xl p-3 flex items-center justify-between shadow-sm">
                                <div class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Ends In:</div>
                                <div class="flex gap-2 text-xs font-bold text-gray-900" data-countdown="{{ $promotion->ends_at->toIso8601String() }}">
                                    <span class="days">00d</span>
                                    <span class="hours">00h</span>
                                    <span class="minutes">00m</span>
                                </div>
                            </div>
                        @endif
                    </div>

                    <div class="p-6">
                        <div class="flex items-start justify-between gap-4">
                            <div>
                                <h3 class="text-lg font-bold text-gray-900 leading-tight">{{ $promotion->name }}</h3>
                                <p class="text-sm text-gray-500 mt-1 line-clamp-2">{{ $promotion->description }}</p>
                            </div>
                            <div class="text-right flex-shrink-0">
                                <span class="block text-xl font-black text-[#0082C3] leading-none">{{ $promotion->discount_label }}</span>
                            </div>
                        </div>

                        <div class="mt-6">
                            <a href="{{ route('shop', ['promotion' => $promotion->slug]) }}" class="inline-flex items-center gap-2 px-6 py-3 bg-gray-950 text-white text-xs font-black uppercase tracking-[0.2em] rounded-xl hover:bg-[#0082C3] transition-all">
                                Shop Now
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>

<script>
    if (typeof initPromotionCountdowns === 'undefined') {
        function initPromotionCountdowns() {
            const counters = document.querySelectorAll('[data-countdown]');
            counters.forEach(counter => {
                const targetDate = new Date(counter.dataset.countdown).getTime();
                
                const update = () => {
                    const now = new Date().getTime();
                    const distance = targetDate - now;
                    
                    if (distance < 0) {
                        counter.innerHTML = "EXPIRED";
                        return;
                    }
                    
                    const days = Math.floor(distance / (1000 * 60 * 60 * 24));
                    const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                    const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                    
                    const dEl = counter.querySelector('.days');
                    const hEl = counter.querySelector('.hours');
                    const mEl = counter.querySelector('.minutes');
                    
                    if (dEl) dEl.textContent = String(days).padStart(2, '0') + 'd';
                    if (hEl) hEl.textContent = String(hours).padStart(2, '0') + 'h';
                    if (mEl) mEl.textContent = String(minutes).padStart(2, '0') + 'm';
                };
                
                update();
                setInterval(update, 60000);
            });
        }
        initPromotionCountdowns();
        var initPromotionCountdowns = true;
    }
</script>
@endif
