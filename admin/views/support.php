<?php
/**
 * the view for the support page
 */
?><div class="wrap">
    <?php screen_icon(); ?>
    <h1><?php echo esc_html( get_admin_page_title() ); ?></h1>
    <?php if( $mail_sent ) { ?>
        <div class="notice updated"><p><?php _e( 'Email was successfully sent.', ADVADS_SLUG ); ?></p></div>
    <?php } ?>
    <h2><?php _e( 'Search', ADVADS_SLUG ); ?></h2>
    <p><?php _e( 'Use the following form to search for solutions in the manual on wpadvancedads.com', ADVADS_SLUG ); ?></p>
    <form action="https://wpadvancedads.com/" method="get">
	<input type="search" name="s"/>
	<input type="submit" value="<?php _e( 'search', ADVADS_SLUG ); ?>">
    </form>

    <h2><?php _e( 'Possible Issues', ADVADS_SLUG ); ?></h2>
    <p><?php _e( 'Please fix the issues below or try to understand their consequences before contacting support.', ADVADS_SLUG ); ?></p>

    <?php $messages = array();
    if( ! Advanced_Ads_Checks::php_version_minimum() ) :
	    $messages[] = sprintf(__( 'Your <strong>PHP version (%s) is too low</strong>. Advanced Ads is built for PHP 5.3 and higher. It might work, but updating PHP is highly recommended. Please ask your hosting provider for more information.', ADVADS_SLUG ), phpversion() );
    endif;
    if( Advanced_Ads_Checks::cache() && ! defined( 'AAP_VERSION' ) ) :
	    $messages[] = sprintf(__( 'Your <strong>website uses cache</strong>. Some dynamic features like ad rotation or visitor conditions might not work properly. Use the cache-busting feature of <a href="%s" target="_blank">Advanced Ads Pro</a> to load ads dynamically.', ADVADS_SLUG ), ADVADS_URL . 'add-ons/advanced-ads-pro' );
    endif;
    if( Advanced_Ads_Checks::wp_update_available() ) :
	    $messages[] = __( 'There is a <strong>new WordPress version available</strong>. Please update.', ADVADS_SLUG );
    endif;
    if( Advanced_Ads_Checks::plugin_updates_available() ) :
	    $messages[] = __( 'There are <strong>plugin updates available</strong>. Please update.', ADVADS_SLUG );
    endif;
    if( Advanced_Ads_Checks::licenses_invalid() ) :
	    $messages[] = sprintf( __( 'One or more license keys for <strong>Advanced Ads add-ons are invalid or missing</strong>. Please add valid license keys <a href="%s">here</a>.', ADVADS_SLUG ), admin_url( 'admin.php?page=advanced-ads-settings#top#licenses' ) );
    endif;
    if( Advanced_Ads_Checks::licenses_expired() ) :
	    $messages[] = sprintf( __( '<strong>Advanced Ads</strong> license(s) expired. Support and updates are disabled. Please visit <a href="%s"> the license page</a> for more information.', ADVADS_SLUG ), admin_url( 'admin.php?page=advanced-ads-settings#top#licenses' ) );
    endif;
    if( Advanced_Ads_Checks::active_autoptimize() && ! defined( 'AAP_VERSION' ) ) :
	    $messages[] = sprintf(__( '<strong>Autoptimize plugin detected</strong>. While this plugin is great for site performance, it is known to alter code, including scripts from ad networks. <a href="%s" target="_blank">Advanced Ads Pro</a> has a build-in support for Autoptimize.', ADVADS_SLUG ), ADVADS_URL . 'add-ons/advanced-ads-pro');
    endif;

    if( count( $messages )) :
	foreach( $messages as $_message ) :
	?><div class="message error"><p><?php echo $_message; ?></p></div><?php
	endforeach;
    endif; ?>
    <h2><?php _e( 'Contact', ADVADS_SLUG ); ?></h2>
    <p><?php printf(__( 'Please search the manual for a solution and take a look at <a href="%s" target="_blank">Ads not showing up?</a> before contacting me for help.', ADVADS_SLUG ), ADVADS_URL . 'manual/ads-not-showing-up/' ); ?></p>
    <form action="" method="post">
	<table class="form-table advads-support-form">
	    <tbody>
		<tr>
		    <th scope="row"><label for="advads-support-email"><?php _e( 'your email', ADVADS_SLUG ); ?></label></th>
		    <td scope="row"><input id="advads-support-email" class="regular-text" type="email" name="advads_support[email]" value="<?php echo $email; ?>"/></td>
		</tr>
		<tr>
		    <th scope="row"><label for="advads-support-name"><?php _e( 'your name', ADVADS_SLUG ); ?></label></th>
		    <td scope="row"><input type="text" class="regular-text" name="advads_support[name]" value="<?php echo $name; ?>"/></td>
		</tr>
		<tr>
		    <th scope="row"><label for="advads-support-text"><?php _e( 'your message', ADVADS_SLUG ); ?></label></th>
		    <td scope="row"><textarea name="advads_support[message]"><?php echo $message; ?></textarea></td>
		</tr>
		<tr>
		    <td></td>
		    <td><input type="submit" class="button button-primary" value="<?php _e( 'send', ADVADS_SLUG ); ?>"></td>
		</tr>
	    </tbody>
	</table>
    </form>
</div>