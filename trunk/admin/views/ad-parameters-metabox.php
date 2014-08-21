<?php $types = Advanced_Ads::get_instance()->ad_types; ?>
<h4><?php _e('Ad Parameters', Advanced_Ads::TD); ?></h4>
<?php
/**
 * when changing ad type ad parameter content is loaded via ajax
 * @filesource admin/assets/js/admin.js
 * @filesource includes/class-ajax-callbacks.php ::load_ad_parameters_metabox
 * @filesource classes/ad-type-content.php :: renter_parameters()
 */
?>
<div id="advanced-ads-ad-parameters">
    <?php $type = (isset($types[$ad->type])) ? $types[$ad->type] : current($types);
        $type->render_parameters($ad); ?>
</div>
<?php do_action('advanced-ads-ad-params-after', $ad, $types);