<?php

/**
 * Abstracts ad selection.
 *
 * The class allows to modify 'methods' (named callbacks) to provide ads
 * through `advanced-ads-ad-select-methods` filter.
 * This can be used to replace default methods, wrap them or add new ones.
 *
 * Further allows to provide ad selection attributes
 * through `advanced-ads-ad-select-args` filter to influence behaviour of the
 * selection method.
 * Default methods have a `override` attribute that allows to replace the
 * content. This may be used to defer or skip ad codes dynamically.
 *
 * @since 1.5.0
 */
class Advanced_Ads_Select {

	protected $methods;

	private function __construct() {}

	/**
	 *
	 * @var Advanced_Ads_Select
	 */
	private static $instance;

	/**
	 *
	 * @return Advanced_Ads_Select
	 */
	public static function get_instance()
	{
		if ( ! isset(self::$instance) ) {
			self::$instance = new self;
		}

		return self::$instance;
	}

	/**
	 *
	 * @return array
	 */
	public function get_methods()
	{
		if ( ! isset($this->methods) ) {
			$methods = array(
				'id' => array( $this, 'get_ad_by_id' ),
				'group' => array( $this, 'get_ad_by_group' ),
				'placement' => array( $this, 'get_ad_by_placement' ),
			);

			$this->methods = apply_filters( 'advanced-ads-ad-select-methods', $methods );
		}

		return $this->methods;
	}

	/**
	 * Advanced ad selection methods should not directly rely on
	 * current environment factors.
	 * Prior to actual ad selection the meta is provided to allow for
	 * serialised, proxied or otherwise defered selection workflows.
	 *
	 * @return array
	 */
	public function get_ad_arguments( $method, $id, $args = array() )
	{
		$args = (array) $args;

		if ( ! isset($args['method']) ) $args['method'] = $method;
		if ( ! isset($args['id']) ) $args['id'] = $id;

		$args = apply_filters( 'advanced-ads-ad-select-args', $args );

		return $args;
	}

	public function get_ad_by_method( $id, $method, $args = array() ) {

		$methods = $this->get_methods();
		if ( ! isset($methods[ $method ]) ) {
			return ;
		}
		if ( ! advads_can_display_ads() ) {
			return ;
		}
		$args = $this->get_ad_arguments( $method, $id, $args );

		return call_user_func( $methods[ $method ], $args );
	}

	// internal
	public function get_ad_by_id($args) {
		if ( ! isset($args['id']) || $args['id'] == 0 ) {
			return isset($args['override']) ? $args['override'] : null;
		}

		// get ad
		$ad = new Advanced_Ads_Ad( (int) $args['id'], $args );

		// check conditions
		if ( $ad->can_display() ) {
			return $ad->output();
		}
	}

	// internal
	public function get_ad_by_group($args) {
		if ( ! isset($args['id']) || $args['id'] == 0 ) {
			return isset($args['override']) ? $args['override'] : null;
		}

		// get ad
		$adgroup = new Advanced_Ads_Group( (int) $args['id'] );
		return $adgroup->output();
	}

	// internal
	public function get_ad_by_placement($args) {
		if ( ! isset($args['id']) || $args['id'] == '' ) {
			return isset($args['override']) ? $args['override'] : null;
		}

		// get placement content
		return Advanced_Ads_Placements::output( $args['id'] );
	}
}