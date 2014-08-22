<?php

/**
 * Advanced Ads.
 *
 * @package   Advanced_Ads_Admin
 * @author    Thomas Maier <thomas.maier@webgilde.com>
 * @license   GPL-2.0+
 * @link      http://webgilde.com
 * @copyright 2013 Thomas Maier, webgilde GmbH
 */

/**
 * Plugin class. This class should ideally be used to work with the
 * public-facing side of the WordPress site.
 *
 * @package Advanced_Ads
 * @author  Thomas Maier <thomas.maier@webgilde.com>
 */
class Advanced_Ads {
    /**
     * Plugin version, used for cache-busting of style and script file references.
     *
     * @since   1.0.0
     * @var     string
     */

    const VERSION = '1.1.1';

    /**
     * post type slug
     *
     * @since   1.0.0
     * @var     string
     */
    const POST_TYPE_SLUG = 'advanced_ads';

    /**
     * text domain
     *
     * @since   1.0.0
     * @var     string
     */
    const TD = 'advanced-ads';

    /**
     * ad group slug
     *
     * @since   1.0.0
     * @var     string
     */
    const AD_GROUP_TAXONOMY = 'advanced_ads_groups';

    /**
     * general plugin slug
     *
     * DEPRECATED: The variable name is used as the text domain when internationalizing strings
     * of text. Its value should match the Text Domain file header in the main
     * plugin file.
     *
     * @since    1.0.0
     * @var      string
     */
    protected $plugin_slug = 'advanced-ads';

    /**
     * Instance of this class.
     *
     * @since    1.0.0
     * @var      object
     */
    protected static $instance = null;

    /**
     * ad types
     */
    public $ad_types = array();

    /**
     * plugin options
     *
     * @since   1.0.1
     * @var     array (if loaded)
     */
    protected $options = false;

    /**
     * Initialize the plugin by setting localization and loading public scripts
     * and styles.
     *
     * @since     1.0.0
     */
    private function __construct() {

        // Load plugin text domain
        add_action('init', array($this, 'load_plugin_textdomain'));

        // activate plugin when new blog is added on multisites
        add_action('wpmu_new_blog', array($this, 'activate_new_site'));

        // Load public-facing style sheet and JavaScript.
        add_action('wp_enqueue_scripts', array($this, 'enqueue_styles'));
        add_action('wp_enqueue_scripts', array($this, 'enqueue_scripts'));

        // initialize plugin specific functions
        add_action( 'init', array( $this, 'init' ) );
        register_activation_hook(__FILE__, array($this,'post_types_rewrite_flush'));

        // add short codes
        add_shortcode('the_ad', array($this, 'shortcode_display_ad'));
        add_shortcode('the_ad_group', array($this, 'shortcode_display_ad_group'));
        add_shortcode('the_ad_placement', array($this, 'shortcode_display_ad_placement'));
        // remove default ad group menu item
        add_action('admin_menu', array($this, 'remove_taxonomy_menu_item'));

        // setup default ad types
        add_filter('advanced-ads-ad-types', array($this, 'setup_default_ad_types'));

        // register hooks and filters for auto ad injection
        add_action('wp_head', array($this, 'inject_header'), 20);
        add_action('wp_footer', array($this, 'inject_footer'), 20);
        add_filter('the_content', array($this, 'inject_content'), 20);
    }

    /**
     * init / load plugin specific functions and settings
     *
     * @since 1.0.0
     */
    public function init(){
        // load ad post types
        $this->create_post_types();
        // set ad types array
        $this->set_ad_types();
    }

    /**
     * Return the plugin slug.
     *
     * @since    1.0.0
     * @return    Plugin slug variable.
     */
    public function get_plugin_slug() {
        return $this->plugin_slug;
    }

    /**
     * Return an instance of this class.
     *
     * @since     1.0.0
     * @return    object    A single instance of this class.
     */
    public static function get_instance() {

        // If the single instance hasn't been set, set it now.
        if (null == self::$instance) {
            self::$instance = new self;
        }

        return self::$instance;
    }

    /**
     * Fired when the plugin is activated.
     *
     * @since    1.0.0
     * @param    boolean    $network_wide    True if WPMU superadmin uses
     *                                       "Network Activate" action, false if
     *                                       WPMU is disabled or plugin is
     *                                       activated on an individual blog.
     */
    public static function activate($network_wide) {

        if (function_exists('is_multisite') && is_multisite()) {

            if ($network_wide) {

                // Get all blog ids
                $blog_ids = self::get_blog_ids();

                foreach ($blog_ids as $blog_id) {

                    switch_to_blog($blog_id);
                    self::single_activate();
                }

                restore_current_blog();
            } else {
                self::single_activate();
            }
        } else {
            self::single_activate();
        }
    }

