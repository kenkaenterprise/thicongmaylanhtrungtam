<?php

namespace Phangia\App;

use Phangia\App\Enum;
use Timber\Menu;
use Phangia\App\Helper\MenuItem\Data;
/**
 * Class Core
 * @package Phangia\App
 */
class Core extends \Timber\Site {

	/**
	 * @var string
	 */
	protected $identifier = 'Phangia';

	/**
	 * @var \Phangia\App\Loader
	 */
	protected $loader;

	/**
	 * @var string
	 */
	protected $version = '1.0';

	/**
	 * Core constructor.
	 */
	public function __construct( $site_name_or_id = null ) {
		add_filter( 'timber/context', [$this, 'add_to_context'] );
		add_filter( 'timber/twig', [$this, 'add_to_twig'] );
		add_action( 'init', [$this, 'register_post_types'] );
		add_action( 'init', [$this, 'register_taxonomies'] );
		add_action( 'timber/twig/functions', [ $this, 'add_func_to_twig' ] );

		$this->loader = new \Phangia\App\Loader;
		//$this->define_admin_hooks();
		$this->define_public_hooks();
		new \Phangia\App\Admin\Main( $this->name, $this->version );
		parent::__construct( $site_name_or_id );
	}

	/**
	 * @return void
	 */
	public function register_post_types() {

	}

	/**
	 * @return void
	 */
	public function register_taxonomies() {

	}

	/**
	 * @return void
	 */
	public function execute() {
		$this->loader->execute();
	}

	/**
	 * @param array $context context['this'] Being the Twig's {{ this }}.
	 *
	 * @return array
	 */
	public function add_to_context( $context ) {
		$config = \Phangia\App\Config::get_instance();
		// Primary menu, footer menu, footer menu 2, menu links
		if ( $config->get_config( 'load_config' ) === 'Yes' ) {
			$context[ 'primary_menu' ] = new Menu( $config->get_config( 'primary_menu' ) );
			$context[ 'footer_menu' ] = new Menu( $config->get_config( 'footer_menu' ) );
			$context[ 'footer_menu_2' ] = new Menu( $config->get_config( 'footer_menu_2' ) );
			$context[ 'footer_menu_3' ] = new Menu( $config->get_config( 'footer_menu_3' ) );
			$context[ 'menu_links' ] = new Menu( $config->get_config( 'menu_links' ) );
		} else {
			$context[ 'primary_menu' ] = new Menu( Enum::MENU_PRIMARY );
			$context[ 'footer_menu' ] = new Menu( Enum::MENU_FOOTER );
			$context[ 'footer_menu_2' ] = new Menu( Enum::MENU_FOOTER_2 );
			$context[ 'footer_menu_3' ] = new Menu( Enum::MENU_FOOTER_3 );
			$context[ 'menu_links' ] = new Menu( Enum::MENU_LINKS );
		}


		// Logo
		$custom_logo_id = get_theme_mod( 'custom_logo' );
		$logo = wp_get_attachment_image_src( $custom_logo_id , 'full' );
		$context[ 'logo' ] = $logo[0];



		$context[ 'theme_settings' ] = $config;


		// Partners
	/*	$settings_query = new \Timber\PostQuery( [
			'post_type'   => Enum::CPT_PARTNER,
			//'posts_per_page' => -1,
			//'nopaging' => true,
			'post_status' => [
				'publish'
			],
			'order'       => 'DESC'
		] );*/
		$context[ 'partners' ] = new \Timber\PostQuery( [
			'post_type'   => Enum::CPT_PARTNER,
			//'posts_per_page' => -1,
			//'nopaging' => true,
			'post_status' => [
				'publish'
			],
			'order'       => 'DESC'
		] );

		// Slider
		$context[ 'sliders' ] = new \Timber\PostQuery( [
			'post_type' => Enum::CPT_SLIDER,
			'post_status' => [
				'publish'
			],
			'order'       => 'DESC'
		] );

		// Figures
		$context[ 'figures' ] = new \Timber\PostQuery( [
			'post_type' => Enum::CPT_FIGURE,
			'post_status' => [
				'publish'
			],
			'order'       => 'DESC'
		] );
		$home_query = new \Timber\PostQuery([
			'post_type' => 'frontpage',
		]);
		$home = $home_query->get_posts();
		$context['home'] =  reset($home);

		//$context['settings'] = $settings[ count($settings) - 1 ] ?? [];
		/*Sliders*/


		$context['site']               = $this;
		return $context;
	}

