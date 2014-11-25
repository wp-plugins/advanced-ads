<?php
/**
 * the view for the placements page
 */
?><div class="wrap">
<?php if ($error) : ?>
        <div id="message" class="error"><p><?php echo $error; ?></p></div>
    <?php endif; ?>
    <?php if ($success) : ?>
        <div id="message" class="updated"><p><?php echo $success; ?></p></div>
    <?php endif; ?>
    <?php screen_icon(); ?>
    <h1><?php echo esc_html(get_admin_page_title()); ?></h1>
    <p class="description"><?php _e('Placements are physically places in your theme and posts. You can use them if you plan to change ads and ad groups on the same place without the need to change your templates.', ADVADS_SLUG); ?></p>
    <p class="description"><?php printf(__('See also the manual for more information on <a href="%s">placements</a> and <a href="%s">auto injection</a>.', ADVADS_SLUG), 'http://wpadvancedads.com/advancedads/manual/placements/', 'http://wpadvancedads.com/advancedads/manual/auto-injection/'); ?></p>
    <h2><?php _e('Create a new placement', ADVADS_SLUG); ?></h2>
    <form method="POST" action="" class="advads-placements-new-form">
        <label for="advads-placement-type"><?php _e('Type', ADVADS_SLUG); ?></label>
        <select id="advads-plcement-type" name="advads[placement][type]">
            <?php
            if (is_array($placement_types))
                foreach ($placement_types as $_key => $_place) :
                    ?><option value="<?php echo $_key; ?>"><?php echo $_place['title']; ?></option><?php
                endforeach;
            ?>
        </select>
        <a onclick="advads_toggle('#advads-ad-place-type-info')"><?php _e('What is this?', ADVADS_SLUG); ?></a>
        <div id="advads-ad-place-type-info" style="display: none;">
            <p class="description"><?php _e('Placement types define how the placements works and where it is going to be displayed.', ADVADS_SLUG); ?></p>
            <dl><?php foreach($placement_types as $_place) : ?>
                <dt><?php echo $_place['title']; ?></dt><dd><?php echo $_place['description']; ?></dd>
            <?php endforeach; ?>
            </dl>
        </div>


        <br/>
        <label for="advads-placement-name"><?php _e('Name', ADVADS_SLUG); ?></label>
        <input id="advads-plcement-name" name="advads[placement][name]" type="text" value=""/><br/>
        <label for="advads-placement-slug"><?php _e('ID', ADVADS_SLUG); ?></label>
        <input id="advads-plcement-slug" name="advads[placement][slug]" type="text" value=""/>
        <p class="description"><?php _e('Individual identifier. Allowed are alphanumeric signs (lower case) and hyphen.', ADVADS_SLUG); ?></p>
        <p class=""><?php _e('You can assign Ads and Groups after you created the placement.', ADVADS_SLUG); ?></p>
        <input type="submit" class="button button-primary" value="<?php _e('Save New Placement', ADVADS_SLUG); ?>"/>
    </form>
