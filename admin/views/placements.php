<?php
/**
 * the view for the placements page
 */
?>

<div class="wrap">
    <?php if ($error) : ?>
	<div id="message" class="error"><p><?php echo $error; ?></p></div>
    <?php endif; ?>
    <?php if ($success) : ?>
	<div id="message" class="updated"><p><?php echo $success; ?></p></div>
    <?php endif; ?>
    <?php screen_icon(); ?>
    <h1><?php echo esc_html(get_admin_page_title()); ?></h1>
    <p class="description"><?php _e('Placements are physically places in your theme and posts. You can use them if you plan to change ads and ad groups on the same place without the need to change your templates.', ADVADS_SLUG); ?></p>
    <h2><?php _e('Create a new placement', ADVADS_SLUG); ?></h2>
    <form method="POST" action="" class="advads-placements-new-form">
        <label for="advads-placement-name"><?php _e('Name', ADVADS_SLUG); ?></label>
        <input id="advads-plcement-name" name="advads[placement][name]" type="text" value=""/><br/>
        <label for="advads-placement-slug"><?php _e('ID', ADVADS_SLUG); ?></label>
        <input id="advads-plcement-slug" name="advads[placement][slug]" type="text" value=""/>
        <p class="description"><?php _e('Individual identifier. Allowed are alphanumeric signs (lower case) and hyphen.', ADVADS_SLUG); ?></p>
        <p class=""><?php _e('You can assign Ads and Groups after you created the placement.', ADVADS_SLUG); ?></p>
        <input type="submit" class="button button-primary" value="<?php _e('Save New Placement', ADVADS_SLUG); ?>"/>
    </form>
    <?php if(isset($placements) && is_array($placements)) : ?>
    <h2><?php _e('Placements', ADVADS_SLUG); ?></h2>
    <a onclick="advads_toggle('#advads-ad-place-display-info')"><?php _e('How to use an Ad Placement?', ADVADS_SLUG); ?></a>
    <div id="advads-ad-place-display-info" style="display: none;">
        <p><?php printf(__('Examples on how to use an ad placement? Find more help and examples in the <a href="%s" target="_blank">manual</a>', ADVADS_SLUG), 'http://wpadvancedads.com/advanced-ads/manual/placements/'); ?></p>
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
                <th><?php _e('Slug', ADVADS_SLUG); ?></th>
                <th><?php _e('Name', ADVADS_SLUG); ?></th>
                <th><?php _e('Item', ADVADS_SLUG); ?></th>
                <th><?php _e('Options', ADVADS_SLUG); ?></th>
            </tr>
        </thead>
        <tbody>
    <?php foreach($placements as $_placement_slug => $_placement) : ?>
        <tr>
            <th><?php echo $_placement_slug; ?></th>
            <td><?php echo $_placement['name']; ?></td>
            <td>
                <?php $items = Advads_Ad_Placements::items_for_select(); ?>
                <select name="advads[placements][<?php echo $_placement_slug; ?>][item]">
                    <option value=""><?php _e('--empty--', ADVADS_SLUG);  ?></option>
                    <?php if(isset($items['ads'])) : ?>
                    <optgroup label="<?php _e('Ads', ADVADS_SLUG); ?>">
                    <?php foreach($items['ads'] as $_item_id => $_item_title) : ?>
                    <option value="<?php echo $_item_id; ?>" <?php if(isset($_placement['item'])) selected($_item_id, $_placement['item']); ?>><?php echo $_item_title; ?></option>
                    <?php endforeach; ?>
                    </optgroup>
                    <?php endif; ?>
                    <?php if(isset($items['groups'])) : ?>
                    <optgroup label="<?php _e('Ad Groups', ADVADS_SLUG); ?>">
                    <?php foreach($items['groups'] as $_item_id => $_item_title) : ?>
                    <option value="<?php echo $_item_id; ?>" <?php if(isset($_placement['item'])) selected($_item_id, $_placement['item']); ?>><?php echo $_item_title; ?></option>
                    <?php endforeach; ?>
                    </optgroup>
                    <?php endif; ?>
                </select>
            </td>
            <td>
                <input type="checkbox" id="adsads-placements-item-delete" name="advads[placements][<?php echo $_placement_slug; ?>][delete]" value="1"/>
                <label for="adsads-placements-item-delete"><?php _e('remove placement', ADVADS_SLUG); ?></label>
            </td>
        </tr>
    <?php    endforeach; ?>
        </tbody>
    </table>
        <input type="submit" class="button button-primary" value="<?php _e('Save Placements', ADVADS_SLUG); ?>"/>
    </form>
    <?php    endif; ?>
</div>
