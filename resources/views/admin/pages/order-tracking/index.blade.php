@extends('admin.layouts.app')
@section('title', 'Order Tracking')

@section('content')
<div class="space-y-6">

    {{-- Header --}}
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Order Tracking</h1>
            <p class="text-sm text-gray-500 mt-0.5">Track shipment status in real-time from Shiprocket</p>
        </div>
    </div>

    {{-- Search Card --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="p-8 md:p-12 bg-gradient-to-br from-[#0082C3] to-[#006ba3] relative overflow-hidden">
            {{-- Decorative circles --}}
            <div class="absolute -top-12 -right-12 w-64 h-64 bg-white/10 rounded-full blur-3xl"></div>
            <div class="absolute -bottom-12 -left-12 w-64 h-64 bg-black/10 rounded-full blur-3xl"></div>

            <div class="relative z-10 max-w-2xl mx-auto text-center space-y-6">
                <h2 class="text-2xl md:text-3xl font-black text-white uppercase tracking-tight">Track Your Shipment</h2>
                <p class="text-blue-100 text-sm md:text-base font-medium">Enter Order Number or AWB / Tracking ID</p>
                
                <div class="flex flex-col md:flex-row gap-3">
                    <div class="flex-1 relative">
                        <input type="text" id="trackInput" 
                               placeholder="e.g. DEC-1001 or 1432567890" 
                               class="w-full px-6 py-4 rounded-xl text-lg font-bold text-gray-900 border-0 focus:ring-4 focus:ring-white/20 outline-none shadow-xl"
                               onkeypress="if(event.key === 'Enter') performTrack()">
                        <div id="trackLoader" class="absolute right-4 top-1/2 -translate-y-1/2 hidden">
                            <div class="w-6 h-6 border-2 border-[#0082C3] border-t-transparent rounded-full animate-spin"></div>
                        </div>
                    </div>
                    <button onclick="performTrack()" class="px-10 py-4 bg-yellow-400 hover:bg-yellow-500 text-gray-900 font-black text-lg rounded-xl shadow-xl transition-all hover:scale-105 active:scale-95 uppercase tracking-widest">
                        Track Now
                    </button>
                </div>
            </div>
        </div>

        {{-- Results Area --}}
        <div id="resultsArea" class="p-6 md:p-8 hidden">
            {{-- Order Summary --}}
            <div id="orderSummary" class="mb-8 grid grid-cols-1 md:grid-cols-3 gap-6">
                {{-- Dynamic content injected here --}}
            </div>

            {{-- Tracking Timeline --}}
            <div class="bg-gray-50 rounded-2xl p-6 md:p-8">
                <h3 class="text-sm font-black text-gray-900 uppercase tracking-widest mb-8 flex items-center gap-2">
                    <i data-lucide="clipboard-list" class="w-5 h-5 text-[#0082C3]"></i>
                    Shipment Journey
                </h3>
                
                <div id="trackingTimeline" class="space-y-0 relative before:absolute before:inset-0 before:left-[15px] md:before:left-[135px] before:w-0.5 before:bg-gray-200">
                    {{-- Dynamic timeline injected here --}}
                </div>
            </div>
        </div>

        {{-- Empty State --}}
        <div id="emptyState" class="p-12 text-center space-y-4">
            <div class="w-20 h-20 bg-gray-50 rounded-full flex items-center justify-center mx-auto">
                <i data-lucide="search" class="w-10 h-10 text-gray-300"></i>
            </div>
            <div>
                <h3 class="text-lg font-bold text-gray-900">No Tracking Data Requested</h3>
                <p class="text-sm text-gray-500">Enter a valid ID above to fetch real-time updates from Shiprocket</p>
            </div>
        </div>

        {{-- Error State --}}
        <div id="errorState" class="p-12 text-center space-y-4 hidden">
            <div class="w-20 h-20 bg-red-50 rounded-full flex items-center justify-center mx-auto">
                <i data-lucide="alert-triangle" class="w-10 h-10 text-red-400"></i>
            </div>
            <div>
                <h3 class="text-lg font-bold text-gray-900" id="errorTitle">Track Failed</h3>
                <p class="text-sm text-gray-500" id="errorMsg">The tracking ID or order number is invalid or not yet synced.</p>
            </div>
        </div>
    </div>

    {{-- Recent Tracked --}}
    <div class="space-y-4">
        <h3 class="text-sm font-black text-gray-900 uppercase tracking-widest flex items-center gap-2">
            <i data-lucide="clock" class="w-4 h-4 text-gray-400"></i>
            Recently Tracked Orders
        </h3>
        <div id="recentTracked" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4">
            {{-- Dynamic recent list --}}
        </div>
    </div>

</div>

@endsection

@push('scripts')
<script>
let isFirstLoad = true;
function performTrack() {
    const query = document.getElementById('trackInput').value.trim();
    if (query.length < 3) return;

    const loader = document.getElementById('trackLoader');
    const results = document.getElementById('resultsArea');
    const empty = document.getElementById('emptyState');
    const error = document.getElementById('errorState');

    loader.classList.remove('hidden');
    results.classList.add('hidden');
    empty.classList.add('hidden');
    error.classList.add('hidden');

    fetch(`{{ route('admin.order-tracking.search') }}?query=${encodeURIComponent(query)}`)
        .then(r => r.json())
        .then(res => {
            loader.classList.add('hidden');
            if (res.success) {
                renderResults(res.data);
                results.classList.remove('hidden');
                loadRecent(); // Refresh recent list
            } else {
                showError(res.message);
            }
        })
        .catch(err => {
            loader.classList.add('hidden');
            showError(err.message);
        });
}

function showError(msg) {
    document.getElementById('errorMsg').textContent = msg;
    document.getElementById('errorState').classList.remove('hidden');
}

function renderResults(data) {
    const order = data.order;
    const tracking = data.tracking;

    // Render Order Summary
    const summary = document.getElementById('orderSummary');
    summary.innerHTML = `
        <div class="bg-white border border-gray-100 p-5 rounded-2xl shadow-sm">
            <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Order Information</p>
            <h4 class="text-lg font-bold text-[#0082C3]">${order.order_number}</h4>
            <div class="mt-2 space-y-1">
                <p class="text-sm font-bold text-gray-900">${order.customer_name}</p>
                <p class="text-xs text-gray-500">${order.customer_email}</p>
            </div>
        </div>
        <div class="bg-white border border-gray-100 p-5 rounded-2xl shadow-sm">
            <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Status & Carrier</p>
            <div class="flex items-center gap-2 mb-2">
                <span class="px-2 py-0.5 bg-blue-100 text-blue-700 rounded-full text-[10px] font-black uppercase tracking-widest">${order.status}</span>
                <span class="px-2 py-0.5 bg-gray-100 text-gray-600 rounded-full text-[10px] font-black uppercase tracking-widest">${order.payment_status}</span>
            </div>
            <p class="text-sm font-bold text-gray-900">${order.shipping_carrier || 'Not assigned'}</p>
            <p class="text-xs font-mono text-gray-500">${order.tracking_number || 'No AWB'}</p>
        </div>
        <div class="bg-white border border-gray-100 p-5 rounded-2xl shadow-sm">
            <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Shipping Destination</p>
            <p class="text-sm font-bold text-gray-900 truncate">${order.shipping_city}, ${order.shipping_state}</p>
            <p class="text-xs text-gray-500">${order.shipping_pincode}</p>
            <p class="text-[10px] text-gray-400 mt-2">Ordered on: ${new Date(order.created_at).toLocaleDateString('en-IN', {day:'numeric', month:'short', year:'numeric'})}</p>
        </div>
    `;

    // Render Timeline
    const timeline = document.getElementById('trackingTimeline');
    
    if (!tracking || !tracking.data || !tracking.data.tracking_data || !tracking.data.tracking_data.shipment_track_activities) {
        timeline.innerHTML = `
            <div class="py-12 text-center">
                <p class="text-sm text-gray-400 italic">No tracking activities found for this AWB yet.</p>
                ${data.message ? `<p class="text-xs text-red-400 mt-2">${data.message}</p>` : ''}
            </div>
        `;
        return;
    }

    const activities = tracking.data.tracking_data.shipment_track_activities;
    
    timeline.innerHTML = activities.map((act, idx) => {
        const date = new Date(act.date);
        const dateStr = date.toLocaleDateString('en-IN', { day: 'numeric', month: 'short' });
        const timeStr = date.toLocaleTimeString('en-IN', { hour: '2-digit', minute: '2-digit' });
        const isFirst = idx === 0;

        return `
            <div class="relative flex flex-col md:flex-row gap-4 md:gap-12 pb-10 last:pb-0 group">
                {{-- Date Column (Desktop) --}}
                <div class="hidden md:block w-[100px] text-right pt-0.5">
                    <p class="text-sm font-black text-gray-900">${dateStr}</p>
                    <p class="text-[10px] font-bold text-gray-400 uppercase">${timeStr}</p>
                </div>

                {{-- Dot --}}
                <div class="absolute left-0 md:left-[128px] top-1 z-10">
                    <div class="w-4 h-4 rounded-full border-4 border-white shadow-sm ${isFirst ? 'bg-[#0082C3] ring-4 ring-blue-100 scale-125' : 'bg-gray-300'}"></div>
                </div>

                {{-- Content --}}
                <div class="pl-8 md:pl-0 flex-1">
                    <div class="md:hidden mb-1 flex items-center gap-2">
                        <span class="text-[10px] font-black text-gray-400 uppercase">${dateStr}, ${timeStr}</span>
                    </div>
                    <h4 class="text-sm font-bold ${isFirst ? 'text-[#0082C3]' : 'text-gray-900'} uppercase tracking-tight">${act.activity}</h4>
                    <p class="text-xs text-gray-500 mt-1 font-medium">${act.location}</p>
                </div>
            </div>
        `;
    }).join('');
}

function loadRecent() {
    fetch(`{{ route('admin.order-tracking.recent') }}`)
        .then(r => r.json())
        .then(res => {
            if (res.success) {
                const container = document.getElementById('recentTracked');
                if (res.data.length === 0) {
                    container.innerHTML = '<p class="col-span-full text-xs text-gray-400 italic text-center py-4 bg-gray-50 rounded-xl">No recently tracked orders</p>';
                    return;
                }
                container.innerHTML = res.data.map(o => `
                    <button onclick="fillAndTrack('${o.order_number}')" class="text-left p-4 bg-white border border-gray-100 rounded-2xl hover:border-[#0082C3] hover:shadow-md transition-all group">
                        <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1 group-hover:text-[#0082C3]">${o.order_number}</p>
                        <p class="text-sm font-bold text-gray-900 truncate">${o.customer_name}</p>
                        <div class="mt-2 flex items-center justify-between">
                            <span class="text-[10px] font-mono text-gray-400">${o.tracking_number}</span>
                            <i data-lucide="chevron-right" class="w-3 h-3 text-gray-300 group-hover:text-[#0082C3]"></i>
                        </div>
                    </button>
                `).join('');
            }
            if (isFirstLoad) { isFirstLoad = false; if (typeof window.dismissSkeleton === 'function') window.dismissSkeleton(); }
        });
}

function fillAndTrack(val) {
    document.getElementById('trackInput').value = val;
    performTrack();
}

document.addEventListener('DOMContentLoaded', loadRecent);
</script>
@endpush
