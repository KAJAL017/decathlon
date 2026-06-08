/**
 * Searchable Select - Pure Vanilla JS
 * Fixed: dropdown closes immediately bug (caused by visibilityCheck + hidden class false positive)
 */

const searchableSelectInstances = [];

class SearchableSelect {
    constructor(selectElement, options = {}) {
        this.select       = selectElement;
        this.isMultiple   = this.select.hasAttribute('multiple');
        this.options      = {
            placeholder:       options.placeholder       || 'Select...',
            searchPlaceholder: options.searchPlaceholder || 'Type to search...',
            noResultsText:     options.noResultsText     || 'No results found',
            ajaxUrl:           options.ajaxUrl           || null,
            ajaxParams:        options.ajaxParams        || {},
            onChange:          options.onChange          || null,
            minChars:          options.minChars          || 0,
            debounceTime:      options.debounceTime      || 300,
        };

        this.isOpen          = false;
        this._justOpened     = false;   // guard against same-click close
        this.selectedValue   = this.select.value;
        this.searchTimeout   = null;
        this.allOptions      = [];

        this._init();
        searchableSelectInstances.push(this);
    }

    /* ─────────────────────────── INIT ─────────────────────────── */

    _init() {
        this._injectStyles();
        this.allOptions = this._readNativeOptions();
        this._buildDOM();
        this.select.style.display = 'none';
        this.select.parentNode.insertBefore(this.wrapper, this.select.nextSibling);
        this._bindEvents();
        if (this.options.ajaxUrl) this._loadAjax();
    }

    _injectStyles() {
        if (document.getElementById('searchable-select-styles')) return;
        const style = document.createElement('style');
        style.id = 'searchable-select-styles';
        style.textContent = `
            .searchable-select-dropdown {
                animation: ss-fade-in 0.2s cubic-bezier(0.4, 0, 0.2, 1);
                box-shadow: 0 10px 40px -10px rgba(0,0,0,0.15), 0 0 1px rgba(0,0,0,0.1);
            }
            @keyframes ss-fade-in {
                from { opacity: 0; transform: translateY(8px); }
                to { opacity: 1; transform: translateY(0); }
            }
            .searchable-select-options::-webkit-scrollbar { width: 5px; }
            .searchable-select-options::-webkit-scrollbar-track { background: transparent; }
            .searchable-select-options::-webkit-scrollbar-thumb { background: #e2e8f0; border-radius: 10px; }
            .searchable-select-options::-webkit-scrollbar-thumb:hover { background: #cbd5e1; }
            .searchable-select-option.active { background-color: #f8fafc; }
            .searchable-select-display {
                min-height: 40px !important;
                height: 40px !important;
                padding-top: 0 !important;
                padding-bottom: 0 !important;
            }
        `;
        document.head.appendChild(style);
    }

    _readNativeOptions() {
        return Array.from(this.select.options).map(o => ({
            value:    o.value,
            text:     o.textContent.trim(),
            selected: o.selected,
        }));
    }

    /* ─────────────────────────── DOM BUILD ─────────────────────── */

