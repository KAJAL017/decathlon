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
            ['login_methods','🔐', 'Login Methods'],
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
        <div class="col-span-2">
            <label class="block text-sm font-medium text-gray-700 mb-1">
                Website URL (App URL)
                <span class="ml-1 text-xs text-gray-400 font-normal">— used for Google OAuth, emails, links</span>
            </label>
            <div class="flex items-center gap-2">
                <input name="app_url" id="appUrlInput" type="url"
                    value="{{ \App\Models\Setting::get('app_url', config('app.url', '')) }}"
                    oninput="syncGoogleUri()"
                    placeholder="https://yourdomain.com"
                    class="w-full px-3.5 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#0082C3] font-mono">
                <span class="text-xs text-gray-400 whitespace-nowrap">No trailing /</span>
            </div>
            <p class="text-xs text-gray-400 mt-1">
                Current: <span class="font-mono text-gray-600">{{ config('app.url') }}</span>
                (from .env — this setting overrides it for integrations)
            </p>
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
    </div>
    <div class="space-y-4">
        {{-- Payment methods — managed in Integrations --}}
        <div class="bg-blue-50 border border-blue-200 rounded-xl p-4 flex items-center gap-3">
            <svg class="w-5 h-5 text-blue-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            <div>
                <p class="text-sm font-semibold text-blue-800">Payment methods are managed in Integrations</p>
                <p class="text-xs text-blue-600 mt-0.5">
                    Configure <strong>COD</strong> and <strong>Razorpay</strong> in
                    <a href="{{ route('admin.integrations.index') }}#payments" class="underline font-bold hover:text-blue-800">Integrations → Payments</a> and
                    <a href="{{ route('admin.integrations.index') }}#shipping" class="underline font-bold hover:text-blue-800">Integrations → Shipping</a>
                </p>
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
                <div><p class="text-sm font-medium text-gray-800">Disable Search Indexing</p><p class="text-xs text-gray-500">Prevent search engines from indexing your site (noindex)</p></div>
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

