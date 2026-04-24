/**
 * Summernote Helper - jQuery-based Rich Text Editor
 * 100% Free, No API Key Required, Lightweight
 */

// Store editor instances
const summernoteInstances = {};

/**
 * Initialize Summernote on a textarea
 * @param {string} textareaId - ID of the textarea element
 * @param {string} type - 'simple' or 'full' configuration
 */
function initSummernote(textareaId, type = 'full') {
    const $textarea = $('#' + textareaId);
    if ($textarea.length === 0) {
        console.error(`Textarea with ID "${textareaId}" not found`);
        return;
    }

    // Destroy existing instance if any
    if (summernoteInstances[textareaId]) {
        $textarea.summernote('destroy');
    }

    const config = type === 'simple' ? getSimpleConfig() : getFullConfig();
    
    $textarea.summernote(config);
    summernoteInstances[textareaId] = true;
}

function getSimpleConfig() {
    return {
        height: 120,
        minHeight: 80,
        maxHeight: 150,
        placeholder: 'Enter short description...',
        toolbar: [
            ['style', ['bold', 'italic', 'underline']],
            ['para', ['ul', 'ol']],
            ['insert', ['link']],
            ['view', ['undo', 'redo']]
        ],
        disableDragAndDrop: true,
        dialogsInBody: true,
        callbacks: {
            onInit: function() {
                // Custom styling for simple editor
                $(this).next('.note-editor').css({
                    'border': '1px solid #d1d5db',
                    'border-radius': '0.5rem',
                    'overflow': 'hidden'
                });
            }
        }
    };
}

function getFullConfig() {
    return {
        height: 300,
        minHeight: 200,
        maxHeight: 500,
        placeholder: 'Enter detailed description...',
        toolbar: [
            ['style', ['style']],
            ['font', ['bold', 'italic', 'underline', 'strikethrough', 'clear']],
            ['color', ['color']],
            ['para', ['ul', 'ol', 'paragraph']],
            ['table', ['table']],
            ['insert', ['link']],
            ['view', ['fullscreen', 'codeview', 'undo', 'redo']]
        ],
        styleTags: ['p', 'h1', 'h2', 'h3', 'h4', 'h5', 'h6'],
        disableDragAndDrop: true,
        dialogsInBody: true,
        callbacks: {
            onInit: function() {
                // Custom styling for full editor
                $(this).next('.note-editor').css({
                    'border': '1px solid #d1d5db',
                    'border-radius': '0.5rem',
                    'overflow': 'hidden'
                });
            }
        }
    };
}

/**
 * Get content from Summernote
 * @param {string} textareaId - ID of the textarea element
 * @returns {string} - HTML content from editor
 */
function getSummernoteContent(textareaId) {
    const $textarea = $('#' + textareaId);
    if ($textarea.length > 0 && summernoteInstances[textareaId]) {
        return $textarea.summernote('code');
    }
    // Fallback to textarea value
    return $textarea.val() || '';
}

/**
 * Set content in Summernote
 * @param {string} textareaId - ID of the textarea element
 * @param {string} content - HTML content to set
 */
function setSummernoteContent(textareaId, content) {
    const $textarea = $('#' + textareaId);
    if ($textarea.length > 0 && summernoteInstances[textareaId]) {
        $textarea.summernote('code', content || '');
    } else {
        // Fallback to textarea
        $textarea.val(content || '');
    }
}

/**
 * Clear content in Summernote
 * @param {string} textareaId - ID of the textarea element
 */
function clearSummernoteContent(textareaId) {
    setSummernoteContent(textareaId, '');
}

/**
 * Remove Summernote instance
 * @param {string} textareaId - ID of the textarea element
 */
function removeSummernote(textareaId) {
    const $textarea = $('#' + textareaId);
    if ($textarea.length > 0 && summernoteInstances[textareaId]) {
        $textarea.summernote('destroy');
        delete summernoteInstances[textareaId];
    }
}

/**
 * Auto-initialize Summernote on textareas with data-editor attribute
 */
$(document).ready(function() {
    $('textarea[data-editor]').each(function() {
        const textareaId = $(this).attr('id');
        const type = $(this).data('editor') || 'full';
        if (textareaId) {
            initSummernote(textareaId, type);
        }
    });
});

// Add custom CSS for better Summernote styling
const style = document.createElement('style');
style.textContent = `
    .note-editor {
        border: 1px solid #d1d5db !important;
        border-radius: 0.5rem !important;
        overflow: hidden !important;
    }
    
    .note-editor.note-frame {
        border: 1px solid #d1d5db !important;
    }
    
    .note-toolbar {
        background: #f9fafb !important;
        border-bottom: 1px solid #e5e7eb !important;
        padding: 0.5rem !important;
    }
    
    .note-editable {
        background: white !important;
        padding: 0.75rem 1rem !important;
        font-size: 0.875rem !important;
        line-height: 1.5 !important;
        color: #374151 !important;
    }
    
    .note-editable:focus {
        background: white !important;
    }
    
    .note-statusbar {
        display: none !important;
    }
    
    .note-btn-group .note-btn {
        background: white !important;
        border: 1px solid #e5e7eb !important;
        color: #374151 !important;
        padding: 0.25rem 0.5rem !important;
        font-size: 0.875rem !important;
    }
    
    .note-btn-group .note-btn:hover {
        background: #f3f4f6 !important;
        border-color: #d1d5db !important;
    }
    
    .note-btn-group .note-btn.active {
        background: #e0f2fe !important;
        border-color: #0082C3 !important;
        color: #0082C3 !important;
    }
    
    .note-dropdown-menu {
        border: 1px solid #d1d5db !important;
        border-radius: 0.5rem !important;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1) !important;
    }
    
    .note-dropdown-item {
        padding: 0.5rem 1rem !important;
        font-size: 0.875rem !important;
    }
    
    .note-dropdown-item:hover {
        background: #f3f4f6 !important;
    }
    
    .note-editable p {
        margin: 0.5rem 0 !important;
    }
    
    .note-editable p:first-child {
        margin-top: 0 !important;
    }
    
    .note-editable p:last-child {
        margin-bottom: 0 !important;
    }
    
    .note-editable ul,
    .note-editable ol {
        margin: 0.5rem 0 !important;
        padding-left: 1.5rem !important;
    }
    
    .note-editable h1,
    .note-editable h2,
    .note-editable h3,
    .note-editable h4,
    .note-editable h5,
    .note-editable h6 {
        margin: 1rem 0 0.5rem 0 !important;
        font-weight: 600 !important;
    }
    
    .note-editable h1:first-child,
    .note-editable h2:first-child,
    .note-editable h3:first-child,
    .note-editable h4:first-child,
    .note-editable h5:first-child,
    .note-editable h6:first-child {
        margin-top: 0 !important;
    }
`;
document.head.appendChild(style);
