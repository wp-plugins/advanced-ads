<fieldset class="inline-edit-col-left">
    <div class="inline-edit-col"><?php
	if( $post_future ) :
	?><p><?php printf(__( 'starts %s', 'advanced-ads' ), date( 'd.m.Y, H:i', $post_future ) ); ?></p><?php
	endif;
	if( $expiry ) :
	?><p><?php printf(__( 'expires %s', 'advanced-ads' ), date( 'd.m.Y, H:i', $expiry ) ); ?></p><?php
	endif;
if ( ! empty($size) ) :
	?><p><?php echo $size; ?></p><?php
endif;
do_action( 'advanced-ads-ad-list-timing-column-after', $ad );
?></div></fieldset>