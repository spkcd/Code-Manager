<?php
namespace Code_Manager\Includes;

final class CM_Loader {
    public static function init() {
        // Load dependencies
        require_once CM_PLUGIN_DIR . 'admin/class-cm-admin.php';
        require_once CM_PLUGIN_DIR . 'public/class-cm-public.php';

        // Initialize components
        Admin\CM_Admin::init();
        Public\CM_Public::init();
    }
}
