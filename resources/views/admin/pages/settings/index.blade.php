@extends('admin.layouts.app')
@section('title', 'Settings')

@section('content')
<div class="space-y-6">

{{-- Header --}}
<div>
    <h1 class="text-2xl font-bold text-gray-900">Settings</h1>
    <p class="text-sm text-gray-500 mt-0.5">Manage your store configuration</p>
</div>

<div class="flex gap-6">

{{-- Left Sidebar Nav --}}
<div class="w-56 flex-shrink-0">
    <nav class="bg-white rounded-xl border border-gray-200 overflow-hidden">
        @foreach([
            ['general',      '🏪', 'General'],
            ['store',        '🛒', 'Store'],
            ['payment',      '💳', 'Payment'],
            ['shipping',     '🚚', 'Shipping'],
            ['tax',          '🧾', 'Tax'],
            ['notifications','🔔', 'Notifications'],
            ['seo',          '🔍', 'SEO'],
            ['advanced',     '⚙️', 'Advanced'],
        ] as [$key, $icon, $label])
        <button onclick="switchSection('{{$key}}')" id="nav-{{$key}}"
                class="settings-nav w-full flex items-center gap-3 px-4 py-3 text-sm font-medium text-left transition-colors border-b border-gray-100 last:border-0
                       {{ $key === 'general' ? 'bg-blue-50 text-[#0082C3] border-l-2 border-l-[#0082C3]' : 'text-gray-700 hover:bg-gray-50' }}">
            <span>{{$icon}}</span>
            {{$label}}
        </button>
        @endforeach
    </nav>
</div>

{{-- Right Content --}}
<div class="flex-1 min-w-0">

{{-- ── GENERAL ── --}}
<div id="section-general" class="settings-section bg-white rounded-xl border border-gray-200 p-6 space-y-5">
    <div class="flex items-center justify-between border-b border-gray-100 pb-4">
        <div><h2 class="text-base font-semibold text-gray-900">General Settings</h2><p class="text-xs text-gray-500 mt-0.5">Basic store information</p></div>
        <button onclick="saveSection('general')" class="px-4 py-2 bg-[#0082C3] text-white text-sm font-semibold rounded-lg hover:bg-[#006ba3] transition-colors">Save</button>
    </div>
    <div class="grid grid-cols-2 gap-5">
        <div class="col-span-2">
            <label class="block text-sm font-medium text-gray-700 mb-1">Store Name <span class="text-red-500">*</span></label>
            <input name="general_store_name" type="text" value="{{ $settings['general']['general_store_name'] ?? 'Decathlon' }}"
                   class="w-full px-3.5 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#0082C3]">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Store Email</label>
            <input name="general_store_email" type="email" value="{{ $settings['general']['general_store_email'] ?? 'hello@decathlon.com' }}"
                   class="w-full px-3.5 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#0082C3]">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Store Phone</label>
            <input name="general_store_phone" type="text" value="{{ $settings['general']['general_store_phone'] ?? '+91 98765 43210' }}"
                   class="w-full px-3.5 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#0082C3]">
        </div>
        <div class="col-span-2">
            <label class="block text-sm font-medium text-gray-700 mb-1">Store Address</label>
            <textarea name="general_store_address" rows="2"
                      class="w-full px-3.5 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#0082C3]">{{ $settings['general']['general_store_address'] ?? 'Mumbai, Maharashtra, India' }}</textarea>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Currency</label>
            <select name="general_currency" class="w-full px-3.5 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#0082C3]">
                @foreach(['INR'=>'₹ Indian Rupee (INR)','USD'=>'$ US Dollar (USD)','EUR'=>'€ Euro (EUR)','GBP'=>'£ British Pound (GBP)'] as $code=>$label)
                <option value="{{$code}}" {{ ($settings['general']['general_currency'] ?? 'INR') === $code ? 'selected' : '' }}>{{$label}}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Timezone</label>
            <select name="general_timezone" class="w-full px-3.5 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#0082C3]">
                @foreach(['Asia/Kolkata'=>'Asia/Kolkata (IST)','UTC'=>'UTC','America/New_York'=>'America/New_York','Europe/London'=>'Europe/London'] as $tz=>$label)
                <option value="{{$tz}}" {{ ($settings['general']['general_timezone'] ?? 'Asia/Kolkata') === $tz ? 'selected' : '' }}>{{$label}}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Date Format</label>
            <select name="general_date_format" class="w-full px-3.5 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#0082C3]">
                @foreach(['d/m/Y'=>'DD/MM/YYYY','m/d/Y'=>'MM/DD/YYYY','Y-m-d'=>'YYYY-MM-DD','d M Y'=>'DD Mon YYYY'] as $fmt=>$label)
                <option value="{{$fmt}}" {{ ($settings['general']['general_date_format'] ?? 'd/m/Y') === $fmt ? 'selected' : '' }}>{{$label}}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Language</label>
            <select name="general_language" class="w-full px-3.5 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#0082C3]">
                <option value="en" {{ ($settings['general']['general_language'] ?? 'en') === 'en' ? 'selected' : '' }}>English</option>
                <option value="hi" {{ ($settings['general']['general_language'] ?? 'en') === 'hi' ? 'selected' : '' }}>Hindi</option>
            </select>
        </div>
    </div>
