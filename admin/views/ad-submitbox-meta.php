<div id="advanced-ads-expiry-date" class="misc-pub-section curtime misc-pub-curtime">
    <label onclick="advads_toggle_box('#advanced-ads-expiry-date-enable', '#advanced-ads-expiry-date .inner')">
        <input type="checkbox" id="advanced-ads-expiry-date-enable" name="advanced_ad[expiry_date][enabled]"
            value="1" <?php checked( $enabled, 1 ); ?>/><?php _e( 'Set expiry date', ADVADS_SLUG ); ?></label><br/>
    <div class="inner" <?php if ( ! $enabled ) : ?>style="display:none;"<?php endif; ?>>
    <?php
		$month = '<select name="advanced_ad[expiry_date][month]"' . ">\n";
	for ( $i = 1; $i < 13; $i = $i + 1 ) {
		$monthnum = zeroise( $i, 2 );
		$month .= "\t\t\t" . '<option value="' . $monthnum . '" ' . selected( $monthnum, $curr_month, false ) . '>';
		$month .= sprintf( _x( '%1$s-%2$s', '1: month number (01, 02, etc.), 2: month abbreviation', ADVADS_SLUG ),
		$monthnum, $wp_locale->get_month_abbrev( $wp_locale->get_month( $i ) ) ) . "</option>\n";
	}
	$month .= '</select>';

	$day = '<input type="text" name="advanced_ad[expiry_date][day]" value="' . $curr_day . '" size="2" maxlength="2" autocomplete="off" />';
	$year = '<input type="text" name="advanced_ad[expiry_date][year]" value="' . $curr_year . '" size="4" maxlength="4" autocomplete="off" />';

		?><div class="timestamp-wrap">
	<?php printf( _x( '%1$s %2$s, %3$s', 'order of expiry date fields 1: month, 2: day, 3: year', ADVADS_SLUG ), $month, $day, $year ); ?>
        </div>
    </div>
</div>