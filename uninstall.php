<?php
if (!defined('WP_UNINSTALL_PLUGIN')) {
    exit;
}

// Delete all plugin data
delete_option('cm_code_snippets');
delete_option('cm_defaults_installed');

// Remove any transients
$transients = [
    'cm_available_updates',
    'cm_last_activation'
];

foreach ($transients as $transient) {
    delete_transient($transient);
}
