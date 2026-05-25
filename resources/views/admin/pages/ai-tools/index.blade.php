@extends('admin.layouts.app')
@section('title', 'AI Tools')

@php
$aiProvider = \App\Models\Setting::get('ai_provider', '');
$aiKeySet   = !empty(\App\Models\Setting::get('ai_api_key', ''));
$providerNames = ['openai'=>'ChatGPT (OpenAI)','gemini'=>'Gemini (Google)','claude'=>'Claude (Anthropic)','custom'=>'Custom / Local'];
$currentProviderName = $providerNames[$aiProvider] ?? 'Not configured';
@endphp

@section('content')
<div class="space-y-6">

    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">AI Tools</h1>
            <p class="text-sm text-gray-500 mt-0.5">AI-powered tools to boost your store's content and SEO</p>
        </div>
        @if($aiProvider && $aiKeySet)
        <span class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-green-100 text-green-700 text-xs font-semibold rounded-full">
            <svg class="w-3 h-3 fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
            {{ $currentProviderName }}
        </span>
        @else
        <span class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-yellow-100 text-yellow-700 text-xs font-semibold rounded-full">
            ⚠️ AI Not Configured
        </span>
        @endif
    </div>

    <div class="flex gap-6">
        <div class="w-52 flex-shrink-0">
            <nav class="bg-white rounded-xl border border-gray-200 overflow-hidden sticky top-4">
                @foreach(['setup'=>'AI Setup','description'=>'Product Description','seo'=>'SEO Generator','tags'=>'Tag Suggester','image'=>'Image Alt Text','usage'=>'Usage'] as $key=>$label)
                @php
                $icons = [
                    'setup'       => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>',
                    'description' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>',
                    'seo'         => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>',
                    'tags'        => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>',
                    'image'       => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>',
                    'usage'       => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>',
                ];
                @endphp
                <button onclick="switchTab('{{$key}}')" id="nav-{{$key}}"
                        class="ai-nav w-full flex items-center gap-2 px-4 py-3 text-sm font-medium text-left transition-colors border-b border-gray-100 last:border-0
                               {{ $key === 'setup' ? 'bg-blue-50 text-[#0082C3] border-l-2 border-l-[#0082C3]' : 'text-gray-700 hover:bg-gray-50' }}">
                    <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">{!! $icons[$key] !!}</svg>
                    <span>{{$label}}</span>
                    @if($key === 'setup' && (!$aiProvider || !$aiKeySet))
                    <span class="ml-auto w-2 h-2 rounded-full bg-yellow-400 flex-shrink-0"></span>
                    @endif
                </button>
                @endforeach
            </nav>
        </div>

        <div class="flex-1 min-w-0">

            {{-- ══ SETUP TAB ══ --}}
            <div id="tab-setup" class="ai-tab space-y-5">

                {{-- Provider Selection --}}
                <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-sm font-semibold text-gray-900">Choose AI Provider</h3>
                        <p class="text-xs text-gray-400 mt-0.5">Select which AI service to use for content generation</p>
                    </div>
                    <div class="p-6 grid grid-cols-2 gap-4">

                        @foreach([
                            ['id'=>'openai',  'name'=>'ChatGPT','company'=>'OpenAI',    'model'=>'GPT-4o / GPT-3.5','color'=>'green', 'desc'=>'Best for creative writing, product descriptions, SEO content','link'=>'https://platform.openai.com/api-keys',
                             'svg'=>'<img src="https://upload.wikimedia.org/wikipedia/commons/0/04/ChatGPT_logo.svg" class="w-7 h-7" alt="OpenAI">'],
                            ['id'=>'gemini',  'name'=>'Gemini', 'company'=>'Google',    'model'=>'Gemini 2.5 Flash','color'=>'blue',  'desc'=>'Great for multilingual content, Hindi/regional language support','link'=>'https://aistudio.google.com/app/apikey',
                             'svg'=>'<img src="https://www.gstatic.com/lamda/images/gemini_sparkle_v002_d4735304ff6292a690345.svg" class="w-7 h-7" alt="Gemini">'],
                            ['id'=>'claude',  'name'=>'Claude', 'company'=>'Anthropic', 'model'=>'Claude 3.5 Sonnet','color'=>'orange','desc'=>'Excellent for long-form content, detailed product descriptions','link'=>'https://console.anthropic.com/settings/keys',
                             'svg'=>'<img src="https://upload.wikimedia.org/wikipedia/commons/8/8a/Claude_AI_logo.svg" class="w-7 h-7" alt="Claude">'],
                            ['id'=>'custom',  'name'=>'Custom', 'company'=>'Self-hosted','model'=>'Any OpenAI-compatible','color'=>'gray','desc'=>'Use your own API endpoint (Ollama, LM Studio, etc.)','link'=>null,
                             'svg'=>'<svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h14M12 5l7 7-7 7"/></svg>'],
                        ] as $p)
                        <div id="provider-card-{{ $p['id'] }}"
                             onclick="selectProvider('{{ $p['id'] }}')"
                             class="provider-card relative p-4 border-2 rounded-xl cursor-pointer transition-all hover:border-[#0082C3]
                                    {{ ($aiProvider === $p['id']) ? 'border-[#0082C3] bg-blue-50' : 'border-gray-200' }}">
                            @if($aiProvider === $p['id'])
                            <div class="absolute top-3 right-3 w-5 h-5 bg-[#0082C3] rounded-full flex items-center justify-center">
                                <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                            </div>
                            @endif
                            <div class="flex items-center gap-3 mb-2">
                                <div class="w-10 h-10 rounded-xl bg-{{ $p['color'] }}-50 flex items-center justify-center text-{{ $p['color'] }}-600 flex-shrink-0">
                                    {!! $p['svg'] !!}
                                </div>
                                <div>
                                    <p class="text-sm font-bold text-gray-900">{{ $p['name'] }}</p>
                                    <p class="text-xs text-gray-400">{{ $p['company'] }} · {{ $p['model'] }}</p>
                                </div>
                            </div>
                            <p class="text-xs text-gray-500">{{ $p['desc'] }}</p>
                            @if($p['link'])
                            <a href="{{ $p['link'] }}" target="_blank" onclick="event.stopPropagation()"
                               class="inline-flex items-center gap-1 mt-2 text-xs text-[#0082C3] hover:underline">
                                Get API Key
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                            </a>
                            @endif
                        </div>
                        @endforeach

                    </div>
                </div>

                {{-- API Key Config --}}
                <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-sm font-semibold text-gray-900">API Configuration</h3>
                    </div>
                    <div class="px-6 py-5 space-y-4">

                        {{-- Provider-specific instructions --}}
                        <div id="provider-hint" class="rounded-xl p-4 text-sm
                            {{ $aiProvider ? 'bg-blue-50 border border-blue-200 text-blue-700' : 'bg-gray-50 border border-gray-200 text-gray-500' }}">
                            @if(!$aiProvider)
                            <p>👆 Select an AI provider above first</p>
                            @elseif($aiProvider === 'openai')
                            <p><strong>OpenAI:</strong> Get your key from <a href="https://platform.openai.com/api-keys" target="_blank" class="underline">platform.openai.com/api-keys</a>. Key starts with <code class="bg-blue-100 px-1 rounded">sk-</code></p>
                            @elseif($aiProvider === 'gemini')
                            <p><strong>Google Gemini:</strong> Get your key from <a href="https://aistudio.google.com/app/apikey" target="_blank" class="underline">aistudio.google.com</a>. Key starts with <code class="bg-blue-100 px-1 rounded">AIza</code></p>
                            @elseif($aiProvider === 'claude')
                            <p><strong>Anthropic Claude:</strong> Get your key from <a href="https://console.anthropic.com/settings/keys" target="_blank" class="underline">console.anthropic.com</a>. Key starts with <code class="bg-blue-100 px-1 rounded">sk-ant-</code></p>
                            @elseif($aiProvider === 'custom')
                            <p><strong>Custom:</strong> Enter your OpenAI-compatible API endpoint URL and key below</p>
                            @endif
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div class="{{ $aiProvider === 'custom' ? '' : 'col-span-2' }}">
                                <label class="block text-sm font-medium text-gray-700 mb-1">
                                    API Key <span class="text-red-500">*</span>
                                    <span class="text-gray-400 font-normal text-xs ml-1">(stored encrypted in DB)</span>
                                </label>
                                <div class="relative">
                                    <input id="ai_api_key" type="password"
                                           value="{{ $aiKeySet ? str_repeat('•', 32) : '' }}"
                                           placeholder="{{ $aiProvider === 'openai' ? 'sk-...' : ($aiProvider === 'gemini' ? 'AIza...' : ($aiProvider === 'claude' ? 'sk-ant-...' : 'Your API key')) }}"
                                           class="w-full px-3.5 py-2.5 border border-gray-300 rounded-lg text-sm font-mono focus:outline-none focus:ring-2 focus:ring-[#0082C3] pr-10">
                                    <button onclick="toggleVisibility('ai_api_key')" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                    </button>
                                </div>
                            </div>
                            @if($aiProvider === 'custom')
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">API Endpoint URL</label>
                                <input id="ai_endpoint" type="text"
                                       value="{{ \App\Models\Setting::get('ai_endpoint', '') }}"
                                       placeholder="http://localhost:11434/v1"
                                       class="w-full px-3.5 py-2.5 border border-gray-300 rounded-lg text-sm font-mono focus:outline-none focus:ring-2 focus:ring-[#0082C3]">
                            </div>
                            @endif
                        </div>

                        @if($aiProvider === 'openai')
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Model</label>
                            <select id="ai_model" class="w-full px-3.5 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#0082C3]">
                                @foreach(['gpt-4o'=>'GPT-4o (Recommended)','gpt-4o-mini'=>'GPT-4o Mini (Faster, cheaper)','gpt-4-turbo'=>'GPT-4 Turbo','gpt-3.5-turbo'=>'GPT-3.5 Turbo (Budget)'] as $v=>$l)
                                <option value="{{ $v }}" {{ \App\Models\Setting::get('ai_model','gpt-4o') === $v ? 'selected' : '' }}>{{ $l }}</option>
                                @endforeach
                            </select>
                        </div>
                        @elseif($aiProvider === 'gemini')
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Model</label>
                            <select id="ai_model" class="w-full px-3.5 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#0082C3]">
                                @foreach([
                                    'gemini-2.5-flash'        => 'Gemini 2.5 Flash ⚡ (Recommended)',
                                    'gemini-2.5-pro'          => 'Gemini 2.5 Pro (Most capable)',
                                    'gemini-3.5-flash'        => 'Gemini 3.5 Flash (Latest)',
                                    'gemini-3.1-pro-preview'  => 'Gemini 3.1 Pro Preview',
                                    'gemini-3.1-flash-lite'   => 'Gemini 3.1 Flash-Lite (Fast)',
                                    'gemini-2.0-flash'        => 'Gemini 2.0 Flash',
                                    'gemini-2.0-flash-lite'   => 'Gemini 2.0 Flash-Lite',
                                    'gemini-flash-latest'     => 'Gemini Flash Latest',
                                ] as $v=>$l)
                                <option value="{{ $v }}" {{ (\App\Models\Setting::get('ai_model','gemini-2.5-flash') === $v) ? 'selected' : '' }}>{{ $l }}</option>
                                @endforeach
                            </select>
                        </div>
                        @elseif($aiProvider === 'claude')
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Model</label>
                            <select id="ai_model" class="w-full px-3.5 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#0082C3]">
                                @foreach(['claude-3-5-sonnet-20241022'=>'Claude 3.5 Sonnet (Recommended)','claude-3-haiku-20240307'=>'Claude 3 Haiku (Fast)','claude-3-opus-20240229'=>'Claude 3 Opus (Most capable)'] as $v=>$l)
                                <option value="{{ $v }}" {{ \App\Models\Setting::get('ai_model','claude-3-5-sonnet-20241022') === $v ? 'selected' : '' }}>{{ $l }}</option>
                                @endforeach
                            </select>
                        </div>
                        @endif

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Default Language for Output</label>
                                <select id="ai_language" class="w-full px-3.5 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#0082C3]">
                                    @foreach(['English'=>'English','Hindi'=>'Hindi (हिन्दी)','Hinglish'=>'Hinglish (Mix)','Tamil'=>'Tamil','Telugu'=>'Telugu','Bengali'=>'Bengali'] as $v=>$l)
                                    <option value="{{ $v }}" {{ \App\Models\Setting::get('ai_language','English') === $v ? 'selected' : '' }}>{{ $l }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Max Tokens per Request</label>
                                <select id="ai_max_tokens" class="w-full px-3.5 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#0082C3]">
                                    @foreach(['500'=>'500 (Short)','1000'=>'1000 (Medium)','2000'=>'2000 (Long)','4000'=>'4000 (Very Long)'] as $v=>$l)
                                    <option value="{{ $v }}" {{ \App\Models\Setting::get('ai_max_tokens','1000') === $v ? 'selected' : '' }}>{{ $l }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        {{-- Status --}}
                        @if($aiProvider && $aiKeySet)
                        <div class="bg-green-50 border border-green-200 rounded-xl p-3 flex items-center gap-3">
                            <svg class="w-5 h-5 text-green-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            <div>
                                <p class="text-sm font-semibold text-green-700">{{ $currentProviderName }} is configured</p>
                                <p class="text-xs text-green-600">Model: {{ \App\Models\Setting::get('ai_model', 'default') }} · Language: {{ \App\Models\Setting::get('ai_language', 'English') }}</p>
                            </div>
                        </div>
                        @endif

                        <div class="flex items-center gap-3 pt-2 border-t border-gray-100">
                            <button onclick="saveAIConfig()" id="saveAiBtn"
                                    class="px-6 py-2.5 bg-[#0082C3] text-white text-sm font-semibold rounded-lg hover:bg-[#006ba3] transition-colors disabled:opacity-60">
                                Save AI Configuration
                            </button>
                            <button onclick="testAIConnection()" id="testAiBtn"
                                    class="px-5 py-2.5 bg-white border border-gray-300 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-50 transition-colors">
                                🧪 Test Connection
                            </button>
                            @if($aiProvider && $aiKeySet)
                            <button onclick="clearAIConfig()"
                                    class="px-4 py-2.5 bg-red-50 text-red-600 text-sm font-medium rounded-lg hover:bg-red-100 border border-red-200 ml-auto">
                                Clear Config
                            </button>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- Pricing info --}}
                <div class="bg-white rounded-xl border border-gray-200 p-5">
                    <h3 class="text-sm font-semibold text-gray-900 mb-3">💰 Approximate Pricing (per 1000 requests)</h3>
                    <div class="grid grid-cols-4 gap-3">
                        @foreach([
                            ['name'=>'GPT-4o Mini','price'=>'~₹4','color'=>'green','note'=>'Best value'],
                            ['name'=>'Gemini Flash','price'=>'~₹0','color'=>'blue','note'=>'Free tier available'],
                            ['name'=>'GPT-4o','price'=>'~₹40','color'=>'yellow','note'=>'Best quality'],
                            ['name'=>'Claude Haiku','price'=>'~₹2','color'=>'orange','note'=>'Fast & cheap'],
                        ] as $p)
                        <div class="p-3 bg-{{ $p['color'] }}-50 border border-{{ $p['color'] }}-100 rounded-xl text-center">
                            <p class="text-sm font-bold text-gray-800">{{ $p['price'] }}</p>
                            <p class="text-xs font-medium text-gray-600 mt-0.5">{{ $p['name'] }}</p>
                            <p class="text-xs text-gray-400 mt-0.5">{{ $p['note'] }}</p>
                        </div>
                        @endforeach
                    </div>
                </div>

            </div>

            {{-- ══ PRODUCT DESCRIPTION TAB ══ --}}
            <div id="tab-description" class="ai-tab" style="display:none">
                <div class="bg-white rounded-xl border border-gray-200 p-6 space-y-4">
                    <div>
                        <h2 class="text-base font-semibold text-gray-900">Product Description Generator</h2>
                        <p class="text-sm text-gray-500 mt-0.5">Generate compelling product descriptions using AI</p>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Product Name <span class="text-red-500">*</span></label>
                            <input id="aiProdName" type="text" class="w-full px-3.5 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#0082C3]" placeholder="e.g. Artengo Badminton Racket">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Category</label>
                            <input id="aiProdCat" type="text" class="w-full px-3.5 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#0082C3]" placeholder="e.g. Badminton Equipment">
                        </div>
                        <div class="col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Key Features (comma separated)</label>
                            <input id="aiProdFeatures" type="text" class="w-full px-3.5 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#0082C3]" placeholder="e.g. Lightweight, Aluminium frame, Beginner friendly">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Tone</label>
                            <select id="aiTone" class="w-full px-3.5 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#0082C3]">
                                <option value="professional">Professional</option>
                                <option value="casual">Casual & Friendly</option>
                                <option value="exciting">Exciting & Energetic</option>
                                <option value="technical">Technical & Detailed</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Length</label>
                            <select id="aiLength" class="w-full px-3.5 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#0082C3]">
                                <option value="short">Short (50-80 words)</option>
                                <option value="medium" selected>Medium (100-150 words)</option>
                                <option value="long">Long (200+ words)</option>
                            </select>
                        </div>
                    </div>
                    <button onclick="generateDescription()" id="genDescBtn"
                            class="inline-flex items-center gap-2 px-5 py-2.5 bg-purple-600 text-white text-sm font-semibold rounded-lg hover:bg-purple-700 transition-colors disabled:opacity-60">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                        Generate Description
                    </button>

                    <div id="descResult" class="hidden">
                        <div class="flex items-center justify-between mb-2">
                            <label class="block text-sm font-medium text-gray-700">Generated Description</label>
                            <button onclick="copyResult('descOutput')" class="text-xs text-[#0082C3] hover:underline">Copy</button>
                        </div>
                        <textarea id="descOutput" rows="6" class="w-full px-3.5 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#0082C3]"></textarea>
                        <div class="flex gap-2 mt-2">
                            <button onclick="generateDescription()" class="px-3 py-1.5 text-xs font-medium text-gray-600 border border-gray-300 rounded-lg hover:bg-gray-50">Regenerate</button>
                            <button onclick="toast('Description copied to clipboard')" class="px-3 py-1.5 text-xs font-medium bg-[#0082C3] text-white rounded-lg hover:bg-[#006ba3]">Use This</button>
                        </div>
                    </div>
                </div>
            </div>

            {{-- SEO Generator --}}
            <div id="tab-seo" class="ai-tab" style="display:none">
                <div class="bg-white rounded-xl border border-gray-200 p-6 space-y-4">
                    <div>
                        <h2 class="text-base font-semibold text-gray-900">SEO Meta Generator</h2>
                        <p class="text-sm text-gray-500 mt-0.5">Generate optimized SEO titles, descriptions and keywords</p>
                    </div>
                    <div class="space-y-3">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Product/Page Name <span class="text-red-500">*</span></label>
                            <input id="seoName" type="text" class="w-full px-3.5 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#0082C3]" placeholder="e.g. Artengo Badminton Racket">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Target Keywords</label>
                            <input id="seoKeywords" type="text" class="w-full px-3.5 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#0082C3]" placeholder="e.g. badminton racket, buy online, India">
                        </div>
                    </div>
                    <button onclick="generateSEO()" class="inline-flex items-center gap-2 px-5 py-2.5 bg-purple-600 text-white text-sm font-semibold rounded-lg hover:bg-purple-700 transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                        Generate SEO
                    </button>
                    <div id="seoResult" class="hidden space-y-3">
                        <div>
                            <div class="flex items-center justify-between mb-1">
                                <label class="text-sm font-medium text-gray-700">SEO Title <span id="seoTitleCount" class="text-xs text-gray-400"></span></label>
                                <button onclick="copyResult('seoTitleOut')" class="text-xs text-[#0082C3] hover:underline">Copy</button>
                            </div>
                            <input id="seoTitleOut" type="text" class="w-full px-3.5 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#0082C3]" oninput="updateCount('seoTitleOut','seoTitleCount',60)">
                        </div>
                        <div>
                            <div class="flex items-center justify-between mb-1">
                                <label class="text-sm font-medium text-gray-700">Meta Description <span id="seoDescCount" class="text-xs text-gray-400"></span></label>
                                <button onclick="copyResult('seoDescOut')" class="text-xs text-[#0082C3] hover:underline">Copy</button>
                            </div>
                            <textarea id="seoDescOut" rows="3" class="w-full px-3.5 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#0082C3]" oninput="updateCount('seoDescOut','seoDescCount',160)"></textarea>
                        </div>
                        <div>
                            <div class="flex items-center justify-between mb-1">
                                <label class="text-sm font-medium text-gray-700">Keywords</label>
                                <button onclick="copyResult('seoKwOut')" class="text-xs text-[#0082C3] hover:underline">Copy</button>
                            </div>
                            <input id="seoKwOut" type="text" class="w-full px-3.5 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#0082C3]">
                        </div>
                    </div>
                </div>
            </div>

            {{-- Tag Suggester --}}
            <div id="tab-tags" class="ai-tab" style="display:none">
                <div class="bg-white rounded-xl border border-gray-200 p-6 space-y-4">
                    <div>
                        <h2 class="text-base font-semibold text-gray-900">Tag Suggester</h2>
                        <p class="text-sm text-gray-500 mt-0.5">Get AI-suggested tags for your products</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Product Name / Description</label>
                        <textarea id="tagInput" rows="4" class="w-full px-3.5 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#0082C3]" placeholder="Describe your product…"></textarea>
                    </div>
                    <button onclick="generateTags()" class="inline-flex items-center gap-2 px-5 py-2.5 bg-purple-600 text-white text-sm font-semibold rounded-lg hover:bg-purple-700 transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                        Suggest Tags
                    </button>
                    <div id="tagResult" class="hidden">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Suggested Tags</label>
                        <div id="tagChips" class="flex flex-wrap gap-2"></div>
                        <p class="text-xs text-gray-400 mt-2">Click a tag to copy it</p>
                    </div>
                </div>
            </div>

            {{-- Image Alt Text --}}
            <div id="tab-image" class="ai-tab" style="display:none">
                <div class="bg-white rounded-xl border border-gray-200 p-6 space-y-4">
                    <div>
                        <h2 class="text-base font-semibold text-gray-900">Image Alt Text Generator</h2>
                        <p class="text-sm text-gray-500 mt-0.5">Generate SEO-friendly alt text for product images</p>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Product Name</label>
                            <input id="altProdName" type="text" class="w-full px-3.5 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#0082C3]" placeholder="e.g. Artengo Badminton Racket">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Image Context</label>
                            <input id="altContext" type="text" class="w-full px-3.5 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#0082C3]" placeholder="e.g. front view, blue color">
                        </div>
                    </div>
                    <button onclick="generateAltText()" class="inline-flex items-center gap-2 px-5 py-2.5 bg-purple-600 text-white text-sm font-semibold rounded-lg hover:bg-purple-700 transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                        Generate Alt Text
                    </button>
                    <div id="altResult" class="hidden">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Generated Alt Texts</label>
                        <div id="altList" class="space-y-2"></div>
                    </div>
                </div>
            </div>

            {{-- ══ USAGE TAB ══ --}}
            <div id="tab-usage" class="ai-tab space-y-5" style="display:none">

                {{-- Period selector --}}
                <div class="bg-white rounded-xl border border-gray-200 p-4 flex items-center justify-between">
                    <h2 class="text-base font-semibold text-gray-900">AI Usage Analytics</h2>
                    <div class="flex items-center gap-2">
                        <span class="text-sm text-gray-500">Last</span>
                        <select id="usageDays" onchange="loadUsage()" class="px-3 py-1.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#0082C3]">
                            <option value="7">7 days</option>
                            <option value="30" selected>30 days</option>
                            <option value="90">90 days</option>
                            <option value="365">1 year</option>
                        </select>
                        <button onclick="loadUsage()" class="px-3 py-1.5 text-sm text-gray-600 border border-gray-300 rounded-lg hover:bg-gray-50">↻ Refresh</button>
                    </div>
                </div>

                {{-- Stats cards --}}
                <div class="grid grid-cols-4 gap-4">
                    @foreach([
                        ['id'=>'uTotal',   'label'=>'Total Requests', 'color'=>'blue'],
                        ['id'=>'uSuccess', 'label'=>'Successful',     'color'=>'green'],
                        ['id'=>'uFailed',  'label'=>'Failed',         'color'=>'red'],
                        ['id'=>'uTokens',  'label'=>'Tokens Used',    'color'=>'purple'],
                    ] as $s)
                    <div class="bg-white rounded-xl border border-gray-200 p-4">
                        <p class="text-xs text-gray-500 mb-1">{{ $s['label'] }}</p>
                        <p id="{{ $s['id'] }}" class="text-2xl font-bold text-{{ $s['color'] }}-600">—</p>
                    </div>
                    @endforeach
                </div>

                <div class="grid grid-cols-2 gap-5">

                    {{-- By Type --}}
                    <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
                        <div class="px-5 py-3 border-b border-gray-100">
                            <h3 class="text-sm font-semibold text-gray-900">Requests by Tool</h3>
                        </div>
                        <div id="usageByType" class="p-5 space-y-3">
                            <p class="text-sm text-gray-400">Loading…</p>
                        </div>
                    </div>

                    {{-- By Provider --}}
                    <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
                        <div class="px-5 py-3 border-b border-gray-100">
                            <h3 class="text-sm font-semibold text-gray-900">Requests by Provider</h3>
                        </div>
                        <div id="usageByProvider" class="p-5 space-y-3">
                            <p class="text-sm text-gray-400">Loading…</p>
                        </div>
                    </div>

                </div>

                {{-- Daily chart --}}
                <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
                    <div class="px-5 py-3 border-b border-gray-100">
                        <h3 class="text-sm font-semibold text-gray-900">Daily Usage (Last 14 days)</h3>
                    </div>
                    <div id="dailyChart" class="p-5">
                        <p class="text-sm text-gray-400">Loading…</p>
                    </div>
                </div>

                {{-- Recent logs --}}
                <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
                    <div class="px-5 py-3 border-b border-gray-100">
                        <h3 class="text-sm font-semibold text-gray-900">Recent Requests</h3>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm">
                            <thead class="bg-gray-50 border-b border-gray-100">
                                <tr>
                                    <th class="px-4 py-2 text-left text-xs font-semibold text-gray-500 uppercase">Time</th>
                                    <th class="px-4 py-2 text-left text-xs font-semibold text-gray-500 uppercase">Tool</th>
                                    <th class="px-4 py-2 text-left text-xs font-semibold text-gray-500 uppercase">Provider</th>
                                    <th class="px-4 py-2 text-left text-xs font-semibold text-gray-500 uppercase">Model</th>
                                    <th class="px-4 py-2 text-right text-xs font-semibold text-gray-500 uppercase">Tokens</th>
                                    <th class="px-4 py-2 text-center text-xs font-semibold text-gray-500 uppercase">Status</th>
                                </tr>
                            </thead>
                            <tbody id="recentLogs" class="divide-y divide-gray-50">
                                <tr><td colspan="6" class="px-4 py-8 text-center text-gray-400 text-sm">Loading…</td></tr>
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>

        </div>
    </div>
