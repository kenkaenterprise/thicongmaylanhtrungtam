<?php

use Timber\Post;

$context = \Timber\Timber::context();

$timber_post     = new Timber\Post();
$context['post'] = $timber_post;

$table_of_content = new \Phangia\App\TableOfContent($timber_post->get_content());
$templates = [
	'single-' . $timber_post->post_type . '.twig',
	'single.twig',
	'page-' . $timber_post->post_name . '.twig',
	'page.twig'
];
$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
$context['custom_content']=$table_of_content->get_content();
$context[ 'relate_posts' ] = new \Timber\PostQuery( [

	'post_type' => 'post',
	'cat' => !is_null($timber_post->category())? $timber_post->category()->id: null,
	'post__not_in' => [$timber_post->id],
	'orderby' => 'rand',
	'order'    => 'DESC',
	'posts_per_page' => 9,
	'paged' => $paged,

] );
$context[ 'other_posts' ] = new \Timber\PostQuery( [

    'post_type' => 'post',
    'cat__not_in' => !is_null($timber_post->category())? $timber_post->category()->id: null,
    'post__not_in' => [$timber_post->id],
    'orderby' => 'rand',
    'order'    => 'DESC',
    'posts_per_page' => 9,
    'paged' => $paged,

] );
$context['breadcrumbs'] = [];
$context['table_of_content'] = $table_of_content->get_index();
array_unshift($context['breadcrumbs'], [
  'link' => $timber_post->link(),
  'title' => $timber_post->get_title()
]);
array_unshift($context['breadcrumbs'], [
  'link' => '/' . $timber_post->category()->slug(),
  'title' => $timber_post->category()->title()
]);
array_unshift($context['breadcrumbs'], [
  'link' => '/',
  'title' => 'Trang chủ'
]);
\Timber\Timber::render( $templates, $context );



