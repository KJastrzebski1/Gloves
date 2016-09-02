<?php

namespace Gloves;

defined('ABSPATH') or die('No script kiddies please!');

abstract class AbstractMetaBox {
    protected $postType;

    public function __construct(PostType $postType) {
        $this->postType = $postType;
        
        add_action('add_meta_boxes', array($this, 'add'));
        add_action('save_post', array($this, 'save'));
        
    }
    
    public function add(){
        $postType = $this->postType;
        $names = $postType->getName();
        add_meta_box($names['plural'] . '-meta-box-id', $names['singular'] . ' data', array($this, 'init'), $postType->getSlug(), 'normal', 'high');
    }
    
    /**
     * Main function of meta box
     * 
     */
    abstract public function init();
    
    
    /**
     * On save action
     * 
     */
    abstract public function save($post_id);
}