</div>

<div id="toast" class="hidden fixed top-5 right-5 z-[9999] px-5 py-3 rounded-xl shadow-xl text-white text-sm font-medium transition-all"></div>

@endsection

@push('scripts')
<script>
function switchTab(name) {
    document.querySelectorAll('.ai-tab').forEach(t => t.style.display = 'none');
    document.querySelectorAll('.ai-nav').forEach(b => {
        b.className = 'ai-nav w-full flex items-center gap-2 px-4 py-3 text-sm font-medium text-left transition-colors border-b border-gray-100 last:border-0 text-gray-700 hover:bg-gray-50';
    });
    document.getElementById('tab-' + name).style.display = 'block';
    document.getElementById('nav-' + name).className = 'ai-nav w-full flex items-center gap-2 px-4 py-3 text-sm font-medium text-left transition-colors border-b border-gray-100 last:border-0 bg-blue-50 text-[#0082C3] border-l-2 border-l-[#0082C3]';
    if (name === 'usage') loadUsage();
}

// ── AI Provider Setup ─────────────────────────────────────────────
const CSRF = document.querySelector('meta[name="csrf-token"]').content;

function selectProvider(id) {
    document.querySelectorAll('.provider-card').forEach(c => {
        c.classList.remove('border-[#0082C3]', 'bg-blue-50');
        c.classList.add('border-gray-200');
    });
    const card = document.getElementById('provider-card-' + id);
    if (card) { card.classList.add('border-[#0082C3]', 'bg-blue-50'); card.classList.remove('border-gray-200'); }

    // Update hint
    const hints = {
        openai: '<p><strong>OpenAI:</strong> Get your key from <a href="https://platform.openai.com/api-keys" target="_blank" class="underline">platform.openai.com/api-keys</a>. Key starts with <code class="bg-blue-100 px-1 rounded">sk-</code></p>',
        gemini: '<p><strong>Google Gemini:</strong> Get your key from <a href="https://aistudio.google.com/app/apikey" target="_blank" class="underline">aistudio.google.com</a>. Key starts with <code class="bg-blue-100 px-1 rounded">AIza</code></p>',
        claude: '<p><strong>Anthropic Claude:</strong> Get your key from <a href="https://console.anthropic.com/settings/keys" target="_blank" class="underline">console.anthropic.com</a>. Key starts with <code class="bg-blue-100 px-1 rounded">sk-ant-</code></p>',
        custom: '<p><strong>Custom:</strong> Enter your OpenAI-compatible API endpoint URL and key below</p>',
    };
    const hint = document.getElementById('provider-hint');
    if (hint) {
        hint.className = 'rounded-xl p-4 text-sm bg-blue-50 border border-blue-200 text-blue-700';
        hint.innerHTML = hints[id] || '';
    }

    // Store selection temporarily
    document.getElementById('ai_api_key').placeholder = id === 'openai' ? 'sk-...' : id === 'gemini' ? 'AIza...' : id === 'claude' ? 'sk-ant-...' : 'Your API key';
    window._selectedProvider = id;
}

