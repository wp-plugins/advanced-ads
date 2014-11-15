<?php

/**
 * Advanced Ads
 *
 * @package   Advanced_Ads_Placements
 * @author    Thomas Maier <thomas.maier@webgilde.com>
 * @license   GPL-2.0+
 * @link      http://webgilde.com
 * @copyright 2014 Thomas Maier, webgilde GmbH
 */

/**
 * grouping placements functions
 *
 * @since 1.1.0
 * @package Advads_Placements
 * @author  Thomas Maier <thomas.maier@webgilde.com>
 */
class Advads_Ad_Placements {

    /**
     * get placement types
     *
     * @since 1.2.1
     * @return arr $types array with placement types
     */
    static function get_placement_types() {
        $types = array(
            'default' => array(
                'title' => __('default', ADVADS_SLUG),
                'description' => __('Manual placement.', ADVADS_SLUG),
                ),
            'header' => array(
                'title' => __('header', ADVADS_SLUG),
                'description' => __('Injected in Header (before closing </head> Tag, often not visible).', ADVADS_SLUG),
                ),
            'footer' => array(
                'title' => __('footer', ADVADS_SLUG),
                'description' => __('Injected in Footer (before closing </body> Tag).', ADVADS_SLUG),
                ),
            'post_top' => array(
                'title' => __('before post', ADVADS_SLUG),
                'description' => __('Injected before the post content.', ADVADS_SLUG),
                ),
            'post_bottom' => array(
                'title' => __('after post', ADVADS_SLUG),
                'description' => __('Injected after the post content.', ADVADS_SLUG),
                ),
            'post_content' => array(
                'title' => __('post content', ADVADS_SLUG),
                'description' => __('Injected into the post content. You can choose the paragraph after which the ad content is displayed.', ADVADS_SLUG),
                ),
        );
        return apply_filters('advads-placement-types', $types);
    }

    /**
     * save a new placement
     *
     * @since 1.1.0
     * @param array $new_placement
     * @return mixed true if saved; error message if not
     */
    static function save_new_placement($new_placement) {
        // load placements
        $placements = Advanced_Ads::get_ad_placements_array();

        // escape slug as slug
        $new_placement['slug'] = sanitize_title($new_placement['slug']);

        // check if slug already exists
        if ($new_placement['slug'] == '')
            return __('Slug can’t be empty.', ADVADS_SLUG);
        if (isset($placements[$new_placement['slug']]))
            return __('Slug already exists.', ADVADS_SLUG);

        // make sure only allowed types are being saved
        $placement_types = Advads_Ad_Placements::get_placement_types();
        $new_placement['type'] = (isset($placement_types[$new_placement['type']])) ? $new_placement['type'] : 'default';
        // escape name
        $new_placement['name'] = esc_attr($new_placement['name']);

        // add new place to all placements
        $placements[$new_placement['slug']] = array(
            'type' => $new_placement['type'],
            'name' => $new_placement['name']
        );

        // save array
        update_option('advads-ads-placements', $placements);

        return true;
    }

    /**
     * save placements
     *
     * @since 1.1.0
     * @param array $placement_items
     * @return mixed true if saved; error message if not
     */
    static function save_placements($placement_items) {

        // load placements
        $placements = Advanced_Ads::get_ad_placements_array();

        foreach ($placement_items as $_placement_slug => $_placement) {
            // remove the placement
            if (isset($_placement['delete'])) {
                unset($placements[$_placement_slug]);
                continue;
            }
            // save item
            if (isset($_placement['item']))
                $placements[$_placement_slug]['item'] = $_placement['item'];
            // save item options
            if (isset($_placement['options'])){
                $placements[$_placement_slug]['options'] = $_placement['options'];
                if(isset($placements[$_placement_slug]['options']['index']))
                    $placements[$_placement_slug]['options']['index'] = absint($placements[$_placement_slug]['options']['index']);
            }
        }

        // save array
        update_option('advads-ads-placements', $placements);

        return true;
    }

    /**
     * get items for item select field
     *
     * @since 1.1
     * @return arr $select items for select field
     */
    static function items_for_select() {
        $select = array();

        // load all ads
        $ads = Advanced_Ads::get_ads();
        foreach ($ads as $_ad) {
            $select['ads']['ad_' . $_ad->ID] = $_ad->post_title;
        }

        // load all ad groups
        $groups = Advanced_Ads::get_ad_groups();
        foreach ($groups as $_group) {
            $select['groups']['group_' . $_group->term_id] = $_group->name;
        }

        return $select;
    }

    /**
     * return content of a placement
     *
     * @since 1.1.0
     * @param string $id slug of the display
     */
    static function output($id = '') {
        // get placement data for the slug
        if ($id == '')
            return;

        $placements = get_option('advads-ads-placements', array());

        if (isset($placements[$id]['item'])) {
            $_item = explode('_', $placements[$id]['item']);

            if (isset($_item[1]))
                $_item_id = absint($_item[1]);
            elseif (empty($_item_id))
                return;

            // return either ad or group content
            if ($_item[0] == 'ad') {
                return get_ad($_item_id);
            } elseif ($_item[0] == 'group') {
                return get_ad_group($_item_id);
            }
        } else {
            return;
        }

        return;
    }

    /**
     * inject ads directly into the content
     *
     * @since 1.2.1
     * @param string $placement_id id of the placement
     * @param arr $options placement options
     * @param string $content
     * @return type
     * @link inspired by http://www.wpbeginner.com/wp-tutorials/how-to-insert-ads-within-your-post-content-in-wordpress/
     */
    static function inject_in_content($placement_id, $options, $content) {
        $closing_p = '</p>';
        $paragraph_id = isset($options['index']) ? $options['index'] : 1;
        $ad_content = Advads_Ad_Placements::output($placement_id);

        $paragraphs = explode($closing_p, $content);
        $offset = 0;
        $running = true;
        foreach ($paragraphs as $index => $paragraph) {

            // check if current paragraph is empty and if so, increate offset
            if($running && $index > 0 && trim(str_replace(array('<p>', '&nbsp;'), '', $paragraph)) == '')
                    $offset++;

            if (trim($paragraph)) {
                $paragraphs[$index] .= $closing_p;
            }

            if ($paragraph_id + $offset == $index + 1) {
                $paragraphs[$index] .= $ad_content;
                $running = false;
            }
        }
        return implode('', $paragraphs);
    }

}
