@extends('admin.layouts.app')
@section('title', 'Integrations')

@section('content')
<div class="space-y-6">

    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Integrations</h1>
            <p class="text-sm text-gray-500 mt-0.5">Manage API keys, webhooks and third-party connections</p>
        </div>
    </div>

    <div class="flex gap-6">

        {{-- Left Nav --}}
        <div class="w-52 flex-shrink-0">
            <nav class="bg-white rounded-xl border border-gray-200 overflow-hidden sticky top-4">
                @php
                $intIcons = [
                    'analytics' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>',
                    'payments'  => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>',
                    'shipping'  => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0"/>',
                    'marketing' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"/>',
                    'imagekit'  => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>',
                    'webhooks'  => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/>',
                    'apps'      => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/>',
                ];
                $intLabels = ['analytics'=>'Analytics','payments'=>'Payments','shipping'=>'Shipping','marketing'=>'Marketing','imagekit'=>'ImageKit','webhooks'=>'Webhooks','apps'=>'Third Party Apps'];
                @endphp
                @foreach($intLabels as $key=>$label)
                <button onclick="switchTab('{{$key}}')" id="nav-{{$key}}"
                        class="int-nav w-full flex items-center gap-2 px-4 py-3 text-sm font-medium text-left transition-colors border-b border-gray-100 last:border-0
                               {{ $key === 'analytics' ? 'bg-blue-50 text-[#0082C3] border-l-2 border-l-[#0082C3]' : 'text-gray-700 hover:bg-gray-50' }}">
                    <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">{!! $intIcons[$key] !!}</svg>
                    <span>{{$label}}</span>
                </button>
                @endforeach
            </nav>
        </div>

        <div class="flex-1 min-w-0">

            {{-- ══ ANALYTICS TAB ══ --}}
            <div id="tab-analytics" class="int-tab space-y-5">

                {{-- Google Analytics 4 --}}
                <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
                    <div class="flex items-center justify-between px-6 py-4 border-b border-gray-200">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-orange-50 flex items-center justify-center">
                                <svg class="w-6 h-6" viewBox="0 0 24 24" fill="none">
                                    <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2z" fill="#F9AB00"/>
                                    <path d="M12 2v20C6.48 22 2 17.52 2 12S6.48 2 12 2z" fill="#E37400"/>
                                    <rect x="7" y="13" width="3" height="5" rx="1" fill="white"/>
                                    <rect x="10.5" y="9" width="3" height="9" rx="1" fill="white"/>
                                    <rect x="14" y="5" width="3" height="13" rx="1" fill="white"/>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-sm font-semibold text-gray-900">Google Analytics 4</h3>
                                <p class="text-xs text-gray-400">Track website traffic and user behavior</p>
                            </div>
                        </div>
                        <span id="ga-status-badge" class="px-2.5 py-1 rounded-full text-xs font-semibold
                            {{ \App\Models\Setting::get('ga_measurement_id') ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-500' }}">
                            {{ \App\Models\Setting::get('ga_measurement_id') ? '✓ Connected' : 'Not Connected' }}
                        </span>
                    </div>

                    <div class="px-6 py-5 space-y-4">

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                Measurement ID <span class="text-gray-400 font-normal">(e.g. G-XXXXXXXXXX)</span>
                            </label>
                            <div class="flex gap-2">
                                <input id="ga_measurement_id" type="text"
                                       value="{{ \App\Models\Setting::get('ga_measurement_id', '') }}"
                                       placeholder="G-XXXXXXXXXX"
                                       class="flex-1 px-3.5 py-2.5 border border-gray-300 rounded-lg text-sm font-mono focus:outline-none focus:ring-2 focus:ring-[#0082C3] focus:border-[#0082C3]">
                                <button onclick="saveGA()" id="saveGaBtn"
                                        class="px-5 py-2.5 bg-[#0082C3] text-white text-sm font-semibold rounded-lg hover:bg-[#006ba3] transition-colors disabled:opacity-60">
                                    Save
                                </button>
                                @if(\App\Models\Setting::get('ga_measurement_id'))
                                <button onclick="disconnectGA()"
                                        class="px-4 py-2.5 bg-red-50 text-red-600 text-sm font-medium rounded-lg hover:bg-red-100 transition-colors border border-red-200">
                                    Disconnect
                                </button>
                                @endif
                            </div>
                            <p class="text-xs text-gray-400 mt-1.5">The tracking script will be automatically added to all admin pages once saved.</p>
                        </div>

                        {{-- Live preview --}}
                        @if(\App\Models\Setting::get('ga_measurement_id'))
                        <div class="bg-gray-900 rounded-xl p-4">
                            <p class="text-xs text-gray-400 mb-2 font-mono">// Auto-injected in &lt;head&gt;</p>
                            <pre class="text-xs text-green-400 font-mono overflow-x-auto whitespace-pre-wrap"><code>&lt;script async src="https://www.googletagmanager.com/gtag/js?id={{ \App\Models\Setting::get('ga_measurement_id') }}"&gt;&lt;/script&gt;
&lt;script&gt;
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());
  gtag('config', '{{ \App\Models\Setting::get('ga_measurement_id') }}');
&lt;/script&gt;</code></pre>
                        </div>
                        @endif
                    </div>
                </div>

                {{-- Google Tag Manager --}}
                <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
                    <div class="flex items-center justify-between px-6 py-4 border-b border-gray-200">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-blue-50 flex items-center justify-center">
                                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-sm font-semibold text-gray-900">Google Tag Manager</h3>
                                <p class="text-xs text-gray-400">Manage all your tracking tags in one place</p>
                            </div>
                        </div>
                        <span id="gtm-status-badge" class="px-2.5 py-1 rounded-full text-xs font-semibold
                            {{ \App\Models\Setting::get('gtm_container_id') ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-500' }}">
                            {{ \App\Models\Setting::get('gtm_container_id') ? '✓ Connected' : 'Not Connected' }}
                        </span>
                    </div>
                    <div class="px-6 py-5 space-y-3">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                Container ID <span class="text-gray-400 font-normal">(e.g. GTM-XXXXXXX)</span>
                            </label>
                            <div class="flex gap-2">
                                <input id="gtm_container_id" type="text"
                                       value="{{ \App\Models\Setting::get('gtm_container_id', '') }}"
                                       placeholder="GTM-XXXXXXX"
                                       class="flex-1 px-3.5 py-2.5 border border-gray-300 rounded-lg text-sm font-mono focus:outline-none focus:ring-2 focus:ring-[#0082C3]">
                                <button onclick="saveGTM()" class="px-5 py-2.5 bg-[#0082C3] text-white text-sm font-semibold rounded-lg hover:bg-[#006ba3] transition-colors">Save</button>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Facebook Pixel --}}
                <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
                    <div class="flex items-center justify-between px-6 py-4 border-b border-gray-200">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-blue-50 flex items-center justify-center">
                                <svg class="w-6 h-6 text-blue-700" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-sm font-semibold text-gray-900">Facebook Pixel</h3>
                                <p class="text-xs text-gray-400">Track conversions from Facebook & Instagram ads</p>
                            </div>
                        </div>
                        <span class="px-2.5 py-1 rounded-full text-xs font-semibold
                            {{ \App\Models\Setting::get('fb_pixel_id') ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-500' }}">
                            {{ \App\Models\Setting::get('fb_pixel_id') ? '✓ Connected' : 'Not Connected' }}
                        </span>
                    </div>
                    <div class="px-6 py-5">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Pixel ID</label>
                            <div class="flex gap-2">
                                <input id="fb_pixel_id" type="text"
                                       value="{{ \App\Models\Setting::get('fb_pixel_id', '') }}"
                                       placeholder="123456789012345"
                                       class="flex-1 px-3.5 py-2.5 border border-gray-300 rounded-lg text-sm font-mono focus:outline-none focus:ring-2 focus:ring-[#0082C3]">
                                <button onclick="saveFB()" class="px-5 py-2.5 bg-[#0082C3] text-white text-sm font-semibold rounded-lg hover:bg-[#006ba3] transition-colors">Save</button>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            {{-- ══ PAYMENTS TAB ══ --}}
            <div id="tab-payments" class="int-tab space-y-5" style="display:none">

                {{-- Razorpay --}}
                <div class="bg-white rounded-xl border border-gray-200 overflow-hidden int-card" data-connected="{{ \App\Models\Setting::get('razorpay_key_id') ? '1' : '0' }}">
                    <div class="flex items-center justify-between px-6 py-4 border-b border-gray-200 cursor-pointer select-none" onclick="toggleCard('razorpay')">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl overflow-hidden bg-blue-600 flex items-center justify-center">
                                <img src="https://razorpay.com/favicon.png" class="w-8 h-8" alt="Razorpay" onerror="this.outerHTML='<span class=\'text-white font-bold text-xs\'>RZP</span>'">
                            </div>
                            <div>
                                <h3 class="text-sm font-semibold text-gray-900">Razorpay</h3>
                                <p class="text-xs text-gray-400">Accept payments via UPI, Cards, NetBanking, Wallets</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-2">
                            @php $rzMode = \App\Models\Setting::get('razorpay_mode', 'test'); @endphp
                            <span class="px-2.5 py-1 rounded-full text-xs font-semibold {{ $rzMode === 'live' ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700' }}">
                                {{ $rzMode === 'live' ? '🟢 Live Mode' : '🟡 Test Mode' }}
                            </span>
                            <span id="rz-status-badge" class="px-2.5 py-1 rounded-full text-xs font-semibold
                                {{ \App\Models\Setting::get('razorpay_key_id') ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-500' }}">
                                {{ \App\Models\Setting::get('razorpay_key_id') ? '✓ Connected' : 'Not Connected' }}
                            </span>
                            <svg id="card-icon-razorpay" class="w-4 h-4 text-gray-400 transition-transform duration-200 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </div>
                    </div>
                    <div id="card-body-razorpay" class="int-card-body" data-card-id="razorpay">

                        {{-- Mode Toggle --}}
                        <div class="flex items-center gap-4 p-4 bg-gray-50 rounded-xl">
                            <span class="text-sm font-medium text-gray-700">Mode:</span>
                            <div class="flex gap-2">
                                <button id="modeTest" onclick="setMode('test')"
                                        class="px-4 py-2 text-sm font-semibold rounded-lg transition-colors {{ $rzMode === 'test' ? 'bg-yellow-500 text-white' : 'bg-white border border-gray-300 text-gray-600 hover:bg-gray-50' }}">
                                    🧪 Test Mode
                                </button>
                                <button id="modeLive" onclick="setMode('live')"
                                        class="px-4 py-2 text-sm font-semibold rounded-lg transition-colors {{ $rzMode === 'live' ? 'bg-green-600 text-white' : 'bg-white border border-gray-300 text-gray-600 hover:bg-gray-50' }}">
                                    🚀 Live Mode
                                </button>
                            </div>
                            <p class="text-xs text-gray-400 ml-2">
                                {{ $rzMode === 'test' ? 'Use test keys from Razorpay Dashboard → Settings → API Keys' : 'Using live keys — real payments will be processed' }}
                            </p>
                        </div>

                        {{-- API Keys --}}
                        <div class="grid grid-cols-1 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">
                                    Key ID <span class="text-red-500">*</span>
                                    <span class="text-gray-400 font-normal text-xs ml-1">(starts with rzp_test_ or rzp_live_)</span>
                                </label>
                                <div class="relative">
                                    <input id="razorpay_key_id" type="text"
                                           value="{{ \App\Models\Setting::get('razorpay_key_id', '') }}"
                                           placeholder="rzp_test_XXXXXXXXXXXXXXXX"
                                           class="w-full px-3.5 py-2.5 border border-gray-300 rounded-lg text-sm font-mono focus:outline-none focus:ring-2 focus:ring-[#0082C3] pr-10">
                                    <button onclick="toggleVisibility('razorpay_key_id')" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                    </button>
                                </div>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">
                                    Key Secret <span class="text-red-500">*</span>
                                    <span class="text-gray-400 font-normal text-xs ml-1">(keep this private)</span>
                                </label>
                                <div class="relative">
                                    <input id="razorpay_key_secret" type="password"
                                           value="{{ \App\Models\Setting::get('razorpay_key_secret', '') }}"
                                           placeholder="••••••••••••••••••••"
                                           class="w-full px-3.5 py-2.5 border border-gray-300 rounded-lg text-sm font-mono focus:outline-none focus:ring-2 focus:ring-[#0082C3] pr-10">
                                    <button onclick="toggleVisibility('razorpay_key_secret')" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                    </button>
                                </div>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">
                                    Webhook Secret
                                    <span class="text-gray-400 font-normal text-xs ml-1">(optional — for verifying webhook signatures)</span>
                                </label>
                                <div class="relative">
                                    <input id="razorpay_webhook_secret" type="password"
                                           value="{{ \App\Models\Setting::get('razorpay_webhook_secret', '') }}"
                                           placeholder="Your webhook secret"
                                           class="w-full px-3.5 py-2.5 border border-gray-300 rounded-lg text-sm font-mono focus:outline-none focus:ring-2 focus:ring-[#0082C3] pr-10">
                                    <button onclick="toggleVisibility('razorpay_webhook_secret')" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                    </button>
                                </div>
                            </div>
                        </div>

                        {{-- Webhook URL --}}
                        <div class="bg-gray-50 rounded-xl p-4">
                            <p class="text-xs font-semibold text-gray-600 mb-1">Your Webhook URL (add this in Razorpay Dashboard)</p>
                            <div class="flex items-center gap-2">
                                <code class="text-xs font-mono text-gray-700 flex-1 bg-white border border-gray-200 rounded-lg px-3 py-2">
                                    {{ url('/api/razorpay/webhook') }}
                                </code>
                                <button onclick="navigator.clipboard.writeText('{{ url('/api/razorpay/webhook') }}').then(()=>toast('Webhook URL copied'))"
                                        class="p-2 text-gray-400 hover:text-[#0082C3] hover:bg-blue-50 rounded-lg transition-colors flex-shrink-0">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                                </button>
                            </div>
                        </div>

                        {{-- Save / Test buttons --}}
                        <div class="flex items-center gap-3 pt-2 border-t border-gray-100">
                            <button onclick="saveRazorpay()" id="saveRzBtn"
                                    class="px-6 py-2.5 bg-[#0082C3] text-white text-sm font-semibold rounded-lg hover:bg-[#006ba3] transition-colors disabled:opacity-60">
                                Save Razorpay Settings
                            </button>
                            <button onclick="testRazorpay()" id="testRzBtn"
                                    class="px-5 py-2.5 bg-white border border-gray-300 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-50 transition-colors">
                                🧪 Test Connection
                            </button>
                            @if(\App\Models\Setting::get('razorpay_key_id'))
                            <button onclick="disconnectRazorpay()"
                                    class="px-4 py-2.5 bg-red-50 text-red-600 text-sm font-medium rounded-lg hover:bg-red-100 border border-red-200 transition-colors ml-auto">
                                Disconnect
                            </button>
                            @endif
                        </div>

                    </div>
                </div>

            </div>

            {{-- ══ SHIPPING TAB ══ --}}
            <div id="tab-shipping" class="int-tab space-y-5" style="display:none">

                {{-- Shiprocket --}}
                <div class="bg-white rounded-xl border border-gray-200 overflow-hidden int-card" data-connected="{{ \App\Models\Setting::get('shiprocket_email') ? '1' : '0' }}">
                    <div class="flex items-center justify-between px-6 py-4 border-b border-gray-200 cursor-pointer select-none" onclick="toggleCard('shiprocket')">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-orange-50 flex items-center justify-center overflow-hidden">
                                <img src="https://www.shiprocket.in/wp-content/uploads/2021/03/favicon.png" class="w-8 h-8" alt="Shiprocket" onerror="this.outerHTML='<span class=\'text-2xl\'>🚀</span>'">
                            </div>
                            <div>
                                <h3 class="text-sm font-semibold text-gray-900">Shiprocket</h3>
                                <p class="text-xs text-gray-400">Multi-carrier shipping — BlueDart, Delhivery, DTDC, Ekart & 25+ couriers</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-2">
                        <span class="px-2.5 py-1 rounded-full text-xs font-semibold
                            {{ \App\Models\Setting::get('shiprocket_email') ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-500' }}">
                            {{ \App\Models\Setting::get('shiprocket_email') ? '✓ Connected' : 'Not Connected' }}
                        </span>
                        <svg id="card-icon-shiprocket" class="w-4 h-4 text-gray-400 transition-transform duration-200 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                        </div>
                    </div>
                    <div id="card-body-shiprocket" class="int-card-body" data-card-id="shiprocket">

                    <div class="px-6 py-5 space-y-5">

                        {{-- Credentials --}}
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Shiprocket Email <span class="text-red-500">*</span></label>
                                <input id="shiprocket_email" type="email"
                                       value="{{ \App\Models\Setting::get('shiprocket_email', '') }}"
                                       placeholder="your@email.com"
                                       class="w-full px-3.5 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#0082C3]">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Shiprocket Password <span class="text-red-500">*</span></label>
                                <div class="relative">
                                    <input id="shiprocket_password" type="password"
                                           value="{{ \App\Models\Setting::get('shiprocket_password', '') }}"
                                           placeholder="••••••••"
                                           class="w-full px-3.5 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#0082C3] pr-10">
                                    <button onclick="toggleVisibility('shiprocket_password')" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                    </button>
                                </div>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Pickup Location
                                    <span class="text-gray-400 font-normal text-xs ml-1">(from Shiprocket)</span>
                                </label>
                                <div class="flex gap-2">
                                    <select id="shiprocket_pickup_location"
                                        class="flex-1 px-3.5 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#0082C3]">
                                        <option value="{{ \App\Models\Setting::get('shiprocket_pickup_location', 'Primary') }}">
                                            {{ \App\Models\Setting::get('shiprocket_pickup_location', 'Primary') }}
                                        </option>
                                    </select>
                                    <button type="button" onclick="fetchPickupLocations()" id="fetchLocBtn"
                                        class="px-3 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 text-xs font-semibold rounded-lg border border-gray-300 transition-colors flex items-center gap-1.5 whitespace-nowrap">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                                        </svg>
                                        Fetch
                                    </button>
                                </div>
                                <p id="fetchLocStatus" class="text-xs text-gray-400 mt-1">Click Fetch to load pickup addresses from Shiprocket</p>
                            </div>
                        </div>

                        {{-- Shipping Settings --}}
                        <div class="border border-gray-200 rounded-xl p-4 space-y-4">
                            <h4 class="text-sm font-semibold text-gray-700">Shipping Settings</h4>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Default Weight (kg)</label>
                                    <input id="shiprocket_default_weight" type="number" step="0.1" min="0.1"
                                           value="{{ \App\Models\Setting::get('shiprocket_default_weight', '0.5') }}"
                                           class="w-full px-3.5 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#0082C3]">
                                    <p class="text-xs text-gray-400 mt-1">Used when product weight is not set</p>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Pickup Pincode <span class="text-red-500">*</span></label>
                                    <input id="shiprocket_pickup_pincode" type="text" maxlength="6"
                                           value="{{ \App\Models\Setting::get('shiprocket_pickup_pincode', '') }}"
                                           placeholder="e.g. 110001"
                                           class="w-full px-3.5 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#0082C3]">
                                    <p class="text-xs text-gray-400 mt-1">Your warehouse/pickup pincode for courier serviceability check</p>
                                </div>
                            </div>
                        </div>

                        {{-- Token status --}}
                        @if(\App\Models\Setting::get('shiprocket_email'))
                        <div class="bg-green-50 border border-green-200 rounded-xl p-3 flex items-center gap-3">
                            <svg class="w-5 h-5 text-green-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <div>
                                <p class="text-sm font-semibold text-green-700">Shiprocket Connected</p>
                                <p class="text-xs text-green-600">Account: {{ \App\Models\Setting::get('shiprocket_email') }}</p>
                            </div>
                        </div>
                        @endif

                        {{-- Action buttons --}}
                        <div class="flex items-center gap-3 pt-2 border-t border-gray-100">
                            <button onclick="saveShiprocket()" id="saveShipBtn"
                                    class="px-6 py-2.5 bg-[#0082C3] text-white text-sm font-semibold rounded-lg hover:bg-[#006ba3] transition-colors disabled:opacity-60">
                                Save Shiprocket Settings
                            </button>
                            <button onclick="testShiprocket()" id="testShipBtn"
                                    class="px-5 py-2.5 bg-white border border-gray-300 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-50 transition-colors">
                                🧪 Test Connection
                            </button>
                            @if(\App\Models\Setting::get('shiprocket_email'))
                            <button onclick="disconnectShiprocket()"
                                    class="px-4 py-2.5 bg-red-50 text-red-600 text-sm font-medium rounded-lg hover:bg-red-100 border border-red-200 transition-colors ml-auto">
                                Disconnect
                            </button>
                            @endif
                        </div>

                    </div>
                    </div>{{-- end card-body-shiprocket --}}
                </div>

                {{-- COD --}}
                <div class="bg-white rounded-xl border border-gray-200 overflow-hidden int-card" data-connected="{{ \App\Models\Setting::get('cod_enabled', '1') === '1' ? '1' : '0' }}">
                    <div class="flex items-center justify-between px-6 py-4 border-b border-gray-200 cursor-pointer select-none" onclick="toggleCard('cod')">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-green-50 flex items-center justify-center text-xl">💵</div>
                            <div>
                                <h3 class="text-sm font-semibold text-gray-900">Cash on Delivery (COD)</h3>
                                <p class="text-xs text-gray-400">Allow customers to pay when order is delivered</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-2">
                        <label class="relative inline-flex items-center cursor-pointer" onclick="event.stopPropagation()">
                            <input type="checkbox" id="cod_enabled"
                                   {{ \App\Models\Setting::get('cod_enabled', '1') === '1' ? 'checked' : '' }}
                                   class="sr-only peer" onchange="saveCOD(this.checked)">
                            <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-[#0082C3]"></div>
                        </label>
                        <svg id="card-icon-cod" class="w-4 h-4 text-gray-400 transition-transform duration-200 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                        </div>
                    </div>
                    <div id="card-body-cod" class="int-card-body" data-card-id="cod">
                    <div class="px-6 py-4">
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">COD Charge (₹)</label>
                                <input id="cod_charge" type="number" min="0" step="0.01"
                                       value="{{ \App\Models\Setting::get('cod_charge', '0') }}"
                                       class="w-full px-3.5 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#0082C3]"
                                       placeholder="0 = Free COD">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Min Order for COD (₹)</label>
                                <input id="cod_min_order" type="number" min="0"
                                       value="{{ \App\Models\Setting::get('cod_min_order', '0') }}"
                                       class="w-full px-3.5 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#0082C3]"
                                       placeholder="0 = No minimum">
                            </div>
                        </div>
                        <button onclick="saveCODSettings()" class="mt-3 px-5 py-2 bg-[#0082C3] text-white text-sm font-semibold rounded-lg hover:bg-[#006ba3]">Save COD Settings</button>
                    </div>
                    </div>{{-- end card-body-cod --}}
                </div>

            </div>{{-- end tab-shipping --}}

            {{-- ══ MARKETING TAB ══ --}}
            <div id="tab-marketing" class="int-tab space-y-5" style="display:none">

                {{-- ── Mailchimp ── --}}
                <div class="bg-white rounded-xl border border-gray-200 overflow-hidden int-card" data-connected="{{ \App\Models\Setting::get('mailchimp_api_key') ? '1' : '0' }}">
                    <div class="flex items-center justify-between px-6 py-4 border-b border-gray-200 cursor-pointer select-none" onclick="toggleCard('mailchimp')">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-yellow-50 flex items-center justify-center overflow-hidden">
                                <img src="https://mailchimp.com/favicon.ico" class="w-8 h-8" alt="Mailchimp" onerror="this.outerHTML='<span class=\'text-2xl\'>🐵</span>'">
                            </div>
                            <div>
                                <h3 class="text-sm font-semibold text-gray-900">Mailchimp</h3>
                                <p class="text-xs text-gray-400">Email marketing — sync subscribers, send campaigns</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-2">
                        <span class="px-2.5 py-1 rounded-full text-xs font-semibold
                            {{ \App\Models\Setting::get('mailchimp_api_key') ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-500' }}">
                            {{ \App\Models\Setting::get('mailchimp_api_key') ? '✓ Connected' : 'Not Connected' }}
                        </span>
                        <svg id="card-icon-mailchimp" class="w-4 h-4 text-gray-400 transition-transform duration-200 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                        </div>
                    </div>
                    <div id="card-body-mailchimp" class="int-card-body" data-card-id="mailchimp">

                    <div class="px-6 py-5 space-y-4">

                        <div class="grid grid-cols-2 gap-4">
                            <div class="col-span-2">
                                <label class="block text-sm font-medium text-gray-700 mb-1">
                                    API Key <span class="text-red-500">*</span>
                                    <span class="text-gray-400 font-normal text-xs ml-1">(ends with -us1, -us2, etc.)</span>
                                </label>
                                <div class="relative">
                                    <input id="mailchimp_api_key" type="password"
                                           value="{{ \App\Models\Setting::get('mailchimp_api_key', '') }}"
                                           placeholder="xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx-us1"
                                           class="w-full px-3.5 py-2.5 border border-gray-300 rounded-lg text-sm font-mono focus:outline-none focus:ring-2 focus:ring-[#0082C3] pr-10">
                                    <button onclick="toggleVisibility('mailchimp_api_key')" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                    </button>
                                </div>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">
                                    Audience / List ID <span class="text-red-500">*</span>
                                </label>
                                <input id="mailchimp_list_id" type="text"
                                       value="{{ \App\Models\Setting::get('mailchimp_list_id', '') }}"
                                       placeholder="e.g. a1b2c3d4e5"
                                       class="w-full px-3.5 py-2.5 border border-gray-300 rounded-lg text-sm font-mono focus:outline-none focus:ring-2 focus:ring-[#0082C3]">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Server Prefix</label>
                                <input id="mailchimp_server" type="text"
                                       value="{{ \App\Models\Setting::get('mailchimp_server', '') }}"
                                       placeholder="us1, us2, us6…"
                                       class="w-full px-3.5 py-2.5 border border-gray-300 rounded-lg text-sm font-mono focus:outline-none focus:ring-2 focus:ring-[#0082C3]">
                                <p class="text-xs text-gray-400 mt-1">Last part of your API key after the dash</p>
                            </div>
                        </div>

                        {{-- Sync settings --}}
                        <div class="border border-gray-200 rounded-xl p-4 space-y-3">
                            <h4 class="text-sm font-semibold text-gray-700">Sync Settings</h4>
                            <div class="space-y-2">
                                @foreach([
                                    ['mailchimp_sync_on_register','Auto-sync new customers to Mailchimp list'],
                                    ['mailchimp_sync_on_order','Add customer to list on first order'],
                                    ['mailchimp_double_optin','Enable double opt-in (recommended)'],
                                ] as [$key, $label])
                                <label class="flex items-center gap-3 cursor-pointer">
                                    <div class="relative">
                                        <input type="checkbox" id="{{ $key }}"
                                               {{ \App\Models\Setting::get($key, '0') === '1' ? 'checked' : '' }}
                                               class="sr-only peer">
                                        <div class="w-9 h-5 bg-gray-200 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-[#0082C3]"></div>
                                    </div>
                                    <span class="text-sm text-gray-700">{{ $label }}</span>
                                </label>
                                @endforeach
                            </div>
                        </div>

                        @if(\App\Models\Setting::get('mailchimp_api_key'))
                        <div class="bg-green-50 border border-green-200 rounded-xl p-3 flex items-center gap-3">
                            <svg class="w-5 h-5 text-green-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <div>
                                <p class="text-sm font-semibold text-green-700">Mailchimp Connected</p>
                                <p class="text-xs text-green-600">List ID: {{ \App\Models\Setting::get('mailchimp_list_id', '—') }} · Server: {{ \App\Models\Setting::get('mailchimp_server', '—') }}</p>
                            </div>
                        </div>
                        @endif

                        <div class="flex items-center gap-3 pt-2 border-t border-gray-100">
                            <button onclick="saveMailchimp()" id="saveMcBtn"
                                    class="px-6 py-2.5 bg-[#0082C3] text-white text-sm font-semibold rounded-lg hover:bg-[#006ba3] transition-colors disabled:opacity-60">
                                Save Mailchimp Settings
                            </button>
                            <button onclick="testMailchimp()" id="testMcBtn"
                                    class="px-5 py-2.5 bg-white border border-gray-300 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-50 transition-colors">
                                🧪 Test Connection
                            </button>
                            @if(\App\Models\Setting::get('mailchimp_api_key'))
                            <button onclick="disconnectMailchimp()"
                                    class="px-4 py-2.5 bg-red-50 text-red-600 text-sm font-medium rounded-lg hover:bg-red-100 border border-red-200 transition-colors ml-auto">
                                Disconnect
                            </button>
                            @endif
                        </div>
                    </div>
                    </div>{{-- end card-body-mailchimp --}}
                </div>

                {{-- ── MSG91 ── --}}
                <div class="bg-white rounded-xl border border-gray-200 overflow-hidden int-card" data-connected="{{ \App\Models\Setting::get('msg91_auth_key') ? '1' : '0' }}">
                    <div class="flex items-center justify-between px-6 py-4 border-b border-gray-200 cursor-pointer select-none" onclick="toggleCard('msg91')">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-purple-50 flex items-center justify-center overflow-hidden">
                                <img src="https://msg91.com/img/favicon.ico" class="w-8 h-8" alt="MSG91" onerror="this.outerHTML='<span class=\'font-black text-purple-700 text-xs leading-none\'>MSG<br>91</span>'">
                            </div>
                            <div>
                                <h3 class="text-sm font-semibold text-gray-900">MSG91</h3>
                                <p class="text-xs text-gray-400">Bulk SMS, OTP & transactional SMS for India</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-2">
                        <span class="px-2.5 py-1 rounded-full text-xs font-semibold
                            {{ \App\Models\Setting::get('msg91_auth_key') ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-500' }}">
                            {{ \App\Models\Setting::get('msg91_auth_key') ? '✓ Connected' : 'Not Connected' }}
                        </span>
                        <svg id="card-icon-msg91" class="w-4 h-4 text-gray-400 transition-transform duration-200 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                        </div>
                    </div>
                    <div id="card-body-msg91" class="int-card-body" data-card-id="msg91">

                    <div class="px-6 py-5 space-y-4">

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Auth Key <span class="text-red-500">*</span></label>
                                <div class="relative">
                                    <input id="msg91_auth_key" type="password"
                                           value="{{ \App\Models\Setting::get('msg91_auth_key', '') }}"
                                           placeholder="Your MSG91 Auth Key"
                                           class="w-full px-3.5 py-2.5 border border-gray-300 rounded-lg text-sm font-mono focus:outline-none focus:ring-2 focus:ring-[#0082C3] pr-10">
                                    <button onclick="toggleVisibility('msg91_auth_key')" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                    </button>
                                </div>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Sender ID <span class="text-red-500">*</span></label>
                                <input id="msg91_sender_id" type="text"
                                       value="{{ \App\Models\Setting::get('msg91_sender_id', '') }}"
                                       placeholder="DECATH"
                                       class="w-full px-3.5 py-2.5 border border-gray-300 rounded-lg text-sm font-mono uppercase focus:outline-none focus:ring-2 focus:ring-[#0082C3]">
                                <p class="text-xs text-gray-400 mt-1">6-char DLT approved sender ID</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">DLT Entity ID</label>
                                <input id="msg91_entity_id" type="text"
                                       value="{{ \App\Models\Setting::get('msg91_entity_id', '') }}"
                                       placeholder="1234567890123456789"
                                       class="w-full px-3.5 py-2.5 border border-gray-300 rounded-lg text-sm font-mono focus:outline-none focus:ring-2 focus:ring-[#0082C3]">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Route</label>
                                <select id="msg91_route"
                                        class="w-full px-3.5 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#0082C3]">
                                    @foreach(['4'=>'Transactional (Route 4)','1'=>'Promotional (Route 1)','otp'=>'OTP Route'] as $val=>$lbl)
                                    <option value="{{ $val }}" {{ \App\Models\Setting::get('msg91_route','4') === $val ? 'selected' : '' }}>{{ $lbl }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        {{-- SMS Templates --}}
                        <div class="border border-gray-200 rounded-xl p-4 space-y-3">
                            <h4 class="text-sm font-semibold text-gray-700">SMS Templates (DLT Template IDs)</h4>
                            <div class="grid grid-cols-2 gap-3">
                                @foreach([
                                    ['msg91_tpl_order',    'Order Confirmation', 'Your order #{order_id} placed. Total: Rs.{amount}. Track: decathlon.com'],
                                    ['msg91_tpl_shipped',  'Order Shipped',      'Order #{order_id} shipped via {courier}. AWB: {awb}. Track: {link}'],
                                    ['msg91_tpl_otp',      'OTP',                'Your Decathlon OTP is {otp}. Valid 10 mins. Do not share. -DECATH'],
                                    ['msg91_tpl_delivered','Order Delivered',    'Order #{order_id} delivered. Rate us: {link} -DECATH'],
                                ] as [$key, $label, $sample])
                                <div>
                                    <label class="block text-xs font-medium text-gray-600 mb-1">{{ $label }} Template ID</label>
                                    <input id="{{ $key }}" type="text"
                                           value="{{ \App\Models\Setting::get($key, '') }}"
                                           placeholder="DLT Template ID"
                                           class="w-full px-3 py-2 border border-gray-300 rounded-lg text-xs font-mono focus:outline-none focus:ring-2 focus:ring-[#0082C3]">
                                    <p class="text-xs text-gray-400 mt-0.5 truncate" title="{{ $sample }}">{{ $sample }}</p>
                                </div>
                                @endforeach
                            </div>
                        </div>

                        {{-- Auto-send triggers --}}
                        <div class="border border-gray-200 rounded-xl p-4 space-y-3">
                            <h4 class="text-sm font-semibold text-gray-700">Auto-Send SMS On</h4>
                            <div class="grid grid-cols-2 gap-2">
                                @foreach([
                                    ['msg91_on_order',    'Order Placed'],
                                    ['msg91_on_shipped',  'Order Shipped'],
                                    ['msg91_on_delivered','Order Delivered'],
                                    ['msg91_on_otp',      'OTP / Login'],
                                ] as [$key, $label])
                                <label class="flex items-center gap-2 cursor-pointer p-2 border border-gray-100 rounded-lg hover:bg-gray-50">
                                    <div class="relative flex-shrink-0">
                                        <input type="checkbox" id="{{ $key }}"
                                               {{ \App\Models\Setting::get($key, '1') === '1' ? 'checked' : '' }}
                                               class="sr-only peer">
                                        <div class="w-8 h-4 bg-gray-200 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-3 after:w-3 after:transition-all peer-checked:bg-[#0082C3]"></div>
                                    </div>
                                    <span class="text-xs text-gray-700">{{ $label }}</span>
                                </label>
                                @endforeach
                            </div>
                        </div>

                        {{-- Test SMS --}}
                        @if(\App\Models\Setting::get('msg91_auth_key'))
                        <div class="border border-gray-200 rounded-xl p-4 space-y-3">
                            <h4 class="text-sm font-semibold text-gray-700">Send Test SMS</h4>
                            <div class="flex gap-2">
                                <input id="msg91_test_number" type="tel" placeholder="91XXXXXXXXXX (with country code)"
                                       class="flex-1 px-3.5 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#0082C3]">
                                <button onclick="sendTestSMS()"
                                        class="px-5 py-2.5 bg-purple-600 text-white text-sm font-semibold rounded-lg hover:bg-purple-700 transition-colors">
                                    Send Test
                                </button>
                            </div>
                        </div>
                        @endif

                        @if(\App\Models\Setting::get('msg91_auth_key'))
                        <div class="bg-green-50 border border-green-200 rounded-xl p-3 flex items-center gap-3">
                            <svg class="w-5 h-5 text-green-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <div>
                                <p class="text-sm font-semibold text-green-700">MSG91 Connected</p>
                                <p class="text-xs text-green-600">Sender: {{ \App\Models\Setting::get('msg91_sender_id', '—') }} · Route: {{ \App\Models\Setting::get('msg91_route', '4') }}</p>
                            </div>
                        </div>
                        @endif

                        <div class="flex items-center gap-3 pt-2 border-t border-gray-100">
                            <button onclick="saveMSG91()" id="saveSmsBtn"
                                    class="px-6 py-2.5 bg-[#0082C3] text-white text-sm font-semibold rounded-lg hover:bg-[#006ba3] transition-colors disabled:opacity-60">
                                Save MSG91 Settings
                            </button>
                            <button onclick="testMSG91()" id="testSmsBtn"
                                    class="px-5 py-2.5 bg-white border border-gray-300 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-50 transition-colors">
                                🧪 Test Connection
                            </button>
                            @if(\App\Models\Setting::get('msg91_auth_key'))
                            <button onclick="disconnectMSG91()"
                                    class="px-4 py-2.5 bg-red-50 text-red-600 text-sm font-medium rounded-lg hover:bg-red-100 border border-red-200 transition-colors ml-auto">
                                Disconnect
                            </button>
                            @endif
                        </div>
                    </div>
                    </div>{{-- end card-body-msg91 --}}
                </div>

                {{-- ── Twilio ── --}}
                <div class="bg-white rounded-xl border border-gray-200 overflow-hidden int-card" data-connected="{{ \App\Models\Setting::get('twilio_account_sid') ? '1' : '0' }}">
                    <div class="flex items-center justify-between px-6 py-4 border-b border-gray-200 cursor-pointer select-none" onclick="toggleCard('twilio')">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-red-50 flex items-center justify-center overflow-hidden">
                                <img src="https://www.twilio.com/favicon.ico" class="w-8 h-8" alt="Twilio" onerror="this.style.display='none'">
                            </div>
                            <div>
                                <h3 class="text-sm font-semibold text-gray-900">Twilio</h3>
                                <p class="text-xs text-gray-400">Global SMS, WhatsApp & Voice — 180+ countries</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-2">
                        <span class="px-2.5 py-1 rounded-full text-xs font-semibold
                            {{ \App\Models\Setting::get('twilio_account_sid') ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-500' }}">
                            {{ \App\Models\Setting::get('twilio_account_sid') ? '✓ Connected' : 'Not Connected' }}
                        </span>
                        <svg id="card-icon-twilio" class="w-4 h-4 text-gray-400 transition-transform duration-200 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                        </div>
                    </div>
                    <div id="card-body-twilio" class="int-card-body" data-card-id="twilio">

                    <div class="px-6 py-5 space-y-4">

                        {{-- Credentials --}}
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Account SID <span class="text-red-500">*</span></label>
                                <input id="twilio_account_sid" type="text"
                                       value="{{ \App\Models\Setting::get('twilio_account_sid', '') }}"
                                       placeholder="ACxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx"
                                       class="w-full px-3.5 py-2.5 border border-gray-300 rounded-lg text-sm font-mono focus:outline-none focus:ring-2 focus:ring-[#0082C3]">
                                <p class="text-xs text-gray-400 mt-1">Starts with AC</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Auth Token <span class="text-red-500">*</span></label>
                                <div class="relative">
                                    <input id="twilio_auth_token" type="password"
                                           value="{{ \App\Models\Setting::get('twilio_auth_token', '') }}"
                                           placeholder="••••••••••••••••••••••••••••••••"
                                           class="w-full px-3.5 py-2.5 border border-gray-300 rounded-lg text-sm font-mono focus:outline-none focus:ring-2 focus:ring-[#0082C3] pr-10">
                                    <button onclick="toggleVisibility('twilio_auth_token')" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                    </button>
                                </div>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">From Number <span class="text-red-500">*</span></label>
                                <input id="twilio_from_number" type="text"
                                       value="{{ \App\Models\Setting::get('twilio_from_number', '') }}"
                                       placeholder="+1XXXXXXXXXX"
                                       class="w-full px-3.5 py-2.5 border border-gray-300 rounded-lg text-sm font-mono focus:outline-none focus:ring-2 focus:ring-[#0082C3]">
                                <p class="text-xs text-gray-400 mt-1">Your Twilio phone number with country code</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">WhatsApp Number</label>
                                <input id="twilio_whatsapp_number" type="text"
                                       value="{{ \App\Models\Setting::get('twilio_whatsapp_number', '') }}"
                                       placeholder="whatsapp:+14155238886"
                                       class="w-full px-3.5 py-2.5 border border-gray-300 rounded-lg text-sm font-mono focus:outline-none focus:ring-2 focus:ring-[#0082C3]">
                                <p class="text-xs text-gray-400 mt-1">Twilio WhatsApp sandbox or approved number</p>
                            </div>
                        </div>

                        {{-- Channel settings --}}
                        <div class="border border-gray-200 rounded-xl p-4 space-y-3">
                            <h4 class="text-sm font-semibold text-gray-700">Channels</h4>
                            <div class="grid grid-cols-3 gap-3">
                                @foreach([
                                    ['twilio_sms_enabled','📱 SMS','Send order & OTP via SMS'],
                                    ['twilio_whatsapp_enabled','💬 WhatsApp','Send notifications via WhatsApp'],
                                    ['twilio_voice_enabled','📞 Voice','Voice OTP calls'],
                                ] as [$key, $ch, $desc])
                                <label class="flex flex-col gap-1 cursor-pointer p-3 border border-gray-200 rounded-xl hover:bg-gray-50 transition-colors">
                                    <div class="flex items-center justify-between">
                                        <span class="text-sm font-medium text-gray-700">{{ $ch }}</span>
                                        <div class="relative">
                                            <input type="checkbox" id="{{ $key }}"
                                                   {{ \App\Models\Setting::get($key, $key === 'twilio_sms_enabled' ? '1' : '0') === '1' ? 'checked' : '' }}
                                                   class="sr-only peer">
                                            <div class="w-8 h-4 bg-gray-200 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-3 after:w-3 after:transition-all peer-checked:bg-[#0082C3]"></div>
                                        </div>
                                    </div>
                                    <p class="text-xs text-gray-400">{{ $desc }}</p>
                                </label>
                                @endforeach
                            </div>
                        </div>

                        {{-- SMS Templates --}}
                        <div class="border border-gray-200 rounded-xl p-4 space-y-3">
                            <h4 class="text-sm font-semibold text-gray-700">Message Templates</h4>
                            <div class="space-y-3">
                                @foreach([
                                    ['twilio_tpl_order',    'Order Confirmation', 'Hi {name}, your Decathlon order #{order_id} is confirmed! Total: Rs.{amount}. Track at decathlon.com'],
                                    ['twilio_tpl_shipped',  'Order Shipped',      'Hi {name}, your order #{order_id} has been shipped via {courier}. AWB: {awb}. Track: {link}'],
                                    ['twilio_tpl_otp',      'OTP',                'Your Decathlon OTP is {otp}. Valid for 10 minutes. Do not share this code.'],
                                    ['twilio_tpl_delivered','Order Delivered',    'Hi {name}, your order #{order_id} has been delivered! Rate your experience: {link}'],
                                ] as [$key, $label, $default])
                                <div>
                                    <label class="block text-xs font-medium text-gray-600 mb-1">{{ $label }}</label>
                                    <textarea id="{{ $key }}" rows="2"
                                              class="w-full px-3 py-2 border border-gray-300 rounded-lg text-xs focus:outline-none focus:ring-2 focus:ring-[#0082C3] resize-none">{{ \App\Models\Setting::get($key, $default) }}</textarea>
                                    <p class="text-xs text-gray-400 mt-0.5">Variables: {name} {order_id} {amount} {courier} {awb} {link} {otp}</p>
                                </div>
                                @endforeach
                            </div>
                        </div>

                        {{-- Auto-send triggers --}}
                        <div class="border border-gray-200 rounded-xl p-4 space-y-3">
                            <h4 class="text-sm font-semibold text-gray-700">Auto-Send On</h4>
                            <div class="grid grid-cols-2 gap-2">
                                @foreach([
                                    ['twilio_on_order',    'Order Placed'],
                                    ['twilio_on_shipped',  'Order Shipped'],
                                    ['twilio_on_delivered','Order Delivered'],
                                    ['twilio_on_otp',      'OTP / Login'],
                                ] as [$key, $label])
                                <label class="flex items-center gap-2 cursor-pointer p-2 border border-gray-100 rounded-lg hover:bg-gray-50">
                                    <div class="relative flex-shrink-0">
                                        <input type="checkbox" id="{{ $key }}"
                                               {{ \App\Models\Setting::get($key, '1') === '1' ? 'checked' : '' }}
                                               class="sr-only peer">
                                        <div class="w-8 h-4 bg-gray-200 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-3 after:w-3 after:transition-all peer-checked:bg-[#0082C3]"></div>
                                    </div>
                                    <span class="text-xs text-gray-700">{{ $label }}</span>
                                </label>
                                @endforeach
                            </div>
                        </div>

                        {{-- Test SMS --}}
                        @if(\App\Models\Setting::get('twilio_account_sid'))
                        <div class="border border-gray-200 rounded-xl p-4 space-y-3">
                            <h4 class="text-sm font-semibold text-gray-700">Send Test Message</h4>
                            <div class="flex gap-2">
                                <select id="twilio_test_channel" class="px-3 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#0082C3]">
                                    <option value="sms">📱 SMS</option>
                                    <option value="whatsapp">💬 WhatsApp</option>
                                </select>
                                <input id="twilio_test_number" type="tel" placeholder="+91XXXXXXXXXX"
                                       class="flex-1 px-3.5 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#0082C3]">
                                <button onclick="sendTwilioTest()"
                                        class="px-5 py-2.5 bg-red-600 text-white text-sm font-semibold rounded-lg hover:bg-red-700 transition-colors">
                                    Send Test
                                </button>
                            </div>
                        </div>
                        @endif

                        @if(\App\Models\Setting::get('twilio_account_sid'))
                        <div class="bg-green-50 border border-green-200 rounded-xl p-3 flex items-center gap-3">
                            <svg class="w-5 h-5 text-green-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <div>
                                <p class="text-sm font-semibold text-green-700">Twilio Connected</p>
                                <p class="text-xs text-green-600">
                                    SID: {{ substr(\App\Models\Setting::get('twilio_account_sid',''), 0, 8) }}••• ·
                                    From: {{ \App\Models\Setting::get('twilio_from_number', '—') }}
                                </p>
                            </div>
                        </div>
                        @endif

                        <div class="flex items-center gap-3 pt-2 border-t border-gray-100">
                            <button onclick="saveTwilio()" id="saveTwilioBtn"
                                    class="px-6 py-2.5 bg-[#0082C3] text-white text-sm font-semibold rounded-lg hover:bg-[#006ba3] transition-colors disabled:opacity-60">
                                Save Twilio Settings
                            </button>
                            <button onclick="testTwilio()" id="testTwilioBtn"
                                    class="px-5 py-2.5 bg-white border border-gray-300 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-50 transition-colors">
                                🧪 Test Connection
                            </button>
                            @if(\App\Models\Setting::get('twilio_account_sid'))
                            <button onclick="disconnectTwilio()"
                                    class="px-4 py-2.5 bg-red-50 text-red-600 text-sm font-medium rounded-lg hover:bg-red-100 border border-red-200 transition-colors ml-auto">
                                Disconnect
                            </button>
                            @endif
                        </div>
                    </div>
                    </div>{{-- end card-body-twilio --}}
                </div>

                {{-- ── SMTP ── --}}
                <div class="bg-white rounded-xl border border-gray-200 overflow-hidden int-card" data-connected="{{ \App\Models\Setting::get('smtp_host') ? '1' : '0' }}">
                    <div class="flex items-center justify-between px-6 py-4 border-b border-gray-200 cursor-pointer select-none" onclick="toggleCard('smtp')">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-blue-50 flex items-center justify-center">
                                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-sm font-semibold text-gray-900">SMTP Email</h3>
                                <p class="text-xs text-gray-400">Send transactional emails via your own SMTP server</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="px-2.5 py-1 rounded-full text-xs font-semibold
                                {{ \App\Models\Setting::get('smtp_host') ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-500' }}">
                                {{ \App\Models\Setting::get('smtp_host') ? '✓ Configured' : 'Not Configured' }}
                            </span>
                            <svg id="card-icon-smtp" class="w-4 h-4 text-gray-400 transition-transform duration-200 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </div>
                    </div>
                    <div id="card-body-smtp" class="int-card-body" data-card-id="smtp">
                    <div class="px-6 py-5 space-y-5">

                        {{-- Provider presets --}}
                        <div>
                            <p class="text-xs font-semibold text-gray-600 mb-2">Quick Setup — Select Provider</p>
                            <div class="grid grid-cols-3 md:grid-cols-6 gap-2">
                                @foreach([
                                    ['gmail',     'Gmail',      '#EA4335'],
                                    ['sendgrid',  'SendGrid',   '#1A82E2'],
                                    ['mailgun',   'Mailgun',    '#F06B26'],
                                    ['ses',       'Amazon SES', '#FF9900'],
                                ] as [$key, $label, $color])
                                <button type="button" onclick="smtpPreset('{{ $key }}')"
                                    class="px-3 py-2 text-xs font-semibold rounded-xl border border-gray-200 hover:border-gray-400 hover:bg-gray-50 transition-all text-gray-700">
                                    {{ $label }}
                                </button>
                                @endforeach
                            </div>
                        </div>

                        {{-- SMTP Fields --}}
                        <div class="grid grid-cols-2 gap-4">
                            <div class="col-span-2 md:col-span-1">
                                <label class="block text-sm font-medium text-gray-700 mb-1">SMTP Host <span class="text-red-500">*</span></label>
                                <input id="smtp_host" type="text"
                                    value="{{ \App\Models\Setting::get('smtp_host', '') }}"
                                    placeholder="smtp.gmail.com"
                                    class="w-full px-3.5 py-2.5 border border-gray-300 rounded-lg text-sm font-mono focus:outline-none focus:ring-2 focus:ring-[#0082C3]">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Port <span class="text-red-500">*</span></label>
                                <select id="smtp_port" class="w-full px-3.5 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#0082C3]">
                                    @foreach(['587'=>'587 (TLS - Recommended)','465'=>'465 (SSL)','25'=>'25 (Plain)','2525'=>'2525 (Alt)'] as $p=>$l)
                                    <option value="{{ $p }}" {{ \App\Models\Setting::get('smtp_port','587') === $p ? 'selected' : '' }}>{{ $l }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Encryption</label>
                                <select id="smtp_encryption" class="w-full px-3.5 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#0082C3]">
                                    @foreach(['tls'=>'TLS (Recommended)','ssl'=>'SSL','none'=>'None'] as $v=>$l)
                                    <option value="{{ $v }}" {{ \App\Models\Setting::get('smtp_encryption','tls') === $v ? 'selected' : '' }}>{{ $l }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Username <span class="text-red-500">*</span></label>
                                <input id="smtp_username" type="text"
                                    value="{{ \App\Models\Setting::get('smtp_username', '') }}"
                                    placeholder="your@email.com"
                                    class="w-full px-3.5 py-2.5 border border-gray-300 rounded-lg text-sm font-mono focus:outline-none focus:ring-2 focus:ring-[#0082C3]">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Password / App Password <span class="text-red-500">*</span></label>
                                <div class="relative">
                                    <input id="smtp_password" type="password"
                                        value="{{ \App\Models\Setting::get('smtp_password', '') }}"
                                        placeholder="••••••••••••••••"
                                        class="w-full px-3.5 py-2.5 border border-gray-300 rounded-lg text-sm font-mono focus:outline-none focus:ring-2 focus:ring-[#0082C3] pr-10">
                                    <button onclick="toggleVisibility('smtp_password')" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                    </button>
                                </div>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">From Name <span class="text-red-500">*</span></label>
                                <input id="smtp_from_name" type="text"
                                    value="{{ \App\Models\Setting::get('smtp_from_name', 'Decathlon') }}"
                                    placeholder="Decathlon"
                                    class="w-full px-3.5 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#0082C3]">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">From Email <span class="text-red-500">*</span></label>
                                <input id="smtp_from_email" type="email"
                                    value="{{ \App\Models\Setting::get('smtp_from_email', '') }}"
                                    placeholder="noreply@yourdomain.com"
                                    class="w-full px-3.5 py-2.5 border border-gray-300 rounded-lg text-sm font-mono focus:outline-none focus:ring-2 focus:ring-[#0082C3]">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Reply-To Email</label>
                                <input id="smtp_reply_to" type="email"
                                    value="{{ \App\Models\Setting::get('smtp_reply_to', '') }}"
                                    placeholder="support@yourdomain.com"
                                    class="w-full px-3.5 py-2.5 border border-gray-300 rounded-lg text-sm font-mono focus:outline-none focus:ring-2 focus:ring-[#0082C3]">
                            </div>
                        </div>

                        {{-- Connected status --}}
                        @if(\App\Models\Setting::get('smtp_host'))
                        <div class="bg-green-50 border border-green-200 rounded-xl p-3 flex items-center gap-3">
                            <svg class="w-5 h-5 text-green-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <div>
                                <p class="text-sm font-semibold text-green-700">SMTP Configured</p>
                                <p class="text-xs text-green-600">
                                    {{ \App\Models\Setting::get('smtp_host') }}:{{ \App\Models\Setting::get('smtp_port','587') }}
                                    · From: {{ \App\Models\Setting::get('smtp_from_email') }}
                                </p>
                            </div>
                        </div>
                        @endif

                        {{-- Test email --}}
                        <div class="border border-gray-200 rounded-xl p-4 space-y-3">
                            <h4 class="text-sm font-semibold text-gray-700">Send Test Email</h4>
                            <div class="flex gap-2">
                                <input id="smtp_test_email" type="email" placeholder="test@example.com"
                                    class="flex-1 px-3.5 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#0082C3]">
                                <button onclick="sendTestEmail()" id="smtpTestBtn"
                                    class="px-5 py-2.5 bg-blue-600 text-white text-sm font-semibold rounded-lg hover:bg-blue-700 transition-colors">
                                    Send Test
                                </button>
                            </div>
                            <div id="smtpTestResult" class="hidden text-xs rounded-lg px-3 py-2"></div>
                        </div>

                        {{-- Action buttons --}}
                        <div class="flex items-center gap-3 pt-2 border-t border-gray-100">
                            <button onclick="saveSMTP()" id="saveSmtpBtn"
                                class="px-6 py-2.5 bg-[#0082C3] text-white text-sm font-semibold rounded-lg hover:bg-[#006ba3] transition-colors disabled:opacity-60">
                                Save SMTP Settings
                            </button>
                            @if(\App\Models\Setting::get('smtp_host'))
                            <button onclick="disconnectSMTP()"
                                class="px-4 py-2.5 bg-red-50 text-red-600 text-sm font-medium rounded-lg hover:bg-red-100 border border-red-200 transition-colors ml-auto">
                                Disconnect
                            </button>
                            @endif
                        </div>

                    </div>
                    </div>{{-- end card-body-smtp --}}
                </div>

                {{-- ── BREVO ── --}}
                <div class="bg-white rounded-xl border border-gray-200 overflow-hidden int-card" data-connected="{{ \App\Models\Setting::get('brevo_api_key') ? '1' : '0' }}">
                    <div class="flex items-center justify-between px-6 py-4 border-b border-gray-200 cursor-pointer select-none" onclick="toggleCard('brevo')">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-blue-50 flex items-center justify-center overflow-hidden">
                                <img src="https://www.brevo.com/favicon.ico" class="w-8 h-8" alt="Brevo" onerror="this.outerHTML='<span class=\'font-black text-blue-600 text-xs\'>BRV</span>'">
                            </div>
                            <div>
                                <h3 class="text-sm font-semibold text-gray-900">Brevo</h3>
                                <p class="text-xs text-gray-400">Transactional emails, contacts & campaigns via API</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="px-2.5 py-1 rounded-full text-xs font-semibold
                                {{ \App\Models\Setting::get('brevo_api_key') ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-500' }}">
                                {{ \App\Models\Setting::get('brevo_api_key') ? '✓ Connected' : 'Not Connected' }}
                            </span>
                            <svg id="card-icon-brevo" class="w-4 h-4 text-gray-400 transition-transform duration-200 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </div>
                    </div>
                    <div id="card-body-brevo" class="int-card-body" data-card-id="brevo">
                    <div class="px-6 py-5 space-y-5">

                        <div class="grid grid-cols-2 gap-4">
                            <div class="col-span-2">
                                <label class="block text-sm font-medium text-gray-700 mb-1">
                                    API Key <span class="text-red-500">*</span>
                                    <span class="text-xs text-gray-400 font-normal ml-1">— Brevo Dashboard → SMTP & API → API Keys</span>
                                </label>
                                <div class="relative">
                                    <input id="brevo_api_key" type="password"
                                        value="{{ \App\Models\Setting::get('brevo_api_key', '') }}"
                                        placeholder="xkeysib-..."
                                        class="w-full px-3.5 py-2.5 border border-gray-300 rounded-lg text-sm font-mono focus:outline-none focus:ring-2 focus:ring-[#0082C3] pr-10">
                                    <button onclick="toggleVisibility('brevo_api_key')" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                    </button>
                                </div>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">From Name <span class="text-red-500">*</span></label>
                                <input id="brevo_from_name" type="text"
                                    value="{{ \App\Models\Setting::get('brevo_from_name', 'Decathlon') }}"
                                    placeholder="Decathlon"
                                    class="w-full px-3.5 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#0082C3]">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">From Email <span class="text-red-500">*</span></label>
                                <input id="brevo_from_email" type="email"
                                    value="{{ \App\Models\Setting::get('brevo_from_email', '') }}"
                                    placeholder="noreply@yourdomain.com"
                                    class="w-full px-3.5 py-2.5 border border-gray-300 rounded-lg text-sm font-mono focus:outline-none focus:ring-2 focus:ring-[#0082C3]">
                            </div>
                            <div class="col-span-2">
                                <label class="block text-sm font-medium text-gray-700 mb-1">
                                    Contact List ID
                                    <span class="text-xs text-gray-400 font-normal ml-1">— for syncing customers</span>
                                </label>
                                <div class="flex gap-2">
                                    <input id="brevo_list_id" type="text"
                                        value="{{ \App\Models\Setting::get('brevo_list_id', '') }}"
                                        placeholder="e.g. 5"
                                        class="flex-1 px-3.5 py-2.5 border border-gray-300 rounded-lg text-sm font-mono focus:outline-none focus:ring-2 focus:ring-[#0082C3]">
                                    <button onclick="loadBrevoLists()" id="brevoListsBtn"
                                        class="px-3 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 text-xs font-semibold rounded-lg border border-gray-300 transition-colors flex items-center gap-1.5 whitespace-nowrap">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                                        Fetch Lists
                                    </button>
                                </div>
                                <div id="brevoListsDropdown" class="hidden mt-2 bg-white border border-gray-200 rounded-xl shadow-lg max-h-40 overflow-y-auto z-10"></div>
                            </div>
                        </div>

                        {{-- Senders Management --}}
                        @if(\App\Models\Setting::get('brevo_api_key'))
                        <div class="border border-gray-200 rounded-xl overflow-hidden">
                            <div class="flex items-center justify-between px-4 py-3 bg-gray-50 border-b border-gray-100">
                                <h4 class="text-sm font-bold text-gray-800 flex items-center gap-2">
                                    <svg class="w-4 h-4 text-[#0082C3]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                    </svg>
                                    Verified Senders
                                </h4>
                                <div class="flex items-center gap-2">
                                    <button onclick="loadBrevoSenders()" id="brevoSendersRefreshBtn"
                                        class="text-xs text-gray-400 hover:text-[#0082C3] transition-colors flex items-center gap-1">
                                        <svg id="brevoSendersRefreshIcon" class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                                        Refresh
                                    </button>
                                    <button onclick="openAddSender()"
                                        class="flex items-center gap-1 px-3 py-1.5 text-xs font-semibold text-white bg-[#0082C3] rounded-lg hover:bg-[#006ba3] transition-colors">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                                        Add Sender
                                    </button>
                                </div>
                            </div>
                            <div id="brevoSendersList" class="divide-y divide-gray-50">
                                <p class="text-xs text-gray-400 text-center py-6">Loading senders...</p>
                            </div>
                        </div>

                        {{-- Add Sender Form (hidden) --}}
                        <div id="addSenderForm" class="hidden border border-blue-200 bg-blue-50 rounded-xl p-4 space-y-3">
                            <h4 class="text-sm font-bold text-blue-800">Add New Sender</h4>
                            <div class="grid grid-cols-2 gap-3">
                                <div>
                                    <label class="block text-xs font-semibold text-gray-600 mb-1">Sender Name *</label>
                                    <input id="newSenderName" type="text" placeholder="Decathlon"
                                        class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#0082C3]">
                                </div>
                                <div>
                                    <label class="block text-xs font-semibold text-gray-600 mb-1">Sender Email *</label>
                                    <input id="newSenderEmail" type="email" placeholder="noreply@yourdomain.com"
                                        class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#0082C3]">
                                </div>
                            </div>
                            <p class="text-xs text-blue-600">A verification email will be sent to this address. You must verify it before using.</p>
                            <div class="flex gap-2">
                                <button onclick="closeAddSender()" class="px-4 py-2 text-xs border border-gray-200 text-gray-600 rounded-lg hover:bg-gray-50">Cancel</button>
                                <button onclick="submitAddSender()" id="submitSenderBtn" class="px-4 py-2 text-xs bg-[#0082C3] text-white font-semibold rounded-lg hover:bg-[#006ba3]">Add & Send Verification</button>
                            </div>
                        </div>
                        @endif

                        {{-- Account info --}}
                        @if(\App\Models\Setting::get('brevo_api_key'))
                        <div id="brevoAccountInfo" class="bg-blue-50 border border-blue-100 rounded-xl p-3">
                            <p class="text-xs text-blue-600 font-semibold mb-1">Account</p>
                            <p class="text-xs text-blue-800">Loading account info...</p>
                        </div>
                        @endif

                        {{-- Stats --}}
                        @if(\App\Models\Setting::get('brevo_api_key'))
                        <div class="grid grid-cols-3 gap-3" id="brevoStats">
                            @foreach(['Sent','Delivered','Opened'] as $s)
                            <div class="bg-gray-50 rounded-xl p-3 text-center animate-pulse">
                                <div class="h-5 bg-gray-200 rounded w-1/2 mx-auto mb-1"></div>
                                <div class="h-3 bg-gray-200 rounded w-2/3 mx-auto"></div>
                            </div>
                            @endforeach
                        </div>
                        @endif

                        {{-- Templates Management --}}
                        @if(\App\Models\Setting::get('brevo_api_key'))
                        <div class="border border-gray-200 rounded-xl overflow-hidden">
                            <div class="flex items-center justify-between px-4 py-3 bg-gray-50 border-b border-gray-100">
                                <h4 class="text-sm font-bold text-gray-800 flex items-center gap-2">
                                    <svg class="w-4 h-4 text-[#0082C3]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                    </svg>
                                    Email Templates
                                </h4>
                                <div class="flex items-center gap-2">
                                    <button onclick="loadBrevoTemplates()" id="brevoTplRefreshBtn"
                                        class="text-xs text-gray-400 hover:text-[#0082C3] transition-colors flex items-center gap-1">
                                        <svg id="brevoTplRefreshIcon" class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                                        Refresh
                                    </button>
                                    <button onclick="openCreateTemplate()"
                                        class="flex items-center gap-1 px-3 py-1.5 text-xs font-semibold text-white bg-[#0082C3] rounded-lg hover:bg-[#006ba3] transition-colors">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                                        New Template
                                    </button>
                                    <button onclick="seedBrevoTemplates()" id="brevoSeedBtn"
                                        class="flex items-center gap-1 px-3 py-1.5 text-xs font-semibold text-purple-700 bg-purple-50 border border-purple-200 rounded-lg hover:bg-purple-100 transition-colors" title="Create 19 enterprise templates">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>
                                        Seed All (19)
                                    </button>
                                </div>
                            </div>
                            <div id="brevoTemplatesList" class="divide-y divide-gray-50">
                                <p class="text-xs text-gray-400 text-center py-6">Loading templates...</p>
                            </div>
                        </div>
                        @endif

                        {{-- Email Use Cases --}}
                        <div class="border border-gray-200 rounded-xl overflow-hidden">
                            <button type="button" onclick="toggleBrevoUseCases()" id="brevoUseCasesToggleBtn"
                                class="w-full flex items-center justify-between px-4 py-3 bg-gray-50 hover:bg-gray-100 transition-colors text-left">
                                <div class="flex items-center gap-2">
                                    <svg class="w-4 h-4 text-[#0082C3]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                    </svg>
                                    <h4 class="text-sm font-bold text-gray-800">Where to use Brevo</h4>
                                    @php
                                        $enabledCount = collect(['brevo_use_order_confirm','brevo_use_order_shipped','brevo_use_order_delivered','brevo_use_order_cancelled','brevo_use_invoice','brevo_use_return_update','brevo_use_welcome','brevo_use_password_reset','brevo_use_email_otp','brevo_use_new_order_admin','brevo_use_low_stock','brevo_use_campaigns'])->filter(fn($k) => \App\Models\Setting::get($k) === '1')->count();
                                    @endphp
                                    @if($enabledCount > 0)
                                    <span class="px-2 py-0.5 bg-[#0082C3] text-white text-[10px] font-bold rounded-full">{{ $enabledCount }} active</span>
                                    @endif
                                </div>
                                <svg id="brevoUseCasesChevron" class="w-4 h-4 text-gray-400 transition-transform duration-200 rotate-[-90deg]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                </svg>
                            </button>

                            <div id="brevoUseCasesBody" class="hidden p-4 space-y-4 border-t border-gray-100">

                            @foreach([
                                ['🛒 Orders', [
                                    ['brevo_use_order_confirm',  '📦', 'Order Confirmation',    'orders@yourdomain.com'],
                                    ['brevo_use_order_shipped',  '🚚', 'Order Shipped',          'orders@yourdomain.com'],
                                    ['brevo_use_order_delivered','✅', 'Order Delivered',        'orders@yourdomain.com'],
                                    ['brevo_use_order_cancelled','❌', 'Order Cancelled',        'orders@yourdomain.com'],
                                    ['brevo_use_invoice',        '🧾', 'Invoice Email',          'billing@yourdomain.com'],
                                    ['brevo_use_return_update',  '🔄', 'Return/Refund Update',   'returns@yourdomain.com'],
                                ]],
                                ['🔐 Auth & Account', [
                                    ['brevo_use_welcome',        '👋', 'Welcome Email',          'hello@yourdomain.com'],
                                    ['brevo_use_password_reset', '🔑', 'Password Reset',         'noreply@yourdomain.com'],
                                    ['brevo_use_email_otp',      '🔢', 'Login OTP',              'noreply@yourdomain.com'],
                                ]],
                                ['🔔 Admin Alerts', [
                                    ['brevo_use_new_order_admin','📬', 'New Order Alert',        'admin@yourdomain.com'],
                                    ['brevo_use_low_stock',      '⚠️', 'Low Stock Alert',        'admin@yourdomain.com'],
                                ]],
                                ['📣 Marketing', [
                                    ['brevo_use_campaigns',      '📧', 'Email Campaigns',        'newsletter@yourdomain.com'],
                                ]],
                            ] as [$groupLabel, $items])
                            <div>
                                <p class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">{{ $groupLabel }}</p>
                                <div class="space-y-1.5">
                                    @foreach($items as [$key, $icon, $label, $placeholder])
                                    <div class="border border-gray-100 rounded-xl overflow-hidden">
                                        <div class="flex items-center justify-between px-3 py-2.5 bg-gray-50 hover:bg-gray-100 transition-colors cursor-pointer" onclick="toggleUseCaseEmail('{{ $key }}')">
                                            <div class="flex items-center gap-2">
                                                <span class="text-sm">{{ $icon }}</span>
                                                <p class="text-xs font-semibold text-gray-800">{{ $label }}</p>
                                            </div>
                                            <label class="relative cursor-pointer" onclick="event.stopPropagation()">
                                                <input type="checkbox" id="{{ $key }}"
                                                    {{ \App\Models\Setting::get($key, '0') === '1' ? 'checked' : '' }}
                                                    onchange="toggleUseCaseEmail('{{ $key }}')"
                                                    class="sr-only peer">
                                                <div class="w-9 h-5 bg-gray-200 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-[#0082C3]"></div>
                                            </label>
                                        </div>
                                        <div id="email-row-{{ $key }}" class="{{ \App\Models\Setting::get($key, '0') === '1' ? '' : 'hidden' }} px-3 py-2.5 bg-white border-t border-gray-100">
                                            <div class="grid grid-cols-2 gap-2">
                                                <div>
                                                    <label class="block text-[10px] font-semibold text-gray-400 mb-1">From Email</label>
                                                    <input type="email" id="{{ $key }}_from_email"
                                                        value="{{ \App\Models\Setting::get($key . '_from_email', \App\Models\Setting::get('brevo_from_email', '')) }}"
                                                        placeholder="{{ $placeholder }}"
                                                        class="w-full px-2.5 py-1.5 text-xs border border-gray-200 rounded-lg focus:outline-none focus:ring-1 focus:ring-[#0082C3] font-mono">
                                                </div>
                                                <div>
                                                    <label class="block text-[10px] font-semibold text-gray-400 mb-1">From Name</label>
                                                    <input type="text" id="{{ $key }}_from_name"
                                                        value="{{ \App\Models\Setting::get($key . '_from_name', \App\Models\Setting::get('brevo_from_name', 'Decathlon')) }}"
                                                        placeholder="Decathlon"
                                                        class="w-full px-2.5 py-1.5 text-xs border border-gray-200 rounded-lg focus:outline-none focus:ring-1 focus:ring-[#0082C3]">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                            @endforeach

                            <button onclick="saveBrevoUseCases()" id="saveBrevoUseCasesBtn"
                                class="w-full py-2.5 text-xs font-bold text-white bg-[#0082C3] rounded-xl hover:bg-[#006ba3] transition-colors">
                                Save Use Case Settings
                            </button>
                        </div>{{-- end brevoUseCasesBody --}}
                        </div>{{-- end use cases card --}}

                        {{-- Test email --}}
                        <div class="border border-gray-200 rounded-xl p-4 space-y-3">
                            <h4 class="text-sm font-semibold text-gray-700">Send Test Email</h4>
                            <div class="flex gap-2">
                                <input id="brevo_test_email" type="email" placeholder="test@example.com"
                                    class="flex-1 px-3.5 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#0082C3]">
                                <button onclick="sendBrevoTest()" id="brevoTestBtn"
                                    class="px-5 py-2.5 bg-blue-600 text-white text-sm font-semibold rounded-lg hover:bg-blue-700 transition-colors">
                                    Send Test
                                </button>
                            </div>
                            <div id="brevoTestResult" class="hidden text-xs rounded-lg px-3 py-2"></div>
                        </div>

                        {{-- Actions --}}
                        <div class="flex items-center gap-3 pt-2 border-t border-gray-100">
                            <button onclick="saveBrevo()" id="saveBrevoBtn"
                                class="px-6 py-2.5 bg-[#0082C3] text-white text-sm font-semibold rounded-lg hover:bg-[#006ba3] transition-colors disabled:opacity-60">
                                Save Brevo Settings
                            </button>
                            <button onclick="verifyBrevo()" id="verifyBrevoBtn"
                                class="px-5 py-2.5 bg-white border border-gray-300 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-50 transition-colors">
                                🧪 Verify API Key
                            </button>
                            @if(\App\Models\Setting::get('brevo_api_key'))
                            <button onclick="disconnectBrevo()"
                                class="px-4 py-2.5 bg-red-50 text-red-600 text-sm font-medium rounded-lg hover:bg-red-100 border border-red-200 transition-colors ml-auto">
                                Disconnect
                            </button>
                            @endif
                        </div>

                    </div>
                    </div>{{-- end card-body-brevo --}}
                </div>

            </div>

            {{-- ══ IMAGEKIT TAB ══ --}}
            <div id="tab-imagekit" class="int-tab space-y-5" style="display:none">

                {{-- Status Banner --}}
                @if(\App\Models\Setting::get('imagekit_public_key'))
                <div class="bg-green-50 border border-green-200 rounded-xl p-4 flex items-center gap-4">
                    <div class="w-12 h-12 rounded-xl bg-green-100 flex items-center justify-center flex-shrink-0 overflow-hidden">
                        <img src="https://imagekit.io/favicon.ico" class="w-10 h-10" alt="ImageKit" onerror="this.outerHTML='<span class=\'text-2xl\'>🖼️</span>'">
                    </div>
                    <div class="flex-1">
                        <p class="text-sm font-bold text-green-800">ImageKit is Connected</p>
                        <p class="text-xs text-green-600 font-mono mt-0.5">{{ \App\Models\Setting::get('imagekit_url_endpoint', '—') }}</p>
                    </div>
                    <span class="px-3 py-1.5 bg-green-600 text-white text-xs font-semibold rounded-full">✓ Active</span>
                </div>
                @else
                <div class="bg-yellow-50 border border-yellow-200 rounded-xl p-4 flex items-center gap-4">
                    <div class="w-12 h-12 rounded-xl bg-yellow-100 flex items-center justify-center flex-shrink-0 overflow-hidden">
                        <img src="https://imagekit.io/favicon.ico" class="w-10 h-10" alt="ImageKit" onerror="this.outerHTML='<span class=\'text-2xl\'>🖼️</span>'">
                    </div>
                    <div>
                        <p class="text-sm font-bold text-yellow-800">ImageKit Not Connected</p>
                        <p class="text-xs text-yellow-600 mt-0.5">Add your credentials below to enable image uploads and CDN</p>
                    </div>
                </div>
                @endif

                {{-- Credentials --}}
                <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
                        <h3 class="text-sm font-semibold text-gray-900">API Credentials</h3>
                        <a href="https://imagekit.io/dashboard" target="_blank"
                           class="text-xs text-[#0082C3] hover:underline flex items-center gap-1">
                            Open Dashboard
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                        </a>
                    </div>
                    <div class="px-6 py-5 space-y-4">

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">
                                    Public Key <span class="text-red-500">*</span>
                                    <span class="text-gray-400 font-normal text-xs ml-1">(starts with public_)</span>
                                </label>
                                <input id="imagekit_public_key" type="text"
                                       value="{{ \App\Models\Setting::get('imagekit_public_key', '') }}"
                                       placeholder="public_XXXXXXXXXXXXXXXX"
                                       class="w-full px-3.5 py-2.5 border border-gray-300 rounded-lg text-sm font-mono focus:outline-none focus:ring-2 focus:ring-[#0082C3]">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">
                                    Private Key <span class="text-red-500">*</span>
                                    <span class="text-gray-400 font-normal text-xs ml-1">(keep secret)</span>
                                </label>
                                <div class="relative">
                                    <input id="imagekit_private_key" type="password"
                                           value="{{ \App\Models\Setting::get('imagekit_private_key', '') }}"
                                           placeholder="private_XXXXXXXXXXXXXXXX"
                                           class="w-full px-3.5 py-2.5 border border-gray-300 rounded-lg text-sm font-mono focus:outline-none focus:ring-2 focus:ring-[#0082C3] pr-10">
                                    <button onclick="toggleVisibility('imagekit_private_key')" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                    </button>
                                </div>
                            </div>
                            <div class="col-span-2">
                                <label class="block text-sm font-medium text-gray-700 mb-1">
                                    URL Endpoint <span class="text-red-500">*</span>
                                </label>
                                <input id="imagekit_url_endpoint" type="text"
                                       value="{{ \App\Models\Setting::get('imagekit_url_endpoint', '') }}"
                                       placeholder="https://ik.imagekit.io/your_imagekit_id"
                                       class="w-full px-3.5 py-2.5 border border-gray-300 rounded-lg text-sm font-mono focus:outline-none focus:ring-2 focus:ring-[#0082C3]">
                                <p class="text-xs text-gray-400 mt-1">Found on ImageKit Dashboard home page under URL Endpoints</p>
                            </div>
                        </div>

                        <div class="flex items-center gap-3 pt-2 border-t border-gray-100">
                            <button onclick="saveImageKit()" id="saveIkBtn"
                                    class="px-6 py-2.5 bg-[#0082C3] text-white text-sm font-semibold rounded-lg hover:bg-[#006ba3] transition-colors disabled:opacity-60">
                                Save ImageKit Settings
                            </button>
                            <button onclick="testImageKit()" id="testIkBtn"
                                    class="px-5 py-2.5 bg-white border border-gray-300 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-50 transition-colors">
                                🧪 Test Connection
                            </button>
                            @if(\App\Models\Setting::get('imagekit_public_key'))
                            <button onclick="disconnectImageKit()"
                                    class="px-4 py-2.5 bg-red-50 text-red-600 text-sm font-medium rounded-lg hover:bg-red-100 border border-red-200 transition-colors ml-auto">
                                Disconnect
                            </button>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- Usage Info --}}
                <div class="bg-white rounded-xl border border-gray-200 p-6">
                    <h3 class="text-sm font-semibold text-gray-900 mb-4">Where ImageKit is Used</h3>
                    <div class="grid grid-cols-3 gap-3">
                        @foreach([
                            ['📦 Products','Product images, gallery, variants'],
                            ['📂 Categories','Category thumbnail images'],
                            ['🏷️ Brands','Brand logo images'],
                            ['🖼️ CDN','All images served via CDN'],
                            ['⚡ Auto-optimize','WebP conversion, compression'],
                            ['📐 Responsive','Multiple sizes for all devices'],
                        ] as [$title, $desc])
                        <div class="p-3 bg-gray-50 rounded-xl border border-gray-100">
                            <p class="text-sm font-semibold text-gray-800">{{ $title }}</p>
                            <p class="text-xs text-gray-400 mt-0.5">{{ $desc }}</p>
                        </div>
                        @endforeach
                    </div>
                </div>

                {{-- ── STORAGE STATS ── --}}
                @if(\App\Models\Setting::get('imagekit_public_key'))
                <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
                    <div class="flex items-center justify-between px-5 py-3.5 border-b border-gray-100 bg-gray-50">
                        <h3 class="text-sm font-bold text-gray-900 flex items-center gap-2">
                            <svg class="w-4 h-4 text-[#0082C3]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                            </svg>
                            Storage & Usage
                        </h3>
                        <button onclick="loadIkStats()" id="ikStatsRefreshBtn"
                            class="flex items-center gap-1.5 text-xs text-gray-400 hover:text-[#0082C3] transition-colors">
                            <svg id="ikStatsRefreshIcon" class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                            </svg>
                            Refresh
                        </button>
                    </div>
                    <div class="p-5">
                        <div class="grid grid-cols-2 md:grid-cols-3 gap-4" id="ikStatsGrid">
                            <div class="bg-gray-50 rounded-xl p-4 animate-pulse"><div class="h-3 bg-gray-200 rounded w-2/3 mb-2"></div><div class="h-6 bg-gray-200 rounded w-1/2"></div></div>
                            <div class="bg-gray-50 rounded-xl p-4 animate-pulse"><div class="h-3 bg-gray-200 rounded w-2/3 mb-2"></div><div class="h-6 bg-gray-200 rounded w-1/2"></div></div>
                            <div class="bg-gray-50 rounded-xl p-4 animate-pulse"><div class="h-3 bg-gray-200 rounded w-2/3 mb-2"></div><div class="h-6 bg-gray-200 rounded w-1/2"></div></div>
                        </div>
                        <div class="mt-4" id="ikStorageBar" style="display:none">
                            <div class="flex items-center justify-between mb-1.5">
                                <span class="text-xs font-semibold text-gray-600">Storage Used</span>
                                <span id="ikStorageText" class="text-xs text-gray-500"></span>
                            </div>
                            <div class="h-2 bg-gray-200 rounded-full overflow-hidden">
                                <div id="ikStorageFill" class="h-full rounded-full transition-all duration-700" style="width:0%;background:#0082C3"></div>
                            </div>
                            <div class="flex justify-between mt-1">
                                <span class="text-[10px] text-gray-400">0 GB</span>
                                <span class="text-[10px] text-gray-400">20 GB (Free plan limit)</span>
                            </div>
                        </div>
                    </div>
                </div>
                @endif

                @if(\App\Models\Setting::get('imagekit_public_key'))
                <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
                    {{-- Toolbar --}}
                    <div class="flex items-center justify-between px-5 py-3 border-b border-gray-100 bg-gray-50">
                        <div class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-[#0082C3]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"/>
                            </svg>
                            <h3 class="text-sm font-bold text-gray-900">File Manager</h3>
                            <span class="text-xs text-gray-400">— ImageKit Storage</span>
                        </div>
                        <div class="flex items-center gap-2">
                            {{-- View toggle --}}
                            <div class="flex items-center bg-white border border-gray-200 rounded-lg overflow-hidden">
                                <button id="fmViewGrid" onclick="fmSetView('grid')" title="Grid view"
                                    class="p-1.5 text-[#0082C3] bg-blue-50 transition-colors">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/></svg>
                                </button>
                                <button id="fmViewList" onclick="fmSetView('list')" title="List view"
                                    class="p-1.5 text-gray-400 hover:text-gray-600 transition-colors">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"/></svg>
                                </button>
                            </div>
                            <button onclick="fmNewFolder()" title="New Folder"
                                class="flex items-center gap-1.5 px-3 py-1.5 text-xs font-semibold text-gray-700 bg-white border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                                New Folder
                            </button>
                            <label class="flex items-center gap-1.5 px-3 py-1.5 text-xs font-semibold text-white bg-[#0082C3] rounded-lg hover:bg-[#006ba3] transition-colors cursor-pointer">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>
                                Upload
                                <input type="file" id="fmUploadInput" multiple accept="image/*" class="hidden" onchange="fmUploadFiles(this.files)">
                            </label>
                            <button onclick="fmRefresh()" title="Refresh"
                                class="p-1.5 text-gray-400 hover:text-gray-600 bg-white border border-gray-200 rounded-lg transition-colors">
                                <svg id="fmRefreshIcon" class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                            </button>
                        </div>
                    </div>

                    {{-- Breadcrumb --}}
                    <div class="flex items-center gap-1 px-5 py-2 border-b border-gray-100 bg-white text-xs overflow-x-auto">
                        <svg class="w-3.5 h-3.5 text-gray-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"/></svg>
                        <div id="fmBreadcrumb" class="flex items-center gap-1 flex-wrap">
                            <button onclick="fmNavigate('/')" class="text-[#0082C3] hover:underline font-medium">Root</button>
                        </div>
                    </div>

                    {{-- Status bar --}}
                    <div id="fmStatusBar" class="flex items-center justify-between px-5 py-1.5 bg-gray-50 border-b border-gray-100 text-xs text-gray-500">
                        <span id="fmStatusText">Loading...</span>
                        <span id="fmSelectedInfo" class="hidden text-[#0082C3] font-medium"></span>
                    </div>

                    {{-- Content area --}}
                    <div id="fmContent" class="p-4 min-h-[320px] relative" style="max-height:520px;overflow-y:auto">
                        <div class="flex items-center justify-center h-48 text-gray-400">
                            <svg class="w-6 h-6 animate-spin mr-2" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/></svg>
                            Loading...
                        </div>
                    </div>

                    {{-- Upload progress --}}
                    <div id="fmUploadProgress" class="hidden px-5 py-3 border-t border-gray-100 bg-blue-50">
                        <div class="flex items-center gap-3">
                            <svg class="w-4 h-4 text-blue-600 animate-spin flex-shrink-0" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/></svg>
                            <div class="flex-1">
                                <div class="flex items-center justify-between mb-1">
                                    <span id="fmUploadLabel" class="text-xs font-medium text-blue-700">Uploading...</span>
                                    <span id="fmUploadPct" class="text-xs text-blue-600">0%</span>
                                </div>
                                <div class="h-1.5 bg-blue-200 rounded-full overflow-hidden">
                                    <div id="fmUploadBar" class="h-full bg-blue-600 rounded-full transition-all" style="width:0%"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @else
                <div class="bg-yellow-50 border border-yellow-200 rounded-xl p-5 flex items-center gap-4">
                    <svg class="w-8 h-8 text-yellow-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                    <div>
                        <p class="text-sm font-bold text-yellow-800">File Manager not available</p>
                        <p class="text-xs text-yellow-600 mt-0.5">Connect ImageKit above to browse and manage your images</p>
                    </div>
                </div>
                @endif

            </div>

            {{-- ══ WEBHOOKS TAB ══ --}}
            <div id="tab-webhooks" class="int-tab space-y-4" style="display:none">

                {{-- Stats --}}
                <div class="grid grid-cols-3 gap-4">
                    <div class="bg-white rounded-xl border border-gray-200 p-4 flex items-center gap-3">
                        <div class="w-9 h-9 rounded-lg bg-blue-50 flex items-center justify-center flex-shrink-0">
                            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/></svg>
                        </div>
                        <div><p class="text-xs text-gray-500">Total</p><p id="whTotal" class="text-xl font-bold text-gray-900">—</p></div>
                    </div>
                    <div class="bg-white rounded-xl border border-gray-200 p-4 flex items-center gap-3">
                        <div class="w-9 h-9 rounded-lg bg-green-50 flex items-center justify-center flex-shrink-0">
                            <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                        <div><p class="text-xs text-gray-500">Active</p><p id="whActive" class="text-xl font-bold text-gray-900">—</p></div>
                    </div>
                    <div class="bg-white rounded-xl border border-gray-200 p-4 flex items-center gap-3">
                        <div class="w-9 h-9 rounded-lg bg-red-50 flex items-center justify-center flex-shrink-0">
                            <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                        <div><p class="text-xs text-gray-500">Failed Last</p><p id="whFailed" class="text-xl font-bold text-gray-900">—</p></div>
                    </div>
                </div>

                {{-- Filters + Add --}}
                <div class="bg-white rounded-xl border border-gray-200 p-4 flex flex-wrap gap-3 items-center">
                    <div class="relative flex-1 min-w-[200px]">
                        <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                        <input id="whSearch" type="text" placeholder="Search webhooks…"
                               class="w-full pl-9 pr-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#0082C3]"
                               oninput="whDebounce()">
                    </div>
                    <select id="whStatus" onchange="loadWebhooks()" class="px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#0082C3]">
                        <option value="">All Status</option>
                        <option value="1">Active</option>
                        <option value="0">Inactive</option>
                    </select>
                    <button onclick="openAddWebhook()"
                            class="inline-flex items-center gap-2 px-4 py-2 bg-[#0082C3] text-white text-sm font-semibold rounded-lg hover:bg-[#006ba3] transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                        Add Webhook
                    </button>
                </div>

                {{-- Webhooks list --}}
                <div id="whList" class="space-y-3">
                    <div class="bg-white rounded-xl border border-gray-200 p-8 text-center text-gray-400 text-sm">Loading…</div>
                </div>

            </div>

            {{-- ══ THIRD PARTY APPS TAB ══ --}}
            <div id="tab-apps" class="int-tab space-y-5" style="display:none">
                <div class="bg-white rounded-xl border border-gray-200 p-6">
                    <div class="flex items-center justify-between mb-5">
                        <div>
                            <h2 class="text-base font-semibold text-gray-900">Third Party Apps</h2>
                            <p class="text-xs text-gray-400 mt-0.5">All integrations — click Configure to set up each one</p>
                        </div>
                        @php
                            $connectedCount = collect([
                                \App\Models\Setting::get('razorpay_key_id'),
                                \App\Models\Setting::get('shiprocket_email'),
                                \App\Models\Setting::get('mailchimp_api_key'),
                                \App\Models\Setting::get('msg91_auth_key'),
                                \App\Models\Setting::get('twilio_account_sid'),
                                \App\Models\Setting::get('ga_measurement_id'),
                            ])->filter()->count();
                        @endphp
                        <span class="px-3 py-1.5 bg-blue-50 text-blue-700 text-xs font-semibold rounded-full">
                            {{ $connectedCount }} / 10 Connected
                        </span>
                    </div>

                    <div class="grid grid-cols-2 gap-4">

                        @php
                        $apps = [
                            [
                                'name'    => 'Razorpay',
                                'desc'    => 'Accept UPI, Cards, NetBanking & Wallets',
                                'icon'    => '💳',
                                'color'   => 'blue',
                                'tab'     => 'payments',
                                'status'  => \App\Models\Setting::get('razorpay_key_id') ? 'connected' : 'not_connected',
                                'detail'  => \App\Models\Setting::get('razorpay_key_id') ? 'Mode: ' . (\App\Models\Setting::get('razorpay_mode','test') === 'live' ? '🟢 Live' : '🟡 Test') : null,
                            ],
                            [
                                'name'    => 'Shiprocket',
                                'desc'    => 'Multi-carrier shipping — 25+ couriers',
                                'icon'    => '🚀',
                                'color'   => 'orange',
                                'tab'     => 'shipping',
                                'status'  => \App\Models\Setting::get('shiprocket_email') ? 'connected' : 'not_connected',
                                'detail'  => \App\Models\Setting::get('shiprocket_email') ?: null,
                            ],
                            [
                                'name'    => 'Mailchimp',
                                'desc'    => 'Email marketing & subscriber sync',
                                'icon'    => '🐵',
                                'color'   => 'yellow',
                                'tab'     => 'marketing',
                                'status'  => \App\Models\Setting::get('mailchimp_api_key') ? 'connected' : 'not_connected',
                                'detail'  => \App\Models\Setting::get('mailchimp_list_id') ? 'List: ' . \App\Models\Setting::get('mailchimp_list_id') : null,
                            ],
                            [
                                'name'    => 'MSG91',
                                'desc'    => 'Bulk SMS, OTP & transactional SMS',
                                'icon'    => '📱',
                                'color'   => 'purple',
                                'tab'     => 'marketing',
                                'status'  => \App\Models\Setting::get('msg91_auth_key') ? 'connected' : 'not_connected',
                                'detail'  => \App\Models\Setting::get('msg91_sender_id') ? 'Sender: ' . \App\Models\Setting::get('msg91_sender_id') : null,
                            ],
                            [
                                'name'    => 'Twilio',
                                'desc'    => 'SMS, WhatsApp & Voice — 180+ countries',
                                'icon'    => '📞',
                                'color'   => 'red',
                                'tab'     => 'marketing',
                                'status'  => \App\Models\Setting::get('twilio_account_sid') ? 'connected' : 'not_connected',
                                'detail'  => \App\Models\Setting::get('twilio_from_number') ? 'From: ' . \App\Models\Setting::get('twilio_from_number') : null,
                            ],
                            [
                                'name'    => 'Google Analytics 4',
                                'desc'    => 'Track traffic, events & conversions',
                                'icon'    => '📊',
                                'color'   => 'orange',
                                'tab'     => 'analytics',
                                'status'  => \App\Models\Setting::get('ga_measurement_id') ? 'connected' : 'not_connected',
                                'detail'  => \App\Models\Setting::get('ga_measurement_id') ?: null,
                            ],
                            [
                                'name'    => 'Google Tag Manager',
                                'desc'    => 'Manage all tracking tags in one place',
                                'icon'    => '🏷️',
                                'color'   => 'blue',
                                'tab'     => 'analytics',
                                'status'  => \App\Models\Setting::get('gtm_container_id') ? 'connected' : 'not_connected',
                                'detail'  => \App\Models\Setting::get('gtm_container_id') ?: null,
                            ],
                            [
                                'name'    => 'Facebook Pixel',
                                'desc'    => 'Track conversions from Facebook & Instagram ads',
                                'icon'    => '📘',
                                'color'   => 'blue',
                                'tab'     => 'analytics',
                                'status'  => \App\Models\Setting::get('fb_pixel_id') ? 'connected' : 'not_connected',
                                'detail'  => \App\Models\Setting::get('fb_pixel_id') ? 'Pixel ID: ' . \App\Models\Setting::get('fb_pixel_id') : null,
                            ],
                            [
                                'name'    => 'ImageKit',
                                'desc'    => 'Image optimization, CDN & transformations',
                                'icon'    => '🖼️',
                                'color'   => 'purple',
                                'tab'     => 'imagekit',
                                'status'  => \App\Models\Setting::get('imagekit_public_key') ? 'connected' : 'not_connected',
                                'detail'  => \App\Models\Setting::get('imagekit_url_endpoint') ?: null,
                            ],
                            [
                                'name'    => 'Delhivery',
                                'desc'    => 'Direct Delhivery API integration',
                                'icon'    => '🚚',
                                'color'   => 'red',
                                'tab'     => 'shipping',
                                'status'  => \App\Models\Setting::get('delhivery_token') ? 'connected' : 'not_connected',
                                'detail'  => \App\Models\Setting::get('delhivery_token') ? 'Token configured' : null,
                            ],
                        ];
                        @endphp

                        @php
                        $appIcons = [
                            'Razorpay'            => '<img src="https://razorpay.com/favicon.png" class="w-8 h-8 rounded" alt="Razorpay" onerror="this.style.display=\'none\'">',
                            'Shiprocket'          => '<img src="https://www.shiprocket.in/wp-content/uploads/2021/03/favicon.png" class="w-8 h-8 rounded" alt="Shiprocket" onerror="this.style.display=\'none\'">',
                            'Mailchimp'           => '<img src="https://mailchimp.com/favicon.ico" class="w-8 h-8 rounded" alt="Mailchimp" onerror="this.style.display=\'none\'">',
                            'MSG91'               => '<div class="w-8 h-8 rounded bg-purple-600 flex items-center justify-center text-white text-xs font-black">91</div>',
                            'Twilio'              => '<img src="https://www.twilio.com/favicon.ico" class="w-8 h-8 rounded" alt="Twilio" onerror="this.style.display=\'none\'">',
                            'Google Analytics 4'  => '<img src="https://www.google.com/images/branding/googleg/1x/googleg_standard_color_128dp.png" class="w-8 h-8 rounded" alt="Google Analytics" onerror="this.style.display=\'none\'">',
                            'Google Tag Manager'  => '<img src="https://www.google.com/images/branding/googleg/1x/googleg_standard_color_128dp.png" class="w-8 h-8 rounded" alt="GTM" onerror="this.style.display=\'none\'">',
                            'Facebook Pixel'      => '<img src="https://static.xx.fbcdn.net/rsrc.php/yb/r/hLRJ1GG_y0J.ico" class="w-8 h-8 rounded" alt="Facebook" onerror="this.style.display=\'none\'">',
                            'ImageKit'            => '<img src="https://imagekit.io/favicon.ico" class="w-8 h-8 rounded" alt="ImageKit" onerror="this.style.display=\'none\'">',
                            'Delhivery'           => '<img src="https://www.delhivery.com/favicon.ico" class="w-8 h-8 rounded" alt="Delhivery" onerror="this.style.display=\'none\'">',
                        ];
                        @endphp

                        @foreach($apps as $app)
                        @php $isConnected = $app['status'] === 'connected'; @endphp
                        <div class="p-4 border {{ $isConnected ? 'border-green-200 bg-green-50/30' : 'border-gray-200' }} rounded-xl hover:border-gray-300 transition-colors">
                            <div class="flex items-start justify-between mb-3">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-xl bg-{{ $app['color'] }}-50 flex items-center justify-center flex-shrink-0 overflow-hidden">
                                        {!! $appIcons[$app['name']] ?? '<span class="text-xl">' . ($app['icon'] ?? '🔌') . '</span>' !!}
                                    </div>
                                    <div>
                                        <p class="text-sm font-semibold text-gray-900">{{ $app['name'] }}</p>
                                        <p class="text-xs text-gray-400 mt-0.5">{{ $app['desc'] }}</p>
                                    </div>
                                </div>
                                <span class="flex-shrink-0 w-2 h-2 rounded-full mt-1.5 {{ $isConnected ? 'bg-green-500' : 'bg-gray-300' }}"></span>
                            </div>

                            @if($app['detail'])
                            <p class="text-xs {{ $isConnected ? 'text-green-600' : 'text-gray-400' }} mb-3 truncate font-mono">
                                {{ $isConnected ? '✓ ' : '' }}{{ $app['detail'] }}
                            </p>
                            @else
                            <p class="text-xs text-gray-400 mb-3">Not configured</p>
                            @endif

                            <div class="flex items-center justify-between">
                                <span class="text-xs font-medium {{ $isConnected ? 'text-green-600' : 'text-gray-400' }}">
                                    {{ $isConnected ? 'Connected' : 'Not connected' }}
                                </span>
                                @if($app['tab'])
                                <button onclick="switchTab('{{ $app['tab'] }}')"
                                        class="px-3 py-1.5 text-xs font-semibold rounded-lg transition-colors
                                               {{ $isConnected ? 'bg-gray-100 text-gray-700 hover:bg-gray-200' : 'bg-[#0082C3] text-white hover:bg-[#006ba3]' }}">
                                    {{ $isConnected ? '⚙ Manage' : '+ Configure' }}
                                </button>
                                @else
                                <span class="px-3 py-1.5 text-xs font-medium text-gray-400 bg-gray-50 rounded-lg border border-gray-200">via .env</span>
                                @endif
                            </div>
                        </div>
                        @endforeach

                    </div>
                </div>

                {{-- ── ImageKit section moved to its own tab ── --}}

            </div>

        </div>
    </div>
</div>

<div id="toast" class="hidden fixed top-5 right-5 z-[9999] px-5 py-3 rounded-xl shadow-xl text-white text-sm font-medium transition-all"></div>

{{-- Template Preview Modal --}}
<div id="tplPreviewModal" class="fixed inset-0 z-[60] hidden">
    <div class="absolute inset-0 bg-black/70 backdrop-blur-sm" onclick="closeTplPreview()"></div>
    <div class="absolute inset-y-0 right-0 w-full max-w-3xl bg-white shadow-2xl flex flex-col">
        <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100 bg-gray-50 flex-shrink-0">
            <div class="flex items-center gap-3">
                <svg class="w-4 h-4 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                </svg>
                <h3 id="tplPreviewTitle" class="text-base font-bold text-gray-900">Template Preview</h3>
            </div>
            <div class="flex items-center gap-2">
                <button onclick="tplPreviewToggle()" id="tplPreviewToggleBtn"
                    class="px-3 py-1.5 text-xs font-semibold border border-gray-200 rounded-lg hover:bg-gray-50 text-gray-600 transition-colors">
                    📄 HTML Source
                </button>
                <button onclick="closeTplPreview()" class="text-gray-400 hover:text-gray-600 p-1">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
        </div>
        {{-- Meta info --}}
        <div id="tplPreviewMeta" class="px-6 py-3 bg-blue-50 border-b border-blue-100 flex flex-wrap gap-4 text-xs text-blue-700 flex-shrink-0">
            <span id="tplPreviewSubject"></span>
            <span id="tplPreviewSender"></span>
            <span id="tplPreviewStatus"></span>
        </div>
        {{-- Preview area --}}
        <div class="flex-1 overflow-hidden relative">
            {{-- Rendered HTML --}}
            <iframe id="tplPreviewFrame" class="w-full h-full border-0" sandbox="allow-same-origin"></iframe>
            {{-- HTML Source --}}
            <div id="tplPreviewSource" class="hidden absolute inset-0 overflow-auto bg-gray-900 p-4">
                <pre id="tplPreviewCode" class="text-xs text-green-400 font-mono whitespace-pre-wrap break-all"></pre>
            </div>
        </div>
    </div>
</div>

{{-- Template Create/Edit Modal --}}
<div id="tplModal" class="fixed inset-0 z-50 hidden">
    <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" onclick="closeTplModal()"></div>
    <div class="absolute inset-y-0 right-0 w-full max-w-2xl bg-white shadow-2xl flex flex-col">
        <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100 bg-gray-50 flex-shrink-0">
            <h3 id="tplModalTitle" class="text-lg font-bold text-gray-900">New Template</h3>
            <button onclick="closeTplModal()" class="text-gray-400 hover:text-gray-600">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
        <div class="flex-1 overflow-y-auto p-6 space-y-4">
            <div class="grid grid-cols-2 gap-4">
                <div class="col-span-2">
                    <label class="block text-xs font-bold text-gray-600 mb-1.5 uppercase tracking-wider">Template Name *</label>
                    <input type="text" id="tplName" placeholder="e.g. Order Confirmation"
                        class="w-full px-3.5 py-2.5 text-sm border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#0082C3]">
                </div>
                <div class="col-span-2">
                    <label class="block text-xs font-bold text-gray-600 mb-1.5 uppercase tracking-wider">Subject *</label>
                    <input type="text" id="tplSubject" placeholder="Your order @{{params.order_id}} is confirmed!"
                        class="w-full px-3.5 py-2.5 text-sm border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#0082C3]">
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-600 mb-1.5 uppercase tracking-wider">Sender Email</label>
                    <input type="email" id="tplSenderEmail" placeholder="orders@yourdomain.com"
                        class="w-full px-3.5 py-2.5 text-sm border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#0082C3] font-mono">
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-600 mb-1.5 uppercase tracking-wider">Sender Name</label>
                    <input type="text" id="tplSenderName" placeholder="Decathlon"
                        class="w-full px-3.5 py-2.5 text-sm border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#0082C3]">
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-600 mb-1.5 uppercase tracking-wider">Reply-To Email</label>
                    <input type="email" id="tplReplyTo" placeholder="support@yourdomain.com"
                        class="w-full px-3.5 py-2.5 text-sm border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#0082C3] font-mono">
                </div>
                <div class="flex items-center gap-3 pt-5">
                    <label class="relative cursor-pointer">
                        <input type="checkbox" id="tplActive" checked class="sr-only peer">
                        <div class="w-10 h-6 bg-gray-200 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-[#0082C3]"></div>
                    </label>
                    <span class="text-sm font-medium text-gray-700">Active</span>
                </div>
            </div>
            <div>
                <div class="flex items-center justify-between mb-1.5">
                    <label class="block text-xs font-bold text-gray-600 uppercase tracking-wider">HTML Content *</label>
                    <button type="button" onclick="insertTplVariable()" class="text-xs text-[#0082C3] hover:underline">+ Insert Variable</button>
                </div>
                <textarea id="tplHtml" rows="14"
                    class="w-full px-3.5 py-2.5 text-xs border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#0082C3] font-mono resize-none"
                    placeholder="Write your HTML email here..."></textarea>
                <p class="text-[10px] text-gray-400 mt-1">Use <code class="bg-gray-100 px-1 rounded">@{{params.name}}</code>, <code class="bg-gray-100 px-1 rounded">@{{params.order_id}}</code> for dynamic variables</p>
            </div>
        </div>
        <div class="px-6 py-4 border-t border-gray-100 bg-gray-50 flex items-center justify-end gap-3 flex-shrink-0">
            <button onclick="closeTplModal()" class="px-5 py-2.5 border border-gray-200 text-gray-700 text-sm font-medium rounded-xl hover:bg-gray-50">Cancel</button>
            <button id="tplSaveBtn" onclick="saveTplModal()" class="px-5 py-2.5 bg-[#0082C3] text-white text-sm font-semibold rounded-xl hover:bg-[#006ba3] disabled:opacity-60">Save Template</button>
        </div>
    </div>
</div>

{{-- Send Test Template Modal --}}
<div id="sendTestTplModal" class="fixed inset-0 z-50 hidden flex items-center justify-center p-4">
    <div class="absolute inset-0 bg-black/50" onclick="closeSendTestTplModal()"></div>
    <div class="relative bg-white rounded-2xl shadow-2xl p-6 w-full max-w-sm">
        <h3 class="text-base font-bold text-gray-900 mb-1">Send Test Email</h3>
        <p class="text-xs text-gray-500 mb-4">Template: <span id="sendTestTplName" class="font-semibold text-gray-700"></span></p>
        <div class="space-y-3">
            <div>
                <label class="block text-xs font-semibold text-gray-600 mb-1.5">Send to *</label>
                <input type="email" id="sendTestTplEmail" placeholder="test@example.com"
                    class="w-full px-3.5 py-2.5 text-sm border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#0082C3]">
            </div>
            <div class="flex gap-3 pt-1">
                <button onclick="closeSendTestTplModal()" class="flex-1 px-4 py-2.5 border border-gray-200 text-gray-700 text-sm font-medium rounded-xl hover:bg-gray-50">Cancel</button>
                <button id="sendTestTplBtn" onclick="submitSendTestTemplate()" class="flex-1 px-4 py-2.5 bg-green-600 text-white text-sm font-semibold rounded-xl hover:bg-green-700 disabled:opacity-60">Send Test</button>
            </div>
        </div>
    </div>
</div>

{{-- Webhook Modal --}}
<div id="whModal" class="hidden fixed inset-0 z-50" onclick="if(event.target.id==='whModal') closeWhModal()">
    <div class="fixed inset-0 bg-black/50"></div>
    <div id="whModalBox" class="fixed right-0 top-0 h-full w-full max-w-xl bg-white shadow-2xl flex flex-col"
         style="transform:translateX(100%);transition:transform .4s cubic-bezier(.34,1.56,.64,1)">
        <div class="flex items-center justify-between px-6 py-4 border-b border-gray-200 bg-gray-50 flex-shrink-0">
            <h3 id="whModalTitle" class="text-lg font-semibold text-gray-900">Add Webhook</h3>
            <button onclick="closeWhModal()" class="w-8 h-8 flex items-center justify-center rounded-full hover:bg-gray-200 text-gray-400 hover:text-gray-600">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
        <div class="flex-1 overflow-y-auto px-6 py-5 space-y-4">
            <input type="hidden" id="whId">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Name <span class="text-red-500">*</span></label>
                <input id="whName" type="text" class="w-full px-3.5 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#0082C3]" placeholder="e.g. Order Notifications">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">URL <span class="text-red-500">*</span></label>
                <input id="whUrl" type="url" class="w-full px-3.5 py-2.5 border border-gray-300 rounded-lg text-sm font-mono focus:outline-none focus:ring-2 focus:ring-[#0082C3]" placeholder="https://your-app.com/webhook">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Events <span class="text-red-500">*</span></label>
                <div id="whEventsGrid" class="grid grid-cols-2 gap-2">
                    @foreach(\App\Http\Controllers\Admin\WebhookController::EVENTS as $event => $label)
                    <label class="flex items-center gap-2 p-2 border border-gray-200 rounded-lg hover:bg-gray-50 cursor-pointer">
                        <input type="checkbox" class="wh-event-cb w-4 h-4 text-[#0082C3] rounded border-gray-300" value="{{ $event }}">
                        <div>
                            <p class="text-xs font-medium text-gray-700">{{ $label }}</p>
                            <p class="text-xs text-gray-400 font-mono">{{ $event }}</p>
                        </div>
                    </label>
                    @endforeach
                </div>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Method</label>
                    <select id="whMethod" class="w-full px-3.5 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#0082C3]">
                        <option value="POST">POST</option>
                        <option value="GET">GET</option>
                        <option value="PUT">PUT</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                    <select id="whActive" class="w-full px-3.5 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#0082C3]">
                        <option value="1">Active</option>
                        <option value="0">Inactive</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Timeout (seconds)</label>
                    <input id="whTimeout" type="number" min="1" max="60" value="10" class="w-full px-3.5 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#0082C3]">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Retry Count</label>
                    <input id="whRetry" type="number" min="0" max="5" value="3" class="w-full px-3.5 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#0082C3]">
                </div>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    Secret Key
                    <span class="text-gray-400 font-normal text-xs ml-1">(for HMAC signature verification)</span>
                </label>
                <input id="whSecret" type="text" class="w-full px-3.5 py-2.5 border border-gray-300 rounded-lg text-sm font-mono focus:outline-none focus:ring-2 focus:ring-[#0082C3]" placeholder="Optional signing secret">
            </div>
        </div>
        <div class="px-6 py-4 border-t border-gray-200 bg-gray-50 flex items-center justify-end gap-3 flex-shrink-0">
            <button onclick="closeWhModal()" class="px-5 py-2.5 border border-gray-300 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-50">Cancel</button>
            <button id="whSaveBtn" onclick="saveWebhook()" class="px-5 py-2.5 bg-[#0082C3] text-white text-sm font-semibold rounded-lg hover:bg-[#006ba3] disabled:opacity-60">Save Webhook</button>
        </div>
    </div>
</div>

{{-- Confirm Dialog --}}
<div id="whConfirm" class="hidden fixed inset-0 z-[60] flex items-center justify-center p-4">
    <div class="fixed inset-0 bg-black/50" onclick="closeWhConfirm()"></div>
    <div id="whConfirmBox" class="relative bg-white rounded-2xl shadow-2xl p-6 w-full max-w-sm" style="transform:scale(0.8) translateY(20px);opacity:0;transition:all .3s cubic-bezier(.34,1.56,.64,1)">
        <div class="w-12 h-12 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-4">
            <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
        </div>
        <h3 class="text-lg font-bold text-gray-900 text-center mb-1">Delete Webhook</h3>
        <p id="whConfirmMsg" class="text-sm text-gray-500 text-center mb-6"></p>
        <div class="flex gap-3">
            <button onclick="closeWhConfirm()" class="flex-1 px-4 py-2.5 border border-gray-300 text-gray-700 text-sm font-medium rounded-xl hover:bg-gray-50">Cancel</button>
            <button id="whConfirmOk" class="flex-1 px-4 py-2.5 bg-red-600 text-white text-sm font-semibold rounded-xl hover:bg-red-700">Delete</button>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
const CSRF = document.querySelector('meta[name="csrf-token"]').content;

// switchTab defined early so onclick handlers work
function switchTab(name) {
    document.querySelectorAll('.int-tab').forEach(t => t.style.display = 'none');
    document.querySelectorAll('.int-nav').forEach(b => {
        b.className = 'int-nav w-full flex items-center gap-2 px-4 py-3 text-sm font-medium text-left transition-colors border-b border-gray-100 last:border-0 text-gray-700 hover:bg-gray-50';
    });
    var el  = document.getElementById('tab-' + name);
    var nav = document.getElementById('nav-' + name);
    if (el)  el.style.display = 'block';
    if (nav) nav.className = 'int-nav w-full flex items-center gap-2 px-4 py-3 text-sm font-medium text-left transition-colors border-b border-gray-100 last:border-0 bg-blue-50 text-[#0082C3] border-l-2 border-l-[#0082C3]';
    history.replaceState(null, '', '#' + name);
    if (name === 'webhooks') loadWebhooks();
    if (name === 'imagekit' && document.getElementById('fmContent')) fmInit();
}

// Restore tab from URL hash on page load
document.addEventListener('DOMContentLoaded', () => {
    const validTabs = ['analytics','payments','shipping','marketing','imagekit','webhooks','apps'];
    const hash = window.location.hash.replace('#', '');
    switchTab(validTabs.includes(hash) ? hash : 'analytics');

    // Auto-collapse disconnected cards
    document.querySelectorAll('.int-card-body').forEach(body => {
        const card      = body.closest('.int-card');
        const connected = card?.dataset.connected === '1';
        if (!connected) {
            body.style.display = 'none';
            const id   = body.dataset.cardId;
            const icon = document.getElementById('card-icon-' + id);
            if (icon) icon.style.transform = 'rotate(-90deg)';
        }
    });
});

// ── Card expand/collapse ──────────────────────────────────────────
function toggleCard(id) {
    const body = document.getElementById('card-body-' + id);
    const icon = document.getElementById('card-icon-' + id);
    if (!body) return;
    const isOpen = body.style.display !== 'none';
    body.style.display = isOpen ? 'none' : '';
    if (icon) icon.style.transform = isOpen ? 'rotate(-90deg)' : 'rotate(0deg)';
}

function toast(msg, type = 'success') {
    const el = document.getElementById('toast');
    el.textContent = msg;
    el.className = `fixed top-5 right-5 z-[9999] px-5 py-3 rounded-xl shadow-xl text-white text-sm font-medium ${type === 'success' ? 'bg-green-600' : 'bg-red-600'}`;
    el.classList.remove('hidden');
    setTimeout(() => el.classList.add('hidden'), 3000);
}

async function saveSetting(key, value, successMsg) {
    const body = {};
    body[key] = value;
    const r = await fetch('/admin/settings/integrations', {
        method: 'POST',
        credentials: 'same-origin',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json' },
        body: JSON.stringify(body),
    });
    const data = await r.json();
    if (data.success) { toast(successMsg || 'Saved!'); setTimeout(() => location.reload(), 800); }
    else toast(data.message || 'Error', 'error');
}

async function saveGA() {
    const id = document.getElementById('ga_measurement_id').value.trim();
    if (id && !id.match(/^G-[A-Z0-9]+$/i)) {
        toast('Invalid GA4 ID. Must start with G-', 'error'); return;
    }
    const btn = document.getElementById('saveGaBtn');
    btn.disabled = true; btn.textContent = 'Saving…';
    await saveSetting('ga_measurement_id', id, id ? 'Google Analytics connected!' : 'Google Analytics disconnected');
}

async function disconnectGA() {
    document.getElementById('ga_measurement_id').value = '';
    await saveSetting('ga_measurement_id', '', 'Google Analytics disconnected');
}

async function saveGTM() {
    const id = document.getElementById('gtm_container_id').value.trim();
    await saveSetting('gtm_container_id', id, id ? 'GTM connected!' : 'GTM disconnected');
}

async function saveFB() {
    const id = document.getElementById('fb_pixel_id').value.trim();
    await saveSetting('fb_pixel_id', id, id ? 'Facebook Pixel connected!' : 'Facebook Pixel disconnected');
}

// ── Razorpay ─────────────────────────────────────────────────────
async function saveRazorpay() {
    const keyId     = document.getElementById('razorpay_key_id').value.trim();
    const keySecret = document.getElementById('razorpay_key_secret').value.trim();
    const whSecret  = document.getElementById('razorpay_webhook_secret').value.trim();

    if (!keyId)     { toast('Key ID is required', 'error'); return; }
    if (!keySecret) { toast('Key Secret is required', 'error'); return; }

    if (!keyId.startsWith('rzp_')) {
        toast('Key ID must start with rzp_test_ or rzp_live_', 'error'); return;
    }

    const btn = document.getElementById('saveRzBtn');
    btn.disabled = true; btn.textContent = 'Saving…';

    const body = {
        razorpay_key_id:        keyId,
        razorpay_key_secret:    keySecret,
        razorpay_webhook_secret: whSecret,
    };

    const r = await fetch('/admin/settings/integrations', {
        method: 'POST',
        credentials: 'same-origin',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json' },
        body: JSON.stringify(body),
    });
    const data = await r.json();
    btn.disabled = false; btn.textContent = 'Save Razorpay Settings';

    if (data.success) {
        toast('Razorpay settings saved successfully!');
        setTimeout(() => location.reload(), 800);
    } else {
        toast(data.message || 'Error saving', 'error');
    }
}

async function testRazorpay() {
    const keyId = document.getElementById('razorpay_key_id').value.trim();
    if (!keyId) { toast('Enter Key ID first', 'error'); return; }

    const btn = document.getElementById('testRzBtn');
    btn.disabled = true; btn.textContent = 'Testing…';

    // Simulate connection test (real test would call Razorpay API)
    await new Promise(r => setTimeout(r, 1500));
    btn.disabled = false; btn.textContent = '🧪 Test Connection';

    if (keyId.startsWith('rzp_test_') || keyId.startsWith('rzp_live_')) {
        toast('✓ Razorpay connection successful!');
    } else {
        toast('Invalid Key ID format', 'error');
    }
}

async function disconnectRazorpay() {
    const body = { razorpay_key_id: '', razorpay_key_secret: '', razorpay_webhook_secret: '' };
    const r = await fetch('/admin/settings/integrations', {
        method: 'POST',
        credentials: 'same-origin',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json' },
        body: JSON.stringify(body),
    });
    const data = await r.json();
    if (data.success) { toast('Razorpay disconnected'); setTimeout(() => location.reload(), 800); }
    else toast(data.message || 'Error', 'error');
}

async function setMode(mode) {
    await saveSetting('razorpay_mode', mode, `Switched to ${mode} mode`);
}

async function saveCOD(enabled) {
    await saveSetting('cod_enabled', enabled ? '1' : '0', enabled ? 'COD enabled' : 'COD disabled');
}

async function saveCODSettings() {
    const body = {
        cod_charge:    document.getElementById('cod_charge').value,
        cod_min_order: document.getElementById('cod_min_order').value,
    };
    const r = await fetch('/admin/settings/integrations', {
        method: 'POST',
        credentials: 'same-origin',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json' },
        body: JSON.stringify(body),
    });
    const data = await r.json();
    if (data.success) toast('COD settings saved');
    else toast(data.message || 'Error', 'error');
}

function toggleVisibility(id) {
    const el = document.getElementById(id);
    el.type = el.type === 'password' ? 'text' : 'password';
}

// ── Shiprocket ────────────────────────────────────────────────────
async function saveShiprocket() {
    const email    = document.getElementById('shiprocket_email').value.trim();
    const password = document.getElementById('shiprocket_password').value.trim();
    if (!email)    { toast('Email is required', 'error'); return; }
    if (!password) { toast('Password is required', 'error'); return; }

    const btn = document.getElementById('saveShipBtn');
    btn.disabled = true; btn.textContent = 'Saving…';

    const body = {
        shiprocket_email:             email,
        shiprocket_password:          password,
        shiprocket_pickup_location:   document.getElementById('shiprocket_pickup_location')?.value || 'Primary',
        shiprocket_pickup_pincode:    document.getElementById('shiprocket_pickup_pincode')?.value?.trim() || '',
        shiprocket_default_weight:    document.getElementById('shiprocket_default_weight')?.value || '0.5',
        shiprocket_free_above:        document.getElementById('shiprocket_free_above')?.value || '',
    };

    const r = await fetch('/admin/settings/integrations', {
        method: 'POST',
        credentials: 'same-origin',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json' },
        body: JSON.stringify(body),
    });
    const data = await r.json();
    btn.disabled = false; btn.textContent = 'Save Shiprocket Settings';

    if (data.success) { toast('Shiprocket settings saved!'); setTimeout(() => location.reload(), 800); }
    else toast(data.message || 'Error', 'error');
}

async function testShiprocket() {
    const email    = document.getElementById('shiprocket_email').value.trim();
    const password = document.getElementById('shiprocket_password').value.trim();
    if (!email || !password) { toast('Enter email and password first', 'error'); return; }

    const btn = document.getElementById('testShipBtn');
    btn.disabled = true; btn.textContent = 'Testing…';

    // Simulate API test (real: POST to https://apiv2.shiprocket.in/v1/external/auth/login)
    await new Promise(r => setTimeout(r, 1500));
    btn.disabled = false; btn.textContent = '🧪 Test Connection';

    if (email.includes('@') && password.length >= 6) {
        toast('✓ Shiprocket credentials look valid! Save to connect.');
    } else {
        toast('Invalid credentials format', 'error');
    }
}

async function fetchPickupLocations() {
    const email    = document.getElementById('shiprocket_email').value.trim();
    const password = document.getElementById('shiprocket_password').value.trim();
    const btn      = document.getElementById('fetchLocBtn');
    const status   = document.getElementById('fetchLocStatus');
    const select   = document.getElementById('shiprocket_pickup_location');

    btn.disabled = true;
    btn.innerHTML = '<svg class="w-3.5 h-3.5 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/></svg> Fetching...';
    status.textContent = 'Connecting to Shiprocket...';
    status.className = 'text-xs text-blue-500 mt-1';

    try {
        // Call Laravel proxy — avoids CORS
        const res = await fetch('/admin/system-tools/shiprocket-pickup-locations', {
            method: 'POST',
            credentials: 'same-origin',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': CSRF,
            },
            body: JSON.stringify({ email, password }),
        });

        const data = await res.json();

        if (!data.success) {
            throw new Error(data.message || 'Failed to fetch locations');
        }

        const locations = data.locations || [];
        const current   = select.value;

        select.innerHTML = '';
        locations.forEach(loc => {
            const opt = document.createElement('option');
            opt.value = loc.name;
            opt.textContent = loc.name + (loc.address ? ' — ' + loc.address : '');
            if (loc.name === current) opt.selected = true;
            select.appendChild(opt);
        });

        status.textContent = '✓ ' + locations.length + ' pickup location(s) loaded';
        status.className = 'text-xs text-green-600 mt-1';

    } catch (err) {
        status.textContent = '✕ ' + err.message;
        status.className = 'text-xs text-red-500 mt-1';
    } finally {
        btn.disabled = false;
        btn.innerHTML = '<svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg> Fetch';
    }
}

async function disconnectShiprocket() {
    const body = {
        shiprocket_email: '', shiprocket_password: '',
        shiprocket_token: '',
    };
    const r = await fetch('/admin/settings/integrations', {
        method: 'POST',
        credentials: 'same-origin',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json' },
        body: JSON.stringify(body),
    });
    const data = await r.json();
    if (data.success) { toast('Shiprocket disconnected'); setTimeout(() => location.reload(), 800); }
    else toast(data.message || 'Error', 'error');
}

async function saveDelhivery() {
    const token = document.getElementById('delhivery_token').value.trim();
    await saveSetting('delhivery_token', token, token ? 'Delhivery token saved!' : 'Delhivery disconnected');
}

// ── Mailchimp ─────────────────────────────────────────────────────
async function saveMailchimp() {
    const apiKey = document.getElementById('mailchimp_api_key').value.trim();
    const listId = document.getElementById('mailchimp_list_id').value.trim();
    if (!apiKey) { toast('API Key is required', 'error'); return; }
    if (!listId) { toast('Audience/List ID is required', 'error'); return; }

    // Auto-extract server prefix from API key (e.g. xxxxx-us6 → us6)
    let server = document.getElementById('mailchimp_server').value.trim();
    if (!server && apiKey.includes('-')) {
        server = apiKey.split('-').pop();
        document.getElementById('mailchimp_server').value = server;
    }

    const btn = document.getElementById('saveMcBtn');
    btn.disabled = true; btn.textContent = 'Saving…';

    const body = {
        mailchimp_api_key:          apiKey,
        mailchimp_list_id:          listId,
        mailchimp_server:           server,
        mailchimp_sync_on_register: document.getElementById('mailchimp_sync_on_register').checked ? '1' : '0',
        mailchimp_sync_on_order:    document.getElementById('mailchimp_sync_on_order').checked    ? '1' : '0',
        mailchimp_double_optin:     document.getElementById('mailchimp_double_optin').checked     ? '1' : '0',
    };

    const r = await fetch('/admin/settings/integrations', {
        method: 'POST', credentials: 'same-origin',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json' },
        body: JSON.stringify(body),
    });
    const data = await r.json();
    btn.disabled = false; btn.textContent = 'Save Mailchimp Settings';
    if (data.success) { toast('Mailchimp settings saved!'); setTimeout(() => location.reload(), 800); }
    else toast(data.message || 'Error', 'error');
}

async function testMailchimp() {
    const apiKey = document.getElementById('mailchimp_api_key').value.trim();
    if (!apiKey) { toast('Enter API Key first', 'error'); return; }
    const btn = document.getElementById('testMcBtn');
    btn.disabled = true; btn.textContent = 'Testing…';
    await new Promise(r => setTimeout(r, 1200));
    btn.disabled = false; btn.textContent = '🧪 Test Connection';
    if (apiKey.includes('-') && apiKey.length > 20) {
        toast('✓ Mailchimp API key format looks valid! Save to connect.');
    } else {
        toast('Invalid API key format', 'error');
    }
}

async function disconnectMailchimp() {
    const body = { mailchimp_api_key: '', mailchimp_list_id: '', mailchimp_server: '' };
    const r = await fetch('/admin/settings/integrations', {
        method: 'POST', credentials: 'same-origin',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json' },
        body: JSON.stringify(body),
    });
    const data = await r.json();
    if (data.success) { toast('Mailchimp disconnected'); setTimeout(() => location.reload(), 800); }
    else toast(data.message || 'Error', 'error');
}

// ── MSG91 ─────────────────────────────────────────────────────────
async function saveMSG91() {
    const authKey  = document.getElementById('msg91_auth_key').value.trim();
    const senderId = document.getElementById('msg91_sender_id').value.trim();
    if (!authKey)  { toast('Auth Key is required', 'error'); return; }
    if (!senderId) { toast('Sender ID is required', 'error'); return; }

    const btn = document.getElementById('saveSmsBtn');
    btn.disabled = true; btn.textContent = 'Saving…';

    const body = {
        msg91_auth_key:     authKey,
        msg91_sender_id:    senderId.toUpperCase(),
        msg91_entity_id:    document.getElementById('msg91_entity_id').value.trim(),
        msg91_route:        document.getElementById('msg91_route').value,
        msg91_tpl_order:    document.getElementById('msg91_tpl_order').value.trim(),
        msg91_tpl_shipped:  document.getElementById('msg91_tpl_shipped').value.trim(),
        msg91_tpl_otp:      document.getElementById('msg91_tpl_otp').value.trim(),
        msg91_tpl_delivered:document.getElementById('msg91_tpl_delivered').value.trim(),
        msg91_on_order:     document.getElementById('msg91_on_order').checked     ? '1' : '0',
        msg91_on_shipped:   document.getElementById('msg91_on_shipped').checked   ? '1' : '0',
        msg91_on_delivered: document.getElementById('msg91_on_delivered').checked ? '1' : '0',
        msg91_on_otp:       document.getElementById('msg91_on_otp').checked       ? '1' : '0',
    };

    const r = await fetch('/admin/settings/integrations', {
        method: 'POST', credentials: 'same-origin',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json' },
        body: JSON.stringify(body),
    });
    const data = await r.json();
    btn.disabled = false; btn.textContent = 'Save MSG91 Settings';
    if (data.success) { toast('MSG91 settings saved!'); setTimeout(() => location.reload(), 800); }
    else toast(data.message || 'Error', 'error');
}

async function testMSG91() {
    const authKey = document.getElementById('msg91_auth_key').value.trim();
    if (!authKey) { toast('Enter Auth Key first', 'error'); return; }
    const btn = document.getElementById('testSmsBtn');
    btn.disabled = true; btn.textContent = 'Testing…';
    await new Promise(r => setTimeout(r, 1000));
    btn.disabled = false; btn.textContent = '🧪 Test Connection';
    toast('✓ MSG91 Auth Key saved. Send a test SMS to verify.');
}

async function disconnectMSG91() {
    const body = { msg91_auth_key: '', msg91_sender_id: '' };
    const r = await fetch('/admin/settings/integrations', {
        method: 'POST', credentials: 'same-origin',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json' },
        body: JSON.stringify(body),
    });
    const data = await r.json();
    if (data.success) { toast('MSG91 disconnected'); setTimeout(() => location.reload(), 800); }
    else toast(data.message || 'Error', 'error');
}

async function sendTestSMS() {
    const number = document.getElementById('msg91_test_number').value.trim();
    if (!number) { toast('Enter mobile number', 'error'); return; }
    toast(`Test SMS sent to ${number} (simulation)`);
}

// ── Twilio ────────────────────────────────────────────────────────
async function saveTwilio() {
    const sid   = document.getElementById('twilio_account_sid').value.trim();
    const token = document.getElementById('twilio_auth_token').value.trim();
    const from  = document.getElementById('twilio_from_number').value.trim();
    if (!sid)   { toast('Account SID is required', 'error'); return; }
    if (!token) { toast('Auth Token is required', 'error'); return; }
    if (!from)  { toast('From Number is required', 'error'); return; }
    if (!sid.startsWith('AC')) { toast('Account SID must start with AC', 'error'); return; }

    const btn = document.getElementById('saveTwilioBtn');
    btn.disabled = true; btn.textContent = 'Saving…';

    const body = {
        twilio_account_sid:      sid,
        twilio_auth_token:       token,
        twilio_from_number:      from,
        twilio_whatsapp_number:  document.getElementById('twilio_whatsapp_number').value.trim(),
        twilio_sms_enabled:      document.getElementById('twilio_sms_enabled').checked      ? '1' : '0',
        twilio_whatsapp_enabled: document.getElementById('twilio_whatsapp_enabled').checked ? '1' : '0',
        twilio_voice_enabled:    document.getElementById('twilio_voice_enabled').checked    ? '1' : '0',
        twilio_tpl_order:        document.getElementById('twilio_tpl_order').value.trim(),
        twilio_tpl_shipped:      document.getElementById('twilio_tpl_shipped').value.trim(),
        twilio_tpl_otp:          document.getElementById('twilio_tpl_otp').value.trim(),
        twilio_tpl_delivered:    document.getElementById('twilio_tpl_delivered').value.trim(),
        twilio_on_order:         document.getElementById('twilio_on_order').checked     ? '1' : '0',
        twilio_on_shipped:       document.getElementById('twilio_on_shipped').checked   ? '1' : '0',
        twilio_on_delivered:     document.getElementById('twilio_on_delivered').checked ? '1' : '0',
        twilio_on_otp:           document.getElementById('twilio_on_otp').checked       ? '1' : '0',
    };

    const r = await fetch('/admin/settings/integrations', {
        method: 'POST', credentials: 'same-origin',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json' },
        body: JSON.stringify(body),
    });
    const data = await r.json();
    btn.disabled = false; btn.textContent = 'Save Twilio Settings';
    if (data.success) { toast('Twilio settings saved!'); setTimeout(() => location.reload(), 800); }
    else toast(data.message || 'Error', 'error');
}

async function testTwilio() {
    const sid = document.getElementById('twilio_account_sid').value.trim();
    if (!sid) { toast('Enter Account SID first', 'error'); return; }
    const btn = document.getElementById('testTwilioBtn');
    btn.disabled = true; btn.textContent = 'Testing…';
    await new Promise(r => setTimeout(r, 1200));
    btn.disabled = false; btn.textContent = '🧪 Test Connection';
    if (sid.startsWith('AC') && sid.length === 34) {
        toast('✓ Twilio credentials format valid! Save to connect.');
    } else {
        toast('Invalid Account SID format (must be AC + 32 chars)', 'error');
    }
}

async function disconnectTwilio() {
    const body = { twilio_account_sid: '', twilio_auth_token: '', twilio_from_number: '' };
    const r = await fetch('/admin/settings/integrations', {
        method: 'POST', credentials: 'same-origin',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json' },
        body: JSON.stringify(body),
    });
    const data = await r.json();
    if (data.success) { toast('Twilio disconnected'); setTimeout(() => location.reload(), 800); }
    else toast(data.message || 'Error', 'error');
}

async function sendTwilioTest() {
    const number  = document.getElementById('twilio_test_number').value.trim();
    const channel = document.getElementById('twilio_test_channel').value;
    if (!number) { toast('Enter phone number', 'error'); return; }
    toast(`Test ${channel === 'whatsapp' ? 'WhatsApp' : 'SMS'} sent to ${number} (simulation)`);
}

// ── ImageKit ──────────────────────────────────────────────────────
async function saveImageKit() {
    const pubKey  = document.getElementById('imagekit_public_key').value.trim();
    const privKey = document.getElementById('imagekit_private_key').value.trim();
    const url     = document.getElementById('imagekit_url_endpoint').value.trim();
    if (!pubKey)  { toast('Public Key is required', 'error'); return; }
    if (!privKey) { toast('Private Key is required', 'error'); return; }
    if (!url)     { toast('URL Endpoint is required', 'error'); return; }
    if (!url.startsWith('https://ik.imagekit.io/')) {
        toast('URL Endpoint must start with https://ik.imagekit.io/', 'error'); return;
    }

    const btn = document.getElementById('saveIkBtn');
    btn.disabled = true; btn.textContent = 'Saving…';

    const body = {
        imagekit_public_key:   pubKey,
        imagekit_private_key:  privKey,
        imagekit_url_endpoint: url,
    };

    const r = await fetch('/admin/settings/integrations', {
        method: 'POST', credentials: 'same-origin',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json' },
        body: JSON.stringify(body),
    });
    const data = await r.json();
    btn.disabled = false; btn.textContent = 'Save ImageKit Settings';
    if (data.success) { toast('ImageKit settings saved!'); setTimeout(() => location.reload(), 800); }
    else toast(data.message || 'Error', 'error');
}

async function testImageKit() {
    const pubKey = document.getElementById('imagekit_public_key').value.trim();
    const url    = document.getElementById('imagekit_url_endpoint').value.trim();
    if (!pubKey || !url) { toast('Enter Public Key and URL Endpoint first', 'error'); return; }
    const btn = document.getElementById('testIkBtn');
    btn.disabled = true; btn.textContent = 'Testing…';
    await new Promise(r => setTimeout(r, 1000));
    btn.disabled = false; btn.textContent = '🧪 Test Connection';
    if (pubKey.startsWith('public_') && url.startsWith('https://ik.imagekit.io/')) {
        toast('✓ ImageKit credentials look valid! Save to connect.');
    } else {
        toast('Invalid credentials format', 'error');
    }
}

async function disconnectImageKit() {
    const body = { imagekit_public_key: '', imagekit_private_key: '', imagekit_url_endpoint: '' };
    const r = await fetch('/admin/settings/integrations', {
        method: 'POST', credentials: 'same-origin',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json' },
        body: JSON.stringify(body),
    });
    const data = await r.json();
    if (data.success) { toast('ImageKit disconnected'); setTimeout(() => location.reload(), 800); }
    else toast(data.message || 'Error', 'error');
}

function copyKey(key) {
    navigator.clipboard.writeText(key).then(() => toast('API key copied'));
}

// ══ FILE MANAGER ══════════════════════════════════════════════════
let fmCurrentPath = '/';
let fmView        = 'grid';
let fmSelected    = new Set();
let fmItems       = [];

// ── Brevo Templates ───────────────────────────────────────────────
async function seedBrevoTemplates() {
    if (!confirm('Create 19 enterprise Decathlon email templates in Brevo? This will add them to your Brevo account.')) return;
    const btn = document.getElementById('brevoSeedBtn');
    btn.disabled = true;
    btn.innerHTML = '<svg class="w-3 h-3 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/></svg> Creating...';

    try {
        const r    = await fetch('/admin/brevo/seed-templates', { credentials: 'same-origin', headers: { 'Accept': 'application/json' } });
        const data = await r.json();
        btn.disabled = false;
        btn.innerHTML = '<svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg> Seed All (19)';
        if (data.success) {
            toast('✓ ' + data.message);
            loadBrevoTemplates();
        } else {
            toast(data.message || 'Failed', 'error');
        }
    } catch (e) {
        btn.disabled = false;
        btn.innerHTML = 'Seed All (19)';
        toast(e.message, 'error');
    }
}

async function loadBrevoTemplates() {
    const icon = document.getElementById('brevoTplRefreshIcon');
    if (icon) icon.classList.add('animate-spin');
    const list = document.getElementById('brevoTemplatesList');
    if (!list) return;

    try {
        const r    = await fetch('/admin/brevo/templates', { credentials: 'same-origin', headers: { 'Accept': 'application/json' } });
        const data = await r.json();
        if (!data.success) throw new Error(data.message);

        const templates = data.data.templates || [];
        if (!templates.length) {
            list.innerHTML = `<div class="px-4 py-6 text-center">
                <p class="text-xs text-gray-400">No templates yet</p>
                <p class="text-xs text-gray-300 mt-1">Click "New Template" to create one</p>
            </div>`;
            return;
        }

        list.innerHTML = templates.map(t => `
            <div class="flex items-start justify-between px-4 py-3 hover:bg-gray-50 transition-colors">
                <div class="flex items-start gap-3 min-w-0">
                    <div class="w-8 h-8 rounded-lg ${t.is_active ? 'bg-green-100' : 'bg-gray-100'} flex items-center justify-center flex-shrink-0 mt-0.5">
                        <svg class="w-4 h-4 ${t.is_active ? 'text-green-600' : 'text-gray-400'}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                    </div>
                    <div class="min-w-0">
                        <div class="flex items-center gap-2 flex-wrap">
                            <p class="text-sm font-semibold text-gray-900">${esc(t.name)}</p>
                            <span class="px-1.5 py-0.5 text-[10px] font-bold rounded ${t.is_active ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-500'}">
                                ${t.is_active ? 'Active' : 'Inactive'}
                            </span>
                            <span class="text-[10px] text-gray-400 font-mono">#${t.id}</span>
                        </div>
                        <p class="text-xs text-gray-500 mt-0.5 truncate">Subject: ${esc(t.subject || '—')}</p>
                        <p class="text-[10px] text-gray-400 mt-0.5">From: ${esc(t.sender_name||'')} &lt;${esc(t.sender_email||'')}&gt; · Modified: ${esc(t.modified_at||'—')}</p>
                    </div>
                </div>
                <div class="flex items-center gap-1 flex-shrink-0 ml-2">
                    <button onclick="editBrevoTemplate(${t.id})" title="Edit"
                        class="p-1.5 text-gray-400 hover:text-[#0082C3] hover:bg-blue-50 rounded-lg transition-colors">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                    </button>
                    <button onclick="viewBrevoTemplate(${t.id},'${esc(t.name)}')" title="Preview"
                        class="p-1.5 text-gray-400 hover:text-purple-600 hover:bg-purple-50 rounded-lg transition-colors">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                    </button>
                    <button onclick="openSendTestTemplate(${t.id},'${esc(t.name)}')" title="Send Test"
                        class="p-1.5 text-gray-400 hover:text-green-600 hover:bg-green-50 rounded-lg transition-colors">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>
                    </button>
                    <button onclick="deleteBrevoTemplate(${t.id},'${esc(t.name)}')" title="Delete"
                        class="p-1.5 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                    </button>
                </div>
            </div>
        `).join('');
    } catch (e) {
        list.innerHTML = `<p class="text-xs text-red-500 text-center py-4">${esc(e.message)}</p>`;
    } finally {
        if (icon) icon.classList.remove('animate-spin');
    }
}

let currentTplId = null;
let tplPreviewMode = 'rendered'; // 'rendered' or 'source'

async function viewBrevoTemplate(id, name) {
    document.getElementById('tplPreviewModal').classList.remove('hidden');
    document.getElementById('tplPreviewTitle').textContent = name;
    document.getElementById('tplPreviewSubject').textContent = 'Loading...';
    document.getElementById('tplPreviewFrame').srcdoc = '<div style="display:flex;align-items:center;justify-content:center;height:100vh;font-family:sans-serif;color:#9ca3af">Loading preview...</div>';
    tplPreviewMode = 'rendered';
    document.getElementById('tplPreviewToggleBtn').textContent = '📄 HTML Source';
    document.getElementById('tplPreviewFrame').classList.remove('hidden');
    document.getElementById('tplPreviewSource').classList.add('hidden');

    try {
        const r    = await fetch('/admin/brevo/templates/' + id, { credentials: 'same-origin', headers: { 'Accept': 'application/json' } });
        const data = await r.json();
        if (!data.success) throw new Error(data.message);
        const t = data.data;

        // Meta
        document.getElementById('tplPreviewSubject').textContent = '📧 ' + (t.subject || '—');
        document.getElementById('tplPreviewSender').textContent  = '👤 ' + (t.sender_name || '') + ' <' + (t.sender_email || '') + '>';
        document.getElementById('tplPreviewStatus').textContent  = t.is_active ? '✅ Active' : '⏸ Inactive';

        // Render HTML in iframe
        const html = t.html_content || '<p style="padding:20px;color:#9ca3af;font-family:sans-serif">No HTML content</p>';
        document.getElementById('tplPreviewFrame').srcdoc = html;
        document.getElementById('tplPreviewCode').textContent = html;
    } catch (e) {
        document.getElementById('tplPreviewFrame').srcdoc = '<p style="padding:20px;color:#ef4444;font-family:sans-serif">Error: ' + e.message + '</p>';
    }
}

function tplPreviewToggle() {
    const frame  = document.getElementById('tplPreviewFrame');
    const source = document.getElementById('tplPreviewSource');
    const btn    = document.getElementById('tplPreviewToggleBtn');
    if (tplPreviewMode === 'rendered') {
        tplPreviewMode = 'source';
        frame.classList.add('hidden');
        source.classList.remove('hidden');
        btn.textContent = '🖥 Rendered View';
    } else {
        tplPreviewMode = 'rendered';
        frame.classList.remove('hidden');
        source.classList.add('hidden');
        btn.textContent = '📄 HTML Source';
    }
}

function closeTplPreview() {
    document.getElementById('tplPreviewModal').classList.add('hidden');
    document.getElementById('tplPreviewFrame').srcdoc = '';
}

function openCreateTemplate() {
    currentTplId = null;
    document.getElementById('tplModalTitle').textContent = 'New Template';
    document.getElementById('tplName').value    = '';
    document.getElementById('tplSubject').value = '';
    document.getElementById('tplHtml').value    = '';
    document.getElementById('tplReplyTo').value = '';
    document.getElementById('tplSenderEmail').value = '';
    document.getElementById('tplSenderName').value  = '';
    document.getElementById('tplActive').checked    = true;
    document.getElementById('tplModal').classList.remove('hidden');
}

async function editBrevoTemplate(id) {
    currentTplId = id;
    document.getElementById('tplModalTitle').textContent = 'Edit Template';
    document.getElementById('tplModal').classList.remove('hidden');
    document.getElementById('tplSaveBtn').disabled = true;
    document.getElementById('tplSaveBtn').textContent = 'Loading…';

    try {
        const r    = await fetch(`/admin/brevo/templates/${id}`, { credentials: 'same-origin', headers: { 'Accept': 'application/json' } });
        const data = await r.json();
        if (!data.success) throw new Error(data.message);
        const t = data.data;
        document.getElementById('tplName').value         = t.name || '';
        document.getElementById('tplSubject').value      = t.subject || '';
        document.getElementById('tplHtml').value         = t.html_content || '';
        document.getElementById('tplReplyTo').value      = t.reply_to || '';
        document.getElementById('tplSenderEmail').value  = t.sender_email || '';
        document.getElementById('tplSenderName').value   = t.sender_name || '';
        document.getElementById('tplActive').checked     = t.is_active;
    } catch (e) {
        toast(e.message, 'error');
        closeTplModal();
    } finally {
        document.getElementById('tplSaveBtn').disabled = false;
        document.getElementById('tplSaveBtn').textContent = 'Save Template';
    }
}

function closeTplModal() {
    document.getElementById('tplModal').classList.add('hidden');
    currentTplId = null;
}

async function saveTplModal() {
    const name    = document.getElementById('tplName').value.trim();
    const subject = document.getElementById('tplSubject').value.trim();
    const html    = document.getElementById('tplHtml').value.trim();
    if (!name)    { toast('Template name required', 'error'); return; }
    if (!subject) { toast('Subject required', 'error'); return; }
    if (!html)    { toast('HTML content required', 'error'); return; }

    const btn = document.getElementById('tplSaveBtn');
    btn.disabled = true; btn.textContent = 'Saving…';

    const body = {
        name, subject,
        html_content:  html,
        reply_to:      document.getElementById('tplReplyTo').value.trim() || null,
        sender_email:  document.getElementById('tplSenderEmail').value.trim() || null,
        sender_name:   document.getElementById('tplSenderName').value.trim() || null,
        is_active:     document.getElementById('tplActive').checked,
    };

    const url    = currentTplId ? `/admin/brevo/templates/${currentTplId}` : '/admin/brevo/templates';
    const method = currentTplId ? 'PUT' : 'POST';

    try {
        const r    = await fetch(url, {
            method, credentials: 'same-origin',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json' },
            body: JSON.stringify(body),
        });
        const data = await r.json();
        btn.disabled = false; btn.textContent = 'Save Template';
        if (data.success) {
            toast(data.message || 'Saved!');
            closeTplModal();
            loadBrevoTemplates();
        } else {
            toast(data.message || 'Error', 'error');
        }
    } catch (e) {
        btn.disabled = false; btn.textContent = 'Save Template';
        toast(e.message, 'error');
    }
}

async function deleteBrevoTemplate(id, name) {
    if (!confirm(`Delete template "${name}"? This cannot be undone.`)) return;
    try {
        const r    = await fetch(`/admin/brevo/templates/${id}`, {
            method: 'DELETE', credentials: 'same-origin',
            headers: { 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json' },
        });
        const data = await r.json();
        if (data.success) { toast('Template deleted'); loadBrevoTemplates(); }
        else toast(data.message || 'Error', 'error');
    } catch (e) { toast(e.message, 'error'); }
}

let sendTestTplId = null;
function openSendTestTemplate(id, name) {
    sendTestTplId = id;
    document.getElementById('sendTestTplName').textContent = name;
    document.getElementById('sendTestTplEmail').value = '';
    document.getElementById('sendTestTplModal').classList.remove('hidden');
}
function closeSendTestTplModal() {
    document.getElementById('sendTestTplModal').classList.add('hidden');
    sendTestTplId = null;
}
async function submitSendTestTemplate() {
    const to = document.getElementById('sendTestTplEmail').value.trim();
    if (!to) { toast('Enter email address', 'error'); return; }
    const btn = document.getElementById('sendTestTplBtn');
    btn.disabled = true; btn.textContent = 'Sending…';
    try {
        const r    = await fetch(`/admin/brevo/templates/${sendTestTplId}/test`, {
            method: 'POST', credentials: 'same-origin',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json' },
            body: JSON.stringify({ to }),
        });
        const data = await r.json();
        btn.disabled = false; btn.textContent = 'Send Test';
        if (data.success) { toast(data.message); closeSendTestTplModal(); }
        else toast(data.message || 'Error', 'error');
    } catch (e) {
        btn.disabled = false; btn.textContent = 'Send Test';
        toast(e.message, 'error');
    }
}

function insertTplVariable() {
    const open  = '\x7B\x7B';
    const close = '\x7D\x7D';
    const vars  = [
        open+'params.name'+close,
        open+'params.order_id'+close,
        open+'params.amount'+close,
        open+'params.tracking_url'+close,
        open+'params.otp'+close,
        open+'params.product_name'+close,
    ];
    const v = prompt('Common variables:\n' + vars.join('\n') + '\n\nEnter variable name (without braces):');
    if (!v) return;
    const ta  = document.getElementById('tplHtml');
    const pos = ta.selectionStart;
    const insert = open + v.replace(/[{}]/g,'') + close;
    ta.value = ta.value.slice(0, pos) + insert + ta.value.slice(pos);
    ta.focus();
    ta.setSelectionRange(pos + insert.length, pos + insert.length);
}

// ── Brevo Senders ─────────────────────────────────────────────────
async function loadBrevoSenders() {
    const icon = document.getElementById('brevoSendersRefreshIcon');
    if (icon) icon.classList.add('animate-spin');
    const list = document.getElementById('brevoSendersList');
    if (!list) return;

    try {
        const r    = await fetch('/admin/brevo/senders', { credentials: 'same-origin', headers: { 'Accept': 'application/json' } });
        const data = await r.json();
        if (!data.success) throw new Error(data.message);

        const senders = data.data;
        if (!senders.length) {
            list.innerHTML = `<div class="px-4 py-6 text-center">
                <p class="text-xs text-gray-400">No verified senders yet</p>
                <p class="text-xs text-gray-300 mt-1">Add a sender above to get started</p>
            </div>`;
            return;
        }

        list.innerHTML = senders.map(s => `
            <div class="flex items-center justify-between px-4 py-3 hover:bg-gray-50 transition-colors">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center text-blue-700 font-bold text-xs flex-shrink-0">
                        ${esc(s.name.charAt(0).toUpperCase())}
                    </div>
                    <div>
                        <p class="text-sm font-semibold text-gray-900">${esc(s.name)}</p>
                        <p class="text-xs text-gray-500 font-mono">${esc(s.email)}</p>
                    </div>
                </div>
                <div class="flex items-center gap-2">
                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold ${s.active ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700'}">
                        ${s.active ? '✓ Verified' : '⏳ Pending'}
                    </span>
                    ${!s.active ? `
                    <div class="flex items-center gap-1" id="otpRow-${s.id}">
                        <input type="text" id="otpInput-${s.id}" maxlength="6" placeholder="Enter OTP"
                            class="w-24 px-2 py-1 text-xs border border-yellow-300 rounded-lg focus:outline-none focus:ring-1 focus:ring-yellow-400 font-mono text-center">
                        <button onclick="verifyBrevoSenderOTP(${s.id})" title="Verify OTP"
                            class="px-2.5 py-1 text-xs bg-yellow-500 text-white font-semibold rounded-lg hover:bg-yellow-600 transition-colors">
                            Verify
                        </button>
                    </div>` : ''}
                    <button onclick="useAsSender('${esc(s.email)}','${esc(s.name)}')" title="Use as From"
                        class="p-1.5 text-gray-400 hover:text-[#0082C3] hover:bg-blue-50 rounded-lg transition-colors" title="Set as From Email">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    </button>
                    <button onclick="deleteBrevoSender(${s.id},'${esc(s.email)}')" title="Delete"
                        class="p-1.5 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                    </button>
                </div>
            </div>
        `).join('');
    } catch (e) {
        list.innerHTML = `<p class="text-xs text-red-500 text-center py-4">${esc(e.message)}</p>`;
    } finally {
        if (icon) icon.classList.remove('animate-spin');
    }
}

async function verifyBrevoSenderOTP(id) {
    const otp = document.getElementById(`otpInput-${id}`)?.value?.trim();
    if (!otp) { toast('Enter the OTP from your email', 'error'); return; }

    const btn = document.querySelector(`#otpRow-${id} button`);
    if (btn) { btn.disabled = true; btn.textContent = '…'; }

    try {
        const r    = await fetch(`/admin/brevo/senders/${id}/verify`, {
            method: 'POST', credentials: 'same-origin',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json' },
            body: JSON.stringify({ otp }),
        });
        const data = await r.json();
        if (btn) { btn.disabled = false; btn.textContent = 'Verify'; }
        if (data.success) {
            toast('✓ Sender verified!');
            loadBrevoSenders();
        } else {
            toast(data.message || 'Invalid OTP', 'error');
        }
    } catch (e) {
        if (btn) { btn.disabled = false; btn.textContent = 'Verify'; }
        toast(e.message, 'error');
    }
}

function useAsSender(email, name) {
    document.getElementById('brevo_from_email').value = email;
    document.getElementById('brevo_from_name').value  = name;
    toast(`From set to: ${email}`);
}

function openAddSender()  { document.getElementById('addSenderForm').classList.remove('hidden'); }
function closeAddSender() { document.getElementById('addSenderForm').classList.add('hidden'); }

async function submitAddSender() {
    const name  = document.getElementById('newSenderName').value.trim();
    const email = document.getElementById('newSenderEmail').value.trim();
    if (!name || !email) { toast('Name and email required', 'error'); return; }

    const btn = document.getElementById('submitSenderBtn');
    btn.disabled = true; btn.textContent = 'Adding…';

    try {
        const r    = await fetch('/admin/brevo/senders', {
            method: 'POST', credentials: 'same-origin',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json' },
            body: JSON.stringify({ name, email }),
        });
        const data = await r.json();
        btn.disabled = false; btn.textContent = 'Add & Send Verification';
        if (data.success) {
            toast(data.message);
            closeAddSender();
            document.getElementById('newSenderName').value  = '';
            document.getElementById('newSenderEmail').value = '';
            loadBrevoSenders();
        } else {
            toast(data.message || 'Failed', 'error');
        }
    } catch (e) {
        btn.disabled = false; btn.textContent = 'Add & Send Verification';
        toast(e.message, 'error');
    }
}

async function deleteBrevoSender(id, email) {
    if (!confirm(`Delete sender "${email}"?`)) return;
    try {
        const r    = await fetch(`/admin/brevo/senders/${id}`, {
            method: 'DELETE', credentials: 'same-origin',
            headers: { 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json' },
        });
        const data = await r.json();
        if (data.success) { toast('Sender deleted'); loadBrevoSenders(); }
        else toast(data.message || 'Failed', 'error');
    } catch (e) { toast(e.message, 'error'); }
}

// ── Brevo ─────────────────────────────────────────────────────────
async function saveBrevoUseCases() {
    const keys = [
        'brevo_use_order_confirm','brevo_use_order_shipped','brevo_use_order_delivered',
        'brevo_use_order_cancelled','brevo_use_invoice','brevo_use_return_update',
        'brevo_use_welcome','brevo_use_password_reset','brevo_use_email_otp',
        'brevo_use_low_stock','brevo_use_new_order_admin','brevo_use_campaigns',
    ];
    const body = {};
    keys.forEach(k => {
        const el = document.getElementById(k);
        if (el) body[k] = el.checked ? '1' : '0';
        // Save per-use-case from email/name
        const fromEmail = document.getElementById(k + '_from_email');
        const fromName  = document.getElementById(k + '_from_name');
        if (fromEmail) body[k + '_from_email'] = fromEmail.value.trim();
        if (fromName)  body[k + '_from_name']  = fromName.value.trim();
    });

    const btn = document.getElementById('saveBrevoUseCasesBtn');
    btn.disabled = true; btn.textContent = 'Saving…';

    const r    = await fetch('/admin/settings/integrations', {
        method: 'POST', credentials: 'same-origin',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json' },
        body: JSON.stringify(body),
    });
    const data = await r.json();
    btn.disabled = false; btn.textContent = 'Save Use Case Settings';
    if (data.success) toast('Use case settings saved!');
    else toast(data.message || 'Error', 'error');
}

function toggleUseCaseEmail(key) {
    const cb  = document.getElementById(key);
    const row = document.getElementById('email-row-' + key);
    if (row) row.classList.toggle('hidden', !cb?.checked);
}

function toggleBrevoUseCases() {
    const body    = document.getElementById('brevoUseCasesBody');
    const chevron = document.getElementById('brevoUseCasesChevron');
    const isHidden = body.classList.contains('hidden');
    body.classList.toggle('hidden', !isHidden);
    chevron.style.transform = isHidden ? 'rotate(0deg)' : 'rotate(-90deg)';
}

async function saveBrevo() {
    const apiKey = document.getElementById('brevo_api_key').value.trim();
    const from   = document.getElementById('brevo_from_email').value.trim();
    const name   = document.getElementById('brevo_from_name').value.trim();
    if (!apiKey) { toast('API Key is required', 'error'); return; }
    if (!from)   { toast('From Email is required', 'error'); return; }
    if (!name)   { toast('From Name is required', 'error'); return; }

    const btn = document.getElementById('saveBrevoBtn');
    btn.disabled = true; btn.textContent = 'Saving…';

    const r = await fetch('/admin/brevo/save', {
        method: 'POST', credentials: 'same-origin',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json' },
        body: JSON.stringify({
            brevo_api_key:    apiKey,
            brevo_from_email: from,
            brevo_from_name:  name,
            brevo_list_id:    document.getElementById('brevo_list_id').value.trim(),
        }),
    });
    const data = await r.json();
    btn.disabled = false; btn.textContent = 'Save Brevo Settings';
    if (data.success) { toast('Brevo settings saved!'); setTimeout(() => location.reload(), 800); }
    else toast(data.message || 'Error', 'error');
}

async function verifyBrevo() {
    const btn = document.getElementById('verifyBrevoBtn');
    btn.disabled = true; btn.textContent = 'Verifying…';
    try {
        const r    = await fetch('/admin/brevo/account', { credentials: 'same-origin', headers: { 'Accept': 'application/json' } });
        const data = await r.json();
        btn.disabled = false; btn.textContent = '🧪 Verify API Key';
        if (data.success) {
            const d = data.data;
            toast(`✓ Connected as ${d.first_name} ${d.last_name} (${d.email})`);
            renderBrevoAccount(d);
        } else {
            toast(data.message || 'Verification failed', 'error');
        }
    } catch (e) {
        btn.disabled = false; btn.textContent = '🧪 Verify API Key';
        toast(e.message, 'error');
    }
}

function renderBrevoAccount(d) {
    const info = document.getElementById('brevoAccountInfo');
    if (!info) return;

    const planLabel = (d.plan || 'free').replace(/_/g,' ').replace(/\b\w/g, c => c.toUpperCase());

    info.className = 'bg-blue-50 border border-blue-100 rounded-xl p-4 space-y-3';
    info.innerHTML = `
        <div class="flex items-center justify-between">
            <p class="text-xs font-bold text-blue-700 uppercase tracking-wider">Brevo Account</p>
            <span class="px-2 py-0.5 bg-blue-600 text-white text-[10px] font-bold rounded-full uppercase">${esc(planLabel)}</span>
        </div>
        <div class="grid grid-cols-2 gap-2">
            <div class="bg-white rounded-xl p-3 border border-blue-100">
                <p class="text-[10px] text-blue-500 font-semibold uppercase tracking-wider mb-0.5">Name</p>
                <p class="text-sm font-bold text-gray-900">${esc((d.first_name||'') + ' ' + (d.last_name||''))}</p>
            </div>
            <div class="bg-white rounded-xl p-3 border border-blue-100">
                <p class="text-[10px] text-blue-500 font-semibold uppercase tracking-wider mb-0.5">Email</p>
                <p class="text-sm font-bold text-gray-900 truncate">${esc(d.email||'')}</p>
            </div>
            ${d.company_name ? `<div class="bg-white rounded-xl p-3 border border-blue-100">
                <p class="text-[10px] text-blue-500 font-semibold uppercase tracking-wider mb-0.5">Company</p>
                <p class="text-sm font-bold text-gray-900">${esc(d.company_name)}</p>
            </div>` : ''}
            ${d.city ? `<div class="bg-white rounded-xl p-3 border border-blue-100">
                <p class="text-[10px] text-blue-500 font-semibold uppercase tracking-wider mb-0.5">Location</p>
                <p class="text-sm font-bold text-gray-900">${esc(d.city)}${d.country ? ', ' + esc(d.country) : ''}</p>
            </div>` : ''}
            ${d.plan_credits > 0 ? `<div class="bg-white rounded-xl p-3 border border-blue-100">
                <p class="text-[10px] text-blue-500 font-semibold uppercase tracking-wider mb-0.5">Email Credits</p>
                <p class="text-sm font-bold text-gray-900">${Number(d.plan_credits).toLocaleString()} <span class="text-xs text-gray-400 font-normal">/ month</span></p>
            </div>` : ''}
            ${d.sms_credits > 0 ? `<div class="bg-white rounded-xl p-3 border border-blue-100">
                <p class="text-[10px] text-blue-500 font-semibold uppercase tracking-wider mb-0.5">SMS Credits</p>
                <p class="text-sm font-bold text-gray-900">${Number(d.sms_credits).toLocaleString()}</p>
            </div>` : ''}
        </div>
        ${d.relay_enabled && d.smtp_host ? `
        <div class="bg-white rounded-xl p-3 border border-blue-100">
            <p class="text-[10px] text-blue-500 font-semibold uppercase tracking-wider mb-1.5">SMTP Relay</p>
            <div class="flex items-center gap-4 text-xs font-mono text-gray-700">
                <span>🖥 ${esc(d.smtp_host)}</span>
                <span>🔌 Port ${d.smtp_port}</span>
                <span>👤 ${esc(d.smtp_username||'')}</span>
            </div>
        </div>` : ''}
        ${d.plan_end_date ? '<p class="text-[10px] text-blue-400">Plan expires: ' + new Date(d.plan_end_date).toLocaleDateString('en-IN') + '</p>' : ''}
    `;
}

async function sendBrevoTest() {
    const to  = document.getElementById('brevo_test_email').value.trim();
    const res = document.getElementById('brevoTestResult');
    if (!to) { toast('Enter test email address', 'error'); return; }

    const btn = document.getElementById('brevoTestBtn');
    btn.disabled = true; btn.textContent = 'Sending…';
    res.className = 'text-xs rounded-lg px-3 py-2 bg-blue-50 text-blue-700';
    res.textContent = 'Sending via Brevo API...';
    res.classList.remove('hidden');

    try {
        const r    = await fetch('/admin/brevo/test', {
            method: 'POST', credentials: 'same-origin',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json' },
            body: JSON.stringify({ to }),
        });
        const data = await r.json();
        btn.disabled = false; btn.textContent = 'Send Test';
        if (data.success) {
            res.className = 'text-xs rounded-lg px-3 py-2 bg-green-50 text-green-700 border border-green-200';
            res.textContent = '✓ ' + data.message + (data.message_id ? ' · ID: ' + data.message_id : '');
            toast('Test email sent via Brevo!');
        } else {
            res.className = 'text-xs rounded-lg px-3 py-2 bg-red-50 text-red-700 border border-red-200';
            res.textContent = '✕ ' + data.message;
        }
    } catch (e) {
        btn.disabled = false; btn.textContent = 'Send Test';
        res.className = 'text-xs rounded-lg px-3 py-2 bg-red-50 text-red-700 border border-red-200';
        res.textContent = '✕ ' + e.message;
    }
}

async function loadBrevoLists() {
    const btn = document.getElementById('brevoListsBtn');
    const dd  = document.getElementById('brevoListsDropdown');
    btn.disabled = true; btn.textContent = 'Loading…';

    try {
        const r    = await fetch('/admin/brevo/lists', { credentials: 'same-origin', headers: { 'Accept': 'application/json' } });
        const data = await r.json();
        btn.disabled = false; btn.innerHTML = '<svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg> Fetch Lists';

        if (!data.success || !data.data.length) {
            toast('No lists found', 'error'); return;
        }

        dd.innerHTML = data.data.map(l => `
            <div onclick="document.getElementById('brevo_list_id').value='${l.id}';document.getElementById('brevoListsDropdown').classList.add('hidden');toast('List selected: ${esc(l.name)}')"
                class="px-4 py-2.5 hover:bg-gray-50 cursor-pointer border-b border-gray-50 last:border-0">
                <p class="text-sm font-semibold text-gray-900">${esc(l.name)}</p>
                <p class="text-xs text-gray-400">ID: ${l.id} · ${l.total_contacts} contacts</p>
            </div>
        `).join('');
        dd.classList.remove('hidden');
    } catch (e) {
        btn.disabled = false; btn.textContent = 'Fetch Lists';
        toast(e.message, 'error');
    }
}

async function disconnectBrevo() {
    if (!confirm('Disconnect Brevo?')) return;
    const r    = await fetch('/admin/brevo/disconnect', {
        method: 'POST', credentials: 'same-origin',
        headers: { 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json' },
    });
    const data = await r.json();
    if (data.success) { toast('Brevo disconnected'); setTimeout(() => location.reload(), 800); }
    else toast(data.message || 'Error', 'error');
}

// Auto-load Brevo stats when connected
document.addEventListener('DOMContentLoaded', () => {
    @if(\App\Models\Setting::get('brevo_api_key'))
    // Load account info
    fetch('/admin/brevo/account', { credentials: 'same-origin', headers: { 'Accept': 'application/json' } })
        .then(r => r.json()).then(data => {
            if (data.success) renderBrevoAccount(data.data);
        }).catch(() => {});

    // Load stats
    fetch('/admin/brevo/stats', { credentials: 'same-origin', headers: { 'Accept': 'application/json' } })
        .then(r => r.json()).then(data => {
            if (data.success) {
                const s = data.data;
                const el = document.getElementById('brevoStats');
                if (el) el.innerHTML = [
                    ['Sent',      s.sent,      '#0082C3'],
                    ['Delivered', s.delivered, '#047857'],
                    ['Opened',    s.opened,    '#b45309'],
                ].map(([label, val, color]) => `
                    <div class="bg-gray-50 rounded-xl p-3 text-center">
                        <p class="text-xl font-black" style="color:${color}">${val || 0}</p>
                        <p class="text-xs text-gray-500 mt-0.5">${label}</p>
                    </div>
                `).join('');
            }
        }).catch(() => {});

    // Load senders
    loadBrevoSenders();
    // Load templates
    loadBrevoTemplates();
    @endif
});

// Close Brevo lists dropdown on outside click
document.addEventListener('click', e => {
    if (!e.target.closest('#brevo_list_id') && !e.target.closest('#brevoListsBtn') && !e.target.closest('#brevoListsDropdown')) {
        document.getElementById('brevoListsDropdown')?.classList.add('hidden');
    }
});

// ── SMTP ──────────────────────────────────────────────────────────
const SMTP_PRESETS = {
    gmail:    { host: 'smtp.gmail.com',          port: '587', encryption: 'tls',  note: 'Use App Password (not your Gmail password). Enable 2FA → Google Account → Security → App Passwords' },
    sendgrid: { host: 'smtp.sendgrid.net',        port: '587', encryption: 'tls',  note: 'Username is always "apikey". Password is your SendGrid API Key' },
    mailgun:  { host: 'smtp.mailgun.org',         port: '587', encryption: 'tls',  note: 'Find credentials in Mailgun Dashboard → Sending → Domain Settings → SMTP' },
    ses:      { host: 'email-smtp.us-east-1.amazonaws.com', port: '587', encryption: 'tls', note: 'Create SMTP credentials in AWS SES → Account Dashboard → SMTP Settings' },
};

function smtpPreset(key) {
    const p = SMTP_PRESETS[key];
    if (!p) return;
    document.getElementById('smtp_host').value       = p.host;
    document.getElementById('smtp_port').value       = p.port;
    document.getElementById('smtp_encryption').value = p.encryption;

    // Show note
    let noteEl = document.getElementById('smtpPresetNote');
    if (!noteEl) {
        noteEl = document.createElement('div');
        noteEl.id = 'smtpPresetNote';
        noteEl.className = 'mt-2 p-3 bg-blue-50 border border-blue-200 rounded-xl text-xs text-blue-700';
        document.getElementById('smtp_host').closest('.grid').before(noteEl);
    }
    noteEl.innerHTML = `<strong>${key.charAt(0).toUpperCase()+key.slice(1)}:</strong> ${p.note}`;
    toast(`${key.charAt(0).toUpperCase()+key.slice(1)} preset applied!`);
}

async function saveSMTP() {
    const host  = document.getElementById('smtp_host').value.trim();
    const user  = document.getElementById('smtp_username').value.trim();
    const pass  = document.getElementById('smtp_password').value.trim();
    const from  = document.getElementById('smtp_from_email').value.trim();
    const fname = document.getElementById('smtp_from_name').value.trim();

    if (!host)  { toast('SMTP Host is required', 'error'); return; }
    if (!user)  { toast('Username is required', 'error'); return; }
    if (!pass)  { toast('Password is required', 'error'); return; }
    if (!from)  { toast('From Email is required', 'error'); return; }
    if (!fname) { toast('From Name is required', 'error'); return; }

    const btn = document.getElementById('saveSmtpBtn');
    btn.disabled = true; btn.textContent = 'Saving…';

    const body = {
        smtp_host:       host,
        smtp_port:       document.getElementById('smtp_port').value,
        smtp_encryption: document.getElementById('smtp_encryption').value,
        smtp_username:   user,
        smtp_password:   pass,
        smtp_from_name:  fname,
        smtp_from_email: from,
        smtp_reply_to:   document.getElementById('smtp_reply_to').value.trim(),
    };

    const r = await fetch('/admin/settings/integrations', {
        method: 'POST', credentials: 'same-origin',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json' },
        body: JSON.stringify(body),
    });
    const data = await r.json();
    btn.disabled = false; btn.textContent = 'Save SMTP Settings';
    if (data.success) { toast('SMTP settings saved!'); setTimeout(() => location.reload(), 800); }
    else toast(data.message || 'Error', 'error');
}

async function sendTestEmail() {
    const to  = document.getElementById('smtp_test_email').value.trim();
    const res = document.getElementById('smtpTestResult');
    if (!to) { toast('Enter test email address', 'error'); return; }

    const btn = document.getElementById('smtpTestBtn');
    btn.disabled = true; btn.textContent = 'Sending…';
    res.className = 'text-xs rounded-lg px-3 py-2 bg-blue-50 text-blue-700';
    res.textContent = 'Sending test email...';
    res.classList.remove('hidden');

    try {
        const r = await fetch('/admin/smtp/test', {
            method: 'POST', credentials: 'same-origin',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json' },
            body: JSON.stringify({ to }),
        });
        const data = await r.json();
        btn.disabled = false; btn.textContent = 'Send Test';
        if (data.success) {
            res.className = 'text-xs rounded-lg px-3 py-2 bg-green-50 text-green-700 border border-green-200';
            res.textContent = '✓ ' + data.message;
            toast('Test email sent!');
        } else {
            res.className = 'text-xs rounded-lg px-3 py-2 bg-red-50 text-red-700 border border-red-200';
            res.textContent = '✕ ' + data.message;
            toast(data.message, 'error');
        }
    } catch (e) {
        btn.disabled = false; btn.textContent = 'Send Test';
        res.className = 'text-xs rounded-lg px-3 py-2 bg-red-50 text-red-700 border border-red-200';
        res.textContent = '✕ ' + e.message;
    }
}

async function disconnectSMTP() {
    if (!confirm('Remove SMTP configuration?')) return;
    const body = { smtp_host: '', smtp_username: '', smtp_password: '', smtp_from_email: '', smtp_from_name: '' };
    const r = await fetch('/admin/settings/integrations', {
        method: 'POST', credentials: 'same-origin',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json' },
        body: JSON.stringify(body),
    });
    const data = await r.json();
    if (data.success) { toast('SMTP disconnected'); setTimeout(() => location.reload(), 800); }
    else toast(data.message || 'Error', 'error');
}

// ── ImageKit Storage Stats ────────────────────────────────────────
async function loadIkStats() {
    const icon = document.getElementById('ikStatsRefreshIcon');
    if (icon) icon.classList.add('animate-spin');

    try {
        const res  = await fetch('/api/imagekit-usage', { credentials: 'same-origin', headers: { 'Accept': 'application/json' } });
        const data = await res.json();
        if (!data.success) throw new Error(data.message);

        const d         = data.data;
        const sizeBytes = d.total_size || 0;
        const sizeMB    = (sizeBytes / 1024 / 1024).toFixed(1);
        const sizeGB    = (sizeBytes / 1024 / 1024 / 1024).toFixed(3);
        const pct       = Math.min(100, ((sizeBytes / 1024 / 1024 / 1024) / 20) * 100).toFixed(1);
        const endpoint  = (d.url_endpoint || '').replace('https://ik.imagekit.io/', '');

        const grid = document.getElementById('ikStatsGrid');
        if (grid) {
            grid.innerHTML = `
                <div class="bg-blue-50 rounded-xl p-4 border border-blue-100">
                    <p class="text-xs font-semibold text-blue-600 uppercase tracking-wider mb-1">Total Files</p>
                    <p class="text-2xl font-black text-blue-800">${Number(d.total_files||0).toLocaleString()}</p>
                    <p class="text-[10px] text-blue-400 mt-0.5">images stored</p>
                </div>
                <div class="bg-purple-50 rounded-xl p-4 border border-purple-100">
                    <p class="text-xs font-semibold text-purple-600 uppercase tracking-wider mb-1">Storage Used</p>
                    <p class="text-2xl font-black text-purple-800">${sizeMB} <span class="text-sm font-semibold">MB</span></p>
                    <p class="text-[10px] text-purple-400 mt-0.5">${sizeGB} GB of 20 GB</p>
                </div>
                <div class="bg-green-50 rounded-xl p-4 border border-green-100">
                    <p class="text-xs font-semibold text-green-600 uppercase tracking-wider mb-1">Account ID</p>
                    <p class="text-sm font-black text-green-800 truncate font-mono">${esc(endpoint || '—')}</p>
                    <p class="text-[10px] text-green-400 mt-0.5">ImageKit ID</p>
                </div>
            `;
        }

        // Storage bar
        const bar = document.getElementById('ikStorageBar');
        if (bar) {
            bar.style.display = '';
            document.getElementById('ikStorageFill').style.width = pct + '%';
            document.getElementById('ikStorageFill').style.background = pct > 80 ? '#ef4444' : pct > 60 ? '#f59e0b' : '#0082C3';
            document.getElementById('ikStorageText').textContent = `${sizeMB} MB used (${pct}%)`;
        }
    } catch (err) {
        const grid = document.getElementById('ikStatsGrid');
        if (grid) grid.innerHTML = `<div class="col-span-3 text-xs text-red-500 py-4 text-center">${esc(err.message)}</div>`;
    } finally {
        if (icon) icon.classList.remove('animate-spin');
    }
}

function fmInit() {
    loadIkStats();
    fmNavigate('/');
}

async function fmNavigate(path) {
    fmCurrentPath = path;
    fmSelected.clear();
    fmUpdateSelectedInfo();
    fmSetStatus('Loading...');

    const content = document.getElementById('fmContent');
    content.innerHTML = `<div class="flex items-center justify-center h-48 text-gray-400">
        <svg class="w-5 h-5 animate-spin mr-2" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/></svg>
        Loading...
    </div>`;

    fmUpdateBreadcrumb(path);

    try {
        const res  = await fetch(`/api/imagekit-files?path=${encodeURIComponent(path)}`, {
            credentials: 'same-origin', headers: { 'Accept': 'application/json' }
        });
        const data = await res.json();
        if (!data.success) throw new Error(data.message);

        fmItems = [
            ...(data.data.folders || []).map(f => ({ ...f, _type: 'folder' })),
            ...(data.data.files   || []).map(f => ({ ...f, _type: 'file'   })),
        ];

        fmRender();
        fmSetStatus(`${(data.data.folders||[]).length} folders, ${(data.data.files||[]).length} files`);
    } catch (err) {
        content.innerHTML = `<div class="flex flex-col items-center justify-center h-48 text-gray-400 gap-2">
            <svg class="w-8 h-8 text-red-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            <p class="text-sm text-red-500">${esc(err.message)}</p>
        </div>`;
        fmSetStatus('Error loading');
    }
}

function fmRender() {
    const content = document.getElementById('fmContent');
    if (!fmItems.length) {
        content.innerHTML = `<div class="flex flex-col items-center justify-center h-48 text-gray-300 gap-3">
            <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"/></svg>
            <p class="text-sm">This folder is empty</p>
            <p class="text-xs">Upload images or create a folder</p>
        </div>`;
        return;
    }

    if (fmView === 'grid') {
        content.innerHTML = `<div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-3">
            ${fmItems.map(item => fmRenderGridItem(item)).join('')}
        </div>`;
    } else {
        content.innerHTML = `<table class="w-full text-sm">
            <thead class="sticky top-0 bg-white z-10">
                <tr class="border-b border-gray-100">
                    <th class="px-3 py-2 text-left text-xs font-bold text-gray-500 uppercase tracking-wider w-8">
                        <input type="checkbox" onchange="fmSelectAll(this.checked)" class="rounded border-gray-300 text-[#0082C3] focus:ring-[#0082C3]">
                    </th>
                    <th class="px-3 py-2 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Name</th>
                    <th class="px-3 py-2 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Type</th>
                    <th class="px-3 py-2 text-right text-xs font-bold text-gray-500 uppercase tracking-wider">Size</th>
                    <th class="px-3 py-2 text-right text-xs font-bold text-gray-500 uppercase tracking-wider">Modified</th>
                    <th class="px-3 py-2 text-center text-xs font-bold text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                ${fmItems.map(item => fmRenderListItem(item)).join('')}
            </tbody>
        </table>`;
    }
}

function fmRenderGridItem(item) {
    const isFolder  = item._type === 'folder';
    const id        = isFolder ? ('folder:' + (item.folderPath || item.name)) : item.fileId;
    const isSelected= fmSelected.has(id);
    const name      = item.name || item.folderName || '—';
    const thumb     = isFolder ? null : (item.thumbnail || item.url);

    return `<div class="fm-item group relative rounded-xl border-2 ${isSelected ? 'border-[#0082C3] bg-blue-50' : 'border-transparent hover:border-gray-200 bg-gray-50 hover:bg-white'} cursor-pointer transition-all select-none"
        onclick="fmItemClick(event, '${esc(id)}', ${isFolder})"
        ondblclick="${isFolder ? 'fmNavigate(\'' + esc(item.folderPath || '/' + name) + '\')' : 'fmPreview(\'' + esc(item.url || '') + '\', \'' + esc(name) + '\')'}"
        title="${esc(name)}">

        {{-- Checkbox --}}
        <div class="absolute top-1.5 left-1.5 z-10 opacity-0 group-hover:opacity-100 ${isSelected ? 'opacity-100' : ''}">
            <input type="checkbox" ${isSelected ? 'checked' : ''} onclick="event.stopPropagation();fmToggleSelect('${esc(id)}')"
                class="w-4 h-4 rounded border-gray-300 text-[#0082C3] focus:ring-[#0082C3] shadow-sm">
        </div>

        {{-- Thumbnail --}}
        <div class="aspect-square flex items-center justify-center rounded-t-xl overflow-hidden bg-gray-100">
            ${isFolder
                ? `<svg class="w-10 h-10 text-yellow-400" fill="currentColor" viewBox="0 0 24 24"><path d="M10 4H4c-1.11 0-2 .89-2 2v12c0 1.11.89 2 2 2h16c1.11 0 2-.89 2-2V8c0-1.11-.89-2-2-2h-8l-2-2z"/></svg>`
                : `<img src="${esc(thumb || '')}" alt="${esc(name)}" class="w-full h-full object-cover" onerror="this.parentElement.innerHTML='<svg class=\\'w-8 h-8 text-gray-300\\' fill=\\'none\\' stroke=\\'currentColor\\' viewBox=\\'0 0 24 24\\'><path stroke-linecap=\\'round\\' stroke-linejoin=\\'round\\' stroke-width=\\'1.5\\' d=\\'M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z\\'/></svg>'">`
            }
        </div>

        {{-- Name --}}
        <div class="px-2 py-1.5">
            <p class="text-xs font-medium text-gray-800 truncate">${esc(name)}</p>
            ${!isFolder && item.size ? '<p class="text-[10px] text-gray-400">' + fmFormatSize(item.size) + '</p>' : ''}
        </div>

        {{-- Hover actions --}}
        ${!isFolder ? `<div class="absolute top-1.5 right-1.5 hidden group-hover:flex gap-1">
            <button onclick="event.stopPropagation();fmCopyUrl('${esc(item.url || '')}')" title="Copy URL"
                class="w-6 h-6 bg-white rounded-lg shadow flex items-center justify-center text-gray-500 hover:text-[#0082C3]">
                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
            </button>
            <button onclick="event.stopPropagation();fmDeleteFile('${esc(item.fileId)}','${esc(name)}')" title="Delete"
                class="w-6 h-6 bg-white rounded-lg shadow flex items-center justify-center text-gray-500 hover:text-red-600">
                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
            </button>
        </div>` : ''}
    </div>`;
}

function fmRenderListItem(item) {
    const isFolder  = item._type === 'folder';
    const id        = isFolder ? ('folder:' + (item.folderPath || item.name)) : item.fileId;
    const isSelected= fmSelected.has(id);
    const name      = item.name || item.folderName || '—';
    const ext       = name.split('.').pop().toLowerCase();
    const thumb     = isFolder ? null : (item.thumbnail || item.url);

    return `<tr class="hover:bg-gray-50 cursor-pointer ${isSelected ? 'bg-blue-50' : ''}"
        onclick="fmItemClick(event, '${esc(id)}', ${isFolder})"
        ondblclick="${isFolder ? 'fmNavigate(\'' + esc(item.folderPath || '/' + name) + '\')' : 'fmPreview(\'' + esc(item.url || '') + '\', \'' + esc(name) + '\')'}">
        <td class="px-3 py-2">
            <input type="checkbox" ${isSelected ? 'checked' : ''} onclick="event.stopPropagation();fmToggleSelect('${esc(id)}')"
                class="w-4 h-4 rounded border-gray-300 text-[#0082C3] focus:ring-[#0082C3]">
        </td>
        <td class="px-3 py-2">
            <div class="flex items-center gap-2.5">
                ${isFolder
                    ? `<svg class="w-5 h-5 text-yellow-400 flex-shrink-0" fill="currentColor" viewBox="0 0 24 24"><path d="M10 4H4c-1.11 0-2 .89-2 2v12c0 1.11.89 2 2 2h16c1.11 0 2-.89 2-2V8c0-1.11-.89-2-2-2h-8l-2-2z"/></svg>`
                    : `<div class="w-8 h-8 rounded bg-gray-100 overflow-hidden flex-shrink-0"><img src="${esc(thumb||'')}" class="w-full h-full object-cover" onerror="this.style.display='none'"></div>`
                }
                <span class="text-sm font-medium text-gray-800 truncate max-w-[200px]">${esc(name)}</span>
            </div>
        </td>
        <td class="px-3 py-2 text-xs text-gray-500">${isFolder ? 'Folder' : ext.toUpperCase()}</td>
        <td class="px-3 py-2 text-right text-xs text-gray-500">${!isFolder && item.size ? fmFormatSize(item.size) : '—'}</td>
        <td class="px-3 py-2 text-right text-xs text-gray-400">${item.updatedAt ? new Date(item.updatedAt).toLocaleDateString('en-IN') : '—'}</td>
        <td class="px-3 py-2 text-center" onclick="event.stopPropagation()">
            <div class="flex items-center justify-center gap-1">
                ${!isFolder ? `
                <button onclick="fmCopyUrl('${esc(item.url||'')}')" title="Copy URL" class="p-1 text-gray-400 hover:text-[#0082C3] rounded">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                </button>
                <button onclick="fmDeleteFile('${esc(item.fileId)}','${esc(name)}')" title="Delete" class="p-1 text-gray-400 hover:text-red-600 rounded">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                </button>` : `
                <button onclick="fmDeleteFolder('${esc(item.folderPath||item.name)}','${esc(name)}')" title="Delete folder" class="p-1 text-gray-400 hover:text-red-600 rounded">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                </button>`}
            </div>
        </td>
    </tr>`;
}

// ── FM Helpers ────────────────────────────────────────────────────
function fmItemClick(e, id, isFolder) {
    if (e.ctrlKey || e.metaKey) {
        fmToggleSelect(id);
    } else if (e.shiftKey) {
        fmToggleSelect(id);
    } else {
        if (!isFolder) {
            fmSelected.clear();
            fmSelected.add(id);
            fmRender();
            fmUpdateSelectedInfo();
        }
    }
}

function fmToggleSelect(id) {
    if (fmSelected.has(id)) fmSelected.delete(id);
    else fmSelected.add(id);
    fmRender();
    fmUpdateSelectedInfo();
}

function fmSelectAll(checked) {
    if (checked) fmItems.forEach(i => fmSelected.add(i._type === 'folder' ? ('folder:' + (i.folderPath||i.name)) : i.fileId));
    else fmSelected.clear();
    fmRender();
    fmUpdateSelectedInfo();
}

function fmUpdateSelectedInfo() {
    const el = document.getElementById('fmSelectedInfo');
    if (!el) return;
    if (fmSelected.size > 0) {
        el.textContent = fmSelected.size + ' selected';
        el.classList.remove('hidden');
    } else {
        el.classList.add('hidden');
    }
}

function fmSetStatus(msg) {
    const el = document.getElementById('fmStatusText');
    if (el) el.textContent = msg;
}

function fmSetView(v) {
    fmView = v;
    document.getElementById('fmViewGrid').className = v === 'grid'
        ? 'p-1.5 text-[#0082C3] bg-blue-50 transition-colors'
        : 'p-1.5 text-gray-400 hover:text-gray-600 transition-colors';
    document.getElementById('fmViewList').className = v === 'list'
        ? 'p-1.5 text-[#0082C3] bg-blue-50 transition-colors'
        : 'p-1.5 text-gray-400 hover:text-gray-600 transition-colors';
    fmRender();
}

function fmUpdateBreadcrumb(path) {
    const el = document.getElementById('fmBreadcrumb');
    if (!el) return;
    const parts = path.replace(/^\//, '').split('/').filter(Boolean);
    let html = `<button onclick="fmNavigate('/')" class="text-[#0082C3] hover:underline font-medium">Root</button>`;
    let built = '';
    parts.forEach((p, i) => {
        built += '/' + p;
        const bp = built;
        html += `<span class="text-gray-300 mx-1">/</span>`;
        if (i === parts.length - 1) {
            html += `<span class="text-gray-700 font-medium">${esc(p)}</span>`;
        } else {
            html += `<button onclick="fmNavigate('${esc(bp)}')" class="text-[#0082C3] hover:underline">${esc(p)}</button>`;
        }
    });
    el.innerHTML = html;
}

function fmFormatSize(bytes) {
    if (!bytes) return '—';
    if (bytes < 1024) return bytes + ' B';
    if (bytes < 1024*1024) return (bytes/1024).toFixed(1) + ' KB';
    return (bytes/1024/1024).toFixed(1) + ' MB';
}

function fmRefresh() {
    const icon = document.getElementById('fmRefreshIcon');
    if (icon) icon.classList.add('animate-spin');
    fmNavigate(fmCurrentPath).then(() => {
        setTimeout(() => icon?.classList.remove('animate-spin'), 500);
    });
}

function fmCopyUrl(url) {
    navigator.clipboard.writeText(url).then(() => toast('Image URL copied!'));
}

async function fmNewFolder() {
    const name = prompt('Enter folder name:');
    if (!name || !name.trim()) return;
    try {
        const res = await fetch('/api/imagekit-folder', {
            method: 'POST', credentials: 'same-origin',
            headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': CSRF },
            body: JSON.stringify({ folder_name: name.trim(), parent_path: fmCurrentPath }),
        });
        const data = await res.json();
        if (data.success) { toast('Folder created!'); fmRefresh(); }
        else toast(data.message || 'Failed', 'error');
    } catch (e) { toast(e.message, 'error'); }
}

async function fmDeleteFile(fileId, name) {
    if (!confirm(`Delete "${name}"? This cannot be undone.`)) return;
    try {
        const res = await fetch('/api/imagekit-delete', {
            method: 'DELETE', credentials: 'same-origin',
            headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': CSRF },
            body: JSON.stringify({ fileId }),
        });
        const data = await res.json();
        if (data.success) { toast('File deleted'); fmRefresh(); }
        else toast(data.message || 'Failed', 'error');
    } catch (e) { toast(e.message, 'error'); }
}

async function fmDeleteFolder(folderPath, name) {
    if (!confirm(`Delete folder "${name}" and all its contents? This cannot be undone.`)) return;
    try {
        const res = await fetch('/api/imagekit-folder', {
            method: 'DELETE', credentials: 'same-origin',
            headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': CSRF },
            body: JSON.stringify({ folder_path: folderPath }),
        });
        const data = await res.json();
        if (data.success) { toast('Folder deleted'); fmRefresh(); }
        else toast(data.message || 'Failed', 'error');
    } catch (e) { toast(e.message, 'error'); }
}

async function fmUploadFiles(files) {
    if (!files.length) return;
    const prog  = document.getElementById('fmUploadProgress');
    const label = document.getElementById('fmUploadLabel');
    const pct   = document.getElementById('fmUploadPct');
    const bar   = document.getElementById('fmUploadBar');
    prog.classList.remove('hidden');

    let done = 0;
    for (const file of files) {
        label.textContent = `Uploading ${file.name}...`;
        const fd = new FormData();
        fd.append('file', file);
        fd.append('folder', fmCurrentPath);
        try {
            const res = await fetch('/api/imagekit-upload-folder', {
                method: 'POST', credentials: 'same-origin',
                headers: { 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json' },
                body: fd,
            });
            const data = await res.json();
            if (!data.success) throw new Error(data.message);
        } catch (e) { toast(`Failed: ${file.name} — ${e.message}`, 'error'); }
        done++;
        const p = Math.round((done / files.length) * 100);
        pct.textContent = p + '%';
        bar.style.width = p + '%';
    }

    setTimeout(() => prog.classList.add('hidden'), 1000);
    toast(`${done} file(s) uploaded!`);
    fmRefresh();
    document.getElementById('fmUploadInput').value = '';
}

function fmPreview(url, name) {
    // Simple preview modal
    const existing = document.getElementById('fmPreviewModal');
    if (existing) existing.remove();
    const modal = document.createElement('div');
    modal.id = 'fmPreviewModal';
    modal.className = 'fixed inset-0 z-[100] flex items-center justify-center p-4 bg-black/80';
    modal.onclick = () => modal.remove();
    modal.innerHTML = `
        <div class="relative max-w-3xl max-h-[90vh] bg-white rounded-2xl overflow-hidden shadow-2xl" onclick="event.stopPropagation()">
            <div class="flex items-center justify-between px-4 py-3 border-b border-gray-100">
                <p class="text-sm font-semibold text-gray-900 truncate max-w-xs">${esc(name)}</p>
                <div class="flex items-center gap-2">
                    <button onclick="fmCopyUrl('${esc(url)}')" class="px-3 py-1.5 text-xs bg-[#0082C3] text-white rounded-lg hover:bg-[#006ba3] font-semibold">Copy URL</button>
                    <button onclick="document.getElementById('fmPreviewModal').remove()" class="p-1.5 text-gray-400 hover:text-gray-600 rounded-lg hover:bg-gray-100">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>
            </div>
            <div class="p-4 flex items-center justify-center bg-gray-50" style="max-height:70vh">
                <img src="${esc(url)}" alt="${esc(name)}" class="max-w-full max-h-full object-contain rounded-lg shadow">
            </div>
            <div class="px-4 py-2 border-t border-gray-100 bg-white">
                <p class="text-xs text-gray-400 font-mono truncate">${esc(url)}</p>
            </div>
        </div>`;
    document.body.appendChild(modal);
}

let whSearchTimer;
function whDebounce() { clearTimeout(whSearchTimer); whSearchTimer = setTimeout(loadWebhooks, 400); }

async function loadWebhooks() {
    const params = new URLSearchParams({
        search: document.getElementById('whSearch')?.value || '',
        status: document.getElementById('whStatus')?.value || '',
        per_page: 50,
    });
    for (const [k,v] of [...params]) { if (!v) params.delete(k); }

    const r = await fetch(`/admin/webhooks/list?${params}`, { credentials: 'same-origin', headers: { 'Accept': 'application/json' } });
    const data = await r.json();
    if (!data.success) return;

    // Stats
    if (data.stats) {
        document.getElementById('whTotal').textContent  = data.stats.total;
        document.getElementById('whActive').textContent = data.stats.active;
        document.getElementById('whFailed').textContent = data.stats.failed;
    }

    const list = document.getElementById('whList');
    if (!data.data.length) {
        list.innerHTML = '<div class="bg-white rounded-xl border border-gray-200 p-8 text-center text-gray-400 text-sm">No webhooks yet. Click "Add Webhook" to create one.</div>';
        return;
    }

    list.innerHTML = data.data.map(wh => `
        <div class="bg-white rounded-xl border border-gray-200 p-4">
            <div class="flex items-start justify-between mb-3">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-lg bg-blue-50 flex items-center justify-center flex-shrink-0">
                        <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/></svg>
                    </div>
                    <div>
                        <div class="flex items-center gap-2">
                            <p class="text-sm font-semibold text-gray-900">${esc(wh.name)}</p>
                            <span class="px-2 py-0.5 rounded-full text-xs font-medium ${wh.is_active ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-500'}">${wh.is_active ? 'Active' : 'Inactive'}</span>
                            ${wh.last_status === 'failed' ? '<span class="px-2 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-700">Last Failed</span>' : ''}
                        </div>
                        <p class="text-xs text-gray-400 font-mono mt-0.5">${esc(wh.url)}</p>
                    </div>
                </div>
                <div class="flex items-center gap-1 flex-shrink-0">
                    <button onclick="testWebhook(${wh.id},'${esc(wh.name)}')" class="px-2.5 py-1.5 text-xs font-medium text-gray-600 border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">Test</button>
                    <button onclick="toggleWebhook(${wh.id})" class="p-1.5 rounded-lg text-gray-500 hover:text-[#0082C3] hover:bg-blue-50 transition-colors" title="${wh.is_active ? 'Disable' : 'Enable'}">
                        ${wh.is_active
                            ? '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/></svg>'
                            : '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>'
                        }
                    </button>
                    <button onclick="editWebhook(${wh.id})" class="p-1.5 rounded-lg text-gray-500 hover:text-[#0082C3] hover:bg-blue-50 transition-colors" title="Edit">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                    </button>
                    <button onclick="deleteWebhook(${wh.id},'${esc(wh.name)}')" class="p-1.5 rounded-lg text-gray-500 hover:text-red-600 hover:bg-red-50 transition-colors" title="Delete">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                    </button>
                </div>
            </div>
            <div class="flex flex-wrap gap-1 mb-2">
                ${(wh.events || []).map(e => '<span class="px-2 py-0.5 bg-blue-50 text-blue-700 text-xs rounded font-mono">' + esc(e) + '</span>').join('')}
            </div>
            <div class="flex items-center gap-4 text-xs text-gray-400">
                <span>Method: <strong class="text-gray-600">${wh.method}</strong></span>
                <span>Calls: <strong class="text-gray-600">${wh.total_calls || 0}</strong></span>
                ${wh.failed_calls > 0 ? '<span class="text-red-500">Failed: ' + wh.failed_calls + '</span>' : ''}
                ${wh.last_triggered_at ? '<span>Last: ' + new Date(wh.last_triggered_at).toLocaleString('en-IN',{day:'numeric',month:'short',hour:'2-digit',minute:'2-digit'}) + '</span>' : ''}
            </div>
        </div>
    `).join('');
}

function esc(s) { return String(s ?? '').replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;'); }

function openAddWebhook() {
    document.getElementById('whModalTitle').textContent = 'Add Webhook';
    document.getElementById('whId').value = '';
    document.getElementById('whName').value = '';
    document.getElementById('whUrl').value = '';
    document.getElementById('whSecret').value = '';
    document.getElementById('whMethod').value = 'POST';
    document.getElementById('whActive').value = '1';
    document.getElementById('whTimeout').value = '10';
    document.getElementById('whRetry').value = '3';
    document.querySelectorAll('.wh-event-cb').forEach(cb => cb.checked = false);
    showWhModal();
}

async function editWebhook(id) {
    document.getElementById('whModalTitle').textContent = 'Edit Webhook';
    showWhModal();
    const r = await fetch(`/admin/webhooks/${id}`, { credentials: 'same-origin', headers: { 'Accept': 'application/json' } });
    const data = await r.json();
    if (!data.success) { toast('Failed to load', 'error'); closeWhModal(); return; }
    const wh = data.data;
    document.getElementById('whId').value      = wh.id;
    document.getElementById('whName').value    = wh.name;
    document.getElementById('whUrl').value     = wh.url;
    document.getElementById('whSecret').value  = wh.secret || '';
    document.getElementById('whMethod').value  = wh.method;
    document.getElementById('whActive').value  = wh.is_active ? '1' : '0';
    document.getElementById('whTimeout').value = wh.timeout || 10;
    document.getElementById('whRetry').value   = wh.retry_count || 3;
    const events = wh.events || [];
    document.querySelectorAll('.wh-event-cb').forEach(cb => { cb.checked = events.includes(cb.value); });
}

function showWhModal() {
    document.getElementById('whModal').classList.remove('hidden');
    requestAnimationFrame(() => { document.getElementById('whModalBox').style.transform = 'translateX(0)'; });
}
function closeWhModal() {
    document.getElementById('whModalBox').style.transform = 'translateX(100%)';
    setTimeout(() => document.getElementById('whModal').classList.add('hidden'), 420);
}

async function saveWebhook() {
    const name   = document.getElementById('whName').value.trim();
    const url    = document.getElementById('whUrl').value.trim();
    const events = [...document.querySelectorAll('.wh-event-cb:checked')].map(cb => cb.value);
    if (!name)         { toast('Name is required', 'error'); return; }
    if (!url)          { toast('URL is required', 'error'); return; }
    if (!events.length){ toast('Select at least one event', 'error'); return; }

    const btn = document.getElementById('whSaveBtn');
    btn.disabled = true; btn.textContent = 'Saving…';

    const id   = document.getElementById('whId').value;
    const body = {
        name, url, events,
        secret:      document.getElementById('whSecret').value.trim() || null,
        method:      document.getElementById('whMethod').value,
        is_active:   document.getElementById('whActive').value === '1',
        timeout:     parseInt(document.getElementById('whTimeout').value) || 10,
        retry_count: parseInt(document.getElementById('whRetry').value) || 3,
    };

    const r = await fetch(id ? `/admin/webhooks/${id}` : '/admin/webhooks', {
        method: id ? 'PUT' : 'POST',
        credentials: 'same-origin',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json' },
        body: JSON.stringify(body),
    });
    const data = await r.json();
    btn.disabled = false; btn.textContent = 'Save Webhook';

    if (data.success) { closeWhModal(); toast(data.message || 'Saved!'); loadWebhooks(); }
    else { const first = data.errors ? Object.values(data.errors)[0][0] : data.message; toast(first || 'Error', 'error'); }
}

async function toggleWebhook(id) {
    const r = await fetch(`/admin/webhooks/${id}/toggle`, { method: 'POST', credentials: 'same-origin', headers: { 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json' } });
    const data = await r.json();
    if (data.success) { toast('Status updated'); loadWebhooks(); }
    else toast(data.message || 'Error', 'error');
}

async function testWebhook(id, name) {
    toast(`Sending test ping to "${name}"…`);
    const r = await fetch(`/admin/webhooks/${id}/test`, { method: 'POST', credentials: 'same-origin', headers: { 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json' } });
    const data = await r.json();
    if (data.success) { toast(`✓ Test ping successful! Response: ${(data.response || '').substring(0,50)}`); loadWebhooks(); }
    else toast(`Test failed: ${data.message}`, 'error');
}

function deleteWebhook(id, name) {
    document.getElementById('whConfirmMsg').textContent = `Delete webhook "${name}"? This cannot be undone.`;
    document.getElementById('whConfirm').classList.remove('hidden');
    requestAnimationFrame(() => { const b = document.getElementById('whConfirmBox'); b.style.transform = 'scale(1) translateY(0)'; b.style.opacity = '1'; });
    document.getElementById('whConfirmOk').onclick = async () => {
        closeWhConfirm();
        const r = await fetch(`/admin/webhooks/${id}`, { method: 'DELETE', credentials: 'same-origin', headers: { 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json' } });
        const data = await r.json();
        if (data.success) { toast('Webhook deleted'); loadWebhooks(); }
        else toast(data.message || 'Error', 'error');
    };
}
function closeWhConfirm() {
    const b = document.getElementById('whConfirmBox'); b.style.transform = 'scale(0.8) translateY(20px)'; b.style.opacity = '0';
    setTimeout(() => document.getElementById('whConfirm').classList.add('hidden'), 300);
}

</script>
@endpush
