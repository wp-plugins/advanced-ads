<?php

/**
 * Advanced Ads
 *
 * @package   Advads_Ad_Group
 * @author    Thomas Maier <thomas.maier@webgilde.com>
 * @license   GPL-2.0+
 * @link      http://webgilde.com
 * @copyright 2014 Thomas Maier, webgilde GmbH
 */

/**
 * an ad group object
 *
 * @package Advads_Ad_Group
 * @author  Thomas Maier <thomas.maier@webgilde.com>
 */
class Advads_Ad_Group {

	/**
	 * default ad group weight
	 */
	const MAX_AD_GROUP_WEIGHT = 10;

	/**
	 * id of the taxonomy of this ad group
	 */
	public $id = 0;

	/**
	 * name of the taxonomy
	 */
	protected $taxonomy = '';

	/**
	 * post type of the ads
	 */
	protected $post_type = '';

	/**
	 * the current loaded ad
	 */
	protected $current_ad = '';

	/**
	 * the name of the term
	 */
	public $name = '';

	/**
	 * the slug of the term
	 */
	public $slug = '';

	/**
	 * the description of the term
	 */
	public $description = '';

	/**
	 * containing ad weights
	 */
	private $ad_weights = 0;

	/**
	 * array with post type objects (ads)
	 */
	private $ads = array();

	/**
	 * init ad group object
	 *
	 * @since 1.0.0
	 * @param int $id id of the ad group (= taxonomy id)
	 */
	public function __construct($id) {
		$id = absint( $id );

		if ( empty($id) ) {
			return; }

		$this->id = $id;
		$this->taxonomy = Advanced_Ads::AD_GROUP_TAXONOMY;
		$this->post_type = Advanced_Ads::POST_TYPE_SLUG;

		$this->load( $id );
	}

	/**
	 * load an ad group object by id
	 *
	 * @since 1.0.0
	 */
	private function load($id = 0) {
		$_group = get_term( $id, $this->taxonomy );
		if ( $_group == null ) {
			return; }

		$this->name = $_group->name;
		$this->slug = $_group->slug;
		$this->description = $_group->description;
	}

	/**
	 * return random ad content for frontend output
	 *
	 * @since 1.0.0
	 * @return string ad content
	 */
	public function output_random_ad() {
		// see prepare_frontend_output for example for this filter
		$ad = $this->get_random_ad();

		if ( ! is_object( $ad ) ) {
			return ''; }

		// add the group to the global output array
		$advads = Advanced_Ads::get_instance();
		$advads->current_ads[] = array('type' => 'group', 'id' => $this->id, 'title' => $this->name);

		// makes sure the ad filters can also run here
		$adcontent = $ad->output();

		// filter again, in case a developer wants to filter group output individually
		$output = apply_filters( 'advanced-ads-group-output', $adcontent, $this );

		return $output;
	}

	/**
	 * get a random ad from this group
	 *
	 * @since 1.0.0
	 * @return
	 */
	private function get_random_ad() {

		// load all ads
		$ads = $this->load_all_ads();

		// return, if no ads given
		if ( $ads === array() ) {
			return; }

		// shuffle ads based on ad weight
		$ads = $this->shuffle_ads( $ads );

		// check ads one by one for being able to be displayed on this spot
		foreach ( $ads as $_ad ) {
			// load the ad object
			$ad = new Advads_Ad( $_ad->ID );
			if ( $ad->can_display() ) {
				return $ad;
			}
		}

		return '';
	}

	/**
	 * return all ads from this group
	 *
	 * @since 1.0.0
	 */
	public function get_all_ads() {
		if ( count( $this->ads ) > 0 ) {
			return $this->ads; }
		else {
			return $this->load_all_ads(); }
	}

	/**
	 * load all public ads for this group
	 *
	 * @since 1.0.0
	 * @update 1.1.0 load only public ads
	 * @return arr $ads array with ad (post) objects
	 */
	private function load_all_ads() {

		$args = array(
			'post_type' => $this->post_type,
			'post_status' => 'publish',
			'posts_per_page' => -1,
			'taxonomy' => $this->taxonomy,
			'term' => $this->slug,
			'orderby' => 'id'
		);
		$ads = new WP_Query( $args );

		if ( $ads->have_posts() ) {
			return $this->ads = $this->add_post_ids( $ads->posts );
		} else {
			return $this->ads = array();
		}
	}

	/**
	 * use post ids as keys for ad array
	 *
	 * @since 1.0.0
	 * @param arr $ads array with post objects
	 * @return arr $ads array with post objects with post id as their key
	 * @todo check, if there isn’t a WP function for this already
	 */
	private function add_post_ids(array $ads){

		$ads_with_id = array();
		foreach ( $ads as $_ad ){
			$ads_with_id[$_ad->ID] = $_ad;
		}

		return $ads_with_id;
	}

