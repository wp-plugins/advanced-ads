<p class="description"><?php _e( 'Everything connected to the ads layout and output.', ADVADS_SLUG ); ?></p>
<?php $options = $ad->options( 'output' ); ?>
<div id="advanced-ad-output">
    <div id="advanced-ad-output-position">
    <span class="label"><?php _e( 'Position', ADVADS_SLUG ); ?></span>
        <label><input type="radio" name="advanced_ad[output][position]" value="" title="<?php _e( '- default -', ADVADS_SLUG );
			?>" <?php if ( empty($options['position']) ) { checked( 1, 1 ); } ?>/><?php _e( 'default', ADVADS_SLUG ); ?></label>
        <label title="<?php _e( 'left', ADVADS_SLUG ); ?>"><input type="radio" name="advanced_ad[output][position]" value="left"
            <?php if ( isset($options['position']) ) { checked( $options['position'], 'left' ); } ?>/>
            <img src="<?php echo ADVADS_BASE_URL; ?>admin/assets/img/output-left.png" width="60" height="45"/></label>
        <label title="<?php _e( 'center', ADVADS_SLUG ); ?>"><input type="radio" name="advanced_ad[output][position]" value="center"
            <?php if ( isset($options['position']) ) { checked( $options['position'], 'center' ); } ?>/>
            <img src="<?php echo ADVADS_BASE_URL; ?>admin/assets/img/output-center.png" width="60" height="45"/></label>
        <label title="<?php _e( 'right', ADVADS_SLUG ); ?>"><input type="radio" name="advanced_ad[output][position]" value="right"
            <?php if ( isset($options['position']) ) { checked( $options['position'], 'right' ); } ?>/>
            <img src="<?php echo ADVADS_BASE_URL; ?>admin/assets/img/output-right.png" width="60" height="45"/></label>
    </div>
    <p><label><input type="checkbox" name="advanced_ad[output][clearfix]" value="1"<?php if ( isset($options['clearfix']) ) { checked( $options['clearfix'], 1 ); } ?>/><?php
		_e( 'Check this if you don\'t want the following elements to float around the ad. (adds a clearfix)', ADVADS_SLUG );
	?></label></p>
    <div id="advanced-ad-output-margin">
        <span class="label"><?php _e( 'Margin', ADVADS_SLUG ); ?></span>
        <label><?php _e( 'top:', ADVADS_SLUG ); ?> <input type="number" value="<?php
		if ( isset($options['margin']['top']) ) { echo $options['margin']['top']; } ?>" name="advanced_ad[output][margin][top]"/>px</label>
        <label><?php _e( 'right:', ADVADS_SLUG ); ?> <input type="number" value="<?php
		if ( isset($options['margin']['right']) ) { echo $options['margin']['right']; } ?>" name="advanced_ad[output][margin][right]"/>px</label>
        <label><?php _e( 'bottom:', ADVADS_SLUG ); ?> <input type="number" value="<?php
		if ( isset($options['margin']['bottom']) ) { echo $options['margin']['bottom']; } ?>" name="advanced_ad[output][margin][bottom]"/>px</label>
        <label><?php _e( 'left:', ADVADS_SLUG ); ?> <input type="number" value="<?php
		if ( isset($options['margin']['left']) ) { echo $options['margin']['left']; } ?>" name="advanced_ad[output][margin][left]"/>px</label>
        <p class="description"><?php _e( 'tip: use this to add a margin around the ad', ADVADS_SLUG ); ?></p>
    </div>
</div>