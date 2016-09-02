<?php

namespace Gloves;
defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

trait Module{
    
    /*
     * acts always
     */
    abstract static function init();
    
    /*
     * on plugins activation
     */
    abstract static function activate();
    
    /*
     * on plugin's deactivation
     */
    abstract static function deactivate();
    
    /*
     *  on plugin's uninstall
     */
    abstract static function uninstall();
}