</div>

{{-- ── STORE ── --}}
<div id="section-store" class="settings-section hidden bg-white rounded-xl border border-gray-200 p-6 space-y-5">
    <div class="flex items-center justify-between border-b border-gray-100 pb-4">
        <div><h2 class="text-base font-semibold text-gray-900">Store Settings</h2><p class="text-xs text-gray-500 mt-0.5">Customer experience and store behaviour</p></div>
        <button onclick="saveSection('store')" class="px-4 py-2 bg-[#0082C3] text-white text-sm font-semibold rounded-lg hover:bg-[#006ba3] transition-colors">Save</button>
    </div>
    <div class="space-y-4">
        @foreach([
            ['store_maintenance_mode','Maintenance Mode','Put store in maintenance mode — customers will see a maintenance page'],
            ['store_guest_checkout','Guest Checkout','Allow customers to checkout without creating an account'],
            ['store_reviews_enabled','Product Reviews','Allow customers to submit product reviews'],
            ['store_wishlist_enabled','Wishlist','Allow customers to save products to wishlist'],
            ['store_compare_enabled','Product Compare','Allow customers to compare products'],
        ] as [$key,$label,$desc])
        <label class="flex items-center justify-between p-4 border border-gray-200 rounded-xl hover:bg-gray-50 cursor-pointer transition-colors">
            <div>
                <p class="text-sm font-medium text-gray-800">{{$label}}</p>
                <p class="text-xs text-gray-500 mt-0.5">{{$desc}}</p>
            </div>
            <div class="relative ml-4">
                <input type="checkbox" name="{{$key}}" value="1" {{ ($settings['store'][$key] ?? '0') === '1' ? 'checked' : '' }}
                       class="sr-only peer" id="toggle-{{$key}}">
                <label for="toggle-{{$key}}" class="w-11 h-6 bg-gray-200 peer-checked:bg-[#0082C3] rounded-full cursor-pointer transition-colors block after:content-[''] after:absolute after:top-0.5 after:left-0.5 after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:after:translate-x-5"></label>
            </div>
        </label>
        @endforeach
        <div class="grid grid-cols-2 gap-4 pt-2">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Products Per Page</label>
                <input name="store_products_per_page" type="number" min="4" max="100" value="{{ $settings['store']['store_products_per_page'] ?? '24' }}"
                       class="w-full px-3.5 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#0082C3]">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Default Sort Order</label>
                <select name="store_default_sort" class="w-full px-3.5 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#0082C3]">
                    @foreach(['newest'=>'Newest First','price_asc'=>'Price: Low to High','price_desc'=>'Price: High to Low','popular'=>'Most Popular','rating'=>'Highest Rated'] as $v=>$l)
                    <option value="{{$v}}" {{ ($settings['store']['store_default_sort'] ?? 'newest') === $v ? 'selected' : '' }}>{{$l}}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Low Stock Threshold</label>
                <input name="store_low_stock_threshold" type="number" min="1" value="{{ $settings['store']['store_low_stock_threshold'] ?? '5' }}"
                       class="w-full px-3.5 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#0082C3]">
                <p class="text-xs text-gray-400 mt-1">Alert when stock falls below this number</p>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Order Prefix</label>
                <input name="store_order_prefix" type="text" value="{{ $settings['store']['store_order_prefix'] ?? 'DEC-' }}"
                       class="w-full px-3.5 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#0082C3]">
                <p class="text-xs text-gray-400 mt-1">e.g. DEC-10001</p>
            </div>
        </div>
    </div>
