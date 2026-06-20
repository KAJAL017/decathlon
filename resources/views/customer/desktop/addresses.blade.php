@extends('customer.layouts.desktop')

@section('title', 'My Addresses')
@section('page-title', 'Addresses')
@section('page-subtitle')
{{ $addresses->count() }} saved address{{ $addresses->count() !== 1 ? 'es' : '' }}
@endsection

@section('content')
<div class="max-w-6xl">

    <div class="flex items-center justify-between mb-8">
        <div>
            <p class="text-sm text-surface-400 mt-1">Manage your delivery addresses for faster checkout</p>
        </div>
        <button onclick="showAddressModal()" class="btn-primary inline-flex items-center gap-2 text-white px-5 py-3 rounded-xl text-sm font-semibold">
            <i data-lucide="plus" class="w-4 h-4"></i> Add Address
        </button>
    </div>

    @if($addresses->isEmpty())
        <div class="bg-white rounded-2xl p-16 border border-surface-100 text-center animate-fade-in">
            <div class="w-20 h-20 bg-surface-50 rounded-2xl flex items-center justify-center mx-auto mb-5">
                <i data-lucide="map-pin" class="w-10 h-10 text-surface-300"></i>
            </div>
            <h3 class="text-xl font-bold text-surface-900 mb-2">No addresses saved</h3>
            <p class="text-sm text-surface-400 mb-8 max-w-sm mx-auto">Add a delivery address to enjoy faster checkout and accurate deliveries.</p>
            <button onclick="showAddressModal()" class="btn-primary inline-flex items-center gap-2 text-white px-8 py-3.5 rounded-xl text-sm font-semibold">
                <i data-lucide="plus" class="w-4 h-4"></i> Add Your First Address
            </button>
        </div>
    @else
        <div class="grid grid-cols-2 gap-5" id="addressGrid">
            @foreach($addresses as $index => $address)
                <div class="bg-white rounded-2xl p-6 border {{ $address->is_default ? 'border-brand-300 ring-2 ring-brand-500/10' : 'border-surface-100' }} card-hover animate-slide-up opacity-0 stagger-{{ min($index + 1, 6) }}" id="address-{{ $address->id }}">
                    <div class="flex items-start justify-between mb-3">
                        <div class="flex items-center gap-2">
                            <span class="px-2.5 py-1 bg-surface-100 rounded-lg text-[11px] font-semibold text-surface-600 uppercase">{{ $address->label }}</span>
                            @if($address->is_default)
                                <span class="px-2.5 py-1 bg-brand-50 text-brand-700 rounded-lg text-[11px] font-semibold border border-brand-200 flex items-center gap-1">
                                    <i data-lucide="check-circle" class="w-3 h-3"></i> Default
                                </span>
                            @endif
                        </div>
                        <div class="flex items-center gap-1">
                            <button onclick="editAddress({{ $address->id }})" class="p-2 text-surface-400 hover:text-brand-600 rounded-lg hover:bg-brand-50 transition-colors" title="Edit">
                                <i data-lucide="pencil" class="w-4 h-4"></i>
                            </button>
                            <button onclick="deleteAddress({{ $address->id }})" class="p-2 text-surface-400 hover:text-red-500 rounded-lg hover:bg-red-50 transition-colors" title="Delete">
                                <i data-lucide="trash-2" class="w-4 h-4"></i>
                            </button>
                        </div>
                    </div>
                    <div class="space-y-1">
                        <p class="text-base font-semibold text-surface-900">{{ $address->full_name }}</p>
                        <p class="text-sm text-surface-600">{{ $address->address_line1 }}</p>
                        @if($address->address_line2)
                            <p class="text-sm text-surface-600">{{ $address->address_line2 }}</p>
                        @endif
                        <p class="text-sm text-surface-600">{{ $address->city }}, {{ $address->state }} {{ $address->pincode }}</p>
                        <p class="text-sm text-surface-600">{{ $address->country }}</p>
                        @if($address->phone)
                            <p class="text-xs text-surface-400 mt-2 flex items-center gap-1">
                                <i data-lucide="phone" class="w-3 h-3"></i> {{ $address->phone }}
                            </p>
                        @endif
                    </div>
                    @if(!$address->is_default)
                        <form action="{{ route('customer.addresses.default', $address->id) }}" method="POST" class="mt-4 pt-4 border-t border-surface-100">
                            @csrf
                            <button type="submit" class="text-sm font-medium text-brand-600 hover:text-brand-700 flex items-center gap-1.5">
                                <i data-lucide="star" class="w-3.5 h-3.5"></i> Set as Default
                            </button>
                        </form>
                    @endif
                </div>
            @endforeach
        </div>
    @endif
</div>

