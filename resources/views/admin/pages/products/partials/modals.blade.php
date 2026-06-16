<!-- Product Selector Modal -->
<div id="productSelectorModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-[60] flex items-center justify-center p-4">
    <div class="bg-white rounded-xl shadow-2xl w-full max-w-3xl max-h-[80vh] flex flex-col">
        <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
            <h3 class="text-lg font-semibold text-gray-900" id="selectorModalTitle">Select Products</h3>
            <button type="button" onclick="closeProductSelector()" class="text-gray-400 hover:text-gray-600 transition-colors">
                <i data-lucide="x" class="w-5 h-5"></i>
            </button>
        </div>
        <div class="px-6 py-3 border-b border-gray-200">
            <input type="text" id="productSelectorSearch" placeholder="Search products..." class="w-full px-3.5 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#0082C3]" onkeyup="searchProductsForSelector()">
        </div>
        <div class="flex-1 overflow-y-auto px-6 py-4">
            <div id="productSelectorList" class="space-y-2"></div>
        </div>
        <div class="px-6 py-4 border-t border-gray-200 flex justify-end gap-2">
            <button type="button" onclick="closeProductSelector()" class="px-4 py-2 border border-gray-300 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-50">Cancel</button>
            <button type="button" onclick="addSelectedProducts()" class="px-4 py-2 bg-[#0082C3] text-white text-sm font-medium rounded-lg hover:bg-[#006ba3]">Add Selected</button>
        </div>
    </div>
</div>

<!-- Add Attribute Modal -->
<div id="addAttributeModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-[200] flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md">
        <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100">
            <h3 class="text-base font-semibold text-gray-900">Add Product Attribute</h3>
            <button type="button" onclick="closeAddAttributeModal()" class="text-gray-400 hover:text-gray-600"><i data-lucide="x" class="w-5 h-5"></i></button>
        </div>
        <div class="px-6 py-5 space-y-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">Attribute Group *</label>
                <select id="attrModalGroup" onchange="onAttrGroupChange(this.value)" class="w-full px-3.5 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-green-500"></select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">Attribute *</label>
                <select id="attrModalAttribute" onchange="onAttrAttributeChange(this.value)" class="w-full px-3.5 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-green-500"></select>
            </div>
            <div id="attrModalValueRow" class="hidden">
                <label class="block text-sm font-medium text-gray-700 mb-1.5">Value *</label>
                <select id="attrModalValue" class="w-full px-3.5 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-green-500"></select>
            </div>
            <div id="attrModalCustomRow" class="hidden">
                <label class="block text-sm font-medium text-gray-700 mb-1.5">Value *</label>
                <input type="text" id="attrModalCustomValue" placeholder="e.g. Cotton..." class="w-full px-3.5 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-green-500">
            </div>
        </div>
        <div class="flex items-center justify-end gap-3 px-6 py-4 border-t border-gray-100 bg-gray-50 rounded-b-2xl">
            <button type="button" onclick="closeAddAttributeModal()" class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50">Cancel</button>
            <button type="button" onclick="saveAttributeToProduct()" class="px-4 py-2 text-sm font-medium text-white bg-green-600 rounded-lg hover:bg-green-700">Add Attribute</button>
        </div>
    </div>
</div>

<!-- Import Modal -->
<div id="importModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-xl shadow-2xl w-full max-w-2xl max-h-[90vh] overflow-y-auto">
        <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
            <h3 class="text-lg font-semibold text-gray-900">Import Products</h3>
            <button onclick="closeImportModal()" class="text-gray-400 hover:text-gray-600"><i data-lucide="x" class="w-5 h-5"></i></button>
        </div>
        <form id="importForm" class="p-6 space-y-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">CSV File</label>
                <input type="file" name="file" accept=".csv,.txt" required class="w-full px-3 py-2 border border-gray-300 rounded-lg">
            </div>
            <div class="flex items-center gap-4">
                <label class="flex items-center gap-2"><input type="checkbox" name="update_existing" value="1" class="rounded"><span class="text-sm text-gray-700">Update existing</span></label>
                <label class="flex items-center gap-2"><input type="checkbox" name="update_by_sku" value="1" class="rounded"><span class="text-sm text-gray-700">Match by SKU</span></label>
            </div>
        </form>
        <div class="px-6 py-4 border-t border-gray-200 flex justify-end gap-2">
            <button onclick="closeImportModal()" class="px-4 py-2 border border-gray-300 text-gray-700 rounded-lg">Cancel</button>
            <button onclick="startImport()" class="px-4 py-2 bg-blue-600 text-white rounded-lg">Start Import</button>
        </div>
    </div>
