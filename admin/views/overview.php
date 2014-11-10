<?php
/**
 * the view for the settings page
 */
?><div class="wrap">
    <?php screen_icon(); ?>
    <h1><?php echo esc_html(get_admin_page_title()); ?></h1>
    <div class="advads-content-wrapper">
        <div class="advads-content-left">
            <div class="advads-box">
                <div class="advads-content-half">
                    <h2><?php _e('Ads', ADVADS_SLUG); ?></h2>
                    <p class="description"><?php _e('Ads are the smallest unit, containing the content or a single ad to be displayed.', ADVADS_SLUG); ?></p>
                    <p><?php printf(__('You have published %d ads.', ADVADS_SLUG), count($recent_ads));?>&nbsp;<?php
                    printf(__('<a href="%s">Manage</a> them or <a href="%s">create</a> a new one', ADVADS_SLUG),
                            'edit.php?post_type='. Advanced_Ads::POST_TYPE_SLUG,
                            'post-new.php?post_type='. Advanced_Ads::POST_TYPE_SLUG);
                    ?>
                    </p>
                </div>
                <div class="advads-content-half">
                    <?php if(count($recent_ads) > 0) : ?>
                    <h4><?php _e('recent ads', ADVADS_SLUG); ?></h4>
                    <ul>
                    <?php foreach($recent_ads as $_index => $_ad) : ?>
                        <li><a href="<?php echo get_edit_post_link($_ad->ID); ?>"><?php echo $_ad->post_title; ?></a></li><?php
                        if($_index == 2) break;
                        ?>
                    <?php endforeach; ?>
                    </ul>
                    <?php endif; ?>
                </div>
                <br class="clear"/>
            </div>
            <div class="advads-box">
                <div class="advads-content-half">
                    <h2><?php _e('Groups', ADVADS_SLUG); ?></h2>
                    <p class="description"><?php _e('Ad Groups contain ads and are currently used to rotate multiple ads on a single spot.', ADVADS_SLUG); ?></p>
                    <p><?php printf(__('You have %d groups.', ADVADS_SLUG), count($groups)); ?>&nbsp;<?php
                    printf(__('<a href="%s">Manage</a> them.', ADVADS_SLUG),
                            'admin.php?page=advanced-ads-groups');
                    ?></p>
                </div>
                <div class="advads-content-half">
                    <?php if(count($groups) > 0) : ?>
                    <h4><?php _e('recent groups', ADVADS_SLUG); ?></h4>
                    <ul>
                    <?php foreach($groups as $_index => $_group) : ?>
                        <li><?php echo $_group->name; ?></li><?php
                        if($_index == 2) break;
                        ?>
                    <?php endforeach; ?>
                    </ul>
                    <?php endif; ?>
                </div>
            <br class="clear"/>
            </div>
            <div class="advads-box">
                <div class="advads-content-half">
                    <h2><?php _e('Placements', ADVADS_SLUG); ?></h2>
                    <p class="description"><?php _e('Ad Placements are the best way to manage where to display ads and groups.', ADVADS_SLUG); ?></p>
                    <p><?php printf(__('You have %d placements.', ADVADS_SLUG), count($placements)); ?>&nbsp;<?php
                    printf(__('<a href="%s">Manage</a> them.', ADVADS_SLUG),
                            'admin.php?page=advanced-ads-placements');
                    ?></p>
                </div>
                <div class="advads-content-half">
                    <?php if(count($placements) > 0) : ?>
                    <h4><?php _e('recent placements', ADVADS_SLUG); ?></h4>
                    <ul>
                    <?php $_i = 0; foreach($placements as $_index => $_placement) : ?>
                        <li><?php echo $_placement['name']; ?></li><?php
                        if($_i++ == 2) break;
                        ?>
                    <?php endforeach; ?>
                    </ul>
                    <?php endif; ?>
                </div>
            <br class="clear"/>
            </div>
        </div>
        <div class="advads-content-right">
            <div class="advads-box">
                <h3><?php _e('Manual and Support', ADVADS_SLUG); ?></h3>
                <p><?php _e('Need some help? These are your options', ADVADS_SLUG); ?></p>
                <ul>
                    <li><?php printf(__('Visit the <a href="%s">plugin homepage</a>', ADVADS_SLUG), 'http://wpadvancedads.com/advancedads/'); ?></li>
                    <li><?php printf(__('Have a look into the <a href="%s">manual</a>', ADVADS_SLUG), 'http://wpadvancedads.com/advancedads/manual/'); ?></li>
                    <li><?php printf(__('Ask a question to other users in the <a href="%s">wordpress.org forum</a>', ADVADS_SLUG), 'http://wordpress.org/plugins/advanced-ads/'); ?></li>
                    <li><?php printf(__('<a href="%s">Hire the developer</a>', ADVADS_SLUG), 'http://webgilde.com/en/contact/'); ?></li>
                </ul>
            </div>
            <div class="advads-box">
                <h3><?php _e('Add-ons', ADVADS_SLUG); ?></h3>
                <p><?php _e('Want to boost your ad income? Try these add-ons', ADVADS_SLUG); ?></p>
                <ul>
                    <li><a href="http://wpadvancedads.com/responsive-ads/?utm_campaign=advads&utm_medium=plugin&utm_source=overview" target="_blank">Responsive Ads</a></li>
                    <li><a href="http://wpadvancedads.com/layer-ads/?utm_campaign=advads&utm_medium=plugin&utm_source=overview" target="_blank">PopUp and Layer Ads</a></li>
                    <li><a href="http://wpadvancedads.com/sticky-ads/?utm_campaign=advads&utm_medium=plugin&utm_source=overview" target="_blank">Sticky Ads</a></li>
                </ul>
            </div>
        </div>
    </div>
</div>
