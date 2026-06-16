@extends('admin.layouts.app')
@section('title', 'Email Campaigns')

@section('content')
<div class="space-y-6">

{{-- Header --}}
<div class="flex items-center justify-between">
    <div>
        <h1 class="text-2xl font-bold text-gray-900">Email Campaigns</h1>
        <p class="text-sm text-gray-500 mt-0.5">Create, schedule and track email campaigns</p>
    </div>
    <button onclick="openAdd()"
            class="inline-flex items-center gap-2 px-4 py-2.5 bg-[#0082C3] text-white text-sm font-semibold rounded-lg hover:bg-[#006ba3] transition-colors shadow-sm">
        <i data-lucide="plus" class="w-4 h-4"></i>
        Create Campaign
    </button>
</div>

{{-- Stats --}}
<div class="grid grid-cols-2 md:grid-cols-5 gap-4">
    @foreach([
        ['id'=>'sTot',  'label'=>'Total',      'color'=>'blue',   'icon'=>'mail'],
        ['id'=>'sSent', 'label'=>'Sent',        'color'=>'green',  'icon'=>'circle-check'],
        ['id'=>'sSch',  'label'=>'Scheduled',   'color'=>'yellow', 'icon'=>'calendar'],
        ['id'=>'sDraft','label'=>'Draft',        'color'=>'gray',   'icon'=>'pencil'],
        ['id'=>'sOpen', 'label'=>'Avg Open Rate','color'=>'purple', 'icon'=>'eye'],
    ] as $s)
    <div class="bg-white rounded-xl border border-gray-200 p-4 flex items-center gap-3">
        <div class="w-10 h-10 rounded-lg bg-{{ $s['color'] }}-50 flex items-center justify-center flex-shrink-0">
            <i data-lucide="{{ $s['icon'] }}" class="w-5 h-5 text-{{ $s['color'] }}-600"></i>
        </div>
        <div>
            <p class="text-xs text-gray-500">{{ $s['label'] }}</p>
            <p class="text-xl font-bold text-gray-900" id="{{ $s['id'] }}">—</p>
        </div>
    </div>
    @endforeach
</div>

{{-- Filters --}}
<div class="bg-white rounded-xl border border-gray-200 p-4 flex flex-wrap gap-3 items-center">
    <div class="relative flex-1 min-w-[200px]">
        <i data-lucide="search" class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400"></i>
        <input id="fSearch" type="text" placeholder="Search campaigns…"
               class="w-full pl-9 pr-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#0082C3]"
               oninput="debounceLoad()">
    </div>
    <select id="fType" onchange="load(1)" class="px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#0082C3]">
        <option value="">All Types</option>
        <option value="newsletter">Newsletter</option>
        <option value="promotional">Promotional</option>
        <option value="abandoned_cart">Abandoned Cart</option>
        <option value="welcome">Welcome</option>
        <option value="re_engagement">Re-engagement</option>
        <option value="product_launch">Product Launch</option>
    </select>
    <select id="fStatus" onchange="load(1)" class="px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#0082C3]">
        <option value="">All Status</option>
        <option value="draft">Draft</option>
        <option value="scheduled">Scheduled</option>
        <option value="sending">Sending</option>
        <option value="sent">Sent</option>
        <option value="paused">Paused</option>
        <option value="cancelled">Cancelled</option>
    </select>
    <select id="fPer" onchange="load(1)" class="px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#0082C3]">
        <option value="15">15/page</option>
        <option value="25">25/page</option>
        <option value="50">50/page</option>
    </select>
</div>

{{-- Table --}}
<div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-50 border-b border-gray-200">
                <tr>
                    <th class="px-5 py-3"><input type="checkbox" id="chkAll" onchange="toggleAll(this)" class="w-4 h-4 text-[#0082C3] rounded border-gray-300"></th>
                    <th class="px-5 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Campaign</th>
                    <th class="px-5 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Type</th>
                    <th class="px-5 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Audience</th>
                    <th class="px-5 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Performance</th>
                    <th class="px-5 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Schedule</th>
                    <th class="px-5 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Status</th>
                    <th class="px-5 py-3 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody id="tBody" class="divide-y divide-gray-100">
                <tr><td colspan="8" class="px-5 py-12 text-center text-gray-400 text-sm">Loading…</td></tr>
            </tbody>
        </table>
    </div>
    <div id="pagination" class="px-5 py-4 border-t border-gray-100 bg-gray-50 flex items-center justify-between"></div>
</div>

