<?php

namespace Gloves;

defined('ABSPATH') or die('No script kiddies please!');

class PluginSettings {

    protected static $settings;

    public static function init() {
        add_action('admin_init', array('\Gloves\PluginSettings', 'register'));
    }

    public static function register() {
        $domain = Config::get('text-domain') . '-settings';
        if (isset(static::$settings)) {
            foreach (static::$settings as $name) {
                \register_setting($domain, $name);
            }
        }
    }

    public static function unregister() {
        $domain = Config::get('text-domain') . '-settings';
        if (isset(static::$settings)) {
            foreach (static::$settings as $name) {
                \unregister_setting($domain, $name);
                \delete_option($name);
            }
        }
    }

    public static function add($settings) {
        foreach ($settings as $option) {
            static::$settings[] = $option;
        }
    }

    public static function get($option) {
        return \get_option($option);
    }

    public static function set($option, $value) {
        return \update_option($option, $value);
    }

}
