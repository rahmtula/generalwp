<?php
// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

/**
 * Admin Class
 *
 * Handles Admin functionality.
 *
 * @package WooCommerce Add Fee Per Product
 * @since 1.0.0
 */
class WOOAFPP_Admin {

	public function __construct() {
	}

	/**
	 * Add Menu Page
	 *
	 * Runs when the admin_menu hook fires and adds a new admin page.
	 */
	public function register_admin_menu() {

		//menu page
		$menu_page = add_submenu_page( 'woocommerce', __( 'Product Fee', WOOAFPP_TEXTDOMAIN ), __( 'Product Fee', WOOAFPP_TEXTDOMAIN ), 'manage_options', 'wooafpp_settings', array( $this,'add_menu_settings_page' ) );
	}

	/**
	 * Display settings Page.
	 */
	public function add_menu_settings_page() {
		include_once( WOOAFPP_ADMIN_DIR .'/forms/wooafpp-settings.php' );		
	}
	
	/**
	 * Show settings
	 */
	function register_plugin_options() {
		register_setting( 'wooafpp_settings_options', 'wooafpp_options', array( $this, 'validate_options' ) );
	}

	/**
	 * Validate settings
	 */
	public function validate_options( $input ){
		return $input;
	}

	/**
	 * Adding Hooks
	 */
	public function add_hooks() {

		//add submenu page
		add_action( 'admin_menu', array( $this, 'register_admin_menu' ) );

		//Add plugin options
		add_action( 'admin_init', array( $this, "register_plugin_options" ) );
	}
}