{{-- Bulk bar --}}
<div id="bulkBar" class="hidden fixed bottom-6 left-1/2 -translate-x-1/2 bg-gray-900 text-white px-6 py-3 rounded-2xl shadow-2xl flex items-center gap-4 z-50">
    <span id="bulkCount" class="text-sm font-medium"></span>
    <select id="bulkAction" class="px-3 py-1.5 bg-gray-800 border border-gray-600 rounded-lg text-sm text-white">
        <option value="">Choose action</option>
        <option value="pause">Pause</option>
        <option value="cancel">Cancel</option>
        <option value="delete">Delete</option>
    </select>
    <button onclick="applyBulk()" class="px-4 py-1.5 bg-[#0082C3] text-white text-sm font-medium rounded-lg hover:bg-[#006ba3]">Apply</button>
    <button onclick="clearSel()" class="text-gray-400 hover:text-white">✕</button>
</div>

</div>

{{-- ══ MODAL ══ --}}
<div id="modal" class="hidden fixed inset-0 z-50" onclick="onBd(event)">
    <div class="fixed inset-0 bg-black/50"></div>
    <div id="mBox" class="fixed right-0 top-0 h-full w-full max-w-2xl bg-white shadow-2xl flex flex-col"
         style="transform:translateX(100%);transition:transform .4s cubic-bezier(.34,1.56,.64,1)">

        {{-- Header --}}
        <div class="flex items-center justify-between px-6 py-4 border-b border-gray-200 bg-gray-50 flex-shrink-0">
            <div>
                <h3 id="mTitle" class="text-lg font-semibold text-gray-900">Create Campaign</h3>
                <p class="text-xs text-gray-500 mt-0.5" id="mStep">Step 1 of 3 — Campaign Details</p>
            </div>
            <button onclick="closeModal()" class="w-8 h-8 flex items-center justify-center rounded-full hover:bg-gray-200 text-gray-400 hover:text-gray-600">
                <i data-lucide="x" class="w-5 h-5"></i>
            </button>
        </div>

        {{-- Step Progress --}}
        <div class="px-6 py-3 border-b border-gray-100 bg-white flex-shrink-0">
            <div class="flex items-center gap-2">
                @foreach([1=>'Campaign Details', 2=>'Content & Audience', 3=>'Schedule & Send'] as $n=>$label)
                <div class="flex items-center gap-2 {{ $n < 3 ? 'flex-1' : '' }}">
                    <div id="sdot-{{$n}}" class="w-7 h-7 rounded-full flex items-center justify-center text-xs font-bold transition-all {{ $n===1?'bg-[#0082C3] text-white':'bg-gray-200 text-gray-500' }}">{{$n}}</div>
                    <span id="slbl-{{$n}}" class="text-xs font-medium hidden sm:block {{ $n===1?'text-[#0082C3]':'text-gray-400' }}">{{$label}}</span>
                    @if($n < 3)<div id="sline-{{$n}}" class="flex-1 h-0.5 {{ $n===1?'bg-[#0082C3]':'bg-gray-200' }} transition-all"></div>@endif
                </div>
                @endforeach
            </div>
        </div>

        {{-- Form --}}
        <form id="cForm" class="flex-1 overflow-y-auto">
            <input type="hidden" id="cId">

            {{-- Step 1: Campaign Details --}}
            <div id="s1" class="px-6 py-5 space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Campaign Name <span class="text-red-500">*</span></label>
                    <input id="cName" type="text" placeholder="e.g. Summer Sale Newsletter"
                           class="w-full px-3.5 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#0082C3]">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Campaign Type <span class="text-red-500">*</span></label>
                    <div class="grid grid-cols-2 gap-2">
                        @foreach([
                            ['newsletter',     '📰', 'Newsletter',     'Regular updates & content'],
                            ['promotional',    '🏷️', 'Promotional',    'Sales, offers & discounts'],
                            ['abandoned_cart', '🛒', 'Abandoned Cart', 'Recover lost sales'],
                            ['welcome',        '👋', 'Welcome',        'New customer onboarding'],
                            ['re_engagement',  '💌', 'Re-engagement',  'Win back inactive users'],
                            ['product_launch', '🚀', 'Product Launch', 'New product announcements'],
                        ] as [$val,$icon,$label,$sub])
                        <label class="type-card cursor-pointer border-2 border-gray-200 rounded-xl p-3 hover:border-[#0082C3] transition-all flex items-start gap-2.5" data-val="{{$val}}">
                            <input type="radio" name="cType" value="{{$val}}" class="hidden">
                            <span class="text-xl leading-none mt-0.5">{{$icon}}</span>
                            <div>
                                <div class="text-xs font-semibold text-gray-800">{{$label}}</div>
                                <div class="text-xs text-gray-400 mt-0.5">{{$sub}}</div>
                            </div>
                        </label>
                        @endforeach
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">From Name</label>
                        <input id="cFromName" type="text" placeholder="Decathlon" value="Decathlon"
                               class="w-full px-3.5 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#0082C3]">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">From Email</label>
                        <input id="cFromEmail" type="email" placeholder="noreply@decathlon.com" value="noreply@decathlon.com"
                               class="w-full px-3.5 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#0082C3]">
                    </div>
                </div>
            </div>

            {{-- Step 2: Content & Audience --}}
            <div id="s2" class="px-6 py-5 space-y-4" style="display:none">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Email Subject <span class="text-red-500">*</span></label>
                    <input id="cSubject" type="text" placeholder="e.g. ☀️ Summer Sale is LIVE — Up to 50% off!"
                           class="w-full px-3.5 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#0082C3]">
                    <p class="text-xs text-gray-400 mt-1">This is what recipients see in their inbox</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Preview Text</label>
                    <input id="cPreview" type="text" maxlength="255" placeholder="Short preview shown after subject line…"
                           class="w-full px-3.5 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#0082C3]">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Email Content</label>
                    <textarea id="cContent" rows="6" placeholder="Write your email content here (HTML supported)…"
                              class="w-full px-3.5 py-2.5 border border-gray-300 rounded-lg text-sm font-mono focus:outline-none focus:ring-2 focus:ring-[#0082C3]"></textarea>
                </div>
                <div class="border-t pt-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Target Audience</label>
                    <div class="grid grid-cols-2 gap-2">
                        @foreach([
                            ['all',                 '👥', 'All Subscribers',    'Send to everyone'],
                            ['new_customers',        '🆕', 'New Customers',      'Joined in last 30 days'],
                            ['inactive_customers',   '😴', 'Inactive Customers', 'No purchase in 90+ days'],
                            ['specific_segment',     '🎯', 'Specific Segment',   'Custom audience list'],
                        ] as [$val,$icon,$label,$sub])
                        <label class="aud-card cursor-pointer border-2 border-gray-200 rounded-xl p-3 hover:border-[#0082C3] transition-all flex items-start gap-2.5" data-val="{{$val}}">
                            <input type="radio" name="cAud" value="{{$val}}" class="hidden">
                            <span class="text-xl leading-none mt-0.5">{{$icon}}</span>
                            <div>
                                <div class="text-xs font-semibold text-gray-800">{{$label}}</div>
                                <div class="text-xs text-gray-400 mt-0.5">{{$sub}}</div>
                            </div>
                        </label>
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- Step 3: Schedule & Send --}}
            <div id="s3" class="px-6 py-5 space-y-4" style="display:none">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Send Option</label>
                    <div class="space-y-2">
                        <label class="flex items-center gap-3 cursor-pointer p-3.5 border-2 border-gray-200 rounded-xl hover:border-[#0082C3] transition-all" id="opt-now">
                            <input type="radio" name="sendOpt" value="now" class="w-4 h-4 text-[#0082C3]" onchange="toggleSchedule(false)">
                            <div>
                                <p class="text-sm font-semibold text-gray-800">⚡ Send Now</p>
                                <p class="text-xs text-gray-400">Campaign will be queued immediately</p>
                            </div>
                        </label>
                        <label class="flex items-center gap-3 cursor-pointer p-3.5 border-2 border-gray-200 rounded-xl hover:border-[#0082C3] transition-all" id="opt-sched">
                            <input type="radio" name="sendOpt" value="schedule" class="w-4 h-4 text-[#0082C3]" onchange="toggleSchedule(true)">
                            <div>
                                <p class="text-sm font-semibold text-gray-800">📅 Schedule for Later</p>
                                <p class="text-xs text-gray-400">Pick a specific date and time</p>
                            </div>
                        </label>
                        <label class="flex items-center gap-3 cursor-pointer p-3.5 border-2 border-[#0082C3] bg-blue-50 rounded-xl" id="opt-draft">
                            <input type="radio" name="sendOpt" value="draft" class="w-4 h-4 text-[#0082C3]" checked onchange="toggleSchedule(false)">
                            <div>
                                <p class="text-sm font-semibold text-gray-800">📝 Save as Draft</p>
                                <p class="text-xs text-gray-400">Save and send later</p>
                            </div>
                        </label>
                    </div>
                </div>
                <div id="scheduleRow" class="hidden">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Schedule Date & Time</label>
                    <input id="cScheduled" type="datetime-local"
                           class="w-full px-3.5 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#0082C3]">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Reply-To Email</label>
                    <input id="cReplyTo" type="email" placeholder="support@decathlon.com"
                           class="w-full px-3.5 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#0082C3]">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Tags <span class="text-gray-400 text-xs">(comma separated)</span></label>
                    <input id="cTags" type="text" placeholder="summer, sale, newsletter"
                           class="w-full px-3.5 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#0082C3]">
                </div>
                {{-- Summary card --}}
                <div class="bg-blue-50 border border-blue-200 rounded-xl p-4 space-y-2">
                    <p class="text-sm font-semibold text-blue-800">📋 Campaign Summary</p>
                    <div class="text-xs text-blue-700 space-y-1">
                        <p>Name: <span id="sumName" class="font-medium">—</span></p>
                        <p>Type: <span id="sumType" class="font-medium">—</span></p>
                        <p>Subject: <span id="sumSubject" class="font-medium">—</span></p>
                        <p>Audience: <span id="sumAud" class="font-medium">—</span></p>
                    </div>
                </div>
            </div>
        </form>

        {{-- Footer --}}
        <div class="px-6 py-4 border-t border-gray-200 bg-gray-50 flex items-center justify-between flex-shrink-0">
            <button id="btnPrev" onclick="prevStep()" class="hidden px-5 py-2.5 border border-gray-300 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-50 transition-colors">← Previous</button>
            <div class="flex gap-3 ml-auto">
                <button onclick="closeModal()" class="px-5 py-2.5 border border-gray-300 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-50 transition-colors">Cancel</button>
                <button id="btnNext" onclick="nextStep()" class="px-5 py-2.5 bg-[#0082C3] text-white text-sm font-semibold rounded-lg hover:bg-[#006ba3] transition-colors">Next →</button>
                <button id="btnSave" onclick="save()" style="display:none" class="px-5 py-2.5 bg-green-600 text-white text-sm font-semibold rounded-lg hover:bg-green-700 transition-colors">Save Campaign</button>
            </div>
        </div>
    </div>
