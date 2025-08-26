<?php
defined('PLUGINPATH') or exit('No direct script access allowed');

/*
Plugin Name: live Custom CSS
Description: Permite editar CSS personalizado desde el CRM y aplicarlo en tiempo real en toda la aplicación.
Version: 1.0.1
Requires at least: 3.9.4
Author: M&S CRETORS S.A.S.
Author URL: https://myscreators.com/
*/

app_hooks()->add_filter('app_filter_admin_settings_menu', function ($settings_menu) {
    $settings_menu["setup"][] = array("name" => "live_custom_css", "url" => "live_custom_css_settings");
    return $settings_menu;
});

app_hooks()->add_filter('app_filter_action_links_of_Live_Custom_CSS', function ($links) {
    $links = array(
        anchor(get_uri("live_custom_css_settings"), app_lang("settings"))
    );
    return $links;
});

register_uninstallation_hook("Live_Custom_CSS", function () {
    $db = db_connect('default');
    $prefix = get_db_prefix();
    $setting = 'live_custom_css_code';
    $db->query("DELETE FROM `{$prefix}settings` WHERE `setting_name` = ?", [$setting]);
});

app_hooks()->add_action('app_hook_head_extension', function () {
    $css = get_setting('live_custom_css_code');
    if (!empty($css)) {
        $processed_css = process_css_for_priority($css);
        echo "\n<style id=\"live-custom-css\">\n" . $processed_css . "\n</style>\n";
    }
});

app_hooks()->add_action('app_hook_footer_extension', function () {
    $css = get_setting('live_custom_css_code');
    if (!empty($css)) {
        $processed_css = process_css_for_priority($css);
        echo "\n<style id=\"live-custom-css-footer\">\n" . $processed_css . "\n</style>\n";
    }
});

function process_css_for_priority($css) {
    $lines = explode("\n", $css);
    $processed_lines = [];
    $in_rule = false;
    $current_rule = '';
    
    foreach ($lines as $line) {
        $line = trim($line);
        
        if (empty($line) || strpos($line, '/*') === 0 || strpos($line, '//') === 0) {
            $processed_lines[] = $line;
            continue;
        }
        
        if (strpos($line, '{') !== false && !$in_rule) {
            $in_rule = true;
            $selector_part = trim(substr($line, 0, strpos($line, '{')));
            $rule_part = substr($line, strpos($line, '{'));
            
            $high_priority_selector = add_high_specificity($selector_part);
            $processed_lines[] = $high_priority_selector . ' ' . $rule_part;
            continue;
        }
        
        if (strpos($line, '}') !== false) {
            $in_rule = false;
            $processed_lines[] = $line;
            continue;
        }
        
        $processed_lines[] = $line;
    }
    
    return implode("\n", $processed_lines);
}

function add_high_specificity($selector) {
    $selector = trim($selector);
    
    if (strpos($selector, 'html body') === 0) {
        return $selector;
    }
    
    return 'html body ' . $selector;
}