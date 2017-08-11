<?php

if ( ! class_exists( 'Timber' ) ) {
	add_action( 'admin_notices', function() {
		echo '<div class="error"><p>Timber not activated. Make sure you activate the plugin in <a href="' . esc_url( admin_url( 'plugins.php#timber' ) ) . '">' . esc_url( admin_url( 'plugins.php') ) . '</a></p></div>';
	});
	
	add_filter('template_include', function($template) {
		return get_stylesheet_directory() . '/static/no-timber.html';
	});
	
	return;
}

Timber::$dirname = array('templates', 'views');

class StarterSite extends TimberSite {

	function __construct() {
		add_theme_support( 'post-formats' );
		add_theme_support( 'post-thumbnails' );
		add_theme_support( 'menus' );
		add_theme_support( 'html5', array( 'comment-list', 'comment-form', 'search-form', 'gallery', 'caption' ) );

		add_filter( 'timber_context', array( $this, 'addToContext' ) );
		add_filter( 'get_twig', array( $this, 'addToTwig' ) );

		// Add ACF options page.
        acf_add_options_page();

        $this->addImageSizes();
        $this->routes();

		parent::__construct();
	}

    public function addImageSizes() {
//        add_image_size('page-banner', 1600, 510, true);
//        add_image_size('page-banner-md', 1100, 450, true);
//        add_image_size('page-banner-sm', 600, 450, true);
    }

    protected function routes()
    {
        // Handle exposed filter form submits.
        Routes::map('forms/exposed-filters', function () {
            $filters = [];

            $valid_fields = [
                'filter_name'
            ];

            foreach ($_REQUEST as $name => $value) {
                if (!empty($value) && in_array($name, $valid_fields)) {
                    if (is_array($value)) {
                        $filters[$name] = implode('+', $value);
                    }
                    else {
                        $filters[$name] = $value;
                    }

                }
            }

            $redirect = $_REQUEST['redirect'];

            if ($filters) {
                $redirect .= '?' . http_build_query($filters);
            };

            // In case there are no files or the user doesn't have access.
            wp_redirect($redirect);
            exit();
        });
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

    public function relativeLinks( $text ) {
        $targets = [
            'http://live.example.com',
            'http://staging.example.com',
            'http://dev.example.com',
            'http://MY_SITE.local'
        ];

        $text = str_replace($targets, '', $text);

        return $text;
    }

	function addtoContext( $context ) {
        $main_menu = new \TimberMenu();
//        $meta_menu = new \TimberMenu(3);

        $context['menu']['main'] = $main_menu;
//        $context['menu']['meta'] = $meta_menu;

//        $main_menu_items = $main_menu->get_items();
//        $meta_menu_items = $meta_menu->get_items();

//        $context['menu']['mobile'] = array_merge($main_menu_items, $meta_menu_items);

		// Add ACF options.
        //$context['options'] = get_fields('options');

		$context['site'] = $this;
		return $context;
	}

	function myfoo( $text ) {
		$text .= ' bar!';
		return $text;
	}

	function addToTwig( $twig ) {
		/* this is where you can add your own functions to twig */
		$twig->addExtension( new Twig_Extension_StringLoader() );
		$twig->addFilter('myfoo', new Twig_SimpleFilter('myfoo', array($this, 'myfoo')));
		return $twig;
	}

}

new StarterSite();
