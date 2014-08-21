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
        /**
         * only display setting field if any registered
         * @TODO remove when base plugin has its own settings
         */
        global $wp_settings_fields;

        if(isset($wp_settings_fields['advanced_ads_page_advanced-ads-settings'])){
            settings_fields($this->plugin_screen_hook_suffix);
            do_settings_sections( $this->plugin_screen_hook_suffix);

            do_action('advanced-ads-settings-form');
            submit_button();
        } else {
            echo '<p>'.__('No settings available yet', $this->plugin_slug).'</p>';
        }

        ?>
    </form>
    <hr/>
    <ul>
        <li><a href="/wp-admin/edit.php?post_type=advanced_ads&page=advanced-ads-debug"><?php _e('Debug Page', $this->plugin_slug); ?></a></li>
        <li><a href="http://wordpress.org/plugins/advanced-ads/" title="<?php _e('Advanced Ads on WordPress.org', $this->plugin_slug); ?>"><?php _e('Advanced Ads on wp.org', $this->plugin_slug); ?></a></li>
        <li><a href="http://webgilde.com" title="<?php _e('the company behind Advanced Ads', $this->plugin_slug); ?>"><?php _e('webgilde GmbH', $this->plugin_slug); ?></a></li>
    </ul>

</div>
