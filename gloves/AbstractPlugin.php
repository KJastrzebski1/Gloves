<?php

namespace Gloves;

include_once 'Config.php';

defined('ABSPATH') or die('No script kiddies please!');

abstract class AbstractPlugin
{
    /*
     * list of modules
     * 
     * @var array
     */

    protected static $modules;

    /**
     * list of database models
     *
     * @var array
     */
    protected static $models;

    /**
     * list of settings
     *
     * @var array
     */
    protected static $settings;

    private function __construct()
    {
    }

    /*
     * Modules initialization
     */

    public static function init()
    {


        $main = new \ReflectionClass(get_called_class());

        $dir = $main->getFileName();
        $class = get_called_class();

        register_activation_hook($dir, array($class, "activate"));
        register_deactivation_hook($dir, array($class, "deactivate"));
        register_uninstall_hook($dir, array($class, "uninstall"));
        array_push(static::$settings, 'installed');
        PluginSettings::add(static::$settings);
        PluginSettings::init();
        Render::init(dirname($dir));

        foreach (static::$modules as $module => $args) {
            $module = '\Module\\' . $module;
            if (method_exists($module, 'init')) {
                $module::init($args);
            }
        }
        foreach (static::$models as $model) {
            $model = '\Model\\' . $model;

            if (method_exists($model, 'create')) {
                $model::create();
            }
        }
        add_action('plugins_loaded', array($class, 'lang'));
    }

    public static function lang()
    {
        $main = new \ReflectionClass(get_called_class());

        $dir = $main->getFileName();
        $domain = Config::get('text-domain');
        $lang = Config::get('lang-directory');
        load_plugin_textdomain($domain, false, $dir . '/' . $lang);
    }

    public static function activateOnce()
    {
        Logger::write('activateOnce');
        foreach (static::$modules as $module => $args) {
            $module = '\Module\\' . $module;
            if (method_exists($module, 'activateOnce')) {
                $module::activateOnce($args);
            }
        }
    }

    public static function activate()
    {
        Logger::write('activate');
        foreach (static::$modules as $module => $args) {
            $module = '\Module\\' . $module;
            if (method_exists($module, 'activate')) {
                $module::activate($args);
            }
        }
        if (!PluginSettings::get('installed')) {
            static::activateOnce();
            PluginSettings::set('installed', 1);
        }
    }

    public static function deactivate()
    {
        Logger::write('deactivate');
        foreach (static::$modules as $module => $args) {
            $module = '\Module\\' . $module;
            if (method_exists($module, 'deactivate')) {
                $module::deactivate($args);
            }
        }
        PluginSettings::unregister();
        foreach (static::$models as $module) {
            $module = '\Model\\' . $module;
            Logger::write("dropping");
            if (method_exists($module, 'drop')) {
                $module::drop();
            }
        }
    }

    public static function uninstall()
    {
        Logger::write('uninstall');
        foreach (static::$modules as $module => $args) {
            $module = '\Module\\' . $module;
            if (method_exists($module, 'uninstall')) {
                $module::uninstall($args);
            }
        }
    }
}
