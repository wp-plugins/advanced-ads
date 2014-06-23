<?php

/*
 * functions that are directly available in WordPress themes (and plugins)
 */

/**
 * return ad content
 *
 * @since 1.0.0
 * @param int $id id of the ad (post)
 *
 */
function get_ad($id = 0){
    $id = absint($id);
    if(empty($id)) return;

    // get ad
    $ad = new Advads_Ad($id);

    // check conditions
    if($ad->can_display())
        return $ad->output();
}

/**
 * echo an ad
 *
 * @since 1.0.0
 * @param int $id id of the ad (post)
 */
function the_ad($id = 0){

    echo get_ad($id);

}

/**
 * return an ad from an ad group based on ad weight
 *
 * @since 1.0.0
 * @param int $id id of the ad group (taxonomy)
 *
 */
function get_ad_group($id = 0){
    $id = absint($id);
    if(empty($id)) return;

    // get ad
    $adgroup = new Advads_Ad_Group($id);
    return $adgroup->output_random_ad();
}

/**
 * echo an ad from an ad group
 *
 * @since 1.0.0
 * @param int $id id of the ad (post)
 */
function the_ad_group($id = 0){

    echo get_ad_group($id);

}