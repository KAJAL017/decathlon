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
                            <a href="{{ route('product.show', $product->slug) }}" class="flex items-center gap-5 group">
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
                                            <svg class="w-3 h-3 text-yellow-400" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
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