</div>

{{-- Toast --}}
<div id="toast" class="hidden fixed top-5 right-5 z-[9999] px-5 py-3 rounded-xl shadow-xl text-white text-sm font-medium"></div>

@endsection

@push('scripts')
<script>
const CSRF=document.querySelector('meta[name="csrf-token"]').content;
const BASE='/admin/email-campaigns';
let step=1,checkedIds=new Set(),searchTimer,currentType='',currentAud='all';
let isFirstLoad = true;

async function api(url,method='GET',body=null){
    const opts={method,credentials:'same-origin',headers:{'X-Requested-With':'XMLHttpRequest','Accept':'application/json','X-CSRF-TOKEN':CSRF}};
    if(body){opts.headers['Content-Type']='application/json';opts.body=JSON.stringify(body);}
    const r=await fetch(url,opts);
    const ct=r.headers.get('content-type')||'';
    if(!ct.includes('json')){window.location.reload();return{success:false};}
    return r.json();
}

function toast(msg,type='success'){
    const el=document.getElementById('toast');
    el.textContent=msg;
    el.className=`fixed top-5 right-5 z-[9999] px-5 py-3 rounded-xl shadow-xl text-white text-sm font-medium ${type==='success'?'bg-green-600':type==='error'?'bg-red-600':'bg-yellow-500'}`;
    el.classList.remove('hidden');
    setTimeout(()=>el.classList.add('hidden'),3000);
}

