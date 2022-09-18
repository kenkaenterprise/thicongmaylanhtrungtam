<?php
/*
* Template Name: Urinary health hub
*/
$context         = \Timber\Timber::context();
$context['title'] = 'Kết quả tìm kiếm từ khóa' . get_search_query();
$context['posts'] = new \Timber\PostQuery();
$context['keyword'] = get_search_query();
\Timber\Timber::render( [ 'news-template.twig' ], $context );
