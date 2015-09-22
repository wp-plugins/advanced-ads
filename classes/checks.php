<?php

/**
 * checks for various things
 *
 * @since 1.6.9
 */
class Advanced_Ads_Checks {

	/**
	 * php version minimum 5.3
	 *
	 * @return bool true if 5.3 and higher
	 */
	 public static function php_version_minimum(){

		if (version_compare(phpversion(), '5.3', '>=')) {
			return true;
		}

		return false;
	 }

	/**
	 * caching used
	 *
	 * @return bool true if active
	 */
	 public static function cache(){

		if( ( defined( 'WP_CACHE' ) && WP_CACHE ) // general cache constant
			|| defined('W3TC') // W3 Total Cache
			|| function_exists( 'wp_super_cache_text_domain' ) // WP SUper Cache
			|| class_exists('zencache\\plugin') // ZenCache
		){
			return true;
		}

		return false;
	 }

	 /**
	  * WordPress update available
	  *
	  * @return bool true if WordPress update available
	  */
	 public static function wp_update_available(){

		$update_data = wp_get_update_data();
		$count = absint( $update_data['counts']['wordpress'] );

		if( $count ){
			return true;
		}

		return false;
	 }

	 /**
	  * any plugin updates available
	  *
	  * @return bool true if plugin updates are available
	  */
	 public static function plugin_updates_available(){

		$update_data = wp_get_update_data();
		$count = absint( $update_data['counts']['plugins'] );

		if( $count ){
			return true;
		}

		return false;
	 }

	 /**
	  * check if license keys are missing or invalid
	  *
	  * @since 1.6.6
	  * @update 1.6.9 moved from Advanced_Ads_Plugin
	  * @return true if there are missing licenses
	  */
	public static function licenses_invalid(){

	    $add_ons = apply_filters( 'advanced-ads-add-ons', array() );

	    if( $add_ons === array() ) {
		    return false;
	    }

	    foreach( $add_ons as $_add_on_key => $_add_on ){
		    $status = get_option($_add_on['options_slug'] . '-license-status', false);

		    // don’t check if license is valid
		    if( $status === 'valid' ) {
			    continue;
		    }

		    // retrieve our license key from the DB
		    $licenses = get_option(ADVADS_SLUG . '-licenses', array());

		    $license_key = isset($licenses[$_add_on_key]) ? $licenses[$_add_on_key] : false;

		    if( ! $license_key || $status !== 'valid' ){
			    return true;
		    }
	    }

	    return false;
	}

	/**
	 * check if license keys are going to expire within next 14 days
	 *
	 * @since 1.6.6
	 * @update 1.6.9 moved from Advanced_Ads_Plugin
	 * @return true if there are expiring licenses
	 */
	public static function licenses_expire(){

	    $add_ons = apply_filters( 'advanced-ads-add-ons', array() );

	    if( $add_ons === array() ) {
		    return false;
	    }

	    $now = time();

	    foreach( $add_ons as $_add_on_key => $_add_on ){
		    // don’t display error for invalid licenses
		    if( get_option($_add_on['options_slug'] . '-license-status', false) === 'invalid' ) {
			    continue;
		    }

		    $expiry_date = get_option($_add_on['options_slug'] . '-license-expires', false);

		    if( $expiry_date ){
			    $expiry_date_t = strtotime( $expiry_date );
			    $in_two_weeks = time() + ( WEEK_IN_SECONDS * 2) ;
			    // check if expiry date is within next comming 2 weeks
			    if( $expiry_date_t < $in_two_weeks && $expiry_date_t >= $now ){
				    return true;
			    }

		    }
	    }

	    return false;
	}

	/**
	 * check if license keys are already expired
	 *
	 * @since 1.6.6
	 * @update 1.6.9 moved from Advanced_Ads_Plugin
	 * @return true if there are expired licenses
	 */
	public static function licenses_expired(){

	    $add_ons = apply_filters( 'advanced-ads-add-ons', array() );

	    if( $add_ons === array() ) {
		    return false;
	    }

	    $now = time();

	    foreach( $add_ons as $_add_on_key => $_add_on ){
		    // don’t display error for invalid licenses
		    if( get_option($_add_on['options_slug'] . '-license-status', false) === 'invalid' ) {
			    continue;
		    }

		    $expiry_date = get_option($_add_on['options_slug'] . '-license-expires', false);

		    if( $expiry_date && strtotime( $expiry_date ) < $now ){
			    return true;
		    }
	    }

	    return false;
	}

	/**
	 * Autoptimize plugin installed
	 *   can change ad tags, especially inline css and scripts
	 *
	 * @link https://wordpress.org/plugins/autoptimize/
	 * @return bool true if Autoptimize is installed
	 */
	public static function active_autoptimize(){

		if( defined( 'AUTOPTIMIZE_CACHE_DIR' ) ){
			return true;
		}

		return false;
	}
}