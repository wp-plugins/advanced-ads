<?php

/*
 * functions that are directly available in WordPress themes (and plugins)
 */

/**
 * return ad content
 *
 * @since 1.0.0
 * @param int $id id of the ad (post)
 * @param arr $args additional arguments
 */
function get_ad($id = 0, $args = array()){
    $id = absint($id);
    if(empty($id)) return;

    // get ad
    $ad = new Advads_Ad($id, $args);

    // check conditions
    if($ad->can_display())
        return $ad->output();
}

/**
 * echo an ad
 *
 * @since 1.0.0
 * @param int $id id of the ad (post)
 * @param arr $args additional arguments
 */
function the_ad($id = 0, $args = array()){

    echo get_ad($id, $args);

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

/**
 * return content of an ad placement
 *
 * @since 1.1.0
 * @param string $id slug of the ad placement
 *
 */
function get_ad_placement($id = ''){
    if($id == '') return;

    // get placement content
    $output = Advads_Ad_Placements::output($id);
    return $output;
}

/**
 * return content of an ad placement
 *
 * @since 1.1.0
 * @param string $id slug of the ad placement
 */
function the_ad_placement($id = ''){

    echo get_ad_placement($id);

}