/**
 * Searchable Select - Pure Vanilla JS
 * No dependencies, lightweight, AJAX support
 */

// Global array to track all instances
const searchableSelectInstances = [];

class SearchableSelect {
    constructor(selectElement, options = {}) {
        this.select = selectElement;
        this.isMultiple = this.select.hasAttribute('multiple');
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
        this.selectedValues = []; // For multi-select
        this.searchTimeout = null;
        this.visibilityCheckInterval = null; // For checking if parent is visible
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
        this.selectedDisplay.className = 'searchable-select-display px-3.5 py-2.5 border border-gray-300 rounded-lg text-sm bg-white cursor-pointer flex items-center justify-between hover:border-gray-400 transition-colors min-h-[42px]';
        
        if (this.isMultiple) {
            // Multi-select: badges container
            this.selectedDisplay.innerHTML = `
                <div class="flex-1 flex flex-wrap gap-1.5 items-center searchable-select-badges">
                    <span class="searchable-select-placeholder text-gray-400 text-sm">${this.options.placeholder}</span>
                </div>
                <svg class="w-4 h-4 text-gray-400 transition-transform flex-shrink-0 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                </svg>
            `;
        } else {
            // Single select: text display
            this.selectedDisplay.innerHTML = `
                <span class="searchable-select-text text-gray-700">${this.getSelectedText()}</span>
                <svg class="w-4 h-4 text-gray-400 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                </svg>
            `;
        }
        
        // Dropdown container (will be appended to body)
        this.dropdown = document.createElement('div');
        this.dropdown.className = 'searchable-select-dropdown hidden fixed z-[99999] mt-1 bg-white border border-gray-300 rounded-lg shadow-lg max-h-64 overflow-hidden';
        this.dropdown.innerHTML = `
            <div class="p-2 border-b border-gray-200 flex items-center gap-2">
                <input type="text" class="searchable-select-search flex-1 px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#0082C3] focus:border-transparent" placeholder="${this.options.searchPlaceholder}">
                <button type="button" class="searchable-select-close-btn flex-shrink-0 w-8 h-8 flex items-center justify-center rounded-lg hover:bg-gray-100 text-gray-400 hover:text-gray-600 transition-colors" title="Close">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            <div class="searchable-select-options overflow-y-auto max-h-48">
                ${this.renderOptions()}
            </div>
        `;
        
        this.wrapper.appendChild(this.selectedDisplay);
        // Append dropdown to body instead of wrapper
        document.body.appendChild(this.dropdown);
        
        // Get references
        this.searchInput = this.dropdown.querySelector('.searchable-select-search');
        this.optionsContainer = this.dropdown.querySelector('.searchable-select-options');
        this.closeBtn = this.dropdown.querySelector('.searchable-select-close-btn');
    }
    
    renderOptions(options = this.allOptions) {
        if (options.length === 0) {
            return `<div class="px-3 py-2 text-sm text-gray-500 text-center">${this.options.noResultsText}</div>`;
        }
        
        if (this.isMultiple) {
            // Multi-select with checkboxes
            return options.map(opt => `
                <div class="searchable-select-option px-3 py-2 text-sm cursor-pointer hover:bg-gray-100 transition-colors ${opt.selected ? 'bg-blue-50' : ''}" data-value="${opt.value}">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" ${opt.selected ? 'checked' : ''} class="w-4 h-4 text-[#0082C3] border-gray-300 rounded focus:ring-[#0082C3]" onclick="event.stopPropagation()">
                        <span class="${opt.selected ? 'text-[#0082C3] font-medium' : 'text-gray-700'}">${opt.text}</span>
                    </label>
                </div>
            `).join('');
        } else {
            // Single select
            return options.map(opt => `
                <div class="searchable-select-option px-3 py-2 text-sm cursor-pointer hover:bg-gray-100 transition-colors ${opt.selected ? 'bg-blue-50 text-blue-700' : 'text-gray-700'}" data-value="${opt.value}">
                    ${opt.text}
                </div>
            `).join('');
        }
    }
    