// ── Load ─────────────────────────────────────────────────────────
async function load(page=1){
    const params=new URLSearchParams({page,per_page:document.getElementById('fPer').value||15,
        search:document.getElementById('fSearch').value||'',
        type:document.getElementById('fType').value||'',
        status:document.getElementById('fStatus').value||''});
    for(const[k,v]of[...params]){if(!v)params.delete(k);}
    document.getElementById('tBody').innerHTML=`<tr><td colspan="8" class="px-5 py-12 text-center text-gray-400 text-sm">Loading…</td></tr>`;
    try{
        const data=await api(`${BASE}/list?${params}`);
        if(!data.success){document.getElementById('tBody').innerHTML=`<tr><td colspan="8" class="px-5 py-12 text-center text-red-500 text-sm">Failed to load</td></tr>`;if (isFirstLoad) { isFirstLoad = false; if (typeof window.dismissSkeleton === 'function') window.dismissSkeleton(); }return;}
        renderTable(data.data);
        renderPagination(data.pagination,page);
        if(data.stats)renderStats(data.stats);
    }catch(e){document.getElementById('tBody').innerHTML=`<tr><td colspan="8" class="px-5 py-12 text-center text-red-500 text-sm">Error: ${e.message}</td></tr>`;}
    if (isFirstLoad) { isFirstLoad = false; if (typeof window.dismissSkeleton === 'function') window.dismissSkeleton(); }
}