    /**
     * Fired when the plugin is deactivated.
     *
     * @since    1.0.0
     * @param    boolean    $network_wide
     *
     * True if WPMU superadmin uses
     * "Network Deactivate" action, false if
     * WPMU is disabled or plugin is
     * deactivated on an individual blog.
     */
    public static function deactivate($network_wide) {

        if (function_exists('is_multisite') && is_multisite()) {

            if ($network_wide) {

                // Get all blog ids
                $blog_ids = self::get_blog_ids();

                foreach ($blog_ids as $blog_id) {

                    switch_to_blog($blog_id);
                    self::single_deactivate();
                }

                restore_current_blog();
            } else {
                self::single_deactivate();
            }
        } else {
            self::single_deactivate();
        }
    }

    /**
     * Fired when a new site is activated with a WPMU environment.
     *
     * @since    1.0.0
     * @param    int    $blog_id    ID of the new blog.
     */
    public function activate_new_site($blog_id) {

        if (1 !== did_action('wpmu_new_blog')) {
            return;
        }

        switch_to_blog($blog_id);
        self::single_activate();
        restore_current_blog();
    }

    /**
     * Get all blog ids of blogs in the current network that are:
     * - not archived
     * - not spam
     * - not deleted
     *
     * @since    1.0.0
     * @return   array|false    The blog ids, false if no matches.
     */
    private static function get_blog_ids() {

        global $wpdb;

        // get an array of blog ids
        $sql = "SELECT blog_id FROM $wpdb->blogs
			WHERE archived = '0' AND spam = '0'
			AND deleted = '0'";

        return $wpdb->get_col($sql);
    }

    /**
     * Fired for each blog when the plugin is activated.
     *
     * @since    1.0.0
     */
    private static function single_activate() {
        // @TODO: Define activation functionality here
    }

    /**
     * Fired for each blog when the plugin is deactivated.
     *
     * @since    1.0.0
     */
    private static function single_deactivate() {
        // @TODO: Define deactivation functionality here
    }

    /**
     * Load the plugin text domain for translation.
     *
     * @since    1.0.0
     */
    public function load_plugin_textdomain() {

        $domain = self::TD;
        $locale = apply_filters('advanced-ads-plugin-locale', get_locale(), $domain);

        load_textdomain($domain, trailingslashit(WP_LANG_DIR) . $domain . '/' . $domain . '-' . $locale . '.mo');
    }

    /**
     * Register and enqueue public-facing style sheet.
     *
     * @since    1.0.0
     */
    public function enqueue_styles() {
        wp_enqueue_style($this->plugin_slug . '-plugin-styles', plugins_url('assets/css/public.css', __FILE__), array(), self::VERSION);
    }

    /**
     * Register and enqueues public-facing JavaScript files.
     *
     * @since    1.0.0
     */
    public function enqueue_scripts() {
        wp_enqueue_script($this->plugin_slug . '-plugin-script', plugins_url('assets/js/public.js', __FILE__), array('jquery'), self::VERSION);
    }

    /**
     * define ad types with their options
     *
     * name => publically readable name
     * description => publically readable description
     * editor => kind of editor: text (normal text field), content (WP content field), none (no content field)
     *  will display text field, if left empty
     *
     * @since 1.0.0
     */
    public function set_ad_types() {

        /**
         * load default ad type files
         * custom ad types can also be loaded in your own plugin or functions.php
         */
        $types = array();

        /**
         * developers can add new ad types using this filter
         * see classes/ad-type-content.php for an example for an ad type and usage of this filter
         */
        $this->ad_types = apply_filters('advanced-ads-ad-types', $types);
    }

    /**
     * add plain and content ad types to the default ads of the plugin using a filter
     *
     * @since 1.0.0
     *
     */
    function setup_default_ad_types($types){
        $types['plain'] = new Advads_Ad_Type_Plain(); /* plain text and php code */
        // $types['content'] = new Advads_Ad_Type_Content(); /* rich content editor */
        return $types;
    }

    /**
     * get an array with all ads by conditions
     * = list possible ads for each condition
     *
     * @since 1.0.0
     * @return arr $ads_by_conditions
     * @todo make static
     */
    public function get_ads_by_conditions_array(){

        $ads_by_conditions = get_option('advads-ads-by-conditions', array());
        // load default array if no conditions saved yet
        if(!is_array($ads_by_conditions)){
            $ads_by_conditions = array();
        }

        return $ads_by_conditions;
    }