</div>

{{-- ── PAYMENT ── --}}
<div id="section-payment" class="settings-section hidden bg-white rounded-xl border border-gray-200 p-6 space-y-5">
    <div class="flex items-center justify-between border-b border-gray-100 pb-4">
        <div><h2 class="text-base font-semibold text-gray-900">Payment Settings</h2><p class="text-xs text-gray-500 mt-0.5">Configure payment methods</p></div>
        <button onclick="saveSection('payment')" class="px-4 py-2 bg-[#0082C3] text-white text-sm font-semibold rounded-lg hover:bg-[#006ba3] transition-colors">Save</button>
    </div>
    <div class="space-y-4">
        {{-- COD --}}
        <div class="border border-gray-200 rounded-xl p-4">
            <div class="flex items-center justify-between mb-3">
                <div class="flex items-center gap-3">
                    <span class="text-2xl">💵</span>
                    <div><p class="text-sm font-semibold text-gray-800">Cash on Delivery</p><p class="text-xs text-gray-500">Collect payment when order is delivered</p></div>
                </div>
                <div class="relative">
                    <input type="checkbox" name="payment_cod_enabled" value="1" {{ ($settings['payment']['payment_cod_enabled'] ?? '1') === '1' ? 'checked' : '' }} class="sr-only peer" id="tog-cod">
                    <label for="tog-cod" class="w-11 h-6 bg-gray-200 peer-checked:bg-[#0082C3] rounded-full cursor-pointer transition-colors block after:content-[''] after:absolute after:top-0.5 after:left-0.5 after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:after:translate-x-5"></label>
                </div>
            </div>
            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1">COD Charge (₹)</label>
                    <input name="payment_cod_charge" type="number" min="0" value="{{ $settings['payment']['payment_cod_charge'] ?? '0' }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#0082C3]">
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1">Min Order for COD (₹)</label>
                    <input name="payment_cod_min_order" type="number" min="0" value="{{ $settings['payment']['payment_cod_min_order'] ?? '0' }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#0082C3]">
                </div>
            </div>
        </div>
        {{-- Online Payment --}}
        <div class="border border-gray-200 rounded-xl p-4">
            <div class="flex items-center justify-between mb-3">
                <div class="flex items-center gap-3">
                    <span class="text-2xl">💳</span>
                    <div><p class="text-sm font-semibold text-gray-800">Online Payment (Razorpay)</p><p class="text-xs text-gray-500">UPI, Cards, Net Banking, Wallets</p></div>
                </div>
                <div class="relative">
                    <input type="checkbox" name="payment_online_enabled" value="1" {{ ($settings['payment']['payment_online_enabled'] ?? '0') === '1' ? 'checked' : '' }} class="sr-only peer" id="tog-online">
                    <label for="tog-online" class="w-11 h-6 bg-gray-200 peer-checked:bg-[#0082C3] rounded-full cursor-pointer transition-colors block after:content-[''] after:absolute after:top-0.5 after:left-0.5 after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:after:translate-x-5"></label>
                </div>
            </div>
            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1">Razorpay Key ID</label>
                    <input name="payment_razorpay_key" type="text" value="{{ $settings['payment']['payment_razorpay_key'] ?? '' }}" placeholder="rzp_live_..."
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#0082C3]">
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1">Razorpay Secret</label>
                    <input name="payment_razorpay_secret" type="password" value="{{ $settings['payment']['payment_razorpay_secret'] ?? '' }}" placeholder="••••••••"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#0082C3]">
                </div>
            </div>
        </div>
        {{-- Wallet --}}
        <div class="border border-gray-200 rounded-xl p-4 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <span class="text-2xl">👛</span>
                <div><p class="text-sm font-semibold text-gray-800">Store Wallet / Credits</p><p class="text-xs text-gray-500">Allow customers to use store credits</p></div>
            </div>
            <div class="relative">
                <input type="checkbox" name="payment_wallet_enabled" value="1" {{ ($settings['payment']['payment_wallet_enabled'] ?? '0') === '1' ? 'checked' : '' }} class="sr-only peer" id="tog-wallet">
                <label for="tog-wallet" class="w-11 h-6 bg-gray-200 peer-checked:bg-[#0082C3] rounded-full cursor-pointer transition-colors block after:content-[''] after:absolute after:top-0.5 after:left-0.5 after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:after:translate-x-5"></label>
            </div>
        </div>
    </div>
