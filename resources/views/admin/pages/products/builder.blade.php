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
            <i data-lucide="check" class="w-4 h-4"></i>
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
                    <i data-lucide="check" class="w-5 h-5"></i>
                </div>
                <div>
                    <div class="font-semibold text-sm text-gray-900">Features</div>
                    <div class="text-[10px] text-gray-500">Key product features grid</div>
                </div>
            </button>

            <button onclick="addSection('specifications')" class="w-full flex items-center gap-3 p-3 border border-gray-200 rounded-lg hover:border-[#0082C3] hover:bg-blue-50 transition-colors text-left group">
                <div class="bg-gray-100 p-2 rounded group-hover:bg-blue-100 group-hover:text-[#0082C3]">
                    <i data-lucide="clipboard" class="w-5 h-5"></i>
                </div>
                <div>
                    <div class="font-semibold text-sm text-gray-900">Specifications</div>
                    <div class="text-[10px] text-gray-500">Detailed technical specs table</div>
                </div>
            </button>

            <button onclick="addSection('banner')" class="w-full flex items-center gap-3 p-3 border border-gray-200 rounded-lg hover:border-[#0082C3] hover:bg-blue-50 transition-colors text-left group">
                <div class="bg-gray-100 p-2 rounded group-hover:bg-blue-100 group-hover:text-[#0082C3]">
                    <i data-lucide="image" class="w-5 h-5"></i>
                </div>
                <div>
                    <div class="font-semibold text-sm text-gray-900">Image Banner</div>
                    <div class="text-[10px] text-gray-500">Full-width promotional banner</div>
                </div>
            </button>

            <button onclick="addSection('custom_html')" class="w-full flex items-center gap-3 p-3 border border-gray-200 rounded-lg hover:border-[#0082C3] hover:bg-blue-50 transition-colors text-left group">
                <div class="bg-gray-100 p-2 rounded group-hover:bg-blue-100 group-hover:text-[#0082C3]">
                    <i data-lucide="code" class="w-5 h-5"></i>
                </div>
                <div>
                    <div class="font-semibold text-sm text-gray-900">Custom HTML</div>
                    <div class="text-[10px] text-gray-500">Free-form code or text block</div>
                </div>
            </button>

            <button onclick="addSection('downloads')" class="w-full flex items-center gap-3 p-3 border border-gray-200 rounded-lg hover:border-[#0082C3] hover:bg-blue-50 transition-colors text-left group">
                <div class="bg-gray-100 p-2 rounded group-hover:bg-blue-100 group-hover:text-[#0082C3]">
                    <i data-lucide="download" class="w-5 h-5"></i>
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
            <i data-lucide="package" class="w-12 h-12 text-gray-400 mx-auto mb-3"></i>
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
                <i data-lucide="x" class="w-6 h-6"></i>
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
                                <i data-lucide="chevron-up" class="w-4 h-4"></i>
                            </button>
                            <button onclick="moveSection(${index}, 'down')" class="hover:text-[#0082C3] disabled:opacity-30" ${index === sections.length - 1 ? 'disabled' : ''}>
                                <i data-lucide="chevron-down" class="w-4 h-4"></i>
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
                                ? '<i data-lucide="eye" class="w-5 h-5 text-green-600"></i>'
                                : '<i data-lucide="eye-off" class="w-5 h-5 text-gray-400"></i>'
                            }
                        </button>
                        <button onclick="openEditModal(${index})" class="p-2 text-blue-600 hover:bg-blue-50 rounded" title="Edit">
                            <i data-lucide="pencil" class="w-5 h-5"></i>
                        </button>
                        <button onclick="removeSection(${index})" class="p-2 text-red-600 hover:bg-red-50 rounded" title="Delete">
                            <i data-lucide="trash-2" class="w-5 h-5"></i>
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