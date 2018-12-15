<?php
// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

/**
 * Scripts Class
 *
 * Handles adding scripts functionality.
 *
 * @package WooCommerce Add Fee Per Product
 * @since 1.0.0
 */
class WOOAFPP_Scripts {

	public function __construct() {}

	/**
	 * Admin enqueue scripts
	 */
	public function admin_enqueue_scripts() {

		//Regiter scripts
		wp_register_script( 'wooafpp-admin-scripts',  WOOAFPP_URL .'includes/js/wooafpp-admin.js', array('jquery'), false, true );
		
		//Loacalize script data
		wp_localize_script( 'wooafpp-admin-scripts', 'WOOAFPP_Data', array(
			'confirm_delete_msg' => __( 'Are you sure you want to delete ?', WOOAFPP_TEXTDOMAIN ),
		) );

		//Enqueue scripts
		wp_enqueue_script( 'wooafpp-admin-scripts' );
	}

	/**
	 * Adding Hooks
	 */
	public function add_hooks() {

		//Action to enqueue scripts
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_scripts' ) );
	}
}