</div>

{{-- ── SHIPPING ── --}}
<div id="section-shipping" class="settings-section hidden bg-white rounded-xl border border-gray-200 p-6 space-y-5">
    <div class="flex items-center justify-between border-b border-gray-100 pb-4">
        <div><h2 class="text-base font-semibold text-gray-900">Shipping Settings</h2><p class="text-xs text-gray-500 mt-0.5">Delivery charges and shipping rules</p></div>
        <button onclick="saveSection('shipping')" class="px-4 py-2 bg-[#0082C3] text-white text-sm font-semibold rounded-lg hover:bg-[#006ba3] transition-colors">Save</button>
    </div>
    <div class="space-y-4">
        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Standard Shipping Charge (₹)</label>
                <input name="shipping_standard_charge" type="number" min="0" value="{{ $settings['shipping']['shipping_standard_charge'] ?? '49' }}"
                       class="w-full px-3.5 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#0082C3]">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Free Shipping Above (₹)</label>
                <input name="shipping_free_above" type="number" min="0" value="{{ $settings['shipping']['shipping_free_above'] ?? '499' }}"
                       class="w-full px-3.5 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#0082C3]">
                <p class="text-xs text-gray-400 mt-1">0 = always charge shipping</p>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Estimated Delivery (days)</label>
                <input name="shipping_delivery_days" type="text" value="{{ $settings['shipping']['shipping_delivery_days'] ?? '3-5' }}"
                       class="w-full px-3.5 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#0082C3]" placeholder="e.g. 3-5">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Weight Unit</label>
                <select name="shipping_weight_unit" class="w-full px-3.5 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#0082C3]">
                    @foreach(['kg'=>'Kilograms (kg)','g'=>'Grams (g)','lb'=>'Pounds (lb)'] as $v=>$l)
                    <option value="{{$v}}" {{ ($settings['shipping']['shipping_weight_unit'] ?? 'kg') === $v ? 'selected' : '' }}>{{$l}}</option>
                    @endforeach
                </select>
            </div>
        </div>
        {{-- Free Shipping Toggle --}}
        <label class="flex items-center justify-between p-4 border border-gray-200 rounded-xl hover:bg-gray-50 cursor-pointer">
            <div><p class="text-sm font-medium text-gray-800">🚚 Free Shipping Always</p><p class="text-xs text-gray-500">Override all charges — always free shipping</p></div>
            <div class="relative ml-4">
                <input type="checkbox" name="shipping_free_enabled" value="1" {{ ($settings['shipping']['shipping_free_enabled'] ?? '0') === '1' ? 'checked' : '' }} class="sr-only peer" id="tog-free-ship">
                <label for="tog-free-ship" class="w-11 h-6 bg-gray-200 peer-checked:bg-[#0082C3] rounded-full cursor-pointer transition-colors block after:content-[''] after:absolute after:top-0.5 after:left-0.5 after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:after:translate-x-5"></label>
            </div>
        </label>
        {{-- Express --}}
        <div class="border border-gray-200 rounded-xl p-4">
            <div class="flex items-center justify-between mb-3">
                <div><p class="text-sm font-semibold text-gray-800">⚡ Express Delivery</p><p class="text-xs text-gray-500">Same day / next day delivery option</p></div>
                <div class="relative">
                    <input type="checkbox" name="shipping_express_enabled" value="1" {{ ($settings['shipping']['shipping_express_enabled'] ?? '0') === '1' ? 'checked' : '' }} class="sr-only peer" id="tog-express">
                    <label for="tog-express" class="w-11 h-6 bg-gray-200 peer-checked:bg-[#0082C3] rounded-full cursor-pointer transition-colors block after:content-[''] after:absolute after:top-0.5 after:left-0.5 after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:after:translate-x-5"></label>
                </div>
            </div>
            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1">Express Charge (₹)</label>
                    <input name="shipping_express_charge" type="number" min="0" value="{{ $settings['shipping']['shipping_express_charge'] ?? '149' }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#0082C3]">
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1">Express Delivery Time</label>
                    <input name="shipping_express_days" type="text" value="{{ $settings['shipping']['shipping_express_days'] ?? '1' }}" placeholder="e.g. Same Day"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#0082C3]">
                </div>
            </div>
        </div>
    </div>
