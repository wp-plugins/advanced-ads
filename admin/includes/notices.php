<?php
/**
 * array with admin notices
 */
$advanced_ads_admin_notices = array(
    // if users updated from a previous version to 1.4.5
    '1.4.5' => array(
	'type' => 'update',
	'text' => 'Advanced Ads 1.4.5 changes the behavior of some display conditions. Please read this <a href="http://wpadvancedads.com/advanced-ads-1-4-5/" target="_blank">update post</a> to learn if this change should concern you.',
    ),
    // newsletter after activation
    'nl_first_steps' => array(
	'type' => 'subscribe',
	'text' => __( 'Thank you for activating <strong>Advanced Ads</strong>. Would you like to receive the first steps via email?', ADVADS_SLUG ),
	'confirm_text' => __( 'Yes, send it', ADVADS_SLUG )
    ),
    // adsense newsletter group
    'nl_adsense' => array(
	'type' => 'subscribe',
	'text' => __( 'Learn more about how and <strong>how much you can earn with AdSense</strong> and Advanced Ads from my dedicated newsletter.', ADVADS_SLUG ),
	'confirm_text' => __( 'Subscribe me now', ADVADS_SLUG )
    ),
    // if users updated from a previous version to 1.5.4
    '1.5.4' => array(
	'type' => 'update',
	'text' => 'With Advanced Ads 1.5.4 the handling of <strong>visitor conditions</strong> became more consistent, flexible, and hopefully easier to use too. Please read this <a href="http://wpadvancedads.com/advanced-ads-1-5-4/" target="_blank">update post</a> to learn if this change should concern you.',
    ),
);