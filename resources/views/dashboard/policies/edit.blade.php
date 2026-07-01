@extends('layouts.dashboard')

@section('title', 'Policy Editor - Ghost Compiler')
@section('page-title', 'Policy Markdown Editor')

@section('content')
<div class="space-y-6">
    <!-- Header Box -->
    <div class="rounded-xl border border-brand-border bg-gradient-to-r from-brand-card/30 to-brand-accent/5 p-5">
        <h2 class="text-sm font-bold text-brand-text">Local Markdown Policy Manager</h2>
        <p class="text-xs text-brand-muted mt-1 leading-relaxed">
            Draft, format, and save legal documents directly to the codebase repository markdown files (no database storage).
            These files are automatically rendered into styled dark-mode layouts on the public policy pages.
        </p>
    </div>

    <!-- Alert Messages -->
    @if(session('success'))
        <div class="rounded-lg border border-emerald-500/20 bg-emerald-500/10 p-3 text-xs text-brand-accent">
            {{ session('success') }}
        </div>
    @endif

    @if($errors->any())
        <div class="rounded-lg border border-rose-500/20 bg-rose-500/10 p-3 text-xs text-rose-500">
            @foreach($errors->all() as $error)
                <p>{{ $error }}</p>
            @endforeach
        </div>
    @endif

    <!-- Editor card -->
    <div class="rounded-xl border border-brand-border bg-brand-card/40 p-5 space-y-4">
        <form action="{{ route('admin.policies.save') }}" method="POST" class="space-y-4">
            @csrf

            <!-- Dropdown Selector -->
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-b border-brand-border/40 pb-4">
                <div class="space-y-1">
                    <label for="policy_type" class="text-xs font-bold text-brand-text">Select Document to Edit</label>
                    <p class="text-[10px] text-brand-muted">Changing selector reloads corresponding root markdown file.</p>
                </div>
                <input type="hidden" name="type" value="{{ $type }}">
                <select id="policy_type" onchange="window.location.href = '{{ route('admin.policies.edit') }}?type=' + this.value"
                        class="rounded-lg border border-brand-border bg-brand-bg px-3 py-2 text-xs font-bold text-brand-text outline-none focus:border-brand-accent/50 focus:ring-1 focus:ring-brand-accent/20 cursor-pointer min-w-[200px]">
                    <option value="privacy-policy" {{ $type === 'privacy-policy' ? 'selected' : '' }}>Privacy Policy</option>
                    <option value="terms-of-service" {{ $type === 'terms-of-service' ? 'selected' : '' }}>Terms of Service</option>
                    <option value="terms-and-conditions" {{ $type === 'terms-and-conditions' ? 'selected' : '' }}>Terms & Conditions</option>
                </select>
            </div>

            <!-- Markdown Toolbar -->
            <div class="flex flex-wrap gap-2 items-center bg-brand-bg/50 border border-brand-border/60 rounded-lg p-2">
                <button id="btn_bold" type="button" onclick="toggleFormat('**')" title="Bold"
                        class="h-8 w-8 rounded hover:bg-brand-border/40 text-brand-text text-xs font-bold transition flex items-center justify-center cursor-pointer border border-transparent hover:border-brand-border/50">
                    B
                </button>
                <button id="btn_italic" type="button" onclick="toggleFormat('*')" title="Italic"
                        class="h-8 w-8 rounded hover:bg-brand-border/40 text-brand-text text-xs italic transition flex items-center justify-center cursor-pointer border border-transparent hover:border-brand-border/50">
                    I
                </button>
                <button id="btn_code" type="button" onclick="toggleFormat('`')" title="Inline Code"
                        class="h-8 w-8 rounded hover:bg-brand-border/40 text-brand-text text-xs font-mono font-semibold transition flex items-center justify-center cursor-pointer border border-transparent hover:border-brand-border/50">
                    &lt;&gt;
                </button>
                <div class="h-4 border-r border-brand-border/60 mx-1"></div>
                <button type="button" onclick="insertMarkdown('# ')" title="Heading 1"
                        class="px-2.5 h-8 rounded hover:bg-brand-border/40 text-brand-text text-xs font-semibold transition flex items-center justify-center cursor-pointer border border-transparent hover:border-brand-border/50">
                    H1
                </button>
                <button type="button" onclick="insertMarkdown('## ')" title="Heading 2"
                        class="px-2.5 h-8 rounded hover:bg-brand-border/40 text-brand-text text-xs font-semibold transition flex items-center justify-center cursor-pointer border border-transparent hover:border-brand-border/50">
                    H2
                </button>
                <button type="button" onclick="insertMarkdown('### ')" title="Heading 3"
                        class="px-2.5 h-8 rounded hover:bg-brand-border/40 text-brand-text text-xs font-semibold transition flex items-center justify-center cursor-pointer border border-transparent hover:border-brand-border/50">
                    H3
                </button>
                <div class="h-4 border-r border-brand-border/60 mx-1"></div>
                <button type="button" onclick="insertMarkdown('[', '](url)')" title="Insert Link"
                        class="px-3 h-8 rounded hover:bg-brand-border/40 text-brand-text text-xs font-semibold transition flex items-center justify-center cursor-pointer border border-transparent hover:border-brand-border/50">
                    Link
                </button>
                <button type="button" onclick="insertMarkdown('- ')" title="Unordered List"
                        class="px-3 h-8 rounded hover:bg-brand-border/40 text-brand-text text-xs font-semibold transition flex items-center justify-center cursor-pointer border border-transparent hover:border-brand-border/50">
                    List
                </button>
                <button type="button" onclick="insertMarkdown('1. ')" title="Ordered List"
                        class="px-3 h-8 rounded hover:bg-brand-border/40 text-brand-text text-xs font-semibold transition flex items-center justify-center cursor-pointer border border-transparent hover:border-brand-border/50">
                    1. List
                </button>
                <button type="button" onclick="insertMarkdown('```\n', '\n```')" title="Code Block"
                        class="px-3 h-8 rounded hover:bg-brand-border/40 text-brand-text text-xs font-mono font-semibold transition flex items-center justify-center cursor-pointer border border-transparent hover:border-brand-border/50">
                    Block &lt;/&gt;
                </button>
            </div>

            <!-- Full-Height Editor Textarea -->
            <div class="space-y-1">
                <textarea id="editor_content" name="content" rows="22" placeholder="Write policy content in Markdown format..."
                          class="w-full rounded-lg border border-brand-border bg-brand-bg/40 p-4 text-xs text-brand-text font-mono outline-none focus:border-brand-accent/50 focus:ring-1 focus:ring-brand-accent/20 leading-relaxed resize-y">{{ $content }}</textarea>
                <div class="flex justify-between items-center text-[10px] text-brand-muted px-1">
                    <span>Markdown formatting supported</span>
                    <span>File target: <code class="bg-brand-bg px-1 py-0.5 rounded">{{ $type }}.md</code></span>
                </div>
            </div>

            <!-- Action buttons -->
            <div class="flex items-center justify-end gap-3 border-t border-brand-border/40 pt-4">
                <button type="submit" class="rounded-lg bg-brand-accent px-4 py-2.5 text-xs font-bold text-white hover:bg-brand-accent-hover transition shadow-md shadow-brand-accent/15 cursor-pointer">
                    Save Policy File
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    const textarea = document.getElementById('editor_content');
    const btnBold = document.getElementById('btn_bold');
    const btnItalic = document.getElementById('btn_italic');
    const btnCode = document.getElementById('btn_code');

    // Monitor cursor movements and selection state
    textarea.addEventListener('keyup', updateToolbarStates);
    textarea.addEventListener('mouseup', updateToolbarStates);
    textarea.addEventListener('select', updateToolbarStates);
    textarea.addEventListener('click', updateToolbarStates);

    function updateToolbarStates() {
        const start = textarea.selectionStart;
        const end = textarea.selectionEnd;
        const text = textarea.value;

        // Check if formatting states exist surrounding selection or current cursor
        const isBold = isFormatted(text, start, end, '**');
        const isItalic = isFormatted(text, start, end, '*') && !isFormatted(text, start, end, '**');
        const isCode = isFormatted(text, start, end, '`');

        toggleButtonHighlight(btnBold, isBold);
        toggleButtonHighlight(btnItalic, isItalic);
        toggleButtonHighlight(btnCode, isCode);
    }

    function toggleButtonHighlight(btn, active) {
        if (!btn) return;
        if (active) {
            btn.classList.add('bg-brand-accent', 'text-white');
            btn.classList.remove('hover:bg-brand-border/40', 'text-brand-text');
        } else {
            btn.classList.remove('bg-brand-accent', 'text-white');
            btn.classList.add('hover:bg-brand-border/40', 'text-brand-text');
        }
    }

    // Returns true if selection is wrapped or cursor is inside block wrapped by marker
    function isFormatted(text, start, end, marker) {
        const len = marker.length;
        
        // 1. Text is wrapped at selection boundaries: marker|selected|marker
        if (start >= len && end <= text.length - len) {
            if (text.substring(start - len, start) === marker && text.substring(end, end + len) === marker) {
                return true;
            }
        }
        
        // 2. Selection contains formatting characters internally: |markerselectedmarker|
        if (end - start >= len * 2) {
            if (text.substring(start, start + len) === marker && text.substring(end - len, end) === marker) {
                return true;
            }
        }

        // 3. Cursor (or selection) is inside a block wrapped by the marker on the same line
        let beforeIdx = text.lastIndexOf(marker, start - 1);
        let afterIdx = text.indexOf(marker, start);
        if (beforeIdx !== -1 && afterIdx !== -1 && beforeIdx !== afterIdx) {
            const inlineSection = text.substring(beforeIdx, afterIdx + len);
            if (!inlineSection.includes('\n')) {
                // Ensure there is an odd number of markers before our current position on this line to represent a toggle
                // Let's count markers in the current line
                const lineStart = text.lastIndexOf('\n', start - 1) + 1;
                const lineEnd = text.indexOf('\n', start);
                const lineText = text.substring(lineStart, lineEnd !== -1 ? lineEnd : text.length);
                const relativePos = start - lineStart;
                
                let countBefore = 0;
                let idx = lineText.indexOf(marker);
                while (idx !== -1 && idx < relativePos) {
                    countBefore++;
                    idx = lineText.indexOf(marker, idx + len);
                }
                
                // If odd number of markers appear before selection, cursor is inside the wrapped text block
                if (countBefore % 2 !== 0) {
                    return true;
                }
            }
        }
        return false;
    }

    // Toggle formatting wrapper around selected text or current cursor
    function toggleFormat(marker) {
        const start = textarea.selectionStart;
        const end = textarea.selectionEnd;
        const text = textarea.value;
        const len = marker.length;

        // 1. Selection is wrapped externally: marker|selectedText|marker
        if (start >= len && end <= text.length - len && 
            text.substring(start - len, start) === marker && 
            text.substring(end, end + len) === marker) {
            
            textarea.value = text.substring(0, start - len) + text.substring(start, end) + text.substring(end + len);
            textarea.focus();
            textarea.setSelectionRange(start - len, end - len);
            updateToolbarStates();
            return;
        }

        // 2. Selection contains formatting internally: |markerselectedTextmarker|
        if (end - start >= len * 2 && 
            text.substring(start, start + len) === marker && 
            text.substring(end - len, end) === marker) {
            
            textarea.value = text.substring(0, start) + text.substring(start + len, end - len) + text.substring(end);
            textarea.focus();
            textarea.setSelectionRange(start, end - len * 2);
            updateToolbarStates();
            return;
        }

        // 3. Cursor/Selection is inside a formatted block: marker...cur...marker
        let beforeIdx = text.lastIndexOf(marker, start - 1);
        let afterIdx = text.indexOf(marker, start);
        if (beforeIdx !== -1 && afterIdx !== -1 && beforeIdx !== afterIdx) {
            const inlineSection = text.substring(beforeIdx, afterIdx + len);
            if (!inlineSection.includes('\n')) {
                // Confirm cursor is inside by counting markers before relative pos in line
                const lineStart = text.lastIndexOf('\n', start - 1) + 1;
                const lineEnd = text.indexOf('\n', start);
                const lineText = text.substring(lineStart, lineEnd !== -1 ? lineEnd : text.length);
                const relativePos = start - lineStart;
                
                let countBefore = 0;
                let idx = lineText.indexOf(marker);
                while (idx !== -1 && idx < relativePos) {
                    countBefore++;
                    idx = lineText.indexOf(marker, idx + len);
                }
                
                if (countBefore % 2 !== 0) {
                    // Strip the markers
                    textarea.value = text.substring(0, beforeIdx) + 
                                     text.substring(beforeIdx + len, afterIdx) + 
                                     text.substring(afterIdx + len);
                    textarea.focus();
                    
                    // Adjust cursor positions
                    const newStart = start - len;
                    const newEnd = end - len;
                    textarea.setSelectionRange(newStart, newEnd);
                    updateToolbarStates();
                    return;
                }
            }
        }

        // 4. Default: No formatting detected, apply new format
        const selectedText = text.substring(start, end);
        const replacement = marker + selectedText + marker;
        textarea.value = text.substring(0, start) + replacement + text.substring(end);
        textarea.focus();
        textarea.setSelectionRange(start + len, start + len + selectedText.length);
        updateToolbarStates();
    }

    // Standard markdown insert logic for headings, links, etc.
    function insertMarkdown(before, after = '') {
        const start = textarea.selectionStart;
        const end = textarea.selectionEnd;
        const text = textarea.value;
        const selectedText = text.substring(start, end);
        const replacement = before + selectedText + after;
        
        textarea.value = text.substring(0, start) + replacement + text.substring(end);
        textarea.focus();
        textarea.setSelectionRange(start + before.length, start + before.length + selectedText.length);
        updateToolbarStates();
    }
</script>
@endsection
