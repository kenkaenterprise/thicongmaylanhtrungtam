<?php

namespace Phangia\App\Admin;


use Phangia\App\Enum;

/**
 * Class Main
 * @package Phangia\App\Admin
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
	 * Main constructor.
	 *
	 * @param $name
	 * @param $version
	 */
	public function __construct( $name, $version ) {
		$this->name = 'admin_' . $name;
		$this->version = $version;
		\Phangia\App\Config::get_instance();
		//add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_scripts' ) );
		add_action( 'admin_menu', array( $this, 'admin_menu' ) );

	}

	/**
	 * @return void
	 * @param $hook
	 */
	public function admin_enqueue_scripts( $hook ) {
		// Load only on ?page = pgtheme_settings.
		if ( 'settings_page_pgtheme_settings' !== $hook ) {
			return;
		}
		$default = [
			'redirect_url' => get_admin_url(),
		];
		wp_enqueue_style( $this->name, get_stylesheet_directory() . '/assets/adminhtml/style.css', [], $this->version );
		// Styles.
		// Scripts.
		wp_register_script( $this->name, get_stylesheet_directory() . '/assets/adminhtml/main.js', ['jquery'], $this->version );
		wp_localize_script( $this->name, 'ural', $default );
	}

	public function pgtheme_settings() {
		echo '<div class="wrap" id="pgtheme">';
		echo '<h2>' . esc_html( __( 'Custom Settings', 'pgtheme' ) ) . '</h2>';
		echo '<div id="pgthemepoststuff"><div id="post-body">';

		$message = '';
		$error   = '';

		$config = \Phangia\App\Config::get_instance();
		$text_fields = $config->get_list();


		$pgtheme_options = get_option( Enum::THEME_OPTIONS );

		if ( isset( $_POST['pgtheme_form_submit'] ) ) {
			// check nonce
			if ( ! check_admin_referer( plugin_basename( __FILE__ ), 'pgtheme_nonce_name' ) ) {
				$error .= ' ' . __( 'Nonce check failed.', 'pgtheme' );
			}
			/* Update settings */




			foreach ( $text_fields as $field ) {
				$pgtheme_options[ $field[ 'name' ] ]         = isset( $_POST[ $field[ 'name' ] ] ) ? sanitize_text_field( wp_unslash( $_POST[ $field[ 'name' ] ] ) ) : '';

			}



			/* Update settings in the database */
			if ( empty( $error ) ) {
				update_option( Enum::THEME_OPTIONS, $pgtheme_options );
				$message .= __( 'Settings saved.', 'pgtheme' );
			} else {
				$error .= ' ' . __( 'Settings are not saved.', 'pgtheme' );
			}
		}

		?>

		<div class="updated fade" <?php echo empty( $message ) ? ' style="display:none"' : ''; ?>>
			<p><strong><?php echo esc_html( $message ); ?></strong></p>
		</div>
		<div class="error" <?php echo empty( $error ) ? 'style="display:none"' : ''; ?>>
			<p><strong><?php echo esc_html( $error ); ?></strong></p>
		</div>

		<div class="pgtheme-settings-container">
			<div class="pgtheme-settings-grid pgtheme-settings-main-cont">
				<form autocomplete="off" id="pgtheme_settings_form" method="post" action="">
					<input type="hidden" id="pgtheme-urlHash" name="pgtheme-urlHash" value="">
					<div class="pgtheme-tab-container" data-tab-name="pgtheme">
						<div class="postbox">
							<div class="inside">
								<table class="form-table">
									<?php




									$settings = [
										'settings' => $text_fields,
										'theme_options' => $pgtheme_options
									];
									?>
									<?= \Timber\Timber::compile( 'custom_settings.twig', $settings ) ?>
								</table>
								<p class="submit">
									<input type="submit" id="settings-form-submit" class="button-primary" value="<?php esc_attr_e( 'Save Changes', 'pgtheme' ); ?>" />
									<input type="hidden" name="pgtheme_form_submit" value="submit" />
									<?php wp_nonce_field( plugin_basename( __FILE__ ), 'pgtheme_nonce_name' ); ?>
								</p>
							</div>
						</div>
					</div>
				</form>
			</div>
		</div>

		<?php
		echo '</div></div>';
		echo '</div>';
	}


	public function admin_menu() {
		add_options_page(
			'Custom Theme Settings',
			'Custom Theme Settings',
			'manage_options',
			'pgtheme_settings',
			[$this, 'pgtheme_settings']
		);
	}
}