    _buildDOM() {
        /* Wrapper (sits next to the hidden <select>) */
        this.wrapper = document.createElement('div');
        this.wrapper.className = 'searchable-select-wrapper relative w-full min-w-0';

        /* Trigger button */
        this.trigger = document.createElement('div');
        this.trigger.className =
            'searchable-select-display w-full min-w-0 px-4 border border-gray-200 rounded-xl ' +
            'text-[13px] font-semibold text-gray-700 bg-white cursor-pointer flex items-center justify-between ' +
            'hover:border-blue-400 hover:shadow-sm transition-all duration-200';

        if (this.isMultiple) {
            this.trigger.innerHTML = `
                <div class="flex-1 min-w-0 flex flex-wrap gap-1 items-center searchable-select-badges py-1">
                    <span class="searchable-select-placeholder text-gray-400 font-medium truncate">${this.options.placeholder}</span>
                </div>
                <svg class="w-4 h-4 text-gray-400 transition-transform flex-shrink-0 ml-2"
                     fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                </svg>`;
        } else {
            this.trigger.innerHTML = `
                <span class="searchable-select-text text-gray-400 truncate flex-1 min-w-0 pr-2">${this._getSelectedText()}</span>
                <svg class="w-3.5 h-3.5 text-gray-400 transition-transform flex-shrink-0"
                     fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                </svg>`;
        }

        /* Floating dropdown — appended to <body> to escape overflow:hidden parents */
        this.dropdown = document.createElement('div');
        this.dropdown.className =
            'searchable-select-dropdown hidden fixed bg-white border border-gray-100 ' +
            'rounded-2xl shadow-2xl overflow-hidden';
        this.dropdown.style.zIndex = '999999';
        this.dropdown.innerHTML = `
            <div class="p-2 border-b border-gray-50 flex items-center gap-2 bg-gray-50/50">
                <div class="relative flex-1">
                    <svg class="absolute left-2.5 top-1/2 -translate-y-1/2 w-3.5 h-3.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                    <input type="text"
                           class="searchable-select-search block w-full pl-8 pr-3 py-1.5 bg-white border border-gray-200 rounded-lg
                                  text-xs font-medium placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all"
                           placeholder="${this.options.searchPlaceholder}">
                </div>
                <button type="button"
                        class="searchable-select-close-btn w-7 h-7 flex items-center justify-center
                               rounded-lg hover:bg-white hover:shadow-sm text-gray-400 hover:text-gray-600 transition-all">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
            <div class="searchable-select-options overflow-y-auto p-1.5" style="max-height:260px">
                ${this._renderOptions()}
            </div>
            <div class="h-1 bg-gradient-to-t from-gray-50 to-transparent pointer-events-none"></div>`;

        this.wrapper.appendChild(this.trigger);
        document.body.appendChild(this.dropdown);

        this.searchInput      = this.dropdown.querySelector('.searchable-select-search');
        this.optionsContainer = this.dropdown.querySelector('.searchable-select-options');
        this.closeBtn         = this.dropdown.querySelector('.searchable-select-close-btn');
    }

    /* ─────────────────────────── EVENTS ────────────────────────── */