	/**
	 * shuffle ads based on ad weight
	 *
	 * @since 1.0.0
	 * @param arr $ads array with ad objects
	 * @return arr $shuffled_ads shuffled array with ad objects
	 */
	private function shuffle_ads($ads = array()) {

		// get saved ad weights
		$weights = $this->get_ad_weights();

		// if ads and weights don’t have the same keys, update weights array
		if ( (count( $weights ) == 0 && count( $ads ) > 0) || count( $weights ) != count( $ads ) || array_diff_key( $weights, $ads ) != array()
				|| array_diff_key( $ads, $weights ) != array() ) {
			$this->update_ad_weights();
		}

		// get a random ad for every ad there is
		$shuffled_ads = array();
		// order array by ad weight; lowest first
		asort( $weights );
		// while non-zero weights are set select random next
		while ( null !== $random_ad_id = $this->get_random_ad_by_weight( $weights ) ) {
			// remove chosen ad from weights array
			unset($weights[$random_ad_id]);
			// put random ad into shuffled array
			if ( ! empty($ads[$random_ad_id]) ) {
				$shuffled_ads[] = $ads[$random_ad_id]; }
		}

		return $shuffled_ads;
	}

	/**
	 * get random ad by ad weight
	 *
	 * @since 1.0.0
	 * @param array $ad_weights e.g. array(A => 2, B => 3, C => 5)
	 * @source applied with fix for order http://stackoverflow.com/a/11872928/904614
	 */
	private function get_random_ad_by_weight(array $ad_weights) {

		// use maximum ad weight for ads without this
		// ads might have a weight of zero (0); to avoid mt_rand fail assume that at least 1 is set.
		$max = array_sum( $ad_weights );
		if ( $max < 1 ) {
			return ;
		}

		$rand = mt_rand( 1, $max );

		foreach ( $ad_weights as $key => $value ) {
			$rand -= $value;
			if ( $rand <= 0 ) {
				return $key;
			}
		}
	}

	/**
	 * get weights of ads in this group
	 *
	 * @since 1.0.0
	 */
	public function get_ad_weights() {
		// load and save ad weights if not yet set
		if ( $this->ad_weights == 0 ) {
			$weights = get_option( 'advads-ad-weights', array() );
			if ( isset($weights[$this->id]) ) {
				return $this->ad_weights = $weights[$this->id];
			}
		} elseif ( is_array( $this->ad_weights ) ) { // return ad weigths if not empty
			return $this->ad_weights;
		}

		// return empty array
		return array();
	}

	/**
	 * save ad group weight (into global ad weight array)
	 *
	 * @since 1.0.0
	 * @param arr|str $weights array with ad weights (key: ad id; value: weight)
	 */
	public function save_ad_weights($weights = '') {

		// allow only arrays and empty string
		if ( ! is_array( $weights ) && $weights = '' ) {
			return; }

		$global_weights = get_option( 'advads-ad-weights', array() );

		$global_weights[$this->id] = $this->sanitize_ad_weights( $weights );

		update_option( 'advads-ad-weights', $global_weights );

		// refresh ad weights after update to avoid conflict
		$this->ad_weights = $global_weights[$this->id];
	}

	/**
	 * update ad weight based on current ads for the group and ad weight
	 *
	 * @since 1.0.0
	 */
	private function update_ad_weights(){
		$ads = $this->get_all_ads();
		$weights = $this->get_ad_weights();

		$new_weights = array();
		// use only ads assigned to the group
		foreach ( $ads as $_ad ){
			if ( isset($weights[$_ad->ID]) ){
				$new_weights[$_ad->ID] = $weights[$_ad->ID];
			} else {
				// if no weight is given, use maximum default value
				$new_weights[$_ad->ID] = self::MAX_AD_GROUP_WEIGHT;
			}
		}

		$this->save_ad_weights( $new_weights );
	}

	/**
	 * sanitize ad weights
	 *
	 * @since 1.0.0
	 * @param arr $weights ad weights array with (key: ad id; value: weight)
	 */
	private function sanitize_ad_weights($weights = array()) {

		if ( ! is_array( $weights ) ) {
			return ''; }

		$sanitized_weights = array();
		foreach ( $weights as $_ad_id => $_weight ) {
			$_ad_id = absint( $_ad_id );
			$_weight = absint( $_weight );
			$sanitized_weights[$_ad_id] = $_weight;
		}

		return $sanitized_weights;
	}

}