</div>

{{-- ── TAX ── --}}
<div id="section-tax" class="settings-section hidden bg-white rounded-xl border border-gray-200 p-6 space-y-5">
    <div class="flex items-center justify-between border-b border-gray-100 pb-4">
        <div><h2 class="text-base font-semibold text-gray-900">Tax Settings</h2><p class="text-xs text-gray-500 mt-0.5">GST and tax configuration</p></div>
        <button onclick="saveSection('tax')" class="px-4 py-2 bg-[#0082C3] text-white text-sm font-semibold rounded-lg hover:bg-[#006ba3] transition-colors">Save</button>
    </div>
    <div class="space-y-4">
        <label class="flex items-center justify-between p-4 border border-gray-200 rounded-xl hover:bg-gray-50 cursor-pointer">
            <div><p class="text-sm font-medium text-gray-800">Enable Tax</p><p class="text-xs text-gray-500">Apply tax to orders</p></div>
            <div class="relative ml-4">
                <input type="checkbox" name="tax_enabled" value="1" {{ ($settings['tax']['tax_enabled'] ?? '1') === '1' ? 'checked' : '' }} class="sr-only peer" id="tog-tax">
                <label for="tog-tax" class="w-11 h-6 bg-gray-200 peer-checked:bg-[#0082C3] rounded-full cursor-pointer transition-colors block after:content-[''] after:absolute after:top-0.5 after:left-0.5 after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:after:translate-x-5"></label>
            </div>
        </label>
        <label class="flex items-center justify-between p-4 border border-gray-200 rounded-xl hover:bg-gray-50 cursor-pointer">
            <div><p class="text-sm font-medium text-gray-800">Prices Include Tax (GST Inclusive)</p><p class="text-xs text-gray-500">Product prices already include tax</p></div>
            <div class="relative ml-4">
                <input type="checkbox" name="tax_inclusive" value="1" {{ ($settings['tax']['tax_inclusive'] ?? '1') === '1' ? 'checked' : '' }} class="sr-only peer" id="tog-tax-inc">
                <label for="tog-tax-inc" class="w-11 h-6 bg-gray-200 peer-checked:bg-[#0082C3] rounded-full cursor-pointer transition-colors block after:content-[''] after:absolute after:top-0.5 after:left-0.5 after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:after:translate-x-5"></label>
            </div>
        </label>
        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Default GST Rate (%)</label>
                <select name="tax_default_rate" class="w-full px-3.5 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#0082C3]">
                    @foreach(['0'=>'0% (Exempt)','5'=>'5% GST','12'=>'12% GST','18'=>'18% GST','28'=>'28% GST'] as $v=>$l)
                    <option value="{{$v}}" {{ ($settings['tax']['tax_default_rate'] ?? '18') === $v ? 'selected' : '' }}>{{$l}}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">GSTIN Number</label>
                <input name="tax_gstin" type="text" value="{{ $settings['tax']['tax_gstin'] ?? '' }}" placeholder="22AAAAA0000A1Z5"
                       class="w-full px-3.5 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#0082C3]">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Business Legal Name</label>
                <input name="tax_business_name" type="text" value="{{ $settings['tax']['tax_business_name'] ?? 'Decathlon Sports India Pvt Ltd' }}"
                       class="w-full px-3.5 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#0082C3]">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Tax Display on Invoice</label>
                <select name="tax_display" class="w-full px-3.5 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#0082C3]">
                    <option value="inclusive" {{ ($settings['tax']['tax_display'] ?? 'inclusive') === 'inclusive' ? 'selected' : '' }}>Inclusive (included in price)</option>
                    <option value="exclusive" {{ ($settings['tax']['tax_display'] ?? 'inclusive') === 'exclusive' ? 'selected' : '' }}>Exclusive (added on top)</option>
                </select>
            </div>
        </div>
    </div>
</div>

