<?php
namespace Code_Manager\Admin;

class CM_Admin {
    public static function init() {
        add_action('admin_menu', [__CLASS__, 'add_admin_menu']);
        add_action('admin_enqueue_scripts', [__CLASS__, 'enqueue_assets']);
        add_action('wp_ajax_cm_save_snippet', [__CLASS__, 'save_snippet']);
    }

    public static function add_admin_menu() {
        add_menu_page(
            __('Code Manager', 'code-manager'),
            __('Code Manager', 'code-manager'),
            'manage_options',
            'code-manager',
            [__CLASS__, 'render_admin_page'],
            'dashicons-editor-code'
        );
    }

    public static function render_admin_page() {
        $snippets = get_option('cm_code_snippets', []);
        ?>
        <div class="wrap cm-admin-wrap">
            <h1><?php esc_html_e('Code Manager', 'code-manager'); ?></h1>
            
            <div class="cm-snippet-form">
                <h2><?php esc_html_e('Add New Snippet', 'code-manager'); ?></h2>
                <form id="cmAddSnippetForm">
                    <div>
                        <label for="cmSnippetName"><?php esc_html_e('Snippet Name:', 'code-manager'); ?></label>
                        <input type="text" id="cmSnippetName" required>
                    </div>
                    
                    <div>
                        <label for="cmSnippetType"><?php esc_html_e('Code Type:', 'code-manager'); ?></label>
                        <select id="cmSnippetType" required>
                            <option value="css">CSS</option>
                            <option value="js">JavaScript</option>
                        </select>
                    </div>

                    <div>
                        <label for="cmSnippetCode"><?php esc_html_e('Code:', 'code-manager'); ?></label>
                        <textarea id="cmSnippetCode" rows="10" required></textarea>
                    </div>

                    <?php wp_nonce_field('cm_save_snippet', 'cm_nonce'); ?>
                    <button type="submit" class="button button-primary">
                        <?php esc_html_e('Save Snippet', 'code-manager'); ?>
                    </button>
                </form>
            </div>

            <div class="cm-snippets-list">
                <h2><?php esc_html_e('Manage Snippets', 'code-manager'); ?></h2>
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
                            <td><?php echo esc_html($snippet['name']); ?></td>
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
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
        <?php
    }

    public static function enqueue_assets($hook) {
        if ('toplevel_page_code-manager' !== $hook) return;

        wp_enqueue_style(
            'cm-admin-css',
            CM_PLUGIN_URL . 'admin/css/code-manager-admin.css',
            [],
            CM_VERSION
        );

        wp_enqueue_script(
            'cm-admin-js',
            CM_PLUGIN_URL . 'admin/js/code-manager-admin.js',
            ['jquery'],
            CM_VERSION,
            true
        );

        wp_localize_script('cm-admin-js', 'cmData', [
            'ajaxUrl' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('cm_ajax_nonce')
        ]);
    }

    public static function save_snippet() {
        check_ajax_referer('cm_ajax_nonce', 'security');

        if (!current_user_can('manage_options')) {
            wp_send_json_error(__('Unauthorized', 'code-manager'));
        }

        $snippets = get_option('cm_code_snippets');
        $snippet_id = uniqid('cm_');

        $snippets[$snippet_id] = [
            'name' => sanitize_text_field(wp_unslash($_POST['name'])),
            'type' => sanitize_text_field($_POST['type']),
            'code' => $_POST['type'] === 'css' 
                ? sanitize_textarea_field(wp_unslash($_POST['code']))
                : wp_unslash($_POST['code']),
            'active' => false,
            'created' => current_time('mysql')
        ];

        update_option('cm_code_snippets', $snippets);
        wp_send_json_success($snippet_id);
    }
}

// Add this to the enqueue_assets function
public static function enqueue_assets($hook) {
    if ('toplevel_page_code-manager' !== $hook) return;

    // Initialize code editor
    wp_enqueue_code_editor([
        'type' => 'text/css',
        'codemirror' => [
            'mode' => 'css',
            'lint' => true,
            'autoCloseBrackets' => true,
            'matchBrackets' => true,
        ]
    ]);

    // Add these dependencies
    wp_enqueue_script('wp-theme-plugin-editor');
    wp_enqueue_style('wp-codemirror');

    // ... rest of existing enqueue code ...
}

