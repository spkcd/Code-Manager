<?php
namespace Code_Manager\Public;

class CM_Public {
    public static function init() {
        add_action('wp_head', [__CLASS__, 'output_css_snippets']);
        add_action('wp_footer', [__CLASS__, 'output_js_snippets']);
    }

    public static function output_css_snippets() {
        $snippets = get_option('cm_code_snippets', []);
        $css = '';

        foreach ($snippets as $snippet) {
            if ($snippet['active'] && $snippet['type'] === 'css') {
                $css .= wp_strip_all_tags($snippet['code']) . "\n";
            }
        }

        if (!empty($css)) {
            echo "<style id='cm-css-snippets'>\n" . $css . "</style>\n";
        }
    }

    public static function output_js_snippets() {
        $snippets = get_option('cm_code_snippets', []);
        $js = '';

        foreach ($snippets as $snippet) {
            if ($snippet['active'] && $snippet['type'] === 'js') {
                $js .= wp_strip_all_tags($snippet['code']) . "\n";
            }
        }

        if (!empty($js)) {
            echo "<script id='cm-js-snippets'>\n" . $js . "</script>\n";
        }
    }
}