{{-- ── NOTIFICATIONS ── --}}
<div id="section-notifications" class="settings-section hidden bg-white rounded-xl border border-gray-200 p-6 space-y-5">
    <div class="flex items-center justify-between border-b border-gray-100 pb-4">
        <div><h2 class="text-base font-semibold text-gray-900">Notification Settings</h2><p class="text-xs text-gray-500 mt-0.5">Admin alert preferences</p></div>
        <button onclick="saveSection('notifications')" class="px-4 py-2 bg-[#0082C3] text-white text-sm font-semibold rounded-lg hover:bg-[#006ba3] transition-colors">Save</button>
    </div>
    <div class="space-y-3">
        @foreach([
            ['notif_new_order','🛒','New Order','Get notified when a new order is placed'],
            ['notif_low_stock','📦','Low Stock Alert','Get notified when product stock is low'],
            ['notif_new_review','⭐','New Review','Get notified when a customer submits a review'],
            ['notif_new_customer','👤','New Customer','Get notified when a new customer registers'],
        ] as [$key,$icon,$label,$desc])
        <label class="flex items-center justify-between p-4 border border-gray-200 rounded-xl hover:bg-gray-50 cursor-pointer">
            <div class="flex items-center gap-3">
                <span class="text-xl">{{$icon}}</span>
                <div><p class="text-sm font-medium text-gray-800">{{$label}}</p><p class="text-xs text-gray-500">{{$desc}}</p></div>
            </div>
            <div class="relative ml-4">
                <input type="checkbox" name="{{$key}}" value="1" {{ ($settings['notifications'][$key] ?? '1') === '1' ? 'checked' : '' }} class="sr-only peer" id="tog-{{$key}}">
                <label for="tog-{{$key}}" class="w-11 h-6 bg-gray-200 peer-checked:bg-[#0082C3] rounded-full cursor-pointer transition-colors block after:content-[''] after:absolute after:top-0.5 after:left-0.5 after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:after:translate-x-5"></label>
            </div>
        </label>
        @endforeach
        <div class="pt-2">
            <label class="block text-sm font-medium text-gray-700 mb-1">Admin Notification Email</label>
            <input name="notif_admin_email" type="email" value="{{ $settings['notifications']['notif_admin_email'] ?? 'admin@decathlon.com' }}"
                   class="w-full px-3.5 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#0082C3]">
            <p class="text-xs text-gray-400 mt-1">All admin notifications will be sent to this email</p>
        </div>
    </div>
</div>

{{-- ── SEO ── --}}
<div id="section-seo" class="settings-section hidden bg-white rounded-xl border border-gray-200 p-6 space-y-5">
    <div class="flex items-center justify-between border-b border-gray-100 pb-4">
        <div><h2 class="text-base font-semibold text-gray-900">SEO Settings</h2><p class="text-xs text-gray-500 mt-0.5">Search engine optimization defaults</p></div>
        <button onclick="saveSection('seo')" class="px-4 py-2 bg-[#0082C3] text-white text-sm font-semibold rounded-lg hover:bg-[#006ba3] transition-colors">Save</button>
    </div>
    <div class="space-y-4">
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Default Meta Title</label>
            <input name="seo_meta_title" type="text" maxlength="60" value="{{ $settings['seo']['seo_meta_title'] ?? 'Decathlon — Sports Equipment & Gear India' }}"
                   class="w-full px-3.5 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#0082C3]">
            <p class="text-xs text-gray-400 mt-1">Max 60 characters</p>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Default Meta Description</label>
            <textarea name="seo_meta_description" rows="3" maxlength="160"
                      class="w-full px-3.5 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#0082C3]">{{ $settings['seo']['seo_meta_description'] ?? 'Shop sports equipment, apparel and footwear at Decathlon India. Best prices on running, cycling, football, yoga and more.' }}</textarea>
            <p class="text-xs text-gray-400 mt-1">Max 160 characters</p>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Default Meta Keywords</label>
            <input name="seo_meta_keywords" type="text" value="{{ $settings['seo']['seo_meta_keywords'] ?? 'sports equipment, decathlon india, running shoes, cycling gear' }}"
                   class="w-full px-3.5 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#0082C3]">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Google Analytics ID</label>
            <input name="seo_google_analytics" type="text" value="{{ $settings['seo']['seo_google_analytics'] ?? '' }}" placeholder="G-XXXXXXXXXX"
                   class="w-full px-3.5 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#0082C3]">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Google Search Console Verification</label>
            <input name="seo_google_verification" type="text" value="{{ $settings['seo']['seo_google_verification'] ?? '' }}" placeholder="google-site-verification=..."
                   class="w-full px-3.5 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#0082C3]">
        </div>
        <div class="grid grid-cols-2 gap-4">
            <label class="flex items-center justify-between p-4 border border-gray-200 rounded-xl hover:bg-gray-50 cursor-pointer">
                <div><p class="text-sm font-medium text-gray-800">XML Sitemap</p><p class="text-xs text-gray-500">Auto-generate sitemap.xml</p></div>
                <div class="relative ml-4">
                    <input type="checkbox" name="seo_sitemap_enabled" value="1" {{ ($settings['seo']['seo_sitemap_enabled'] ?? '1') === '1' ? 'checked' : '' }} class="sr-only peer" id="tog-sitemap">
                    <label for="tog-sitemap" class="w-11 h-6 bg-gray-200 peer-checked:bg-[#0082C3] rounded-full cursor-pointer transition-colors block after:content-[''] after:absolute after:top-0.5 after:left-0.5 after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:after:translate-x-5"></label>
                </div>
            </label>
            <label class="flex items-center justify-between p-4 border border-gray-200 rounded-xl hover:bg-gray-50 cursor-pointer">
                <div><p class="text-sm font-medium text-gray-800">Noindex (Dev Mode)</p><p class="text-xs text-gray-500">Prevent search engine indexing</p></div>
                <div class="relative ml-4">
                    <input type="checkbox" name="seo_robots_noindex" value="1" {{ ($settings['seo']['seo_robots_noindex'] ?? '0') === '1' ? 'checked' : '' }} class="sr-only peer" id="tog-noindex">
                    <label for="tog-noindex" class="w-11 h-6 bg-gray-200 peer-checked:bg-[#0082C3] rounded-full cursor-pointer transition-colors block after:content-[''] after:absolute after:top-0.5 after:left-0.5 after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:after:translate-x-5"></label>
                </div>
            </label>
        </div>
    </div>
