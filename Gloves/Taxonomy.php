<?php

namespace Gloves;

defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

abstract class Taxonomy {

    protected $slug;
    protected $single;
    protected $plural;
    protected $labels;
    protected $args;

    protected function __construct($taxonomy, $single, $plural, $labels = array(), $args = array()) {
        $this->slug = $taxonomy;
        $this->single = strtolower($single);
        $this->plural = strtolower($plural);
        $this->labels = $labels;
        $this->args = $args;
        
        add_action('init', array($this, 'register'));
    }

    public function register() {
        $plural = $this->plural;
        $single = $this->single;
        $textDomain = Config::get('text-domain');
        $labels = array(
            'name' => _x(ucfirst($plural), 'taxonomy general name', $textDomain),
            'singular_name' => _x(ucfirst($single), 'taxonomy singular name', $textDomain),
            'search_items' => __('Search '.ucfirst($plural), $textDomain),
            'popular_items' => __('Popular '.ucfirst($plural), $textDomain),
            'all_items' => __('All '.ucfirst($plural), $textDomain),
            'parent_item' => null,
            'parent_item_colon' => null,
            'edit_item' => __('Edit '.ucfirst($single), $textDomain),
            'update_item' => __('Update '.ucfirst($single), $textDomain),
            'add_new_item' => __('Add New '.ucfirst($single), $textDomain),
            'new_item_name' => __('New Writer '.ucfirst($single), $textDomain),
            'separate_items_with_commas' => __('Separate '.$plural.' with commas', $textDomain),
            'add_or_remove_items' => __('Add or remove '.$plural, $textDomain),
            'choose_from_most_used' => __('Choose from the most used '.$plural, $textDomain),
            'not_found' => __('No '.$plural.' found.', $textDomain),
            'menu_name' => __(ucfirst($plural), $textDomain),
        );
        $args = array(
            'hierarchical' => false,
            'labels' => $labels,
            'show_ui' => true,
            'show_admin_column' => true,
            'update_count_callback' => '_update_post_term_count',
            'query_var' => true,
            'rewrite' => array('slug' => $this->slug),
        );
        register_taxonomy(
            $this->slug, 'calendar_event', $args
        );
    }

}
