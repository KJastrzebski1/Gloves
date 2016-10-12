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
    
    protected static $groupName;

    public static function init() {
        static::$groupName = Config::get('text-domain') . '-settings';
        add_action('admin_init', array('\Gloves\PluginSettings', 'register'));
    }

    /**
     * On admin_init hook
     */
    public static function register() {
        $domain = static::$groupName;
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
        $domain = static::$groupName;
        $slug = Config::get('text-domain');
        if (isset(static::$settings)) {
            foreach (static::$settings as $name) {
                \unregister_setting($domain, $slug.'-'.$name);
                \delete_option($slug.'-'.$name);
            }
        }
    }
    
    public static function generateView($id, $type, $classes=''){
        $domain = static::$groupName;
        $slug = Config::get('text-domain');
        echo "<input type='$type' id='$id' name='$slug-$id' class='$classes' value='".static::get($id)."' />";
    }
    
    public static function getSettingsGroup(){
        return static::$groupName;
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
    
    public static function getAll(){
        $options = array();
        $slug = Config::get('text-domain');
        foreach (static::$settings as $option){
            $options[$option] = \get_option($slug.'-'.$option);
        }
        return $options;
    }

    public static function set($option, $value) {
        $slug = Config::get('text-domain');
        return \update_option($slug.'-'.$option, $value);
    }

}
