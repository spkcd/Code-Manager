<?php
/**
 * Plugin Name: Code Manager
 * Description: Manage and toggle CSS/JS code snippets
 * Version: 1.3.0
 * Author: SPARKWEB Studio
 * Author URI: https://sparkwebstudio.com/
 * License: GPL-3.0
 * Text Domain: code-manager
 */

defined('ABSPATH') || exit;

// Define constants
define('CM_VERSION', '1.3.0');
define('CM_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('CM_PLUGIN_URL', plugin_dir_url(__FILE__));

// Loader class
require_once CM_PLUGIN_DIR . 'includes/class-cm-loader.php';

// Initialize plugin
add_action('plugins_loaded', 'cm_initialize_plugin');
function cm_initialize_plugin() {
    if (class_exists('Code_Manager\Includes\CM_Loader')) {
        Code_Manager\Includes\CM_Loader::init();
    } else {
        add_action('admin_notices', function() {
            echo '<div class="notice notice-error"><p>';
            _e('Code Manager failed to initialize. Please reinstall.', 'code-manager');
            echo '</p></div>';
        });
    }
}
