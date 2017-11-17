<?php


trait SiteHelper
{
    public function logoUrl() {
        return home_url();
    }

    public function editorStyles() {
        add_editor_style( 'css/wysiwyg.css' );
    }

    public function loginStylesheet() {
        echo '<link rel="stylesheet" type="text/css" href="' . get_bloginfo('stylesheet_directory') . '/css/site.css" />';
    }

    public function getSubMenu($menu_items, $post_id)
    {
        $sub_menu = null;
        // Get the sub menu, if applicable.
        if ($current_item = $this->getMenuItemByPostId($menu_items, $post_id)) {

            if ($current_item->children) {
                $sub_menu = $current_item->children;

                if ($current_item->menu_item_parent) {
                    array_unshift($sub_menu, $current_item);
                }
            }
            else if ($current_item->menu_item_parent) {
                $parent_item = $this->getMenuItemByMenuId($menu_items, $current_item->menu_item_parent);
                $sub_menu = $parent_item->children;
                array_unshift($sub_menu, $parent_item);
            }

        }

        return $sub_menu;
    }

    public function getMenuItemByPostId($menu_items, $post_id)
    {
        $result = null;

        foreach ($menu_items as $item) {

            if ($item->object_id == $post_id) {
                $result = $item;
                break;
            }
            else if ($item->children) {
                if ($result = $this->getMenuItemByPostId($item->children, $post_id)) {
                    break;
                }
            }
        }

        return $result;
    }

    public function getMenuItemByMenuId($menu_items, $menu_id)
    {
        $result = null;

        foreach ($menu_items as $item) {
            if ($item->ID == $menu_id) {
                $result = $item;
                break;
            }
            else if ($item->children) {
                if ($result = $this->getMenuItemByMenuId($item->children, $menu_id)) {
                    break;
                }
            }
        }

        return $result;
    }

}