</div>

{{-- ── ADVANCED ── --}}
<div id="section-advanced" class="settings-section hidden bg-white rounded-xl border border-gray-200 p-6 space-y-5">
    <div class="flex items-center justify-between border-b border-gray-100 pb-4">
        <div><h2 class="text-base font-semibold text-gray-900">Advanced Settings</h2><p class="text-xs text-gray-500 mt-0.5">Developer and system settings</p></div>
        <button onclick="saveSection('advanced')" class="px-4 py-2 bg-[#0082C3] text-white text-sm font-semibold rounded-lg hover:bg-[#006ba3] transition-colors">Save</button>
    </div>
    <div class="space-y-4">
        <div class="p-4 bg-yellow-50 border border-yellow-200 rounded-xl">
            <p class="text-sm font-semibold text-yellow-800">⚠️ Caution</p>
            <p class="text-xs text-yellow-700 mt-1">These settings affect system performance and behaviour. Change only if you know what you're doing.</p>
        </div>
        @foreach([
            ['advanced_debug_mode','🐛','Debug Mode','Show detailed error messages (disable in production)'],
            ['advanced_cache_enabled','⚡','Cache Enabled','Enable application caching for better performance'],
        ] as [$key,$icon,$label,$desc])
        <label class="flex items-center justify-between p-4 border border-gray-200 rounded-xl hover:bg-gray-50 cursor-pointer">
            <div class="flex items-center gap-3">
                <span class="text-xl">{{$icon}}</span>
                <div><p class="text-sm font-medium text-gray-800">{{$label}}</p><p class="text-xs text-gray-500">{{$desc}}</p></div>
            </div>
            <div class="relative ml-4">
                <input type="checkbox" name="{{$key}}" value="1" {{ ($settings['advanced'][$key] ?? '0') === '1' ? 'checked' : '' }} class="sr-only peer" id="tog-{{$key}}">
                <label for="tog-{{$key}}" class="w-11 h-6 bg-gray-200 peer-checked:bg-[#0082C3] rounded-full cursor-pointer transition-colors block after:content-[''] after:absolute after:top-0.5 after:left-0.5 after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:after:translate-x-5"></label>
            </div>
        </label>
        @endforeach
        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Items Per Page (API)</label>
                <input name="advanced_api_per_page" type="number" min="5" max="100" value="{{ $settings['advanced']['advanced_api_per_page'] ?? '20' }}"
                       class="w-full px-3.5 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#0082C3]">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Session Lifetime (minutes)</label>
                <input name="advanced_session_lifetime" type="number" min="30" value="{{ $settings['advanced']['advanced_session_lifetime'] ?? '120' }}"
                       class="w-full px-3.5 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#0082C3]">
            </div>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Custom CSS</label>
            <textarea name="advanced_custom_css" rows="4" placeholder="/* Add custom CSS here */"
                      class="w-full px-3.5 py-2.5 border border-gray-300 rounded-lg text-sm font-mono focus:outline-none focus:ring-2 focus:ring-[#0082C3]">{{ $settings['advanced']['advanced_custom_css'] ?? '' }}</textarea>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Custom JS (Footer)</label>
            <textarea name="advanced_custom_js" rows="4" placeholder="// Add custom JavaScript here"
                      class="w-full px-3.5 py-2.5 border border-gray-300 rounded-lg text-sm font-mono focus:outline-none focus:ring-2 focus:ring-[#0082C3]">{{ $settings['advanced']['advanced_custom_js'] ?? '' }}</textarea>
        </div>
    </div>
