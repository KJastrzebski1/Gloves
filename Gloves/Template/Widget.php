<?php
namespace Module\Template;

defined('ABSPATH') or die('No script kiddies please!');

use \Gloves\Config;
/**
 * Template file to create module by CLI
 */
class Widget extends \WP_Widget {

    function __construct() {
        $domain = Config::get('text-domain');
        $name = Config::get('name');
        parent::__construct(
                $domain.'_widget', __($name, $domain), array('description' => __('', $domain),)
        );
    }
    public static function init(){
        add_action('widgets_init', function() {
            register_widget(__CLASS__); 
        });
    }
    public function widget($args, $instance) {
        echo $args['before_widget'];
        ?>
        <div id="">

        </div>
        <?php
        echo $args['after_widget'];
    }

    public function form($instance) {
        
    }

    public function update($new_instance, $old_instance) {
        $instance = array();
        $instance['title'] = (!empty($new_instance['title']) ) ? strip_tags($new_instance['title']) : '';

        return $instance;
    }

}