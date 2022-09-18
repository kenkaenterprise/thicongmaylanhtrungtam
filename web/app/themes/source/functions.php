<?php
/**
 * Functions and definitions
 */

/*Autoloading*/
$composer_autoload = __DIR__ . '/vendor/autoload.php';
if ( file_exists( $composer_autoload ) ) {
	require_once $composer_autoload;
	$timber = new \Timber\Timber();
}
/**
 * This ensures that Timber is loaded and available as a PHP class.
 * If not, it gives an error message to help direct developers on where to activate
 */
if ( !class_exists( 'Timber' ) ) {
	add_action( 'admin_notices', function( $template ) {
		return get_stylesheet_directory() . '/static/no-timber.html';
	} );
	return;
}

/**
 * Sets the directories (inside your theme) to find .twig files
 */
\Timber\Timber::$dirname = [ 'templates', 'views' ];

/**
 * By default, Timber does NOT autoescape values. Want to enable Twig's autoescape?
 * No prob! Just set this value to true
 */
Timber::$autoescape = false;

/*Initial plugin*/
$app  = new \Phangia\App\Core();
//$app->execute();
/*Theme setup*/
add_action('after_setup_theme', function() use ($app) {
	/*Enable plugins to manage the document title*/
	add_theme_support('title-tag');

	/*Enable gutenberg editor style*/
	add_theme_support( 'editor-styles' );

	/*Register navigation menus*/
	register_nav_menus([
		'primary_navigation' => 'Primary Navigation'
	]);

	/*Enable post thumbnails*/
	add_theme_support('post-thumbnails');

	/*Enable post thumbnails*/
	add_theme_support( 'custom-logo' );

	/*Enable HTML5 markup support*/
	add_theme_support('html5', ['caption', 'comment-form', 'comment-list', 'gallery', 'search-form']);

	/*Enable selective refresh for widgets in customizer*/
	add_theme_support('customize-selective-refresh-widgets');

}, 20);

add_action('widgets_init', function() {
	/*Register sidebars*/
	register_sidebar([
		'name'          => 'Primary Sidebar',
		'id'            => 'sidebar-primary',
		'before_widget' => '<div>',
		'after_widget'  => '</div>',
		'before_title'  => '<h3>',
		'after_title'   => '</h3>',
	]);
});

function thicongmaylanh_scripts() {
    // Loads our main stylesheet.
    wp_enqueue_style( 'thicongmaylanh-style', get_stylesheet_uri() );
}
add_action( 'wp_enqueue_scripts', 'thicongmaylanh_scripts' );