	/**
	 * @param \Twig\Environment $twig
	 * @return \Twig\Environment
	 */
	public function add_to_twig( $twig ) {
		/**
		 * Add gettext __ functions to twig functions.
		 */
//		$function = new \Twig\TwigFunction('__', '__');
//		$twig->addFunction($function);
		$twig->addExtension( new \Twig\Extension\StringLoaderExtension() );
		$fn = new \Timber\Twig_Function('get_theme_setting', function($key) {
			$result = '';
			$config = \Phangia\App\Config::get_instance();
			$result = $config->get_config($key);

			return $result;
		});
		$twig->addFunction($fn);
		$fn = new \Timber\Twig_Function( 'is_item_active', function ( \Timber\MenuItem $item, $post ) {
			if ( ! $post ) {
				return false;
			}

			if ( $post instanceof \Timber\Post ) {
				$current_post_id  = $post->id;
				$master_object_id = $item->master_object()->id;

				return $master_object_id == $current_post_id;
			}

			return false;
		} );
		$twig->addFunction( $fn );

		return $twig;
	}

	/**
	 * Adds functions to Twig.
	 *
	 * @param \Twig\Environment $twig The Twig Environment.
	 * @return \Twig\Environment
	 */
	public function add_func_to_twig( $twig ) {
		//$twig->addFunction(new Twig_Function('function', array(&$this, 'exec_function')));
		return $twig;
	}
	/**
	 * @param string|int $menu_id
	 *
	 * @return array
	 */
	public function get_nav_menu( $menu_id ) {
		$sorted_menu_items        = [];
		$menu_items_with_children = [];

		// Get the nav menu based on the requested menu.
		$menu = wp_get_nav_menu_object( $menu_id );

		$menu_items = wp_get_nav_menu_items( $menu->term_id, [ 'update_post_term_cache' => false ] );
		foreach ( (array) $menu_items as $menu_item ) {
			$sorted_menu_items[ $menu_item->menu_order ] = $menu_item;
			if ( $menu_item->menu_item_parent ) {
				$menu_items_with_children[ $menu_item->menu_item_parent ] = true;
			}
		}
		// Add the menu-item-has-children class where applicable.
		if ( $menu_items_with_children ) {
			foreach ( $sorted_menu_items as &$menu_item ) {
				if ( isset( $menu_items_with_children[ $menu_item->ID ] ) ) {
					$menu_item->classes[] = 'menu-item-has-children';
				}
			}
		}

		return apply_filters( 'wp_nav_menu_objects', $sorted_menu_items );
	}

	/**
	 * @param int|string $item_parent
	 * @param array $source_items
	 *
	 * @return array
	 */
	public function get_menu_items_level2( $item_parent, $source_items ) {
		// If has children, return array of children

		$result = [];
		foreach ( $source_items as $item ) {
			$parent = $item->menu_item_parent;
			// Skip level 1
			if ( ! $parent ) {
				continue;
			}
			if ( $parent == $item_parent ) {
				$result[]  = $item;
			}
		}

		return $result;
	}

	/**
	 * @param int $item_parent
	 * @param array $source_items
	 *
	 * @return bool
	 */
	public function should_show_dropdown_class( $item_parent, $source_items  ) {
		$result = $this->get_menu_items_level2( $item_parent, $source_items );

		return count($result) > 0;
	}

	/**
	 * @param \WP_Post $item
	 *
	 * @return bool
	 */
	public function is_menu_item_our_product( $item ) {
		return $item->post_name == Enum::MENU_ITEM_OUR_PRODUCT;
	}

