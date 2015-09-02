<?php
/**
 * the view for the support page
 */
?><div class="wrap">
    <?php screen_icon(); ?>
    <h1><?php echo esc_html( get_admin_page_title() ); ?></h1>
    <?php if( $mail_sent )
	?><div class="notice updated"><p><?php _e( 'Email was successfully sent.', ADVADS_SLUG ); ?></p></div><?php
    ?><h2><?php _e( 'Search', ADVADS_SLUG ); ?></h2>
    <p><?php _e( 'Use the following form to search for solutions in the manual on wpadvancedads.com', ADVADS_SLUG ); ?></p>
    <form action="https://wpadvancedads.com/" method="get">
	<input type="search" name="s"/>
	<input type="submit" value="<?php _e( 'search', ADVADS_SLUG ); ?>">
    </form>

    <h2><?php _e( 'Contact', ADVADS_SLUG ); ?></h2>
    <p><?php printf(__( 'Please search the manual for a solution and take a look at <a href="%s" target="_blank">Ads not showing up?</a> before contacting me for help.', ADVADS_SLUG ), ADVADS_URL . 'manual/ads-not-showing-up/' ); ?></p>
    <form action="" method="post">
	<label><?php _e( 'your email', ADVADS_SLUG ); ?><input type="email" name="advads_support[email]" value="<?php echo $email; ?>"/></label><br/>
	<label><?php _e( 'your name', ADVADS_SLUG ); ?><input type="text" name="advads_support[name]" value="<?php echo $name; ?>"/></label><br/>
	<label><?php _e( 'your message', ADVADS_SLUG ); ?><br/><textarea name="advads_support[message]" cols="40" rows="10"><?php echo $message; ?></textarea></label><br/>
	<input type="submit" value="<?php _e( 'send', ADVADS_SLUG ); ?>">
    </form>
</div>