<?php

/**
 * Advanced Ads.
 *
 * @package   Advanced_Ads
 * @author    Thomas Maier <thomas.maier@webgilde.com>
 * @license   GPL-2.0+
 * @link      http://webgilde.com
 * @copyright 2013 Thomas Maier, webgilde GmbH
 */

/**
 * This class is used to bundle all ajax callbacks
 *
 * @package Advanced_Ads_Ajax_Callbacks
 * @author  Thomas Maier <thomas.maier@webgilde.com>
 */
class Advads_Ad_Ajax_Callbacks {

	public function __construct() {

		add_action( 'wp_ajax_load_content_editor', array( $this, 'load_content_editor' ) );
		add_action( 'wp_ajax_load_ad_parameters_metabox', array( $this, 'load_ad_parameters_metabox' ) );
		add_action( 'wp_ajax_advads-ad-group-ads-form', array( $this, 'load_ad_groups_ad_form' ) );
		add_action( 'wp_ajax_advads-ad-group-ads-form-save', array( $this, 'save_ad_groups_ad_form' ) );

	}

	/**
	 * load content of the ad parameter metabox
	 *
	 * @since 1.0.0
	 */
	public function load_ad_parameters_metabox() {

		$types = Advanced_Ads::get_instance()->ad_types;
		$type = $_REQUEST['ad_type'];
		$ad_id = absint( $_REQUEST['ad_id'] );
		if ( empty($ad_id) ) { wp_die(); }

		$ad = new Advads_Ad( $ad_id );

		if ( ! empty($types[$type]) && method_exists( $types[$type], 'render_parameters' ) ) {
			$types[$type]->render_parameters( $ad );
			?>
			<div id="advanced-ads-ad-parameters-size">
				<p><?php _e( 'size:', ADVADS_SLUG ); ?></p>
				<label><?php _e( 'width', ADVADS_SLUG ); ?><input type="number" size="4" maxlength="4" value="<?php echo isset($ad->width) ? $ad->width : 0; ?>" name="advanced_ad[width]">px</label>
				<label><?php _e( 'height', ADVADS_SLUG ); ?><input type="number" size="4" maxlength="4" value="<?php echo isset($ad->height) ? $ad->height : 0; ?>" name="advanced_ad[height]">px</label>
			</div>
			<?php
		}

		wp_die();

	}

	/**
	 * load the form to edit ads in an ad group
	 *
	 * @since 1.0.0
	 */
	public function load_ad_groups_ad_form(){
		$id = absint( $_POST['group_id'] );
		// load the group
		$group = new Advads_Ad_Group( $id );
		// get weights
		$weights = $group->get_ad_weights();
		// get group ads
		$ads = $group->get_all_ads();

		include_once(ADVADS_BASE_PATH . 'admin/views/ad-group-ads-inline-form.php');
		die();
	}

	/**
	 * save
	 *
	 * @since 1.0.0
	 */
	public function save_ad_groups_ad_form(){

		// load field values
		$fields = array();
		parse_str( $_POST['fields'], $fields );

		if ( ! wp_verify_nonce( $fields['advads-ad-groups-inline-form-nonce'], 'ad-groups-inline-edit-nonce' ) ) { die(); }

		// load the group
		$id = absint( $_POST['group_id'] );
		$group = new Advads_Ad_Group( $id );

		if ( ! isset($fields['weight']) ) { die(); }
		$group->save_ad_weights( $fields['weight'] );

		// returning the weights as an array
		header( 'Content-Type: application/json' );
		echo json_encode( $group->get_ad_weights() );

		die();
	}

}