	/**
	 * @param \WP_Post $item
	 *
	 * @return bool
	 */
	public function is_menu_item_family( $item ) {
		return $item->post_name == ENUM::MENU_ITEM_FAMILY;
	}

	/**
	 * @param \WP_Post $item
	 *
	 * @return bool
	 */
	public function is_menu_item_vitamin( $item ) {
		return $item->post_name == ENUM::MENU_ITEM_VITAMIN;
	}

	/**
	 * @param string $current_url
	 * @param string $parent
	 *
	 * @return bool
	 */
	public function should_parent_active( $current_url, $parent ) {
		$array_pages = [];
		if ($parent == 'our-product') {
			$array_pages = [
				\Wtabbottcecon\App\Helper\MenuItem\Data::get_link_ascorbic_acid_cecon_junior()['url'],
				\Wtabbottcecon\App\Helper\MenuItem\Data::get_link_ascorbic_acid_cecon()['url'],
				\Wtabbottcecon\App\Helper\MenuItem\Data::get_link_multi_zinc_cecon_plus()['url'],
				get_site_url() . '/all-products'
			];
		} elseif ($parent == 'family') {
			$array_pages = [
				\Wtabbottcecon\App\Helper\MenuItem\Data::get_link_causes_of_weak_immunity​()['url'],
				\Wtabbottcecon\App\Helper\MenuItem\Data::get_link_tips_to_stay_protected_outdoors()['url'],
				\Wtabbottcecon\App\Helper\MenuItem\Data::get_link_tips_to_care_for_your_family_health()['url'],
				\Wtabbottcecon\App\Helper\MenuItem\Data::get_link_tips_to_stick_to_healthier_habits()['url'],
				\Wtabbottcecon\App\Helper\MenuItem\Data::get_link_multi_zinc_overral_wellbeing()['url'],
				\Wtabbottcecon\App\Helper\MenuItem\Data::get_link_effect_of_vitamin_c_deficiency_inactivity()['url'],
				\Wtabbottcecon\App\Helper\MenuItem\Data::get_link_effect_of_vitamin_c_deficiency_fatigue()['url'],
				\Wtabbottcecon\App\Helper\MenuItem\Data::get_link_healthy_habits_to_teach_children()['url'],
				\Wtabbottcecon\App\Helper\MenuItem\Data::get_link_tips_to_help_children_adapt_to_homeschooling()['url'],
				\Wtabbottcecon\App\Helper\MenuItem\Data::get_link_games_to_play_with_children()['url']
			];
		} elseif ($parent == 'vitamin') {
			$array_pages = [
				\Wtabbottcecon\App\Helper\MenuItem\Data::get_link_vitamin_c_immunity()['url'],
				\Wtabbottcecon\App\Helper\MenuItem\Data::get_link_multi_zinc_overral_wellbeing()['url']
			];
		}

		foreach ($array_pages as $item) {
			if (trim($item, '/') == $current_url) {
				return true;
			}
		}

		return false;
	}

	/**
	 * @param string $key
	 * @param \WP_Post $post
	 *
	 * @return string
	 */
	public function get_attr_val( $key, $post ) {
		// Get
		$result = get_field( $key, $post->ID );
		// Validate if exist.
		if ( $result ) {
			// Remove whitespace.
			// LowerCase and return.
			$result = strtolower( $result );
			$result = str_replace( ' ', '', $result );

			return preg_replace( "/[!@+#$\(\)%^&*]/", '', $result );
		}

		return '';
	}

	private function define_public_hooks() {
		$main = new \Phangia\App\Frontend\Main( $this->identifier, $this->version );
		//$this->loader->add_filter( 'timber_context', $main, 'modify_timber_context' );
	}

	private function define_admin_hooks() {
		$main = new \Phangia\App\Admin\Main( $this->identifier, $this->version );
		//$this->loader->add_action( 'admin_enqueue_scripts ', $main, 'admin_enqueue_scripts' );
		//$this->loader->add_action( 'admin_menu', $main, 'admin_menu' );
	}
}