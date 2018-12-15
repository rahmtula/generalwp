<?php
/**
 * Plugin Name: WooCommerce Add Fee Per Product
 * Plugin URI: 
 * Description: This plugins provides functionality to add fee per product bases.
 * Author: Rahmtula Ansari
 * Author URI: 
 * Version: 1.0.0
 * Text Domain: woo-product-fee
 * Domain Path: languages/
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'Woo_AddFeePerProduct' ) ) {

	/**
	 * WooCommerce Add Fee Per Product main class.
	 */
	class Woo_AddFeePerProduct {

		/**
		 * Plugin version.
		 *
		 * @var string
		 */
		const VERSION = '1.0.0';

		/**
		 * Instance of this class.
		 *
		 * @var object
		 */
		protected static $instance = null;

		/**
		 * Initialize the plugin.
		 */
		private function __construct() {

			// Checks with WooCommerce is installed.
			if ( class_exists( 'WooCommerce' ) ) {

				// Load plugin text domain
				add_action( 'init', array( $this, 'load_plugin_textdomain' ) );

				$this->define_constants();
				$this->includes();
				$this->init_hooks();
			}
		}

		/**
		 * Return an instance of this class.
		 *
		 * @return object A single instance of this class.
		 */
		public static function get_instance() {
			// If the single instance hasn't been set, set it now.
			if ( null == self::$instance ) {
				self::$instance = new self;
			}

			return self::$instance;
		}

		/**
		 * Load the plugin text domain for translation.
		 */
		public function load_plugin_textdomain() {
			load_plugin_textdomain( WOOAFPP_TEXTDOMAIN, false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
		}

		/**
		 * Define all Constants.
		 */
		private function define_constants() {

			if( ! defined( 'WOOAFPP_TEXTDOMAIN' ) ) {
				define( 'WOOAFPP_TEXTDOMAIN', 'woo-product-fee' );
			}
			if( ! defined( 'WOOAFPP_DIR' ) ) {
				define( 'WOOAFPP_DIR', dirname( __FILE__ ) );
			}
			if( !defined( 'WOOAFPP_ADMIN_DIR' ) ) {
				define( 'WOOAFPP_ADMIN_DIR', WOOAFPP_DIR .'/includes/admin' ); // plugin admin dir
			}
			if( ! defined( 'WOOAFPP_URL' ) ) {
				define( 'WOOAFPP_URL', plugin_dir_url( __FILE__ ) );
			}
			if( ! defined( 'WOOAFPP_BASENAME' ) ) {
				define( 'WOOAFPP_BASENAME', basename( WOOAFPP_DIR ) );
			}
		}

		/**
		 * Include required files.
		 */
		private function includes() {

			//Declares global variables
			global $wooafpp_admin, $wooafpp_scripts;

			//Load admin class
			include_once WOOAFPP_DIR .'/includes/class-wooafpp-scripts.php';
			$wooafpp_scripts = new WOOAFPP_Scripts();
			$wooafpp_scripts->add_hooks();

			//Load admin class
			include_once WOOAFPP_ADMIN_DIR .'/class-wooafpp-admin.php';
			$wooafpp_admin = new WOOAFPP_Admin();
			$wooafpp_admin->add_hooks();
		}

		/**
		 * Load Hooks.
		 */
		private function init_hooks() {
			add_action( 'woocommerce_cart_calculate_fees', array( $this, 'woo_cart_calculate_fees' ) );
		}

		/**
		 * Added fees based on products.
		 */
		public function woo_cart_calculate_fees() {

			//Get details
			$total_fees = 0;
			$cart_items = WC()->cart->get_cart();
			$options = get_option( 'wooafpp_options' );

			//Check if product exists in cart and option
			if( !empty( $cart_items ) && !empty( $options['product_fees'] ) ) {
				foreach ( $options['product_fees'] as $product_fee ) {

					//Check option properly added
					if( !empty( $product_fee['product_id'] )
						&& !empty( $product_fee['fee'] ) && is_numeric( $product_fee['fee'] ) ) {

						//Get product data
						$_product = wc_get_product( $product_fee['product_id'] );
						$fee_type = !empty( $product_fee['fee_type'] ) ? $product_fee['fee_type'] : 'fixed';

						//Check product exist in cart
						foreach ( $cart_items as $cart_key => $cart_item ) {

							//Get product id
							$cart_product_id = !empty( $cart_item['variation_id'] ) ? $cart_item['variation_id'] : $cart_item['product_id'];

							//calculate fee if product exists
							if( !empty( $cart_item['product_id'] ) && $cart_product_id == $product_fee['product_id'] ) {

								$total_fees += $fee_type == 'fixed' ? $product_fee['fee'] : ( $_product->get_price() * $product_fee['fee'] ) / 100;

								break;
							}
						}
					}
				}
			}

			//Finaly add total product fees to cart
			if( !empty( $total_fees ) ) {
				WC()->cart->add_fee( __('Product(s) Fees', WOOAFPP_TEXTDOMAIN ), $total_fees, true, 'standard' );
			}
		}
	}

	add_action( 'plugins_loaded', array( 'Woo_AddFeePerProduct', 'get_instance' ) );
}