async function saveAIConfig() {
    const provider = window._selectedProvider || '{{ $aiProvider }}';
    const apiKey   = document.getElementById('ai_api_key').value.trim();
    const model    = document.getElementById('ai_model')?.value || '';
    const language = document.getElementById('ai_language')?.value || 'English';
    const maxTokens= document.getElementById('ai_max_tokens')?.value || '1000';
    const endpoint = document.getElementById('ai_endpoint')?.value || '';

    if (!provider) { toast('Please select an AI provider first', 'error'); return; }
    if (!apiKey || apiKey.includes('•')) { toast('Please enter your API key', 'error'); return; }

    const btn = document.getElementById('saveAiBtn');
    btn.disabled = true; btn.textContent = 'Saving…';

    const body = {
        ai_provider:   provider,
        ai_api_key:    apiKey,
        ai_model:      model,
        ai_language:   language,
        ai_max_tokens: maxTokens,
        ai_endpoint:   endpoint,
    };

    const r = await fetch('/admin/settings/integrations', {
        method: 'POST',
        credentials: 'same-origin',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json' },
        body: JSON.stringify(body),
    });
    const data = await r.json();
    btn.disabled = false; btn.textContent = 'Save AI Configuration';

    if (data.success) { toast('AI configuration saved!'); setTimeout(() => location.reload(), 800); }
    else toast(data.message || 'Error', 'error');
}

