@php $d = $data; @endphp
<section class="py-24 bg-white overflow-hidden">
    <div class="w-full px-4 md:px-10 lg:px-16">
        <div class="flex flex-col {{ ($d['alignment'] ?? 'left') === 'right' ? 'lg:flex-row-reverse' : 'lg:flex-row' }} items-center gap-16 lg:gap-32">
            
            <!-- Image Section with Cinematic Overlap -->
            <div class="w-full lg:w-1/2 relative group">
                <!-- Large Decorative Background Shape -->
                <div class="absolute -top-12 -left-12 w-64 h-64 bg-[#0082C3]/5 rounded-full blur-3xl group-hover:bg-[#0082C3]/10 transition-colors duration-700"></div>
                
                <div class="relative">
                    <!-- Primary Image -->
                    <div class="relative rounded-[60px] overflow-hidden shadow-[0_50px_100px_-20px_rgba(0,0,0,0.15)] aspect-[4/5] z-10 border-[12px] border-white">
                        @if($d['image'])
                            <img src="{{ $d['image'] }}" alt="{{ $d['title'] }}" class="w-full h-full object-cover transform scale-100 group-hover:scale-110 transition-transform duration-[3000ms]">
                        @else
                            <div class="w-full h-full bg-gray-100 flex items-center justify-center">
                                <svg class="w-20 h-20 text-gray-200" fill="currentColor" viewBox="0 0 24 24"><path d="M19 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm0 16H5V5h14v14zm-5.04-6.71l-2.75 3.54-1.96-2.36L6.5 17h11l-3.54-4.71z"/></svg>
                            </div>
                        @endif
                        <div class="absolute inset-0 bg-gradient-to-tr from-gray-950/20 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-700"></div>
                    </div>

                    <!-- Overlapping Accent Element -->
                    <div class="absolute -bottom-10 -right-10 w-48 h-48 bg-[#0082C3] rounded-[40px] shadow-2xl z-20 flex flex-col items-center justify-center text-white p-6 transform group-hover:rotate-6 transition-transform duration-700">
                        <span class="text-4xl font-black mb-1">40+</span>
                        <span class="text-[10px] font-bold uppercase tracking-widest text-center leading-tight">Years of Innovation in Sports</span>
                    </div>

                    <!-- Floating Dots Decorative -->
                    <div class="absolute -top-6 -right-6 w-24 h-24 grid grid-cols-4 gap-2 opacity-20 group-hover:opacity-40 transition-opacity duration-700">
                        @for($i=0; $i<16; $i++)
                            <div class="w-1.5 h-1.5 bg-[#0082C3] rounded-full"></div>
                        @endfor
                    </div>
                </div>
            </div>

            <!-- Content Section -->
            <div class="w-full lg:w-1/2 space-y-10">
                <div class="space-y-6">
                    <div class="flex items-center gap-4">
                        <span class="h-[2px] w-12 bg-[#0082C3]"></span>
                        <span class="text-[11px] font-black uppercase tracking-[0.4em] text-[#0082C3]">Our Heritage</span>
                    </div>
                    
                    @if($d['title'])
                        <h2 class="text-5xl md:text-7xl font-black text-gray-950 leading-[0.95] tracking-tighter uppercase">
                            {{ $d['title'] }}
                        </h2>
                    @endif
                    
                    @if($d['text'])
                        <div class="text-xl text-gray-600 font-medium leading-relaxed max-w-xl">
                            {!! $d['text'] !!}
                        </div>
                    @endif
                </div>

                <div class="grid grid-cols-2 gap-8 py-4">
                    <div class="space-y-2">
                        <h4 class="text-xs font-black text-gray-400 uppercase tracking-widest">Quality Focus</h4>
                        <p class="text-sm font-bold text-gray-900 uppercase">Tested in extreme conditions by experts.</p>
                    </div>
                    <div class="space-y-2">
                        <h4 class="text-xs font-black text-gray-400 uppercase tracking-widest">Global Reach</h4>
                        <p class="text-sm font-bold text-gray-900 uppercase">Empowering millions of athletes worldwide.</p>
                    </div>
                </div>

                @if($d['button_text'] && $d['button_link'])
                    <div class="pt-6">
                        <a href="{{ $d['button_link'] }}" class="inline-flex items-center gap-5 px-12 py-5 bg-gray-950 text-white text-[11px] font-black uppercase tracking-[0.3em] rounded-full shadow-[0_20px_40px_-10px_rgba(0,0,0,0.3)] hover:bg-[#0082C3] hover:scale-105 active:scale-95 transition-all duration-500 group">
                            {{ $d['button_text'] }}
                            <svg class="w-5 h-5 group-hover:translate-x-2 transition-transform duration-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                            </svg>
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</section>
