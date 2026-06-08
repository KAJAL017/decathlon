/**
 * Shop Module
 * Handles sidebar filters, sorting, and product grid interactions
 */
export default class Shop {
    constructor() {
        this.initSidebar();
        this.initSorting();
    }

    /**
     * Initialize sidebar filter accordions
     */
    initSidebar() {
        const triggers = document.querySelectorAll('.filter-trigger');
        
        triggers.forEach(trigger => {
            const panelId = trigger.dataset.target;
            if (panelId) {
                trigger.addEventListener('click', () => this.toggleSidebarSection(panelId));
            }
        });

        // Initialize panels state
        const panels = document.querySelectorAll('[id$="-panel"]');
        panels.forEach(p => {
            const isActive = p.querySelector('.font-bold');
            const isPrice = p.id === 'price-panel';

            if (isActive || isPrice) {
                p.style.maxHeight = p.scrollHeight + 'px';
                p.classList.remove('collapsed');
                const arrow = document.getElementById(p.id.replace('-panel', '-arrow'));
                if (arrow) arrow.textContent = '—';
            } else {
                p.style.maxHeight = '0px';
                p.classList.add('collapsed');
                const arrow = document.getElementById(p.id.replace('-panel', '-arrow'));
                if (arrow) arrow.textContent = '+';
            }
        });
    }

    /**
     * Toggle a sidebar filter section
     * @param {string} id 
     */
    toggleSidebarSection(id) {
        const panel = document.getElementById(id);
        const arrow = document.getElementById(id.replace('-panel', '-arrow'));
        
        if (!panel) return;

        if (panel.style.maxHeight === '0px' || panel.classList.contains('collapsed')) {
            panel.style.maxHeight = panel.scrollHeight + 'px';
            panel.classList.remove('collapsed');
            if (arrow) arrow.textContent = '—';
        } else {
            panel.style.maxHeight = '0px';
            panel.classList.add('collapsed');
            if (arrow) arrow.textContent = '+';
        }
    }

    /**
     * Initialize sorting dropdown behavior if needed beyond hover
     */
    initSorting() {
        // Sort functionality is mostly handled by hover in CSS, 
        // but we can add mobile click support here if needed
    }
}
