=== Advanced Ads ===
Contributors: webzunft
Donate link:https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=5RRRCEBGN3UT2
Tags: ads, ad, adsense, display, banner, advertisements, adverts, advert, monetization
Requires at least: WP 3.5, PHP 5.3
Tested up to: 4.2.2
Stable tag: 1.5.4.1
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Manage and optimize your ads in WordPress as easy as creating posts. Including support for AdSense, ad injection, ad planning and ad rotation.

== Description ==

Advanced Ads is a simple ad manager made by a publisher for publishers. Based on my experience delivering millions of ads per month I built this plugin as a powerful, but light weight solution to not only manage and serve ads in WordPress, but to test and optimize them as well.

Learn more on the [plugin homepage](https://wpadvancedads.com).

= create and manage ads =

* create ads as easy as creating posts
* group ads to create ad rotations
* create drafts or ads only visible to logged in users
* set a date for when to publish the ad
* make internal notes about each ad

= ad types =

choose between different ad types that enable you to:

* insert code for ad and affiliate networks (e.g., Chitika, Amazon)
* dedicated support for Google AdSense
* display images and image banners
* use shortcodes (to also deliver ads from other ad plugins)
* create content rich ad with the tinymc editor

= display ads =

* auto inject ads (see _ad injection_ below)
* display ad in template files (with functions)
* display ad in post content (with shortcodes)
* widget to display ads in widget areas (sidebars)
* display grouped ads based on customizable ad weight
* use placements in your theme to change ads and groups in template files without coding
* disable all ads on individual single pages
* set start time and expiry date for ads
* display multiple ads from an ad group (ad blocks)
* define the order of ads from an ad group and allow default ads

= display conditions =

deliver ads based on conditions like

* individual posts, pages and other post type
* post type
* posts by category, tags, taxonomies
* archive pages by category, tags, taxonomies
* special page types like 404, attachment and front page
* hide ads on secondary queries (e.g. posts in sidebars)

global conditions

* disable all ads in the frontend (e.g. when your ad network breaks down)
* disable all ads on 404 pages (e.g. AdSense doesn’t allow that)
* disable all ads on non-singular pages with a single click
* disable all ads in secondary queries
* hide ads from bots and web crawlers

= visitor conditions =

display ads by conditions based on the visitor

* display ads on all devices, mobile only or exclude mobile users
* hide all ads from logged in users based on their role
* display ads by exact browser width with the [Responsive add-on](https://wpadvancedads.com/add-ons/responsive-ads/)

= ad injection =

Advanced Ads comes with many options for ad injection (= display ads without the need to alter content or code)

* inject ads into header and footer
* inject ads into posts content (top, bottom, by paragraph)
* inject ads into content before or after a specific paragraph or headline

= ad networks =

Advanced Ads is compatible with all ad networks and banners from affiliate programs like Google AdSense, Chitika, Clickbank, Amazon, etc.
You can also use it to add additional ad network tags into header or footer of your site without additional coding)

= Google AdSense =

There is an ad type dedicated to Google AdSense that supports:

* switch ad sizes
* switch between normal and responsive
* automatic limit 3 AdSense ads according to AdSense terms of service (can be disabled)
* assistant for exact sizes of responsive ads with the [Responsive add-on](https://wpadvancedads.com/add-ons/responsive-ads/)
* (more coming soon)

= based on WordPress standards =

* integrated into WordPress using standards like custom post types, taxonomies and hooks
* easily customizable by any WordPress plugin developer

Learn more on the [plugin homepage](https://wpadvancedads.com).

Localizations: English, German, Italien, Portuguese

> <strong>Add-Ons</strong>
>
> * Tracking – ad tracking and statistics – [more](https://wpadvancedads.com/add-ons/tracking/)
> * Responsive Ads – create mobile ads or ads for specific browser sizes - [Demo](https://wpadvancedads.com/add-ons/responsive-ads/)
> * Sticky Ads – increase click rates with fixed, sticky, and anchor ads - [Demo](https://wpadvancedads.com/sticky-ads/demo/)
> * PopUp and Layer Ads – display ads and other content in layers and popups - [Demo](https://wpadvancedads.com/add-ons/layer-ads/)

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

= 1.5.4.1 =

* hotfix for new visitor conditions not showing up

= 1.5.4 =

* PLEASE READ the [update notice](https://wpadvancedads.com/advanced-ads-1-5-4/) to learn more about the changes on visitor conditions
* visitor conditions completely rewritten to allow combination of multiple conditions
* created simpler placement creation
* reordered Advanced Ads dashboard
* added AdSense tutorial

= 1.5.3 =

* display all ads of an ad group
* no ad wrapper is created if the main ad content is empty
* hide ad meta box on posts and pages for non admins
* display if ad expired on group overview
* added tutorial subscription
* added notices and newsletter logic
* new hook `advanced-ads-debug-after`
* updated all class names from "Advads_" to "Advanced_Ads_"
* updated German translation

= 1.5.2.1 =

* fixed inclusion / exclusion of ads for single posts

= 1.5.2 =

* fixed empty bots not excluded if option is activated
* fixed updated placements not showing up right away
* removing spaces from AdSense publisher id
* simplify admin capabilities for modules
* fixed admin includes to avoid relative paths
* fixed a warning in add-on admin settings
* fixed wrong path to advanced.js
* removed old code and global ad conditions previously saved in field 'advads-ads-by-conditions'
* updated German translation

= 1.5.1 =

* added tab menu für settings
* allow to set ad weights for pending, future and private ads
* improvements to groups overview based on group types
* fixed switching ad types
* fixed individual post conditions not showing up
* fixed update message being displayed for new installations
* other fixes under the hood
* new hooks: `advanced-ads-setting-tabs`, `advanced-ads-adsense-settings-init`
* removed hooks: `advanced-ads-gadsense-after-id-changed`

= 1.5.0 =

* major changes in the code base to support upcoming features
* further interface cleanup
* fully implemented autoloading
* added composer definitions
* hook modules deep into ad selection and display
* autoload modules (for base plugin and add-ons)
* added `advanced-ads-ad-select-args` filter to modify ad selection arguments
* added `advanced-ads-ad-select-methods` filter to append or override ad code selection methods
* standardise and autoload modules
* add AJAX handler

= 1.4.9 =

* added option to hide ads from crawlers and other bots (option is disabled by default)
* added Secondary Queries display condition, e.g. to hide ads from posts in sidebars
* added frontend function `advads_can_display_ads()` to check if ads are displayed in general
* global option to disable all ads in secondary queries
* search for term ids in display conditions
* fixed ad conditions using conditional tags of subquery instead of the main query
* fixed search for terms in display conditions

= 1.4.8 =

* COMPLETE MAKEOVER OF AD GROUPS
* added ordered ad group type to control the order of ads displayed
* display multiple ads from an ad group (allowing ad blocks)
* fixed wrong group ids displaying ads
* fixed ads group output being empty on first frontend impression
* added filter `advanced-ads-group-types`

= 1.4.7 =

* COOL: beautiful selection of terms in display conditions
* search for terms if there are more than 50 in the current taxonomy
* updated more messages in the dashboard
* fixed expiry date discrepancy
* minor general code fixes
* minor fix for AdSense ads

= 1.4.6 =

* hotfix

= 1.4.5 =

* optimized code for some WordPress coding standards
* ad content injection now also supports tags with attributes (e.g. `<h2 class="headline">)
* added `advanced-ads-output-inside-wrapper` filter
* avoid session for gadsense module option page
* complete makeover of display conditions for specific page types
* added logic for important update messages
* fix for `is_home` condition

= 1.4.4 =

* possible hotfix for update issue
* cleared unneeded sessions for better performance

= 1.4.3 =

* COOL: complete makeover of the plugin dashboard based on WP standards
* added `advanced-ads-admin-overview-after` action hook to overview page
* fixed display of only 10 posts for display conditions
* minor optimization
* updated German translation

= 1.4.2 =

* COOL: [vote for and suggest features](http://wpadvancedads.com/advancedads/feature-requests/)
* switching from an existing plain text ad with AdSense code into the AdSense ad type gets the right options automatically
* added Advanced Ads Tutorials rss to dashboard widget

Need ad analytics and impression tracking? Try the [tracking add-on](http://wpadvancedads.com/ad-tracking/).

= 1.4.1 =

* COOL: limitation of AdSense ads prevents you from breaking the AdSense terms of service (can be disabled)
* added option to change the content injection priority
* load ad output for content injection only, if injection is possible
* added hook `advanced-ads-settings-init` to add new settings
* renamed multiple hooks in the AdSense module
* updated German translation

= 1.4.0 =

* COOL: AdSense ad type, [manual](http://wpadvancedads.com/advancedads/manual/ad-types/adsense-ads/)
* added multiple action hooks
* fix translation of textdomain if the plugin folder is renamed
* load pro module, if exists
* updated German translation

[Changelog Archive](http://wpadvancedads.com/advancedads/codex/changelog-archive/)

== Upgrade Notice ==

= 1.3.2 =

Hotfix: prevent infinite loops (ads within ads) for rich content ads

= 1.3 =

Don’t miss out on the new layout options to align ads and set margins
Also fixed issues with languages and added Italien and German translation (partial)
