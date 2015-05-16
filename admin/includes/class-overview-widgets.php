<?php
/**
 * container class for callbacks for overview widgets
 *
 * @package WordPress
 * @subpackage Advanced Ads Plugin
 * @since 1.4.3
 */
class Advanced_Ads_Overview_Widgets_Callbacks {

	 /**
	 * set the overview page to one column layout so widgets can get ordered horizontally
	 *
	 * @since 1.4.3
	 * @param arr $columns columns array
	 * @return int $columns
	 */
	static function one_column_overview_page( $columns ) {
		// $columns['toplevel_page_advanced-ads'] = 1;
		return $columns;
	}

	/**
	 * set the overview page to one column layout so widgets can get ordered horizontally
	 *  this overwrites user settings
	 *
	 * @since 1.4.3
	 * @return int $columns
	 */
	function one_column_overview_page_user() {
		// return 1;
	}

	/**
	 * register the plugin overview widgets
	 *
	 * @since 1.4.3
	 * @param obj $screen
	 */
	public static function setup_overview_widgets($screen){

		// abort if not on the overview page
		if ( ! isset($screen->id) || $screen->id !== 'toplevel_page_advanced-ads' ) { return; }

		add_meta_box('advads_overview_news', __( 'Tips and Tutorials', ADVADS_SLUG ),
		array('Advanced_Ads_Overview_Widgets_Callbacks', 'render_subscribe'), $screen->id, 'normal', 'high');
		add_meta_box('advads_overview_addon_help', __( 'Setup and Optimization Help', ADVADS_SLUG ),
		array('Advanced_Ads_Overview_Widgets_Callbacks', 'render_help'), $screen->id, 'normal', 'high');
		add_meta_box('advads_overview_support', __( 'Manual and Support', ADVADS_SLUG ),
		array('Advanced_Ads_Overview_Widgets_Callbacks', 'render_support'), $screen->id, 'normal', 'high');

		// add widgets for pro add ons
		add_meta_box('advads_overview_addon_tracking', __( 'Tracking and Stats', ADVADS_SLUG ),
		array('Advanced_Ads_Overview_Widgets_Callbacks', 'render_add_on_tracking'), $screen->id, 'side', 'high');
		add_meta_box('advads_overview_addon_responsive', __( 'Responsive and Mobile ads', ADVADS_SLUG ),
		array('Advanced_Ads_Overview_Widgets_Callbacks', 'render_add_on_responsive'), $screen->id, 'side', 'high');
		add_meta_box('advads_overview_addon_sticky', __( 'Sticky ads', ADVADS_SLUG ),
		array('Advanced_Ads_Overview_Widgets_Callbacks', 'render_add_on_sticky'), $screen->id, 'side', 'high');
		add_meta_box('advads_overview_addon_layer', __( 'PopUps and Layers', ADVADS_SLUG ),
		array('Advanced_Ads_Overview_Widgets_Callbacks', 'render_add_on_layer'), $screen->id, 'side', 'high');

	}

	/**
	 * subscribe widget
	 *
	 * @since 1.5.4
	 */
	public static function render_subscribe(){

		$is_subscribed = Advanced_Ads_Admin_Notices::get_instance()->is_subscribed();
		$options = Advanced_Ads_Admin_Notices::get_instance()->options();
		$_notice = 'nl_adsense';

		if ( ! isset($options['closed'][ $_notice ] )  && ! $is_subscribed ) {
			?><div class="advads-admin-notice">
			    <p><?php _e( 'Learn more about how and <strong>how much you can earn with AdSense</strong> and Advanced Ads from my dedicated newsletter.', ADVADS_SLUG ); ?></p>
			    <button type="button" class="button-primary advads-notices-button-subscribe" data-notice="<?php echo $_notice ?>"><?php _e('Subscribe me now', ADVADS_SLUG); ?></button>
			</div><?php
		}

		$_notice = 'nl_first_steps';
		if ( ! isset($options['closed'][ $_notice ] ) && ! $is_subscribed  ) {
			?><div class="advads-admin-notice">
			    <p><?php _e( 'Get the first steps and more tutorials to your inbox.', ADVADS_SLUG ); ?></p>
			    <button type="button" class="button-primary advads-notices-button-subscribe" data-notice="<?php echo $_notice ?>"><?php _e('Send it now', ADVADS_SLUG); ?></button>
			</div><?php
		}

		$model = Advanced_Ads::get_instance()->get_model();
		$recent_ads = $model->get_ads();

		// get next steps
		self::render_next_steps( $recent_ads );
	}

	/**
	 * render next-steps
	 */
	private static function render_next_steps($recent_ads = array()){
		$model = Advanced_Ads::get_instance()->get_model();
		$groups = $model->get_ad_groups();
		$placements = $model->get_ad_placements_array();

		$next_steps = array();

		if ( count( $recent_ads ) == 0 ) :
			$next_steps[] = '<p><a class="button button-primary" href="' . admin_url( 'post-new.php?post_type=' . Advanced_Ads::POST_TYPE_SLUG ) .
			'">' . __( 'Create your first ad', ADVADS_SLUG ) . '</a></p>';
		endif;
		if ( count( $groups ) == 0 ) :
			$next_steps[] = '<p class="description">' . __( 'Ad Groups contain ads and are currently used to rotate multiple ads on a single spot.', ADVADS_SLUG ) . '</p>' .
				'<p><a class="button button-primary" href="' . admin_url( 'admin.php?action=edit&page=advanced-ads-groups' ) .
				'">' . __( 'Create your first group', ADVADS_SLUG ) . '</a></p>';
		endif;
		if ( count( $placements ) == 0 ) :
			$next_steps[] = '<p class="description">' . __( 'Ad Placements are the best way to manage where to display ads and groups.', ADVADS_SLUG ) . '</p>'
				. '<p><a class="button button-primary" href="' . admin_url( 'admin.php?action=edit&page=advanced-ads-placements' ) .
				'">' . __( 'Create your first placement', ADVADS_SLUG ) . '</a></p>';
		endif;

		// display all options
		if ( count( $next_steps ) > 0 ){
		    ?><br/><h4><?php _e( 'Next steps', ADVADS_SLUG ); ?></h4><?php
foreach ( $next_steps as $_step ){
	echo $_step;
}
		}
	}