const TYPE_COLORS={newsletter:'bg-blue-100 text-blue-700',promotional:'bg-orange-100 text-orange-700',abandoned_cart:'bg-red-100 text-red-700',welcome:'bg-green-100 text-green-700',re_engagement:'bg-purple-100 text-purple-700',product_launch:'bg-pink-100 text-pink-700'};
const TYPE_LABELS={newsletter:'Newsletter',promotional:'Promotional',abandoned_cart:'Abandoned Cart',welcome:'Welcome',re_engagement:'Re-engagement',product_launch:'Product Launch'};
const STATUS_COLORS={draft:'bg-gray-100 text-gray-600',scheduled:'bg-yellow-100 text-yellow-700',sending:'bg-blue-100 text-blue-700',sent:'bg-green-100 text-green-700',paused:'bg-orange-100 text-orange-700',cancelled:'bg-red-100 text-red-700'};
function fmtDate(d){return d?new Date(d).toLocaleDateString('en-IN',{day:'2-digit',month:'short',year:'numeric',hour:'2-digit',minute:'2-digit'}):'—';}
function fmtNum(n){return n>=1000?(n/1000).toFixed(1)+'K':n||0;}

function renderTable(rows){
    const tbody=document.getElementById('tBody');
    if(!rows.length){tbody.innerHTML=`<tr><td colspan="8" class="px-5 py-12 text-center text-gray-400 text-sm">No campaigns found</td></tr>`;return;}
    tbody.innerHTML=rows.map(c=>`
        <tr class="hover:bg-gray-50 transition-colors">
            <td class="px-5 py-3.5"><input type="checkbox" class="row-chk w-4 h-4 text-[#0082C3] rounded border-gray-300" value="${c.id}" ${checkedIds.has(c.id)?'checked':''} onchange="onChk(${c.id},this.checked)"></td>
            <td class="px-5 py-3.5">
                <p class="text-sm font-semibold text-gray-900">${c.name}</p>
                <p class="text-xs text-gray-400 mt-0.5 truncate max-w-xs">${c.subject||'—'}</p>
            </td>
            <td class="px-5 py-3.5"><span class="px-2.5 py-0.5 rounded-full text-xs font-medium ${TYPE_COLORS[c.type]||'bg-gray-100 text-gray-600'}">${TYPE_LABELS[c.type]||c.type}</span></td>
            <td class="px-5 py-3.5 text-xs text-gray-600">${(c.audience_type||'all').replace('_',' ')}<br><span class="text-gray-400">${fmtNum(c.total_recipients)} recipients</span></td>
            <td class="px-5 py-3.5">
                ${c.status==='sent'?`
                <div class="space-y-1 text-xs">
                    <div class="flex items-center gap-1.5"><span class="w-2 h-2 rounded-full bg-blue-400 inline-block"></span><span class="text-gray-600">Open: <strong>${c.open_rate||'0%'}</strong></span></div>
                    <div class="flex items-center gap-1.5"><span class="w-2 h-2 rounded-full bg-green-400 inline-block"></span><span class="text-gray-600">Click: <strong>${c.click_rate||'0%'}</strong></span></div>
                </div>`:'<span class="text-xs text-gray-400">—</span>'}
            </td>
            <td class="px-5 py-3.5 text-xs text-gray-500">${c.sent_at?'Sent '+fmtDate(c.sent_at):c.scheduled_at?'Sched '+fmtDate(c.scheduled_at):'—'}</td>
            <td class="px-5 py-3.5"><span class="px-2.5 py-0.5 rounded-full text-xs font-medium ${STATUS_COLORS[c.status]||'bg-gray-100 text-gray-600'}">${c.status||'draft'}</span></td>
            <td class="px-5 py-3.5">
                <div class="flex items-center justify-end gap-1">
                    <button onclick="openEdit(${c.id})" class="p-2 rounded-lg text-gray-500 hover:text-[#0082C3] hover:bg-blue-50 transition-colors" title="Edit">
                        <i data-lucide="pencil" class="w-4 h-4"></i>
                    </button>
                    <button onclick="dupCampaign(${c.id})" class="p-2 rounded-lg text-gray-500 hover:text-purple-600 hover:bg-purple-50 transition-colors" title="Duplicate">
                        <i data-lucide="copy" class="w-4 h-4"></i>
                    </button>
                    <button onclick="del(${c.id},'${c.name.replace(/'/g,"&#39;")}')" class="p-2 rounded-lg text-gray-500 hover:text-red-600 hover:bg-red-50 transition-colors" title="Delete">
                        <i data-lucide="trash-2" class="w-4 h-4"></i>
                    </button>
                </div>
            </td>
        </tr>`).join('');
}

