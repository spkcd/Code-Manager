<?php
namespace Code_Manager\Includes;

defined('ABSPATH') || exit;

class CM_Loader {
    const DEFAULT_SNIPPETS_VERSION = '1.3.0';
    
    public static function init() {
        register_activation_hook(CM_PLUGIN_DIR . 'code-manager.php', [__CLASS__, 'activate_plugin']);
        
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

    private static function load_default_snippets() {
        $defaults_file = CM_PLUGIN_DIR . 'includes/default-snippets.json';
        
        if (!file_exists($defaults_file)) {
            error_log('Code Manager: Default snippets file missing');
            return;
        }

        $default_snippets = json_decode(file_get_contents($defaults_file), true);
        $existing_snippets = get_option('cm_code_snippets', []);
        $defaults_installed = get_option('cm_defaults_installed', '0');

        if (version_compare($defaults_installed, self::DEFAULT_SNIPPETS_VERSION, '<')) {
            foreach ($default_snippets as $snippet) {
                if (!isset($existing_snippets[$snippet['id']])) {
                    $existing_snippets[$snippet['id']] = [
                        'name' => sanitize_text_field($snippet['name']),
                        'type' => in_array($snippet['type'], ['css', 'js']) ? $snippet['type'] : 'css',
                        'code' => $snippet['code'],
                        'active' => (bool) $snippet['active'],
                        'created' => current_time('mysql'),
                        'is_default' => true
                    ];
                }
            }

            update_option('cm_code_snippets', $existing_snippets);
            update_option('cm_defaults_installed', self::DEFAULT_SNIPPETS_VERSION);
        }
    }
}
