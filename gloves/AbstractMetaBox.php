<?php

namespace Gloves;

defined('ABSPATH') or die('No script kiddies please!');

/**
 * Adds meta box to given post type
 */
abstract class AbstractMetaBox
{
    protected $postType;

    public function __construct(PostType $postType)
    {
        $this->postType = $postType;
        
        add_action('add_meta_boxes', array($this, 'add'));
        add_action('save_post', array($this, 'save'));
    }
    
    public function add()
    {
        $postType = $this->postType;
        $names = $postType->getName();
        $id = $names['plural'] . '-meta-box-id';
        add_meta_box($id, $names['singular'] . ' data', array($this, 'init'), $postType->getSlug(), 'normal', 'high');
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
