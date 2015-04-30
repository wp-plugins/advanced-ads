<tr class="advads-group-row">
    <td>
        <input type="hidden" class="advads-group-id" name="advads-groups[<?php echo $group->id; ?>][id]" value="<?php echo $group->id; ?>"/>
        <strong><a class="row-title" href="#"><?php echo $group->name; ?></a></strong>
        <p class="description"><?php echo $group->description; ?></p>
        <?php echo $this->render_action_links( $group ); ?>
        <div class="hidden advads-usage">
            <label><?php _e( 'shortcode', ADVADS_SLUG ); ?>
                <code><input type="text" onclick="this.select();" style="width: 200px;" value='[the_ad_group id="<?php echo $group->id; ?>"]'/></code>
            </label><br/>
            <label><?php _e( 'template', ADVADS_SLUG ); ?>
                <code><input type="text" onclick="this.select();" value="the_ad_group(<?php echo $group->id; ?>);"/></code>
            </label>
            <p><?php printf( __( 'Learn more about using groups in the <a href="%s" target="_blank">manual</a>.', ADVADS_SLUG ), ADVADS_URL . 'advanced-ads/manual/ad-groups/' ); ?></p>
        </div>
    </td>
    <td>
        <ul><?php $_type = isset($this->types[$group->type]['title']) ? $this->types[$group->type]['title'] : 'default'; ?>
            <li><strong><?php printf( __( 'Type: %s', ADVADS_SLUG ), $_type ); ?></strong></li>
            <li><?php printf( __( 'ID: %s', ADVADS_SLUG ), $group->id ); ?></li>
            <li><?php printf( __( 'Slug: %s', ADVADS_SLUG ), $group->slug ); ?></li>
        </ul>
    </td>
    <td class="advads-ad-group-list-ads"><?php $this->render_ads_list( $group ); ?></td>
</tr>