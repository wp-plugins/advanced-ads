<input type="text" class="regular-text" placeholder="<?php _e('License key', AAT_SLUG); ?>"
       name="<?php echo ADVADS_SLUG . '-licenses'; ?>[<?php echo $index; ?>]"
       value="<?php echo esc_attr_e($license_key); ?>"
       <?php if( $license_status === 'valid' ) echo ' disabled="disabled"'; ?>/><?php
if( $license_status !== false && $license_status == 'valid' ) :
    $show_active = true;
else :
    $show_active = false;
    if($license_key !== '') :
        ?><button type="button" class="button-secondary advads-license-activate"
		data-addon="<?php echo $index; ?>"
		data-pluginname="<?php echo $plugin_name; ?>"
		data-optionslug="<?php echo $options_slug; ?>"
		name="advads_license_activate"><?php _e('Activate License'); ?></button><?php
    endif;
    $errortext = ( ! $license_status || $license_status == 'invalid') ? __('license key invalid', AAT_SLUG) : '';
    ?><span class="advads-license-activate-error"><?php echo $errortext; ?></span><?php
endif;
?><span class="advads-license-activate-active" <?php if( ! $show_active ) echo 'style="display: none;"'; ?>><?php _e('active', AAT_SLUG); ?></span><?php
if($license_key === '') :
    ?><p class="description"><?php _e('1. enter the key and save options; 2. click the activate button behind the field'); ?></p><?php
endif;