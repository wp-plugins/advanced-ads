<?php
/**
 * the view for the settings page
 */

// array with setting tabs for frontend
$setting_tabs = apply_filters('advanced-ads-setting-tabs', array(
	'general' => array(
		'page' => $this->plugin_screen_hook_suffix,
		'group' => ADVADS_SLUG,
		'tabid' => 'general',
		'title' => __( 'General', ADVADS_SLUG )
	),
	'licenses' => array(
		'page' => 'advanced-ads-settings-license-page',
		'group' => ADVADS_SLUG . '-licenses',
		'tabid' => 'licenses',
		'title' => __( 'Licenses', ADVADS_SLUG )
	)
));
?><div class="wrap">
    <?php screen_icon(); ?>
    <h2><?php echo esc_html( get_admin_page_title() ); ?></h2>
    <?php settings_errors(); ?>
    <h2 class="nav-tab-wrapper" id="advads-tabs">
        <?php foreach ( $setting_tabs as $_setting_tab_id => $_setting_tab ) : ?>
            <a class="nav-tab" id="<?php echo $_setting_tab_id; ?>-tab"
                href="#top#<?php echo $_setting_tab_id; ?>"><?php echo $_setting_tab['title']; ?></a>
        <?php endforeach; ?>
    </h2>
        <?php foreach ( $setting_tabs as $_setting_tab_id => $_setting_tab ) : ?>
    <form method="POST" action="options.php">
            <div id="<?php echo $_setting_tab_id; ?>" class="advads-tab"><?php
			if ( isset( $_setting_tab['group'] ) ) {
				settings_fields( $_setting_tab['group'] );
			}
			do_settings_sections( $_setting_tab['page'] );

			do_action( 'advanced-ads-settings-form', $_setting_tab_id, $_setting_tab );
			submit_button( __( 'Save settings on this page', ADVADS_SLUG ) );
				?></div></form>
        <?php endforeach; ?>
        <?php
			do_action( 'advanced-ads-additional-settings-form' );
		?>
    <ul>
        <li><a href="/wp-admin/admin.php?page=advanced-ads-debug"><?php _e( 'Debug Page', ADVADS_SLUG ); ?></a></li>
        <li><a href="http://wordpress.org/plugins/advanced-ads/" title="<?php _e( 'Advanced Ads on WordPress.org', ADVADS_SLUG ); ?>"><?php _e( 'Advanced Ads on wp.org', ADVADS_SLUG ); ?></a></li>
        <li><a href="http://webgilde.com" title="<?php _e( 'the company behind Advanced Ads', ADVADS_SLUG ); ?>"><?php _e( 'webgilde GmbH', ADVADS_SLUG ); ?></a></li>
    </ul>

</div>
