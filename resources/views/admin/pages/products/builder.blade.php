@extends('admin.layouts.app')

@section('title', 'Product Page Builder - ' . $product->name)

@push('styles')
<style>
    .builder-sidebar { height: calc(100vh - 140px); overflow-y: auto; }
    .builder-canvas { height: calc(100vh - 140px); overflow-y: auto; }
    .section-card { cursor: move; }
    .section-card.dragging { opacity: 0.5; border: 2px dashed #0082C3; }
</style>
@endpush

@section('content')
<div class="flex items-center justify-between mb-6">
    <div>
        <h1 class="text-2xl font-bold text-gray-900">Page Builder</h1>
        <p class="text-sm text-gray-600">Customize the layout for: <span class="font-semibold">{{ $product->name }}</span></p>
    </div>
    <div class="flex items-center gap-3">
        <a href="{{ route('admin.products.index') }}" class="px-4 py-2 bg-white border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 text-sm font-medium">
            Back to Products
        </a>
        <button onclick="saveLayout()" class="px-5 py-2 bg-[#0082C3] text-white rounded-lg hover:bg-[#006ba3] text-sm font-semibold shadow-sm flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
            Save Layout
        </button>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
    <!-- Sidebar: Available Blocks -->
    <div class="bg-white rounded-xl border border-gray-200 shadow-sm builder-sidebar p-4">
        <h3 class="font-bold text-gray-900 mb-4 uppercase tracking-wider text-xs">Available Sections</h3>
        <p class="text-xs text-gray-500 mb-4">Click to add a section to the product page.</p>
        
        <div class="space-y-2" id="available-blocks">
            <button onclick="addSection('features')" class="w-full flex items-center gap-3 p-3 border border-gray-200 rounded-lg hover:border-[#0082C3] hover:bg-blue-50 transition-colors text-left group">
                <div class="bg-gray-100 p-2 rounded group-hover:bg-blue-100 group-hover:text-[#0082C3]">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                </div>
                <div>
                    <div class="font-semibold text-sm text-gray-900">Features</div>
                    <div class="text-[10px] text-gray-500">Key product features grid</div>
                </div>
            </button>

            <button onclick="addSection('specifications')" class="w-full flex items-center gap-3 p-3 border border-gray-200 rounded-lg hover:border-[#0082C3] hover:bg-blue-50 transition-colors text-left group">
                <div class="bg-gray-100 p-2 rounded group-hover:bg-blue-100 group-hover:text-[#0082C3]">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path></svg>
                </div>
                <div>
                    <div class="font-semibold text-sm text-gray-900">Specifications</div>
                    <div class="text-[10px] text-gray-500">Detailed technical specs table</div>
                </div>
            </button>

            <button onclick="addSection('banner')" class="w-full flex items-center gap-3 p-3 border border-gray-200 rounded-lg hover:border-[#0082C3] hover:bg-blue-50 transition-colors text-left group">
                <div class="bg-gray-100 p-2 rounded group-hover:bg-blue-100 group-hover:text-[#0082C3]">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                </div>
                <div>
                    <div class="font-semibold text-sm text-gray-900">Image Banner</div>
                    <div class="text-[10px] text-gray-500">Full-width promotional banner</div>
                </div>
            </button>

            <button onclick="addSection('custom_html')" class="w-full flex items-center gap-3 p-3 border border-gray-200 rounded-lg hover:border-[#0082C3] hover:bg-blue-50 transition-colors text-left group">
                <div class="bg-gray-100 p-2 rounded group-hover:bg-blue-100 group-hover:text-[#0082C3]">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"></path></svg>
                </div>
                <div>
                    <div class="font-semibold text-sm text-gray-900">Custom HTML</div>
                    <div class="text-[10px] text-gray-500">Free-form code or text block</div>
                </div>
            </button>

            <button onclick="addSection('downloads')" class="w-full flex items-center gap-3 p-3 border border-gray-200 rounded-lg hover:border-[#0082C3] hover:bg-blue-50 transition-colors text-left group">
                <div class="bg-gray-100 p-2 rounded group-hover:bg-blue-100 group-hover:text-[#0082C3]">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                </div>
                <div>
                    <div class="font-semibold text-sm text-gray-900">Downloads</div>
                    <div class="text-[10px] text-gray-500">Manuals and datasheets</div>
                </div>
            </button>
        </div>
    </div>

    <!-- Canvas: Active Layout -->
    <div class="lg:col-span-3 bg-gray-100 rounded-xl border border-gray-200 shadow-inner builder-canvas p-6 flex flex-col items-center">
        
        <!-- Header Info -->
        <div class="w-full max-w-3xl bg-white border border-gray-300 rounded-lg p-4 mb-6 opacity-75 pointer-events-none">
            <h2 class="text-lg font-bold">Product Header (Fixed)</h2>
            <p class="text-sm text-gray-500">Images, Title, Price, Variants, Add to Cart</p>
        </div>

        <!-- Dynamic Sections Container -->
        <div id="canvas-sections" class="w-full max-w-3xl space-y-4">
            <!-- Sections will be injected here via JS -->
        </div>

        <!-- Empty State -->
        <div id="empty-state" class="w-full max-w-3xl border-2 border-dashed border-gray-300 rounded-xl p-12 text-center mt-4">
            <svg class="w-12 h-12 text-gray-400 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
            <h3 class="text-lg font-semibold text-gray-900">No sections added</h3>
            <p class="text-gray-500 text-sm mt-1">Select blocks from the left sidebar to build your layout.</p>
        </div>
    </div>
</div>

<!-- Edit Section Modal -->
<div id="edit-section-modal" class="fixed inset-0 bg-gray-900/50 hidden z-50 flex items-center justify-center">
    <div class="bg-white rounded-xl shadow-xl w-full max-w-2xl max-h-[90vh] overflow-hidden flex flex-col">
        <div class="p-5 border-b border-gray-200 flex items-center justify-between">
            <h3 class="text-lg font-bold text-gray-900" id="edit-modal-title">Edit Section</h3>
            <button onclick="closeEditModal()" class="text-gray-400 hover:text-gray-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>
        
        <div class="p-6 overflow-y-auto flex-1" id="edit-modal-body">
            <!-- Dynamic Form Fields -->
        </div>

        <div class="p-5 border-t border-gray-200 flex justify-end gap-3 bg-gray-50">
            <button onclick="closeEditModal()" class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 font-medium hover:bg-gray-100">Cancel</button>
            <button onclick="saveActiveSection()" class="px-4 py-2 bg-[#0082C3] text-white rounded-lg font-medium hover:bg-[#006ba3]">Apply Changes</button>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    const productId = {{ $product->id }};
    let sections = [];
    let editingIndex = null;

    // Fetch existing layout
    document.addEventListener('DOMContentLoaded', () => {
        fetch(`/admin/products/${productId}/sections`)
            .then(res => res.json())
            .then(data => {
                if(data.success && data.sections.length > 0) {
                    sections = data.sections;
                    renderCanvas();
                }
            })
            .catch(err => console.error(err));
    });

    function addSection(type) {
        const newSection = {
            id: 'temp_' + Date.now(),
            type: type,
            title: `New ${type.replace('_', ' ')}`,
            subtitle: '',
            content: {},
            settings: { bg_color: '#ffffff' },
            is_active: true
        };
        sections.push(newSection);
        renderCanvas();
        openEditModal(sections.length - 1);
    }

    function removeSection(index) {
        if(confirm('Are you sure you want to remove this section?')) {
            sections.splice(index, 1);
            renderCanvas();
        }
    }

    function moveSection(index, direction) {
        if(direction === 'up' && index > 0) {
            const temp = sections[index];
            sections[index] = sections[index - 1];
            sections[index - 1] = temp;
        } else if (direction === 'down' && index < sections.length - 1) {
            const temp = sections[index];
            sections[index] = sections[index + 1];
            sections[index + 1] = temp;
        }
        renderCanvas();
    }

    function toggleSection(index) {
        sections[index].is_active = !sections[index].is_active;
        renderCanvas();
    }

    function renderCanvas() {
        const container = document.getElementById('canvas-sections');
        const emptyState = document.getElementById('empty-state');
        
        container.innerHTML = '';
        
        if (sections.length === 0) {
            emptyState.style.display = 'block';
            return;
        }
        
        emptyState.style.display = 'none';

        sections.forEach((sec, index) => {
            const el = document.createElement('div');
            el.className = `section-card bg-white border ${sec.is_active ? 'border-gray-200' : 'border-gray-300 opacity-60'} rounded-xl shadow-sm p-4 relative transition-all`;
            el.innerHTML = `
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-4">
                        <div class="flex flex-col gap-1 text-gray-400">
                            <button onclick="moveSection(${index}, 'up')" class="hover:text-[#0082C3] disabled:opacity-30" ${index === 0 ? 'disabled' : ''}>
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"></path></svg>
                            </button>
                            <button onclick="moveSection(${index}, 'down')" class="hover:text-[#0082C3] disabled:opacity-30" ${index === sections.length - 1 ? 'disabled' : ''}>
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                            </button>
                        </div>
                        <div>
                            <span class="text-[10px] font-bold uppercase tracking-wider text-[#0082C3] bg-blue-50 px-2 py-0.5 rounded">${sec.type.replace('_', ' ')}</span>
                            <h4 class="font-bold text-gray-900 mt-1 text-lg">${sec.title || 'Untitled Section'}</h4>
                        </div>
                    </div>
                    <div class="flex items-center gap-2">
                        <button onclick="toggleSection(${index})" class="p-2 text-gray-500 hover:bg-gray-100 rounded" title="${sec.is_active ? 'Disable' : 'Enable'}">
                            ${sec.is_active 
                                ? '<svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>'
                                : '<svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.29 3.29m0 0a9.953 9.953 0 015.71-2.29c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0l-3.29-3.29"></path></svg>'
                            }
                        </button>
                        <button onclick="openEditModal(${index})" class="p-2 text-blue-600 hover:bg-blue-50 rounded" title="Edit">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                        </button>
                        <button onclick="removeSection(${index})" class="p-2 text-red-600 hover:bg-red-50 rounded" title="Delete">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                        </button>
                    </div>
                </div>
            `;
            container.appendChild(el);
        });
    }

    function openEditModal(index) {
        editingIndex = index;
        const sec = sections[index];
        document.getElementById('edit-modal-title').innerText = `Edit ${sec.type.replace('_', ' ')}`;
        
        let html = `
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Section Title</label>
                    <input type="text" id="edit-title" value="${sec.title || ''}" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-[#0082C3] focus:outline-none">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Section Subtitle (Optional)</label>
                    <input type="text" id="edit-subtitle" value="${sec.subtitle || ''}" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-[#0082C3] focus:outline-none">
                </div>
        `;

        if (sec.type === 'custom_html') {
            html += `
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">HTML Content</label>
                    <textarea id="edit-content-html" rows="6" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm font-mono focus:ring-2 focus:ring-[#0082C3] focus:outline-none">${sec.content.html || ''}</textarea>
                </div>
            `;
        } else if (sec.type === 'banner') {
            html += `
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Image URL</label>
                    <input type="text" id="edit-content-image" value="${sec.content.image_url || ''}" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-[#0082C3] focus:outline-none">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Link URL</label>
                    <input type="text" id="edit-content-link" value="${sec.content.link_url || ''}" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-[#0082C3] focus:outline-none">
                </div>
            `;
        }

        html += `</div>`;
        document.getElementById('edit-modal-body').innerHTML = html;
        document.getElementById('edit-section-modal').classList.remove('hidden');
    }

    function closeEditModal() {
        document.getElementById('edit-section-modal').classList.add('hidden');
        editingIndex = null;
    }

    function saveActiveSection() {
        if (editingIndex === null) return;
        
        const sec = sections[editingIndex];
        sec.title = document.getElementById('edit-title').value;
        sec.subtitle = document.getElementById('edit-subtitle').value;

        if (sec.type === 'custom_html') {
            sec.content.html = document.getElementById('edit-content-html').value;
        } else if (sec.type === 'banner') {
            sec.content.image_url = document.getElementById('edit-content-image').value;
            sec.content.link_url = document.getElementById('edit-content-link').value;
        }

        renderCanvas();
        closeEditModal();
    }

    function saveLayout() {
        fetch(`/admin/products/${productId}/sections`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({ sections: sections })
        })
        .then(res => res.json())
        .then(data => {
            if(data.success) {
                Toastify({
                    text: "Layout saved successfully",
                    duration: 3000,
                    style: { background: "linear-gradient(to right, #00b09b, #96c93d)" }
                }).showToast();
            } else {
                alert('Error saving layout.');
            }
        })
        .catch(err => {
            console.error(err);
            alert('Server error.');
        });
    }
</script>
@endpush