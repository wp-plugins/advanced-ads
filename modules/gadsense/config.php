<?php

// module configuration

$path = dirname( __FILE__ );

return array(
	'classmap' => array(
		'Advanced_Ads_Ad_Type_Adsense' => $path . '/includes/class-ad-type-adsense.php',
		'Gadsense_Data' => $path . '/includes/class-gadsense-data.php',
		'Gadsense_Admin' => $path . '/admin/class-gadsense-admin.php',
	),
	'textdomain' => null,
);
