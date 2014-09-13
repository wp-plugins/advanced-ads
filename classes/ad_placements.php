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
     * save a new placement
     *
     * @since 1.1.0
     * @param array $new_placement
     * @return mixed true if saved; error message if not
     */
    static function save_new_placement($new_placement){
        // load placements
        $placements = Advanced_Ads::get_ad_placements_array();

        // escape slug as slug
        $new_placement['slug'] = sanitize_title($new_placement['slug']);

        // check if slug already exists
        if($new_placement['slug'] == '') return __('Slug can’t be empty.', ADVADS_SLUG);
        if(isset($placements[$new_placement['slug']])) return __('Slug already exists.', ADVADS_SLUG);

        // escape name
        $new_placement['name'] = esc_attr($new_placement['name']);

        // add new place to all placements
        $placements[$new_placement['slug']] = array('name' => $new_placement['name']);

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
    static function save_placements($placement_items){

        // load placements
        $placements = Advanced_Ads::get_ad_placements_array();

        foreach($placement_items as $_placement_slug => $_placement){
            // remove the placement
            if(isset($_placement['delete'])) {
                unset($placements[$_placement_slug]);
                continue;
            }
            // save item
            if(isset($_placement['item'])) $placements[$_placement_slug]['item'] = $_placement['item'];
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
    static function items_for_select(){
        $select = array();

        // load all ads
        $ads = Advanced_Ads::get_ads();
        foreach($ads as $_ad){
            $select['ads']['ad_' . $_ad->ID] = $_ad->post_title;
        }

        // load all ad groups
        $groups = Advanced_Ads::get_ad_groups();
        foreach($groups as $_group){
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
    static function output($id = ''){
        // get placement data for the slug
        if($id == '') return;

        $placements = get_option('advads-ads-placements', array());

        if(isset($placements[$id]['item'])) {
            $_item = explode('_', $placements[$id]['item']);

            if(isset($_item[1]))
                $_item_id = absint($_item[1]);
            elseif(empty($_item_id)) return;

            // return either ad or group content
            if($_item[0] == 'ad'){
                return get_ad($_item_id);
            } elseif($_item[0] == 'group'){
                return get_ad_group($_item_id);
            }
        } else {
            return;
        }

        return;
    }
}