<?php

namespace Gloves;
defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

abstract class Module{
    
    /*
     * acts always
     */
    public function init();
    
    /*
     * on plugins activation
     */
    public function activate();
    
    /*
     * on plugin's deactivation
     */
    public function deactivate();
    
    /*
     *  on plugin's uninstall
     */
    public function uninstall();
}