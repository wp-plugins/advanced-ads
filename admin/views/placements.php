<?php
/**
 * the view for the placements page
 */
?><div class="wrap">
<?php if ( isset($_GET['message'] ) ) :
	if ( $_GET['message'] === 'error' ) :
	?><div id="message" class="error"><p><?php _e( 'Couldn’t create the new placement. Please check your form field and whether the name is already in use.', ADVADS_SLUG ); ?></p></div><?php
	elseif ( $_GET['message'] === 'updated' ) :
	?><div id="message" class="updated"><p><?php _e( 'Placements updated', ADVADS_SLUG ); ?></p></div><?php
	endif; ?>
<?php endif; ?>
    <?php screen_icon(); ?>
    <h2><?php echo esc_html( get_admin_page_title() ); ?></h2>
    <p class="description"><?php _e( 'Placements are physically places in your theme and posts. You can use them if you plan to change ads and ad groups on the same place without the need to change your templates.', ADVADS_SLUG ); ?></p>
    <p class="description"><?php printf( __( 'See also the manual for more information on <a href="%s">placements</a>.', ADVADS_SLUG ), ADVADS_URL . 'manual/placements/' ); ?></p>
<?php if ( isset($placements) && is_array( $placements ) && count( $placements ) ) : ?>
        <h2><?php _e( 'Placements', ADVADS_SLUG ); ?></h2>
        <form method="POST" action="">
            <table class="widefat advads-placements-table">
                <thead>
                    <tr>
                        <th><?php _e( 'Type', ADVADS_SLUG ); ?></th>
                        <th><?php _e( 'Name', ADVADS_SLUG ); ?></th>
                        <th><?php _e( 'Options', ADVADS_SLUG ); ?></th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
    <?php foreach ( $placements as $_placement_slug => $_placement ) :
			$_placement['type'] = ( ! empty($_placement['type'])) ? $_placement['type'] : 'default';
		?>
                        <tr>
                            <td><?php
				if( isset($_placement['type'] )) :
				    if( isset( $placement_types[$_placement['type']]['image'] )) :
					    ?><img src="<?php echo $placement_types[$_placement['type']]['image'];
					    ?>" title="<?php echo $placement_types[$_placement['type']]['title']; ?>"/><?php
				    else :
					echo $placement_types[$_placement['type']]['title'];
				    endif;
				else :
				    __( 'default', ADVADS_SLUG );
				endif;
				?></td>
                            <td><?php echo $_placement['name']; ?><br/>
				<?php if( 'default' === $_placement['type']) :
				    ?><a class="usage-link"><?php _e( 'show usage', ADVADS_SLUG ); ?></a><div class="hidden advads-usage">
				    <label><?php _e( 'shortcode', ADVADS_SLUG ); ?>
					<code><input type="text" onclick="this.select();" value='[the_ad_placement id="<?php echo $_placement_slug; ?>"]'/></code>
				    </label>
				    <label><?php _e( 'template', ADVADS_SLUG ); ?>
					<code><input type="text" onclick="this.select();" value="the_ad_placement('<?php echo $_placement_slug; ?>');"/></code>
				    </label>
				</div><?php
				 endif;
			    ?></td>
                            <td class="advads-placements-table-options">
                                <?php do_action( 'advanced-ads-placement-options-before', $_placement_slug, $_placement ); ?>
                                <label for="adsads-placements-item-<?php echo $_placement_slug; ?>"><?php _e( 'Item', ADVADS_SLUG ); ?></label>
                                <select id="adsads-placements-item-<?php echo $_placement_slug; ?>" name="advads[placements][<?php echo $_placement_slug; ?>][item]">
                                    <option value=""><?php _e( '--not selected--', ADVADS_SLUG ); ?></option>
                                        <?php if ( isset($items['groups']) ) : ?>
                                        <optgroup label="<?php _e( 'Ad Groups', ADVADS_SLUG ); ?>">
                                            <?php foreach ( $items['groups'] as $_item_id => $_item_title ) : ?>
                                                <option value="<?php echo $_item_id; ?>" <?php if ( isset($_placement['item']) ) { selected( $_item_id, $_placement['item'] ); } ?>><?php echo $_item_title; ?></option>
                                        <?php endforeach; ?>
                                        </optgroup>
                                        <?php endif; ?>
                                        <?php if ( isset($items['ads']) ) : ?>
                                        <optgroup label="<?php _e( 'Ads', ADVADS_SLUG ); ?>">
                                        <?php foreach ( $items['ads'] as $_item_id => $_item_title ) : ?>
                                                <option value="<?php echo $_item_id; ?>" <?php if ( isset($_placement['item']) ) { selected( $_item_id, $_placement['item'] ); } ?>><?php echo $_item_title; ?></option>
                                        <?php endforeach; ?>
                                        </optgroup>
                                        <?php endif; ?>
                                </select><br/>
                                <?php
								switch ( $_placement['type'] ) :
									case 'post_content' :
										?><div class="advads-placement-options"><?php
										_e( 'Inject', ADVADS_SLUG );
										$_positions = array('after' => __( 'after', ADVADS_SLUG ), 'before' => __( 'before', ADVADS_SLUG )); ?>
                                        <select name="advads[placements][<?php echo $_placement_slug; ?>][options][position]">
                                            <?php foreach ( $_positions as $_pos_key => $_pos ) : ?>
                                            <option value="<?php echo $_pos_key; ?>" <?php if ( isset($_placement['options']['position']) ) { selected( $_placement['options']['position'], $_pos_key ); } ?>><?php echo $_pos; ?></option>
                                            <?php endforeach; ?>
                                        </select>

                                        <input type="number" name="advads[placements][<?php echo $_placement_slug; ?>][options][index]" value="<?php
										echo (isset($_placement['options']['index'])) ? $_placement['options']['index'] : 1;
										?>"/>.

                                        <?php $tags = Advanced_Ads_Placements::tags_for_content_injection(); ?>
                                        <select name="advads[placements][<?php echo $_placement_slug; ?>][options][tag]">
                                            <?php foreach ( $tags as $_tag_key => $_tag ) : ?>
                                            <option value="<?php echo $_tag_key; ?>" <?php if ( isset($_placement['options']['tag']) ) { selected( $_placement['options']['tag'], $_tag_key ); } ?>><?php echo $_tag; ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                        </div><?php
										break;
								endswitch;
								do_action( 'advanced-ads-placement-options-after', $_placement_slug, $_placement );
                            ?></td>
                            <td>
                                <input type="checkbox" id="adsads-placements-item-delete-<?php echo $_placement_slug; ?>" name="advads[placements][<?php echo $_placement_slug; ?>][delete]" value="1"/>
                                <label for="adsads-placements-item-delete-<?php echo $_placement_slug; ?>"><?php _e( 'remove placement', ADVADS_SLUG ); ?></label>
                            </td>
                        </tr>
    <?php endforeach; ?>
                </tbody>
            </table>
            <input type="submit" class="button button-primary" value="<?php _e( 'Save Placements', ADVADS_SLUG ); ?>"/>
	    <?php wp_nonce_field( 'advads-placement', 'advads_placement', true ) ?>
	    <button type="button" title="<?php _e( 'Create a new placement', ADVADS_SLUG ); ?>" class="button-secondary" onclick="advads_toggle('.advads-placements-new-form')"><?php
	    _e( 'New Placement', ADVADS_SLUG ); ?></button>
        </form>
	<?php do_action( 'advanced-ads-placements-list-after', $placements );
