<?php

namespace Gloves;

defined( 'ABSPATH' ) or die( 'No script kiddies please!' );


abstract class PostType {
    
    use Module;
    
    protected $slug;
    protected $single;
    protected $plural;
    protected $labels;
    protected $args;
    protected $metaBoxe;


    protected static $instance;
    
    public static function getInstance(){
        return static::$instance;
    }
    
    public static function setup($postType = '', $single = '', $plural = '', $labels = array(), $args = array()){
        static::$instance = new static($postType , $single, $plural, $labels, $args);
    }
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
    
    public static function addMetaBox($metaBox){
        if(!class_exists($metaBox)){
            $metaBox = '\\Module\\'.$metaBox;
        }
        static::$instance->metaBox = new $metaBox(static::$instance);
    }
    
    public function register() {
        $plural = $this->plural;
        $single = $this->single;
        $postType = $this->slug;
        Logger::write($postType);
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
    
    public static function insert($title, $content = ''){
        if(post_exists($title)){
           $post = get_page_by_title($title, OBJECT, static::$instance->slug); 
           return $post->ID;
        }
        $post = array(
            'post_title' => $title,
            'post_content' => $content,
            'post_status' => 'publish',
            'post_type' => static::$instance->slug,
        );
        
        $id = wp_insert_post($post);
        return $id;
    }
    
    public static function getBy($field, $value){
        switch($field):
        case 'title':
            return get_page_by_title($value, OBJECT, static::$instance->slug);
        default :
            return false;
        endswitch;
        
    }
}