function renderStats(s){
    document.getElementById('sTot').textContent=s.total||0;
    document.getElementById('sSent').textContent=s.sent||0;
    document.getElementById('sSch').textContent=s.scheduled||0;
    document.getElementById('sDraft').textContent=s.draft||0;
    document.getElementById('sOpen').textContent=s.avg_open_rate||'0%';
}

function renderPagination(p,cur){
    const el=document.getElementById('pagination');
    if(p.last_page<=1){el.innerHTML=`<p class="text-sm text-gray-500">Showing ${p.total} campaigns</p>`;return;}
    let pages='';
    for(let i=1;i<=p.last_page;i++){
        if(i===1||i===p.last_page||Math.abs(i-cur)<=2)pages+=`<button onclick="load(${i})" class="px-3 py-1.5 text-sm rounded-lg font-medium ${i===cur?'bg-[#0082C3] text-white':'text-gray-700 hover:bg-gray-100'}">${i}</button>`;
        else if(Math.abs(i-cur)===3)pages+=`<span class="px-1 text-gray-400">…</span>`;
    }
    el.innerHTML=`<p class="text-sm text-gray-500">Showing ${(cur-1)*p.per_page+1}–${Math.min(cur*p.per_page,p.total)} of ${p.total}</p>
        <div class="flex items-center gap-1">
            <button onclick="load(${cur-1})" ${cur===1?'disabled':''} class="px-3 py-1.5 text-sm rounded-lg text-gray-700 hover:bg-gray-100 disabled:opacity-40">←</button>
            ${pages}
            <button onclick="load(${cur+1})" ${cur===p.last_page?'disabled':''} class="px-3 py-1.5 text-sm rounded-lg text-gray-700 hover:bg-gray-100 disabled:opacity-40">→</button>
        </div>`;
}

function debounceLoad(){clearTimeout(searchTimer);searchTimer=setTimeout(()=>load(1),400);}

// ── Step Navigation ──────────────────────────────────────────────
function goStep(n){
    step=n;
    [1,2,3].forEach(i=>{
        document.getElementById(`s${i}`).style.display=i===n?'block':'none';
        const dot=document.getElementById(`sdot-${i}`);
        const lbl=document.getElementById(`slbl-${i}`);
        const line=document.getElementById(`sline-${i}`);
        if(i<n){dot.className='w-7 h-7 rounded-full flex items-center justify-center text-xs font-bold transition-all bg-green-500 text-white';dot.innerHTML='✓';if(lbl)lbl.className='text-xs font-medium hidden sm:block text-green-600';if(line)line.className='flex-1 h-0.5 bg-green-500 transition-all';}
        else if(i===n){dot.className='w-7 h-7 rounded-full flex items-center justify-center text-xs font-bold transition-all bg-[#0082C3] text-white';dot.innerHTML=i;if(lbl)lbl.className='text-xs font-medium hidden sm:block text-[#0082C3]';if(line)line.className='flex-1 h-0.5 bg-[#0082C3] transition-all';}
        else{dot.className='w-7 h-7 rounded-full flex items-center justify-center text-xs font-bold transition-all bg-gray-200 text-gray-500';dot.innerHTML=i;if(lbl)lbl.className='text-xs font-medium hidden sm:block text-gray-400';if(line)line.className='flex-1 h-0.5 bg-gray-200 transition-all';}
    });
    const steps=['Step 1 of 3 — Campaign Details','Step 2 of 3 — Content & Audience','Step 3 of 3 — Schedule & Send'];
    document.getElementById('mStep').textContent=steps[n-1];
    document.getElementById('btnPrev').classList.toggle('hidden',n===1);
    document.getElementById('btnNext').style.display=n<3?'':'none';
    document.getElementById('btnSave').style.display=n===3?'':'none';
    if(n===3)updateSummary();
}

