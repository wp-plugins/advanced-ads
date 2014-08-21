<?php

/**
 * conditions under which to (not) show an ad
 * I don’t like huge arrays like this to clutter my classes
 *  and anyway, this might be needed on multiple places
 *
 * at the bottom, you find a filter to be able to extend / remove your own elements
 *
 * elements
 * key - internal id of the condition; needs to be unique, obviously
 * label - title in the dashboard
 * description - (optional) description displayed in the dashboard
 * type - information / markup type
 *      idfield - input field for comma separated lists of ids
 *      radio - radio button
 *
 * note: ’idfield’ always has a {field}_not version that is created automatically and being its own condition
 *
 */

$advanced_ads_slug = Advanced_Ads::get_instance()->get_plugin_slug();

$advanced_ads_ad_conditions = array(
    'postids' => array(
        'label' => __('Single Pages/Posts', $advanced_ads_slug),
        'description' => __('comma seperated IDs of post, page or custom post type', $advanced_ads_slug),
        'type' => 'idfield',
    ),
    'categoryids' => array(
        'label' => __('Categories', $advanced_ads_slug),
        'description' => __('comma seperated IDs of categories for posts or category archives', $advanced_ads_slug),
        'type' => 'idfield',
    ),
    'categoryarchiveids' => array(
        'label' => __('Category Archives', $advanced_ads_slug),
        'description' => __('comma seperated IDs of category archives', $advanced_ads_slug),
        'type' => 'idfield',
    ),
    'posttypes' => array(
        'label' => __('Post Types', $advanced_ads_slug),
        'description' => __('comma seperated list of post types', $advanced_ads_slug),
        'type' => 'textvalues',
    ),
    'is_front_page' => array(
        'label' => __('Home Page', $advanced_ads_slug),
        'description' => __('(don’t) show on Home page', $advanced_ads_slug),
        'type' => 'radio',
    ),
    'is_singular' => array(
        'label' => __('Singular Pages', $advanced_ads_slug),
        'description' => __('(don’t) show on singular pages/posts', $advanced_ads_slug),
        'type' => 'radio',
    ),
    'is_archive' => array(
        'label' => __('Archive Pages', $advanced_ads_slug),
        'description' => __('(don’t) show on any type of archive page (category, tag, author and date)', $advanced_ads_slug),
        'type' => 'radio',
    ),
    'is_search' => array(
        'label' => __('Search Results', $advanced_ads_slug),
        'description' => __('(don’t) show on search result pages', $advanced_ads_slug),
        'type' => 'radio',
    ),
    'is_404' => array(
        'label' => __('404 Page', $advanced_ads_slug),
        'description' => __('(don’t) show on 404 error page', $advanced_ads_slug),
        'type' => 'radio',
    ),
    'is_attachment' => array(
        'label' => __('Attachment Pages', $advanced_ads_slug),
        'description' => __('(don’t) show on attachment pages', $advanced_ads_slug),
        'type' => 'radio',
    )
);

$advanced_ads_ad_conditions = apply_filters('advanced-ads-conditions', $advanced_ads_ad_conditions);