{{-- ── LOGIN METHODS ── --}}
<div id="section-login_methods" class="settings-section hidden bg-white rounded-xl border border-gray-200 p-6 space-y-5">
    <div class="flex items-center justify-between border-b border-gray-100 pb-4">
        <div>
            <h2 class="text-base font-semibold text-gray-900">Login Methods</h2>
            <p class="text-xs text-gray-500 mt-0.5">Control how customers can register and login on your website</p>
        </div>
        <button onclick="saveSection('login_methods')" class="px-4 py-2 bg-[#0082C3] text-white text-sm font-semibold rounded-lg hover:bg-[#006ba3] transition-colors">Save</button>
    </div>

    {{-- Registration --}}
    <div class="space-y-3">
        <h3 class="text-sm font-bold text-gray-800 flex items-center gap-2">
            <span class="w-6 h-6 bg-blue-100 text-blue-700 rounded-lg flex items-center justify-center text-xs font-bold">1</span>
            Customer Registration
        </h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
            <label class="flex items-start gap-3 p-4 border border-gray-200 rounded-xl cursor-pointer hover:border-[#0082C3] hover:bg-blue-50 transition-all has-[:checked]:border-[#0082C3] has-[:checked]:bg-blue-50">
                <input type="radio" name="registration_enabled" value="1" class="mt-0.5 text-[#0082C3] focus:ring-[#0082C3]"
                    {{ \App\Models\Setting::get('registration_enabled', '1') === '1' ? 'checked' : '' }}>
                <div>
                    <p class="text-sm font-semibold text-gray-900">✅ Registration Open</p>
                    <p class="text-xs text-gray-500 mt-0.5">Anyone can create a new account</p>
                </div>
            </label>
            <label class="flex items-start gap-3 p-4 border border-gray-200 rounded-xl cursor-pointer hover:border-red-300 hover:bg-red-50 transition-all has-[:checked]:border-red-400 has-[:checked]:bg-red-50">
                <input type="radio" name="registration_enabled" value="0" class="mt-0.5 text-red-500 focus:ring-red-400"
                    {{ \App\Models\Setting::get('registration_enabled', '1') === '0' ? 'checked' : '' }}>
                <div>
                    <p class="text-sm font-semibold text-gray-900">🔒 Registration Closed</p>
                    <p class="text-xs text-gray-500 mt-0.5">New registrations are disabled</p>
                </div>
            </label>
        </div>
    </div>

    {{-- Login Methods --}}
    <div class="space-y-3">
        <h3 class="text-sm font-bold text-gray-800 flex items-center gap-2">
            <span class="w-6 h-6 bg-blue-100 text-blue-700 rounded-lg flex items-center justify-center text-xs font-bold">2</span>
            Allowed Login Methods
        </h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
            @foreach([
                ['login_method_email',     '📧', 'Email & Password',  'Standard email + password login', '1', null],
                ['login_method_email_otp', '✉️', 'Email + OTP',       'Login via OTP sent to email (no password needed)', '0', 'brevo_api_key'],
                ['login_method_google',    '🔵', 'Google OAuth',      'Sign in with Google account',     '0', 'google_client_id'],
                ['login_method_otp',       '📱', 'Mobile OTP',        'Login via SMS OTP (requires MSG91)', '0', 'msg91_auth_key'],
                ['login_method_guest',     '👤', 'Guest Checkout',    'Allow checkout without account',  '1', null],
            ] as [$key, $icon, $title, $desc, $default, $requires])
            @php $isConfigured = $requires ? \App\Models\Setting::get($requires) : true; @endphp
            <label class="flex items-start gap-3 p-4 border border-gray-200 rounded-xl cursor-pointer hover:border-[#0082C3] hover:bg-blue-50 transition-all has-[:checked]:border-[#0082C3] has-[:checked]:bg-blue-50 {{ !$isConfigured ? 'relative' : '' }}">
                <input type="checkbox" name="{{ $key }}" value="1"
                    class="mt-0.5 rounded text-[#0082C3] focus:ring-[#0082C3]"
                    {{ \App\Models\Setting::get($key, $default) === '1' ? 'checked' : '' }}
                    @if($requires && !$isConfigured)
                    onchange="checkLoginMethodDep(this, '{{ $requires }}', '{{ $title }}')"
                    @endif>
                <div class="flex-1">
                    <p class="text-sm font-semibold text-gray-900">{{ $icon }} {{ $title }}</p>
                    <p class="text-xs text-gray-500 mt-0.5">{{ $desc }}</p>
                    @if($requires && !$isConfigured)
                    <p class="text-xs text-orange-600 mt-1 font-medium">
                        ⚠️ Not configured —
                        <a href="{{ route('admin.integrations.index') }}#marketing" class="underline hover:text-orange-700">Configure in Integrations</a>
                    </p>
                    @endif
                </div>
            </label>
            @endforeach
        </div>
    </div>

    {{-- Google OAuth Config --}}
    <div class="space-y-3 border border-gray-100 rounded-xl p-4 bg-gray-50">
        <h3 class="text-sm font-bold text-gray-700 flex items-center gap-2">
            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="currentColor"><path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" fill="#4285F4"/><path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853"/><path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z" fill="#FBBC05"/><path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" fill="#EA4335"/></svg>
            Google OAuth Configuration
        </h3>
        <p class="text-xs text-gray-500">Required only if Google login is enabled above</p>

        {{-- Redirect URI to copy --}}
        <div class="bg-blue-50 border border-blue-200 rounded-xl p-3 space-y-3">
            <p class="text-xs font-bold text-blue-700 flex items-center gap-1.5">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                Add this Redirect URI in Google Console
            </p>

            {{-- Domain input --}}
            <div class="flex items-center gap-2">
                <div class="flex-1">
                    <label class="block text-[10px] font-semibold text-blue-600 mb-1 uppercase tracking-wider">Your Website Domain</label>
                    <input type="text" id="googleDomainInput"
                        value="{{ \App\Models\Setting::get('app_url', config('app.url', '')) }}"
                        oninput="updateGoogleRedirectUri()"
                        class="w-full px-3 py-2 text-sm border border-blue-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400 bg-white font-mono"
                        placeholder="https://yourdomain.com">
                </div>
            </div>

            {{-- Generated URI --}}
            <div>
                <label class="block text-[10px] font-semibold text-blue-600 mb-1 uppercase tracking-wider">Redirect URI (copy this to Google Console)</label>
                <div class="flex items-center gap-2">
                    <code id="googleRedirectUri"
                        class="flex-1 text-xs bg-white border border-blue-200 rounded-lg px-3 py-2 font-mono text-blue-800 break-all">
                        {{ rtrim(\App\Models\Setting::get('app_url', config('app.url', 'https://yourdomain.com')), '/') }}/auth/google/callback
                    </code>
                    <button type="button" onclick="copyUri('googleRedirectUri')"
                        class="flex-shrink-0 px-3 py-2 bg-blue-600 text-white text-xs font-semibold rounded-lg hover:bg-blue-700 transition-colors flex items-center gap-1.5">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                        Copy
                    </button>
                </div>
            </div>

            <p class="text-[10px] text-blue-500">
                📌 Go to <strong>console.cloud.google.com</strong> → APIs & Services → Credentials → OAuth 2.0 Client → Authorized redirect URIs → paste the URI above
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-xs font-semibold text-gray-600 mb-1.5">Google Client ID</label>
                <input type="text" name="google_client_id"
                    value="{{ \App\Models\Setting::get('google_client_id', '') }}"
                    class="w-full px-3 py-2.5 text-sm border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#0082C3] font-mono"
                    placeholder="xxxx.apps.googleusercontent.com">
            </div>
            <div>
                <label class="block text-xs font-semibold text-gray-600 mb-1.5">Google Client Secret</label>
                <input type="password" name="google_client_secret"
                    value="{{ \App\Models\Setting::get('google_client_secret', '') }}"
                    class="w-full px-3 py-2.5 text-sm border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#0082C3] font-mono"
                    placeholder="GOCSPX-...">
            </div>
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

