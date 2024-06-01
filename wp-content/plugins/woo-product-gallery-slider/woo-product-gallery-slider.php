<?php

/**
 * @wordpress-plugin
 * Plugin Name:       Product Gallery Slider for WooCommerce
 * Plugin URI:        https://wordpress.org/plugins/woo-product-gallery-slider/
 * Description:       Best product image gallery slider for WooCommerce. It shows your WooCommerce products with an image carousel slider. Beautiful style, increase sales and get customer attention.
 * Version:           2.3.8
 * Author:            Codeixer
 * Author URI:        http://codeixer.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       woo-product-gallery-slider
 * Domain Path:       /languages
 * Tested up to: 6.5.3
 * WC requires at least: 3.9
 * WC tested up to: 8.9.1
 * Requires PHP: 7.4
 * Requires Plugin: WooCommerce
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}
require __DIR__ . '/vendor/autoload.php';

define( 'WPGS_NAME', 'Product Gallery Slider for Woocommerce' );
define( 'WPGS_INC', plugin_dir_path( __FILE__ ) . 'includes/' );
define( 'WPGS_ROOT', plugin_dir_path( __FILE__ ) . '' );
define( 'WPGS_ROOT_URL', plugin_dir_url( __FILE__ ) . '' );
define( 'WPGS_INC_URL', plugin_dir_url( __FILE__ ) . 'includes/' );
define( 'WPGS_PLUGIN_BASE', plugin_basename( __FILE__ ) );

/**
 * Initialize the plugin tracker
 *
 * @return void
 */
function appsero_init_tracker_woo_product_gallery_slider() {

	$client = new Appsero\Client( '862a2d3f-9bbf-42f4-a1ae-89a36cde4e79', 'Product Gallery Slider for WooCommerce', __FILE__ );

	// Active insights
	$client->insights()->init();
}

appsero_init_tracker_woo_product_gallery_slider();

NS7_RDNC::instance()->add_notification( 72, 'a9873a6e608e946e', 'https://www.codeixer.com' );

add_action(
	'before_woocommerce_init',
	function () {
		if ( class_exists( \Automattic\WooCommerce\Utilities\FeaturesUtil::class ) ) {
			\Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility( 'custom_order_tables', __FILE__, true );
		}
	}
);
final class CI_WPGS {

	/**
	 * Plugin version
	 *
	 * @var string
	 */
	const version = '2.3.7';

	private function __construct() {
		register_activation_hook( __FILE__, array( $this, 'activation' ) );
		register_deactivation_hook( __FILE__, array( $this, 'deactivation' ) );

		$this->define_constants();
		add_action( 'admin_init', array( 'PAnD', 'init' ) );
		add_action( 'woocommerce_loaded', array( $this, 'init_plugin' ), 30 );
		add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ) . '', array( $this, 'wpgs_plugin_row_meta' ) );
	}

	/**
	 * Add Pro version link into the plugin row meta
	 *
	 * @param  [type] $links
	 * @return void
	 */
	public function wpgs_plugin_row_meta( $links ) {
		$row_meta = array(
			'settings' => '<a href="' . admin_url( 'admin.php?page=cix-gallery-settings' ) . '">Settings</a>',
			'docs'     => '<a href="' . esc_url( 'https://www.codeixer.com/product-gallery-slider-for-woocommerce?utm_source=freemium&utm_medium=plugin-page&utm_campaign=upgrade_pro' ) . '" target="_blank" aria-label="' . esc_attr__( 'PRO Version', 'woo-product-gallery-slider' ) . '" style="color:#1da867;font-weight:600;">' . esc_html__( 'Get PRO Version', 'woo-product-gallery-slider' ) . '</a>',

		);

		return array_merge( $links, $row_meta );
	}

	/**
	 * Initialize the plugin
	 *
	 * @return void
	 */
	public function init_plugin() {
		new \Product_Gallery_Sldier\Bootstrap();
	}

	/**
	 * Run Codes on Plugin activation
	 *
	 * @return void
	 */
	public function activation() {
		$installed = get_option( 'ciwpgs_installed' );

		if ( ! $installed ) {
			update_option( 'ciwpgs_installed', date( 'Y/m/d' ) );
		}
		\WPGS_Variation_images::delete_transients();
	}
	public function deactivation(): void {
		\WPGS_Variation_images::delete_transients();
	}
	/**
	 * Define the required plugin constants
	 *
	 * @return void
	 */
	public function define_constants() {
		define( 'CIPG_VERSION', self::version );
		define( 'CIPG_FILE', __FILE__ );
		define( 'CIPG_PATH', __DIR__ );
		define( 'CIPG_URL', plugins_url( '', CIPG_FILE ) );
		define( 'CIPG_ASSETS', CIPG_URL . '/assets' );
	}

	/**
	 * Initializes a singleton instance
	 *
	 * @return $instance
	 */
	public static function init() {
		/**
		 * @var mixed
		 */
		static $instance = false;

		if ( ! $instance ) {
			$instance = new self();
		}

		return $instance;
	}
}

// kick-off the plugin
CI_WPGS::init();
