<?php
if ( ! defined( 'WPINC' ) ) {
	die();
}
$is_responsive = ('responsive' == $unit_type) ? true : false;
$use_manual_css = ('manual' == $unit_resize) ? true : false;
if ( $is_responsive ) {
	?>
	<style type="text/css">
		#advanced-ads-ad-parameters-size {
			display: none;
		}
	</style>
	<?php
}
?>
<div id="adsense-new-add-div-default">
    <input type="hidden" id="advads-ad-content-adsense" name="advanced_ad[content]" value="<?php echo esc_attr( $json_content ); ?>" />
    <input type="hidden" name="unit_id" id="unit_id" value="<?php echo esc_attr( $unit_id ); ?>" />
    <?php printf( __( '<a class="button" href="#" id="%s">Copy&Paste existing ad code</a>', ADVADS_SLUG ), 'show-pastecode-div' ); ?>
            <p id="adsense-ad-param-error"></p>
        <label>
            <?php _e( 'Ad Slot ID', ADVADS_SLUG ); ?>&nbsp;:&nbsp;
            <input type="text" name="unit-code" id="unit-code" value="<?php echo $unit_code; ?>" />
        </label>
        <label>
            <?php _e( 'Type', ADVADS_SLUG ); ?>&nbsp;:&nbsp;
            <select name="unit-type" id="unit-type">
                <option value="normal" <?php selected( $unit_type, 'normal' ); ?>><?php _e( 'Normal', ADVADS_SLUG ); ?></option>
                <option value="responsive" <?php selected( $unit_type, 'responsive' ); ?>><?php _e( 'Responsive', ADVADS_SLUG ); ?></option>
            </select>
        </label>
        <?php if ( ! defined( 'AAR_SLUG' ) ) : ?>
            <p><?php printf( __( 'Use the <a href="%s" target="_blank">Responsive add-on</a> in order to define the exact creative for each browser width.', ADVADS_SLUG ), ADVADS_URL . 'add-ons/responsive-ads/' ); ?></p>
        <?php else : ?>
        <br />
        <?php endif; ?>
		<?php
			$db = Gadsense_Data::get_instance();
			$sizing_array = $db->get_responsive_sizing();
		?>
		<label <?php if ( ! $is_responsive || 2 > count( $sizing_array ) ) { echo 'style="display: none;"'; } ?> id="resize-block"><br />
			<?php _e( 'Resizing', ADVADS_SLUG ); ?>&nbsp;:&nbsp;
			<select name="ad-resize-type" id="ad-resize-type">
			<?php foreach ( $sizing_array as $key => $desc ) : ?>
				<option value="<?php echo $key; ?>" <?php selected( $key, $unit_resize ); ?>><?php echo $desc; ?></option>
			<?php endforeach; ?>
			</select>
		</label>
	<?php do_action( 'advanced-ads-gadsense-extra-ad-param', $extra_params, $content ); ?>
</div><!-- #adsense-new-add-div-default -->
<div id="pastecode-div" style="display: none;">
	<div id="pastecode-container">
		<h3><?php _e( 'Copy the ad code from your AdSense account and paste it in the area below', ADVADS_SLUG ); ?></h3>
		<hr />
		<textarea rows="15" cols="55" id="pastecode-content"></textarea><hr />
		<button class="button button-primary" id="submit-pastecode"><?php _e( 'Get details', ADVADS_SLUG ); ?></button>&nbsp;&nbsp;
		<button class="button button-secondary" id="hide-pastecode-div"><?php _e( 'Close', ADVADS_SLUG ); ?></button>
		<div id="pastecode-msg"></div>
	</div>
</div><!-- #pastecode-div -->