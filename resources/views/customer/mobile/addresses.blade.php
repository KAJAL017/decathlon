@extends('customer.layouts.mobile')

@section('title', 'My Addresses')
@section('page-title', 'Addresses')

@section('content')
<div class="space-y-3">

    <button onclick="showAddressModal()" class="w-full btn-primary flex items-center justify-center gap-2 text-white py-3.5 rounded-xl text-sm font-semibold active:scale-[0.98] transition-transform">
        <i data-lucide="plus" class="w-4 h-4"></i> Add New Address
    </button>

    @if($addresses->isEmpty())
        <div class="bg-white rounded-2xl p-8 border border-surface-100 text-center animate-fade-in">
            <div class="w-14 h-14 bg-surface-50 rounded-2xl flex items-center justify-center mx-auto mb-3">
                <i data-lucide="map-pin" class="w-7 h-7 text-surface-300"></i>
            </div>
            <h3 class="text-base font-bold text-surface-900 mb-1">No addresses saved</h3>
            <p class="text-xs text-surface-400 mb-5">Add a delivery address for faster checkout.</p>
            <button onclick="showAddressModal()" class="btn-primary inline-flex items-center gap-2 text-white px-6 py-3 rounded-xl text-sm font-semibold active:scale-[0.98] transition-transform">
                <i data-lucide="plus" class="w-4 h-4"></i> Add Address
            </button>
        </div>
    @else
        <div class="space-y-2.5" id="addressList">
            @foreach($addresses as $index => $address)
                <div class="bg-white rounded-2xl p-4 border {{ $address->is_default ? 'border-brand-300 ring-2 ring-brand-500/10' : 'border-surface-100' }} active:scale-[0.98] transition-transform animate-slide-up opacity-0 stagger-{{ min($index + 1, 6) }}" id="address-{{ $address->id }}">
                    <div class="flex items-start justify-between mb-2">
                        <div class="flex items-center gap-1.5 flex-wrap">
                            <span class="px-2 py-0.5 bg-surface-100 rounded-md text-[10px] font-semibold text-surface-600 uppercase">{{ $address->label }}</span>
                            @if($address->is_default)
                                <span class="px-2 py-0.5 bg-brand-50 text-brand-700 rounded-md text-[10px] font-semibold border border-brand-200 flex items-center gap-1">
                                    <i data-lucide="check-circle" class="w-3 h-3"></i> Default
                                </span>
                            @endif
                        </div>
                        <div class="flex items-center gap-1 shrink-0 ml-2">
                            <button onclick="editAddress({{ $address }})" class="p-2 text-surface-400 active:text-brand-600 rounded-lg active:bg-brand-50 transition-colors" title="Edit">
                                <i data-lucide="pencil" class="w-4 h-4"></i>
                            </button>
                            <button onclick="deleteAddress({{ $address->id }})" class="p-2 text-surface-400 active:text-red-500 rounded-lg active:bg-red-50 transition-colors" title="Delete">
                                <i data-lucide="trash-2" class="w-4 h-4"></i>
                            </button>
                        </div>
                    </div>
                    <p class="text-sm font-semibold text-surface-900">{{ $address->full_name }}</p>
                    <p class="text-xs text-surface-600 mt-0.5 leading-relaxed">{{ $address->address_line1 }}</p>
                    @if($address->address_line2)
                        <p class="text-xs text-surface-600 leading-relaxed">{{ $address->address_line2 }}</p>
                    @endif
                    <p class="text-xs text-surface-600 leading-relaxed">{{ $address->city }}, {{ $address->state }} {{ $address->pincode }}</p>
                    <p class="text-xs text-surface-600">{{ $address->country }}</p>
                    @if($address->phone)
                        <p class="text-[11px] text-surface-400 mt-1.5 flex items-center gap-1">
                            <i data-lucide="phone" class="w-3 h-3"></i> {{ $address->phone }}
                        </p>
                    @endif
                    @if(!$address->is_default)
                        <form action="{{ route('customer.addresses.default', $address->id) }}" method="POST" class="mt-2.5">
                            @csrf
                            <button type="submit" class="text-xs font-medium text-brand-600 active:text-brand-700 flex items-center gap-1">
                                <i data-lucide="check" class="w-3.5 h-3.5"></i> Set as Default
                            </button>
                        </form>
                    @endif
                </div>
            @endforeach
        </div>
    @endif
