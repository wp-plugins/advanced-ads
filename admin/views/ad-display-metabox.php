<?php
// include callback file
require_once(ADVADS_BASE_PATH . 'admin/includes/class-display-condition-callbacks.php');
?>
<?php $types = Advanced_Ads::get_instance()->ad_types; ?>
<p class="description"><?php _e('Choose where to display the ad and where to hide it.', ADVADS_SLUG); ?></p>
<div id="advanced-ad-conditions-enable">
<?php $conditions_enabled = (empty($ad->conditions['enabled'])) ? 0 : 1; ?>
    <label><input type="radio" name="advanced_ad[conditions][enabled]" value="0" <?php checked($conditions_enabled, 0); ?>/><?php _e('Display ad everywhere', ADVADS_SLUG); ?></label>
    <label><input type="radio" name="advanced_ad[conditions][enabled]" value="1" <?php checked($conditions_enabled, 1); ?>/><?php _e('Set display conditions', ADVADS_SLUG); ?></label>
</div>
<div id="advanced-ad-conditions">
    <ul id="advads-how-it-works">
        <li><?php _e('If you want to display the ad everywhere, don\'t do anything here. ', ADVADS_SLUG); ?></li>
        <li><?php _e('The fewer conditions you enter, the better the performance will be.', ADVADS_SLUG); ?></li>
        <li><?php printf(__('Learn more about display conditions from the <a href="%s" target="_blank">manual</a>.', ADVADS_SLUG), 'http://wpadvancedads.com/advancedads/manual/display-conditions/'); ?></li>
    </ul>
    <?php
    global $advanced_ads_ad_conditions;
    if (is_array($advanced_ads_ad_conditions)) :
        foreach ($advanced_ads_ad_conditions as $_key => $_condition) :
            if (!isset($_condition['callback']))
                continue;
            ?><div class="advanced-ad-display-condition">
            <?php
                if (is_array($_condition['callback']) && method_exists($_condition['callback'][0], $_condition['callback'][1])) {
                    call_user_func(array($_condition['callback'][0], $_condition['callback'][1]), $ad); // works also in php below 5.3
                    // $_condition['callback'][0]::$_condition['callback'][1]($ad); // works only in php 5.3 and above
                }
                ?></div><?php
            endforeach;
            ?><h4><?php _e('Other conditions', ADVADS_SLUG); ?></h4>
        <p><?php _e('When using one of the two choices on checkbox conditions, the rule is binding. E.g. "Front Page: show" will result on the ad being only visible on the front page.', ADVADS_SLUG); ?></p>
        <table>
            <thead>
                <tr>
                    <th></th>
                    <th><?php _e('show', ADVADS_SLUG); ?></th>
                    <th><?php _e('hide', ADVADS_SLUG); ?></th>
                    <th></th>
                </tr><?php
                        foreach ($advanced_ads_ad_conditions as $_key => $_condition) :
                            if (isset($_condition['callback']))
                                continue;
                            ?><tr>
                        <th><?php echo $_condition['label']; ?>
                        <?php if (!empty($_condition['description'])) : ?>
                                <span class="description" title="<?php echo $_condition['description']; ?>">(?)</span>
                        <?php endif; ?>
                        </th>
        <?php if (empty($_condition['type'])) : continue; ?>
                        <?php elseif ($_condition['type'] == 'idfield' || $_condition['type'] == 'textvalues') : ?>
                            <td><input type="text" name="advanced_ad[conditions][<?php echo $_key; ?>][include]" value="<?php if (isset($ad->conditions[$_key]['include'])) echo $ad->conditions[$_key]['include']; ?>"/></td>
                            <td><input type="text" name="advanced_ad[conditions][<?php echo $_key; ?>][exclude]" value="<?php if (isset($ad->conditions[$_key]['exclude'])) echo $ad->conditions[$_key]['exclude']; ?>"/></td>
                    <?php elseif ($_condition['type'] == 'radio') : ?>
                            <td><input type="radio" name="advanced_ad[conditions][<?php echo $_key; ?>]" value="1" <?php if (isset($ad->conditions[$_key])) checked($ad->conditions[$_key], 1) ?>/></td>
                            <td><input type="radio" name="advanced_ad[conditions][<?php echo $_key; ?>]" value="0" <?php if (isset($ad->conditions[$_key])) checked($ad->conditions[$_key], 0) ?>/></td>
        <?php endif; ?>
                        <td><button type="button" class="clear-radio"><?php _e('clear', ADVADS_SLUG); ?></button></td>
                    </tr>
                <?php endforeach; ?>
            </thead>
        </table><?php
        if(WP_DEBUG) : ?>
        <fieldset class="advads-debug-output advads-debug-output-conditions"><legend onclick="advads_toggle('.advads-debug-output-conditions .inner')"><?php
        _e('show debug output', ADVADS_SLUG); ?></legend><div class="inner" style="display:none;">
                <p class="description"><?php _e('Values saved for this ad in the database (post metas)', ADVADS_SLUG); ?></p><?php
    echo "<pre>";
    print_r($ad->conditions);
    echo "</pre>";
    ?></div></fieldset>
        <?php endif; ?>
    </div>
    <?php
 endif;