function nextStep(){
    if(step===1){if(!document.getElementById('cName').value.trim()){toast('Enter campaign name','error');return;}if(!currentType){toast('Select campaign type','error');return;}}
    if(step===2){if(!document.getElementById('cSubject').value.trim()){toast('Enter email subject','error');return;}}
    if(step<3)goStep(step+1);
}
function prevStep(){if(step>1)goStep(step-1);}

function updateSummary(){
    document.getElementById('sumName').textContent=document.getElementById('cName').value||'—';
    document.getElementById('sumType').textContent=TYPE_LABELS[currentType]||currentType||'—';
    document.getElementById('sumSubject').textContent=document.getElementById('cSubject').value||'—';
    document.getElementById('sumAud').textContent=(currentAud||'all').replace('_',' ');
}

function toggleSchedule(show){document.getElementById('scheduleRow').classList.toggle('hidden',!show);}

// ── Type & Audience Cards ────────────────────────────────────────
document.querySelectorAll('.type-card').forEach(card=>{
    card.addEventListener('click',()=>{
        document.querySelectorAll('.type-card').forEach(c=>{c.classList.remove('border-[#0082C3]','bg-blue-50');c.classList.add('border-gray-200');});
        card.classList.add('border-[#0082C3]','bg-blue-50');card.classList.remove('border-gray-200');
        card.querySelector('input').checked=true;currentType=card.dataset.val;
    });
});
document.querySelectorAll('.aud-card').forEach(card=>{
    card.addEventListener('click',()=>{
        document.querySelectorAll('.aud-card').forEach(c=>{c.classList.remove('border-[#0082C3]','bg-blue-50');c.classList.add('border-gray-200');});
        card.classList.add('border-[#0082C3]','bg-blue-50');card.classList.remove('border-gray-200');
        card.querySelector('input').checked=true;currentAud=card.dataset.val;
    });
});

// ── Modal ────────────────────────────────────────────────────────
function openAdd(){
    document.getElementById('mTitle').textContent='Create Campaign';
    document.getElementById('cId').value='';
    document.getElementById('cForm').reset();
    document.getElementById('cFromName').value='Decathlon';
    document.getElementById('cFromEmail').value='noreply@decathlon.com';
    currentType='';currentAud='all';
    document.querySelectorAll('.type-card,.aud-card').forEach(c=>{c.classList.remove('border-[#0082C3]','bg-blue-50');c.classList.add('border-gray-200');});
    // Default audience = all
    const allAud=document.querySelector('.aud-card[data-val="all"]');
    if(allAud){allAud.classList.add('border-[#0082C3]','bg-blue-50');allAud.classList.remove('border-gray-200');allAud.querySelector('input').checked=true;}
    goStep(1);showModal();
}

async function openEdit(id){
    document.getElementById('mTitle').textContent='Edit Campaign';
    goStep(1);showModal();
    const data=await api(`${BASE}/${id}`);
    if(!data.success){toast('Failed to load','error');closeModal();return;}
    const c=data.data;
    document.getElementById('cId').value=c.id;
    document.getElementById('cName').value=c.name;
    document.getElementById('cFromName').value=c.from_name||'Decathlon';
    document.getElementById('cFromEmail').value=c.from_email||'noreply@decathlon.com';
    document.getElementById('cSubject').value=c.subject||'';
    document.getElementById('cPreview').value=c.preview_text||'';
    document.getElementById('cContent').value=c.content||'';
    document.getElementById('cReplyTo').value=c.reply_to||'';
    document.getElementById('cTags').value=Array.isArray(c.tags)?c.tags.join(', '):'';
    document.getElementById('cScheduled').value=c.scheduled_at?c.scheduled_at.replace(' ','T').substring(0,16):'';
    currentType=c.type||'';currentAud=c.audience_type||'all';
    document.querySelectorAll('.type-card').forEach(card=>{const a=card.dataset.val===currentType;card.classList.toggle('border-[#0082C3]',a);card.classList.toggle('bg-blue-50',a);card.classList.toggle('border-gray-200',!a);if(a)card.querySelector('input').checked=true;});
    document.querySelectorAll('.aud-card').forEach(card=>{const a=card.dataset.val===currentAud;card.classList.toggle('border-[#0082C3]',a);card.classList.toggle('bg-blue-50',a);card.classList.toggle('border-gray-200',!a);if(a)card.querySelector('input').checked=true;});
    if(c.scheduled_at){document.querySelector('input[name="sendOpt"][value="schedule"]').checked=true;toggleSchedule(true);}
}

