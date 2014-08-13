=== Advanced Ads ===
Contributors: webzunft
Tags: ads, ad, adsense
Requires at least: 3.5
Tested up to: 3.9.2
Stable tag: 1.0.3
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Manage and optimize your ads in WordPress with this easy to use and extendable plugin.

== Description ==

= Features =

* create and manage ads
* deliver ads based on conditions (e.g. post type, post id, category)
* display ad in template files (with functions)
* display ad in post content (with shortcodes)
* group ads
* display ads from groups with customized ad weight
* integrated into WordPress to be as much compatible as possible with WP standards, functions and plugins
* easily customizable by any WordPress plugin developer

= Insights for developers =

* ads are custom post types
* many filters and hooks to add new functions without hacking the plugin and keeping it updateable
* e.g. add your own ad types and display conditions using the api
* an extended online manual is in progress

learn more on the [plugin homepage](http://wpadvancedads.com).

== Installation ==

This section describes how to install the plugin and get it working.

e.g.

= Using The WordPress Dashboard =

1. Navigate to the 'Add New' in the plugins dashboard
2. Search for 'plugin-name'
3. Click 'Install Now'
4. Activate the plugin on the Plugin dashboard

= Uploading in WordPress Dashboard =

1. Navigate to the 'Add New' in the plugins dashboard
2. Navigate to the 'Upload' area
3. Select `plugin-name.zip` from your computer
4. Click 'Install Now'
5. Activate the plugin in the Plugin dashboard

= Using FTP =

1. Download `plugin-name.zip`
2. Extract the `plugin-name` directory to your computer
3. Upload the `plugin-name` directory to the `/wp-content/plugins/` directory
4. Activate the plugin in the Plugin dashboard

== Displaying Ads ==

You can use functions and shortcodes to display ads and ad groups.

The integers in this example are the IDs of the elements.

Use these shortcode to insert an ad or ad group into your post/page.

`[the_ad id="24"]`
`[the_ad_group id="5"]`

Use these functions to insert an ad or ad group into your template file.

`<?php the_ad(24); ?>`
`<?php the_ad_group(5); ?>`

== Frequently Asked Questions ==

= Is there a revenue share? =

There is no revenue share. Advanced Ads doesn’t alter your ad codes in a way that you earn less than you would directly including the ad code in your template.

== Screenshots ==

1. Create an ad almost like you would create an article or page.
2. Choose from various conditions where and where not to display your ad.

== Changelog ==

= 1.0.3 =

* bugfix added missing file to repository

= 1.0.2 =

* bugfix for editing ad weights in ad groups
* bugfix for autoloader

= 1.0.1 =

* several new hooks
* seperated settings and debug page
* few internal optimizations
* few bugfixes for php < 5.3

= 1.0 =
* first release
