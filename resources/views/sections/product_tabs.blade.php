<section class="py-16 bg-white" x-data="{ activeTab: 'featured' }">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col md:flex-row md:items-center justify-between mb-12 gap-6">
            <div>
                <h2 class="text-3xl font-black text-gray-950 uppercase tracking-tighter">{{ $section->title ?? 'Our Collections' }}</h2>
                @if($section->subtitle)
                    <p class="text-sm text-gray-500 font-bold uppercase tracking-widest mt-2">{{ $section->subtitle }}</p>
                @endif
            </div>

            <!-- Tab Switcher -->
            <div class="inline-flex bg-gray-50 p-1.5 rounded-[20px] self-start">
                <button @click="activeTab = 'featured'" :class="activeTab === 'featured' ? 'bg-white text-gray-950 shadow-sm' : 'text-gray-400 hover:text-gray-600'" class="px-6 py-2.5 rounded-[15px] text-[10px] font-black uppercase tracking-widest transition-all">
                    {{ $data['tab_labels']['featured'] ?? 'Featured' }}
                </button>
                <button @click="activeTab = 'best_seller'" :class="activeTab === 'best_seller' ? 'bg-white text-gray-950 shadow-sm' : 'text-gray-400 hover:text-gray-600'" class="px-6 py-2.5 rounded-[15px] text-[10px] font-black uppercase tracking-widest transition-all">
                    {{ $data['tab_labels']['best_seller'] ?? 'Best Sellers' }}
                </button>
                <button @click="activeTab = 'latest'" :class="activeTab === 'latest' ? 'bg-white text-gray-950 shadow-sm' : 'text-gray-400 hover:text-gray-600'" class="px-6 py-2.5 rounded-[15px] text-[10px] font-black uppercase tracking-widest transition-all">
                    {{ $data['tab_labels']['latest'] ?? 'New Arrivals' }}
                </button>
                <button @click="activeTab = 'trending'" :class="activeTab === 'trending' ? 'bg-white text-gray-950 shadow-sm' : 'text-gray-400 hover:text-gray-600'" class="px-6 py-2.5 rounded-[15px] text-[10px] font-black uppercase tracking-widest transition-all">
                    {{ $data['tab_labels']['trending'] ?? 'Trending' }}
                </button>
            </div>
        </div>

        <!-- Tab Content -->
        @foreach(['featured', 'best_seller', 'latest', 'trending'] as $tabKey)
            <div x-show="activeTab === '{{ $tabKey }}'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0" class="grid grid-cols-2 md:grid-cols-4 gap-6">
                @foreach($data['tabs'][$tabKey] as $product)
                    @include('partials.product-card', ['product' => $product])
                @endforeach
            </div>
        @endforeach
    </div>
</section>
