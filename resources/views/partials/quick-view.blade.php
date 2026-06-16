{{-- Quick View Modal --}}
<div id="quick-view-modal" class="fixed inset-0 z-[100000] hidden" aria-modal="true" role="dialog">
    {{-- Backdrop --}}
    <div id="quick-view-backdrop" class="absolute inset-0 bg-black/50"></div>

    {{-- Modal Content --}}
    <div class="relative flex items-center justify-center min-h-screen p-4">
        <div id="quick-view-panel" class="relative bg-white w-full max-w-4xl rounded-2xl shadow-2xl overflow-hidden will-change-transform transition duration-150 ease-out translate-y-4 opacity-0 scale-95">
            
            {{-- Close Button --}}
            <button onclick="window.QuickView.close()" class="absolute top-4 right-4 z-10 p-2 bg-white/80 rounded-full text-gray-400 hover:text-gray-900 transition-colors shadow-sm">
                <i data-lucide="x" class="w-5 h-5"></i>
            </button>

            <div id="quick-view-content" class="grid grid-cols-1 md:grid-cols-2">
                {{-- Content will be injected via JS --}}
                <div class="p-20 flex items-center justify-center col-span-2">
                    <div class="w-10 h-10 border-4 border-[#0082C3] border-t-transparent rounded-full animate-spin"></div>
                </div>
            </div>

        </div>
    </div>
</div>
