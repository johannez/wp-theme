<?php

class Site extends TimberSite {
    use SiteHelper;

    public function __construct() {
        add_theme_support('post-formats');
        add_theme_support('post-thumbnails');
        add_theme_support('menus');
        add_theme_support('html5',['comment-list', 'comment-form', 'search-form', 'gallery', 'caption']);

        add_filter('timber_context', [$this, 'addToContext']);
        add_filter('get_twig', [$this, 'addToTwig']);
        add_filter('login_headerurl', [$this, 'logoUrl']);
        add_filter('default_hidden_meta_boxes', [$this, 'hideMetaBox'], 10, 2);

        add_action('init', [$this, 'removeRoles']);
        add_action('init', [$this, 'editorStyles']);
        add_action('login_head', [$this, 'loginStylesheet']);

        // Add ACF options page.
        //acf_add_options_page();

        $this->registerPostTypes();
        $this->addShortCodes();
        $this->addImageSizes();
        $this->routes();

        parent::__construct();
    }

    public function registerPostTypes()
    {
//        add_action('init', [$this, 'registerPerson']);
//        add_action('init', [$this, 'registerNews']);
//        add_action('init', [$this, 'registerEvent']);
    }

    public function addShortCodes()
    {
//        add_shortcode('team_listing', [$this, 'indexPerson']);
//        add_shortcode('news_listing', [$this, 'indexNews']);
//        add_shortcode('event_listing', [$this, 'indexEvent']);
    }

    public function addImageSizes() {
//        add_image_size('page-banner', 1600, 510, true);
//        add_image_size('page-banner-md', 1100, 450, true);
//        add_image_size('page-banner-sm', 600, 450, true);
    }

    public function hideMetaBox($hidden, $screen) {
        // $post_types = [
        //     'person'
        // ];

        // if ( ('post' == $screen->base) && in_array($screen->id, $post_types) ){
        //     //lets hide everything
        //     $hidden = [
        //         'person_rolesdiv'
        //     ];

        // }
        // return $hidden;
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

    public function addToContext( $context ) {
        $main_menu = new \TimberMenu();

        $context['menu']['main'] = $main_menu->get_items();
        $context['menu']['mobile'] = $main_menu->get_items();

        // Add ACF options.
        //$context['options'] = get_fields('options');

        $context['site'] = $this;
        return $context;
    }

    public function addToTwig( $twig ) {

        $twig->addExtension( new Twig_Extension_StringLoader() );

        // Convert all internal absolute links into relative links.
        $twig->addFilter('relative_links', new Twig_SimpleFilter('relative_links', array($this, 'relativeLinks')));

        return $twig;
    }

    public function removeRoles() {
        if (get_role('author')) {
            remove_role('author');
        }

        if (get_role('contributor')) {
            remove_role('contributor');
        }

        if (get_role('subscriber')) {
            remove_role('subscriber');
        }
    }

}
