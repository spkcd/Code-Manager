<?php
namespace Code_Manager\Public;

defined('ABSPATH') || exit;

class CM_Public {
    private static $snippets_option = 'cm_code_snippets';

    public static function init() {
        add_action('wp_enqueue_scripts', [__CLASS__, 'enqueue_snippets']);
    }

    public static function enqueue_snippets() {
        // Register dummy handles
        wp_register_style('code-manager', false, [], CM_VERSION);
        wp_register_script('code-manager', false, [], CM_VERSION, true);

        $snippets = get_option(self::$snippets_option, []);
        $css = '';
        $js = '';

        foreach ($snippets as $snippet) {
            if (!$snippet['active']) continue;

            if ('css' === $snippet['type']) {
                $css .= $snippet['code'] . "\n";
            } else {
                $js .= $snippet['code'] . "\n";
            }
        }

        if (!empty($css)) {
            wp_enqueue_style('code-manager');
            wp_add_inline_style('code-manager', $css);
        }

        if (!empty($js)) {
            wp_enqueue_script('code-manager');
            wp_add_inline_script('code-manager', $js);
        }
    }
}
