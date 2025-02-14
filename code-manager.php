<?php
/**
 * Plugin Name: Code Manager
 * Description: Manage and toggle CSS/JS code snippets
 * Version: 1.0.0
 * Author: Your Name
 * Text Domain: code-manager
 */

defined('ABSPATH') || exit;

// Define constants
define('CM_VERSION', '1.0.0');
define('CM_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('CM_PLUGIN_URL', plugin_dir_url(__FILE__));

// Autoload classes
require_once CM_PLUGIN_DIR . 'includes/class-cm-loader.php';

// Initialize plugin
Code_Manager\Includes\CM_Loader::init();
