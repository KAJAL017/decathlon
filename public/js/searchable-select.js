/**
 * Searchable Select - Pure Vanilla JS
 * No dependencies, lightweight, AJAX support
 */

// Global array to track all instances
const searchableSelectInstances = [];

class SearchableSelect {
    constructor(selectElement, options = {}) {
        this.select = selectElement;
        this.options = {
            placeholder: options.placeholder || 'Search...',
            searchPlaceholder: options.searchPlaceholder || 'Type to search...',
            noResultsText: options.noResultsText || 'No results found',
            ajaxUrl: options.ajaxUrl || null,
            ajaxParams: options.ajaxParams || {},
            onChange: options.onChange || null,
            minChars: options.minChars || 0,
            debounceTime: options.debounceTime || 300
        };
        
        this.isOpen = false;
        this.selectedValue = this.select.value;
        this.searchTimeout = null;
        this.allOptions = [];
        
        this.init();
        
        // Add to global instances array
        searchableSelectInstances.push(this);
    }
    
    init() {
        // Store original options
        this.allOptions = Array.from(this.select.options).map(opt => ({
            value: opt.value,
            text: opt.textContent,
            selected: opt.selected
        }));
        
        // Create custom select structure
        this.createCustomSelect();
        
        // Hide original select
        this.select.style.display = 'none';
        
        // Insert custom select after original
        this.select.parentNode.insertBefore(this.wrapper, this.select.nextSibling);
        
        // Setup event listeners
        this.setupEventListeners();
        
        // Load AJAX data if URL provided
        if (this.options.ajaxUrl) {
            this.loadAjaxData();
        }
    }
    
    createCustomSelect() {
        // Main wrapper
        this.wrapper = document.createElement('div');
        this.wrapper.className = 'searchable-select-wrapper relative';
        
        // Selected display
        this.selectedDisplay = document.createElement('div');
        this.selectedDisplay.className = 'searchable-select-display px-3.5 py-2.5 border border-gray-300 rounded-lg text-sm bg-white cursor-pointer flex items-center justify-between hover:border-gray-400 transition-colors';
        this.selectedDisplay.innerHTML = `
            <span class="searchable-select-text text-gray-700">${this.getSelectedText()}</span>
            <svg class="w-4 h-4 text-gray-400 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
            </svg>
        `;
        
        // Dropdown container
        this.dropdown = document.createElement('div');
        this.dropdown.className = 'searchable-select-dropdown hidden absolute z-50 w-full mt-1 bg-white border border-gray-300 rounded-lg shadow-lg max-h-64 overflow-hidden';
        this.dropdown.innerHTML = `
            <div class="p-2 border-b border-gray-200">
                <input type="text" class="searchable-select-search w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#0082C3] focus:border-transparent" placeholder="${this.options.searchPlaceholder}">
            </div>
            <div class="searchable-select-options overflow-y-auto max-h-48">
                ${this.renderOptions()}
            </div>
        `;
        
        this.wrapper.appendChild(this.selectedDisplay);
        this.wrapper.appendChild(this.dropdown);
        
        // Get references
        this.searchInput = this.dropdown.querySelector('.searchable-select-search');
        this.optionsContainer = this.dropdown.querySelector('.searchable-select-options');
    }
    
    renderOptions(options = this.allOptions) {
        if (options.length === 0) {
            return `<div class="px-3 py-2 text-sm text-gray-500 text-center">${this.options.noResultsText}</div>`;
        }
        
        return options.map(opt => `
            <div class="searchable-select-option px-3 py-2 text-sm cursor-pointer hover:bg-gray-100 transition-colors ${opt.selected ? 'bg-blue-50 text-blue-700' : 'text-gray-700'}" data-value="${opt.value}">
                ${opt.text}
            </div>
        `).join('');
    }
    
    setupEventListeners() {
        // Toggle dropdown
        this.selectedDisplay.addEventListener('click', (e) => {
            e.stopPropagation();
            this.toggle();
        });
        
        // Search input
        this.searchInput.addEventListener('input', (e) => {
            clearTimeout(this.searchTimeout);
            const query = e.target.value.trim();
            
            if (this.options.ajaxUrl && query.length >= this.options.minChars) {
                this.searchTimeout = setTimeout(() => {
                    this.loadAjaxData(query);
                }, this.options.debounceTime);
            } else {
                this.filterOptions(query);
            }
        });
        
        // Option selection
        this.optionsContainer.addEventListener('click', (e) => {
            const option = e.target.closest('.searchable-select-option');
            if (option) {
                this.selectOption(option.dataset.value);
            }
        });
        
        // Close on outside click
        document.addEventListener('click', (e) => {
            if (!this.wrapper.contains(e.target)) {
                this.close();
            }
        });
        
        // Prevent dropdown close on search input click
        this.searchInput.addEventListener('click', (e) => {
            e.stopPropagation();
        });
    }
    
