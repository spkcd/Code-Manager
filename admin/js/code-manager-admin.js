jQuery(document).ready(function($) {
    let editor;

    function initializeEditor(mode) {
        if (editor) {
          editor.destroy();
          editor = null;
        }

        editor = ace.edit("cmSnippetCode");
        editor.setTheme("ace/theme/" + cmData.defaultTheme); // Use default theme
        editor.session.setMode("ace/mode/" + mode);
        // editor.setOptions({  // Moved to after initialization
        //     enableBasicAutocompletion: true,
        //     enableLiveAutocompletion: true,
        //     enableSnippets: true
        // });

    }


    $('#cmSnippetType').on('change', function() {
        const selectedType = $(this).val();
        if (selectedType === 'js') {
            $('#cmSnippetPageSelector').show();
            initializeEditor('javascript');
            editor.setOptions({
                enableBasicAutocompletion: true,
                enableLiveAutocompletion: true,
                enableSnippets: true
            });
        } else if (selectedType === 'php') {
            $('#cmSnippetPageSelector').hide();
            alert(cmData.i18n.phpNotAllowed);
            initializeEditor('php');
            editor.setOptions({
                enableBasicAutocompletion: true,
                enableLiveAutocompletion: true,
                enableSnippets: true
            });
        } else {
            $('#cmSnippetPageSelector').hide();
            initializeEditor('css');
            editor.setOptions({
                enableBasicAutocompletion: true,
                enableLiveAutocompletion: true,
                enableSnippets: true
            });
        }
    });

    let currentSnippetId = null;

    $('.cm-edit-snippet').on('click', function() {
        const snippetId = $(this).closest('tr').data('snippet-id');
        currentSnippetId = snippetId;

        $.get(cmData.ajaxUrl, {
            action: 'cm_get_snippet',
            snippet_id: snippetId,
            security: cmData.nonce
        }, function(response) {
            if (response.success) {
                const snippet = response.data;
                $('#cmSnippetName').val(snippet.name);
                $('#cmSnippetType').val(snippet.type);

                if (snippet.type === 'js') {
                    $('#cmSnippetPageSelector').show();
                    $('#cmSnippetPage').val(snippet.page_id);
                    initializeEditor('javascript');
                    editor.setOptions({
                        enableBasicAutocompletion: true,
                        enableLiveAutocompletion: true,
                        enableSnippets: true
                    });
                } else if (snippet.type === 'php') {
                    $('#cmSnippetPageSelector').hide();
                    initializeEditor('php');
                    editor.setOptions({
                        enableBasicAutocompletion: true,
                        enableLiveAutocompletion: true,
                        enableSnippets: true
                    });
                }
                else {
                    $('#cmSnippetPageSelector').hide();
                    initializeEditor('css');
                    editor.setOptions({
                        enableBasicAutocompletion: true,
                        enableLiveAutocompletion: true,
                        enableSnippets: true
                    });
                }
                // Set the value in the CodeMirror instance AFTER initialization
                editor.setValue(snippet.code);
                $('#cmAddSnippetForm').find('button[type="submit"]').text(cmData.i18n.updateSnippet);

            }
        });
    });


    $('#cmAddSnippetForm').on('submit', function(e) {
        e.preventDefault();

        // Get the value from the CodeMirror instance
        const code = editor.getValue();


        const data = {
            action: 'cm_save_snippet',
            name: $('#cmSnippetName').val(),
            type: $('#cmSnippetType').val(),
            code: code,
            page_id: $('#cmSnippetPage').val(),
            security: cmData.nonce
        };

        if (currentSnippetId) {
            data.snippet_id = currentSnippetId;
        }

        $.post(cmData.ajaxUrl, data, function(response) {
            if (response.success) {
                location.reload();
            } else {
                alert(cmData.i18n.saveFailed);
            }
        });
    });


    $('.cm-delete-snippet').on('click', function() {
        if (!confirm(cmData.i18n.confirmDelete)) {
            return;
        }

        const snippetId = $(this).closest('tr').data('snippet-id');

        $.post(cmData.ajaxUrl, {
            action: 'cm_delete_snippet',
            snippet_id: snippetId,
            security: cmData.nonce
        }, function(response) {
            if (response.success) {
                location.reload();
            }
        });
    });

    $('#cm-install-defaults').on('click', function() {
        if (!confirm(cmData.i18n.confirmInstall)) {
            return;
        }

        $(this).prop('disabled', true).text(cmData.i18n.installing);

        $.post(cmData.ajaxUrl, {
            action: 'cm_install_defaults',
            security: cmData.nonce
        }, function(response) {
            $('#cm-install-defaults').prop('disabled', false).text(cmData.i18n.installDefaults);

            if (response.success) {
                alert(cmData.i18n.installSuccess);
                location.reload();
            } else {
                alert(cmData.i18n.installFailed + ': ' + response.data);
            }
        });
    });

    $('.cm-toggle-snippet').on('click', function() {
        const snippetId = $(this).closest('tr').data('snippet-id');
        const isActive = $('#cmToggle' + snippetId).is(':checked');

        $.post(cmData.ajaxUrl, {
            action: 'cm_toggle_snippet',
            snippet_id: snippetId,
            active: isActive ? 1 : 0,
            security: cmData.nonce
        }, function(response) {
            if (response.success) {
                location.reload();
            }
        });
    });

    $('#cm-export-defaults').on('click', function() {
        $.post(cmData.ajaxUrl, {
            action: 'cm_export_defaults',
            security: cmData.nonce
        }, function(response) {
            if (response.success) {
                // Create a temporary link to download the JSON data
                const blob = new Blob([JSON.stringify(response.data, null, 2)], {type: 'application/json'});
                const url = URL.createObjectURL(blob);
                const a = document.createElement('a');
                a.href = url;
                a.download = 'code-manager-default-snippets.json';
                document.body.appendChild(a);
                a.click();
                document.body.removeChild(a);
                URL.revokeObjectURL(url);
            } else {
                alert(cmData.i18n.exportFailed);
            }
        });
    });

    $('#cm-import-defaults').on('click', function() {
        if (!confirm(cmData.i18n.confirmImport)) {
            return;
        }

        const input = document.createElement('input');
        input.type = 'file';
        input.accept = '.json';
        input.onchange = function(event) {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const content = e.target.result;
                    $.post(cmData.ajaxUrl, {
                        action: 'cm_import_defaults',
                        snippets: content,
                        security: cmData.nonce
                    }, function(response) {
                        if (response.success) {
                            alert(cmData.i18n.importSuccess);
                            location.reload();
                        } else {
                            alert(cmData.i18n.importFailed + ': ' + response.data);
                        }
                    });
                };
                reader.readAsText(file);
            }
        };
        input.click();
    });

    // Theme switching
    $('#cmSnippetTheme').on('change', function() {
      const theme = $(this).val();
      editor.setTheme("ace/theme/" + theme);
    });

      // Initialize on load with CSS mode
    initializeEditor('css');
});
