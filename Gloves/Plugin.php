<?php

namespace Gloves;

defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

abstract class Plugin {
    /*
     * list of modules
     * @var array
     */

    protected $modules;

    /**
     * Plugins main file directory
     * @var string
     */
    protected $dir;

    /*
     * Plugins main class
     * @var
     */
    protected $class;

    /*
     * @var array
     */
    protected $config;

    public function __construct() {
        
        $main = new \ReflectionClass(get_called_class());

        $dir = $main->getFileName();
        $class = get_called_class();
        
        register_activation_hook($dir, array($class, "activate"));
        register_deactivation_hook($dir, array($class, "deactivate"));
        register_uninstall_hook($dir, array($class, "uninstall"));
         
        $this->init();
    }

    /*
     * Modules initialization
     */

    protected function init() {
        include_once 'Config.php';
        foreach ($this->modules as $module => $args) {
            $module = '\Module\\' . $module;

            $module::init($args);
        }
        spl_autoload_unregister('autoload');

    }

    abstract public static function activate();

    abstract public static function deactivate();

    abstract public static function uninstall();
}
