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
        // editor.setOptions({
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
        } else if (selectedType === 'php') {
            $('#cmSnippetPageSelector').hide();
            alert(cmData.i18n.phpNotAllowed);
            initializeEditor('php');
        } else {
            $('#cmSnippetPageSelector').hide();
            initializeEditor('css');
        }
    });

    let currentSnippetId = null;

    $('.cm-edit-snippet').on('click', function() {
        const snippetId = $(this).closest('tr').data('snippet-id');
        currentSnippetId = snippetId;

        console.log("Snippet ID:", snippetId); // Debug: Log the snippet ID

        $.get(cmData.ajaxUrl, {
            action: 'cm_get_snippet',
            snippet_id: snippetId,
            security: cmData.nonce
        }, function(response) {
            console.log("AJAX Response:", response); // Debug: Log the AJAX response

            if (response.success) {
                const snippet = response.data;
                console.log("Snippet Data:", snippet); // Debug: Log the snippet data

                $('#cmSnippetName').val(snippet.name);
                $('#cmSnippetType').val(snippet.type);

                // Populate condition fields
                $('#cmSnippetConditionType').val(snippet.condition_type);
                $('#cmSnippetUrlsInput').val(snippet.urls.join('\n'));
                $('#cmSnippetHookInput').val(snippet.hook);

                // Show/hide condition fields based on condition_type
                $('#cmSnippetConditionType').trigger('change');

                if (snippet.type === 'js') {
                    $('#cmSnippetPageSelector').show();
                    $('#cmSnippetPage').val(snippet.page_id);
                } else if (snippet.type === 'php') {
                    $('#cmSnippetPageSelector').hide();
                } else {
                    $('#cmSnippetPageSelector').hide();
                }

                // Initialize Ace Editor after fetching the snippet
                if ($('#cmSnippetType').val() === 'js') {
                    initializeEditor('javascript');
                } else if ($('#cmSnippetType').val() === 'php') {
                    initializeEditor('php');
                } else {
                    initializeEditor('css');
                }

                console.log("Editor:", editor); // Debug: Log the editor object
                console.log("Snippet Code:", snippet.code); // Debug: Log the snippet code
                editor.session.setValue(snippet.code); // Use session.setValue
                $('#cmAddSnippetForm').find('button[type="submit"]').text(cmData.i18n.updateSnippet);
            }
        });
    });


    $('#cmAddSnippetForm').on('submit', function(e) {
        e.preventDefault();

        const code = editor.getValue();
        const conditionType = $('#cmSnippetConditionType').val();
        let urls = [];
        let hook = '';

        if (conditionType === 'urls') {
            urls = $('#cmSnippetUrlsInput').val().split('\n').map(url => url.trim());
        } else if (conditionType === 'hook') {
            hook = $('#cmSnippetHookInput').val();
        }

        const data = {
            action: 'cm_save_snippet',
            name: $('#cmSnippetName').val(),
            type: $('#cmSnippetType').val(),
            code: code,
            page_id: $('#cmSnippetPage').val(),
            condition_type: conditionType,
            urls: urls,
            hook: hook,
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

    //Condition type logic
    $('#cmSnippetConditionType').on('change', function() {
        const conditionType = $(this).val();
        $('#cmSnippetPostIds, #cmSnippetPostTypes, #cmSnippetUserRoles, #cmSnippetUrls, #cmSnippetHook').hide();

        if (conditionType === 'post_ids') {
            $('#cmSnippetPostIds').show();
            // Populate the select with options
            const select = $('#cmSnippetPostIdsSelect');
            select.empty(); // Clear existing options
            $.each(cmData.pages, function(id, title) {
                select.append($('<option>', {
                    value: id,
                    text: title
                }));
            });

        } else if (conditionType === 'post_types') {
            $('#cmSnippetPostTypes').show();
            const select = $('#cmSnippetPostTypesSelect');
            select.empty();
            $.each(cmData.post_types, function(id, title) {
                select.append($('<option>', {
                    value: id,
                    text: title
                }));
            });
        } else if (conditionType === 'user_roles') {
            $('#cmSnippetUserRoles').show();
            const select = $('#cmSnippetUserRolesSelect');
            select.empty();
            $.each(cmData.user_roles, function(id, title) {
                select.append($('<option>', {
                    value: id,
                    text: title
                }));
            });
        } else if (conditionType === 'urls') {
            $('#cmSnippetUrls').show();
        } else if (conditionType === 'hook') {
            $('#cmSnippetHook').show();
         }
    });

    // Handle click on the "Versions" button
    $(document).on('click', '.cm-versions-snippet', function() {
        const snippetId = $(this).closest('tr').data('snippet-id');
        $.get(cmData.ajaxUrl, {
            action: 'cm_get_snippet_versions',
            snippet_id: snippetId,
            security: cmData.nonce
        }, function(response) {
            if (response.success) {
                if (response.data.length === 0) {
                    console.log('No versions found for this snippet.');
                    // TODO: Display a message indicating no versions found
                } else {
                    console.log(response.data); // Log the versions to the console
                    // TODO: Display the versions in a modal or a dedicated section
                }
            } else {
                alert('Failed to retrieve versions.');
            }
        });
    });
});
