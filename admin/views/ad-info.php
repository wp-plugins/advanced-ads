<div id="advads-ad-info">
    <span><?php printf( __( 'Ad Id: %s', ADVADS_SLUG ), "<strong>$post->ID</strong>" ); ?></span>
    <label><span><?php _e( 'shortcode', ADVADS_SLUG ); ?></span>
	<pre><input type="text" onclick="this.select();" value='[the_ad id="<?php echo $post->ID; ?>"]'/></pre></label>
    <label><span><?php _e( 'theme function', ADVADS_SLUG ); ?></span>
	<pre><input type="text" onclick="this.select();" value="&lt;?php the_ad(<?php echo $post->ID; ?>); ?&gt;"/></pre></label>
    <span><?php printf( __( 'Find more display options in the <a href="%s" target="_blank">manual</a>.', ADVADS_SLUG ), ADVADS_URL . 'manual/display-ads/' ); ?></span>
</div>
</p>
<div id="advads-ad-description">
    <?php if ( ! empty($ad->description) ) : ?>
    <p title="<?php _e( 'click to change', ADVADS_SLUG ); ?>"
       onclick="advads_toggle('#advads-ad-description textarea'); advads_toggle('#advads-ad-description p')"><?php
		echo nl2br( $ad->description ); ?></p>
    <?php else : ?>
    <button type="button" onclick="advads_toggle('#advads-ad-description textarea'); advads_toggle('#advads-ad-description button')"><?php _e( 'Add a description', ADVADS_SLUG ); ?></button>
    <?php endif; ?>
    <textarea name="advanced_ad[description]" placeholder="<?php
		_e( 'Internal description or your own notes about this ad.', ADVADS_SLUG ); ?>"><?php echo $ad->description; ?></textarea>
</div>