<p><?php printf(__('Ad Id: %s', ADVADS_SLUG), "<strong>$post->ID</strong>"); ?>&nbsp;&nbsp;&nbsp;
    <a onclick="advads_toggle('#advads-ad-info')"><?php _e('How to use this Ad?', ADVADS_SLUG); ?></a>
</p>
<div id="advads-ad-info" style="display: none;">
    <p><?php printf(__('How to display the ad directly? Find more help and examples in the <a href="%s" target="_blank">manual</a>', ADVADS_SLUG), 'http://wpadvancedads.com/advanced-ads/manual/display-ads/'); ?></p>
    <h4><?php _e('shortcode', ADVADS_SLUG); ?></h4>
        <p class="description"><?php _e('To display an ad in content fields', ADVADS_SLUG); ?></p>
        <pre><input type="text" onclick="this.select();" value='[the_ad id="<?php echo $post->ID; ?>"]'/></pre>
    <h4><?php _e('template', ADVADS_SLUG); ?></h4>
        <p class="description"><?php _e('To display an ad in template files', ADVADS_SLUG); ?></p>
        <pre><input type="text" onclick="this.select();" value="the_ad(<?php echo $post->ID; ?>);"/></pre>
</div>

