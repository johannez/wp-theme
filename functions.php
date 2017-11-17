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

// Autoload our classes
spl_autoload_register(function ($class) {
    $file_path = __DIR__ . '/src/' . $class . '.php';

    if (file_exists($file_path)) {
        require_once $file_path;
    }
});

Timber::$dirname = array('templates', 'views');

// Initialize the global site and settings.
$site = new Site();
