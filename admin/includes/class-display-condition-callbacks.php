<?php
/**
 * container class for callbacks for display conditions
 *
 * @package WordPress
 * @subpackage Advanced Ads Plugin
 * @since 1.2.2
 */
class AdvAds_Display_Condition_Callbacks {

    /**
     * display display condition for post types
     *
     * @param obj $ad ad object
     * @since 1.2.2
     */
    public static function post_types($ad = false){

        // set defaults
        if(is_object($ad)){
            $_all = (isset($ad->conditions['posttypes']['all'])) ? 1 : 0;
            if(!$_all && !isset($ad->conditions['posttypes']['all']) && empty($ad->conditions['posttypes']['include']) && empty($ad->conditions['posttypes']['exclude'])){
                $_all = 1;
            }
        }

        ?><label class="advads-conditions-all"><input type="checkbox" name="advanced_ad[conditions][posttypes][all]" value="1" <?php checked($_all, 1); ?>><?php _e('Display on all public post types.', ADVADS_SLUG); ?></label><?php
        $post_types = get_post_types(array('public' => true, 'publicly_queryable' => true), 'object', 'or');
        ?><div class="advads-conditions-single"><?php
        // backward compatibility
        // TODO: remove in a later version
        $_includes = (!empty($ad->conditions['posttypes']['include']) && is_string($ad->conditions['posttypes']['include'])) ? explode(',', $ad->conditions['posttypes']['include']) : array();
        $_excludes = (!empty($ad->conditions['posttypes']['exclude']) && is_string($ad->conditions['posttypes']['exclude'])) ? explode(',', $ad->conditions['posttypes']['exclude']) : array();

        foreach($post_types as $_type_id => $_type){
            // backward compatibility
            // TODO: remove this in a later version
            if($_includes == array() && count($_excludes) > 0 && !in_array($_type_id, $_excludes)){
                $_val = 1;
            } elseif(in_array($_type_id, $_includes)){
                $_val = 1;
            } else {
                $_val = 0;
            }

            if(!$_val && isset($ad->conditions['posttypes']['include']) && is_array($ad->conditions['posttypes']['include']) && in_array($_type_id, $ad->conditions['posttypes']['include'])){
                $_val = 1;
            }

            ?><label><input type="checkbox" name="advanced_ad[conditions][posttypes][include][]" <?php checked($_val, 1); ?> value="<?php echo $_type_id; ?>"><?php echo $_type->label; ?></label><?php
        }
        ?></div><?php
    }
}