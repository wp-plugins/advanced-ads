<label><input id="advanced-ads-disable-ads-all" type="checkbox" value="1" name="<?php
																				echo ADVADS_SLUG ?>[disabled-ads][all]" <?php checked( $disable_all, 1 );
	?>><?php _e( 'Disable all ads in frontend', ADVADS_SLUG ); ?></label>
<p class="description"><?php _e( 'Use this option to disable all ads in the frontend, but still be able to use the plugin.', ADVADS_SLUG ); ?></p>

<label><input id="advanced-ads-disable-ads-404" type="checkbox" value="1" name="<?php
	echo ADVADS_SLUG; ?>[disabled-ads][404]" <?php checked( $disable_404, 1 );
	?>><?php _e( 'Disable ads on 404 error pages', ADVADS_SLUG ); ?></label>

<br/><label><input id="advanced-ads-disable-ads-archives" type="checkbox" value="1" name="<?php
	echo ADVADS_SLUG; ?>[disabled-ads][archives]" <?php checked( $disable_archives, 1 );
	?>><?php _e( 'Disable ads on non-singular pages', ADVADS_SLUG ); ?></label>
    <p class="description"><?php _e( 'e.g. archive pages like categories, tags, authors, front page (if a list)', ADVADS_SLUG ); ?></p>
<label><input id="advanced-ads-disable-ads-secondary" type="checkbox" value="1" name="<?php
	echo ADVADS_SLUG; ?>[disabled-ads][secondary]" <?php checked( $disable_secondary, 1 );
	?>><?php _e( 'Disable ads on secondary queries', ADVADS_SLUG ); ?></label>
    <p class="description"><?php _e( 'Secondary queries are custom queries of posts outside the main query of a page. Try this option if you see ads injected on places where they shouldn’t appear.', ADVADS_SLUG ); ?></p>