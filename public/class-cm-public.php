<?php
namespace Code_Manager\Public;

defined('ABSPATH') || exit;

class CM_Public {
    private static $snippets_option = 'cm_code_snippets';

    public static function init() {
        add_action('wp_enqueue_scripts', [__CLASS__, 'enqueue_snippets']);
    }

    public static function enqueue_snippets() {
        $snippets = get_option(self::$snippets_option, []);

        foreach ($snippets as $snippet) {
            if (!$snippet['active']) {
                continue;
            }

            // Check conditions
            $should_execute = false;
            switch ($snippet['condition_type']) {
                case 'none':
                    $should_execute = true;
                    break;
                case 'urls':
                    $current_url = $_SERVER['REQUEST_URI'];
                    foreach ($snippet['urls'] as $url) {
                        if (strpos($current_url, $url) !== false) {
                            $should_execute = true;
                            break;
                        }
                    }
                    break;
                case 'hook':
                    // PHP snippets with hooks are handled in CM_Admin::execute_php_snippets
                    break;
            }

            if ($should_execute) {
                if ($snippet['type'] === 'css') {
                    wp_add_inline_style('theme-style', $snippet['code']);
                } else if ($snippet['type'] === 'js') {
                    $page_id = isset($snippet['page_id']) ? absint($snippet['page_id']) : 0;
                    if ($page_id > 0 && is_page($page_id)) {
                        wp_add_inline_script('jquery', $snippet['code']);
                    } else if ($page_id === 0) {
                        wp_add_inline_script('jquery', $snippet['code']);
                    }
                }
            }
        }
    }
}
