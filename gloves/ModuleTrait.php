<?php

namespace Gloves;

defined('ABSPATH') or die('No script kiddies please!');

trait ModuleTrait
{
    
    /*
     * acts always
     */
    abstract static public function init();
    
    /*
     * on plugins activation
     */
    abstract static public function activate();
    
    /*
     * on plugin's deactivation
     */
    abstract static public function deactivate();
    
    /*
     *  on plugin's uninstall
     */
    abstract static public function uninstall();
}
