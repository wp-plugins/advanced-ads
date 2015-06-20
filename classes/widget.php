<?php
/**
 * Advanced Ads Widget
 *
 * @package   Advanced_Ads_Widget
 * @author    Thomas Maier <thomas.maier@webgilde.com>
 * @license   GPL-2.0+
 * @link      http://webgilde.com
 * @copyright 2014 Thomas Maier, webgilde GmbH
 */

/**
 * Ad widget
 *
 */
class Advanced_Ads_Widget extends WP_Widget {

	function __construct() {
		$widget_ops = array('classname' => 'advads_widget', 'description' => __( 'Display Ads and Ad Groups.', ADVADS_SLUG ));
		$control_ops = array();
		parent::__construct( 'advads_ad_widget', __( 'Advanced Ads', ADVADS_SLUG ), $widget_ops, $control_ops );
	}

	function widget($args, $instance) {
		/** This filter is documented in wp-includes/default-widgets.php */
		$title = apply_filters( 'widget_title', empty( $instance['title'] ) ? '' : $instance['title'], $instance, $this->id_base );

		extract( $args );
		$item_id = empty($instance['item_id']) ? '' : $instance['item_id'];
		$title = empty($instance['title']) ? '' : $instance['title'];
		echo $before_widget;
		if ( ! empty( $title ) ) {
			echo $before_title . $title . $after_title;
		}
		echo self::output( $item_id );
		echo $after_widget;
	}

	function update($new_instance, $old_instance) {
		$instance = $old_instance;
		$instance['title'] = $new_instance['title'];
		$instance['item_id'] = $new_instance['item_id'];
		return $instance;
	}

	function form($instance) {
		$instance = wp_parse_args( (array) $instance, array('title' => '', 'item_id' => '') );
		$title = strip_tags( $instance['title'] );
		$elementid = $instance['item_id'];

		?><p><label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" /></p><?php

			$items = self::items_for_select();
		?>
        <select id="<?php echo $this->get_field_id( 'item_id' ); ?>" name="<?php echo $this->get_field_name( 'item_id' ); ?>">
            <option value=""><?php _e( '--empty--', ADVADS_SLUG );  ?></option>
            <?php if ( isset($items['groups']) ) : ?>
            <optgroup label="<?php _e( 'Ad Groups', ADVADS_SLUG ); ?>">
            <?php foreach ( $items['groups'] as $_item_id => $_item_title ) : ?>
            <option value="<?php echo $_item_id; ?>" <?php selected( $_item_id, $elementid ); ?>><?php echo $_item_title; ?></option>
            <?php endforeach; ?>
            </optgroup>
            <?php endif; ?>
            <?php if ( isset($items['ads']) ) : ?>
            <optgroup label="<?php _e( 'Ads', ADVADS_SLUG ); ?>">
            <?php foreach ( $items['ads'] as $_item_id => $_item_title ) : ?>
            <option value="<?php echo $_item_id; ?>" <?php selected( $_item_id, $elementid ); ?>><?php echo $_item_title; ?></option>
            <?php endforeach; ?>
            </optgroup>
            <?php endif; ?>
        </select><?php
	}

	 /**
	 * get items for widget select field
	 *
	 * @since 1.2
	 * @return arr $select items for select field
	 */
	static function items_for_select(){
		$select = array();
		$model = Advanced_Ads::get_instance()->get_model();

		// load all ads
		$ads = $model->get_ads( array('orderby' => 'name', 'order' => 'ASC') );
		foreach ( $ads as $_ad ){
			$select['ads']['ad_' . $_ad->ID] = $_ad->post_title;
		}

		// load all ad groups
		$groups = $model->get_ad_groups();
		foreach ( $groups as $_group ){
			$select['groups']['group_' . $_group->term_id] = $_group->name;
		}

		return $select;
	}

	/**
	 * return content of an in a widget
	 *
	 * @since 1.2
	 * @param string $id slug of the display
	 */
	static function output($id = ''){
		// get placement data for the slug
		if ( empty($id) ) { return; }

		$item = explode( '_', $id );

		if ( isset($item[1]) ) {
			$item_id = absint( $item[1] ); }
		elseif (empty($item_id)) return;

		// return either ad or group content
		if ( $item[0] == 'ad' ){
			return get_ad( $item_id );
		} elseif ( $item[0] == 'group' ){
			return get_ad_group( $item_id );
		}

		return;
	}

}