// ── Copy URI helper ───────────────────────────────────────────────
function copyUri(elementId) {
    const el  = document.getElementById(elementId);
    const txt = el.textContent.trim();
    navigator.clipboard.writeText(txt).then(() => {
        toast('Redirect URI copied!', 'success');
    }).catch(() => {
        const ta = document.createElement('textarea');
        ta.value = txt;
        document.body.appendChild(ta);
        ta.select();
        document.execCommand('copy');
        document.body.removeChild(ta);
        toast('Redirect URI copied!', 'success');
    });
}

// ── Update Google Redirect URI live ──────────────────────────────
function updateGoogleRedirectUri() {
    const domain = document.getElementById('googleDomainInput')?.value?.trim() || '';
    const clean  = domain.replace(/\/+$/, ''); // remove trailing slash
    const uri    = document.getElementById('googleRedirectUri');
    if (uri) uri.textContent = (clean || 'https://yourdomain.com') + '/auth/google/callback';
}

// Sync from General Settings APP_URL field
function syncGoogleUri() {
    const appUrl = document.getElementById('appUrlInput')?.value?.trim() || '';
    const googleInput = document.getElementById('googleDomainInput');
    if (googleInput) googleInput.value = appUrl;
    updateGoogleRedirectUri();
}

// ── Login Method Dependency Check ────────────────────────────────
function checkLoginMethodDep(checkbox, requiresKey, methodName) {
    if (checkbox.checked) {
        // Unchecked it — show warning
        checkbox.checked = false;
        const msg = document.createElement('div');
        msg.className = 'fixed top-5 right-5 z-[9999] bg-orange-500 text-white text-sm font-medium px-5 py-3 rounded-xl shadow-xl max-w-sm';
        msg.innerHTML = '⚠️ <strong>' + methodName + '</strong> requires configuration first. '
            + '<a href="/admin/integrations#marketing" class="underline font-bold">Configure in Integrations →</a>';
        document.body.appendChild(msg);
        setTimeout(() => msg.remove(), 5000);
    }
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
    const valid = ['general','store','payment','shipping','tax','notifications','seo','login_methods','advanced'];
    switchSection(valid.includes(hash) ? hash : 'general');

    // Pre-fill Google domain from APP_URL field
    const appUrl = document.getElementById('appUrlInput')?.value?.trim();
    const googleInput = document.getElementById('googleDomainInput');
    if (googleInput && appUrl) {
        googleInput.value = appUrl;
        updateGoogleRedirectUri();
    }
});

document.querySelectorAll('.settings-nav').forEach(btn => {
    btn.addEventListener('click', () => {
        const section = btn.id.replace('nav-','');
        window.location.hash = section;
    });
});
</script>
@endpush
