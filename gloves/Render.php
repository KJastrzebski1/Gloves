<?php

namespace Gloves;

defined('ABSPATH') or die('No script kiddies please!');


/**
 * Manages views
 */
class Render {

    protected static $dir;

    private function __construct() {
        ;
    }

    public static function init($dir) {
        static::$dir = $dir . '/' . Config::get('views-directory');
    }

    public static function view($dir, $context = null) {
        $dir = str_replace('.', '/', $dir);
        include static::$dir . '/' . $dir . '.php';
    }
    
    public static function get($dir, $context = null){
        $dir = str_replace('.', '/', $dir);
        ob_start();
        include static::$dir . '/' . $dir . '.php';
        $content = ob_get_clean();
        return $content;
    }

}
