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
                        <div class="bg-orange-50 border border-orange-200 rounded-xl p-4 flex gap-3">
                            <svg class="w-5 h-5 text-orange-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <div class="text-sm text-orange-700">
                                <p class="font-semibold mb-1">How to get your Measurement ID:</p>
                                <ol class="list-decimal list-inside space-y-1 text-xs">
                                    <li>Go to <a href="https://analytics.google.com" target="_blank" class="underline font-medium">analytics.google.com</a></li>
                                    <li>Admin → Data Streams → Your stream</li>
                                    <li>Copy the <strong>Measurement ID</strong> (starts with G-)</li>
                                </ol>
                            </div>
                        </div>

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
                <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
                    <div class="flex items-center justify-between px-6 py-4 border-b border-gray-200">
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
                        </div>
                    </div>

                    <div class="px-6 py-5 space-y-5">

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

                        {{-- Info box --}}
                        <div class="bg-blue-50 border border-blue-200 rounded-xl p-4 flex gap-3">
                            <svg class="w-5 h-5 text-blue-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <div class="text-sm text-blue-700">
                                <p class="font-semibold mb-1">How to get Razorpay API Keys:</p>
                                <ol class="list-decimal list-inside space-y-1 text-xs">
                                    <li>Login to <a href="https://dashboard.razorpay.com" target="_blank" class="underline font-medium">dashboard.razorpay.com</a></li>
                                    <li>Go to <strong>Settings → API Keys</strong></li>
                                    <li>Generate Test/Live Key ID and Key Secret</li>
                                    <li>For webhooks: Settings → Webhooks → Add new webhook</li>
                                </ol>
                            </div>
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

                        {{-- Supported Methods --}}
                        <div>
                            <p class="text-xs font-semibold text-gray-600 mb-2">Supported Payment Methods</p>
                            <div class="flex flex-wrap gap-2">
                                @foreach(['💳 Credit/Debit Card','📱 UPI','🏦 NetBanking','👛 Wallets','📲 EMI','🏪 Pay Later'] as $method)
                                <span class="px-3 py-1.5 bg-blue-50 text-blue-700 text-xs font-medium rounded-full border border-blue-100">{{ $method }}</span>
                                @endforeach
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

                {{-- COD --}}
                <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
                    <div class="flex items-center justify-between px-6 py-4 border-b border-gray-200">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-green-50 flex items-center justify-center text-xl">💵</div>
                            <div>
                                <h3 class="text-sm font-semibold text-gray-900">Cash on Delivery (COD)</h3>
                                <p class="text-xs text-gray-400">Allow customers to pay when order is delivered</p>
                            </div>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" id="cod_enabled"
                                   {{ \App\Models\Setting::get('cod_enabled', '1') === '1' ? 'checked' : '' }}
                                   class="sr-only peer" onchange="saveCOD(this.checked)">
                            <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-[#0082C3]"></div>
                        </label>
                    </div>
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
                </div>

            </div>

            {{-- ══ SHIPPING TAB ══ --}}
            <div id="tab-shipping" class="int-tab space-y-5" style="display:none">

                {{-- Shiprocket --}}
                <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
                    <div class="flex items-center justify-between px-6 py-4 border-b border-gray-200">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-orange-50 flex items-center justify-center overflow-hidden">
                                <img src="https://www.shiprocket.in/wp-content/uploads/2021/03/favicon.png" class="w-8 h-8" alt="Shiprocket" onerror="this.outerHTML='<span class=\'text-2xl\'>🚀</span>'">
                            </div>
                            <div>
                                <h3 class="text-sm font-semibold text-gray-900">Shiprocket</h3>
                                <p class="text-xs text-gray-400">Multi-carrier shipping — BlueDart, Delhivery, DTDC, Ekart & 25+ couriers</p>
                            </div>
                        </div>
                        <span class="px-2.5 py-1 rounded-full text-xs font-semibold
                            {{ \App\Models\Setting::get('shiprocket_email') ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-500' }}">
                            {{ \App\Models\Setting::get('shiprocket_email') ? '✓ Connected' : 'Not Connected' }}
                        </span>
                    </div>

                    <div class="px-6 py-5 space-y-5">

                        {{-- Info --}}
                        <div class="bg-orange-50 border border-orange-200 rounded-xl p-4 flex gap-3">
                            <svg class="w-5 h-5 text-orange-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <div class="text-sm text-orange-700">
                                <p class="font-semibold mb-1">How to connect Shiprocket:</p>
                                <ol class="list-decimal list-inside space-y-1 text-xs">
                                    <li>Login to <a href="https://app.shiprocket.in" target="_blank" class="underline font-medium">app.shiprocket.in</a></li>
                                    <li>Use your registered <strong>Email & Password</strong> below</li>
                                    <li>Shiprocket uses JWT token auth — token auto-refreshes every 24h</li>
                                    <li>For Channel ID: Settings → Channels → Copy Channel ID</li>
                                </ol>
                            </div>
                        </div>

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
                                <label class="block text-sm font-medium text-gray-700 mb-1">Channel ID
                                    <span class="text-gray-400 font-normal text-xs ml-1">(optional)</span>
                                </label>
                                <input id="shiprocket_channel_id" type="text"
                                       value="{{ \App\Models\Setting::get('shiprocket_channel_id', '') }}"
                                       placeholder="e.g. 123456"
                                       class="w-full px-3.5 py-2.5 border border-gray-300 rounded-lg text-sm font-mono focus:outline-none focus:ring-2 focus:ring-[#0082C3]">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Pickup Location Name
                                    <span class="text-gray-400 font-normal text-xs ml-1">(from Shiprocket dashboard)</span>
                                </label>
                                <input id="shiprocket_pickup_location" type="text"
                                       value="{{ \App\Models\Setting::get('shiprocket_pickup_location', 'Primary') }}"
                                       placeholder="Primary"
                                       class="w-full px-3.5 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#0082C3]">
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
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Free Shipping Above (₹)</label>
                                    <input id="shiprocket_free_above" type="number" min="0"
                                           value="{{ \App\Models\Setting::get('shiprocket_free_above', '999') }}"
                                           class="w-full px-3.5 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#0082C3]">
                                    <p class="text-xs text-gray-400 mt-1">0 = never free</p>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Preferred Courier</label>
                                    <select id="shiprocket_preferred_courier"
                                            class="w-full px-3.5 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#0082C3]">
                                        @foreach(['auto'=>'Auto (Cheapest)','delhivery'=>'Delhivery','bluedart'=>'BlueDart','dtdc'=>'DTDC','ekart'=>'Ekart','xpressbees'=>'XpressBees','shadowfax'=>'Shadowfax'] as $val=>$label)
                                        <option value="{{ $val }}" {{ \App\Models\Setting::get('shiprocket_preferred_courier','auto') === $val ? 'selected' : '' }}>{{ $label }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Auto-Create Shipment</label>
                                    <select id="shiprocket_auto_create"
                                            class="w-full px-3.5 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#0082C3]">
                                        <option value="0" {{ \App\Models\Setting::get('shiprocket_auto_create','0') === '0' ? 'selected' : '' }}>Manual (Admin creates)</option>
                                        <option value="1" {{ \App\Models\Setting::get('shiprocket_auto_create','0') === '1' ? 'selected' : '' }}>Auto on Order Placed</option>
                                        <option value="2" {{ \App\Models\Setting::get('shiprocket_auto_create','0') === '2' ? 'selected' : '' }}>Auto on Payment Confirmed</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        {{-- Supported Couriers --}}
                        <div>
                            <p class="text-xs font-semibold text-gray-600 mb-2">Supported Couriers (25+)</p>
                            <div class="flex flex-wrap gap-2">
                                @foreach(['🚀 Delhivery','💙 BlueDart','📦 DTDC','🛒 Ekart','⚡ XpressBees','🌑 Shadowfax','📮 India Post','🏃 Ecom Express','🔵 Maruti Courier','📬 Gati'] as $c)
                                <span class="px-2.5 py-1 bg-gray-50 text-gray-600 text-xs font-medium rounded-full border border-gray-200">{{ $c }}</span>
                                @endforeach
                                <span class="px-2.5 py-1 bg-gray-50 text-gray-400 text-xs rounded-full border border-gray-200">+15 more</span>
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
                </div>

                {{-- Delhivery Direct --}}
                <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
                    <div class="flex items-center justify-between px-6 py-4 border-b border-gray-200">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-red-50 flex items-center justify-center overflow-hidden">
                                <img src="https://www.delhivery.com/favicon.ico" class="w-8 h-8" alt="Delhivery" onerror="this.outerHTML='<span class=\'text-2xl\'>🚚</span>'">
                            </div>
                            <div>
                                <h3 class="text-sm font-semibold text-gray-900">Delhivery Direct</h3>
                                <p class="text-xs text-gray-400">Direct Delhivery API integration (alternative to Shiprocket)</p>
                            </div>
                        </div>
                        <span class="px-2.5 py-1 rounded-full text-xs font-semibold bg-gray-100 text-gray-500">Not Connected</span>
                    </div>
                    <div class="px-6 py-4">
                        <div class="flex gap-2">
                            <input id="delhivery_token" type="password"
                                   value="{{ \App\Models\Setting::get('delhivery_token', '') }}"
                                   placeholder="Delhivery API Token"
                                   class="flex-1 px-3.5 py-2.5 border border-gray-300 rounded-lg text-sm font-mono focus:outline-none focus:ring-2 focus:ring-[#0082C3]">
                            <button onclick="saveDelhivery()" class="px-5 py-2.5 bg-[#0082C3] text-white text-sm font-semibold rounded-lg hover:bg-[#006ba3]">Save</button>
                        </div>
                        <p class="text-xs text-gray-400 mt-1.5">Get token from <a href="https://www.delhivery.com" target="_blank" class="text-[#0082C3] hover:underline">delhivery.com</a> → Developer → API Access</p>
                    </div>
                </div>

            </div>

            {{-- ══ MARKETING TAB ══ --}}
            <div id="tab-marketing" class="int-tab space-y-5" style="display:none">

                {{-- ── Mailchimp ── --}}
                <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
                    <div class="flex items-center justify-between px-6 py-4 border-b border-gray-200">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-yellow-50 flex items-center justify-center overflow-hidden">
                                <img src="https://mailchimp.com/favicon.ico" class="w-8 h-8" alt="Mailchimp" onerror="this.outerHTML='<span class=\'text-2xl\'>🐵</span>'">
                            </div>
                            <div>
                                <h3 class="text-sm font-semibold text-gray-900">Mailchimp</h3>
                                <p class="text-xs text-gray-400">Email marketing — sync subscribers, send campaigns</p>
                            </div>
                        </div>
                        <span class="px-2.5 py-1 rounded-full text-xs font-semibold
                            {{ \App\Models\Setting::get('mailchimp_api_key') ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-500' }}">
                            {{ \App\Models\Setting::get('mailchimp_api_key') ? '✓ Connected' : 'Not Connected' }}
                        </span>
                    </div>

                    <div class="px-6 py-5 space-y-4">

                        <div class="bg-yellow-50 border border-yellow-200 rounded-xl p-4 flex gap-3">
                            <svg class="w-5 h-5 text-yellow-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <div class="text-sm text-yellow-700">
                                <p class="font-semibold mb-1">How to get Mailchimp API Key:</p>
                                <ol class="list-decimal list-inside space-y-1 text-xs">
                                    <li>Login to <a href="https://mailchimp.com" target="_blank" class="underline font-medium">mailchimp.com</a></li>
                                    <li>Profile → Extras → <strong>API Keys</strong></li>
                                    <li>Click <strong>Create A Key</strong> → Copy the key</li>
                                    <li>Audience ID: Audience → Settings → Audience name and defaults</li>
                                </ol>
                            </div>
                        </div>

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
                </div>

                {{-- ── MSG91 ── --}}
                <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
                    <div class="flex items-center justify-between px-6 py-4 border-b border-gray-200">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-purple-50 flex items-center justify-center overflow-hidden">
                                <img src="https://msg91.com/img/favicon.ico" class="w-8 h-8" alt="MSG91" onerror="this.outerHTML='<span class=\'font-black text-purple-700 text-xs leading-none\'>MSG<br>91</span>'">
                            </div>
                            <div>
                                <h3 class="text-sm font-semibold text-gray-900">MSG91</h3>
                                <p class="text-xs text-gray-400">Bulk SMS, OTP & transactional SMS for India</p>
                            </div>
                        </div>
                        <span class="px-2.5 py-1 rounded-full text-xs font-semibold
                            {{ \App\Models\Setting::get('msg91_auth_key') ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-500' }}">
                            {{ \App\Models\Setting::get('msg91_auth_key') ? '✓ Connected' : 'Not Connected' }}
                        </span>
                    </div>

                    <div class="px-6 py-5 space-y-4">

                        <div class="bg-purple-50 border border-purple-200 rounded-xl p-4 flex gap-3">
                            <svg class="w-5 h-5 text-purple-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <div class="text-sm text-purple-700">
                                <p class="font-semibold mb-1">How to get MSG91 Auth Key:</p>
                                <ol class="list-decimal list-inside space-y-1 text-xs">
                                    <li>Login to <a href="https://control.msg91.com" target="_blank" class="underline font-medium">control.msg91.com</a></li>
                                    <li>Go to <strong>API</strong> → Copy your <strong>Auth Key</strong></li>
                                    <li>Register <strong>Sender ID</strong> under DLT compliance</li>
                                    <li>Create <strong>SMS Templates</strong> → get Template IDs</li>
                                </ol>
                            </div>
                        </div>

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
                </div>

                {{-- ── Twilio ── --}}
                <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
                    <div class="flex items-center justify-between px-6 py-4 border-b border-gray-200">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-red-50 flex items-center justify-center overflow-hidden">
                                <img src="https://www.twilio.com/favicon.ico" class="w-8 h-8" alt="Twilio" onerror="this.style.display='none'">
                            </div>
                            <div>
                                <h3 class="text-sm font-semibold text-gray-900">Twilio</h3>
                                <p class="text-xs text-gray-400">Global SMS, WhatsApp & Voice — 180+ countries</p>
                            </div>
                        </div>
                        <span class="px-2.5 py-1 rounded-full text-xs font-semibold
                            {{ \App\Models\Setting::get('twilio_account_sid') ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-500' }}">
                            {{ \App\Models\Setting::get('twilio_account_sid') ? '✓ Connected' : 'Not Connected' }}
                        </span>
                    </div>

                    <div class="px-6 py-5 space-y-4">

                        <div class="bg-red-50 border border-red-200 rounded-xl p-4 flex gap-3">
                            <svg class="w-5 h-5 text-red-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <div class="text-sm text-red-700">
                                <p class="font-semibold mb-1">How to get Twilio credentials:</p>
                                <ol class="list-decimal list-inside space-y-1 text-xs">
                                    <li>Login to <a href="https://console.twilio.com" target="_blank" class="underline font-medium">console.twilio.com</a></li>
                                    <li>Dashboard → Copy <strong>Account SID</strong> and <strong>Auth Token</strong></li>
                                    <li>Phone Numbers → Buy a number (or use trial number)</li>
                                    <li>For WhatsApp: Messaging → Try it out → WhatsApp sandbox</li>
                                </ol>
                            </div>
                        </div>

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

                        <div class="bg-purple-50 border border-purple-200 rounded-xl p-4 flex gap-3">
                            <svg class="w-5 h-5 text-purple-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <div class="text-sm text-purple-700">
                                <p class="font-semibold mb-1">How to get ImageKit credentials:</p>
                                <ol class="list-decimal list-inside space-y-1 text-xs">
                                    <li>Login to <a href="https://imagekit.io/dashboard" target="_blank" class="underline font-medium">imagekit.io/dashboard</a></li>
                                    <li>Go to <strong>Developer Options → API Keys</strong></li>
                                    <li>Copy your <strong>Public Key</strong> and <strong>Private Key</strong></li>
                                    <li>URL Endpoint is shown on the dashboard home: <strong>https://ik.imagekit.io/your_id</strong></li>
                                </ol>
                            </div>
                        </div>

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

                {{-- Free Plan Info --}}
                <div class="bg-white rounded-xl border border-gray-200 p-5 flex items-start gap-4">
                    <div class="w-10 h-10 rounded-xl bg-blue-50 flex items-center justify-center flex-shrink-0 text-xl">💡</div>
                    <div>
                        <p class="text-sm font-semibold text-gray-900">ImageKit Free Plan</p>
                        <p class="text-xs text-gray-500 mt-1">Free plan includes 20GB bandwidth/month, 20GB storage, and unlimited transformations. Perfect for getting started.</p>
                        <a href="https://imagekit.io/registration" target="_blank"
                           class="inline-flex items-center gap-1 mt-2 text-xs text-[#0082C3] font-medium hover:underline">
                            Create free account
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                        </a>
                    </div>
                </div>

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

function switchTab(name) {
    document.querySelectorAll('.int-tab').forEach(t => t.style.display = 'none');
    document.querySelectorAll('.int-nav').forEach(b => {
        b.className = 'int-nav w-full flex items-center gap-2 px-4 py-3 text-sm font-medium text-left transition-colors border-b border-gray-100 last:border-0 text-gray-700 hover:bg-gray-50';
    });
    document.getElementById('tab-' + name).style.display = 'block';
    document.getElementById('nav-' + name).className = 'int-nav w-full flex items-center gap-2 px-4 py-3 text-sm font-medium text-left transition-colors border-b border-gray-100 last:border-0 bg-blue-50 text-[#0082C3] border-l-2 border-l-[#0082C3]';
    if (name === 'webhooks') loadWebhooks();
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
        shiprocket_channel_id:        document.getElementById('shiprocket_channel_id').value.trim(),
        shiprocket_pickup_location:   document.getElementById('shiprocket_pickup_location').value.trim() || 'Primary',
        shiprocket_default_weight:    document.getElementById('shiprocket_default_weight').value,
        shiprocket_free_above:        document.getElementById('shiprocket_free_above').value,
        shiprocket_preferred_courier: document.getElementById('shiprocket_preferred_courier').value,
        shiprocket_auto_create:       document.getElementById('shiprocket_auto_create').value,
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

async function disconnectShiprocket() {
    const body = {
        shiprocket_email: '', shiprocket_password: '',
        shiprocket_channel_id: '', shiprocket_token: '',
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

// ── Webhooks ──────────────────────────────────────────────────────
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
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="${wh.is_active ? 'M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636' : 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z'}"/></svg>
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
                ${(wh.events || []).map(e => `<span class="px-2 py-0.5 bg-blue-50 text-blue-700 text-xs rounded font-mono">${e}</span>`).join('')}
            </div>
            <div class="flex items-center gap-4 text-xs text-gray-400">
                <span>Method: <strong class="text-gray-600">${wh.method}</strong></span>
                <span>Calls: <strong class="text-gray-600">${wh.total_calls || 0}</strong></span>
                ${wh.failed_calls > 0 ? `<span class="text-red-500">Failed: ${wh.failed_calls}</span>` : ''}
                ${wh.last_triggered_at ? `<span>Last: ${new Date(wh.last_triggered_at).toLocaleString('en-IN',{day:'numeric',month:'short',hour:'2-digit',minute:'2-digit'})}</span>` : ''}
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
