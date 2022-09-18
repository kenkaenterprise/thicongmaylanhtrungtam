<?php
use Phangia\App\Enum;
$context = Timber::context();
$templates = [];
$timber_post     = new Timber\Post();
$context['post'] = $timber_post;
global $paged;
if (!isset($paged) || !$paged){
	$paged = 1;
}
$cat = false;
$config = \Phangia\App\Config::get_instance();

switch ($timber_post->id) {
	case Enum::P_CONTACT:
		array_unshift( $templates, 'contact.twig' );
		break;
	case Enum::P_PRODUCT:
		$context[ 'posts' ] = new \Timber\PostQuery( [
			'post_type' => Enum::CPT_PRODUCT,
			'post_status' => [
				'publish'
			],
			'order'       => 'DESC',
			'posts_per_page' => 12,
			'paged' => $paged
		] );
		break;
	case Enum::P_LAP_DAT_MAY_LANH:
		$cat = Enum::CAT_LAP_DAT_MAY_LANH;
		$context[ 'posts' ] = new \Timber\PostQuery( [
			'post_type' => 'post',
			'post_status' => [
				'publish'
			],
			'order'       => 'DESC',
			'posts_per_page' => 16,
			'paged' => $paged,
			'cat' => $cat
		] );
		break;
	case Enum::P_THI_CONG_ONG_DONG:
		$cat = Enum::CAT_THI_CONG_ONG_DONG;
		$context[ 'posts' ] = new \Timber\PostQuery( [
			'post_type' => 'post',
			'post_status' => [
				'publish'
			],
			'order'       => 'DESC',
			'posts_per_page' => 16,
			'paged' => $paged,
			'cat' => $cat
		] );
		break;
	case Enum::P_THI_CONG_ONG_GIO:
		$cat = Enum::CAT_THI_CONG_ONG_GIO;
		$context[ 'posts' ] = new \Timber\PostQuery( [
			'post_type' => 'post',
			'post_status' => [
				'publish'
			],
			'order'       => 'DESC',
			'posts_per_page' => 16,
			'paged' => $paged,
			'cat' => $cat
		] );
		break;
	case Enum::P_BAO_TRI_VE_SINH:
		$cat = Enum::CAT_BAO_TRI_VE_SINH;
		$context[ 'posts' ] = new \Timber\PostQuery( [
			'post_type' => 'post',
			'post_status' => [
				'publish'
			],
			'order'       => 'DESC',
			'posts_per_page' => 16,
			'paged' => $paged,
			'cat' => $cat
		] );
		break;
	case Enum::P_TIN_TUC:
		$cat = Enum::CAT_TIN_TUC;
		$context[ 'posts' ] = new \Timber\PostQuery( [
			'post_type' => 'post',
			'post_status' => [
				'publish'
			],
			'order'       => 'DESC',
			'posts_per_page' => 16,
			'paged' => $paged,
			'cat' => $cat
		] );
		break;
}

$templates = array_merge($templates, [
	'page-' . $timber_post->post_name . '.twig',
	'page-' . $timber_post->id . '.twig',
]);

if ($cat) {
	$templates[] = 'page-post-base.twig';
}
$templates[] = 'page.twig';
if ( $config->get_config( 'load_config' ) !== 'Yes' ) {
	if ($timber_post->id == $config->get_config('page_bao_tri_ve_sinh')) {
		array_unshift($templates, 'page-156.twig');
	} elseif ($timber_post->id == $config->get_config( 'page_lap_dat_may_lanh' )) {
		array_unshift( $templates, 'page-138.twig' );
	} elseif ($timber_post->id == $config->get_config( 'page_lien_he' ) ) {
		array_unshift( $templates, 'contact.twig' );
	} elseif ( $timber_post->id == $config->get_config( 'page_san_pham_noi_bat' ) ) {
		array_unshift( $templates, 'page-93.twig' );
	} elseif (  $timber_post->id == $config->get_config( 'page_thi_cong_ong_dong' ) ) {
		array_unshift( $templates, 'page-152.twig' );
	} elseif ( $timber_post->id == $config->get_config( 'page_thi_cong_ong_gio' ) ) {
		array_unshift( $templates, 'page-154.twig' );
	} elseif ( $timber_post->id == $config->get_config( 'page_tin_tuc' ) ) {
		array_unshift( $templates, 'page-158.twig' );
	}
}
$context['breadcrumbs'] = [];
array_unshift($context['breadcrumbs'], [
  'link' => $timber_post->link(),
  'title' => $timber_post->get_title()
]);
array_unshift($context['breadcrumbs'], [
  'link' => '/',
  'title' => 'Trang chủ'
]);

Timber::render( $templates, $context );