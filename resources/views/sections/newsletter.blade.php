@php $d = $data; @endphp
<section class="py-16 bg-gray-950 text-white relative overflow-hidden">
    <!-- Decorative background elements -->
    <div class="absolute top-0 right-0 w-96 h-96 bg-[#0082C3] rounded-full blur-[150px] opacity-20 -translate-y-1/2 translate-x-1/2"></div>
    <div class="absolute bottom-0 left-0 w-96 h-96 bg-blue-600 rounded-full blur-[150px] opacity-10 translate-y-1/2 -translate-x-1/2"></div>

    <div class="w-full px-4 md:px-10 lg:px-16 relative z-10">
        <div class="bg-gray-900/50 backdrop-blur-xl border border-white/10 rounded-[40px] p-8 md:p-20 flex flex-col md:flex-row items-center gap-12">
            <div class="flex-1 space-y-6 text-center md:text-left">
                <h2 class="text-4xl md:text-5xl font-black uppercase tracking-tight leading-tight">{{ $d['title'] }}</h2>
                <p class="text-lg text-white/60 font-medium">{{ $d['subtitle'] }}</p>
                
                <form action="{{ route('newsletter.subscribe') }}" method="POST" class="max-w-md mx-auto md:mx-0">
                    @csrf
                    <div class="relative group">
                        <input type="email" name="email" required placeholder="{{ $d['placeholder'] }}" 
                               class="w-full bg-white/5 border border-white/10 rounded-2xl px-6 py-5 text-white placeholder-white/30 focus:outline-none focus:ring-2 focus:ring-[#0082C3] focus:border-transparent transition-all">
                        <button type="submit" class="absolute right-2 top-2 bottom-2 px-8 bg-[#0082C3] text-white text-xs font-black uppercase tracking-widest rounded-xl hover:bg-blue-600 transition-all shadow-lg shadow-blue-500/20">
                            {{ $d['button_text'] }}
                        </button>
                    </div>
                    <p class="mt-4 text-[11px] text-white/40 font-medium uppercase tracking-widest">No spam, only pure sports energy.</p>
                </form>
            </div>

            <div class="hidden lg:block w-72 h-72 relative">
                <div class="absolute inset-0 bg-[#0082C3] rounded-full blur-3xl opacity-20 animate-pulse"></div>
                <svg class="w-full h-full text-[#0082C3] opacity-30" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M20 4H4c-1.1 0-1.99.9-1.99 2L2 18c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 14H4V8l8 5 8-5v10zm-8-7L4 6h16l-8 5z"/>
                </svg>
            </div>
        </div>
    </div>
</section>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
    const newsletterForm = document.querySelector('form[action="{{ route('newsletter.subscribe') }}"]');
    if (newsletterForm) {
        newsletterForm.addEventListener('submit', async (e) => {
            e.preventDefault();
            const form = e.target;
            const emailInput = form.querySelector('input[name="email"]');
            const submitBtn = form.querySelector('button[type="submit"]');
            const originalBtnText = submitBtn.innerHTML;

            // Loading state
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<span class="spinner"></span>';

            try {
                const response = await fetch(form.action, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ email: emailInput.value })
                });

                const data = await response.json();

                if (data.success) {
                    Toastify({
                        text: "✓ " + data.message,
                        duration: 5000,
                        gravity: "bottom",
                        position: "center",
                        stopOnFocus: true,
                        style: {
                            background: "#10B981",
                            color: "white",
                            boxShadow: "0 10px 15px -3px rgba(16, 185, 129, 0.2)"
                        }
                    }).showToast();
                    emailInput.value = '';
                } else {
                    Toastify({
                        text: "✕ " + (data.message || 'Something went wrong.'),
                        duration: 5000,
                        gravity: "bottom",
                        position: "center",
                        stopOnFocus: true,
                        style: {
                            background: "#EF4444",
                            color: "white",
                            boxShadow: "0 10px 15px -3px rgba(239, 68, 68, 0.2)"
                        }
                    }).showToast();
                }
            } catch (error) {
                Toastify({
                    text: "✕ Connection error. Please try again.",
                    duration: 5000,
                    gravity: "bottom",
                    position: "center",
                    stopOnFocus: true,
                    style: {
                        background: "#EF4444",
                        color: "white",
                        boxShadow: "0 10px 15px -3px rgba(239, 68, 68, 0.2)"
                    }
                }).showToast();
            } finally {
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalBtnText;
            }
        });
    }
});
</script>
@endpush
