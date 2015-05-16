<tr class="hidden advads-ad-group-form">
    <td colspan="3">
        <label><strong><?php _e( 'Name', ADVADS_SLUG ); ?></strong><input type="text" name="advads-groups[<?php
			echo $group->id; ?>][name]" value="<?php echo $group->name; ?>"/></label><br/>
        <label><strong><?php _e( 'Description', ADVADS_SLUG ); ?></strong><input type="text" name="advads-groups[<?php
			echo $group->id; ?>][description]" value="<?php echo $group->description; ?>"/></label><br/>
        <strong><?php _e( 'Type', ADVADS_SLUG ); ?></strong>
        <ul class="advads-ad-group-type"><?php foreach ( $this->types as $_type_key => $_type ) :
			?><li><label><input type="radio" name="advads-groups[<?php echo $group->id;
				?>][type]" value="<?php echo $_type_key; ?>" <?php checked( $group->type, $_type_key )?>/><?php
				echo $_type['title']; ?></label>
                <p class="description"><?php echo $_type['description']; ?></p>
            </li><?php
		endforeach; ?></ul><div class="clear"></div>
        <label><strong><?php _e( 'Number of ads', ADVADS_SLUG ); ?></strong>
        <select name="advads-groups[<?php echo $group->id; ?>][ad_count]"><?php
		for ( $i = 1; $i <= 10; $i++ ) : ?>
                <option <?php selected( $group->ad_count, $i ); ?>><?php echo $i; ?></option>
            <?php endfor;
                ?><option <?php selected( $group->ad_count, 'all' ); ?> value="all"><?php _ex('all', 'option to display all ads in an ad groups', ADVADS_SLUG); ?></option>
		</select>
        </label>
        <p class="description"><?php _e( 'Number of ads to display in the block', ADVADS_SLUG ); ?></p>
	<?php do_action( 'advanced-ads-group-form-options', $group ); ?>
        <h3><?php _e( 'Ads', ADVADS_SLUG ); ?></h3>
        <?php if ( count( $ad_form_rows ) ) : ?>
        <table>
            <thead><tr><th><?php _e( 'Ad', ADVADS_SLUG );
			?></th><th><?php _e( 'weight', ADVADS_SLUG ); ?></th></tr></thead>
        <?php foreach ( $ad_form_rows as $_row ){
			echo $_row;
} ?></table><?php
		else : ?>
        <p><?php _e( 'No ads assigned', ADVADS_SLUG ); ?></p><?php
		endif; ?>
    </td>
</tr>