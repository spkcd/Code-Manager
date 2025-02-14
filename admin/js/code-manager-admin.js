jQuery(function($) {
    // Save new snippet
    $('#cmAddSnippetForm').on('submit', function(e) {
        e.preventDefault();
        
        const data = {
            action: 'cm_save_snippet',
            security: cmData.nonce,
            name: $('#cmSnippetName').val(),
            type: $('#cmSnippetType').val(),
            code: $('#cmSnippetCode').val()
        };

        $.post(cmData.ajaxUrl, data, function(response) {
            if (response.success) {
                location.reload();
            }
        });
    });

    // Toggle snippet status
    $('.cm-toggle-snippet').on('click', function() {
        const $row = $(this).closest('tr');
        const snippetId = $row.data('snippet-id');
        const $checkbox = $row.find('input[type="checkbox"]');
        const newStatus = !$checkbox.prop('checked');

        updateSnippetStatus(snippetId, newStatus);
    });

    // Delete snippet
    $('.cm-delete-snippet').on('click', function() {
        if (!confirm('Are you sure you want to delete this snippet?')) return;

        const $row = $(this).closest('tr');
        const snippetId = $row.data('snippet-id');

        $.post(cmData.ajaxUrl, {
            action: 'cm_delete_snippet',
            security: cmData.nonce,
            snippet_id: snippetId
        }, function(response) {
            if (response.success) {
                $row.remove();
            }
        });
    });

    function updateSnippetStatus(snippetId, status) {
        $.post(cmData.ajaxUrl, {
            action: 'cm_update_snippet_status',
            security: cmData.nonce,
            snippet_id: snippetId,
            status: status
        }, function(response) {
            if (response.success) {
                location.reload();
            }
        });
    }
});
