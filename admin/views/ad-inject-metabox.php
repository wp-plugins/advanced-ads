<p class="description"><?php _e('Include ads on specific places automatically without shortcodes or functions.', ADVADS_SLUG); ?></p>
<?php $options = $ad->options('injection'); ?>
<?php if($options) : ?>
<p style="color: red;"><?php _e('This feature is now provided through placements. Please convert the settings made here to placements.', ADVADS_SLUG); ?></p>
<ul id="advanced-ad-injection">
    <li>
        <input type="checkbox" name="advanced_ad[injection][header]"
               id="advanced-ad-injection-header" value="1"
               <?php checked(!empty($options['header']), 1); ?>/>
        <label for="advanced-ad-injection-header"><?php _e('Include in Header (before closing </head> Tag, probably not visible)', ADVADS_SLUG); ?></label>
    </li>
    <li>
        <input type="checkbox" name="advanced_ad[injection][footer]"
               id="advanced-ad-injection-footer" value="1"
               <?php checked(!empty($options['footer']), 1); ?>/>
        <label for="advanced-ad-injection-footer"><?php _e('Include in Footer (before closing </body> Tag)', ADVADS_SLUG); ?></label>
    </li>
    <li>
        <input type="checkbox" name="advanced_ad[injection][post_start]"
               id="advanced-ad-injection-post_start" value="1"
               <?php checked(!empty($options['post_start']), 1); ?>/>
        <label for="advanced-ad-injection-post_start"><?php _e('Include before the post content', ADVADS_SLUG); ?></label>
    </li>
    <li>
        <input type="checkbox" name="advanced_ad[injection][post_end]"
               id="advanced-ad-injection-post_end" value="1"
               <?php checked(!empty($options['post_end']), 1); ?>/>
        <label for="advanced-ad-injection-post_end"><?php _e('Include after the post content', ADVADS_SLUG); ?></label>
    </li>
</ul>
<?php else : ?>
<p><?php _e('This feature is now provided through placements. Please convert the settings made here to placements.', ADVADS_SLUG); ?></p>
<?php endif; ?>