</div>

{{-- Address Bottom Sheet --}}
<div id="addressModal" class="fixed inset-0 bg-black/50 z-[9998] hidden items-end justify-center p-0" onclick="if(event.target===this)hideAddressModal()">
    <div class="bg-white rounded-t-2xl w-full max-h-[85vh] overflow-y-auto animate-slide-up-full shadow-2xl">
        <div class="sticky top-0 bg-white z-10 px-5 pt-4 pb-3 border-b border-surface-100">
            <div class="w-10 h-1 bg-surface-200 rounded-full mx-auto mb-3"></div>
            <div class="flex items-center justify-between">
                <h3 class="text-base font-bold text-surface-900" id="addressModalTitle">Add Address</h3>
                <button onclick="hideAddressModal()" class="p-2 text-surface-400 active:text-surface-600 rounded-lg active:bg-surface-100 transition-colors">
                    <i data-lucide="x" class="w-5 h-5"></i>
                </button>
            </div>
        </div>
        <form action="{{ route('customer.addresses.store') }}" method="POST" id="addressForm" class="px-5 py-4">
            @csrf
            <input type="hidden" name="_method" value="POST" id="addressMethod">
            <div class="space-y-3">
                <div>
                    <label class="block text-[11px] font-medium text-surface-500 mb-1.5">Label</label>
                    <div class="flex gap-2">
                        <label class="flex-1"><input type="radio" name="label" value="Home" checked class="peer hidden"><span class="block text-center py-2.5 border border-surface-200 rounded-xl text-xs font-medium cursor-pointer peer-checked:border-brand-500 peer-checked:bg-brand-50 peer-checked:text-brand-700 transition-all active:scale-[0.98]">Home</span></label>
                        <label class="flex-1"><input type="radio" name="label" value="Work" class="peer hidden"><span class="block text-center py-2.5 border border-surface-200 rounded-xl text-xs font-medium cursor-pointer peer-checked:border-brand-500 peer-checked:bg-brand-50 peer-checked:text-brand-700 transition-all active:scale-[0.98]">Work</span></label>
                        <label class="flex-1"><input type="radio" name="label" value="Other" class="peer hidden"><span class="block text-center py-2.5 border border-surface-200 rounded-xl text-xs font-medium cursor-pointer peer-checked:border-brand-500 peer-checked:bg-brand-50 peer-checked:text-brand-700 transition-all active:scale-[0.98]">Other</span></label>
                    </div>
                </div>
                <div>
                    <label class="block text-[11px] font-medium text-surface-500 mb-1.5">Full Name</label>
                    <input type="text" name="full_name" id="address_full_name" required class="w-full px-4 py-3 bg-surface-50 border border-surface-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500">
                </div>
                <div>
                    <label class="block text-[11px] font-medium text-surface-500 mb-1.5">Phone</label>
                    <input type="tel" name="phone" id="address_phone" class="w-full px-4 py-3 bg-surface-50 border border-surface-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500">
                </div>
                <div>
                    <label class="block text-[11px] font-medium text-surface-500 mb-1.5">Address Line 1</label>
                    <input type="text" name="address_line1" id="address_line1" required class="w-full px-4 py-3 bg-surface-50 border border-surface-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500">
                </div>
                <div>
                    <label class="block text-[11px] font-medium text-surface-500 mb-1.5">Address Line 2 (Optional)</label>
                    <input type="text" name="address_line2" id="address_line2" class="w-full px-4 py-3 bg-surface-50 border border-surface-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500">
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-[11px] font-medium text-surface-500 mb-1.5">City</label>
                        <input type="text" name="city" id="address_city" required class="w-full px-4 py-3 bg-surface-50 border border-surface-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500">
                    </div>
                    <div>
                        <label class="block text-[11px] font-medium text-surface-500 mb-1.5">State</label>
                        <input type="text" name="state" id="address_state" required class="w-full px-4 py-3 bg-surface-50 border border-surface-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500">
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-[11px] font-medium text-surface-500 mb-1.5">Pincode</label>
                        <input type="text" name="pincode" id="address_pincode" required class="w-full px-4 py-3 bg-surface-50 border border-surface-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500">
                    </div>
                    <div>
                        <label class="block text-[11px] font-medium text-surface-500 mb-1.5">Country</label>
                        <input type="text" name="country" id="address_country" value="India" required class="w-full px-4 py-3 bg-surface-50 border border-surface-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500">
                    </div>
                </div>
                <label class="flex items-center gap-2.5 cursor-pointer py-1">
                    <input type="checkbox" name="is_default" value="1" id="address_is_default" class="w-4 h-4 text-brand-600 border-surface-300 rounded focus:ring-brand-500">
                    <span class="text-xs text-surface-600">Set as default address</span>
                </label>
            </div>
            <div class="flex gap-3 mt-5 pb-6">
                <button type="button" onclick="hideAddressModal()" class="flex-1 py-3.5 border border-surface-200 rounded-xl text-sm font-medium active:bg-surface-50 transition-colors">Cancel</button>
                <button type="submit" class="flex-1 btn-primary text-white py-3.5 rounded-xl text-sm font-semibold active:scale-[0.98] transition-transform">Save Address</button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
