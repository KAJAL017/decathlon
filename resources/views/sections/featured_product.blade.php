@php $p = $data; @endphp
@if($p)
<section class="py-16 bg-white overflow-hidden">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-gray-50 rounded-[50px] p-8 md:p-16 flex flex-col lg:flex-row items-center gap-12 lg:gap-20">
            <!-- Product Media -->
            <div class="w-full lg:w-1/2 relative group">
                <div class="absolute -inset-4 bg-[#0082C3] rounded-full blur-3xl opacity-5 -z-10 group-hover:opacity-10 transition-opacity"></div>
                <div class="aspect-square bg-white rounded-[40px] shadow-sm flex items-center justify-center p-8 relative overflow-hidden">
                    <img src="{{ $p->featured_image_url }}" alt="{{ $p->name }}" class="max-h-full max-w-full object-contain transform group-hover:scale-110 transition-transform duration-700">
                    
                    @if($p->on_sale)
                        <div class="absolute top-6 left-6">
                            <span class="px-6 py-2 bg-red-500 text-white text-xs font-black uppercase tracking-[0.2em] rounded-full shadow-lg shadow-red-200 animate-bounce">{{ $section->settings['sale_badge_text'] ?? 'Limited Offer' }}</span>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Product Details -->
            <div class="w-full lg:w-1/2 space-y-8">
                <div class="space-y-4">
                    <div class="flex items-center gap-3">
                        <span class="text-[10px] font-black uppercase tracking-[0.3em] px-3 py-1 bg-white border border-gray-200 rounded-full text-gray-400">{{ $section->settings['badge_text'] ?? 'Featured Item' }}</span>
                        @if($p->brand)
                            <span class="text-[10px] font-black uppercase tracking-[0.3em] text-[#0082C3]">{{ $p->brand->name }}</span>
                        @endif
                    </div>
                    <h2 class="text-4xl md:text-5xl font-black text-gray-950 uppercase tracking-tighter leading-[0.9]">{{ $p->name }}</h2>
                    <div class="flex items-center gap-6">
                        <div class="text-4xl font-black text-gray-950 tracking-tighter">₹{{ number_format($p->price) }}</div>
                        @if($p->compare_price > $p->price)
                            <div class="text-xl font-bold text-gray-400 line-through">₹{{ number_format($p->compare_price) }}</div>
                            <div class="px-3 py-1 bg-green-500 text-white text-[10px] font-black uppercase tracking-widest rounded-lg">Save {{ round((($p->compare_price - $p->price) / $p->compare_price) * 100) }}%</div>
                        @endif
                    </div>
                </div>

                <div class="text-lg text-gray-600 font-medium leading-relaxed line-clamp-3">
                    {!! $p->description !!}
                </div>

                <div class="pt-6 flex flex-col sm:flex-row items-center gap-4">
                    <a href="{{ route('product', $p->slug) }}" class="w-full sm:w-auto inline-flex items-center justify-center gap-3 px-12 py-5 bg-gray-950 text-white text-xs font-black uppercase tracking-[0.2em] rounded-2xl shadow-2xl shadow-gray-300 hover:bg-[#0082C3] hover:-translate-y-1 transition-all duration-300">
                        {{ $section->settings['cta_text'] ?? 'View Product' }}
                        <i data-lucide="arrow-right" class="w-5 h-5"></i>
                    </a>
                    
                    @if($p->variants->count() > 1)
                        <button onclick="window.QuickView.open('{{ $p->slug }}')" class="w-full sm:w-auto px-12 py-5 border-2 border-gray-200 text-gray-950 text-xs font-black uppercase tracking-[0.2em] rounded-2xl hover:border-gray-950 transition-all">
                            {{ $section->settings['secondary_cta_text'] ?? 'Quick Add' }}
                        </button>
                    @else
                        <button onclick="window.Cart.add({{ $p->id }}, {{ $p->variants->first()->id }}, 1)" class="w-full sm:w-auto px-12 py-5 border-2 border-gray-200 text-gray-950 text-xs font-black uppercase tracking-[0.2em] rounded-2xl hover:border-gray-950 transition-all">
                            {{ $section->settings['secondary_cta_text'] ?? 'Quick Add' }}
                        </button>
                    @endif
                </div>

                <div class="flex items-center gap-8 pt-4 border-t border-gray-200">
                    @php
                        $feature1Label = $section->settings['feature1_label'] ?? 'Free Delivery';
                        $feature1Icon = $section->settings['feature1_icon'] ?? 'check';
                        $feature2Label = $section->settings['feature2_label'] ?? '2 Year Warranty';
                        $feature2Icon = $section->settings['feature2_icon'] ?? 'shield';
                    @endphp
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-blue-50 flex items-center justify-center text-[#0082C3]">
                            <i data-lucide="check" class="w-5 h-5"></i>
                        </div>
                        <span class="text-[10px] font-black uppercase tracking-widest text-gray-500">{{ $feature1Label }}</span>
                    </div>
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-green-50 flex items-center justify-center text-green-600">
                            <i data-lucide="shield-check" class="w-5 h-5"></i>
                        </div>
                        <span class="text-[10px] font-black uppercase tracking-widest text-gray-500">{{ $feature2Label }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endif