    /**
     * get the array with global ad injections
     *
     * @since 1.1.0
     * @return arr $ad_injections
     * @todo make static
     */
    public function get_ad_injections_array(){

        $ad_injections = get_option('advads-ads-injections', array());
        // load default array if not saved yet
        if(!is_array($ad_injections)){
            $ad_injections = array();
        }

        return $ad_injections;
    }

    /**
     * get the array with ad placements
     *
     * @since 1.1.0
     * @return arr $ad_placements
     */
    static public function get_ad_placements_array(){

        $ad_placements = get_option('advads-ads-placements', array());
        // load default array if not saved yet
        if(!is_array($ad_placements)){
            $ad_placements = array();
        }

        return $ad_placements;
    }

    /**
     * shortcode to include ad in frontend
     *
     * @since 1.0.0
     * @param arr $atts
     */
    public function shortcode_display_ad($atts){
        extract( shortcode_atts( array(
            'id' => 0,
	), $atts ) );

        // use the public available function here
        return get_ad($id);
    }

    /**
     * shortcode to include ad from an ad group in frontend
     *
     * @since 1.0.0
     * @param arr $atts
     */
    public function shortcode_display_ad_group($atts){
        extract( shortcode_atts( array(
            'id' => 0,
	), $atts ) );

        // use the public available function here
        return get_ad_group($id);
    }

    /**
     * shortcode to display content of an ad placement in frontend
     *
     * @since 1.1.0
     * @param arr $atts
     */
    public function shortcode_display_ad_placement($atts){
        extract( shortcode_atts( array(
            'id' => '',
	), $atts ) );

        // use the public available function here
        return get_ad_placement($id);
    }

    /**
     * Registers ad post type and group taxonomies
     *
     * @since 1.0.0
     */
    public function create_post_types() {
        if (did_action('init') !== 1) {
            return;
        }

        // register ad group taxonomy
        if(!taxonomy_exists(self::AD_GROUP_TAXONOMY)){
            $post_type_params = $this->get_group_taxonomy_params();
            register_taxonomy(self::AD_GROUP_TAXONOMY, array(self::POST_TYPE_SLUG), $post_type_params);
        }

        // register ad post type
        if (!post_type_exists(self::POST_TYPE_SLUG)) {
            $post_type_params = $this->get_post_type_params();
            register_post_type(self::POST_TYPE_SLUG, $post_type_params);
        }
    }

    /**
     * Defines the parameters for the ad post type taxonomy
     *
     * @since 1.0.0
     * @return array
     */
    protected function get_group_taxonomy_params(){
        $labels = array(
            'name'              => _x('Ad Groups', 'ad group general name', $this->plugin_slug),
            'singular_name'     => _x('Ad Group', 'ad group singular name', $this->plugin_slug),
            'search_items'      => __('Search Ad Groups', $this->plugin_slug),
            'all_items'         => __('All Ad Groups', $this->plugin_slug),
            'parent_item'       => __('Parent Ad Groups', $this->plugin_slug),
            'parent_item_colon' => __('Parent Ad Groups:', $this->plugin_slug),
            'edit_item'         => __('Edit Ad Group', $this->plugin_slug),
            'update_item'       => __('Update Ad Group', $this->plugin_slug),
            'add_new_item'      => __('Add New Ad Group', $this->plugin_slug),
            'new_item_name'     => __('New Ad Groups Name', $this->plugin_slug),
            'menu_name'         => __('Ad Groups', $this->plugin_slug),
            'not_found'         => __('No Ad Group found', $this->plugin_slug),
        );

        $args = array(
            'hierarchical'      => true,
            'labels'            => $labels,
            'show_ui'           => true,
            'show_in_nav_menus' => false,
            'show_tagcloud'     => false,
            'show_admin_column' => false,
            'query_var'         => true,
            'rewrite'           => false,
        );

        return $args;
    }

    /**
     * Defines the parameters for the custom post type
     *
     * @since 1.0.0
     * @return array
     */
    protected function get_post_type_params() {
        $labels = array(
            'name' => __('Ads', $this->plugin_slug),
            'singular_name' => __('Ad', $this->plugin_slug),
            'add_new' => 'Add New',
            'add_new_item' => __('Add New Ad', $this->plugin_slug),
            'edit' => __('Edit', $this->plugin_slug),
            'edit_item' => __('Edit Ad', $this->plugin_slug),
            'new_item' => __('New Ad', $this->plugin_slug),
            'view' => __('View', $this->plugin_slug),
            'view_item' => __('View the Ad', $this->plugin_slug),
            'search_items' => __('Search Ads', $this->plugin_slug),
            'not_found' => __('No Ads found', $this->plugin_slug),
            'not_found_in_trash' => __('No Ads found in Trash', $this->plugin_slug),
            'parent' => __('Parent Ad', $this->plugin_slug),
        );

        $post_type_params = array(
            'labels' => $labels,
            'singular_label' => __('Ad', $this->plugin_slug),
            'public' => false,
            'show_ui' => true,
            'menu_position' => 50, // above first seperator
            'hierarchical' => false,
            'capability_type' => 'page',
            'has_archive' => false,
            'rewrite' => array('slug' => $this->plugin_slug),
            'query_var' => true,
            'supports' => array('title'),
            'taxonomies' => array(self::AD_GROUP_TAXONOMY)
        );

        return apply_filters('advanced-ads-post-type-params', $post_type_params);
    }