function showAddressModal() {
    document.getElementById('addressModalTitle').textContent = 'Add Address';
    document.getElementById('addressForm').action = '{{ route("customer.addresses.store") }}';
    document.getElementById('addressMethod').value = 'POST';
    document.getElementById('addressForm').reset();
    document.getElementById('addressModal').classList.remove('hidden');
    document.getElementById('addressModal').classList.add('flex');
}
function hideAddressModal() {
    document.getElementById('addressModal').classList.add('hidden');
    document.getElementById('addressModal').classList.remove('flex');
}
function editAddress(address) {
    document.getElementById('addressModalTitle').textContent = 'Edit Address';
    document.getElementById('addressForm').action = '{{ url("account/addresses") }}/' + address.id;
    document.getElementById('addressMethod').value = 'PUT';
    document.getElementById('address_full_name').value = address.full_name || '';
    document.getElementById('address_phone').value = address.phone || '';
    document.getElementById('address_line1').value = address.address_line1 || '';
    document.getElementById('address_line2').value = address.address_line2 || '';
    document.getElementById('address_city').value = address.city || '';
    document.getElementById('address_state').value = address.state || '';
    document.getElementById('address_pincode').value = address.pincode || '';
    document.getElementById('address_country').value = address.country || 'India';
    document.getElementById('address_is_default').checked = !!address.is_default;
    const labelRadio = document.querySelector(`input[name="label"][value="${address.label || 'Home'}"]`);
    if (labelRadio) labelRadio.checked = true;
    document.getElementById('addressModal').classList.remove('hidden');
    document.getElementById('addressModal').classList.add('flex');
}
function deleteAddress(id) {
    confirmAction('Are you sure you want to delete this address?', async () => {
        const res = await ajax(`{{ url('account/addresses') }}/${id}`, { method: 'DELETE' });
        const data = await res.json();
        if (data.success) {
            const el = document.getElementById(`address-${id}`);
            el.style.transition = 'all 0.3s ease';
            el.style.opacity = '0';
            el.style.transform = 'scale(0.95) translateY(-8px)';
            setTimeout(() => el.remove(), 300);
            showToast(data.message);
        }
    });
}
</script>
@endpush
@endsection
