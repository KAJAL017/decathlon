@extends('admin.layouts.app')

@section('title', 'Image & Media Settings')

@section('content')
<div class="space-y-8 pb-20">
    <!-- Header Section -->
    <div>
        <h1 class="text-3xl font-bold text-gray-900 tracking-tight">Image & Media Settings</h1>
        <p class="text-sm text-gray-600 mt-2">Centralized control for image optimization, conversion, and storage across the platform.</p>
    </div>

    <!-- Global Settings Card -->
    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
        <div class="px-8 py-5 border-b border-gray-100 bg-gray-50 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-blue-100 flex items-center justify-center text-[#0082C3]">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"></path></svg>
                </div>
                <h2 class="text-lg font-bold text-gray-900 uppercase tracking-tight">Global Media Configuration</h2>
            </div>
            <button form="globalSettingsForm" class="px-6 py-2.5 bg-[#0082C3] text-white text-xs font-bold uppercase tracking-widest rounded-xl hover:bg-[#006ba3] transition-all">Save Global Changes</button>
        </div>
        
        <form id="globalSettingsForm" onsubmit="saveGlobalSettings(event)" class="p-8 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @csrf
            <div>
                <label class="block text-xs font-black text-gray-500 uppercase tracking-widest mb-3">Maximum Upload Size (KB)</label>
                <input type="number" name="max_upload_size" value="{{ $globalSettings['max_upload_size'] }}" class="w-full px-5 py-3.5 bg-gray-50 border border-gray-200 rounded-xl text-sm font-bold focus:ring-2 focus:ring-[#0082C3] transition-all">
                <p class="text-[10px] text-gray-400 mt-2 font-medium">Files larger than this will be rejected.</p>
            </div>

            <div>
                <label class="block text-xs font-black text-gray-500 uppercase tracking-widest mb-3">Allowed Extensions</label>
                <input type="text" name="allowed_extensions" value="{{ $globalSettings['allowed_extensions'] }}" class="w-full px-5 py-3.5 bg-gray-50 border border-gray-200 rounded-xl text-sm font-bold focus:ring-2 focus:ring-[#0082C3] transition-all">
                <p class="text-[10px] text-gray-400 mt-2 font-medium">Comma separated: jpg,png,webp,jpeg</p>
            </div>

            <div class="space-y-4">
                <label class="flex items-center gap-3 cursor-pointer group">
                    <input type="checkbox" name="auto_delete_old" {{ ($globalSettings['auto_delete_old'] ?? false) ? 'checked' : '' }} class="w-5 h-5 rounded border-gray-300 text-[#0082C3] focus:ring-[#0082C3]">
                    <span class="text-xs font-bold text-gray-700 uppercase tracking-tight group-hover:text-gray-900 transition-colors">Auto Delete Old Files</span>
                </label>
                <label class="flex items-center gap-3 cursor-pointer group">
                    <input type="checkbox" name="unique_filenames" {{ ($globalSettings['unique_filenames'] ?? false) ? 'checked' : '' }} class="w-5 h-5 rounded border-gray-300 text-[#0082C3] focus:ring-[#0082C3]">
                    <span class="text-xs font-bold text-gray-700 uppercase tracking-tight group-hover:text-gray-900 transition-colors">Generate Unique Filenames</span>
                </label>
                <label class="flex items-center gap-3 cursor-pointer group">
                    <input type="checkbox" name="strip_metadata" {{ ($globalSettings['strip_metadata'] ?? false) ? 'checked' : '' }} class="w-5 h-5 rounded border-gray-300 text-[#0082C3] focus:ring-[#0082C3]">
                    <span class="text-xs font-bold text-gray-700 uppercase tracking-tight group-hover:text-gray-900 transition-colors">Strip Metadata (EXIF)</span>
                </label>
            </div>
        </form>
    </div>

    <!-- Image Types Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
        @foreach($types as $type)
        <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden flex flex-col hover:border-[#0082C3]/30 transition-all group">
            <div class="px-6 py-4 border-b border-gray-50 bg-gray-50/50 flex items-center justify-between">
                <h3 class="text-xs font-black text-gray-900 uppercase tracking-[0.2em]">{{ str_replace('_', ' ', $type->image_type) }}</h3>
                <div class="flex items-center gap-2">
                    <button onclick="resetToDefault({{ $type->id }})" class="text-[10px] font-bold text-gray-400 hover:text-red-500 uppercase tracking-widest transition-colors">Reset</button>
                </div>
            </div>

            <form id="form-type-{{ $type->id }}" onsubmit="saveTypeSettings(event, {{ $type->id }})" class="p-6 flex-1 space-y-6">
                @csrf
                @method('PUT')
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2">Max Width</label>
                        <input type="number" name="max_width" value="{{ $type->max_width }}" class="w-full px-4 py-3 bg-gray-50 border border-gray-100 rounded-xl text-sm font-bold focus:ring-2 focus:ring-[#0082C3] transition-all">
                    </div>
                    <div>
                        <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2">Max Height</label>
                        <input type="number" name="max_height" value="{{ $type->max_height }}" class="w-full px-4 py-3 bg-gray-50 border border-gray-100 rounded-xl text-sm font-bold focus:ring-2 focus:ring-[#0082C3] transition-all">
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2">Quality (1-100)</label>
                        <input type="number" name="quality" value="{{ $type->quality }}" class="w-full px-4 py-3 bg-gray-50 border border-gray-100 rounded-xl text-sm font-bold focus:ring-2 focus:ring-[#0082C3] transition-all">
                    </div>
                    <div>
                        <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2">Format</label>
                        <select name="format" class="w-full px-4 py-3 bg-gray-50 border border-gray-100 rounded-xl text-sm font-bold focus:ring-2 focus:ring-[#0082C3] transition-all">
                            <option value="webp" {{ $type->format === 'webp' ? 'selected' : '' }}>WebP (Best)</option>
                            <option value="jpg" {{ $type->format === 'jpg' ? 'selected' : '' }}>JPG</option>
                            <option value="png" {{ $type->format === 'png' ? 'selected' : '' }}>PNG</option>
                            <option value="original" {{ $type->format === 'original' ? 'selected' : '' }}>Original</option>
                        </select>
                    </div>
                </div>

                <div class="space-y-3 pt-2">
                    <label class="flex items-center gap-3 cursor-pointer group">
                        <input type="checkbox" name="auto_optimize" {{ $type->auto_optimize ? 'checked' : '' }} class="w-4 h-4 rounded border-gray-300 text-[#0082C3] focus:ring-[#0082C3]">
                        <span class="text-[11px] font-bold text-gray-600 uppercase tracking-tight group-hover:text-gray-900 transition-colors">Auto Optimize Uploads</span>
                    </label>
                    <label class="flex items-center gap-3 cursor-pointer group">
                        <input type="checkbox" name="keep_aspect_ratio" {{ $type->keep_aspect_ratio ? 'checked' : '' }} class="w-4 h-4 rounded border-gray-300 text-[#0082C3] focus:ring-[#0082C3]">
                        <span class="text-[11px] font-bold text-gray-600 uppercase tracking-tight group-hover:text-gray-900 transition-colors">Keep Aspect Ratio</span>
                    </label>
                    <label class="flex items-center gap-3 cursor-pointer group">
                        <input type="checkbox" name="generate_thumbnail" onchange="toggleThumb(this, {{ $type->id }})" {{ $type->generate_thumbnail ? 'checked' : '' }} class="w-4 h-4 rounded border-gray-300 text-[#0082C3] focus:ring-[#0082C3]">
                        <span class="text-[11px] font-bold text-gray-600 uppercase tracking-tight group-hover:text-gray-900 transition-colors">Generate Thumbnail</span>
                    </label>
                </div>

                <div id="thumb-fields-{{ $type->id }}" class="{{ $type->generate_thumbnail ? '' : 'hidden' }} grid grid-cols-2 gap-4 pt-4 border-t border-gray-50">
                    <div>
                        <label class="block text-[9px] font-black text-gray-400 uppercase tracking-widest mb-2">Thumb Width</label>
                        <input type="number" name="thumbnail_width" value="{{ $type->thumbnail_width ?? 150 }}" class="w-full px-4 py-3 bg-gray-50 border border-gray-100 rounded-xl text-xs font-bold">
                    </div>
                    <div>
                        <label class="block text-[9px] font-black text-gray-400 uppercase tracking-widest mb-2">Thumb Height</label>
                        <input type="number" name="thumbnail_height" value="{{ $type->thumbnail_height ?? 150 }}" class="w-full px-4 py-3 bg-gray-50 border border-gray-100 rounded-xl text-xs font-bold">
                    </div>
                </div>

                <div class="pt-6 mt-auto border-t border-gray-50">
                    <button type="submit" class="w-full py-3.5 bg-gray-950 text-white text-[10px] font-black uppercase tracking-[0.2em] rounded-xl hover:bg-gray-800 hover:shadow-lg hover:shadow-gray-100 active:scale-[0.98] transition-all">Save {{ $type->image_type }} Settings</button>
                </div>
            </form>
        </div>
        @endforeach
    </div>
</div>

@push('scripts')
<script>
window.Toast = {
    show: function(msg, type = 'success') {
        const toast = document.createElement('div');
        toast.className = `fixed bottom-4 right-4 px-6 py-3 rounded-lg shadow-lg text-white text-sm font-medium z-[9999] transition-all transform translate-y-0 ${type === 'success' ? 'bg-green-600' : 'bg-red-600'}`;
        toast.textContent = msg;
        document.body.appendChild(toast);
        setTimeout(() => {
            toast.style.transform = 'translateY(100px)';
            toast.style.opacity = '0';
            setTimeout(() => toast.remove(), 300);
        }, 3000);
    },
    success: function(msg) { this.show(msg, 'success'); },
    error: function(msg) { this.show(msg, 'error'); }
};

function toggleThumb(checkbox, id) {
    const fields = document.getElementById(`thumb-fields-${id}`);
    if (checkbox.checked) {
        fields.classList.remove('hidden');
    } else {
        fields.classList.add('hidden');
    }
}

async function saveGlobalSettings(e) {
    e.preventDefault();
    const form = e.target;
    const formData = new FormData(form);
    
    // Manual handling for checkboxes if needed, though FormData usually handles them
    // but Laravel validate expects boolean sometimes
    
    try {
        const response = await fetch("{{ route('admin.settings.media.global') }}", {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        });
        const data = await response.json();
        if (data.success) {
            Toast.success(data.message);
        }
    } catch (error) {
        console.error(error);
        Toast.error('Failed to save settings');
    }
}

async function saveTypeSettings(e, id) {
    e.preventDefault();
    const form = e.target;
    const formData = new FormData(form);
    
    try {
        const response = await fetch(`/admin/settings/media/${id}`, {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        });
        const data = await response.json();
        if (data.success) {
            Toast.success(data.message);
        }
    } catch (error) {
        console.error(error);
        Toast.error('Failed to save settings');
    }
}

async function resetToDefault(id) {
    if (!confirm('Are you sure you want to reset settings for this type?')) return;
    
    try {
        const response = await fetch(`/admin/settings/media/${id}/reset`, {
            method: 'POST',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        });
        const data = await response.json();
        if (data.success) {
            Toast.success(data.message);
            // Reload page to show new values or update via JS
            window.location.reload();
        }
    } catch (error) {
        console.error(error);
        Toast.error('Failed to reset settings');
    }
}
</script>
@endpush
@endsection