async function testAIConnection() {
    const btn = document.getElementById('testAiBtn');
    btn.disabled = true; btn.textContent = 'Testing…';

    try {
        const r = await fetch('/admin/ai-tools/test', {
            method: 'POST',
            credentials: 'same-origin',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json' },
            body: JSON.stringify({}),
        });
        const data = await r.json();
        if (data.success) {
            toast('✓ ' + data.provider + ' connected! Response: "' + (data.response || '').substring(0, 60) + '…"');
        } else {
            toast(data.message || 'Connection failed', 'error');
        }
    } catch(e) {
        toast('Network error: ' + e.message, 'error');
    }

    btn.disabled = false; btn.textContent = '🧪 Test Connection';
}

async function clearAIConfig() {
    const body = { ai_provider: '', ai_api_key: '', ai_model: '' };
    const r = await fetch('/admin/settings/integrations', {
        method: 'POST',
        credentials: 'same-origin',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json' },
        body: JSON.stringify(body),
    });
    const data = await r.json();
    if (data.success) { toast('AI configuration cleared'); setTimeout(() => location.reload(), 800); }
    else toast(data.message || 'Error', 'error');
}

function toggleVisibility(id) {
    const el = document.getElementById(id);
    if (el) el.type = el.type === 'password' ? 'text' : 'password';
}

