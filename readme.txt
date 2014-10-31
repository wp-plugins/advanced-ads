=== Advanced Ads ===
Contributors: webzunft
Donate link:https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=5RRRCEBGN3UT2
Tags: ads, ad, adsense, display, banner, advertisements, adverts, advert, monetization
Requires at least: 3.5
Tested up to: 4.0.0
Stable tag: 1.2.4
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Manage and optimize your ads in WordPress as easy as creating posts. + ad injection, ad planning and ad rotation.

== Description ==

Advanced Ads is a simple ad manager made by publishers for publishers. Based on my experience delivering millions of ads per month I build this plugin as a powerful, but light weight solution to not only manage and serve ads in WordPress, but to test and optimize them as well.

Learn more on the [plugin homepage](http://wpadvancedads.com).

= create and manage ads =

* create ads as easy as creating posts
* group ads to create ad rotations
* create drafts or ads only visible to logged in users
* set a date for when to publish the ad

= display ads =

* auto inject ads (see _ad injection_ below)
* display ad in template files (with functions)
* display ad in post content (with shortcodes)
* widget to display ads in widget areas (sidebars)
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

= ad injection =

Advanced Ads comes with many options for ad injection (= display ads without the need to alter content or code)

* inject ads into header and footer
* inject ads into posts content (top, bottom, by paragraph)

= ad networks =

Advanced Ads is compatible with all ad networks and banners from affiliate programs like Google AdSense, Chitika, Clickbank, Amazon, etc.
You can also use it to add additional ad network tags into header or footer of your site without additional coding)

= based on WordPress standards =

* integrated into WordPress using standards like custom post types, taxonomies and hooks
* easily customizable by any WordPress plugin developer

Learn more on the [plugin homepage](http://wpadvancedads.com).

= Add-Ons =

* Responsive Ads – load and display ads only for specific browser sizes - [Demo](http://wpadvancedads.com/responsive-ads/)
* PopUp and Layer Ads – display ads and any other content in layers and popups - [Demo](http://wpadvancedads.com/layer-ads/)
* Sticky Ads – increase click rates with fixed, sticky and anchor ads - [Demo](http://wpadvancedads.com/sticky-ads/demo/)

== Installation ==

How to install the plugin and get it working?

= Using The WordPress Dashboard =

1. Navigate to the 'Add New' in the plugins dashboard
2. Search for 'advanced ads'
3. Click 'Install Now'
4. Activate Advanced Ads on the Plugin dashboard

= Uploading in WordPress Dashboard =

1. Navigate to the 'Add New' in the plugins dashboard
2. Navigate to the 'Upload' area
3. Select `advanced-ads.zip` from your computer
4. Click 'Install Now'
5. Activate Advanced Ads in the Plugin dashboard

= Using FTP =

1. Download `advanced-ads.zip`
2. Extract the `advanced-ads` directory to your computer
3. Upload the `advanced-ads` directory to the `/wp-content/plugins/` directory
4. Activate Advanced Ads in the Plugin dashboard

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

= 1.2.4 =

* fixed wrong links for ad groups and debug page
* display ad groups in ad list

= 1.2.3 =

major changes:

* added advanced js functions ([see some examples](http://wpadvancedads.com/javascript-functions/))
* moved taxonomies display condition to new layout
* rearranged the menu to fix its occasional disappearance
* added donation link – donations are very welcome :)

= 1.2.2 =

major changes:

* added overview page
* new layout for display condition check for post types
* added ad width and height values

fixes:

* don’t display ads that are not published or visible to logged in users only

= 1.2.1 =

major changes:

* moved auto injections from ads to placements [PLEASE MOVE YOUR INJECTIONS THERE]
* added post content injections
* reading suggestion: [My test of AdSense Responsive Ads](http://webgilde.com/en/adsense-responsive-ad/)

other fixes:

* fix bugs with ad weights throwing issues when not set
* removed public ad groups query
* updated arrays displayed on debug page
* ad groups are now displayed before ads in placements and ad widget
* added title to widget

= 1.2 =

* added widget for ads or ad groups
* added information on how to display ads, ad groups and ad placements
* tested with WordPress 4.0
* added filters and function to dynamically create a wrapper around the ad
* ! ad injection works on posts and pages now
* fixed excluded post types for ads

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

== Upgrade Notice ==

= 1.2.4 =

Fixes bug that prevented to create and edit ad groups

= 1.2.3 =

Fixes a bug with the missing menu item