<div id="addressModal" class="fixed inset-0 bg-black/50 z-[9998] hidden items-center justify-center p-4">
    <div class="bg-white rounded-2xl p-6 w-full max-w-lg animate-scale-in shadow-2xl max-h-[85vh] overflow-y-auto">
        <div class="flex items-center justify-between mb-6">
            <h3 class="text-lg font-bold text-surface-900" id="addressModalTitle">Add Address</h3>
            <button onclick="hideAddressModal()" class="p-2 text-surface-400 hover:text-surface-600 rounded-lg hover:bg-surface-50 transition-colors">
                <i data-lucide="x" class="w-5 h-5"></i>
            </button>
        </div>
        <form action="{{ route('customer.addresses.store') }}" method="POST" id="addressForm">
            @csrf
            <input type="hidden" name="_method" value="POST" id="addressMethod">
            <div class="space-y-4">
                <div>
                    <label class="block text-xs font-medium text-surface-500 mb-2">Label</label>
                    <div class="flex gap-3">
                        <label class="flex-1"><input type="radio" name="label" value="Home" checked class="peer hidden"><span class="block text-center py-3 border border-surface-200 rounded-xl text-sm font-medium cursor-pointer peer-checked:border-brand-500 peer-checked:bg-brand-50 peer-checked:text-brand-700 transition-all">Home</span></label>
                        <label class="flex-1"><input type="radio" name="label" value="Work" class="peer hidden"><span class="block text-center py-3 border border-surface-200 rounded-xl text-sm font-medium cursor-pointer peer-checked:border-brand-500 peer-checked:bg-brand-50 peer-checked:text-brand-700 transition-all">Work</span></label>
                        <label class="flex-1"><input type="radio" name="label" value="Other" class="peer hidden"><span class="block text-center py-3 border border-surface-200 rounded-xl text-sm font-medium cursor-pointer peer-checked:border-brand-500 peer-checked:bg-brand-50 peer-checked:text-brand-700 transition-all">Other</span></label>
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-medium text-surface-500 mb-1.5">Full Name</label>
                        <input type="text" name="full_name" required class="w-full px-4 py-2.5 bg-surface-50 border border-surface-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition-all">
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-surface-500 mb-1.5">Phone</label>
                        <input type="tel" name="phone" class="w-full px-4 py-2.5 bg-surface-50 border border-surface-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition-all">
                    </div>
                </div>
                <div>
                    <label class="block text-xs font-medium text-surface-500 mb-1.5">Address Line 1</label>
                    <input type="text" name="address_line1" required class="w-full px-4 py-2.5 bg-surface-50 border border-surface-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition-all">
                </div>
                <div>
                    <label class="block text-xs font-medium text-surface-500 mb-1.5">Address Line 2 (Optional)</label>
                    <input type="text" name="address_line2" class="w-full px-4 py-2.5 bg-surface-50 border border-surface-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition-all">
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-medium text-surface-500 mb-1.5">City</label>
                        <input type="text" name="city" required class="w-full px-4 py-2.5 bg-surface-50 border border-surface-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition-all">
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-surface-500 mb-1.5">State</label>
                        <input type="text" name="state" required class="w-full px-4 py-2.5 bg-surface-50 border border-surface-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition-all">
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-medium text-surface-500 mb-1.5">Pincode</label>
                        <input type="text" name="pincode" required class="w-full px-4 py-2.5 bg-surface-50 border border-surface-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition-all">
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-surface-500 mb-1.5">Country</label>
                        <input type="text" name="country" value="India" required class="w-full px-4 py-2.5 bg-surface-50 border border-surface-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition-all">
                    </div>
                </div>
                <label class="flex items-center gap-2.5 cursor-pointer py-2">
                    <input type="checkbox" name="is_default" value="1" class="w-4 h-4 text-brand-600 border-surface-300 rounded focus:ring-brand-500">
                    <span class="text-sm text-surface-600">Set as default address</span>
                </label>
            </div>
            <div class="flex gap-3 mt-6 pt-6 border-t border-surface-100">
                <button type="button" onclick="hideAddressModal()" class="flex-1 py-3 border border-surface-200 rounded-xl text-sm font-medium hover:bg-surface-50 transition-colors">Cancel</button>
                <button type="submit" class="flex-1 btn-primary text-white py-3 rounded-xl text-sm font-semibold">Save Address</button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => lucide.createIcons());

function showAddressModal() {
    document.getElementById('addressModalTitle').textContent = 'Add Address';
    document.getElementById('addressForm').action = '{{ route("customer.addresses.store") }}';
    document.getElementById('addressMethod').value = 'POST';
    document.getElementById('addressModal').classList.remove('hidden');
    document.getElementById('addressModal').classList.add('flex');
}

function hideAddressModal() {
    document.getElementById('addressModal').classList.add('hidden');
    document.getElementById('addressModal').classList.remove('flex');
}

function editAddress(id) {
    window.location.href = `{{ route('customer.addresses') }}`;
}

async function deleteAddress(id) {
    confirmAction('Are you sure you want to delete this address?', async () => {
        const res = await ajax(`{{ url('account/addresses') }}/${id}`, { method: 'DELETE' });
        const data = await res.json();
        if (data.success) {
            const el = document.getElementById(`address-${id}`);
            el.style.transition = 'all 0.3s ease';
            el.style.opacity = '0';
            el.style.transform = 'scale(0.95)';
            setTimeout(() => el.remove(), 300);
            showToast(data.message);
        }
    });
}
</script>
@endpush
@endsection