    /**
     * remove WP tag edit page for the ad group taxonomy
     *  needed, because we can’t remove it with `show_ui` without also removing the meta box
     *
     * @since 1.0.0
     */
    public function remove_taxonomy_menu_item() {
        remove_submenu_page( 'edit.php?post_type=advanced_ads', 'edit-tags.php?taxonomy=advanced_ads_groups&amp;post_type=advanced_ads' );
    }

    /**
     * flush rewrites on plugin activation so permalinks for them work from the beginning on
     *
     * @since 1.0.0
     * @link http://codex.wordpress.org/Function_Reference/register_post_type#Flushing_Rewrite_on_Activation
     */
    public function post_types_rewrite_flush(){
        // load custom post type
        $this->create_post_types();
        // flush rewrite rules
        flush_rewrite_rules();
    }

    /**
     * log error messages when debug is enabled
     *
     * @since 1.0.0
     * @link http://www.smashingmagazine.com/2011/03/08/ten-things-every-wordpress-plugin-developer-should-know/
     */
    public function log($message) {
        if (WP_DEBUG === true) {
            if (is_array($message) || is_object($message)) {
                error_log('Advanced Ads Error following:', $this->plugin_slug);
                error_log(print_r($message, true));
            } else {
                $message = sprintf(__('Advanced Ads Error: %s', $this->plugin_slug), $message);
                error_log($message);
            }
        }
    }

    /**
     * return plugin options
     *
     * @since 1.0.1
     * @return arr $options
     * @todo parse default options
     */
    public function options(){
        if($this->options === false){
            $this->options = get_option(ADVADS_SLUG, array());
        }

        return $this->options;
    }

    /**
     * injected ad into header
     *
     * @since 1.1.0
     */
    public function inject_header(){
        // get information about injected ads
        $injections = get_option('advads-ads-injections', array());
        if(isset($injections['header']) && is_array($injections['header'])){
            $ads = $injections['header'];
            // randomize ads
            shuffle($ads);
            // check ads one by one for being able to be displayed on this spot
            foreach ($ads as $_ad_id) {
                // load the ad object
                $ad = new Advads_Ad($_ad_id);
                if ($ad->can_display()) {
                    // display the ad
                    echo $ad->output();
                }
            }
        }
    }

    /**
     * injected ads into footer
     *
     * @since 1.1.0
     */
    public function inject_footer(){
        // get information about injected ads
        $injections = get_option('advads-ads-injections', array());
        if(isset($injections['footer']) && is_array($injections['footer'])){
            $ads = $injections['footer'];
            // randomize ads
            shuffle($ads);
            // check ads one by one for being able to be displayed on this spot
            foreach ($ads as $_ad_id) {
                // load the ad object
                $ad = new Advads_Ad($_ad_id);
                if ($ad->can_display()) {
                    // display the ad
                    echo $ad->output();
                }
            }
        }
    }

    /**
     * injected ad into content (before and after)
     * displays ALL ads
     *
     * @since 1.1.0
     * @param str $content post content
     */
    public function inject_content($content = ''){
        // run only on single pages
        if(!is_single()) return $content;

        // get information about injected ads
        $injections = get_option('advads-ads-injections', array());

        // display ad before the content
        if(isset($injections['post_start']) && is_array($injections['post_start'])){
            $ads = $injections['post_start'];
            // randomize ads
            shuffle($ads);
            // check ads one by one for being able to be displayed on this spot
            foreach ($ads as $_ad_id) {
                // load the ad object
                $ad = new Advads_Ad($_ad_id);
                if ($ad->can_display()) {
                    // display the ad
                    $content = $ad->output() . $content;
                }
            }
        }

        // display ad after the content
        if(isset($injections['post_end']) && is_array($injections['post_end'])){
            $ads = $injections['post_end'];
            // randomize ads
            shuffle($ads);
            // check ads one by one for being able to be displayed on this spot
            foreach ($ads as $_ad_id) {
                // load the ad object
                $ad = new Advads_Ad($_ad_id);
                if ($ad->can_display()) {
                    // display the ad
                    $content .= $ad->output();
                }
            }
        }

        return $content;
    }
}
