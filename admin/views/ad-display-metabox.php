<?php $types = Advanced_Ads::get_instance()->ad_types; ?>
<h4><?php _e('Where to display the ad', $this->plugin_slug); ?></h4>
<p class="advads-toggle-link" onclick="advads_toggle('#advads-how-it-works')">>>><?php _e('Click to see Help', $this->plugin_slug); ?><<<</p>
<ul id="advads-how-it-works" style="display: none;">
    <li><?php _e('If you want to display the ad everywhere, don’t do anything here. ', $this->plugin_slug); ?></li>
    <li><?php _e('The fewer conditions you enter, the better the performance will be.', $this->plugin_slug); ?></li>
    <li><?php _e('Filling more than one item into the ’show here’ text field means at least one of them needs to be true. (OR)', $this->plugin_slug); ?></li>
    <li><?php _e('Filling more than one item into the ’don’t show’ text field means all must match (AND).', $this->plugin_slug); ?></li>
    <li><?php _e('When using one of the two choices on checkbox conditions, the rule is binding. E.g. "Front Page: show here" will result on the ad being only visible on the front page.', $this->plugin_slug); ?></li>
    <li><?php _e('If there is nothing in the row, there won’t be any check. Meaning, if you leave everything empty, the ad will be displayed everywhere.', $this->plugin_slug); ?></li>
</ul>
<table id="advanced-ad-conditions">
    <thead>
        <tr>
            <th></th>
            <th><?php _e('show here', $this->plugin_slug); ?></th>
            <th><?php _e('DON’T show', $this->plugin_slug); ?></th>
            <th></th>
        </tr>
        <?php global $advanced_ads_ad_conditions;
        if (is_array($advanced_ads_ad_conditions))
            foreach ($advanced_ads_ad_conditions as $_key => $_condition) :
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
    <?php endforeach; ?>
</thead>
</table>