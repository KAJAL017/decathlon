@extends('layouts.app')

@section('title', 'Secure Checkout - Decathlon')

@section('content')
<div class="w-full px-5 sm:px-10 lg:px-20 py-12">

    {{-- Guest banner --}}
    @if($isGuest)
    <div class="bg-[#f0f5ff] border border-[#dce6fa] rounded-2xl p-4 mb-8 flex flex-col sm:flex-row items-center justify-between gap-3">
        <div class="flex items-center gap-3">
            <i data-lucide="user" class="w-5 h-5 text-[#1c4bbf]"></i>
            <p class="text-sm text-[#1c4bbf] font-bold">Checking out as guest? <a href="{{ route('login') }}" class="font-black underline hover:text-[#0c246b]">Log in</a> for faster checkout and order tracking.</p>
        </div>
    </div>
    @endif

    <h1 class="text-3xl font-black text-gray-900 uppercase tracking-tight mb-8">Secure Checkout</h1>

    <form id="checkout-form" action="{{ route('checkout.store') }}" method="POST">
        @csrf
        @if($isGuest)
        <input type="hidden" name="is_guest" value="1">
        @endif
        <div class="grid grid-cols-1 lg:grid-cols-5 gap-8 lg:gap-16">
            <!-- Left: Shipping & Payment -->
            <div class="lg:col-span-3 space-y-8">

                {{-- Guest Contact Info --}}
                @if($isGuest)
                <section class="bg-white rounded-2xl border border-gray-100 p-6 md:p-8 shadow-sm">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="w-8 h-8 bg-[#0082C3] text-white rounded-full flex items-center justify-center font-black text-sm">1</div>
                        <h2 class="text-xl font-black text-gray-900 uppercase tracking-wide">Contact Information</h2>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-xs font-black text-gray-500 uppercase tracking-widest mb-1">Full Name</label>
                            <input type="text" name="guest_name" required class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:ring-[#0082C3] focus:border-[#0082C3] text-sm" placeholder="John Doe">
                        </div>
                        <div>
                            <label class="block text-xs font-black text-gray-500 uppercase tracking-widest mb-1">Email</label>
                            <input type="email" name="guest_email" required class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:ring-[#0082C3] focus:border-[#0082C3] text-sm" placeholder="you@example.com">
                        </div>
                        <div>
                            <label class="block text-xs font-black text-gray-500 uppercase tracking-widest mb-1">Phone</label>
                            <input type="text" name="guest_phone" required class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:ring-[#0082C3] focus:border-[#0082C3] text-sm" placeholder="+91 98765 43210">
                        </div>
                    </div>
                </section>
                @endif

                <!-- Shipping Address -->
                <section class="bg-white rounded-2xl border border-gray-100 p-6 md:p-8 shadow-sm">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="w-8 h-8 bg-[#0082C3] text-white rounded-full flex items-center justify-center font-black text-sm">{{ $isGuest ? '2' : '1' }}</div>
                        <h2 class="text-xl font-black text-gray-900 uppercase tracking-wide">Shipping Address</h2>
                    </div>

                    @if($addresses->isNotEmpty())
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                            @foreach($addresses as $address)
                                <label class="relative flex p-4 border rounded-xl cursor-pointer focus:outline-none transition-all {{ $address->id == ($defaultAddress->id ?? 0) ? 'border-[#0082C3] bg-blue-50/30' : 'border-gray-200 hover:border-gray-300' }}">
                                    <input type="radio" name="address_id" value="{{ $address->id }}" class="sr-only" {{ $address->id == ($defaultAddress->id ?? 0) ? 'checked' : '' }}>
                                    <div class="flex flex-col">
                                        <span class="block text-sm font-black text-gray-900 uppercase tracking-wider mb-1">{{ $address->label }}</span>
                                        <span class="block text-sm font-bold text-gray-900">{{ $address->full_name }}</span>
                                        <span class="block text-xs text-gray-500 mt-1 leading-relaxed">
                                            {{ $address->address_line1 }}, {{ $address->address_line2 ? $address->address_line2 . ',' : '' }}<br>
                                            {{ $address->city }}, {{ $address->state }} - {{ $address->pincode }}<br>
                                            Phone: {{ $address->phone }}
                                        </span>
                                    </div>
                                    @if($address->id == ($defaultAddress->id ?? 0))
                                        <div class="absolute top-4 right-4 text-[#0082C3]">
                                            <i data-lucide="circle-check" class="w-5 h-5"></i>
                                        </div>
                                    @endif
                                </label>
                            @endforeach
                            <label class="relative flex p-4 border border-dashed border-gray-300 rounded-xl cursor-pointer hover:border-[#0082C3] transition-all group">
                                <input type="radio" name="address_id" value="new" class="sr-only">
                                <div class="flex flex-col items-center justify-center w-full py-4">
                                    <div class="w-10 h-10 bg-gray-50 text-gray-400 group-hover:bg-blue-50 group-hover:text-[#0082C3] rounded-full flex items-center justify-center transition-colors mb-2">
                                        <i data-lucide="plus" class="w-6 h-6"></i>
                                    </div>
                                    <span class="text-sm font-bold text-gray-500 group-hover:text-[#0082C3]">Add New Address</span>
                                </div>
                            </label>
                        </div>
                    @else
                        <input type="hidden" name="address_id" value="new">
                    @endif

                    <!-- New Address Form -->
                    <div id="new-address-fields" class="{{ $addresses->isNotEmpty() ? 'hidden' : '' }} space-y-4 mt-6 p-6 bg-gray-50 rounded-xl border border-gray-100">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-black text-gray-500 uppercase tracking-widest mb-1">Full Name</label>
                                <input type="text" name="new_address[full_name]" class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:ring-[#0082C3] focus:border-[#0082C3] text-sm" @if($isGuest) required @endif>
                            </div>
                            <div>
                                <label class="block text-xs font-black text-gray-500 uppercase tracking-widest mb-1">Phone Number</label>
                                <input type="text" name="new_address[phone]" class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:ring-[#0082C3] focus:border-[#0082C3] text-sm" @if($isGuest) required @endif>
                            </div>
                        </div>
                        <div>
                            <label class="block text-xs font-black text-gray-500 uppercase tracking-widest mb-1">Address Line 1 (House No, Street)</label>
                            <input type="text" name="new_address[address_line1]" class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:ring-[#0082C3] focus:border-[#0082C3] text-sm" @if($isGuest) required @endif>
                        </div>
                        <div>
                            <label class="block text-xs font-black text-gray-500 uppercase tracking-widest mb-1">Address Line 2 (Optional)</label>
                            <input type="text" name="new_address[address_line2]" class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:ring-[#0082C3] focus:border-[#0082C3] text-sm">
                        </div>
                        <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                            <div>
                                <label class="block text-xs font-black text-gray-500 uppercase tracking-widest mb-1">City</label>
                                <input type="text" name="new_address[city]" class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:ring-[#0082C3] focus:border-[#0082C3] text-sm" @if($isGuest) required @endif>
                            </div>
                            <div>
                                <label class="block text-xs font-black text-gray-500 uppercase tracking-widest mb-1">State</label>
                                <input type="text" name="new_address[state]" class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:ring-[#0082C3] focus:border-[#0082C3] text-sm" @if($isGuest) required @endif>
                            </div>
                            <div class="col-span-2 md:col-span-1">
                                <label class="block text-xs font-black text-gray-500 uppercase tracking-widest mb-1">Pincode</label>
                                <input type="text" name="new_address[pincode]" class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:ring-[#0082C3] focus:border-[#0082C3] text-sm" @if($isGuest) required @endif>
                            </div>
                        </div>
                    </div>
                </section>

                <!-- Payment Method -->
                <section class="bg-white rounded-2xl border border-gray-100 p-6 md:p-8 shadow-sm">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="w-8 h-8 bg-[#0082C3] text-white rounded-full flex items-center justify-center font-black text-sm">{{ $isGuest ? '3' : '2' }}</div>
                        <h2 class="text-xl font-black text-gray-900 uppercase tracking-wide">Payment Method</h2>
                    </div>

                    <div class="space-y-4">
                        <label class="relative flex items-center p-4 border rounded-xl cursor-pointer hover:bg-gray-50 transition-all border-[#0082C3] bg-blue-50/30">
                            <input type="radio" name="payment_method" value="cod" class="w-4 h-4 text-[#0082C3] focus:ring-[#0082C3]" checked>
                            <div class="ml-4 flex items-center gap-3">
                                <div class="w-10 h-10 bg-gray-100 rounded-lg flex items-center justify-center text-gray-600">
                                    <i data-lucide="banknote" class="w-6 h-6"></i>
                                </div>
                                <div>
                                    <span class="block text-sm font-black text-gray-900 uppercase tracking-tight">Cash on Delivery</span>
                                    <span class="block text-xs text-gray-500">Pay when your sports gear arrives.</span>
                                </div>
                            </div>
                        </label>

                        <label class="relative flex items-center p-4 border border-gray-200 rounded-xl cursor-pointer hover:bg-gray-50 transition-all">
                            <input type="radio" name="payment_method" value="razorpay" class="w-4 h-4 text-[#0082C3] focus:ring-[#0082C3]">
                            <div class="ml-4 flex items-center gap-3">
                                <div class="w-10 h-10 bg-gray-100 rounded-lg flex items-center justify-center text-gray-600">
                                    <i data-lucide="credit-card" class="w-6 h-6"></i>
                                </div>
                                <div>
                                    <span class="block text-sm font-black text-gray-900 uppercase tracking-tight">Online Payment</span>
                                    <span class="block text-xs text-gray-500">Credit/Debit Cards, UPI, Netbanking.</span>
                                </div>
                            </div>
                            <span class="ml-auto bg-yellow-100 text-yellow-800 text-[10px] font-black px-2 py-0.5 rounded uppercase tracking-tighter">Fastest Delivery</span>
                        </label>
                    </div>
                </section>
            </div>

            <!-- Right: Order Summary -->
            <div class="lg:col-span-2 space-y-6">
                <div class="bg-white rounded-2xl border border-gray-100 shadow-lg sticky top-24">
                    <div class="p-6 border-b border-gray-100">
                        <h3 class="text-lg font-black text-gray-900 uppercase tracking-widest">Order Summary</h3>
                    </div>

                    <div class="p-6 space-y-4">
                        <div class="max-h-[300px] overflow-y-auto space-y-4 pr-2 scrollbar-hide">
                            @foreach($cart->items as $item)
                                <div class="flex gap-4">
                                    <div class="w-16 h-16 bg-gray-50 rounded-lg border border-gray-100 flex-shrink-0 overflow-hidden">
                                        @php $image = $item->product->featuredImage ?? $item->product->images->first(); @endphp
                                        <img src="{{ $image?->image_url ?? asset('images/placeholder-product.svg') }}" alt="{{ $item->product->name }}" class="w-full h-full object-contain">
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <h4 class="text-xs font-bold text-gray-900 truncate uppercase tracking-tight">{{ $item->product->name }}</h4>
                                        <p class="text-[10px] text-gray-500 mt-0.5">{{ $item->variant?->variant_name ?? 'Default' }} x {{ $item->quantity }}</p>
                                        <p class="text-xs font-black text-gray-900 mt-1">₹{{ number_format(($item->variant?->price ?? 0) * $item->quantity, 2) }}</p>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <div class="pt-4 border-t border-gray-100 space-y-3">
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-500 font-medium">Subtotal</span>
                                <span class="text-gray-900 font-bold">₹{{ number_format($cart->total_amount, 2) }}</span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-500 font-medium">Shipping</span>
                                <span class="text-green-600 font-bold uppercase text-xs tracking-widest">Free</span>
                            </div>
                            <div class="flex justify-between pt-3 border-t border-gray-100">
                                <span class="text-base font-black text-gray-900 uppercase">Total</span>
                                <span class="text-xl font-black text-[#0082C3]">₹{{ number_format($cart->total_amount, 2) }}</span>
                            </div>
                        </div>

                        <button type="submit" id="place-order-btn" class="w-full bg-[#183a9e] hover:bg-[#0c246b] text-white py-4 rounded-xl text-sm font-black uppercase tracking-[0.2em] shadow-xl transition-all flex items-center justify-center gap-2 group">
                            <span class="btn-text">Place Order</span>
                            <i data-lucide="chevron-right" class="w-4 h-4 group-hover:translate-x-1 transition-transform"></i>
                            <span class="loading-spinner hidden">
                                <i data-lucide="loader" class="animate-spin h-5 w-5 text-white"></i>
                            </span>
                        </button>

                        <p class="text-[10px] text-center text-gray-400 font-medium leading-relaxed">
                            By placing this order, you agree to Decathlon's <br>
                            <a href="#" class="underline">Terms of Use</a> and <a href="#" class="underline">Sale Conditions</a>.
                        </p>

                        <div class="flex items-center justify-center gap-4 pt-4 border-t border-gray-100 grayscale opacity-50 contrast-125">
                            <img src="https://upload.wikimedia.org/wikipedia/commons/b/b5/PayPal.svg" alt="PayPal" class="h-4">
                            <img src="https://upload.wikimedia.org/wikipedia/commons/5/5e/Visa_Inc._logo.svg" alt="Visa" class="h-3">
                            <img src="https://upload.wikimedia.org/wikipedia/commons/2/2a/Mastercard-logo.svg" alt="Mastercard" class="h-5">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const checkoutForm = document.getElementById('checkout-form');
        const addressRadios = document.querySelectorAll('input[name="address_id"]');
        const newAddressFields = document.getElementById('new-address-fields');
        const placeOrderBtn = document.getElementById('place-order-btn');
        const btnText = placeOrderBtn.querySelector('.btn-text');
        const spinner = placeOrderBtn.querySelector('.loading-spinner');

        // Toggle New Address Fields
        addressRadios.forEach(radio => {
            radio.addEventListener('change', function() {
                document.querySelectorAll('input[name="address_id"]').forEach(r => {
                    r.closest('label').classList.remove('border-[#0082C3]', 'bg-blue-50/30');
                    r.closest('label').classList.add('border-gray-200');
                });

                if (this.checked) {
                    this.closest('label').classList.add('border-[#0082C3]', 'bg-blue-50/30');
                    this.closest('label').classList.remove('border-gray-200');
                }

                if (this.value === 'new') {
                    newAddressFields.classList.remove('hidden');
                    newAddressFields.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
                } else {
                    newAddressFields.classList.add('hidden');
                }
            });
        });

        // Handle Form Submission
        checkoutForm.addEventListener('submit', function(e) {
            e.preventDefault();

            placeOrderBtn.disabled = true;
            btnText.classList.add('hidden');
            spinner.classList.remove('hidden');

            const formData = new FormData(checkoutForm);

            fetch(checkoutForm.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Toastify({
                        text: data.message,
                        duration: 3000,
                        gravity: "top",
                        position: "center",
                        backgroundColor: "#10b981",
                    }).showToast();

                    setTimeout(() => {
                        window.location.href = data.redirect;
                    }, 1000);
                } else {
                    Toastify({
                        text: data.message || "Something went wrong.",
                        duration: 3000,
                        gravity: "top",
                        position: "center",
                        backgroundColor: "#ef4444",
                    }).showToast();

                    placeOrderBtn.disabled = false;
                    btnText.classList.remove('hidden');
                    spinner.classList.add('hidden');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                Toastify({
                    text: "Failed to place order. Please try again.",
                    duration: 3000,
                    gravity: "top",
                    position: "center",
                    backgroundColor: "#ef4444",
                }).showToast();

                placeOrderBtn.disabled = false;
                btnText.classList.remove('hidden');
                spinner.classList.add('hidden');
            });
        });
    });
</script>
@endpush
@endsection
