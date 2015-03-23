<?php
/**
 * page lists ad groups
 *
 * @since 1.0.0
 * @see /wp-admin/edit-tags.php (for a good example in WP core)
 */
$ad_group_table = new AdvAds_Groups_List_Table(array('screen' => get_current_screen()));
$ad_group_table->textdomain = $this->plugin_slug;
$pagenum = $ad_group_table->get_pagenum();

$ad_group_table->prepare_items();
$total_pages = $ad_group_table->get_pagination_arg('total_pages');

if ($pagenum > $total_pages && $total_pages > 0) {
    wp_redirect(add_query_arg('paged', $total_pages));
    exit;
}

/**
 * @TODO those 2 scripts needed? or even extend it with our own?
 */
// wp_enqueue_script('admin-tags');

if (current_user_can($tax->cap->edit_terms)) {
  wp_enqueue_script('inline-edit-group-ads');
}

// require_once( ABSPATH . 'wp-admin/admin-header.php' );

$messages[$taxonomy] = array(
    0 => '', // Unused. Messages start at index 1.
    1 => __('Ad Group added.', ADVADS_SLUG),
    2 => __('Ad Group deleted.', ADVADS_SLUG),
    3 => __('Ad Group updated.', ADVADS_SLUG),
    4 => __('Ad Group not added.', ADVADS_SLUG),
    5 => __('Ad Group not updated.', ADVADS_SLUG),
    6 => __('Ad Group deleted.', ADVADS_SLUG)
);

$message = false;
if (isset($_REQUEST['message']) && ( $msg = (int) $_REQUEST['message'] ) || isset($forced_message)) {
    if (isset($msg) && isset($messages[$taxonomy][$msg])){
        $message = $messages[$taxonomy][$msg];
    } elseif(isset($messages[$taxonomy][$forced_message])) {
        $message = $messages[$taxonomy][$forced_message];
    }
}
?>

<div class="wrap nosubsub">
    <h2><?php
        echo esc_html($title);
        if (!empty($_REQUEST['s'])) {
            printf('<span class="subtitle">' . __('Search results for &#8220;%s&#8221;', ADVADS_SLUG) . '</span>', esc_html(wp_unslash($_REQUEST['s'])));
        } else {
            echo ' <a href="' . Advanced_Ads_Admin::group_page_url(array('action' => 'edit')) . '" class="add-new-h2">' . $tax->labels->add_new_item . '</a>';
        }
        ?>
    </h2>
    <p><?php _e('Ad Groups are a very flexible method to bundle ads. You can use them to display random ads in the frontend or run split tests, but also just for informational purposes. Not only can an Ad Groups have multiple ads, but an ad can belong to multiple ad groups.', ADVADS_SLUG); ?></p>
    <?php if ($message) : ?>
        <div id="message" class="updated"><p><?php echo $message; ?></p></div>
        <?php
        $_SERVER['REQUEST_URI'] = remove_query_arg(array('message'), $_SERVER['REQUEST_URI']);
    endif;
    ?>
    <div id="ajax-response"></div>
    <a onclick="advads_toggle('#advads-ad-group-display-info')"><?php _e('How to display an Ad Group?', ADVADS_SLUG); ?></a>
    <div id="advads-ad-group-display-info" style="display: none;">
        <p><?php printf(__('Examples on how to display an ad group? Find more help and examples in the <a href="%s" target="_blank">manual</a>', ADVADS_SLUG), 'http://wpadvancedads.com/advanced-ads/manual/ad-groups/'); ?></p>
        <h4><?php _e('shortcode', ADVADS_SLUG); ?></h4>
            <p class="description"><?php _e('To display an ad group with the ID 6 in content fields', ADVADS_SLUG); ?></p>
            <pre><input type="text" onclick="this.select();" style="width: 200px;" value='[the_ad_group id="6"]'/></pre>
        <h4><?php _e('template', ADVADS_SLUG); ?></h4>
            <p class="description"><?php _e('To display an ad group with the ID 6 in template files', ADVADS_SLUG); ?></p>
            <pre><input type="text" onclick="this.select();" value="the_ad_group(6);"/></pre>
    </div>

    <div id="col-container">
        <div class="col-wrap">
            <form class="search-form" action="" method="get">
                <!--input type="hidden" name="taxonomy" value="<?php echo esc_attr($taxonomy); ?>" /-->
                <input type="hidden" name="page" value="advanced-ads-groups" />
                <?php $ad_group_table->search_box($tax->labels->search_items, 'tag'); ?>

            </form>
            <br class="clear" />
            <form id="posts-filter" action="" method="post">
                <input type="hidden" name="taxonomy" value="<?php echo esc_attr($taxonomy); ?>" />
                <input type="hidden" name="post_type" value="<?php echo esc_attr($post_type); ?>" />

                <?php $ad_group_table->display(); ?>

                <br class="clear" />
            </form><?php
            /**
             * Fires after the ad grouptable.
             *
             * The dynamic portion of the hook name, $taxonomy, refers to the taxonomy slug.
             *
             * @since 1.0.0
             * @param string $taxonomy The taxonomy name.
             */
            do_action("after-{$taxonomy}-table", $taxonomy);
            ?>

        </div>
    </div><!-- /col-container -->
</div><!-- /wrap -->