endif;

    ?><form method="POST" action="" class="advads-placements-new-form"<?php if ( isset($placements) && count( $placements ) ) echo ' style="display: none;"' ; ?>>
	<h3>1. <?php _e( 'Choose a placement type', ADVADS_SLUG ); ?></h3>
	<p class="description"><?php printf(__( 'Placement types define where the ad is going to be displayed. Learn more about the different types from the <a href="%s">manual</a>', ADVADS_SLUG ), ADVADS_URL . 'manual/placements/' ); ?></p>
	<div class= "advads-new-placement-types advads-buttonset">
	<?php
	if ( is_array( $placement_types ) ) {
		foreach ( $placement_types as $_key => $_place ) :
		    if( isset( $_place['image'] )) :
			    $image = '<img src="' . $_place['image'] . '"/>';
		    else :
			    $image = '<strong>' . $_place['title'] . '</strong><br/><p class="description">' . $_place['description'] . '</p>';
		    endif;
		    ?><div class="advads-placement-type"><label for="advads-placement-type-<?php echo $_key; ?>"><?php echo $image; ?></label>
			<input type="radio" id="advads-placement-type-<?php echo $_key; ?>" name="advads[placement][type]" value="<?php echo $_key; ?>"/>
			<p class="advads-placement-description"><strong><?php echo $_place['title'] ?></strong><br/><?php echo $_place['description']; ?></p>
		    </div><?php
		endforeach; };
		?></div>
	<div class="clear"></div><br/>
	<h3>2. <?php _e( 'Choose a Name', ADVADS_SLUG ); ?></h3>
	<p class="description"><?php _e( 'The name of the placement is only visible to you. Tip: choose a descriptive one, e.g. <em>Below Post Headline</em>.', ADVADS_SLUG ); ?></p>
        <p><input name="advads[placement][name]" type="text" value="" placeholder="<?php _e( 'Placement Name', ADVADS_SLUG ); ?>"/></p>
	<h3>3. <?php _e( 'Choose the Ad or Group', ADVADS_SLUG ); ?></h3>
	<p class="description"><?php _e( 'The ad or group that should be displayed.', ADVADS_SLUG ); ?></p>
	<p><select name="advads[placement][item]">
	    <option value=""><?php _e( '--not selected--', ADVADS_SLUG ); ?></option>
		<?php if ( isset($items['groups']) ) : ?>
		<optgroup label="<?php _e( 'Ad Groups', ADVADS_SLUG ); ?>">
		    <?php foreach ( $items['groups'] as $_item_id => $_item_title ) : ?>
			<option value="<?php echo $_item_id; ?>"><?php echo $_item_title; ?></option>
		<?php endforeach; ?>
		</optgroup>
		<?php endif; ?>
		<?php if ( isset($items['ads']) ) : ?>
		<optgroup label="<?php _e( 'Ads', ADVADS_SLUG ); ?>">
		<?php foreach ( $items['ads'] as $_item_id => $_item_title ) : ?>
			<option value="<?php echo $_item_id; ?>"><?php echo $_item_title; ?></option>
		<?php endforeach; ?>
		</optgroup>
		<?php endif; ?>
	    </select></p>
	<?php wp_nonce_field( 'advads-placement', 'advads_placement', true ) ?>
        <input type="submit" class="button button-primary" value="<?php _e( 'Save New Placement', ADVADS_SLUG ); ?>"/>
    </form>
</div>
