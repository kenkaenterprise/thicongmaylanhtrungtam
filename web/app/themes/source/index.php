<?php

/*
 * The main template file
*/
use Phangia\App\Enum;
global $paged;
if (!isset($paged) || !$paged){
	$paged = 1;
}
if (!class_exists(\Timber\Timber::class)) {
    throw new \Exception(__('Error, theme dependencies are not installed.'));
}

$context           = \Timber\Timber::context();

$context['features']   = new \Timber\PostQuery([
	'post_type' => 'post',
	'post_status' => [
		'publish'
	],
	'order'       => 'DESC',

	'paged' => $paged,
	'post__in' => get_option( 'sticky_posts' ),
	'posts_per_page' => 20
]);

$templates         = [ 'index.twig' ];
if ( is_home() ) {
	array_unshift( $templates,  'front-page.twig', 'home.twig'  );
}
\Timber\Timber::render( $templates, $context, false, \Timber\Loader::CACHE_NONE );