function showModal(){document.getElementById('modal').classList.remove('hidden');requestAnimationFrame(()=>{document.getElementById('mBox').style.transform='translateX(0)';});}
function closeModal(){document.getElementById('mBox').style.transform='translateX(100%)';setTimeout(()=>document.getElementById('modal').classList.add('hidden'),420);}
function onBd(e){if(e.target.id==='modal')closeModal();}

// ── Save ─────────────────────────────────────────────────────────
async function save(){
    const id=document.getElementById('cId').value;
    const sendOpt=document.querySelector('input[name="sendOpt"]:checked')?.value||'draft';
    const body={
        name:document.getElementById('cName').value.trim(),
        subject:document.getElementById('cSubject').value.trim(),
        preview_text:document.getElementById('cPreview').value,
        type:currentType,
        audience_type:currentAud,
        from_name:document.getElementById('cFromName').value,
        from_email:document.getElementById('cFromEmail').value,
        reply_to:document.getElementById('cReplyTo').value,
        content:document.getElementById('cContent').value,
        tags:document.getElementById('cTags').value,
        scheduled_at:sendOpt==='schedule'?document.getElementById('cScheduled').value:null,
        status:sendOpt==='now'?'sending':sendOpt==='schedule'?'scheduled':'draft',
    };
    if(!body.name){toast('Name required','error');goStep(1);return;}
    if(!body.type){toast('Select type','error');goStep(1);return;}
    if(!body.subject){toast('Subject required','error');goStep(2);return;}

    const btn=document.getElementById('btnSave');
    btn.disabled=true;btn.textContent='Saving…';
    const data=await api(id?`${BASE}/${id}`:BASE,id?'PUT':'POST',body);
    btn.disabled=false;btn.textContent='Save Campaign';

    if(data.success){
        Dialog.alert({ title: 'Success!', message: id?'Campaign updated':'Campaign created', type: 'success' });
        closeModal();load(1);
    }
    else{const e=data.errors?Object.values(data.errors)[0][0]:data.message;toast(e||'Error','error');}
}

// ── Actions ──────────────────────────────────────────────────────
async function del(id,name){
    const confirmed = await Dialog.confirm({
        title: 'Delete Campaign',
        message: `Delete "<strong>${name}</strong>"?<br><span class="text-xs text-red-500">This cannot be undone.</span>`,
        type: 'danger'
    });
    if (!confirmed) return;
    
    const data=await api(`${BASE}/${id}`,'DELETE');
    if(data.success){
        Dialog.alert({ title: 'Deleted!', message: 'Campaign deleted', type: 'success' });
        load(1);
    } else toast(data.message||'Error','error');
}
async function dupCampaign(id){
    const data=await api(`${BASE}/${id}/duplicate`,'POST');
    if(data.success){toast('Campaign duplicated');load(1);}else toast(data.message||'Error','error');
}
function onChk(id,checked){checked?checkedIds.add(id):checkedIds.delete(id);syncBulk();}
function toggleAll(chk){document.querySelectorAll('.row-chk').forEach(el=>{el.checked=chk.checked;chk.checked?checkedIds.add(parseInt(el.value)):checkedIds.delete(parseInt(el.value));});syncBulk();}
function clearSel(){checkedIds.clear();document.querySelectorAll('.row-chk,#chkAll').forEach(el=>el.checked=false);syncBulk();}
function syncBulk(){const bar=document.getElementById('bulkBar');checkedIds.size>0?bar.classList.remove('hidden'):bar.classList.add('hidden');document.getElementById('bulkCount').textContent=`${checkedIds.size} selected`;}
async function applyBulk(){
    const action=document.getElementById('bulkAction').value;
    if(!action){toast('Choose an action','warning');return;}
    
    if(action==='delete') {
        const confirmed = await Dialog.confirm({
            title: 'Delete Campaigns',
            message: `Delete ${checkedIds.size} campaign(s)?`,
            type: 'danger'
        });
        if (!confirmed) return;
    }
    
    const data=await api(`${BASE}/bulk-action`,'POST',{action,ids:[...checkedIds]});
    if(data.success){
        if(action==='delete') Dialog.alert({ title: 'Deleted!', message: `${checkedIds.size} deleted`, type: 'success' });
        else toast(data.message);
        clearSel();load(1);
    }else toast(data.message||'Error','error');
}

document.addEventListener('DOMContentLoaded',()=>load(1));
</script>
@endpush
