(function($) {
    'use strict';

    let codeEditor = null;

    // Initialize CodeMirror Editor
    function initCodeEditor(type) {
        if (!wp || !wp.codeEditor) {
            console.error('WordPress code editor library not loaded');
            return;
        }

        const editorSettings = wp.codeEditor.defaultSettings;
        editorSettings.codemirror.mode = type === 'css' ? 'css' : 'javascript';

        // If editor exists, update mode
        if (codeEditor) {
            codeEditor.codemirror.setOption('mode', editorSettings.codemirror.mode);
            return;
        }

        // Initialize editor
        const textarea = document.getElementById('cmSnippetCode');
        if (!textarea) {
            console.error('Could not find code textarea');
            return;
        }

        codeEditor = wp.codeEditor.initialize(textarea, editorSettings);
    }

    // Handle Code Type Changes
    $('#cmSnippetType').on('change', function() {
        initCodeEditor($(this).val());
    });

    // Initial Setup
    initCodeEditor($('#cmSnippetType').val());

    // ----------------------
    // SAVE SNIPPET
    // ----------------------
    $('#cmAddSnippetForm').on('submit', function(e) {
        e.preventDefault();

        if (!codeEditor) {
            alert('Code editor not initialized');
            return;
        }

        codeEditor.codemirror.save();

        const $form = $(this);
        const data = {
            action: 'cm_save_snippet',
            security: cmData.nonce,
            name: $('#cmSnippetName').val(),
            type: $('#cmSnippetType').val(),
            code: $('#cmSnippetCode').val()
        };

        const isEditing = $form.data('editing');
        if (isEditing) {
            data.snippet_id = isEditing;
        }

        $form.find('button').prop('disabled', true).text(cmData.i18n.saving);

        $.post(cmData.ajaxUrl, data)
            .done(function(response) {
                if (response.success) {
                    codeEditor.codemirror.setValue('');
                    $form.trigger('reset').removeData('editing');
                    location.reload();
                }
            })
            .fail(function(jqXHR) {
                alert(cmData.i18n.saveFailed);
                console.error('Save Error:', jqXHR);
            });
    });

    // ----------------------
    // EDIT SNIPPET
    // ----------------------
    $(document).on('click', '.cm-edit-snippet', function(e) {
        e.preventDefault();
        const $button = $(this);
        const $row = $button.closest('tr');
        const snippetId = $row.data('snippet-id');

        $button.prop('disabled', true).text(cmData.i18n.loading);

        $.ajax({
            url: cmData.ajaxUrl,
            dataType: 'json',
            data: {
                action: 'cm_get_snippet',
                security: cmData.nonce,
                snippet_id: snippetId
            }
        }).done(function(response) {
            if (response.success) {
                const snippet = response.data;
                
                // Update form fields
                $('#cmSnippetName').val(snippet.name);
                $('#cmSnippetType').val(snippet.type).trigger('change');

                // Delay setting value to ensure editor is ready
                setTimeout(() => {
                    if (codeEditor) {
                        codeEditor.codemirror.setValue(snippet.code);
                        codeEditor.codemirror.refresh();
                    }
                }, 100);

                // Update form state
                $('#cmAddSnippetForm')
                    .data('editing', snippetId)
                    .find('button[type="submit"]')
                    .text(cmData.i18n.updateSnippet);
            }
        }).fail(function(jqXHR) {
            alert(cmData.i18n.editFailed);
            console.error('Edit Error:', jqXHR);
        }).always(function() {
            $button.prop('disabled', false).text(cmData.i18n.edit);
        });
    });

// ----------------------
// TOGGLE SNIPPET 1.4
// ----------------------
$('.cm-toggle-snippet').on('click', function() {
    const $btn = $(this);
    const $row = $btn.closest('tr');
    const snippetId = $row.data('snippet-id');
    const $checkbox = $row.find('input[type="checkbox"]');
    const currentState = $checkbox.prop('checked');
    
    // Disable during request
    $checkbox.prop('disabled', true);

    $.post(cmData.ajaxUrl, {
        action: 'cm_toggle_snippet',
        security: cmData.nonce,
        snippet_id: snippetId
    }).done(function() {
        $checkbox.prop('checked', !currentState);
    }).fail(function(jqXHR) {
        console.error('Toggle Error:', jqXHR);
        $checkbox.prop('checked', currentState);
    }).always(function() {
        $checkbox.prop('disabled', false);
    });
});

    // ----------------------
    // DELETE SNIPPET
    // ----------------------
    $('.cm-delete-snippet').on('click', function() {
        if (!confirm(cmData.i18n.confirmDelete)) return;
        
        const $row = $(this).closest('tr');
        const snippetId = $row.data('snippet-id');
        
        $.post(cmData.ajaxUrl, {
            action: 'cm_delete_snippet',
            security: cmData.nonce,
            snippet_id: snippetId
        }).done(function(response) {
            if (response.success) {
                $row.fadeOut(300, function() {
                    $(this).remove();
                });
            }
        });
    });

    // ----------------------
    // INSTALL DEFAULTS
    // ----------------------
    $('#cm-install-defaults').on('click', function(e) {
        e.preventDefault();
        const $button = $(this);
        const originalText = $button.text();

        if (!confirm(cmData.i18n.confirmInstall)) return;

        $button
            .prop('disabled', true)
            .text(cmData.i18n.installing);

        $.post(
            cmData.ajaxUrl,
            { action: 'cm_install_defaults', security: cmData.nonce }
        ).done(function(response) {
            if (response.success) {
                alert(cmData.i18n.installSuccess);
                location.reload();
            } else {
                alert(cmData.i18n.installFailed + ': ' + response.data);
            }
        }).fail(function(jqXHR) {
            alert(cmData.i18n.installFailed + ': ' + jqXHR.statusText);
        }).always(function() {
            $button
                .prop('disabled', false)
                .text(originalText);
        });
    });

})(jQuery);
