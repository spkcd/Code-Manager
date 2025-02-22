<?php
/**
 * Plugin Name: Code Manager
 * Description: Manage and toggle CSS/JS code snippets
 * Version: 1.4.0
 * Author: SPARKWEB Studio
 * Author URI: https://sparkwebstudio.com/
 * License: GPL-3.0
 * Text Domain: code-manager
 */

defined('ABSPATH') || exit;

// Define constants
define('CM_VERSION', '1.4.0');
define('CM_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('CM_PLUGIN_URL', plugin_dir_url(__FILE__));

// Loader class
// Loader class
require_once CM_PLUGIN_DIR . 'includes/CM_Loader.php';

add_action('init', array('Code_Manager\Admin\CM_Admin', 'execute_php_snippets'));

// Initialize plugin
add_action('plugins_loaded', 'cm_initialize_plugin');
function cm_initialize_plugin() {
  if (class_exists('Code_Manager\Includes\CM_Loader')) {
    Code_Manager\Includes\CM_Loader::init();
  } else {
    add_action('admin_notices', function () {
      echo '<div class="notice notice-error"><p>'
        . esc_html__('Code Manager failed to initialize. Please reinstall.', 'code-manager')
        . '</p></div>';
    });
  }
}

// Add settings link
add_filter('plugin_action_links_' . plugin_basename(__FILE__), 'cm_plugin_settings_link');
function cm_plugin_settings_link($links) {
    array_unshift($links, ' <a href="admin.php?page=code-manager">' 
        . esc_html__('Manage Snippets', 'code-manager') 
        . '</a>');
    return $links;
}
