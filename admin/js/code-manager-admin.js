jQuery(document).ready(function($) {
    $('#cmSnippetType').on('change', function() {
        if ($(this).val() === 'js') {
            $('#cmSnippetPageSelector').show();
        } else if ($(this).val() === 'php') {
            $('#cmSnippetPageSelector').hide();
            alert(cmData.i18n.phpNotAllowed);
        } else {
            $('#cmSnippetPageSelector').hide();
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
                $('#cmSnippetCode').val(snippet.code);
                if (snippet.type === 'js') {
                    $('#cmSnippetPageSelector').show();
                    $('#cmSnippetPage').val(snippet.page_id);
                } else {
                    $('#cmSnippetPageSelector').hide();
                }
                $('#cmAddSnippetForm').find('button[type="submit"]').text(cmData.i18n.updateSnippet);
            }
        });
    });


    $('#cmAddSnippetForm').on('submit', function(e) {
        e.preventDefault();

        const data = {
            action: 'cm_save_snippet',
            name: $('#cmSnippetName').val(),
            type: $('#cmSnippetType').val(),
            code: $('#cmSnippetCode').val(),
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
});