// Init — set saved provider on page load
window._selectedProvider = '{{ $aiProvider }}';
document.addEventListener('DOMContentLoaded', () => {
    if (window._selectedProvider) {
        const card = document.getElementById('provider-card-' + window._selectedProvider);
        if (card) {
            card.classList.add('border-[#0082C3]', 'bg-blue-50');
            card.classList.remove('border-gray-200');
        }
    }
});

// ── Usage Tab ─────────────────────────────────────────────────────
const TYPE_LABELS = { description: '📝 Description', seo: '🔍 SEO', tags: '🏷️ Tags', alt_text: '🖼️ Alt Text', test: '🧪 Test' };
const TYPE_COLORS = ['#0082C3','#10b981','#f59e0b','#8b5cf6','#ef4444'];

async function loadUsage() {
    const days = document.getElementById('usageDays')?.value || 30;
    const r = await fetch(`/admin/ai-tools/usage?days=${days}`, { credentials: 'same-origin', headers: { 'Accept': 'application/json' } });
    const res = await r.json();
    if (!res.success) return;
    const d = res.data;

    // Stats
    document.getElementById('uTotal').textContent   = d.total.toLocaleString();
    document.getElementById('uSuccess').textContent = d.successful.toLocaleString();
    document.getElementById('uFailed').textContent  = d.failed.toLocaleString();
    document.getElementById('uTokens').textContent  = d.total_tokens.toLocaleString();

    // By type
    const typeEl = document.getElementById('usageByType');
    if (!d.by_type.length) { typeEl.innerHTML = '<p class="text-sm text-gray-400">No data yet</p>'; }
    else {
        const max = Math.max(...d.by_type.map(t => t.count));
        typeEl.innerHTML = d.by_type.map((t, i) => `
            <div class="flex items-center gap-3">
                <span class="text-xs text-gray-600 w-28 flex-shrink-0">${TYPE_LABELS[t.type] || t.type}</span>
                <div class="flex-1 bg-gray-100 rounded-full h-2.5 overflow-hidden">
                    <div class="h-2.5 rounded-full transition-all duration-500" style="width:${Math.round(t.count/max*100)}%;background:${TYPE_COLORS[i%TYPE_COLORS.length]}"></div>
                </div>
                <span class="text-xs font-semibold text-gray-700 w-16 text-right">${t.count} req · ${(t.tokens||0).toLocaleString()} tok</span>
            </div>
        `).join('');
    }

    // By provider
    const provEl = document.getElementById('usageByProvider');
    if (!d.by_provider.length) { provEl.innerHTML = '<p class="text-sm text-gray-400">No data yet</p>'; }
    else {
        const max2 = Math.max(...d.by_provider.map(p => p.count));
        provEl.innerHTML = d.by_provider.map((p, i) => `
            <div class="flex items-center gap-3">
                <span class="text-xs text-gray-600 w-28 flex-shrink-0 truncate">${p.provider} / ${p.model || 'default'}</span>
                <div class="flex-1 bg-gray-100 rounded-full h-2.5 overflow-hidden">
                    <div class="h-2.5 rounded-full transition-all duration-500" style="width:${Math.round(p.count/max2*100)}%;background:${TYPE_COLORS[i%TYPE_COLORS.length]}"></div>
                </div>
                <span class="text-xs font-semibold text-gray-700 w-12 text-right">${p.count}</span>
            </div>
        `).join('');
    }

    // Daily chart
    const chartEl = document.getElementById('dailyChart');
    if (!d.daily.length) { chartEl.innerHTML = '<p class="text-sm text-gray-400">No data yet</p>'; }
    else {
        const maxD = Math.max(...d.daily.map(x => x.count)) || 1;
        chartEl.innerHTML = `<div class="flex items-end gap-1 h-24">` +
            d.daily.map(x => {
                const h = Math.max(4, Math.round((x.count / maxD) * 96));
                const date = new Date(x.date).toLocaleDateString('en-IN', { day:'numeric', month:'short' });
                return `<div class="flex-1 flex flex-col items-center gap-1 group relative">
                    <div class="w-full rounded-t-sm bg-[#0082C3] hover:bg-[#006ba3] transition-colors cursor-default" style="height:${h}px" title="${date}: ${x.count} requests"></div>
                    <span class="text-[9px] text-gray-400 rotate-45 origin-left">${date}</span>
                </div>`;
            }).join('') + `</div>`;
    }

    // Recent logs
    const tbody = document.getElementById('recentLogs');
    if (!d.recent.length) { tbody.innerHTML = '<tr><td colspan="6" class="px-4 py-8 text-center text-gray-400 text-sm">No requests yet</td></tr>'; }
    else {
        tbody.innerHTML = d.recent.map(log => `
            <tr class="hover:bg-gray-50">
                <td class="px-4 py-2.5 text-xs text-gray-500">${new Date(log.created_at).toLocaleString('en-IN', {day:'numeric',month:'short',hour:'2-digit',minute:'2-digit'})}</td>
                <td class="px-4 py-2.5 text-xs text-gray-700">${TYPE_LABELS[log.type] || log.type}</td>
                <td class="px-4 py-2.5 text-xs text-gray-700 capitalize">${log.provider}</td>
                <td class="px-4 py-2.5 text-xs text-gray-500 font-mono">${log.model || '—'}</td>
                <td class="px-4 py-2.5 text-xs text-gray-700 text-right">${(log.total_tokens||0).toLocaleString()}</td>
                <td class="px-4 py-2.5 text-center">
                    <span class="px-2 py-0.5 rounded-full text-xs font-medium ${log.success ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700'}">
                        ${log.success ? '✓' : '✗'}
                    </span>
                </td>
            </tr>
        `).join('');
    }
}

