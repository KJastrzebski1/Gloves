<?php

namespace Gloves;

defined('ABSPATH') or die('No script kiddies please!');

/**
 * Configuration handler for easier usage
 */
class Config
{
    
    protected static $config;

    private function __construct()
    {
    }
    
    public static function get($option)
    {
        if (!isset(self::$config)) {
            self::$config = include(__DIR__.'/../conf.php');
        }
        return self::$config[$option];
    }
}
