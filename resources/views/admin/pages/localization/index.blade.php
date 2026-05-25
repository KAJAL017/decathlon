@extends('admin.layouts.app')
@section('title', 'Localization')

@php
// Load saved settings from DB
$langSettings = \App\Models\Setting::group('localization') ?: [];
$activeLangs  = json_decode(\App\Models\Setting::get('active_languages', '["en","hi"]'), true) ?? ['en','hi'];
$defaultLang  = \App\Models\Setting::get('default_language', 'en');
$activeCurs   = json_decode(\App\Models\Setting::get('active_currencies', '["INR"]'), true) ?? ['INR'];
$defaultCur   = \App\Models\Setting::get('default_currency', 'INR');
$dateFormat   = \App\Models\Setting::get('date_format', 'DD/MM/YYYY');
$timeFormat   = \App\Models\Setting::get('time_format', '12');
$timezone     = \App\Models\Setting::get('timezone', 'Asia/Kolkata');
$weekStart    = \App\Models\Setting::get('week_starts', 'Monday');
@endphp

@section('content')
<div class="space-y-6">

    {{-- Header --}}
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Localization</h1>
            <p class="text-sm text-gray-500 mt-0.5">Manage languages, currencies and regional settings</p>
        </div>
    </div>

    <div class="flex gap-6">

        {{-- Left Nav --}}
        <div class="w-52 flex-shrink-0">
            <nav class="bg-white rounded-xl border border-gray-200 overflow-hidden sticky top-4">
                @foreach(['languages'=>'🌐 Languages','currencies'=>'💰 Currencies','regions'=>'🗺️ Regions','formats'=>'📅 Date & Time'] as $key=>$label)
                <button onclick="switchTab('{{$key}}')" id="nav-{{$key}}"
                        class="loc-nav w-full flex items-center gap-2 px-4 py-3 text-sm font-medium text-left transition-colors border-b border-gray-100 last:border-0
                               {{ $key === 'languages' ? 'bg-blue-50 text-[#0082C3] border-l-2 border-l-[#0082C3]' : 'text-gray-700 hover:bg-gray-50' }}">
                    {{$label}}
                </button>
                @endforeach
            </nav>
        </div>

        {{-- Right Content --}}
        <div class="flex-1 min-w-0">

            {{-- Languages --}}
            <div id="tab-languages" class="loc-tab space-y-4">
                <div class="bg-white rounded-xl border border-gray-200 p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h2 class="text-base font-semibold text-gray-900">Languages</h2>
                    </div>
                    <div class="space-y-2">
                        @foreach([
                            ['code'=>'en','name'=>'English','native'=>'English','flag'=>'🇺🇸'],
                            ['code'=>'hi','name'=>'Hindi','native'=>'हिन्दी','flag'=>'🇮🇳'],
                            ['code'=>'ta','name'=>'Tamil','native'=>'தமிழ்','flag'=>'🇮🇳'],
                            ['code'=>'te','name'=>'Telugu','native'=>'తెలుగు','flag'=>'🇮🇳'],
                            ['code'=>'bn','name'=>'Bengali','native'=>'বাংলা','flag'=>'🇮🇳'],
                            ['code'=>'mr','name'=>'Marathi','native'=>'मराठी','flag'=>'🇮🇳'],
                            ['code'=>'gu','name'=>'Gujarati','native'=>'ગુજરાતી','flag'=>'🇮🇳'],
                            ['code'=>'kn','name'=>'Kannada','native'=>'ಕನ್ನಡ','flag'=>'🇮🇳'],
                        ] as $lang)
                        @php $isActive = in_array($lang['code'], $activeLangs); $isDefault = $defaultLang === $lang['code']; @endphp
                        <div class="flex items-center justify-between p-3 border border-gray-200 rounded-lg hover:bg-gray-50">
                            <div class="flex items-center gap-3">
                                <span class="text-2xl">{{ $lang['flag'] }}</span>
                                <div>
                                    <div class="flex items-center gap-2">
                                        <p class="text-sm font-semibold text-gray-900">{{ $lang['name'] }}</p>
                                        @if($isDefault)
                                        <span class="px-1.5 py-0.5 bg-blue-100 text-blue-700 text-xs font-medium rounded">Default</span>
                                        @endif
                                    </div>
                                    <p class="text-xs text-gray-400">{{ $lang['native'] }} · {{ strtoupper($lang['code']) }}</p>
                                </div>
                            </div>
                            <div class="flex items-center gap-3">
                                <label class="relative inline-flex items-center cursor-pointer">
                                    <input type="checkbox" {{ $isActive ? 'checked' : '' }}
                                           class="sr-only peer lang-toggle" data-code="{{ $lang['code'] }}"
                                           onchange="saveLangToggle('{{ $lang['code'] }}', this.checked)">
                                    <div class="w-9 h-5 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-[#0082C3]"></div>
                                </label>
                                @if(!$isDefault)
                                <button onclick="setDefaultLang('{{ $lang['code'] }}')"
                                        class="text-xs text-gray-500 hover:text-[#0082C3] px-2 py-1 border border-gray-200 rounded hover:border-[#0082C3] transition-colors">
                                    Set Default
                                </button>
                                @endif
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- Currencies --}}
            <div id="tab-currencies" class="loc-tab space-y-4" style="display:none">
                <div class="bg-white rounded-xl border border-gray-200 p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h2 class="text-base font-semibold text-gray-900">Currencies</h2>
                    </div>
                    <div class="space-y-2">
                        @foreach([
                            ['code'=>'INR','name'=>'Indian Rupee','symbol'=>'₹','rate'=>1],
                            ['code'=>'USD','name'=>'US Dollar','symbol'=>'$','rate'=>0.012],
                            ['code'=>'EUR','name'=>'Euro','symbol'=>'€','rate'=>0.011],
                            ['code'=>'GBP','name'=>'British Pound','symbol'=>'£','rate'=>0.0095],
                            ['code'=>'AED','name'=>'UAE Dirham','symbol'=>'د.إ','rate'=>0.044],
                            ['code'=>'SGD','name'=>'Singapore Dollar','symbol'=>'S$','rate'=>0.016],
                        ] as $cur)
                        @php $isActive = in_array($cur['code'], $activeCurs); $isDefault = $defaultCur === $cur['code']; @endphp
                        <div class="flex items-center justify-between p-3 border border-gray-200 rounded-lg hover:bg-gray-50">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-lg bg-green-50 flex items-center justify-center text-green-700 font-bold text-sm">{{ $cur['symbol'] }}</div>
                                <div>
                                    <div class="flex items-center gap-2">
                                        <p class="text-sm font-semibold text-gray-900">{{ $cur['name'] }}</p>
                                        @if($isDefault)
                                        <span class="px-1.5 py-0.5 bg-blue-100 text-blue-700 text-xs font-medium rounded">Default</span>
                                        @endif
                                    </div>
                                    <p class="text-xs text-gray-400">{{ $cur['code'] }} · Rate: {{ $cur['rate'] }}</p>
                                </div>
                            </div>
                            <div class="flex items-center gap-3">
                                <label class="relative inline-flex items-center cursor-pointer">
                                    <input type="checkbox" {{ $isActive ? 'checked' : '' }}
                                           class="sr-only peer cur-toggle" data-code="{{ $cur['code'] }}"
                                           onchange="saveCurToggle('{{ $cur['code'] }}', this.checked)">
                                    <div class="w-9 h-5 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-[#0082C3]"></div>
                                </label>
                                @if(!$isDefault)
                                <button onclick="setDefaultCurrency('{{ $cur['code'] }}')"
                                        class="text-xs text-gray-500 hover:text-[#0082C3] px-2 py-1 border border-gray-200 rounded hover:border-[#0082C3] transition-colors">
                                    Set Default
                                </button>
                                @endif
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- Regions --}}
            <div id="tab-regions" class="loc-tab space-y-4" style="display:none">

                {{-- Summary --}}
                <div class="bg-white rounded-xl border border-gray-200 p-5 flex items-center justify-between">
                    <div>
                        <h2 class="text-base font-semibold text-gray-900">Regions & Shipping Zones</h2>
                        <p class="text-xs text-gray-400 mt-0.5">All 28 States + 8 Union Territories of India</p>
                    </div>
                    <div class="flex items-center gap-3">
                        <span id="activeZoneCount" class="px-3 py-1.5 bg-green-50 text-green-700 text-xs font-semibold rounded-full">— Active</span>
                        <button onclick="toggleAllZones(true)"  class="px-3 py-1.5 text-xs font-medium bg-[#0082C3] text-white rounded-lg hover:bg-[#006ba3]">Enable All</button>
                        <button onclick="toggleAllZones(false)" class="px-3 py-1.5 text-xs font-medium bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200">Disable All</button>
                    </div>
                </div>

                @php
                $zones = [
                    // NORTH
                    ['region'=>'North','code'=>'DL','name'=>'Delhi','type'=>'UT',   'charge'=>49, 'days'=>'1-2','active'=>true],
                    ['region'=>'North','code'=>'UP','name'=>'Uttar Pradesh','type'=>'State','charge'=>49,'days'=>'2-3','active'=>true],
                    ['region'=>'North','code'=>'HR','name'=>'Haryana','type'=>'State','charge'=>49,'days'=>'1-2','active'=>true],
                    ['region'=>'North','code'=>'PB','name'=>'Punjab','type'=>'State','charge'=>49,'days'=>'2-3','active'=>true],
                    ['region'=>'North','code'=>'HP','name'=>'Himachal Pradesh','type'=>'State','charge'=>79,'days'=>'3-4','active'=>true],
                    ['region'=>'North','code'=>'UK','name'=>'Uttarakhand','type'=>'State','charge'=>79,'days'=>'3-4','active'=>true],
                    ['region'=>'North','code'=>'JK','name'=>'Jammu & Kashmir','type'=>'UT','charge'=>99,'days'=>'4-6','active'=>true],
                    ['region'=>'North','code'=>'LA','name'=>'Ladakh','type'=>'UT','charge'=>149,'days'=>'6-8','active'=>true],
                    ['region'=>'North','code'=>'CH','name'=>'Chandigarh','type'=>'UT','charge'=>49,'days'=>'1-2','active'=>true],
                    // SOUTH
                    ['region'=>'South','code'=>'KA','name'=>'Karnataka','type'=>'State','charge'=>49,'days'=>'1-2','active'=>true],
                    ['region'=>'South','code'=>'TN','name'=>'Tamil Nadu','type'=>'State','charge'=>49,'days'=>'2-3','active'=>true],
                    ['region'=>'South','code'=>'KL','name'=>'Kerala','type'=>'State','charge'=>49,'days'=>'2-3','active'=>true],
                    ['region'=>'South','code'=>'AP','name'=>'Andhra Pradesh','type'=>'State','charge'=>49,'days'=>'2-3','active'=>true],
                    ['region'=>'South','code'=>'TS','name'=>'Telangana','type'=>'State','charge'=>49,'days'=>'1-2','active'=>true],
                    ['region'=>'South','code'=>'PY','name'=>'Puducherry','type'=>'UT','charge'=>49,'days'=>'2-3','active'=>true],
                    ['region'=>'South','code'=>'AN','name'=>'Andaman & Nicobar','type'=>'UT','charge'=>199,'days'=>'7-10','active'=>true],
                    ['region'=>'South','code'=>'LD','name'=>'Lakshadweep','type'=>'UT','charge'=>249,'days'=>'8-12','active'=>false],
                    // WEST
                    ['region'=>'West','code'=>'MH','name'=>'Maharashtra','type'=>'State','charge'=>49,'days'=>'1-2','active'=>true],
                    ['region'=>'West','code'=>'GJ','name'=>'Gujarat','type'=>'State','charge'=>49,'days'=>'2-3','active'=>true],
                    ['region'=>'West','code'=>'RJ','name'=>'Rajasthan','type'=>'State','charge'=>49,'days'=>'2-3','active'=>true],
                    ['region'=>'West','code'=>'GA','name'=>'Goa','type'=>'State','charge'=>49,'days'=>'2-3','active'=>true],
                    ['region'=>'West','code'=>'DD','name'=>'Dadra & Nagar Haveli and Daman & Diu','type'=>'UT','charge'=>79,'days'=>'3-4','active'=>true],
                    // EAST
                    ['region'=>'East','code'=>'WB','name'=>'West Bengal','type'=>'State','charge'=>49,'days'=>'2-3','active'=>true],
                    ['region'=>'East','code'=>'OD','name'=>'Odisha','type'=>'State','charge'=>49,'days'=>'2-3','active'=>true],
                    ['region'=>'East','code'=>'BR','name'=>'Bihar','type'=>'State','charge'=>49,'days'=>'2-3','active'=>true],
                    ['region'=>'East','code'=>'JH','name'=>'Jharkhand','type'=>'State','charge'=>49,'days'=>'2-3','active'=>true],
                    // CENTRAL
                    ['region'=>'Central','code'=>'MP','name'=>'Madhya Pradesh','type'=>'State','charge'=>49,'days'=>'2-3','active'=>true],
                    ['region'=>'Central','code'=>'CG','name'=>'Chhattisgarh','type'=>'State','charge'=>49,'days'=>'3-4','active'=>true],
                    // NORTHEAST
                    ['region'=>'Northeast','code'=>'AS','name'=>'Assam','type'=>'State','charge'=>79,'days'=>'3-5','active'=>true],
                    ['region'=>'Northeast','code'=>'AR','name'=>'Arunachal Pradesh','type'=>'State','charge'=>99,'days'=>'4-6','active'=>true],
                    ['region'=>'Northeast','code'=>'MN','name'=>'Manipur','type'=>'State','charge'=>99,'days'=>'4-6','active'=>true],
                    ['region'=>'Northeast','code'=>'ML','name'=>'Meghalaya','type'=>'State','charge'=>99,'days'=>'4-6','active'=>true],
                    ['region'=>'Northeast','code'=>'MZ','name'=>'Mizoram','type'=>'State','charge'=>99,'days'=>'4-6','active'=>true],
                    ['region'=>'Northeast','code'=>'NL','name'=>'Nagaland','type'=>'State','charge'=>99,'days'=>'4-6','active'=>true],
                    ['region'=>'Northeast','code'=>'SK','name'=>'Sikkim','type'=>'State','charge'=>99,'days'=>'4-6','active'=>true],
                    ['region'=>'Northeast','code'=>'TR','name'=>'Tripura','type'=>'State','charge'=>99,'days'=>'4-6','active'=>true],
                ];
                $regionColors = ['North'=>'blue','South'=>'green','West'=>'orange','East'=>'purple','Central'=>'yellow','Northeast'=>'red'];
                $grouped = collect($zones)->groupBy('region');
                @endphp

                @foreach($grouped as $regionName => $states)
                @php $color = $regionColors[$regionName] ?? 'gray'; @endphp
                <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
                    <div class="flex items-center justify-between px-5 py-3 bg-{{ $color }}-50 border-b border-gray-200">
                        <div class="flex items-center gap-2">
                            <span class="w-2 h-2 rounded-full bg-{{ $color }}-500"></span>
                            <h3 class="text-sm font-bold text-gray-800">{{ $regionName }} India</h3>
                            <span class="text-xs text-gray-500">({{ $states->count() }} zones)</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="text-xs text-gray-500">{{ $states->where('active', true)->count() }}/{{ $states->count() }} active</span>
                        </div>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm">
                            <thead class="bg-gray-50 border-b border-gray-100">
                                <tr>
                                    <th class="px-4 py-2 text-left text-xs font-semibold text-gray-500 uppercase">State / UT</th>
                                    <th class="px-4 py-2 text-left text-xs font-semibold text-gray-500 uppercase">Type</th>
                                    <th class="px-4 py-2 text-center text-xs font-semibold text-gray-500 uppercase">Shipping ₹</th>
                                    <th class="px-4 py-2 text-center text-xs font-semibold text-gray-500 uppercase">Delivery Days</th>
                                    <th class="px-4 py-2 text-center text-xs font-semibold text-gray-500 uppercase">Active</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-50">
                                @foreach($states as $zone)
                                <tr class="hover:bg-gray-50 transition-colors" id="zone-{{ $zone['code'] }}">
                                    <td class="px-4 py-2.5">
                                        <div class="flex items-center gap-2">
                                            <span class="text-xs font-mono font-bold text-gray-400 w-6">{{ $zone['code'] }}</span>
                                            <span class="text-sm font-medium text-gray-800">{{ $zone['name'] }}</span>
                                        </div>
                                    </td>
                                    <td class="px-4 py-2.5">
                                        <span class="px-2 py-0.5 rounded text-xs font-medium {{ $zone['type'] === 'UT' ? 'bg-purple-50 text-purple-700' : 'bg-blue-50 text-blue-700' }}">
                                            {{ $zone['type'] }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-2.5 text-center">
                                        <input type="number" min="0" value="{{ $zone['charge'] }}"
                                               class="w-16 px-2 py-1 border border-gray-200 rounded text-xs text-center focus:outline-none focus:ring-1 focus:ring-[#0082C3]"
                                               onchange="saveZone('{{ $zone['code'] }}', this.value, null)">
                                    </td>
                                    <td class="px-4 py-2.5 text-center">
                                        <input type="text" value="{{ $zone['days'] }}"
                                               class="w-16 px-2 py-1 border border-gray-200 rounded text-xs text-center focus:outline-none focus:ring-1 focus:ring-[#0082C3]"
                                               onchange="saveZone('{{ $zone['code'] }}', null, this.value)">
                                    </td>
                                    <td class="px-4 py-2.5 text-center">
                                        <label class="relative inline-flex items-center cursor-pointer">
                                            <input type="checkbox" {{ $zone['active'] ? 'checked' : '' }}
                                                   class="sr-only peer zone-toggle" data-code="{{ $zone['code'] }}"
                                                   onchange="saveZoneActive('{{ $zone['code'] }}', this.checked)">
                                            <div class="w-8 h-4 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-3 after:w-3 after:transition-all peer-checked:bg-[#0082C3]"></div>
                                        </label>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                @endforeach

                {{-- Save All button --}}
                <div class="bg-white rounded-xl border border-gray-200 p-4 flex items-center justify-between">
                    <p class="text-xs text-gray-500">Changes are saved automatically when you edit. Click "Save All" to confirm all changes.</p>
                    <button onclick="saveAllZones()" class="px-5 py-2.5 bg-[#0082C3] text-white text-sm font-semibold rounded-lg hover:bg-[#006ba3]">
                        Save All Zones
                    </button>
                </div>

            </div>

            {{-- Date & Time Formats --}}
            <div id="tab-formats" class="loc-tab space-y-4" style="display:none">
                <div class="bg-white rounded-xl border border-gray-200 p-6 space-y-5">
                    <h2 class="text-base font-semibold text-gray-900">Date & Time Formats</h2>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Date Format</label>
                            <select id="sel_date_format" class="w-full px-3.5 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#0082C3]">
                                @foreach(['DD/MM/YYYY'=>'DD/MM/YYYY (25/05/2026)','MM/DD/YYYY'=>'MM/DD/YYYY (05/25/2026)','YYYY-MM-DD'=>'YYYY-MM-DD (2026-05-25)','D MMM YYYY'=>'D MMM YYYY (25 May 2026)'] as $v=>$l)
                                <option value="{{ $v }}" {{ $dateFormat === $v ? 'selected' : '' }}>{{ $l }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Time Format</label>
                            <select id="sel_time_format" class="w-full px-3.5 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#0082C3]">
                                <option value="12" {{ $timeFormat === '12' ? 'selected' : '' }}>12 Hour (2:30 PM)</option>
                                <option value="24" {{ $timeFormat === '24' ? 'selected' : '' }}>24 Hour (14:30)</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Timezone</label>
                            <select id="sel_timezone" class="w-full px-3.5 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#0082C3]">
                                @foreach(['Asia/Kolkata'=>'Asia/Kolkata (IST +5:30)','UTC'=>'UTC','America/New_York'=>'America/New_York (EST)','Europe/London'=>'Europe/London (GMT)','Asia/Dubai'=>'Asia/Dubai (GST +4)','Asia/Singapore'=>'Asia/Singapore (SGT +8)'] as $v=>$l)
                                <option value="{{ $v }}" {{ $timezone === $v ? 'selected' : '' }}>{{ $l }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Week Starts On</label>
                            <select id="sel_week_starts" class="w-full px-3.5 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#0082C3]">
                                @foreach(['Monday','Sunday','Saturday'] as $day)
                                <option value="{{ $day }}" {{ $weekStart === $day ? 'selected' : '' }}>{{ $day }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="pt-2">
                        <button onclick="saveDateTimeFormats()" class="px-5 py-2.5 bg-[#0082C3] text-white text-sm font-semibold rounded-lg hover:bg-[#006ba3]">Save Settings</button>
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
    document.querySelectorAll('.loc-tab').forEach(t => t.style.display = 'none');
    document.querySelectorAll('.loc-nav').forEach(b => {
        b.className = 'loc-nav w-full flex items-center gap-2 px-4 py-3 text-sm font-medium text-left transition-colors border-b border-gray-100 last:border-0 text-gray-700 hover:bg-gray-50';
    });
    document.getElementById('tab-' + name).style.display = 'block';
    document.getElementById('nav-' + name).className = 'loc-nav w-full flex items-center gap-2 px-4 py-3 text-sm font-medium text-left transition-colors border-b border-gray-100 last:border-0 bg-blue-50 text-[#0082C3] border-l-2 border-l-[#0082C3]';
}
function toast(msg, type = 'success') {
    const el = document.getElementById('toast');
    el.textContent = msg;
    el.className = `fixed top-5 right-5 z-[9999] px-5 py-3 rounded-xl shadow-xl text-white text-sm font-medium ${type === 'success' ? 'bg-green-600' : 'bg-red-600'}`;
    el.classList.remove('hidden');
    setTimeout(() => el.classList.add('hidden'), 3000);
}
function openAddLang() { toast('Language management coming soon', 'info'); }
function openAddCurrency() { toast('Currency management coming soon', 'info'); }

// ── Shared API helper ─────────────────────────────────────────────
const CSRF = document.querySelector('meta[name="csrf-token"]').content;

async function saveSetting(key, value, group = 'localization') {
    const body = {};
    body[key] = value;
    const r = await fetch(`/admin/settings/${group}`, {
        method: 'POST',
        credentials: 'same-origin',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json' },
        body: JSON.stringify(body),
    });
    const data = await r.json();
    return data;
}

// ── Languages ─────────────────────────────────────────────────────
async function saveLangToggle(code, enabled) {
    const active = [...document.querySelectorAll('.lang-toggle:checked')].map(cb => cb.dataset.code);
    const data = await saveSetting('active_languages', JSON.stringify(active));
    if (data.success) toast(enabled ? `${code} language enabled` : `${code} language disabled`);
    else toast(data.message || 'Error', 'error');
}

async function setDefaultLang(code) {
    const data = await saveSetting('default_language', code);
    if (data.success) { toast(`${code} set as default language`); setTimeout(() => location.reload(), 800); }
    else toast(data.message || 'Error', 'error');
}

// ── Currencies ────────────────────────────────────────────────────
async function saveCurToggle(code, enabled) {
    const active = [...document.querySelectorAll('.cur-toggle:checked')].map(cb => cb.dataset.code);
    const data = await saveSetting('active_currencies', JSON.stringify(active));
    if (data.success) toast(enabled ? `${code} currency enabled` : `${code} currency disabled`);
    else toast(data.message || 'Error', 'error');
}

async function setDefaultCurrency(code) {
    const data = await saveSetting('default_currency', code);
    if (data.success) { toast(`${code} set as default currency`); setTimeout(() => location.reload(), 800); }
    else toast(data.message || 'Error', 'error');
}

// ── Date & Time ───────────────────────────────────────────────────
async function saveDateTimeFormats() {
    const body = {
        date_format:  document.getElementById('sel_date_format').value,
        time_format:  document.getElementById('sel_time_format').value,
        timezone:     document.getElementById('sel_timezone').value,
        week_starts:  document.getElementById('sel_week_starts').value,
    };
    const r = await fetch('/admin/settings/localization', {
        method: 'POST',
        credentials: 'same-origin',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json' },
        body: JSON.stringify(body),
    });
    const data = await r.json();
    if (data.success) toast('Date & Time settings saved!');
    else toast(data.message || 'Error', 'error');
}

// ── Shipping Zones ────────────────────────────────────────────────
const zoneData = {};

function saveZone(code, charge, days) {
    if (!zoneData[code]) zoneData[code] = {};
    if (charge !== null) zoneData[code].charge = charge;
    if (days   !== null) zoneData[code].days   = days;
    toast(`Zone ${code} updated — click Save All to confirm`);
}

function saveZoneActive(code, active) {
    if (!zoneData[code]) zoneData[code] = {};
    zoneData[code].active = active;
    updateActiveCount();
    toast(`${code} ${active ? 'enabled' : 'disabled'}`);
}

function updateActiveCount() {
    const total  = document.querySelectorAll('.zone-toggle').length;
    const active = document.querySelectorAll('.zone-toggle:checked').length;
    const el = document.getElementById('activeZoneCount');
    if (el) el.textContent = `${active} / ${total} Active`;
}

function toggleAllZones(enable) {
    document.querySelectorAll('.zone-toggle').forEach(cb => {
        cb.checked = enable;
        if (!zoneData[cb.dataset.code]) zoneData[cb.dataset.code] = {};
        zoneData[cb.dataset.code].active = enable;
    });
    updateActiveCount();
    toast(enable ? 'All zones enabled — click Save All' : 'All zones disabled — click Save All');
}

async function saveAllZones() {
    const body = {};
    document.querySelectorAll('.zone-toggle').forEach(cb => {
        body[`zone_${cb.dataset.code}_active`] = cb.checked ? '1' : '0';
    });
    // Collect all charge/days inputs
    document.querySelectorAll('[id^="zone-"]').forEach(row => {
        const code = row.id.replace('zone-', '');
        const inputs = row.querySelectorAll('input[type="number"], input[type="text"]');
        if (inputs[0]) body[`zone_${code}_charge`] = inputs[0].value;
        if (inputs[1]) body[`zone_${code}_days`]   = inputs[1].value;
    });

    const r = await fetch('/admin/settings/shipping', {
        method: 'POST',
        credentials: 'same-origin',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json' },
        body: JSON.stringify(body),
    });
    const data = await r.json();
    if (data.success) toast('All shipping zones saved!');
    else toast(data.message || 'Error saving', 'error');
}

document.addEventListener('DOMContentLoaded', updateActiveCount);
</script>
@endpush