// Auto-load usage when tab is opened — handled inside switchTab above
function toast(msg, type = 'success') {
    const el = document.getElementById('toast');
    el.textContent = msg;
    el.className = `fixed top-5 right-5 z-[9999] px-5 py-3 rounded-xl shadow-xl text-white text-sm font-medium ${type === 'success' ? 'bg-green-600' : 'bg-red-600'}`;
    el.classList.remove('hidden');
    setTimeout(() => el.classList.add('hidden'), 3000);
}

function copyResult(id) {
    const el = document.getElementById(id);
    navigator.clipboard.writeText(el.value || el.textContent).then(() => toast('Copied to clipboard'));
}

function updateCount(inputId, countId, max) {
    const len = document.getElementById(inputId).value.length;
    const el = document.getElementById(countId);
    el.textContent = `${len}/${max}`;
    el.className = `text-xs ${len > max ? 'text-red-500' : 'text-gray-400'}`;
}

// ── Real AI API call helper ───────────────────────────────────────
async function callAI(type, prompt) {
    const r = await fetch('/admin/ai-tools/generate', {
        method: 'POST',
        credentials: 'same-origin',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json' },
        body: JSON.stringify({ type, prompt }),
    });
    const data = await r.json();
    if (!data.success) throw new Error(data.message || 'AI generation failed');
    return data.result;
}

