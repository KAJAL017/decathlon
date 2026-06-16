{{-- ═══════════════════════════════════════════════════════════════
     PROFESSIONAL DIALOG SYSTEM
     • Smooth Backdrop Blur & Fade
     • Scale & Bounce Entrance
     • Fully Dynamic (Title, Message, Icons, Buttons)
     ═══════════════════════════════════════════════════════════════ --}}
<div id="global-dialog" 
     class="fixed inset-0 z-[100000] hidden flex items-center justify-center p-4 sm:p-6"
     role="dialog" 
     aria-modal="true">
    
    {{-- Backdrop --}}
    <div id="dialog-backdrop" 
         class="absolute inset-0 bg-gray-950/40 backdrop-blur-[4px] opacity-0 transition-opacity duration-300"></div>

    {{-- Dialog Panel --}}
    <div id="dialog-panel" 
         class="relative w-full max-w-md bg-white rounded-3xl shadow-[0_20px_50px_rgba(0,0,0,0.15)] border border-gray-100 overflow-hidden scale-95 opacity-0 transition-all duration-300 transform">
        
        {{-- Content --}}
        <div class="p-8 text-center">
            {{-- Icon Container --}}
            <div id="dialog-icon-container" class="mx-auto w-16 h-16 rounded-full flex items-center justify-center mb-6">
                {{-- Dynamic Icon Injected via JS --}}
                <div id="dialog-icon"></div>
            </div>

            {{-- Text --}}
            <h3 id="dialog-title" class="text-xl font-black text-gray-950 uppercase tracking-tight mb-2"></h3>
            <p id="dialog-message" class="text-sm text-gray-500 font-medium leading-relaxed px-2"></p>
        </div>

        {{-- Actions --}}
        <div class="p-4 bg-gray-50/50 border-t border-gray-100 flex gap-3">
            <button id="dialog-cancel-btn" 
                    class="flex-1 px-6 py-3.5 rounded-2xl text-xs font-black uppercase tracking-widest text-gray-500 hover:text-gray-950 hover:bg-gray-100 transition-all">
                Cancel
            </button>
            <button id="dialog-confirm-btn" 
                    class="flex-1 px-6 py-3.5 rounded-2xl text-xs font-black uppercase tracking-widest text-white shadow-lg transition-all hover:scale-[1.02] active:scale-[0.98]">
                Confirm
            </button>
        </div>
    </div>
</div>

<style>
    #global-dialog.active #dialog-backdrop { opacity: 1; }
    #global-dialog.active #dialog-panel { opacity: 1; scale: 1; }
</style>

<script>
/**
 * Global Dialog Controller
 */
window.Dialog = {
    _resolve: null,
    
    init() {
        this.el = document.getElementById('global-dialog');
        this.panel = document.getElementById('dialog-panel');
        this.backdrop = document.getElementById('dialog-backdrop');
        this.title = document.getElementById('dialog-title');
        this.message = document.getElementById('dialog-message');
        this.iconContainer = document.getElementById('dialog-icon-container');
        this.icon = document.getElementById('dialog-icon');
        this.confirmBtn = document.getElementById('dialog-confirm-btn');
        this.cancelBtn = document.getElementById('dialog-cancel-btn');

        this.confirmBtn.onclick = () => this.close(true);
        this.cancelBtn.onclick = () => this.close(false);
        this.backdrop.onclick = () => this.close(false);
    },

    /**
     * Show confirmation dialog (Two buttons)
     * @param {Object} options {title, message, type, confirmText, cancelText}
     */
    confirm(options = {}) {
        return this.show({ ...options, mode: 'confirm' });
    },

    /**
     * Show alert dialog (One button)
     * @param {Object} options {title, message, type, confirmText}
     */
    alert(options = {}) {
        return this.show({ ...options, mode: 'alert' });
    },

    show(options = {}) {
        return new Promise((resolve) => {
            this._resolve = resolve;
            
            const {
                title = 'Notice',
                message = '',
                type = 'info', // danger, warning, success, info
                confirmText = 'OK',
                cancelText = 'Cancel',
                mode = 'confirm'
            } = options;

            this.title.innerText = title;
            this.message.innerText = message;
            this.confirmBtn.innerText = confirmText;
            this.cancelBtn.innerText = cancelText;

            // Mode toggle
            if (mode === 'alert') {
                this.cancelBtn.classList.add('hidden');
            } else {
                this.cancelBtn.classList.remove('hidden');
            }

            // Type styling
            this.applyType(type);

            // Show
            this.el.classList.remove('hidden');
            this.el.classList.add('flex');
            
            // Force reflow for animation
            setTimeout(() => {
                this.el.classList.add('active');
            }, 10);
        });
    },

    applyType(type) {
        const configs = {
            danger: {
                bg: 'bg-red-50',
                text: 'text-red-600',
                btn: 'bg-red-600 hover:bg-red-700 shadow-red-200',
                icon: 'trash-2'
            },
            warning: {
                bg: 'bg-amber-50',
                text: 'text-amber-600',
                btn: 'bg-amber-500 hover:bg-amber-600 shadow-amber-200',
                icon: 'triangle-alert'
            },
            success: {
                bg: 'bg-emerald-50',
                text: 'text-emerald-600',
                btn: 'bg-emerald-600 hover:bg-emerald-700 shadow-emerald-200',
                icon: 'check'
            },
            info: {
                bg: 'bg-blue-50',
                text: 'text-blue-600',
                btn: 'bg-blue-600 hover:bg-blue-700 shadow-blue-200',
                icon: 'info'
            }
        };

        const config = configs[type] || configs.info;
        
        // Reset and apply
        this.iconContainer.className = `mx-auto w-16 h-16 rounded-full flex items-center justify-center mb-6 ${config.bg} ${config.text}`;
        this.icon.innerHTML = `<i data-lucide="${config.icon}" class="w-8 h-8"></i>`;
        if (typeof lucide !== 'undefined') lucide.createIcons({ nodes: [this.icon] });
        this.confirmBtn.className = `flex-1 px-6 py-3.5 rounded-2xl text-xs font-black uppercase tracking-widest text-white shadow-lg transition-all hover:scale-[1.02] active:scale-[0.98] ${config.btn}`;
    },

    close(result) {
        this.el.classList.remove('active');
        setTimeout(() => {
            this.el.classList.add('hidden');
            this.el.classList.remove('flex');
            if (this._resolve) {
                this._resolve(result);
                this._resolve = null;
            }
        }, 300);
    }
};

document.addEventListener('DOMContentLoaded', () => Dialog.init());
</script>
