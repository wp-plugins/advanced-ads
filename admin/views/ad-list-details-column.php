<?php if ( ! empty($type) ) :
	?><p><strong><?php echo $type; ?></strong></p><?php
endif;
if ( ! empty($size) ) :
	?><p><?php echo $size; ?></p><?php
endif;
do_action( 'advanced-ads-ad-list-details-column-after', $ad );
