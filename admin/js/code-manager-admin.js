jQuery(function($) {
    let codeEditor;

    // Initialize CodeMirror
    function initCodeEditor(type) {
        const editorSettings = wp.codeEditor.defaultSettings;
        editorSettings.codemirror.mode = type === 'css' ? 'css' : 'javascript';
        
        if(codeEditor) {
            codeEditor.destroy();
        }

        codeEditor = wp.codeEditor.initialize($('#cmSnippetCode'), editorSettings);
    }

    // Type Change Handler
    $('#cmSnippetType').on('change', function() {
        initCodeEditor($(this).val());
    });

    // Initial Editor Setup
    initCodeEditor($('#cmSnippetType').val());

    // Form Submission
    $('#cmAddSnippetForm').on('submit', function(e) {
        e.preventDefault();

        // Update textarea with editor content
        codeEditor.codemirror.save();

        const formData = {
            action: 'cm_save_snippet',
            security: cmData.nonce,
            name: $('#cmSnippetName').val(),
            type: $('#cmSnippetType').val(),
            code: $('#cmSnippetCode').val()
        };

        $.post(cmData.ajaxUrl, formData, function(response) {
            if (response.success) {
                location.reload();
            }
        });
    });

    // Toggle Snippet
    $('.cm-toggle-snippet').on('click', function() {
        const $row = $(this).closest('tr');
        const snippetId = $row.data('snippet-id');

        $.post(cmData.ajaxUrl, {
            action: 'cm_toggle_snippet',
            security: cmData.nonce,
            snippet_id: snippetId
        }, function(response) {
            if (response.success) {
                location.reload();
            }
        });
    });

    // Delete Snippet
    $('.cm-delete-snippet').on('click', function() {
        if (!confirm(cmData.i18n.confirmDelete)) return;

        const $row = $(this).closest('tr');
        const snippetId = $row.data('snippet-id');

        $.post(cmData.ajaxUrl, {
            action: 'cm_delete_snippet',
            security: cmData.nonce,
            snippet_id: snippetId
        }, function(response) {
            if (response.success) {
                $row.fadeOut(300, () => $row.remove());
            }
        });
    });
});
