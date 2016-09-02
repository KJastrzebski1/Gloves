<?php

namespace Gloves;

defined( 'ABSPATH' ) or die( 'No script kiddies please!' );
//include 'config.php';

abstract class PostType {
    protected $slug;
    protected $single;
    protected $plural;
    protected $labels;
    protected $args;
    /**
     * 
     * @param string $single
     * @param string $plural
     * @param array $labels TODO
     * @param array $args TODO
     * 
     */
    protected function __construct($postType , $single, $plural, $labels = array(), $args = array()) {
        $this->slug = $postType;
        $this->single = strtolower($single);
        $this->plural = strtolower($plural);
        $this->labels = $labels;
        $this->args = $args;
        
        add_action('init', array($this, 'register'));
    }
    
    public function register() {
        $plural = $this->plural;
        $single = $this->single;
        $postType = $this->slug;
        $textDomain = Config::get('text-domain');
        $dlabels = array(
            'name' => _x(ucfirst($plural), 'post type general name', $textDomain),
            'singular_name' => _x(ucfirst($single), 'post type singular name', $textDomain),
            'menu_name' => _x(ucfirst($plural), 'admin menu', $textDomain),
            'name_admin_bar' => _x(ucfirst($single), 'add new on admin bar', $textDomain),
            'add_new' => _x('Add new', '', $textDomain),
            'add_new_item' => __('Add new '.$single, $textDomain),
            'new_item' => __('New '.$single, $textDomain),
            'edit_item' => __('Edit '.$single, $textDomain),
            'view_item' => __('View '.$single, $textDomain),
            'all_items' => __('All '.$plural, $textDomain),
            'search_items' => __('Search '.$plural, $textDomain),
            'parent_item_colon' => __('Parent '.$plural.':', $textDomain),
            'not_found' => __('No '.$plural.' found.', $textDomain),
            'not_found_in_trash' => __('No '.$plural.' found in Trash.', $textDomain)
        );

        $dargs = array(
            'labels' => $dlabels,
            'description' => __('Description.', $textDomain),
            'public' => true,
            'publicly_queryable' => true,
            'show_ui' => true,
            'show_in_menu' => false,
            'query_var' => true,
            'rewrite' => array('slug' => $postType),
            'capability_type' => 'post',
            'has_archive' => true,
            'hierarchical' => false,
            'menu_position' => 105,
            'supports' => array('title', 'editor')
        );

        register_post_type($postType, $dargs);
    }
    public function getSlug(){
        return $this->slug;
    }
    public function getName(){
        return array(
            'singular' => $this->single,
            'plural' => $this->plural
        );
    }
}
