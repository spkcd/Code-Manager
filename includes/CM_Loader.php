<?php
namespace Code_Manager\Includes;

defined('ABSPATH') || exit;

class CM_Loader {
    const DEFAULT_SNIPPETS_VERSION = '1.7.0';

    public static function init() {
        register_activation_hook(plugin_basename(CM_PLUGIN_DIR . 'code-manager.php'), [__CLASS__, 'activate_plugin']);
        
        try {
        self::load_dependencies();
        self::initialize_components();
    } catch (\Exception $e) {
        add_action('admin_notices', function() use ($e) {
            echo '<div class="notice notice-error"><p>';
            printf(
                esc_html__('Code Manager Error: %s', 'code-manager'),
                esc_html($e->getMessage())
            );
            echo '</p></div>';
        });
    }
  }

    public static function activate_plugin() {
        self::load_default_snippets();
    }

    private static function load_dependencies() {
        $files = [
            'admin/class-cm-admin.php',
            'public/class-cm-public.php'
        ];

        foreach ($files as $file) {
            $path = CM_PLUGIN_DIR . $file;
            if (!file_exists($path)) {
                throw new \Exception(sprintf('Missing file: %s', $file));
            }
            require_once $path;
        }
    }

    private static function initialize_components() {
        \Code_Manager\Admin\CM_Admin::init();
        \Code_Manager\Public\CM_Public::init();
    }

    private static function load_default_snippets($force = false) {
        $defaults_path = CM_PLUGIN_DIR . 'includes/default-snippets.json';
        
        // Debug path
        error_log('[Code Manager] Defaults Path: ' . $defaults_path);
    
        if (!file_exists($defaults_path)) {
            throw new \Exception(__('Defaults file missing', 'code-manager'));
        }
    
        $json = file_get_contents($defaults_path);
        if (!$json) {
            throw new \Exception(__('Could not read defaults file', 'code-manager'));
        }
    
        $default_snippets = json_decode($json, true);
        if (json_last_error() !== JSON_ERROR_NONE || !is_array($default_snippets)) {
            throw new \Exception(__('Invalid JSON format', 'code-manager'));
        }
    
        $current_snippets = get_option('cm_code_snippets', []);
        $existing_ids = array_flip(array_keys($current_snippets));
    
        foreach ($default_snippets as $snippet) {
            $snippet_id = isset($snippet['id']) ? $snippet['id'] : uniqid('cm_default_');
            
            // Force overwrite existing defaults 
            if ($force && isset($current_snippets[$snippet_id]['is_default'])) {
                unset($current_snippets[$snippet_id]);
            }
    
            if (!isset($current_snippets[$snippet_id])) {
                $current_snippets[$snippet_id] = [
                    'name' => sanitize_text_field($snippet['name']),
                    'type' => in_array($snippet['type'], ['css', 'js', 'php']) ? $snippet['type'] : 'css',
                    'code' => isset($snippet['code']) ? $snippet['code'] : '',
                    'active' => false,
                    'created' => current_time('mysql'),
                    'is_default' => true
                ];
                
                error_log('[Code Manager] Added default snippet: ' . $snippet_id);
            }
        }
    
        update_option('cm_code_snippets', $current_snippets);
        update_option('cm_defaults_installing', current_time('mysql'));
    }

    public static function install_default_snippets() {
        $installing_time = get_option('cm_defaults_installing', null);
        $default_snippets_version = get_option('cm_default_snippets_version', '0.0.0');

        if ($default_snippets_version !== self::DEFAULT_SNIPPETS_VERSION || !$installing_time) {
            self::load_default_snippets(true); // Force overwrite if versions mismatch or no install time
            update_option('cm_default_snippets_version', self::DEFAULT_SNIPPETS_VERSION);
        }
    }
}