    setupEventListeners() {
        // Toggle dropdown
        this.selectedDisplay.addEventListener('click', (e) => {
            e.stopPropagation();
            this.toggle();
        });
        
        // Close button
        this.closeBtn.addEventListener('click', (e) => {
            e.stopPropagation();
            this.close();
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
        
        // Close on outside click (anywhere on page)
        document.addEventListener('click', (e) => {
            if (this.isOpen && !this.wrapper.contains(e.target) && !this.dropdown.contains(e.target)) {
                this.close();
            }
        });
        
        // Prevent dropdown close on search input click
        this.searchInput.addEventListener('click', (e) => {
            e.stopPropagation();
        });
        
        // Close on ESC key
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && this.isOpen) {
                this.close();
            }
        });
        
        // Update position on scroll
        window.addEventListener('scroll', () => {
            if (this.isOpen) {
                this.updatePosition();
            }
        }, true);
        
        // Update position on resize
        window.addEventListener('resize', () => {
            if (this.isOpen) {
                this.updatePosition();
            }
        });
    }
    
    updatePosition() {
        const rect = this.selectedDisplay.getBoundingClientRect();
        const dropdownHeight = 300;
        const spaceBelow = window.innerHeight - rect.bottom;
        const spaceAbove = rect.top;
        
        this.dropdown.style.left = rect.left + 'px';
        this.dropdown.style.width = rect.width + 'px';
        
        if (spaceBelow < dropdownHeight && spaceAbove > spaceBelow) {
            this.dropdown.style.bottom = (window.innerHeight - rect.top) + 'px';
            this.dropdown.style.top = 'auto';
        } else {
            this.dropdown.style.top = (rect.bottom + 4) + 'px';
            this.dropdown.style.bottom = 'auto';
        }
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
        
        // Get position of select element
        const rect = this.selectedDisplay.getBoundingClientRect();
        const dropdownHeight = 300; // approximate max height
        const spaceBelow = window.innerHeight - rect.bottom;
        const spaceAbove = rect.top;
        
        // Position dropdown using fixed positioning
        this.dropdown.style.left = rect.left + 'px';
        this.dropdown.style.width = rect.width + 'px';
        
        if (spaceBelow < dropdownHeight && spaceAbove > spaceBelow) {
            // Open upward
            this.dropdown.style.bottom = (window.innerHeight - rect.top) + 'px';
            this.dropdown.style.top = 'auto';
        } else {
            // Open downward (default)
            this.dropdown.style.top = (rect.bottom + 4) + 'px';
            this.dropdown.style.bottom = 'auto';
        }
        
        // Rotate arrow
        const arrow = this.selectedDisplay.querySelector('svg');
        arrow.style.transform = 'rotate(180deg)';
        
        // Check if parent modal/container is still visible
        this.startVisibilityCheck();
    }
    
    startVisibilityCheck() {
        // Clear any existing interval
        if (this.visibilityCheckInterval) {
            clearInterval(this.visibilityCheckInterval);
        }
        
        // Check every 100ms if the select element is still visible
        this.visibilityCheckInterval = setInterval(() => {
            if (this.isOpen) {
                const isVisible = this.isElementVisible(this.wrapper);
                if (!isVisible) {
                    this.close();
                }
            } else {
                // Stop checking if dropdown is closed
                clearInterval(this.visibilityCheckInterval);
                this.visibilityCheckInterval = null;
            }
        }, 100);
    }
    
    isElementVisible(element) {
        // Check if element or any parent has display:none or visibility:hidden
        let el = element;
        while (el) {
            const style = window.getComputedStyle(el);
            if (style.display === 'none' || style.visibility === 'hidden') {
                return false;
            }
            // Check for hidden class (common in Tailwind)
            if (el.classList && el.classList.contains('hidden')) {
                return false;
            }
            el = el.parentElement;
        }
        return true;
    }
    
    close() {
        this.dropdown.classList.add('hidden');
        this.isOpen = false;
        this.searchInput.value = '';
        
        // Clear visibility check interval
        if (this.visibilityCheckInterval) {
            clearInterval(this.visibilityCheckInterval);
            this.visibilityCheckInterval = null;
        }
        
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
        if (this.isMultiple) {
            // Multi-select: toggle selection
            const opt = this.allOptions.find(o => o.value === value);
            if (opt) {
                opt.selected = !opt.selected;
            }
            
            // Update native select
            Array.from(this.select.options).forEach(option => {
                const optData = this.allOptions.find(o => o.value === option.value);
                option.selected = optData ? optData.selected : false;
            });
            
            // Update badges display
            this.updateBadgesDisplay();
            
            // Update options display
            this.optionsContainer.innerHTML = this.renderOptions();
            
            // Trigger change event
            const event = new Event('change', { bubbles: true });
            this.select.dispatchEvent(event);
            
            // Call onChange callback
            if (this.options.onChange) {
                const selectedOpts = this.allOptions.filter(o => o.selected);
                this.options.onChange(selectedOpts.map(o => o.value), selectedOpts);
            }
            
            // Don't close dropdown for multi-select
        } else {
            // Single select: close dropdown after selection
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
    }
    
    updateBadgesDisplay() {
        const badgesContainer = this.selectedDisplay.querySelector('.searchable-select-badges');
        if (!badgesContainer) return;
        
        const selectedOpts = this.allOptions.filter(o => o.selected);
        
        if (selectedOpts.length === 0) {
            // Show placeholder
            badgesContainer.innerHTML = `<span class="searchable-select-placeholder text-gray-400 text-sm">${this.options.placeholder}</span>`;
        } else {
            // Show badges
            badgesContainer.innerHTML = selectedOpts.map(opt => `
                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 bg-blue-100 text-blue-700 text-xs font-medium rounded-full">
                    ${opt.text}
                    <button type="button" class="hover:text-blue-900 focus:outline-none" data-remove-value="${opt.value}">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </span>
            `).join('');
            
            // Add event listeners for remove buttons
            badgesContainer.querySelectorAll('[data-remove-value]').forEach(btn => {
                btn.addEventListener('click', (e) => {
                    e.stopPropagation();
                    const valueToRemove = btn.dataset.removeValue;
                    this.selectOption(valueToRemove); // Toggle off
                });
            });
        }
    }
    
    getSelectedText() {
        if (this.isMultiple) {
            const selected = this.allOptions.filter(opt => opt.selected);
            if (selected.length > 0) {
                return `${selected.length} selected`;
            }
            return this.options.placeholder;
        } else {
            const selected = this.allOptions.find(opt => opt.value === this.selectedValue);
            return selected ? selected.text : this.options.placeholder;
        }
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
        // Clear visibility check interval
        if (this.visibilityCheckInterval) {
            clearInterval(this.visibilityCheckInterval);
            this.visibilityCheckInterval = null;
        }
        
        // Remove from global instances array
        const index = searchableSelectInstances.indexOf(this);
        if (index > -1) {
            searchableSelectInstances.splice(index, 1);
        }
        
        this.wrapper.remove();
        this.dropdown.remove(); // Remove dropdown from body
        this.select.style.display = '';
    }
    
    refresh() {
        this.allOptions = Array.from(this.select.options).map(opt => ({
            value: opt.value,
            text: opt.textContent,
            selected: opt.selected
        }));
        this.optionsContainer.innerHTML = this.renderOptions();
        
        if (this.isMultiple) {
            // Update badges display for multi-select
            this.updateBadgesDisplay();
        } else {
            // Update display text for single select
            const displayText = this.selectedDisplay.querySelector('.searchable-select-text');
            const selected = this.allOptions.find(opt => opt.selected);
            displayText.textContent = selected ? selected.text : this.options.placeholder;
        }
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