    toggle() {
        if (this.isOpen) {
            this.close();
        } else {
            // Close all other open searchable selects
            this.closeAllOthers();
            this.open();
        }
    }
    
    closeAllOthers() {
        searchableSelectInstances.forEach(instance => {
            if (instance !== this && instance.isOpen) {
                instance.close();
            }
        });
    }
    
    open() {
        this.dropdown.classList.remove('hidden');
        this.isOpen = true;
        this.searchInput.focus();
        
        // Rotate arrow
        const arrow = this.selectedDisplay.querySelector('svg');
        arrow.style.transform = 'rotate(180deg)';
    }
    
    close() {
        this.dropdown.classList.add('hidden');
        this.isOpen = false;
        this.searchInput.value = '';
        
        // Reset arrow
        const arrow = this.selectedDisplay.querySelector('svg');
        arrow.style.transform = 'rotate(0deg)';
        
        // Reset options display
        if (!this.options.ajaxUrl) {
            this.optionsContainer.innerHTML = this.renderOptions();
        }
    }
    
    filterOptions(query) {
        const filtered = this.allOptions.filter(opt => 
            opt.text.toLowerCase().includes(query.toLowerCase())
        );
        this.optionsContainer.innerHTML = this.renderOptions(filtered);
    }
    
    selectOption(value) {
        // Update original select
        this.select.value = value;
        this.selectedValue = value;
        
        // Update display
        const selectedOpt = this.allOptions.find(opt => opt.value === value);
        const displayText = this.selectedDisplay.querySelector('.searchable-select-text');
        displayText.textContent = selectedOpt ? selectedOpt.text : this.options.placeholder;
        
        // Update options styling
        this.allOptions.forEach(opt => opt.selected = opt.value === value);
        this.optionsContainer.innerHTML = this.renderOptions();
        
        // Trigger change event
        const event = new Event('change', { bubbles: true });
        this.select.dispatchEvent(event);
        
        // Call onChange callback
        if (this.options.onChange) {
            this.options.onChange(value, selectedOpt);
        }
        
        this.close();
    }
    
    getSelectedText() {
        const selected = this.allOptions.find(opt => opt.value === this.selectedValue);
        return selected ? selected.text : this.options.placeholder;
    }
    
    loadAjaxData(query = '') {
        const params = new URLSearchParams({
            ...this.options.ajaxParams,
            search: query
        });
        
        // Show loading
        this.optionsContainer.innerHTML = `
            <div class="px-3 py-2 text-sm text-gray-500 text-center">
                <svg class="animate-spin h-5 w-5 mx-auto text-gray-400" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
            </div>
        `;
        
        fetch(`${this.options.ajaxUrl}?${params}`)
            .then(response => response.json())
            .then(data => {
                // Assuming data format: { success: true, data: [{id, name}] }
                if (data.success && data.data) {
                    this.allOptions = data.data.map(item => ({
                        value: item.id,
                        text: item.name,
                        selected: item.id == this.selectedValue
                    }));
                    this.optionsContainer.innerHTML = this.renderOptions();
                }
            })
            .catch(error => {
                console.error('AJAX load error:', error);
                this.optionsContainer.innerHTML = `
                    <div class="px-3 py-2 text-sm text-red-500 text-center">Failed to load options</div>
                `;
            });
    }
    
    destroy() {
        // Remove from global instances array
        const index = searchableSelectInstances.indexOf(this);
        if (index > -1) {
            searchableSelectInstances.splice(index, 1);
        }
        
        this.wrapper.remove();
        this.select.style.display = '';
    }
    
    refresh() {
        this.allOptions = Array.from(this.select.options).map(opt => ({
            value: opt.value,
            text: opt.textContent,
            selected: opt.selected
        }));
        this.optionsContainer.innerHTML = this.renderOptions();
    }
}

// Auto-initialize on elements with data-searchable attribute
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('select[data-searchable]').forEach(select => {
        new SearchableSelect(select, {
            ajaxUrl: select.dataset.ajaxUrl || null,
            placeholder: select.dataset.placeholder || 'Select...',
            searchPlaceholder: select.dataset.searchPlaceholder || 'Type to search...'
        });
    });
});
