<div class="updated advads-admin-notice">
    <p><?php echo $text; ?>
	<button type="button" class="button-primary advads-notices-button-subscribe" data-notice="<?php echo $_notice ?>"><?php echo isset( $notice['confirm_text'] ) ? $notice['confirm_text'] : __('Subscribe me now', ADVADS_SLUG); ?></button>
	<button type="button" class="button-secondary advads-notices-button-close" data-notice="<?php echo $_notice; ?>"><?php _e('Close', ADVADS_SLUG); ?></button>
    </p>
</div>
