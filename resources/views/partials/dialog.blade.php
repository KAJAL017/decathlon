{{-- ═══════════════════════════════════════════════════════════════
     PROFESSIONAL DIALOG SYSTEM
     • Smooth Backdrop Blur & Fade
     • Scale & Bounce Entrance
     • Fully Dynamic (Title, Message, Icons, Buttons)
     • Loading State, Keyboard ESC, Backdrop Click
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
    @keyframes dialogSpin { to { transform: rotate(360deg); } }
    .dialog-btn-spinner {
        display: inline-block;
        width: 14px;
        height: 14px;
        border: 2px solid rgba(255,255,255,0.3);
        border-top-color: #fff;
        border-radius: 50%;
        animation: dialogSpin 0.6s linear infinite;
        vertical-align: middle;
        margin-right: 6px;
    }
</style>

<script>
/**
 * Global Dialog Controller
 * - confirm(options) → Promise<boolean>
 * - alert(options)   → Promise<void>
 * - loading(options) → shows spinner, returns close() function
 * - Keyboard ESC to close
 * - Backdrop click to close
 */
window.Dialog = {
    _resolve: null,
    _keyHandler: null,
    
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

    confirm(options = {}) {
        return this.show({ ...options, mode: 'confirm' });
    },

    alert(options = {}) {
        return this.show({ ...options, mode: 'alert' });
    },

    /**
     * Show a loading dialog (no buttons, spinner in icon area)
     * Returns a close function: closeLoading()
     */
    loading(options = {}) {
        const { title = 'Processing...', message = 'Please wait' } = options;
        return this.show({ title, message, type: 'info', mode: 'loading' });
    },

    show(options = {}) {
        return new Promise((resolve) => {
            this._resolve = resolve;
            
            const {
                title = 'Notice',
                message = '',
                type = 'info',
                confirmText = 'OK',
                cancelText = 'Cancel',
                mode = 'confirm'
            } = options;

            this.title.innerText = title;
            this.message.innerText = message;
            this.confirmBtn.innerText = confirmText;
            this.cancelBtn.innerText = cancelText;

            // Reset button states
            this.confirmBtn.disabled = false;
            this.confirmBtn.innerHTML = confirmText;

            if (mode === 'alert' || mode === 'loading') {
                this.cancelBtn.classList.add('hidden');
            } else {
                this.cancelBtn.classList.remove('hidden');
            }

            if (mode === 'loading') {
                this.confirmBtn.classList.add('hidden');
                this.iconContainer.innerHTML = '<div class="dialog-btn-spinner" style="width:32px;height:32px;border-width:3px;"></div>';
            } else {
                this.confirmBtn.classList.remove('hidden');
                this.iconContainer.innerHTML = '<div id="dialog-icon"></div>';
                this.icon = document.getElementById('dialog-icon');
                this.applyType(type);
            }

            this.el.classList.remove('hidden');
            this.el.classList.add('flex');
            
            setTimeout(() => {
                this.el.classList.add('active');
            }, 10);

            // Keyboard ESC
            this._keyHandler = (e) => {
                if (e.key === 'Escape') {
                    e.preventDefault();
                    this.close(mode === 'alert' || mode === 'loading' ? false : false);
                }
            };
            document.addEventListener('keydown', this._keyHandler);
        });
    },

    /**
     * Show loading state on the confirm button (for async confirm dialogs)
     */
    setLoading(isLoading) {
        if (isLoading) {
            this.confirmBtn.disabled = true;
            this.confirmBtn._originalText = this.confirmBtn.innerText;
            this.confirmBtn.innerHTML = '<span class="dialog-btn-spinner"></span> Processing…';
        } else {
            this.confirmBtn.disabled = false;
            if (this.confirmBtn._originalText) {
                this.confirmBtn.innerText = this.confirmBtn._originalText;
                delete this.confirmBtn._originalText;
            }
        }
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
        
        this.iconContainer.className = `mx-auto w-16 h-16 rounded-full flex items-center justify-center mb-6 ${config.bg} ${config.text}`;
        this.icon.innerHTML = `<i data-lucide="${config.icon}" class="w-8 h-8"></i>`;
        if (typeof lucide !== 'undefined') lucide.createIcons({ nodes: [this.icon] });
        this.confirmBtn.className = `flex-1 px-6 py-3.5 rounded-2xl text-xs font-black uppercase tracking-widest text-white shadow-lg transition-all hover:scale-[1.02] active:scale-[0.98] ${config.btn}`;
    },

    close(result) {
        this.el.classList.remove('active');
        
        // Remove keyboard listener
        if (this._keyHandler) {
            document.removeEventListener('keydown', this._keyHandler);
            this._keyHandler = null;
        }

        setTimeout(() => {
            this.el.classList.add('hidden');
            this.el.classList.remove('flex');
            // Reset button states
            this.confirmBtn.classList.remove('hidden');
            this.confirmBtn.disabled = false;
            if (this._resolve) {
                this._resolve(result);
                this._resolve = null;
            }
        }, 300);
    }
};

document.addEventListener('DOMContentLoaded', () => Dialog.init());
</script>
