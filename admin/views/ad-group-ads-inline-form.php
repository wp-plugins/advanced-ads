<?php if(count($ads)) : ?>
<p><form method="get" action="" class="ad-group-ads-form">
    <table>
        <tbody>
            <?php foreach($ads as $_ad) : ?>
            <tr>
                <td><?php echo $_ad->post_title; ?></td>
                <td class="ad-group-ads-weight">
                    <label>
                        <span class="title"><?php _ex('weight', 'ad group ads form', Advanced_Ads::TD); ?></span>
                        <select name="weight[<?php echo $_ad->ID; ?>]">
                            <?php $ad_weight = (isset($weights[$_ad->ID])) ? $weights[$_ad->ID] : Advads_Ad_Group::MAX_AD_GROUP_WEIGHT; ?>
                            <? for($i = 0; $i <= Advads_Ad_Group::MAX_AD_GROUP_WEIGHT; $i++) : ?>
                            <option <?php selected($ad_weight, $i); ?>><?php echo $i; ?></option>
                            <? endfor; ?>
                        </select>
                    </label>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <p class="inline-edit-save submit">
        <a href="#inline-edit" class="cancel button-secondary alignleft"><?php _e('Cancel', Advanced_Ads::TD); ?></a>
        <a href="#inline-edit" class="save button-primary alignright"><?php _e('Update', Advanced_Ads::TD); ?></a>
        <span class="spinner"></span>
        <?php wp_nonce_field('ad-groups-inline-edit-nonce', 'advads-ad-groups-inline-form-nonce', false); ?>
        <input type="hidden" name="taxonomy" value="<?php echo $group->id; ?>" />
        <br class="clear" />
    </p>
</form>
<?php else : ?>
<p><?php _e('There are no ads in this group', Advanced_Ads::TD); ?></p>
<?php endif; ?>