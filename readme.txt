=== Advanced Ads ===
Contributors: webzunft
Tags: ads, ad, adsense
Requires at least: 3.5
Tested up to: 3.9.2
Stable tag: 1.1.3
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Manage and optimize your ads in WordPress as easy as creating posts.

== Description ==

Advanced Ads is made by publishers for publishers. Based on my experience delivering millions of ads per month I build this plugin as a powerful, but light weight solution to not only manage and serve ads in WordPress, but to test and optimize them as well.

Learn more on the [plugin homepage](http://wpadvancedads.com).

= create and manage ads =

* create ads as easy as creating posts
* group ads

= display ads =

* auto inject ads into header, footer and posts
* display ad in template files (with functions)
* display ad in post content (with shortcodes)
* display grouped ads based on customizable ad weight
* use placements in your theme to change ads and groups in template files without coding

= display conditions =

deliver ads based on conditions like

* post type
* post id
* category
* single, category and archive pages
* special page types like 404, attachment and front page

= visitor conditions =

display ads by conditions based on the visitor

* all devices, mobile only or exclude mobile users
* hide all ads from logged in users based on their role

= based on WordPress standards =

* integrated into WordPress using standards like custom post types, taxonomies and hooks
* easily customizable by any WordPress plugin developer

Learn more on the [plugin homepage](http://wpadvancedads.com).

= AddOns =

* increase click rates with fixed, sticky and anchor ads with Sticky Ads - [Demo](http://wpadvancedads.com/sticky-ads/demo/)

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

In addition to directly displaying ads and groups you can define ad placements and assign either an ad or group to them.

`[the_ad_placement id="header-left"]`
`<?php the_ad_placement('header-left'); ?>`

== Frequently Asked Questions ==

= Is there a revenue share? =

There is no revenue share. Advanced Ads doesn’t alter your ad codes in a way that you earn less than you would directly including the ad code in your template.

== Screenshots ==

1. Create an ad almost like you would create an article or page.
2. Choose from various conditions where and where not to display your ad.

== Changelog ==

= 1.1.3 =

* minor changes for better extendability for the [sticky ads addon](http://wpadvancedads.com/sticky-ads/)

= 1.1.2 =

* composer bugfix
* changes some unclear descriptions
* use group names instead of slug on placement page
* reenabled handles for metaboxes on ad edit screen
* added success message for placement updates

= 1.1.1 =

* added filter to be able to add own checks whether to display an ad or not
* added action to add content to the visitor metabox
* option to hide/disable ad conditions
* option to hide all ads from logged in users based on user roles

= 1.1.0 =

* allow displaying ads on mobile devices only or exclude from mobile devices
* auto inject ad into header, footer and post content
* display Ad id on Ad edit page
* hide Ad for groups if the Ad is not made public
* use Ad Placements to be more flexible when displaying ads or ad group in template files
* bugfixes

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
