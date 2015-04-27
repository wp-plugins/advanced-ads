<?php

if ( class_exists( 'Advanced_Ads', false ) ) {
	define( 'GADSENSE_BASE_PATH', plugin_dir_path( __FILE__ ) );
	define( 'GADSENSE_BASE_URL', plugins_url( basename( ADVADS_BASE_PATH ) . '/modules/' . basename( GADSENSE_BASE_PATH ) . '/' ) );
	define( 'GADSENSE_OPT_NAME', ADVADS_SLUG . '-adsense' );

	/**
	 * initialize ad type and add it to the plugins ad types
	 *
	 * "content" key must match the id
	 */
	function advads_add_ad_type_adsense($types) {
		$types['adsense'] = new Advads_Ad_Type_Adsense();
		return $types;
	}

	function gadsense_date_time($time) {
		return date_i18n( get_option( 'date_format' ), $time ) . __( ' at ', ADVADS_SLUG ) . date_i18n( get_option( 'time_format' ), $time );
	}

	Gadsense_Data::get_instance();
	add_filter( 'advanced-ads-ad-types', 'advads_add_ad_type_adsense' );

	if ( ( ! defined( 'DOING_AJAX' ) || ! DOING_AJAX) && is_admin() ) {
		Gadsense_Admin::get_instance();
	}
}
