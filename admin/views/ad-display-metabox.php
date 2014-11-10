<?php // include callback file
require_once(ADVADS_BASE_PATH . 'admin/includes/class-display-condition-callbacks.php');
?>
<?php $types = Advanced_Ads::get_instance()->ad_types; ?>
<p class="description"><?php _e('Choose where to display the ad and where not.', ADVADS_SLUG); ?></p>
<div id="advanced-ad-conditions-enable">
    <?php $conditions_enabled = (empty($ad->conditions['enabled'])) ? 0 : 1; ?>
    <label><input type="radio" name="advanced_ad[conditions][enabled]" value="0" <?php checked($conditions_enabled, 0); ?>/><?php _e('Display ad everywhere', ADVADS_SLUG); ?></label>
    <label><input type="radio" name="advanced_ad[conditions][enabled]" value="1" <?php checked($conditions_enabled, 1); ?>/><?php _e('Set display conditions', ADVADS_SLUG); ?></label>
</div>
<p class="advads-toggle-link" onclick="advads_toggle('#advads-how-it-works')">>>><?php _e('Click to see Help', $this->plugin_slug); ?><<<</p>
<ul id="advads-how-it-works" style="display: none;">
    <li><?php _e('If you want to display the ad everywhere, don’t do anything here. ', $this->plugin_slug); ?></li>
    <li><?php _e('The fewer conditions you enter, the better the performance will be.', $this->plugin_slug); ?></li>
    <li><?php _e('Filling more than one item into the ’show here’ text field means at least one of them needs to be true. (OR)', $this->plugin_slug); ?></li>
    <li><?php _e('Filling more than one item into the ’don’t show’ text field means all must match (AND).', $this->plugin_slug); ?></li>
    <li><?php _e('When using one of the two choices on checkbox conditions, the rule is binding. E.g. "Front Page: show here" will result on the ad being only visible on the front page.', $this->plugin_slug); ?></li>
    <li><?php _e('If there is nothing in the row, there won’t be any check. Meaning, if you leave everything empty, the ad will be displayed everywhere.', $this->plugin_slug); ?></li>
</ul>
<div id="advanced-ad-conditions">
<?php global $advanced_ads_ad_conditions;
    if (is_array($advanced_ads_ad_conditions)) :
        foreach ($advanced_ads_ad_conditions as $_key => $_condition) :
            if(!isset($_condition['callback'])) continue;
            ?><div class="advanced-ad-display-condition">
                <?php if(is_array($_condition['callback']) && method_exists($_condition['callback'][0], $_condition['callback'][1])) {
                    call_user_func(array($_condition['callback'][0], $_condition['callback'][1]), $ad); // works also in php below 5.3
                    // $_condition['callback'][0]::$_condition['callback'][1]($ad); // works only in php 5.3 and above
                }
            ?></div><?php
        endforeach;
        ?><p><?php _e('UPDATE NOTICE: I am currently moving the old settings from below to a new form (above). Don’t worry, the old settings will still work in the future.', ADVADS_SLUG); ?></p>
    <table>
    <thead>
        <tr>
            <th></th>
            <th><?php _e('show here', $this->plugin_slug); ?></th>
            <th><?php _e('DON’T show', $this->plugin_slug); ?></th>
            <th></th>
        </tr><?php
            foreach ($advanced_ads_ad_conditions as $_key => $_condition) :
                if(isset($_condition['callback'])) continue;
            ?><tr>
                    <th><?php echo $_condition['label']; ?>
                    <?php if (!empty($_condition['description'])) : ?>
                            <span class="description" title="<?php echo $_condition['description']; ?>">(?)</span>
                    <?php endif; ?>
                    </th>
                    <?php if (empty($_condition['type'])) : continue; ?>
                    <?php elseif ($_condition['type'] == 'idfield' || $_condition['type'] == 'textvalues') : ?>
                        <td><input type="text" name="advanced_ad[conditions][<?php echo $_key; ?>][include]" value="<?php if(isset($ad->conditions[$_key]['include'])) echo $ad->conditions[$_key]['include']; ?>"/></td>
                        <td><input type="text" name="advanced_ad[conditions][<?php echo $_key; ?>][exclude]" value="<?php if(isset($ad->conditions[$_key]['exclude'])) echo $ad->conditions[$_key]['exclude']; ?>"/></td>
                    <?php elseif ($_condition['type'] == 'radio') : ?>
                        <td><input type="radio" name="advanced_ad[conditions][<?php echo $_key; ?>]" value="1" <?php if(isset($ad->conditions[$_key])) checked($ad->conditions[$_key], 1) ?>/></td>
                        <td><input type="radio" name="advanced_ad[conditions][<?php echo $_key; ?>]" value="0" <?php if(isset($ad->conditions[$_key])) checked($ad->conditions[$_key], 0) ?>/></td>
                <?php endif; ?>
                    <td><button type="button" class="clear-radio"><?php _e('clear', $this->plugin_slug); ?></button></td>
                </tr>
    <?php endforeach;?>
</thead>
</table>
    </div>
<?php endif;