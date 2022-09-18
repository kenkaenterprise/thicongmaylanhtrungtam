<?php

namespace Phangia\App\Frontend;

/**
 * Class Main
 * @package Phangia\App\Frontend
 */
class Main {
	/**
	 * @var string
	 */
	private $name;
	/**
	 * @var string
	 */
	private $version;
	/**
	 * @var string
	 */
	protected $plugin_url;

	/**
	 * App constructor.
	 *
	 * @param $name
	 * @param $version
	 */
	public function __construct( $name, $version ) {
		$this->name    = $name;
		$this->version = $version;
		$theme = wp_get_theme();
		$this->plugin_url = $theme->get_stylesheet_directory_uri();
	}
}