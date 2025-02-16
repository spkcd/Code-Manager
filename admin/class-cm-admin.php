<?php
namespace Code_Manager\Admin;

defined('ABSPATH') || exit;

class CM_Admin {
    private static $snippets_option = 'cm_code_snippets';

    public static function init() {
        add_action('admin_menu', array(__CLASS__, 'add_menu'));
        add_action('admin_enqueue_scripts', array(__CLASS__, 'enqueue_assets'));
        add_action('wp_ajax_cm_save_snippet', array(__CLASS__, 'save_snippet'));
        add_action('wp_ajax_cm_toggle_snippet', array(__CLASS__, 'toggle_snippet'));
        add_action('wp_ajax_cm_delete_snippet', array(__CLASS__, 'delete_snippet'));
        add_action('wp_ajax_cm_install_defaults', array(__CLASS__, 'install_default_snippets'));
        add_action('wp_ajax_cm_get_snippet', [__CLASS__, 'get_snippet']);
    }

    public static function install_default_snippets() {
        check_ajax_referer('cm_ajax_nonce', 'security');
    
        if (!current_user_can('manage_options')) {
            error_log('[Code Manager] Install Defaults - Unauthorized User: ' . get_current_user_id());
            wp_send_json_error(__('Unauthorized', 'code-manager'));
        }
    
        try {
            \Code_Manager\Includes\CM_Loader::load_default_snippets(true);
            error_log('[Code Manager] Successfully installed default snippets');
        } catch (\Exception $e) {
            error_log('[Code Manager] Install Error: ' . $e->getMessage());
            wp_send_json_error($e->getMessage());
        }
    
        wp_send_json_success();
    }
    


    public static function add_menu() {
        add_menu_page(
            __('Code Manager', 'code-manager'),
            __('Code Manager', 'code-manager'),
            'manage_options',
            'code-manager',
            array(__CLASS__, 'render_admin_page'),
            'dashicons-editor-code',
            6
        );
    }

    public static function enqueue_assets($hook) {
        if ('toplevel_page_code-manager' !== $hook) return;

        wp_enqueue_code_editor(array('type' => 'text/css'));
        wp_enqueue_script('wp-theme-plugin-editor');
        wp_enqueue_style('wp-codemirror');

        wp_enqueue_style(
            'cm-admin-css',
            CM_PLUGIN_URL . 'admin/css/code-manager-admin.css',
            array(),
            CM_VERSION
        );

        wp_enqueue_script(
            'cm-admin-js',
            CM_PLUGIN_URL . 'admin/js/code-manager-admin.js',
            array('jquery', 'wp-util', 'wp-i18n'),
            CM_VERSION,
            true
        );

        wp_localize_script('cm-admin-js', 'cmData', array(
            'ajaxUrl' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('cm_ajax_nonce'),
            'i18n' => array(
                'confirmDelete' => __('Are you sure you want to delete this snippet?', 'code-manager'),
                'confirmInstall' => __('This will install predefined snippets. Continue?', 'code-manager'),
                'installSuccess' => __('Default snippets installed successfully!', 'code-manager'),
                'installFailed' => __('Failed to install defaults.', 'code-manager'),
                'installing' => __('Installing...', 'code-manager'),
                'protectedSnippet' => __('Default snippets cannot be deleted', 'code-manager'),
                'editSnippet' => __('Edit', 'code-manager'),
                'updateSnippet' => __('Update Snippet', 'code-manager'),
                'saving' => __('Saving...', 'code-manager'),
                'saveFailed' => __('Failed to save snippet', 'code-manager')
            )
        ));

        wp_set_script_translations('cm-admin-js', 'code-manager', CM_PLUGIN_DIR . 'languages/');
    }

