@extends('layouts.app')

@section('title', ($product->name ?? 'Product') . ' - Decathlon')

@section('content')
<div class="w-full bg-white py-6 px-4 lg:px-8">
        
        <!-- Breadcrumbs -->
        <nav class="text-[11px] text-gray-500 mb-6 flex items-center gap-1.5 flex-wrap uppercase tracking-wider font-semibold">
            <a href="{{ route('home') }}" class="hover:text-gray-900 transition-colors">Home</a>
            <span>/</span>
            <a href="{{ route('shop') }}" class="hover:text-gray-900 transition-colors">All Sports</a>
            @if($product->category)
                <span>/</span>
                @if($product->category->parent)
                    <a href="{{ route('shop') }}?category={{ $product->category->parent->slug }}" class="hover:text-gray-900 transition-colors">{{ $product->category->parent->name }}</a>
                    <span>/</span>
                @endif
                <a href="{{ route('shop') }}?category={{ $product->category->slug }}" class="hover:text-gray-900 transition-colors">{{ $product->category->name }}</a>
            @endif
            <span>/</span>
            <span class="text-gray-900 font-bold">{{ $product->name }}</span>
        </nav>

        <!-- Main Product Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start mb-16">
            
            <!-- Left Column: Product Images (Gallery) -->
            <div class="lg:col-span-7 space-y-6">
                <!-- Mobile Carousel (Horizontal Scroll) -->
                <div class="flex lg:hidden overflow-x-auto snap-x snap-mandatory scrollbar-hide gap-4 pb-2" id="mobile-gallery">
                    @foreach($product->images as $image)
                    <div class="flex-shrink-0 w-[85vw] snap-center aspect-[4/3] bg-gray-50 border border-gray-100 rounded-xl overflow-hidden flex items-center justify-center">
                        <img src="{{ $image->image_url }}" alt="{{ $product->name }}" class="w-full h-full object-cover">
                    </div>
                    @endforeach
                </div>
                
                <!-- Desktop Vertical Stack (Classic Decathlon layout) -->
                <div class="hidden lg:flex flex-col gap-6" id="desktop-gallery">
                    @foreach($product->images as $image)
                    <div class="bg-gray-50 border border-gray-100 rounded-xl overflow-hidden aspect-[4/3] max-h-[600px] flex items-center justify-center">
                        <img src="{{ $image->image_url }}" alt="{{ $product->name }}" class="w-full h-full object-cover">
                    </div>
                    @endforeach
                </div>
            </div>

            <!-- Right Column: Sticky Product Info & Actions -->
            <div class="lg:col-span-5 lg:sticky lg:top-24 space-y-6 bg-white p-2 lg:p-4 rounded-xl border border-gray-100/50">
                
                <!-- Brand & Sharing -->
                <div class="flex items-start justify-between">
                    <div class="space-y-1.5">
                        <div class="flex items-center gap-2">
                            <span class="text-[13px] font-black text-[#0082C3] tracking-widest uppercase">{{ $product->brand?->name ?? 'DECATHLON' }}</span>
                            @if($product->is_featured)
                            <span class="bg-emerald-50 text-emerald-700 text-[10px] font-black px-2 py-0.5 rounded border border-emerald-200/80 uppercase tracking-wide flex items-center gap-1">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path d="M18 18.72a3 3 0 000-5.44 3 3 0 000 5.44zM6 12a3 3 0 100-6 3 3 0 000 6zm0 0a3 3 0 100 6 3 3 0 000-6zm12 1.28L6.72 10.4m11.28.32L6.72 13.6"/></svg>
                                Premium
                            </span>
                            @endif
                        </div>
                        <h1 class="text-xl font-bold text-gray-900 leading-tight">{{ $product->name }}</h1>
                    </div>
                    <button class="p-2.5 border border-gray-200 rounded-full hover:bg-gray-50 transition-colors text-gray-500 hover:text-gray-900 relative group" title="Share Product">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M7.217 10.907a2.25 2.25 0 100 2.186m0-2.186l5.302-3.111m0 0a2.25 2.25 0 103.035-3.035m-3.035 3.035L10.36 12.92m5.302 3.11a2.25 2.25 0 103.035 3.036m-3.035-3.036L10.36 13.5" />
                        </svg>
                    </button>
                </div>

                <!-- Ratings Summary -->
                <div class="flex items-center gap-2">
                    <div class="flex items-center text-yellow-500 text-sm font-semibold">
                        ★★★★★
                        <span class="text-gray-800 font-bold text-xs ml-1">{{ number_format($product->average_rating ?? 4.5, 1) }}</span>
                    </div>
                    <span class="text-gray-300">|</span>
                    <a href="#reviews-section" class="text-[12px] text-gray-500 hover:text-[#0082C3] transition-colors underline font-medium">{{ $product->reviews_count ?? 0 }} reviews</a>
                </div>

                <!-- Price Block -->
                @php
                    $variant = $product->variants->first();
                    $price = $variant?->price ?? 0;
                    $comparePrice = $variant?->compare_price;
                    $discount = ($comparePrice && $comparePrice > $price) ? round((($comparePrice - $price) / $comparePrice) * 100) : 0;
                @endphp
                <div class="pt-2 border-t border-gray-100 flex items-baseline gap-3">
                    <span class="text-3xl font-black text-gray-950">₹{{ number_format($price) }}</span>
                    @if($comparePrice && $comparePrice > $price)
                        <span class="text-sm text-gray-400 font-medium">MRP: <span class="line-through">₹{{ number_format($comparePrice) }}</span></span>
                        <span class="text-xs font-extrabold text-emerald-600 bg-emerald-50 px-2 py-0.5 rounded border border-emerald-200/50">{{ $discount }}% OFF</span>
                    @endif
                </div>

                <!-- Swatches: Colors (If multiple variants) -->
                @php
                    $colors = $product->variants->pluck('color')->unique()->filter();
                    $sizes = $product->variants->pluck('size')->unique()->filter();
                @endphp

                @if($colors->count() > 0)
                <div class="space-y-3">
                    <div class="flex justify-between items-center text-xs text-gray-600">
                        <span class="font-bold uppercase tracking-wider">Colour</span>
                        <span id="selected-color-label" class="font-bold text-gray-900 uppercase">{{ $colors->first() }}</span>
                    </div>
                    <div class="flex flex-wrap gap-2.5">
                        @foreach($colors as $color)
                        <button class="color-swatch w-12 h-12 rounded-lg border-2 overflow-hidden flex items-center justify-center p-0.5 transition-all duration-200 {{ $loop->first ? 'border-gray-900 ring-2 ring-gray-200' : 'border-gray-200 hover:border-gray-400' }}" 
                                data-color="{{ $color }}">
                            <span class="w-full h-full rounded-md block" style="background-color: {{ $color }};"></span>
                        </button>
                        @endforeach
                    </div>
                </div>
                @endif

                <!-- Swatches: Sizes -->
                @if($product->variants->count() > 0)
                <div class="space-y-3 pt-2">
                    <div class="flex justify-between items-center text-xs text-gray-600">
                        <span class="font-bold uppercase tracking-wider">Select Size</span>
                        <span id="selected-size-label" class="font-bold text-gray-900 uppercase">
                            {{ $product->variants->first()->size ?? $product->variants->first()->variant_name }}
                        </span>
                    </div>
                    <div class="flex flex-wrap gap-2">
                        @foreach($product->variants as $v)
                        <button class="size-btn border-2 {{ $loop->first ? 'border-black bg-black text-white' : 'border-gray-200 text-gray-800 hover:border-black' }} text-xs font-bold px-6 min-w-[3.5rem] h-10 rounded flex items-center justify-center transition-all duration-200"
                                data-variant-id="{{ $v->id }}"
                                data-size="{{ $v->size ?? $v->variant_name }}">
                            <span class="relative z-10">{{ $v->size ?? $v->variant_name }}</span>
                        </button>
                        @endforeach
                    </div>
                </div>
                @endif

                <!-- Quantity & Actions -->
                <div class="space-y-3 pt-2">
                    <span class="text-xs font-bold text-gray-600 uppercase tracking-wider block">Quantity</span>
                    <div class="flex items-center gap-3">
                        <div class="flex items-center border border-gray-300 rounded-lg w-28 overflow-hidden shadow-sm flex-shrink-0">
                            <button class="qty-btn w-9 h-9 flex items-center justify-center hover:bg-gray-50 text-gray-600 transition-colors text-sm" data-delta="-1">—</button>
                            <input type="number" id="quantity-input" value="1" min="1" max="10" class="w-10 h-9 text-center text-xs font-bold text-gray-900 focus:outline-none bg-white">
                            <button class="qty-btn w-9 h-9 flex items-center justify-center hover:bg-gray-50 text-gray-600 transition-colors text-sm" data-delta="1">+</button>
                        </div>
                        
                        <button class="add-to-cart-btn flex-1 bg-[#183a9e] hover:bg-[#0c246b] text-white text-[11px] font-black h-9 px-4 rounded-lg shadow-sm transition-all duration-200 uppercase tracking-wider flex items-center justify-center gap-2 group"
                                data-product-id="{{ $product->id }}"
                                data-variant-id="{{ $product->variants->first()->id ?? '' }}">
                            <svg class="w-4 h-4 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 00-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 00-16.536-1.84M7.5 14.25L5.106 5.272M6 20.25a.75.75 0 11-1.5 0 .75.75 0 011.5 0zm12.75 0a.75.75 0 11-1.5 0 .75.75 0 011.5 0z"/></svg>
                            Add to cart
                        </button>

                        <button class="w-9 h-9 border border-gray-300 hover:border-black text-gray-600 hover:text-red-500 rounded-lg transition-all duration-200 flex items-center justify-center bg-white shadow-sm flex-shrink-0" title="Add to Wishlist">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12z" />
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- Warranty & Return Badges -->
                <div class="grid grid-cols-2 gap-4 py-4 border-t border-b border-gray-100 text-xs font-semibold text-gray-700">
                    <div class="flex items-center gap-2">
                        <div class="bg-gray-100 p-2 rounded-full text-gray-800">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path d="M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.749c0 5.592 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.57-.598-3.75h-.152c-3.196 0-6.1-1.248-8.25-3.285z"/></svg>
                        </div>
                        <span>2-year warranty</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <div class="bg-gray-100 p-2 rounded-full text-gray-800">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path d="M19.5 12c0-1.232-.046-2.453-.138-3.662a4.006 4.006 0 00-3.7-3.7 48.678 48.678 0 00-7.324 0 4.006 4.006 0 00-3.7 3.7c-.017.22-.032.441-.046.662M19.5 12l3-3m-3 3l-3-3M3 12a12.905 12.905 0 00.9 4.7l.3-.9M3 12l-3 3m3-3l3 3M19.5 12a12.905 12.905 0 01-.9 4.7M9.54 18.5A11.95 11.95 0 0112 19.5"/></svg>
                        </div>
                        <span>30 days return</span>
                    </div>
                </div>

                <!-- Delivery Pincode Checker -->
                <div class="space-y-3">
                    <span class="text-xs font-bold text-gray-600 uppercase tracking-wider block">Delivery & Services</span>
                    <div class="flex border border-gray-300 focus-within:border-gray-800 rounded-lg overflow-hidden transition-colors shadow-sm">
                        <div class="pl-3 flex items-center text-gray-400">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z"/><path d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25s-7.5-4.108-7.5-11.25gA7.5 7.5 0 1119.5 10.5z"/></svg>
                        </div>
                        <input type="text" id="pincode-input" placeholder="Enter Pincode" class="w-full text-xs font-bold text-gray-900 px-2 py-3.5 focus:outline-none">
                        <button id="pincode-check-btn" class="bg-gray-150 hover:bg-gray-200 border-l border-gray-300 px-4 text-xs font-extrabold text-[#0082C3] uppercase tracking-wider transition-colors">Check</button>
                    </div>
                    
                    <!-- Delivery Info Details -->
                    <div class="space-y-2.5 pt-1" id="delivery-info">
                        <div class="flex items-start gap-2.5 text-xs text-gray-700">
                            <span class="text-emerald-600 mt-0.5">●</span>
                            <div>
                                <span class="font-bold text-gray-900">Standard delivery by tomorrow</span>
                                <p class="text-[10px] text-gray-500 font-medium">Order within <span class="text-orange-500 font-semibold">2 hrs 45 mins</span></p>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

        </div>

        <!-- Dynamic Page Builder Sections -->
        @if($product->sections && $product->sections->count() > 0)
            <div class="space-y-12 mb-16">
            @foreach($product->sections as $section)
                <div class="dynamic-section section-{{ $section->type }} pt-8 border-t border-gray-100" style="background-color: {{ $section->settings['bg_color'] ?? 'transparent' }}">
                    
                    @if($section->title)
                    <div class="mb-6">
                        <h2 class="text-xl font-black text-gray-950 uppercase tracking-tight">{{ $section->title }}</h2>
                        @if($section->subtitle)
                        <p class="text-sm text-gray-500 mt-1">{{ $section->subtitle }}</p>
                        @endif
                    </div>
                    @endif

                    @if($section->type === 'features')
                        <!-- Features rendering -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                            @foreach($product->attributeValues->where('attribute.code', 'benefit') as $benefit)
                            <div class="bg-gray-50/80 border border-gray-100 rounded-xl p-5 flex flex-col items-start gap-4">
                                <div class="bg-white p-3 rounded-lg shadow-sm text-gray-800 border border-gray-100">
                                    <svg class="w-6 h-6 text-[#0082C3]" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                        <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                </div>
                                <div>
                                    <h4 class="text-xs font-black text-gray-900 uppercase tracking-wider mb-1">{{ $benefit->attributeValue?->value ?? 'Feature' }}</h4>
                                </div>
                            </div>
                            @endforeach
                        </div>

                    @elseif($section->type === 'specifications')
                        <!-- Specs rendering -->
                        <div class="bg-white border border-gray-250 rounded-xl overflow-hidden shadow-sm">
                            <div class="p-6 text-xs text-gray-700 space-y-3 font-medium leading-relaxed">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-y-3 max-w-3xl">
                                    @if($product->weight)
                                    <div class="grid grid-cols-2 border-b border-gray-100 pb-2"><span class="font-bold text-gray-900 uppercase">Weight:</span><span class="text-gray-600">{{ $product->weight }} kg</span></div>
                                    @endif
                                    @if($product->length)
                                    <div class="grid grid-cols-2 border-b border-gray-100 pb-2"><span class="font-bold text-gray-900 uppercase">Dimensions:</span><span class="text-gray-600">{{ $product->length }}x{{ $product->width }}x{{ $product->height }} cm</span></div>
                                    @endif
                                    @foreach($product->attributeValues as $attrValue)
                                    <div class="grid grid-cols-2 border-b border-gray-100 pb-2"><span class="font-bold text-gray-900 uppercase">{{ $attrValue->attribute->name }}:</span><span class="text-gray-600">{{ $attrValue->attributeValue?->value ?? $attrValue->value }}</span></div>
                                    @endforeach
                                </div>
                            </div>
                        </div>

                    @elseif($section->type === 'banner')
                        <!-- Banner rendering -->
                        @if(!empty($section->content['image_url']))
                        <a href="{{ $section->content['link_url'] ?? '#' }}" class="block w-full overflow-hidden rounded-xl">
                            <img src="{{ $section->content['image_url'] }}" alt="{{ $section->title }}" class="w-full h-auto object-cover hover:scale-105 transition-transform duration-500">
                        </a>
                        @endif

                    @elseif($section->type === 'custom_html')
                        <!-- Custom HTML rendering -->
                        <div class="prose max-w-none text-sm text-gray-700">
                            {!! $section->content['html'] ?? '' !!}
                        </div>

                    @elseif($section->type === 'downloads')
                        <!-- Downloads rendering -->
                        @if($product->downloads && $product->downloads->count() > 0)
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            @foreach($product->downloads as $download)
                            <a href="{{ $download->file_path }}" target="_blank" class="flex items-center gap-4 p-4 border border-gray-200 rounded-xl hover:border-[#0082C3] hover:bg-blue-50 transition-colors group">
                                <div class="bg-gray-100 p-3 rounded-lg group-hover:bg-blue-100 group-hover:text-[#0082C3]">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                                </div>
                                <div>
                                    <h4 class="font-bold text-gray-900 text-sm">{{ $download->title }}</h4>
                                    <p class="text-xs text-gray-500">{{ strtoupper($download->file_type) }} • {{ $download->file_size }}</p>
                                </div>
                            </a>
                            @endforeach
                        </div>
                        @else
                        <p class="text-sm text-gray-500 italic">No downloads available.</p>
                        @endif
                    @endif

                </div>
            @endforeach
            </div>
        @else
            <!-- Fallback Default Legacy Accordions if no dynamic sections are built -->
            <!-- Accordions -->
            <div class="border-t border-gray-100 pt-6 mb-16 space-y-3 mt-12">
                <!-- Accordion 1: Product details -->
                <div class="border border-gray-200/80 rounded-xl overflow-hidden bg-white shadow-sm">
                    <button class="accordion-trigger w-full flex items-center justify-between p-5 text-left font-bold text-gray-900 text-sm tracking-wide uppercase bg-gray-50/50 hover:bg-gray-50 transition-colors" data-target="detail-content" data-icon="detail-icon">
                        <span>Product details</span>
                        <svg id="detail-icon" class="w-4 h-4 text-gray-500 transition-transform duration-300 transform rotate-90" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path d="M9 5l7 7-7 7"/></svg>
                    </button>
                    <div id="detail-content" class="transition-all duration-300 overflow-hidden max-h-[800px]">
                        <div class="p-6 text-xs text-gray-700 space-y-3 leading-relaxed border-t border-gray-100 font-medium">
                            {!! $product->description !!}
                        </div>
                    </div>
                </div>

                <!-- Accordion 2: Product specifications -->
                <div class="border border-gray-250 rounded-xl overflow-hidden bg-white shadow-sm">
                    <button class="accordion-trigger w-full flex items-center justify-between p-5 text-left font-bold text-gray-900 text-sm tracking-wide uppercase bg-gray-50/50 hover:bg-gray-50 transition-colors" data-target="specs-content" data-icon="specs-icon">
                        <span>Product specifications</span>
                        <svg id="specs-icon" class="w-4 h-4 text-gray-500 transition-transform duration-300 transform" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path d="M9 5l7 7-7 7"/></svg>
                    </button>
                    <div id="specs-content" class="transition-all duration-300 overflow-hidden max-h-0">
                        <div class="p-6 text-xs text-gray-700 space-y-3 border-t border-gray-100 font-medium leading-relaxed">
                            <div class="grid grid-cols-2 gap-y-3 max-md">
                                @if($product->weight)
                                <span class="font-bold text-gray-900 uppercase">Weight:</span>
                                <span class="text-gray-600">{{ $product->weight }} kg</span>
                                @endif
                                
                                @if($product->length)
                                <span class="font-bold text-gray-900 uppercase">Dimensions:</span>
                                <span class="text-gray-600">{{ $product->length }}x{{ $product->width }}x{{ $product->height }} cm</span>
                                @endif

                                @foreach($product->attributeValues->take(5) as $attrValue)
                                <span class="font-bold text-gray-900 uppercase">{{ $attrValue->attribute->name }}:</span>
                                <span class="text-gray-600">{{ $attrValue->attributeValue?->value ?? $attrValue->value }}</span>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>

                <!-- FAQs -->
                @if($product->faqs->count() > 0)
                <div class="border border-gray-250 rounded-xl overflow-hidden bg-white shadow-sm">
                    <button class="accordion-trigger w-full flex items-center justify-between p-5 text-left font-bold text-gray-900 text-sm tracking-wide uppercase bg-gray-50/50 hover:bg-gray-50 transition-colors" data-target="faq-content" data-icon="faq-icon">
                        <span>FAQs</span>
                        <svg id="faq-icon" class="w-4 h-4 text-gray-500 transition-transform duration-300 transform" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path d="M9 5l7 7-7 7"/></svg>
                    </button>
                    <div id="faq-content" class="transition-all duration-300 overflow-hidden max-h-0">
                        <div class="p-6 text-xs text-gray-700 space-y-4 border-t border-gray-100 font-medium leading-relaxed">
                            @foreach($product->faqs as $faq)
                            <div>
                                <p class="font-bold text-gray-900">{{ $faq->question }}</p>
                                <p class="text-gray-600">{{ $faq->answer }}</p>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                @endif
            </div>
        @endif

        <!-- Similar Products Slider -->
        @if($relatedProducts->count() > 0)
        <div class="mb-12">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-base font-black text-gray-950 uppercase tracking-wide">Similar Products</h3>
                <div class="flex gap-2">
                    <button class="slider-scroll p-2 border border-gray-200 rounded-full hover:bg-gray-50 transition-colors text-gray-600" data-slider="similar-slider" data-direction="left">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path d="M15.75 19.5L8.25 12l7.5-7.5"/></svg>
                    </button>
                    <button class="slider-scroll p-2 border border-gray-200 rounded-full hover:bg-gray-50 transition-colors text-gray-600" data-slider="similar-slider" data-direction="right">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path d="M8.25 4.5l7.5 7.5-7.5 7.5"/></svg>
                    </button>
                </div>
            </div>

            <div id="similar-slider" class="flex gap-4 overflow-x-auto scroll-smooth snap-x snap-mandatory scrollbar-hide pb-2">
                @foreach($relatedProducts as $item)
                    @include('partials.product-card', ['product' => $item])
                @endforeach
            </div>
        </div>
        @endif

        <!-- Frequently Bought Together Slider -->
        @if($boughtTogether->count() > 0)
        <div class="mb-16">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-base font-black text-gray-950 uppercase tracking-wide">Frequently Bought Together</h3>
                <div class="flex gap-2">
                    <button class="slider-scroll p-2 border border-gray-200 rounded-full hover:bg-gray-50 transition-colors text-gray-600" data-slider="bought-slider" data-direction="left">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path d="M15.75 19.5L8.25 12l7.5-7.5"/></svg>
                    </button>
                    <button class="slider-scroll p-2 border border-gray-200 rounded-full hover:bg-gray-50 transition-colors text-gray-600" data-slider="bought-slider" data-direction="right">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path d="M8.25 4.5l7.5 7.5-7.5 7.5"/></svg>
                    </button>
                </div>
            </div>

            <div id="bought-slider" class="flex gap-4 overflow-x-auto scroll-smooth snap-x snap-mandatory scrollbar-hide pb-2">
                @foreach($boughtTogether as $item)
                    @include('partials.product-card', ['product' => $item])
                @endforeach
            </div>
        </div>
        @endif

        <!-- Reviews Section Placeholder (Requires Reviews Module) -->
        <div id="reviews-section" class="border-t border-gray-150 pt-12 pb-16">
            <h2 class="text-xl font-black text-gray-950 uppercase tracking-tight mb-8">Reviews</h2>
            <p class="text-gray-500 italic">No reviews yet for this product. Be the first to review!</p>
        </div>

</div>

@push('scripts')
<script>
    window.productData = {
        id: {{ $product->id }},
        name: "{{ $product->name }}",
        brand: "{{ $product->brand?->name ?? 'DECATHLON' }}",
        slug: "{{ $product->slug }}",
        price: {{ $product->variants->first()?->price ?? 0 }},
        variant_id: {{ $product->variants->first()?->id ?? 'null' }},
        image: "{{ $product->images->first()?->image_url ?? '' }}",
        rating: {{ $product->average_rating ?? 0 }}
    };
</script>
@endpush
@endsection