	/**
	 * support widget
	 */
	public static function render_support(){
		?><ul>
            <li><?php printf( __( '<a href="%s" target="_blank">Plugin Homepage</a>', ADVADS_SLUG ), ADVADS_URL . 'advancedads/' ); ?> –
            <?php printf( __( '<a href="%s" target="_blank">Manual</a>', ADVADS_SLUG ), ADVADS_URL . 'manual/' ); ?></li>
            <li><?php printf( __( 'Ask other users in the <a href="%s" target="_blank">wordpress.org forum</a>', ADVADS_SLUG ), 'http://wordpress.org/plugins/advanced-ads/' ); ?></li>
            <li><?php printf( __( 'Vote for a <a href="%s" target="_blank">feature</a>', ADVADS_SLUG ), ADVADS_URL . 'feature-requests/' ); ?></li>
            <li><?php printf( __( 'Thank the developer with a &#9733;&#9733;&#9733;&#9733;&#9733; review on <a href="%s" target="_blank">wordpress.org</a>', ADVADS_SLUG ), 'https://wordpress.org/support/view/plugin-reviews/advanced-ads' ); ?></li>
        </ul><?php
	}

	/**
	 * help widget
	 */
	public static function render_help(){

		?><p><?php _e( 'Need help to set up and optimize your ads? Need custom coding on your site? Ask me for a quote.', ADVADS_SLUG ); ?></p>
		<p><a class="button button-primary" href="mailto:support@wpadvancedads.com?subject=<?php printf( __( 'Help with ads on %s', ADVADS_SLUG ), home_url()); ?>"><?php
			_e( 'Get an offer', ADVADS_SLUG ); ?></a></p><?php
	}

	/**
	 * tracking add-on widget
	 */
	public static function render_add_on_tracking(){

		?><p><?php _e( 'Track the impressions of and clicks on your ads.', ADVADS_SLUG ); ?></p><ul class='list'>
            <li><?php _e( '2 methods to count impressions', ADVADS_SLUG ); ?></li>
            <li><?php _e( 'beautiful stats for all or single ads', ADVADS_SLUG ); ?></li>
            <li><?php _e( 'get stats for predefined and custom persiods', ADVADS_SLUG ); ?></li>
            <li><?php _e( 'group stats by day, week or month', ADVADS_SLUG ); ?></li>
        </ul><p><a class="button button-primary" href="<?php echo ADVADS_URL; ?>add-ons/tracking/" target="_blank"><?php
			_e( 'Get the Tracking add-on', ADVADS_SLUG ); ?></a></p><?php
	}

	/**
	 * responsive add-on widget
	 */
	public static function render_add_on_responsive(){

		?><p><?php _e( 'Display ads based on the size of your visitor’s browser or device.', ADVADS_SLUG ); ?></p><ul class='list'>
            <li><?php _e( 'set a range (from … to …) pixels for the browser size', ADVADS_SLUG ); ?></li>
            <li><?php _e( 'set custom sizes for AdSense responsive ads', ADVADS_SLUG ); ?></li>
            <li><?php _e( 'list all ads by their responsive settings', ADVADS_SLUG ); ?></li>
        </ul><p><a class="button button-primary" href="<?php echo ADVADS_URL; ?>add-ons/responsive-ads/" target="_blank"><?php
			_e( 'Get the Responsive add-on', ADVADS_SLUG ); ?></a></p><?php
	}

	/**
	 * sticky add-on widget
	 */
	public static function render_add_on_sticky(){

		?><p><?php _e( 'Fix ads to the browser while users are scrolling and create best performing anchor ads.', ADVADS_SLUG ); ?></p><ul class='list'>
            <li><?php _e( 'position ads that don’t scroll with the screen', ADVADS_SLUG ); ?></li>
            <li><?php _e( 'build anchor ads not only on mobile devices', ADVADS_SLUG ); ?></li>
        </ul><p><a class="button button-primary" href="<?php echo ADVADS_URL; ?>add-ons/sticky-ads/" target="_blank"><?php
			_e( 'Get the Sticky add-on', ADVADS_SLUG ); ?></a></p><?php
	}

	/**
	 * layer add-on widget
	 */
	public static function render_add_on_layer(){

		?><p><?php _e( 'Display content and ads in layers and popups on custom events.', ADVADS_SLUG ); ?></p><ul class='list'>
            <li><?php _e( 'display a popup after a user interaction like scrolling', ADVADS_SLUG ); ?></li>
            <li><?php _e( 'optional backgroup overlay', ADVADS_SLUG ); ?></li>
            <li><?php _e( 'allow users to close the popup', ADVADS_SLUG ); ?></li>
        </ul><p><a class="button button-primary" href="<?php echo ADVADS_URL; ?>add-ons/popup-and-layer-ads/" target="_blank"><?php
			_e( 'Get the PopUp and Layer add-on', ADVADS_SLUG ); ?></a></p><?php
	}

}