<?php if (isset($placements) && is_array($placements)) : ?>
        <h2><?php _e('Placements', ADVADS_SLUG); ?></h2>
        <a onclick="advads_toggle('#advads-ad-place-display-info')"><?php _e('How to use the <i>default</i> Ad Placement?', ADVADS_SLUG); ?></a>
        <div id="advads-ad-place-display-info" style="display: none;">
            <p><?php printf(__('Examples on how to use the <i>default</i> ad placement? Find more help and examples in the <a href="%s" target="_blank">manual</a>', ADVADS_SLUG), 'http://wpadvancedads.com/advanced-ads/manual/placements/'); ?></p>
            <h4><?php _e('shortcode', ADVADS_SLUG); ?></h4>
            <p class="description"><?php _e('To use an ad placement with the ID skyscraper_left in content fields', ADVADS_SLUG); ?></p>
            <pre><input type="text" onclick="this.select();" style="width: 400px;" value='[the_ad_placement id="skyscraper_left"]'/></pre>
            <h4><?php _e('template', ADVADS_SLUG); ?></h4>
            <p class="description"><?php _e('To use an ad placement with the ID skyscraper_left in template files', ADVADS_SLUG); ?></p>
            <pre><input type="text" onclick="this.select();" style="width: 400px;" value='the_ad_placement("skyscraper_left");'/></pre>
        </div>
        <form method="POST" action="">
            <table class="advads-placements-table">
                <thead>
                    <tr>
                        <th><?php _e('Name', ADVADS_SLUG); ?></th>
                        <th><?php _e('Type', ADVADS_SLUG); ?></th>
                        <th><?php _e('ID', ADVADS_SLUG); ?></th>
                        <th><?php _e('Options', ADVADS_SLUG); ?></th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
    <?php foreach ($placements as $_placement_slug => $_placement) :
            $_placement['type'] = (!empty($_placement['type'])) ? $_placement['type'] : 'default';
        ?>
                        <tr>
                            <td><?php echo $_placement['name']; ?></td>
                            <td><?php echo (isset($_placement['type']) && !empty($placement_types[$_placement['type']]['title'])) ? $placement_types[$_placement['type']]['title'] : __('default', ADVADS_SLUG); ?></td>
                            <th><?php echo $_placement_slug; ?></th>
                            <td class="advads-placements-table-options">
                                <?php do_action('advads-placement-options-before', $_placement_slug, $_placement);
                                $items = Advads_Ad_Placements::items_for_select(); ?>
                                <label for="adsads-placements-item-<?php echo $_placement_slug; ?>"><?php _e('Item', ADVADS_SLUG); ?></label>
                                <select id="adsads-placements-item-<?php echo $_placement_slug; ?>" name="advads[placements][<?php echo $_placement_slug; ?>][item]">
                                    <option value=""><?php _e('--empty--', ADVADS_SLUG); ?></option>
                                        <?php if (isset($items['groups'])) : ?>
                                        <optgroup label="<?php _e('Ad Groups', ADVADS_SLUG); ?>">
                                            <?php foreach ($items['groups'] as $_item_id => $_item_title) : ?>
                                                <option value="<?php echo $_item_id; ?>" <?php if (isset($_placement['item'])) selected($_item_id, $_placement['item']); ?>><?php echo $_item_title; ?></option>
                                        <?php endforeach; ?>
                                        </optgroup>
                                        <?php if (isset($items['ads'])) : ?>
                                        <optgroup label="<?php _e('Ads', ADVADS_SLUG); ?>">
                                            <?php foreach ($items['ads'] as $_item_id => $_item_title) : ?>
                                                <option value="<?php echo $_item_id; ?>" <?php if (isset($_placement['item'])) selected($_item_id, $_placement['item']); ?>><?php echo $_item_title; ?></option>
                                        <?php endforeach; ?>
                                        </optgroup>
                                    <?php endif; ?>
                                <?php endif; ?>
                                </select><br/>
                                <?php
                                switch ($_placement['type']) :
                                    case 'post_content' :
                                        ?><label for="adsads-placements-options-index-<?php echo $_placement_slug; ?>"><?php _e('Index', ADVADS_SLUG); ?></label>
                                        <input type="number" id="adsads-placements-options-index-<?php echo
                               $_placement_slug;
                                        ?>" name="advads[placements][<?php echo
                               $_placement_slug;
                               ?>][options][index]" value="<?php
                                        echo (isset($_placement['options']['index'])) ? $_placement['options']['index'] : 1;
                                        ?>"/>
                                        <span class="description"><?php _e('After which paragraph to insert the placement content.', ADVADS_SLUG); ?></span>
                                        <?php
                                        break;
                                endswitch;
                                do_action('advads-placement-options-after', $_placement_slug, $_placement);
                                ?>
                            </td>
                            <td>
                                <input type="checkbox" id="adsads-placements-item-delete-<?php echo $_placement_slug; ?>" name="advads[placements][<?php echo $_placement_slug; ?>][delete]" value="1"/>
                                <label for="adsads-placements-item-delete-<?php echo $_placement_slug; ?>"><?php _e('remove placement', ADVADS_SLUG); ?></label>
                            </td>
                        </tr>
    <?php endforeach; ?>
                </tbody>
            </table>
            <input type="submit" class="button button-primary" value="<?php _e('Save Placements', ADVADS_SLUG); ?>"/>
        </form>
<?php endif; ?>
</div>