// ── Product Description ───────────────────────────────────────────
async function generateDescription() {
    const name     = document.getElementById('aiProdName').value.trim();
    const cat      = document.getElementById('aiProdCat').value.trim();
    const features = document.getElementById('aiProdFeatures').value.trim();
    const tone     = document.getElementById('aiTone').value;
    const length   = document.getElementById('aiLength').value;
    if (!name) { toast('Please enter a product name', 'error'); return; }

    const btn = document.getElementById('genDescBtn');
    btn.disabled = true; btn.innerHTML = '<svg class="w-4 h-4 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg> Generating…';

    const wordCount = { short: '50-80 words', medium: '100-150 words', long: '200+ words' }[length] || '100-150 words';
    const prompt = `Write a ${tone} product description for "${name}" (Category: ${cat || 'Sports & Fitness'}).
Key features: ${features || 'high quality, durable, performance-focused'}.
Length: ${wordCount}.
Store: Decathlon India.
Do not include any heading or title — just the description paragraph(s).`;

    try {
        const result = await callAI('description', prompt);
        document.getElementById('descOutput').value = result.trim();
        document.getElementById('descResult').classList.remove('hidden');
        toast('Description generated!');
    } catch(e) {
        toast(e.message, 'error');
    }

    btn.disabled = false; btn.innerHTML = '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg> Generate Description';
}

