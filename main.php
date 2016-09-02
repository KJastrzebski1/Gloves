<?php
/*
 * Plugin Name: 
 * Author: 
 * Text Domain: 
 * Version: 
 */


include_once 'autoloader.php';

use Gloves\Plugin;



class MyPlugin extends Plugin{
    
    protected $modules = [];


    public function __construct() {
        parent::__construct();
    }

    public static function activate() {
        ;
    }
    public static function deactivate() {
        ;
    }
    public static function uninstall() {
        ;
    }
}
$instance = new MyPlugin();