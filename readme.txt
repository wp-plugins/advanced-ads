=== Advanced Ads ===
Contributors: webzunft
Donate link:https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=5RRRCEBGN3UT2
Tags: ads, ad, adsense, display, banner, advertisements, adverts, advert, monetization
Requires at least: WP 3.5, PHP 5.3
Tested up to: 4.1.
Stable tag: 1.3.9
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

= ad types =

choose between different ad types that enable you to:

* insert ad networks code (e.g. Google AdSense)
* display images
* use shortcodes (to also deliver ads from other ad plugins)
* create content rich ad with the tinymc editor

= display ads =

* auto inject ads (see _ad injection_ below)
* display ad in template files (with functions)
* display ad in post content (with shortcodes)
* widget to display ads in widget areas (sidebars)
* display grouped ads based on customizable ad weight
* use placements in your theme to change ads and groups in template files without coding

= display conditions =

deliver ads based on conditions like

* individual posts, pages and other post type
* post type
* posts by category, tags, taxonomies
* archive pages by category, tags, taxonomies
* special page types like 404, attachment and front page

= visitor conditions =

display ads by conditions based on the visitor

* all devices, mobile only or exclude mobile users
* hide all ads from logged in users based on their role

= ad injection =

Advanced Ads comes with many options for ad injection (= display ads without the need to alter content or code)

* inject ads into header and footer
* inject ads into posts content (top, bottom, by paragraph)
* inject ads into content before or after a specific paragraph or headline

= ad networks =

Advanced Ads is compatible with all ad networks and banners from affiliate programs like Google AdSense, Chitika, Clickbank, Amazon, etc.
You can also use it to add additional ad network tags into header or footer of your site without additional coding)

= based on WordPress standards =

* integrated into WordPress using standards like custom post types, taxonomies and hooks
* easily customizable by any WordPress plugin developer

Learn more on the [plugin homepage](http://wpadvancedads.com).

Localizations: English, German, Italien

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
2. Align the ad and set a margin to other elements
3. Choose from various conditions where and where not to display your ad.

== Changelog ==

= 1.3.9 =

* disabled empty css file in frontend
* removed older changelog from readme
* fixed saving new ad conditions type "other" into global array

= 1.3.8 =

* fixed empty content placements still being parsed
* fixed missing or double tags created by content placements

= 1.3.7 =

* fixed bug with display conditions not working for custom post types and taxonomies
* minor fix in ad injection

= 1.3.6 =

* COOL: inject ads into content before or after specific paragraphs or headlines
* Updated translation files, German translation

= 1.3.5 =

* hotfix: fix the use of shortcodes within ads

= 1.3.4 =

* hotfix: display ads for placements when no ad group exists

= 1.3.3 =

* added column with ad details
* removed the date column from ad list
* fixed saving and retrieving of settings
* changed the dashboard icon
* renamed admin images so they won’t get blocked by ad blockers

* ACTION REQUIRED: please check and resave settings (_Advanced Ads > Settings_)

= 1.3.2 =

* hotfix: prevent infinite loops (ads within ads…) for rich content ads

= 1.3.1 =

* COOL: new ad type: rich media and formatable content – it’s like editing a normal post
* parse ad content as post content; this allows the use of shortcodes
* increased priority of content filter to reduce the risk of `wpautop` not being run yet
* finished German translation

= 1.3 =

* COOL: layout options for ads, e.g. to set floating and margins (see the [manual](http://wpadvancedads.com/advancedads/manual/optimizing-the-ad-layout/))
* list ad groups before ads when selecting them for a placement
* fixed error when removing an ad that is still in a group
* fixed possible translation issue
* added partial German translation
* added Italien translation (thanks to sangkavr)

== Upgrade Notice ==

= 1.3.2 =

Hotfix: prevent infinite loops (ads within ads) for rich content ads

= 1.3 =

Don’t miss out on the new layout options to align ads and set margins
Also fixed issues with languages and added Italien and German translation (partial)

= 1.2.4 =

Fixes bug that prevented to create and edit ad groups

= 1.2.3 =

Fixes a bug with the missing menu item