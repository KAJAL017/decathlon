/**
 * Header Module
 * Handles mega dropdowns and header UI interactions
 */
export default class Header {
    constructor() {
        this.initMegaMenu();
    }

    /**
     * Initialize mega dropdown hover behavior
     */
    initMegaMenu() {
        const items = document.querySelectorAll('.mega-nav-item');
        let closeTimer = null;

        const closeAll = (except) => {
            items.forEach(item => {
                if (item === except) return;
                const d = item.querySelector('.mega-dropdown');
                const t = item.querySelector('.nav-trigger');
                const c = item.querySelector('.nav-chevron');
                if (d) d.style.display = 'none';
                if (t) { t.style.color = ''; t.style.borderBottomColor = 'transparent'; }
                if (c) c.style.transform = '';
            });
        };

        items.forEach(item => {
            const trigger  = item.querySelector('.nav-trigger');
            const dropdown = item.querySelector('.mega-dropdown');
            const chevron  = item.querySelector('.nav-chevron');
            if (!dropdown) return;

            const open = () => {
                clearTimeout(closeTimer);
                closeAll(item);
                dropdown.style.display = 'block';
                if (trigger) { trigger.style.color = '#0082C3'; trigger.style.borderBottomColor = '#0082C3'; }
                if (chevron) chevron.style.transform = 'rotate(180deg)';
            };

            const close = () => {
                closeTimer = setTimeout(() => {
                    dropdown.style.display = 'none';
                    if (trigger) { trigger.style.color = ''; trigger.style.borderBottomColor = 'transparent'; }
                    if (chevron) chevron.style.transform = '';
                }, 100);
            };

            item.addEventListener('mouseenter', open);
            item.addEventListener('mouseleave', close);
            dropdown.addEventListener('mouseenter', () => clearTimeout(closeTimer));
            dropdown.addEventListener('mouseleave', close);
        });

        /* Close when clicking outside */
        document.addEventListener('click', e => {
            if (!e.target.closest('.mega-nav-item')) closeAll(null);
        });
    }
}
