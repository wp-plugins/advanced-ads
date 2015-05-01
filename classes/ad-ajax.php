<?php

/**
 * Provide public ajax interface.
 *
 * @since 1.5.0
 */
class Advanced_Ads_Ajax {

	private function __construct()
	{
		add_action( 'wp_ajax_advads_ad_select', array( $this, 'advads_ajax_ad_select' ) );
		add_action( 'wp_ajax_nopriv_advads_ad_select', array( $this, 'advads_ajax_ad_select' ) );
	}

	private static $instance;

	public static function get_instance()
	{
		if ( ! isset(self::$instance) ) {
			self::$instance = new self;
		}

		return self::$instance;
	}

	/**
	 * Simple wp ajax interface for ad selection.
	 *
	 * Provides a single ad given ID and selection method.
	 */
	public function advads_ajax_ad_select() {
		// set proper header
		header( 'Content-Type: application/json; charset: utf-8' );

		// init handlers
		$selector = Advanced_Ads_Select::get_instance();
		$methods = $selector->get_methods();
		$method = isset( $_REQUEST['ad_method'] ) ? (string) $_REQUEST['ad_method'] : null;
		$id = isset( $_REQUEST['ad_id'] ) ? (string) $_REQUEST['ad_id'] : null;
		$arguments = isset( $_REQUEST['ad_args'] ) ? (array) $_REQUEST['ad_args'] : array();

		$response = array();
		if ( isset( $methods[ $method ] ) && isset( $id ) ) {
			$content = $selector->get_ad_by_method( $id, $method, $arguments );
			$response = array( 'status' => 'success', 'item' => $content, 'id' => $id, 'method' => $method );
		} else {
			// report error
			$response = array( 'status' => 'error', 'message' => 'No valid ID or METHOD found.' );
		}

		echo json_encode( $response );
		die();
	}
}
