<?php
/**
 * the view for the settings page
 */
?>

<div class="wrap">
    <?php screen_icon(); ?>
    <h1><?php echo esc_html(get_admin_page_title()); ?></h1>
    <form method="POST" action="options.php">
        <?php

        settings_fields($this->plugin_screen_hook_suffix);
        do_settings_sections( $this->plugin_screen_hook_suffix);

        do_action('advanced-ads-settings-form');
        submit_button();

        ?>
    </form>
    <hr/>
	<?php
		do_action('advanced-ads-additional-settings-form');
	?>
    <ul>
        <li><a href="/wp-admin/admin.php?page=advanced-ads-debug"><?php _e('Debug Page', ADVADS_SLUG); ?></a></li>
        <li><a href="http://wordpress.org/plugins/advanced-ads/" title="<?php _e('Advanced Ads on WordPress.org', ADVADS_SLUG); ?>"><?php _e('Advanced Ads on wp.org', ADVADS_SLUG); ?></a></li>
        <li><a href="http://webgilde.com" title="<?php _e('the company behind Advanced Ads', ADVADS_SLUG); ?>"><?php _e('webgilde GmbH', ADVADS_SLUG); ?></a></li>
    </ul>

</div>