</div>

</div>{{-- end right content --}}
</div>{{-- end flex --}}
</div>{{-- end space-y-6 --}}

{{-- Toast --}}
<div id="toast" class="hidden fixed top-5 right-5 z-[9999] px-5 py-3 rounded-xl shadow-xl text-white text-sm font-medium"></div>

@endsection

@push('scripts')
<script>
const CSRF = document.querySelector('meta[name="csrf-token"]').content;

function toast(msg, type='success') {
    const el = document.getElementById('toast');
    el.textContent = msg;
    el.className = `fixed top-5 right-5 z-[9999] px-5 py-3 rounded-xl shadow-xl text-white text-sm font-medium
        ${type==='success'?'bg-green-600':type==='error'?'bg-red-600':'bg-yellow-500'}`;
    el.classList.remove('hidden');
    setTimeout(()=>el.classList.add('hidden'), 3000);
}

// ── Section Switch ────────────────────────────────────────────────
function switchSection(name) {
    // Hide all sections
    document.querySelectorAll('.settings-section').forEach(s => s.classList.add('hidden'));
    // Show target
    document.getElementById('section-' + name).classList.remove('hidden');
    // Update nav
    document.querySelectorAll('.settings-nav').forEach(btn => {
        const isActive = btn.id === 'nav-' + name;
        btn.className = `settings-nav w-full flex items-center gap-3 px-4 py-3 text-sm font-medium text-left transition-colors border-b border-gray-100 last:border-0
            ${isActive ? 'bg-blue-50 text-[#0082C3] border-l-2 border-l-[#0082C3]' : 'text-gray-700 hover:bg-gray-50'}`;
    });
}

// ── Save Section ──────────────────────────────────────────────────
async function saveSection(group) {
    const section = document.getElementById('section-' + group);
    const inputs  = section.querySelectorAll('input, select, textarea');
    const data    = {};

    inputs.forEach(el => {
        if (!el.name) return;
        if (el.type === 'checkbox') {
            data[el.name] = el.checked ? '1' : '0';
        } else {
            data[el.name] = el.value;
        }
    });

    const btn = section.querySelector('button[onclick]');
    if (btn) { btn.disabled = true; btn.textContent = 'Saving…'; }

    try {
        const r = await fetch(`/admin/settings/${group}`, {
            method: 'POST',
            credentials: 'same-origin',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': CSRF,
            },
            body: JSON.stringify(data),
        });
        const res = await r.json();
        if (res.success) {
            toast(res.message || 'Settings saved!');
        } else {
            toast(res.message || 'Failed to save', 'error');
        }
    } catch(e) {
        toast('Error: ' + e.message, 'error');
    } finally {
        if (btn) { btn.disabled = false; btn.textContent = 'Save'; }
    }
}

// ── URL hash navigation ───────────────────────────────────────────
document.addEventListener('DOMContentLoaded', () => {
    const hash = window.location.hash.replace('#','') || 'general';
    const valid = ['general','store','payment','shipping','tax','notifications','seo','advanced'];
    switchSection(valid.includes(hash) ? hash : 'general');
});

document.querySelectorAll('.settings-nav').forEach(btn => {
    btn.addEventListener('click', () => {
        const section = btn.id.replace('nav-','');
        window.location.hash = section;
    });
});
</script>
@endpush
