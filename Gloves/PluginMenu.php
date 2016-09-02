<?php

namespace Gloves;

defined('ABSPATH') or die('No script kiddies please!');

class PluginMenu {

    protected static $viewDir;
    protected static $instance;
    protected static $page;
    protected static $subpages;

    private function __construct() {
        
    }

    public static function getInstance() {
        if (!isset(static::$instance)) {
            static::$instance = new static();
        }
        return static::$instance;
    }

    public static function init($view) {
        static::$viewDir = $view;

        add_action('admin_menu', array('\Gloves\PluginMenu', 'create'));
        add_filter('parent_file', array('\Gloves\PluginMenu', 'filter'));
    }

    public static function create() {
        $domain = Config::get('text-domain');
        static::$page['file'] = $domain;
        $name = Config::get('name');
        static::$page['id'] = add_menu_page($name, $name, 'administrator', $domain, array('\Gloves\PluginMenu', 'view'));
        foreach (static::$subpages as &$page) {
            $page['id'] = (\add_submenu_page($domain, $page['title'], $page['title'], 'administrator', $page['link']));
        }
    }

    /**
     * 
     * 
     * @global type $submenu_file
     * @global type $current_screen
     * @global type $pagenow
     * @return type
     */
    public static function filter() {
        global $submenu_file, $current_screen, $pagenow;
        $parent_file = null;

        foreach (static::$subpages as $page) {
            Logger::write($page['type']);
            if ($current_screen->post_type === $page['post_type']) {

                if ($pagenow == 'post.php' && $page['type'] == "PostType") {
                    $submenu_file = $page['link'];
                }

                if ($pagenow == 'edit-tags.php' && $page['type'] == "Taxonomy") {

                    $submenu_file = $page['link'];
                }

                $parent_file = static::$page['file'];
            }
        }


        return $parent_file;
    }

    public static function view() {
        Render::view(static::$viewDir);
    }

    /**
     * Add page based on the object of Taxonomy or PostType
     * 
     * @param type $title
     * @param object $type
     */
    public static function addPage($title, $type) {
        $objType;
        $postType;
        $link;
        $i = count(static::$subpages);
        static::$subpages[$i]['title'] = $title;
        if ($type instanceof Taxonomy) {
            $objType = "Taxonomy";
            $postType = $type->getPostType();
            $link = "edit-tags.php?taxonomy=" . $type->getSlug() . "&post_type=" . $postType;
        } elseif ($type instanceof PostType) {
            $objType = "PostType";
            $postType = $type->getSlug();
            $link = "edit.php?post_type=" . $postType;
        }
        static::$subpages[$i]['type'] = $objType;
        static::$subpages[$i]['link'] = $link;
        static::$subpages[$i]['post_type'] = $postType;
    }

}