</div>

<!-- Export Modal -->
<div id="exportModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-xl shadow-2xl w-full max-w-2xl">
        <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
            <h3 class="text-lg font-semibold text-gray-900">Export Products</h3>
            <button onclick="closeExportModal()" class="text-gray-400 hover:text-gray-600"><i data-lucide="x" class="w-5 h-5"></i></button>
        </div>
        <form id="exportForm" class="p-6 space-y-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Format</label>
                <select name="format" class="w-full px-3 py-2 border border-gray-300 rounded-lg"><option value="csv">CSV</option><option value="excel">Excel</option></select>
            </div>
        </form>
        <div class="px-6 py-4 border-t border-gray-200 flex justify-end gap-2">
            <button onclick="closeExportModal()" class="px-4 py-2 border border-gray-300 text-gray-700 rounded-lg">Cancel</button>
            <button onclick="startExport()" class="px-4 py-2 bg-green-600 text-white rounded-lg">Start Export</button>
        </div>
    </div>
</div>

<!-- Variant Generator Modal -->
<div id="variantGeneratorModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-[200] flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-2xl flex flex-col max-h-[85vh]">
        <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100 bg-gray-50/50 rounded-t-2xl">
            <div>
                <h3 class="text-base font-bold text-gray-900 uppercase tracking-tight">Variant Generator</h3>
                <p class="text-[10px] text-gray-500 font-medium uppercase tracking-widest mt-0.5">Select attributes to generate combinations</p>
            </div>
            <button type="button" onclick="closeVariantGenerator()" class="w-8 h-8 flex items-center justify-center rounded-lg hover:bg-gray-200 text-gray-400 hover:text-gray-600 transition-colors">
                <i data-lucide="x" class="w-5 h-5"></i>
            </button>
        </div>
        
        <div class="flex-1 overflow-y-auto p-6">
            <div id="variantAttributesContainer" class="grid grid-cols-1 gap-4">
                <!-- Attributes will be loaded here -->
                <div class="flex flex-col items-center justify-center py-12 text-gray-400">
                    <i data-lucide="loader" class="animate-spin h-8 w-8 mb-3"></i>
                    <p class="text-sm font-medium">Loading attributes...</p>
                </div>
            </div>
        </div>

        <div class="px-6 py-4 border-t border-gray-100 bg-gray-50 rounded-b-2xl flex items-center justify-between">
            <p class="text-xs text-gray-500 font-medium">
                Tip: Select multiple values for each attribute.
            </p>
            <div class="flex items-center gap-3">
                <button type="button" onclick="closeVariantGenerator()" class="px-4 py-2 text-sm font-bold text-gray-600 hover:bg-gray-200 rounded-xl transition-colors">Cancel</button>
                <button type="button" onclick="generateVariants()" class="px-6 py-2.5 bg-[#0082C3] text-white text-sm font-black uppercase tracking-widest rounded-xl hover:bg-[#006ba3] transition-all shadow-md">
                    Generate Matrix
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Jobs Modal -->
<div id="jobsModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-xl shadow-2xl w-full max-w-4xl max-h-[90vh] overflow-y-auto">
        <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
            <h3 class="text-lg font-semibold text-gray-900">Import/Export Jobs</h3>
            <button onclick="closeJobsModal()" class="text-gray-400 hover:text-gray-600"><i data-lucide="x" class="w-5 h-5"></i></button>
        </div>
        <div class="p-6"><div id="jobsList" class="space-y-4"></div></div>
    </div>
</div>