    _bindEvents() {
        /* Trigger click — open / close */
        this.trigger.addEventListener('mousedown', (e) => {
            // mousedown fires BEFORE the document-level click listener
            e.preventDefault();           // prevent focus stealing
            e.stopPropagation();

            if (this.isOpen) {
                this.close();
            } else {
                this._openAllOthersClose();
            }
        });

        /* Close button */
        this.closeBtn.addEventListener('mousedown', (e) => {
            e.preventDefault();
            e.stopPropagation();
            this.close();
        });

        /* Stop click from bubbling OUT of the dropdown (prevents outside-click close) */
        this.dropdown.addEventListener('mousedown', (e) => e.stopPropagation());

        /* Search */
        this.searchInput.addEventListener('input', (e) => {
            clearTimeout(this.searchTimeout);
            const q = e.target.value.trim();
            if (this.options.ajaxUrl && q.length >= this.options.minChars) {
                this.searchTimeout = setTimeout(() => this._loadAjax(q), this.options.debounceTime);
            } else {
                this._filterOptions(q);
            }
        });

        /* Option click */
        this.optionsContainer.addEventListener('mousedown', (e) => {
            e.preventDefault();
            e.stopPropagation();
            const opt = e.target.closest('.searchable-select-option');
            if (opt) this._selectOption(opt.dataset.value);
        });

        /* Outside click — close */
        document.addEventListener('mousedown', (e) => {
            if (!this.isOpen) return;
            if (this.wrapper.contains(e.target) || this.dropdown.contains(e.target)) return;
            this.close();
        });

        /* ESC key */
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && this.isOpen) this.close();
        });

        /* Reposition on scroll / resize */
        window.addEventListener('scroll', () => { if (this.isOpen) this._position(); }, true);
        window.addEventListener('resize', () => { if (this.isOpen) this._position(); });
    }

    /* ─────────────────────────── OPEN / CLOSE ──────────────────── */

    _openAllOthersClose() {
        searchableSelectInstances.forEach(i => { if (i !== this && i.isOpen) i.close(); });
        this.open();
    }

    open() {
        this.isOpen = true;
        this.dropdown.classList.remove('hidden');
        this._position();

        const arrow = this.trigger.querySelector('svg:last-child');
        if (arrow) arrow.style.transform = 'rotate(180deg)';

        // Small delay so focus doesn't immediately trigger outside-click
        setTimeout(() => this.searchInput.focus(), 30);
    }

    close() {
        this.isOpen = false;
        this.dropdown.classList.add('hidden');
        this.searchInput.value = '';

        const arrow = this.trigger.querySelector('svg:last-child');
        if (arrow) arrow.style.transform = 'rotate(0deg)';

        if (!this.options.ajaxUrl) {
            this.optionsContainer.innerHTML = this._renderOptions();
        }
    }

    /* ─────────────────────────── POSITION ──────────────────────── */

    _position() {
        const rect         = this.trigger.getBoundingClientRect();
        const dropHeight   = Math.min(this.optionsContainer.scrollHeight + 50, 320); // 50 for search bar, 320 max
        const spaceBelow   = window.innerHeight - rect.bottom - 10;
        const spaceAbove   = rect.top - 10;

        this.dropdown.style.left  = rect.left  + 'px';
        this.dropdown.style.width = rect.width + 'px';

        if (spaceBelow < dropHeight && spaceAbove > spaceBelow) {
            // Show Above
            this.dropdown.style.top    = 'auto';
            this.dropdown.style.bottom = (window.innerHeight - rect.top + 4) + 'px';
            this.dropdown.classList.add('origin-bottom');
        } else {
            // Show Below
            this.dropdown.style.top    = (rect.bottom + 4) + 'px';
            this.dropdown.style.bottom = 'auto';
            this.dropdown.classList.add('origin-top');
        }
    }

    /* ─────────────────────────── RENDER ────────────────────────── */

    _renderOptions(opts = this.allOptions) {
        if (opts.length === 0) {
            return `<div class="px-4 py-8 text-xs font-medium text-gray-400 text-center uppercase tracking-widest">No Results</div>`;
        }

        if (this.isMultiple) {
            return opts.map(o => `
                <div class="searchable-select-option flex items-center gap-3 px-3 py-2 text-[13px] font-medium
                            cursor-pointer hover:bg-blue-50/50 rounded-lg transition-all ${o.selected ? 'bg-blue-50' : ''} mb-0.5 last:mb-0"
                     data-value="${o.value}">
                    <div class="w-4 h-4 border border-gray-300 rounded flex items-center justify-center transition-all ${o.selected ? 'bg-[#0082C3] border-[#0082C3]' : 'bg-white'}">
                        ${o.selected ? '<svg class="w-2.5 h-2.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="4"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>' : ''}
                    </div>
                    <span class="${o.selected ? 'text-[#0082C3] font-bold' : 'text-gray-600'} truncate">${o.text}</span>
                </div>`).join('');
        }

        return opts.map(o => `
            <div class="searchable-select-option px-3 py-2 text-[13px] font-medium cursor-pointer
                        hover:bg-blue-50/50 rounded-lg transition-all ${o.selected ? 'bg-blue-50 text-[#0082C3] font-bold shadow-sm' : 'text-gray-600'} mb-0.5 last:mb-0"
                 data-value="${o.value}">
                ${o.text}
            </div>`).join('');
    }

    _filterOptions(q) {
        const lower    = q.toLowerCase();
        const filtered = this.allOptions.filter(o => o.text.toLowerCase().includes(lower));
        this.optionsContainer.innerHTML = this._renderOptions(filtered);
    }

    /* ─────────────────────────── SELECT OPTION ─────────────────── */

    _selectOption(value) {
        if (this.isMultiple) {
            const opt = this.allOptions.find(o => o.value === value);
            if (opt) opt.selected = !opt.selected;

            Array.from(this.select.options).forEach(o => {
                const d = this.allOptions.find(a => a.value === o.value);
                o.selected = d ? d.selected : false;
            });

            this._updateBadges();
            this.optionsContainer.innerHTML = this._renderOptions();
            this.select.dispatchEvent(new Event('change', { bubbles: true }));
            if (this.options.onChange) {
                const sel = this.allOptions.filter(o => o.selected);
                this.options.onChange(sel.map(o => o.value), sel);
            }
        } else {
            this.select.value  = value;
            this.selectedValue = value;

            const selOpt    = this.allOptions.find(o => o.value === value);
            const textEl    = this.trigger.querySelector('.searchable-select-text');
            if (textEl) {
                textEl.textContent = selOpt ? selOpt.text : this.options.placeholder;
                textEl.classList.toggle('text-gray-400', !selOpt || !selOpt.value);
                textEl.classList.toggle('text-gray-700', !!(selOpt && selOpt.value));
            }

            this.allOptions.forEach(o => o.selected = o.value === value);
            this.optionsContainer.innerHTML = this._renderOptions();
            this.select.dispatchEvent(new Event('change', { bubbles: true }));
            if (this.options.onChange) this.options.onChange(value, selOpt);
            this.close();
        }
    }

    /* ─────────────────────────── BADGES (multi) ────────────────── */

    _updateBadges() {
        const container = this.trigger.querySelector('.searchable-select-badges');
        if (!container) return;

        const selected = this.allOptions.filter(o => o.selected);
        if (selected.length === 0) {
            container.innerHTML =
                `<span class="searchable-select-placeholder text-gray-400 text-sm">${this.options.placeholder}</span>`;
            return;
        }

        container.innerHTML = selected.map(o => `
            <span class="inline-flex items-center gap-1 px-2 py-0.5 bg-blue-100 text-blue-700 text-xs font-medium rounded-full">
                ${o.text}
                <button type="button" data-remove="${o.value}"
                        class="hover:text-blue-900 ml-0.5 leading-none">×</button>
            </span>`).join('');

        container.querySelectorAll('[data-remove]').forEach(btn => {
            btn.addEventListener('mousedown', (e) => {
                e.preventDefault();
                e.stopPropagation();
                this._selectOption(btn.dataset.remove);
            });
        });
    }

    /* ─────────────────────────── HELPERS ───────────────────────── */

    _getSelectedText() {
        if (this.isMultiple) {
            const sel = this.allOptions.filter(o => o.selected);
            return sel.length ? `${sel.length} selected` : this.options.placeholder;
        }
        const sel = this.allOptions.find(o => o.value === this.selectedValue);
        return sel ? sel.text : this.options.placeholder;
    }

    /* ─────────────────────────── AJAX ──────────────────────────── */

    _loadAjax(query = '') {
        const params = new URLSearchParams({ ...this.options.ajaxParams, search: query });
        this.optionsContainer.innerHTML = `
            <div class="px-3 py-4 text-sm text-gray-400 text-center">
                <svg class="animate-spin h-5 w-5 mx-auto mb-1 text-gray-300" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                    <path class="opacity-75" fill="currentColor"
                          d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/>
                </svg>Loading…</div>`;

        fetch(`${this.options.ajaxUrl}?${params}`, { credentials: 'same-origin' })
            .then(r => r.json())
            .then(data => {
                if (data.success && data.data) {
                    this.allOptions = data.data.map(item => ({
                        value:    item.id,
                        text:     item.name,
                        selected: String(item.id) === String(this.selectedValue),
                    }));
                    this.optionsContainer.innerHTML = this._renderOptions();
                }
            })
            .catch(() => {
                this.optionsContainer.innerHTML =
                    `<div class="px-3 py-3 text-sm text-red-500 text-center">Failed to load</div>`;
            });
    }

    /* ─────────────────────────── PUBLIC API ────────────────────── */

    refresh() {
        this.allOptions = this._readNativeOptions();
        this.optionsContainer.innerHTML = this._renderOptions();

        if (this.isMultiple) {
            this._updateBadges();
        } else {
            const textEl = this.trigger.querySelector('.searchable-select-text');
            const sel    = this.allOptions.find(o => o.selected);
            if (textEl) {
                textEl.textContent = sel ? sel.text : this.options.placeholder;
                textEl.classList.toggle('text-gray-400', !sel || !sel.value);
                textEl.classList.toggle('text-gray-700', !!(sel && sel.value));
            }
        }
    }

    setValue(value) {
        this.select.value = value;
        this.selectedValue = value;
        this.allOptions.forEach(o => o.selected = o.value === value);
        this.refresh();
    }

    destroy() {
        const i = searchableSelectInstances.indexOf(this);
        if (i > -1) searchableSelectInstances.splice(i, 1);
        this.wrapper.remove();
        this.dropdown.remove();
        this.select.style.display = '';
    }
}

/* ── Auto-init all [data-searchable] selects on page load ── */
document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('select[data-searchable]').forEach(sel => {
        new SearchableSelect(sel, {
            ajaxUrl:           sel.dataset.ajaxUrl           || null,
            placeholder:       sel.dataset.placeholder       || 'Select...',
            searchPlaceholder: sel.dataset.searchPlaceholder || 'Type to search...',
        });
    });
});
