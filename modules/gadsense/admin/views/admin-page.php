<?php
if (!defined('WPINC')) {
	die;
}
$adsense_id = $this->data->get_adsense_id();
?>
<h3>Google AdSense</h3>
<?php if (isset($_SESSION['gadsense']['admin_notice'])) : ?>
<?php
	$notice = $_SESSION['gadsense']['admin_notice'];
	unset($_SESSION['gadsense']['admin_notice']);
?>
	<div class="<?php echo $notice['class']; ?>">
		<p><?php echo $notice['msg'] ?></p>
	</div>
<?php endif; // END isset($_SESSION['gadsense']['admin_notice']) ?>
<form method="post" id="cred-form" class="gadsense-form">
	<input type="hidden" name="gadsense-form-name" value="cred-form" />
	<input type="hidden" name="gadsense-nonce" value="<?php echo $this->nonce; ?>" />
	<label><?php _e('Account ID', ADVADS_SLUG); ?><br />
	<input type="text" name="adsense-id" id="adsense-id" size="32" value="<?php echo $adsense_id; ?>" /></label>
	<p class="description"><?php _e('Your AdSense Publisher ID <em>(pub-xxxxxxxxxxxxxx)</em>', ADVADS_SLUG) ?></p>
	<br />
	<input type="submit" class="button button-primary" value="<?php _e('Save AdSense ID', ADVADS_SLUG); ?>" />
</form>
<br />
<hr />
