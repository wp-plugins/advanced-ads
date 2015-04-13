=== Advanced Ads ===
Contributors: webzunft
Donate link:https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=5RRRCEBGN3UT2
Tags: ads, ad, adsense, display, banner, advertisements, adverts, advert, monetization
Requires at least: WP 3.5, PHP 5.3
Tested up to: 4.2 beta
Stable tag: 1.4.8
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Manage and optimize your ads in WordPress as easy as creating posts. Including support for AdSense, ad injection, ad planning and ad rotation.

== Description ==

Advanced Ads is a simple ad manager made by a publisher for publishers. Based on my experience delivering millions of ads per month I built this plugin as a powerful, but light weight solution to not only manage and serve ads in WordPress, but to test and optimize them as well.

Learn more on the [plugin homepage](http://wpadvancedads.com).

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

global conditions

* disable all ads in the frontend (e.g. when your ad network breaks down)
* disable all ads on 404 pages (e.g. AdSense doesn’t allow that)
* disable all ads on non-singular pages with a single click

= visitor conditions =

display ads by conditions based on the visitor

* display ads on all devices, mobile only or exclude mobile users
* hide all ads from logged in users based on their role
* display ads by exact browser width with the [Responsive add-on](http://wpadvancedads.com/responsive-ads/)

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
* assistant for exact sizes of responsive ads with the [Responsive add-on](http://wpadvancedads.com/responsive-ads/)
* (more coming soon)

= based on WordPress standards =

* integrated into WordPress using standards like custom post types, taxonomies and hooks
* easily customizable by any WordPress plugin developer

Learn more on the [plugin homepage](http://wpadvancedads.com).

Localizations: English, German, Italien, Portuguese

> <strong>Add-Ons</strong>
>
> * Tracking – ad tracking and statistics – [more](http://wpadvancedads.com/ad-tracking/)
> * Responsive Ads – create mobile ads or ads for specific browser sizes - [Demo](http://wpadvancedads.com/responsive-ads/)
> * Sticky Ads – increase click rates with fixed, sticky, and anchor ads - [Demo](http://wpadvancedads.com/sticky-ads/demo/)
> * PopUp and Layer Ads – display ads and other content in layers and popups - [Demo](http://wpadvancedads.com/layer-ads/)

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

= 1.3.18 =

* removed wrapper for header injection placement
* removed deprecated code used for ad based content injections
* ordered ads by title in ads list
* removed broken pagination from ad groups list. now, all ad groups are displayed
* order ad groups by name when no other order is specified
* fixed search for ad groups in ad groups list
* PHP is not automatically allowed for new plain text ad codes anymore
* add an internal description and notes to your ads

= 1.3.17 =

* allow ad injection in all public post types now
* added Portuguese translation, props to [brunobarros](https://wordpress.org/support/profile/brunobarros)
* added advanced js file into repository

= 1.3.16 =

* fixed minor issue in admin js
* fixed expiry date showing up on other post types too

= 1.3.15 =

* COOL: added expiry date for ads, see the [manual](http://wpadvancedads.com/advancedads/manual/start-expiry-date/)
* removed limit on ads loaded for one group, props to [brunobarros](https://wordpress.org/support/topic/bug-without-posts_per_page)
* updated German translation

= 1.3.14 =

* fixed ad wrapper class for [Advanced Ads Layer add-on](http://wpadvancedads.com/layer-ads/)

= 1.3.13 =

* fixed ad wrapper options disappearing for placements

= 1.3.12 =

* limited number of terms on ad edit screen to 200, introduced _advanced-ads-admin-max-terms_ filter
* wrapped placement ads in a container with a unique id and a class to target them with css and js
* added dashboard widget with plugin version and news about ad optimization

Good to know: [What you didn’t know about the AdSense Program Policies](http://webgilde.com/en/adsense-program-policies/).

= 1.3.11 =

* COOL: disable ads completely, on 404 pages or for non-singular pages with a single click
* renamed hooks starting with _advads_ to _advanced-ads_ for better names consistency
* ordered ads by ad title not by date in placement and widget ad select list

Good to know: AdSense does not allow ads on 404 pages, so if you use AdSense a lot, be sure to check this new option on your settings page.

= 1.3.10 =

* COOL: disable all ads on individual single pages
* fixed saving some ad conditions to global array
* fixed minor issue with empty ad condition
* updated translation files
* updated German translation

Developers might want to take a look at the [Codex](http://wpadvancedads.com/advancedads/codex/). I am currently updating the cool stuff in there.

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

[Changelog Archive](http://wpadvancedads.com/advancedads/codex/changelog-archive/)

== Upgrade Notice ==

= 1.3.2 =

Hotfix: prevent infinite loops (ads within ads) for rich content ads

= 1.3 =

Don’t miss out on the new layout options to align ads and set margins
Also fixed issues with languages and added Italien and German translation (partial)
