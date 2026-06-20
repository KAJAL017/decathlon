<section class="py-16 bg-white border-t border-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-12">
            @foreach($data['columns'] as $column)
                <div class="space-y-8">
                    <div class="flex items-center justify-between border-b border-gray-100 pb-4">
                        <h3 class="text-base font-black text-gray-950 uppercase tracking-widest">{{ $column['title'] }}</h3>
                        <a href="{{ route('shop') }}" class="text-[10px] font-black text-[#0082C3] uppercase tracking-widest hover:underline">View All</a>
                    </div>

                    <div class="space-y-6">
                        @foreach($column['products'] as $product)
                            <a href="{{ route('product', $product->slug) }}" class="flex items-center gap-5 group">
                                <div class="w-20 h-20 bg-gray-50 rounded-2xl overflow-hidden flex-shrink-0">
                                    <img src="{{ $product->featured_image_url }}" alt="{{ $product->name }}" class="w-full h-full object-contain group-hover:scale-110 transition-transform duration-500">
                                </div>
                                <div class="flex-1">
                                    <h4 class="text-xs font-bold text-gray-950 uppercase line-clamp-1 group-hover:text-[#0082C3] transition-colors">{{ $product->name }}</h4>
                                    <div class="mt-1 flex items-center gap-3">
                                        <span class="text-sm font-black text-gray-950 tracking-tighter">₹{{ number_format($product->price) }}</span>
                                        @if($product->compare_price > $product->price)
                                            <span class="text-xs font-bold text-gray-400 line-through">₹{{ number_format($product->compare_price) }}</span>
                                        @endif
                                    </div>
                                    @if($product->rating)
                                        <div class="mt-1 flex items-center gap-1">
                                            <i data-lucide="star" class="w-3 h-3 text-yellow-400"></i>
                                            <span class="text-[9px] font-black text-gray-400 uppercase tracking-tighter">{{ $product->rating }}</span>
                                        </div>
                                    @endif
                                </div>
                            </a>
                        @endforeach
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>
