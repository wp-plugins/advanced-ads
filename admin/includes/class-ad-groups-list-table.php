<?php

/**
 * Groups List Table class.
 *
 * derrived from WP_Terms_List_Table
 *
 * @package WordPress
 * @subpackage List_Table
 * @since 3.1.0
 * @access private
 */
class AdvAds_Groups_List_Table extends AdvAds_List_Table {

	/**
	 * constructor
	 *
	 * @since 1.0.0
	 * @global type $status
	 * @global type $page
	 */
	function __construct() {
		global $status, $page;

		parent::__construct(array(
			'plural' => 'adgroups',
			'singular' => 'adgroup',
		));

		$this->post_type = Advanced_Ads::POST_TYPE_SLUG;
		$this->taxonomy = Advanced_Ads::AD_GROUP_TAXONOMY;
	}

	/**
	 * default column handling
	 *
	 * @since 1.0.0
	 * @param type $item
	 * @param type $column_name
	 * @return output for the column
	 */
	function column_default($item, $column_name) {
		return apply_filters( "manage_{$this->taxonomy}_{$column_name}_column", '', $column_name, $item->term_id );
	}

	/**
	 * handle checkbox column
	 *
	 * @since 1.0.ß
	 * @see WP_List_Table::::single_row_columns()
	 * @param array $item A singular item (one full row's worth of data)
	 * @return string Text to be placed inside the column <td> (movie title only)
	 */
	function column_cb($tag) {
		$default_term = get_option( 'default_' . $this->screen->taxonomy );

		return '<label class="screen-reader-text" for="cb-select-' . $tag->term_id . '">' . sprintf( __( 'Select %s', ADVADS_SLUG ), $tag->name ) . '</label>'
				. '<input type="checkbox" name="delete_tags[]" value="' . $tag->term_id . '" id="cb-select-' . $tag->term_id . '" />';

		return '&nbsp;';
	}

	/**
	 * column with the ad group id
	 * @since 1.0.0
	 * @param type $tag
	 * @return string
	 */
	function column_id($tag) {
		return $tag->term_id;
	}

	/**
	 * render the basic ad group information
	 *
	 * @since 1.0.0
	 * @param obj $tag
	 * @return string
	 */
	function column_name($tag) {
		$tax = get_taxonomy( $this->taxonomy );

		$default_term = get_option( 'default_' . $this->taxonomy );

		$name = apply_filters( 'term_name', $tag->name, $tag );
		$qe_data = get_term( $tag->term_id, $this->taxonomy, OBJECT, 'edit' );
		// $edit_link = esc_url(get_edit_term_link($tag->term_id, $this->taxonomy, $this->screen->post_type));
		$args = array(
			'action' => 'edit',
			'group_id' => $tag->term_id
		);
		$edit_link = Advanced_Ads_Admin::group_page_url( $args );

		$out = '<strong><a class="row-title" href="' . $edit_link . '" title="' . esc_attr( sprintf( __( 'Edit &#8220;%s&#8221;', ADVADS_SLUG ), $name ) ) . '">' . $name . '</a></strong><br />';
		$out .= '<p class="description">' . $tag->description . '</p>';

		$actions = array();
		if ( current_user_can( $tax->cap->edit_terms ) ) {
			$actions['edit'] = '<a href="' . $edit_link . '">' . __( 'Edit', ADVADS_SLUG ) . '</a>';
			//            $actions['inline hide-if-no-js'] = '<a href="#" class="editinline">' . __('Quick&nbsp;Edit') . '</a>';
		}
		if ( current_user_can( $tax->cap->delete_terms ) && $tag->term_id != $default_term ){
			$args = array(
				'action' => 'delete',
				'group_id' => $tag->term_id
			);
			$delete_link = Advanced_Ads_Admin::group_page_url( $args );
			$actions['delete'] = "<a class='delete-tag' href='" . wp_nonce_url( $delete_link, 'delete-tag_' . $tag->term_id ) . "'>" . __( 'Delete' ) . '</a>';
		}

		$actions = apply_filters( "{$this->taxonomy}_row_actions", $actions, $tag );

		$out .= $this->row_actions( $actions );
		$out .= '</div>';

		return $out;
	}

	/**
	 * render the slug column
	 *
	 * @since 1.0.0
	 * @param obj $tag
	 * @return string
	 */
	function column_slug($tag) {
		return apply_filters( 'editable_slug', $tag->slug );
	}

