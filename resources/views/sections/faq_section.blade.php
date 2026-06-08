@php $faqs = $data; @endphp
<section class="py-16 bg-gray-50">
    <div class="w-full px-4 md:px-10 lg:px-16 max-w-6xl mx-auto">
        @if($section->title || $section->subtitle)
            <div class="text-center mb-12">
                @if($section->title)
                    <h2 class="text-3xl font-black text-gray-950 uppercase tracking-tight">{{ $section->title }}</h2>
                @endif
                @if($section->subtitle)
                    <p class="mt-2 text-sm text-gray-500 font-bold uppercase tracking-widest">{{ $section->subtitle }}</p>
                @endif
            </div>
        @endif

        <div class="space-y-4">
            @foreach($faqs as $index => $faq)
                <div class="bg-white rounded-[25px] overflow-hidden border border-gray-100 shadow-sm transition-all duration-300 hover:shadow-md">
                    <button onclick="toggleFaq({{ $section->id }}, {{ $index }})" 
                            class="w-full px-8 py-6 flex items-center justify-between text-left group">
                        <span class="text-base font-black text-gray-950 uppercase tracking-tight">{{ $faq['question'] }}</span>
                        <div class="w-8 h-8 rounded-full bg-gray-50 flex items-center justify-center text-gray-400 group-hover:bg-[#0082C3] group-hover:text-white transition-all transform" id="faq-icon-{{ $section->id }}-{{ $index }}">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 4v16m8-8H4"/></svg>
                        </div>
                    </button>
                    <div class="hidden px-8 pb-8 text-gray-600 font-medium leading-relaxed" id="faq-content-{{ $section->id }}-{{ $index }}">
                        {!! $faq['answer'] !!}
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>

<script>
    function toggleFaq(sectionId, index) {
        const content = document.getElementById(`faq-content-${sectionId}-${index}`);
        const icon = document.getElementById(`faq-icon-${sectionId}-${index}`);
        
        if (content.classList.contains('hidden')) {
            content.classList.remove('hidden');
            icon.style.transform = 'rotate(45deg)';
        } else {
            content.classList.add('hidden');
            icon.style.transform = 'rotate(0deg)';
        }
    }
</script>
