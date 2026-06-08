@php $d = $data; @endphp
<section class="w-full h-[600px] relative overflow-hidden">
    <!-- Video Background -->
    <div class="absolute inset-0 z-0">
        @if(str_contains($d['video_url'], 'youtube.com') || str_contains($d['video_url'], 'youtu.be'))
            @php 
                $videoId = '';
                if (preg_match('%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})%i', $d['video_url'], $match)) {
                    $videoId = $match[1];
                }
            @endphp
            <iframe class="w-full h-[150%] -translate-y-[15%] pointer-events-none" 
                    src="https://www.youtube.com/embed/{{ $videoId }}?autoplay=1&mute=1&controls=0&loop=1&playlist={{ $videoId }}&showinfo=0&rel=0" 
                    frameborder="0" allow="autoplay; encrypted-media" allowfullscreen></iframe>
        @else
            <video class="w-full h-full object-cover" autoplay muted loop playsinline>
                <source src="{{ $d['video_url'] }}" type="video/mp4">
            </video>
        @endif
    </div>

    <!-- Overlay -->
    <div class="absolute inset-0 bg-gray-950 z-10" style="opacity: {{ $d['overlay'] }}"></div>

    <!-- Content -->
    <div class="absolute inset-0 z-20 flex items-center justify-center text-center px-4">
        <div class="max-w-4xl space-y-6">
            @if($d['title'])
                <h2 class="text-5xl md:text-7xl font-black text-white uppercase tracking-tighter leading-[0.9] drop-shadow-2xl">
                    {{ $d['title'] }}
                </h2>
            @endif
            @if($d['subtitle'])
                <p class="text-xl md:text-2xl text-white font-bold uppercase tracking-widest drop-shadow-lg opacity-90">
                    {{ $d['subtitle'] }}
                </p>
            @endif
            @if($d['button_text'] && $d['button_link'])
                <div class="pt-8">
                    <a href="{{ $d['button_link'] }}" class="inline-flex items-center gap-4 px-12 py-5 bg-white text-gray-950 text-xs font-black uppercase tracking-[0.3em] rounded-full hover:bg-[#0082C3] hover:text-white transition-all duration-500 shadow-2xl">
                        {{ $d['button_text'] }}
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"/></svg>
                    </a>
                </div>
            @endif
        </div>
    </div>
</section>