// ── SEO Generator ─────────────────────────────────────────────────
async function generateSEO() {
    const name = document.getElementById('seoName').value.trim();
    const kw   = document.getElementById('seoKeywords').value.trim();
    if (!name) { toast('Please enter a product name', 'error'); return; }

    const prompt = `Generate SEO metadata for a Decathlon India product page.
Product: "${name}"
Target keywords: ${kw || name + ', buy online, India, sports'}

Return EXACTLY in this format (no extra text):
TITLE: [SEO title, max 60 chars]
DESCRIPTION: [Meta description, max 160 chars]
KEYWORDS: [comma-separated keywords, 8-12 keywords]`;

    try {
        const result = await callAI('seo', prompt);
        const lines = result.split('\n').filter(l => l.trim());
        const titleLine = lines.find(l => l.startsWith('TITLE:'));
        const descLine  = lines.find(l => l.startsWith('DESCRIPTION:'));
        const kwLine    = lines.find(l => l.startsWith('KEYWORDS:'));

        document.getElementById('seoTitleOut').value = titleLine ? titleLine.replace('TITLE:', '').trim() : name + ' | Decathlon India';
        document.getElementById('seoDescOut').value  = descLine  ? descLine.replace('DESCRIPTION:', '').trim() : '';
        document.getElementById('seoKwOut').value    = kwLine    ? kwLine.replace('KEYWORDS:', '').trim() : kw;

        document.getElementById('seoResult').classList.remove('hidden');
        updateCount('seoTitleOut', 'seoTitleCount', 60);
        updateCount('seoDescOut', 'seoDescCount', 160);
        toast('SEO metadata generated!');
    } catch(e) {
        toast(e.message, 'error');
    }
}

// ── Tag Suggester ─────────────────────────────────────────────────
async function generateTags() {
    const input = document.getElementById('tagInput').value.trim();
    if (!input) { toast('Please enter product details', 'error'); return; }

    const prompt = `Suggest 12-15 relevant product tags for this Decathlon India product:
"${input}"
Return ONLY a comma-separated list of tags. No explanation, no numbering. Tags should be short (1-3 words each), relevant for ecommerce search.`;

    try {
        const result = await callAI('tags', prompt);
        const tags = result.split(',').map(t => t.trim().replace(/^["'\s]+|["'\s]+$/g, '')).filter(Boolean).slice(0, 15);
        const chips = document.getElementById('tagChips');
        chips.innerHTML = tags.map(tag => `
            <button onclick="navigator.clipboard.writeText('${tag.replace(/'/g,"\\'")}').then(()=>toast('Copied: ${tag.replace(/'/g,"\\'")}'))"
                    class="px-3 py-1.5 bg-blue-50 text-blue-700 text-sm font-medium rounded-full hover:bg-blue-100 transition-colors border border-blue-200">
                ${tag}
            </button>
        `).join('');
        document.getElementById('tagResult').classList.remove('hidden');
        toast(`${tags.length} tags generated!`);
    } catch(e) {
        toast(e.message, 'error');
    }
}

// ── Image Alt Text ────────────────────────────────────────────────
async function generateAltText() {
    const name    = document.getElementById('altProdName').value.trim();
    const context = document.getElementById('altContext').value.trim();
    if (!name) { toast('Please enter a product name', 'error'); return; }

    const prompt = `Generate 4 different SEO-friendly image alt text options for a Decathlon India product image.
Product: "${name}"
Image context: ${context || 'product photo'}

Return EXACTLY 4 lines, each starting with a number and period (1. 2. 3. 4.)
Keep each alt text under 125 characters. Be descriptive and include relevant keywords.`;

    try {
        const result = await callAI('alt_text', prompt);
        const lines = result.split('\n').filter(l => l.match(/^\d+\./)).map(l => l.replace(/^\d+\.\s*/, '').trim());
        const list = document.getElementById('altList');
        list.innerHTML = (lines.length ? lines : [result.trim()]).map((alt, i) => `
            <div class="flex items-center gap-2 p-3 border border-gray-200 rounded-lg">
                <span class="text-xs text-gray-400 w-4">${i+1}.</span>
                <p class="text-sm text-gray-700 flex-1">${alt}</p>
                <button onclick="navigator.clipboard.writeText(this.closest('div').querySelector('p').textContent).then(()=>toast('Copied'))"
                        class="text-xs text-[#0082C3] hover:underline flex-shrink-0">Copy</button>
            </div>
        `).join('');
        document.getElementById('altResult').classList.remove('hidden');
        toast('Alt text generated!');
    } catch(e) {
        toast(e.message, 'error');
    }
}
</script>
@endpush
