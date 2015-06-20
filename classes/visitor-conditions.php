<?php

/**
 * visitor conditions under which to (not) show an ad
 *
 * @since 1.5.4
 *
 */
class Advanced_Ads_Visitor_Conditions {

	/**
	 *
	 * @var Advanced_Ads_Visitor_Conditions
	 */
	protected static $instance;

	/**
	 * registered visitor conditions
	 */
	public $conditions;

	/**
	 * start of name in form elements
	 */
	const FORM_NAME = 'advanced_ad[visitors]';

	public function __construct() {

	    // register conditions
	    $this->conditions = apply_filters( 'advanced-ads-visitor-conditions', array(
			'mobile' => array( // type of the condition
			'label' => __( 'mobile device', ADVADS_SLUG ),
			'description' => __( 'Display ads only on mobile devices or hide them.', ADVADS_SLUG ),
			'metabox' => array( 'Advanced_Ads_Visitor_Conditions', 'metabox_is_or_not' ), // callback to generate the metabox
			'check' => array( 'Advanced_Ads_Visitor_Conditions', 'check_mobile' ) // callback for frontend check
			),
	    ));
	}

	/**
	 *
	 * @return Advanced_Ads_Plugin
	 */
	public static function get_instance() {
		// If the single instance hasn't been set, set it now.
		if ( null === self::$instance ) {
			self::$instance = new self;
		}

		return self::$instance;
	}

	/**
	 * callback to display the "is not" condition
	 *
	 * @param arr $options options of the condition
	 * @param int $index index of the condition
	 */
	static function metabox_is_or_not( $options, $index = 0 ){

	    if ( ! isset ( $options['type'] ) || '' === $options['type'] ) { return; }

	    $type_options = self::get_instance()->conditions;

	    if ( ! isset( $type_options[ $options['type'] ] ) ) {
		    return;
	    }

	    // form name basis
	    $name = self::FORM_NAME . '[' . $index . ']';

	    // options
	    $operator = isset( $options['operator'] ) ? $options['operator'] : 'is';
	    $connector = isset( $options['connector'] ) ? $options['connector'] : 'and';

	    ?><p>
		<input type="hidden" name="<?php echo $name; ?>[type]" value="<?php echo $options['type']; ?>"/>
		<input type="hidden" name="<?php echo $name; ?>[connector]" value="<?php echo $connector; ?>"/>
		<label><?php
		    echo $type_options[ $options['type'] ]['label'];
		?><select name="<?php echo $name; ?>[operator]">
		<option value="is" <?php selected( 'is', $operator ); ?>><?php _e( 'is' ); ?></option>
		<option value="is_not" <?php selected( 'is_not', $operator ); ?>><?php _e( 'is not' ); ?></option>
	    </select></label></p><?php
	}

	/**
	 * callback to display the any condition based on a number
	 *
	 * @param arr $options options of the condition
	 * @param int $index index of the condition
	 */
	static function metabox_number( $options, $index = 0 ){

	    if ( ! isset ( $options['type'] ) || '' === $options['type'] ) { return; }

	    $type_options = self::get_instance()->conditions;

	    if ( ! isset( $type_options[ $options['type'] ] ) ) {
		    return;
	    }

	    // form name basis
	    $name = self::FORM_NAME . '[' . $index . ']';

	    // options
	    $value = isset( $options['value'] ) ? $options['value'] : 0;
	    $operator = isset( $options['operator'] ) ? $options['operator'] : 'is_equal';
	    $connector = isset( $options['connector'] ) ? $options['connector'] : 'and';

	    ?><p>
		<input type="hidden" name="<?php echo $name; ?>[type]" value="<?php echo $options['type']; ?>"/>
		<input type="hidden" name="<?php echo $name; ?>[connector]" value="<?php echo $connector; ?>"/>
		<label><?php echo $type_options[ $options['type'] ]['label'];
		?><select name="<?php echo $name; ?>[operator]">
		    <option value="is_equal" <?php selected( 'is_equal', $operator ); ?>><?php _e( 'equal', ADVADS_SLUG ); ?></option>
		    <option value="is_higher" <?php selected( 'is_higher', $operator ); ?>><?php _e( 'equal or higher', ADVADS_SLUG ); ?></option>
		    <option value="is_lower" <?php selected( 'is_lower', $operator ); ?>><?php _e( 'equal or lower', ADVADS_SLUG ); ?></option>
		</select></label><input type="number" name="<?php echo $name; ?>[value]" value="<?php echo absint( $value ); ?>"/></p><?php
	}

	/**
	 * controls frontend checks for conditions
	 *
	 * @param arr $options options of the condition
	 * @return bool false, if ad can’t be delivered
	 */
	static function frontend_check( $options = array() ){
		$visitor_conditions = Advanced_Ads_Visitor_Conditions::get_instance()->conditions;

		if ( is_array( $options ) && isset( $visitor_conditions[ $options['type'] ]['check'] ) ) {
			$check = $visitor_conditions[ $options['type'] ]['check'];
		} else {
			return true;
		}

		// call frontend check callback
		if ( method_exists( $check[0], $check[1] ) ) {
			return call_user_func( array( $check[0], $check[1] ), $options );
		}

		return true;
	}

	/**
	 * check mobile visitor condition in frontend
	 *
	 * @param arr $options options of the condition
	 * @return bool true if can be displayed
	 */
	static function check_mobile( $options = array() ){

	    if ( ! isset( $options['operator'] ) ) {
			return true;
	    }

	    switch ( $options['operator'] ){
		    case 'is' :
			    if ( ! wp_is_mobile() ) { return false; }
			    break;
		    case 'is_not' :
			    if ( wp_is_mobile() ) { return false; }
			    break;
	    }

	    return true;
	}
}

