<?php
/**
 * Plugin URI: https://github.com/spkcd/Code-Manager
 * Author URI: https://sparkwebstudio.com/
 * License: GPL-3.0
 * License URI: https://www.gnu.org/licenses/gpl-3.0.html
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