	/**
	 * render the ads column (number of ads belonging to this group)
	 *
	 * @since 1.0.0
	 * @updated 1.1.0 only display published ads
	 * @param obj $tag
	 * @return string
	 */
	function column_ads($tag) {
		$count = number_format_i18n( $tag->count );

		$tax = get_taxonomy( $this->taxonomy );
		$args = array(
			'post_type' => $this->post_type,
			'post_status' => 'publish',
			'taxonomy' => $this->taxonomy,
			'term' => $tag->slug
		);
		$ads = new WP_Query( $args );

		$group = new Advads_Ad_Group( $tag->term_id );
		$weights = $group->get_ad_weights();

		$out = '';
		$actions = array();
		// The Loop
		if ( $ads->have_posts() ) {
			$out .= '<table class="advads-groups-ads-list">';
			while ( $ads->have_posts() ) {
				$ads->the_post();
				$out .= '<tr><td><a href="' . get_edit_post_link( get_the_ID() ) . '">' . get_the_title() . '</a>';
				$_weight = (isset($weights[get_the_ID()])) ? $weights[get_the_ID()] : Advads_Ad_Group::MAX_AD_GROUP_WEIGHT;
				$out .= '<td class="ad-weight ad-weight-' . get_the_ID() . '" title="'.__( 'Ad weight', ADVADS_SLUG ).'">' . $_weight . '</td></tr>';
				$out .= '</tr>';
			}
			$out .= '</table>';
			// include actions
			$actions['edit'] = '<a href="#" class="edit-ad-group-ads">' . __( 'Edit', ADVADS_SLUG ) . '</a>';
			$out .= $this->row_actions( $actions );
			// row with the group id
			$out .= '<input type="hidden" class="ad-group-id" value="'. $tag->term_id .'"/>';
		}
		// Restore original Post Data
		wp_reset_postdata();

		return $out;
	}

	/**
	 * load the column names
	 *
	 * @since 1.0.0
	 * @see WP_List_Table::::single_row_columns()
	 * @return array An associative array containing column information: 'slugs'=>'Visible Titles'
	 * ************************************************************************ */
	function get_columns() {
		$columns = array(
		//    'cb' => '<input type="checkbox" />', //Render a checkbox instead of text
			'id' => __( 'ID', $this->textdomain ),
			'name' => __( 'Ad Group', $this->textdomain ),
			'slug' => __( 'Slug', $this->textdomain ),
			'ads' => __( 'Ads', $this->textdomain ),
		);
		return $columns;
	}

	/**
	 * contains sortable columns
	 *
	 * @since 1.0.0
	 * @return array An associative array containing all the columns that should be sortable: 'slugs'=>array('data_values',bool)
	 * ************************************************************************ */
	function get_sortable_columns() {
		$sortable_columns = array(
			'id' => array('id', true), //true means it's already sorted
			'name' => array('name', false),
			'description' => array('description', false),
			'slug' => array('slug', false)
		);
		return $sortable_columns;
	}

	/**
	 * define bulk actions
	 *
	 * @since 1.0.0
	 * @return array An associative array containing all the bulk actions: 'slugs'=>'Visible Titles'
	 * ************************************************************************ */
	function get_bulk_actions() {
		$actions = array(
		//    'delete' => __('Delete', $this->textdomain)
		);
		return $actions;
	}

	/**
	 * handle bulk actions here
	 *
	 * @since 1.0.0
	 * @see $this->prepare_items()
	 * ************************************************************************ */
	function process_bulk_action() {

		//Detect when a bulk action is being triggered...
		if ( 'delete' === $this->current_action() ) {

		}
	}

	/**
	 * load items for output
	 *
	 * @since 1.0.0
	 */
	function prepare_items() {
		// set columns
		$columns = $this->get_columns();
		$hidden = array();
		$sortable = $this->get_sortable_columns();

		// combined array with all kinds of columns
		$this->_column_headers = array($columns, $hidden, $sortable);

		// process bulk actions
		$this->process_bulk_action();

		// prepare items
		$search = ! empty($_REQUEST['s']) ? trim( wp_unslash( $_REQUEST['s'] ) ) : '';

		$args = array(
			'taxonomy' => $this->taxonomy,
			'search' => $search,
			'hide_empty' => 0,
		);

		if ( ! empty($_REQUEST['orderby']) ) {
			$args['orderby'] = trim( wp_unslash( $_REQUEST['orderby'] ) ); }

		if ( ! empty($_REQUEST['order']) ) {
			$args['order'] = trim( wp_unslash( $_REQUEST['order'] ) ); }

		$this->callback_args = $args;

		// get items
		$this->items = get_categories( $args );

		$total_items = count( $this->items );
		$this->set_pagination_args(array(
			'total_items' => $total_items,
			'total_pages' => 1
		));
	}

}