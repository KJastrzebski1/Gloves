<?php

namespace Gloves;

defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

class Config {
    
    protected static $config;

    private function __construct() {
        
    }
    
    public static function get($option){
        if(!isset(self::$config)){
            self::$config = parse_ini_file('/../config.ini');
        }
        return self::$config[$option];
    }
}
