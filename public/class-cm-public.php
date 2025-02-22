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
            if (!$snippet['active']) continue;

            if ('css' === $snippet['type']) {
                wp_add_inline_style('theme-style', $snippet['code']); // Assuming 'theme-style' is the main theme stylesheet handle
            } else {
                // JS snippets with page selector
                $page_id = isset($snippet['page_id']) ? absint($snippet['page_id']) : 0;
                if ($page_id > 0 && is_page($page_id)) {
                    wp_add_inline_script('jquery', $snippet['code']); // Enqueue JS snippets only on selected pages
                } else if ($page_id === 0) {
                    wp_add_inline_script('jquery', $snippet['code']); // Enqueue JS snippets on all pages if no page is selected
                }
            }
        }
    }
}