    public static function render_admin_page() {
        $snippets = get_option(self::$snippets_option, array());
        ?>
        <div class="wrap cm-admin-wrap">
            <h1><?php esc_html_e('Code Manager', 'code-manager'); ?></h1>
            
            <div class="cm-snippet-form">
                <h2><?php esc_html_e('Add New Snippet', 'code-manager'); ?></h2>
                <form id="cmAddSnippetForm">
                    <div class="cm-field-group">
                        <label for="cmSnippetName"><?php esc_html_e('Snippet Name:', 'code-manager'); ?></label>
                        <input type="text" id="cmSnippetName" required class="regular-text">
                    </div>
                    
                    <div class="cm-field-group">
                        <label for="cmSnippetType"><?php esc_html_e('Code Type:', 'code-manager'); ?></label>
                        <select id="cmSnippetType" required>
                            <option value="css">CSS</option>
                            <option value="js">JavaScript</option>
                        </select>
                    </div>

                    <div class="cm-field-group">
                        <label for="cmSnippetCode"><?php esc_html_e('Code:', 'code-manager'); ?></label>
                        <textarea id="cmSnippetCode" rows="10" class="widefat"></textarea>
                    </div>

                    <button type="submit" class="button button-primary">
                        <?php esc_html_e('Save Snippet', 'code-manager'); ?>
                    </button>
                </form>
            </div>

            <div class="cm-snippets-list">
                <h2><?php esc_html_e('Manage Snippets', 'code-manager'); ?></h2>
                <?php if (!empty($snippets)) : ?>
                <table class="wp-list-table widefat fixed striped">
                    <thead>
                        <tr>
                            <th><?php esc_html_e('Name', 'code-manager'); ?></th>
                            <th><?php esc_html_e('Type', 'code-manager'); ?></th>
                            <th><?php esc_html_e('Status', 'code-manager'); ?></th>
                            <th><?php esc_html_e('Actions', 'code-manager'); ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($snippets as $id => $snippet) : ?>
                        <tr data-snippet-id="<?php echo esc_attr($id); ?>">
                            <td>
                                <?php echo esc_html($snippet['name']); ?>
                                <?php if (!empty($snippet['is_default'])) : ?>
                                    <span class="cm-default-badge"><?php esc_html_e('Default', 'code-manager'); ?></span>
                                <?php endif; ?>
                            </td>
                            <td><?php echo strtoupper(esc_html($snippet['type'])); ?></td>
                            <td>
                                <div class="cm-toggle-switch">
                                    <input type="checkbox" 
                                        id="cmToggle<?php echo esc_attr($id); ?>" 
                                        <?php checked($snippet['active'], true); ?>>
                                    <label for="cmToggle<?php echo esc_attr($id); ?>"></label>
                                </div>
                            </td>
                            <td>
                                <button class="button cm-toggle-snippet">
                                    <?php esc_html_e('Toggle', 'code-manager'); ?>
                                </button>
                                <button class="button button-delete cm-delete-snippet">
                                    <?php esc_html_e('Delete', 'code-manager'); ?>
                                </button>
                            </td>
                            <td>
                                <button class="button cm-edit-snippet">
                                    <?php esc_html_e('Edit', 'code-manager'); ?>
                                </button>
                                <button class="button cm-toggle-snippet">
                                    <?php esc_html_e('Toggle', 'code-manager'); ?>
                                </button>
                                <?php if (empty($snippet['is_default'])) : // Show delete only for non-defaults ?>
                                    <button class="button button-delete cm-delete-snippet">
                                        <?php esc_html_e('Delete', 'code-manager'); ?>
                                    </button>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                <?php else : ?>
                    <p><?php esc_html_e('No snippets found. Add your first snippet above.', 'code-manager'); ?></p>
                <?php endif; ?>
            </div>

            <div class="cm-tools-section">
                <h2><?php esc_html_e('Tools', 'code-manager'); ?></h2>
                <button id="cm-install-defaults" class="button button-secondary">
                    <?php esc_html_e('Install Default Snippets', 'code-manager'); ?>
                </button>
                <p class="description">
                    <?php esc_html_e('Adds pre-configured snippets. Will preserve existing snippets.', 'code-manager'); ?>
                </p>
            </div>
        </div>
        <?php
    }

// Update save_snippet() to handle edits:
public static function save_snippet() {
    check_ajax_referer('cm_ajax_nonce', 'security');

    if (!current_user_can('manage_options')) {
        wp_send_json_error(__('Unauthorized', 'code-manager'));
    }

    $snippet_id = isset($_POST['snippet_id']) ? sanitize_key($_POST['snippet_id']) : uniqid('cm_');
    $snippets = get_option(self::$snippets_option, []);

    $snippets[$snippet_id] = [
        'name' => sanitize_text_field($_POST['name']),
        'type' => in_array($_POST['type'], ['css', 'js']) ? $_POST['type'] : 'css',
        'code' => self::sanitize_code($_POST['code'], $_POST['type']),
        'active' => isset($_POST['active']) ? (bool) $_POST['active'] : false,
        'created' => isset($snippets[$snippet_id]['created']) ? $snippets[$snippet_id]['created'] : current_time('mysql'),
        'modified' => current_time('mysql')
    ];

    update_option(self::$snippets_option, $snippets);
    wp_send_json_success($snippet_id);
}


    private static function sanitize_code($code, $type) {
        if ('css' === $type) {
            return wp_strip_all_tags($code);
        }
        return preg_replace('/<script\b[^>]*>(.*?)<\/script>/is', '', $code);
    }

    public static function toggle_snippet() {
        check_ajax_referer('cm_ajax_nonce', 'security');

        if (!current_user_can('manage_options')) {
            wp_send_json_error(__('Unauthorized', 'code-manager'));
        }

        $snippet_id = sanitize_key($_POST['snippet_id']);
        $snippets = get_option(self::$snippets_option, array());

        if (isset($snippets[$snippet_id])) {
            $snippets[$snippet_id]['active'] = !$snippets[$snippet_id]['active'];
            $snippets[$snippet_id]['modified'] = current_time('mysql');
            update_option(self::$snippets_option, $snippets);
            wp_send_json_success();
        }

        wp_send_json_error(__('Snippet not found', 'code-manager'));
    }

    public static function delete_snippet() {
        check_ajax_referer('cm_ajax_nonce', 'security');

        if (!current_user_can('manage_options')) {
            wp_send_json_error(__('Unauthorized', 'code-manager'));
        }

        $snippet_id = sanitize_key($_POST['snippet_id']);
        $snippets = get_option(self::$snippets_option, array());

        if (isset($snippets[$snippet_id]['is_default']) && $snippets[$snippet_id]['is_default']) {
            wp_send_json_error(__('Default snippets cannot be deleted', 'code-manager'));
        }

        if (isset($snippets[$snippet_id])) {
            unset($snippets[$snippet_id]);
            update_option(self::$snippets_option, $snippets);
            wp_send_json_success();
        }

        wp_send_json_error(__('Snippet not found', 'code-manager'));
    }

    public static function get_snippet() {
        check_ajax_referer('cm_ajax_nonce', 'security');
    
        if (!current_user_can('manage_options')) {
            wp_send_json_error(__('Unauthorized', 'code-manager'));
        }
    
        $snippet_id = isset($_GET['snippet_id']) ? sanitize_key($_GET['snippet_id']) : '';
        $snippets = get_option(self::$snippets_option, []);
    
        if (!isset($snippets[$snippet_id])) {
            wp_send_json_error(__('Snippet not found', 'code-manager'));
        }
    
        wp_send_json_success($snippets[$snippet_id]);
    }
}