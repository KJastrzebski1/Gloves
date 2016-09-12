<?php

namespace Gloves;

defined('ABSPATH') or die('No script kiddies please!');
/**
 * Manages plugin options
 * 
 */
class PluginSettings {

    protected static $settings = [
        'installed',
    ];

    public static function init() {
        add_action('admin_init', array('\Gloves\PluginSettings', 'register'));
    }

    /**
     * On admin_init hook
     */
    public static function register() {
        $domain = Config::get('text-domain') . '-settings';
        $slug = Config::get('text-domain');
        if (isset(static::$settings)) {
            foreach (static::$settings as $name) {
                \register_setting($domain, $slug.'-'.$name);
            }
        }
    }

    /**
     * On plugin deactivation
     */
    public static function unregister() {
        $domain = Config::get('text-domain') . '-settings';
        $slug = Config::get('text-domain');
        if (isset(static::$settings)) {
            foreach (static::$settings as $name) {
                \unregister_setting($domain, $slug.'-'.$name);
                \delete_option($slug.'-'.$name);
            }
        }
    }

    /**
     * Adds array of settings
     * 
     * @param array $settings
     */
    public static function add(array $settings) {
        foreach ($settings as $option) {
            static::$settings[] = $option;
        }
    }

    public static function get($option) {
        $slug = Config::get('text-domain');
        return \get_option($slug.'-'.$option);
    }

    public static function set($option, $value) {
        $slug = Config::get('text-domain');
        return \update_option($slug.'-'.$option, $value);
    }

}
