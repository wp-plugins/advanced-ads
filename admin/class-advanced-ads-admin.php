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
 * administrative side of the WordPress site.
 *
 *
 * @package Advanced_Ads_Admin
 * @author  Thomas Maier <thomas.maier@webgilde.com>
 */
class Advanced_Ads_Admin {

    /**
     * Instance of this class.
     *
     * @since    1.0.0
     * @var      object
     */
    protected static $instance = null;

    /**
     * Slug of the settings page
     *
     * @since    1.0.0
     * @var      string
     */
    public $plugin_screen_hook_suffix = null;

    /**
     * Slug of the ad group page
     *
     * @since    1.0.0
     * @var      string
     */
    protected $ad_group_hook_suffix = null;

    /**
     * general plugin slug
     *
     * @since   1.0.0
     * @var     string
     */
    protected $plugin_slug = '';

    /**
     * post type slug
     *
     * @since   1.0.0
     * @var     string
     */
    protected $post_type = '';

    /**
     * Initialize the plugin by loading admin scripts & styles and adding a
     * settings page and menu.
     *
     * @since     1.0.0
     */
    private function __construct() {

        /*
         * Call $plugin_slug from public plugin class.
         *
         */
        $plugin = Advanced_Ads::get_instance();
        $this->plugin_slug = $plugin->get_plugin_slug();
        $this->post_type = constant("Advanced_Ads::POST_TYPE_SLUG");

        // Load admin style sheet and JavaScript.
        add_action('admin_enqueue_scripts', array($this, 'enqueue_admin_styles'));
        add_action('admin_enqueue_scripts', array($this, 'enqueue_admin_scripts'));

        // Add menu items
        add_action('admin_menu', array($this, 'add_plugin_admin_menu'));

        // on post/ad edit screen
        add_action('edit_form_after_title', array($this, 'edit_form_below_title'));
        add_action('admin_init', array($this, 'add_meta_boxes'));

        // save ads post type
        add_action('save_post', array($this, 'save_ad'));

        // settings handling
        add_action('admin_init', array($this, 'settings_init'));

        // admin notices
        add_action('admin_notices', array($this, 'admin_notices'));

        // Add an action link pointing to the options page.
        $plugin_basename = plugin_basename(plugin_dir_path('__DIR__') . $this->plugin_slug . '.php');
        add_filter('plugin_action_links_' . $plugin_basename, array($this, 'add_action_links'));

    }

    /**
     * Return an instance of this class.
     *
     * @since     1.0.0
     *
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
     * Register and enqueue admin-specific style sheet.
     *
     * @since     1.0.0
     *
     * @return    null    Return early if no settings page is registered.
     */
    public function enqueue_admin_styles() {

        global $post;
        if (!isset($this->plugin_screen_hook_suffix) && isset($post) && Advanced_Ads::POST_TYPE_SLUG != $post->type) {
            return;
        }

        wp_enqueue_style($this->plugin_slug . '-admin-styles', plugins_url('assets/css/admin.css', __FILE__), array(), Advanced_Ads::VERSION);
    }

    /**
     * Register and enqueue admin-specific JavaScript.
     *
     * @since     1.0.0
     *
     * @return    null    Return early if no settings page is registered.
     */
    public function enqueue_admin_scripts() {

        global $post;
        if (!isset($this->plugin_screen_hook_suffix) && isset($post) && Advanced_Ads::POST_TYPE_SLUG != $post->type) {
            return;
        }

        wp_enqueue_script($this->plugin_slug . '-admin-script', plugins_url('assets/js/admin.js', __FILE__), array('jquery', 'jquery-ui-autocomplete'), Advanced_Ads::VERSION);

        // just register this script for later inclusion on ad group list page
        wp_register_script('inline-edit-group-ads', plugins_url('assets/js/inline-edit-group-ads.js', __FILE__), array('jquery'), Advanced_Ads::VERSION);

    }

    /**
    * display admin notices
     *
     * @since 1.2.1
    */
    public function admin_notices()
    {

        // display notice in case there are still old ad injections
        $old_injections = get_option('advads-ads-injections', array());

        // display ad before the content
        if(isset($old_injections) && count($old_injections) > 0){
            $_injection_ids = array();
            foreach($old_injections as $_inj){
                $_injection_ids = array_merge($_injection_ids, $_inj);
            }
            $ad_links = array();
            foreach($_injection_ids as $_inj_id){
                $ad_links[] = '<a href="' . get_edit_post_link($_inj_id) . '">'.$_inj_id.'</a>';
            }
        ?>
            <div class="error"><p><?php printf(__('Advanced Ads Update: Auto injections are now managed through placements. Please convert these ads with auto injections: %s', ADVADS_SLUG), implode(', ', $ad_links));?></p></div>
        <?php
        }
    }

    /**
     * Register the administration menu for this plugin into the WordPress Dashboard menu.
     *
     * @since    1.0.0
     */
    public function add_plugin_admin_menu() {

        // add main menu item with overview page
        add_menu_page(
            __('Overview', ADVADS_SLUG), __('Advanced Ads', ADVADS_SLUG), 'manage_options', $this->plugin_slug, array($this, 'display_overview_page'), '', '58.74'
        );

        add_submenu_page(
            $this->plugin_slug, __('Ads', ADVADS_SLUG), __('Ads', ADVADS_SLUG), 'manage_options', 'edit.php?post_type=' . Advanced_Ads::POST_TYPE_SLUG
        );

        $this->ad_group_hook_suffix = add_submenu_page(
            $this->plugin_slug, __('Ad Groups', ADVADS_SLUG), __('Groups', ADVADS_SLUG), 'manage_options', $this->plugin_slug . '-groups', array($this, 'ad_group_admin_page')
        );

        // add placements page
        add_submenu_page(
            $this->plugin_slug, __('Ad Placements', ADVADS_SLUG), __('Placements', ADVADS_SLUG), 'manage_options', $this->plugin_slug . '-placements', array($this, 'display_placements_page')
        );
        // add settings page
        $this->plugin_screen_hook_suffix = add_submenu_page(
            $this->plugin_slug, __('Advanced Ads Settings', ADVADS_SLUG), __('Settings', ADVADS_SLUG), 'manage_options', $this->plugin_slug . '-settings', array($this, 'display_plugin_settings_page')
        );
        add_submenu_page(
            null, __('Advanced Ads Debugging', ADVADS_SLUG), __('Debug', ADVADS_SLUG), 'manage_options', $this->plugin_slug . '-debug', array($this, 'display_plugin_debug_page')
        );
    }

    /**
     * Render the overview page
     *
     * @since    1.2.2
     */
    public function display_overview_page() {
        $recent_ads = Advanced_Ads::get_ads();
        $groups = Advanced_Ads::get_ad_groups();
        $placements = Advanced_Ads::get_ad_placements_array();
        include_once( 'views/overview.php' );
    }

    /**
     * Render the settings page
     *
     * @since    1.0.0
     */
    public function display_plugin_settings_page() {
        include_once( 'views/settings.php' );
    }

    /**
     * Render the placements page
     *
     * @since    1.1.0
     */
    public function display_placements_page() {
        // sace new placement
        if(isset($_POST['advads']['placement'])){
            $return = Advads_Ad_Placements::save_new_placement($_POST['advads']['placement']);
        }
        // save placement data
        if(isset($_POST['advads']['placements'])){
            $return = Advads_Ad_Placements::save_placements($_POST['advads']['placements']);
        }
        $error = false;
        $success = false;
        if(isset($return) && $return !== true) {
            $error = $return;
        } elseif(isset($return) && $return === true){
            $success = __('Placements updated', ADVADS_SLUG);
        }
        $placement_types = Advads_Ad_Placements::get_placement_types();
        $placements = Advanced_Ads::get_ad_placements_array();
        // load ads and groups for select field

        // display view
        include_once( 'views/placements.php' );
    }

    /**
     * Render the debug page
     *
     * @since    1.0.1
     */
    public function display_plugin_debug_page() {
        // load array with ads by condition
        $plugin = Advanced_Ads::get_instance();
        $plugin_options = $plugin->options();
        $ads_by_conditions = $plugin->get_ads_by_conditions_array();
        $ad_placements = Advanced_Ads::get_ad_placements_array();

        include_once( 'views/debug.php' );
    }

    /**
     * Render the ad group page
     *
     * @since    1.0.0
     */
    public function ad_group_admin_page() {

        $taxonomy = Advanced_Ads::AD_GROUP_TAXONOMY;
        $post_type = Advanced_Ads::POST_TYPE_SLUG;
        $tax = get_taxonomy($taxonomy);

        $action = $this->current_action();

        // handle new and updated groups
        if ($action == 'editedgroup') {
            $group_id = (int) $_POST['group_id'];
            check_admin_referer('update-group_' . $group_id);

            if (!current_user_can($tax->cap->edit_terms))
                wp_die(__('Sorry, you are not allowed to access this feature.', ADVADS_SLUG));

            // handle new groups
            if ($group_id == 0) {
                $ret = wp_insert_term($_POST['name'], $taxonomy, $_POST);
                if ($ret && !is_wp_error($ret))
                    $forced_message = 1;
                else
                    $forced_message = 4;
                // handle group updates
            } else {
                $tag = get_term($group_id, $taxonomy);
                if (!$tag)
                    wp_die(__('You attempted to edit an ad group that doesn&#8217;t exist. Perhaps it was deleted?', ADVADS_SLUG));

                $ret = wp_update_term($group_id, $taxonomy, $_POST);
                if ($ret && !is_wp_error($ret))
                    $forced_message = 3;
                else
                    $forced_message = 5;
            }
        // deleting items
        } elseif($action == 'delete'){
            $group_id = (int) $_REQUEST['group_id'];
            check_admin_referer('delete-tag_' . $group_id);

            if (!current_user_can($tax->cap->delete_terms))
                wp_die(__('Sorry, you are not allowed to access this feature.', ADVADS_SLUG));

            wp_delete_term($group_id, $taxonomy);

            $forced_message = 2;
        }

        // handle views
        switch ($action) {
            case 'edit' :
                $title = $tax->labels->edit_item;
                if (isset($_REQUEST['group_id'])) {
                    $group_id = absint($_REQUEST['group_id']);
                    $tag = get_term($group_id, $taxonomy, OBJECT, 'edit');
                } else {
                    $group_id = 0;
                    $tag = false;
                }

                require_once( 'views/ad-group-edit.php' );
                break;

            default :
                $title = $tax->labels->name;
                // load needed classes
                include_once( 'includes/class-list-table.php' );
                include_once( 'includes/class-ad-groups-list-table.php' );
                // load template
                include_once( 'views/ad-group.php' );
        }
    }

    /**
     * returns a link to the ad group list page
     *
     * @since 1.0.0
     * @param arr $args additional arguments, e.g. action or group_id
     * @return string admin url
     */
    static function group_page_url($args = array()) {
        $plugin = Advanced_Ads::get_instance();

        $defaultargs = array(
            // 'post_type' => constant("Advanced_Ads::POST_TYPE_SLUG"),
            'page' => 'advanced-ads-groups',
        );
        $args = $args + $defaultargs;

        return add_query_arg($args, admin_url('admin.php'));
    }

    /**
     * Add settings action link to the plugins page.
     *
     * @since    1.0.0
     */
    public function add_action_links($links) {

        return array_merge(
                array(
            'settings' => '<a href="' . admin_url('edit.php?post_type=advanced_ads&page=advanced-ads-settings') . '">' . __('Settings', ADVADS_SLUG) . '</a>'
                ), $links
        );
    }

    /**
     * add information about the ad below the ad title
     *
     * @since 1.1.0
     * @param obj $post
     */
    public function edit_form_below_title($post){
        if (!isset($post->post_type) || $post->post_type != $this->post_type) {
            return;
        }
        require_once('views/ad_info.php');
    }

    /**
     * Add meta boxes
     *
     * @since    1.0.0
     */
    public function add_meta_boxes() {
        add_meta_box(
                'ad-main-box', __('Ad Type', ADVADS_SLUG), array($this, 'markup_meta_boxes'), Advanced_Ads::POST_TYPE_SLUG, 'normal', 'high'
        );
        add_meta_box(
                'ad-parameters-box', __('Ad Parameters', ADVADS_SLUG), array($this, 'markup_meta_boxes'), Advanced_Ads::POST_TYPE_SLUG, 'normal', 'high'
        );
        add_meta_box(
                'ad-output-box', __('Layout / Output', ADVADS_SLUG), array($this, 'markup_meta_boxes'), Advanced_Ads::POST_TYPE_SLUG, 'normal', 'high'
        );
        add_meta_box(
                'ad-display-box', __('Display Conditions', ADVADS_SLUG), array($this, 'markup_meta_boxes'), Advanced_Ads::POST_TYPE_SLUG, 'normal', 'high'
        );
        add_meta_box(
                'ad-visitor-box', __('Visitor Conditions', ADVADS_SLUG), array($this, 'markup_meta_boxes'), Advanced_Ads::POST_TYPE_SLUG, 'normal', 'high'
        );
        add_meta_box(
                'ad-inject-box', __('Auto injection', ADVADS_SLUG), array($this, 'markup_meta_boxes'), Advanced_Ads::POST_TYPE_SLUG, 'normal', 'high'
        );
    }

    /**
     * load templates for all meta boxes
     *
     * @since 1.0.0
     * @param obj $post
     * @param array $box
     * @todo move ad initialization to main function and just global it
     */
    public function markup_meta_boxes($post, $box) {
        $ad = new Advads_Ad($post->ID);

        switch ($box['id']) {
            case 'ad-main-box':
                $view = 'ad-main-metabox.php';
                break;
            case 'ad-parameters-box':
                $view = 'ad-parameters-metabox.php';
                break;
            case 'ad-output-box':
                $view = 'ad-output-metabox.php';
                break;
            case 'ad-display-box':
                $view = 'ad-display-metabox.php';
                break;
            case 'ad-visitor-box':
                $view = 'ad-visitor-metabox.php';
                break;
            case 'ad-inject-box':
                $view = 'ad-inject-metabox.php';
                break;
        }

        if (empty($view))
            return;
        $view = plugin_dir_path(__FILE__) . 'views/' . $view;
        if (is_file($view)) {
            require_once( $view );
        }
    }

    /**
     * prepare the ad post type to be saved
     *
     * @since 1.0.0
     * @param int $post_id id of the post
     * @todo handling this more dynamic based on ad type
     */
    public function save_ad($post_id) {

        // only use for ads, no other post type
        if (!isset($_POST['post_type']) || $this->post_type != $_POST['post_type'] || !isset($_POST['advanced_ad']['type'])) {
            return;
        }

        // don’t do this on revisions
        if ( wp_is_post_revision( $post_id ) )
		return;

        // get ad object
        $ad = new Advads_Ad($post_id);
        if (!$ad instanceof Advads_Ad)
            return;

        $ad->type = $_POST['advanced_ad']['type'];
        if(isset($_POST['advanced_ad']['output'])) {
            $ad->set_option('output', $_POST['advanced_ad']['output']);
        } else {
            $ad->set_option('output', array());
        }
        if(isset($_POST['advanced_ad']['visitor'])) {
            $ad->set_option('visitor', $_POST['advanced_ad']['visitor']);
        } else {
            $ad->set_option('visitor', array());
        }
        if(isset($_POST['advanced_ad']['injection'])) {
            $ad->set_option('injection', $_POST['advanced_ad']['injection']);
        } else {
            $ad->set_option('injection', array());
        }
        // save size
        $ad->width = 0;
        if(isset($_POST['advanced_ad']['width'])) {
            $ad->width = absint($_POST['advanced_ad']['width']);
        }
        $ad->height = 0;
        if(isset($_POST['advanced_ad']['height'])) {
            $ad->height = absint($_POST['advanced_ad']['height']);
        }

        if(!empty($_POST['advanced_ad']['content']))
            $ad->content = $_POST['advanced_ad']['content'];
        else $ad->content = '';
        if(!empty($_POST['advanced_ad']['conditions'])){
            $ad->conditions = $_POST['advanced_ad']['conditions'];
        } else {
            $ad->conditions = array();
        }

        $ad->save();

        // update global ad information
        $this->update_global_injection_array();
    }

    /**
     * get action from the params
     *
     * @since 1.0.0
     */
    public function current_action() {
        if (isset($_REQUEST['action']) && -1 != $_REQUEST['action'])
            return $_REQUEST['action'];

        return false;
    }

    /**
     * initialize settings
     *
     * @since 1.0.1
     */
    public function settings_init(){

        // get settings page hook
        $hook = $this->plugin_screen_hook_suffix;

        // register settings
 	register_setting($hook, 'advancedads');

        // add new section
 	add_settings_section(
		'advanced_ads_setting_section',
		__('General', ADVADS_SLUG),
		array($this, 'render_settings_section_callback'),
		$hook
	);

 	// add setting fields for user role
 	add_settings_field(
		'hide-for-user-role',
		__('Hide ads for logged in users', ADVADS_SLUG),
		array($this, 'render_settings_hide_for_users'),
		$hook,
		'advanced_ads_setting_section'
	);
 	// add setting fields for advanced ads
 	add_settings_field(
		'activate-advanced-js',
		__('Use advanced JavaScript', ADVADS_SLUG),
		array($this, 'render_settings_advanced_js'),
		$hook,
		'advanced_ads_setting_section'
	);
    }

    /**
     * render settings section
     *
     * @since 1.1.1
     */
    public function render_settings_section_callback(){
        // for whatever purpose there might come
    }

    /**
     * render setting to hide ads from logged in users
     *
     * @since 1.1.1
     */
    public function render_settings_hide_for_users(){
        $options = Advanced_Ads::get_instance()->options();
        $current_capability_role = isset($options['hide-for-user-role']) ? $options['hide-for-user-role'] : 0;

        $capability_roles = array(
            '' => __('(display to all)', ADVADS_SLUG),
            'read' => __('Subscriber', ADVADS_SLUG),
            'delete_posts' => __('Contributor', ADVADS_SLUG),
            'edit_posts' => __('Author', ADVADS_SLUG),
            'edit_pages' => __('Editor', ADVADS_SLUG),
            'activate_plugins' => __('Admin', ADVADS_SLUG),
        );
        echo '<select name="advancedads[hide-for-user-role]">';
        foreach($capability_roles as $_capability => $_role) {
            echo '<option value="'.$_capability.'" '.selected($_capability, $current_capability_role, false).'>'.$_role.'</option>';
        }
        echo '</select>';

        echo '<p class="description">'. __('Choose the lowest role a user must have in order to not see any ads.', ADVADS_SLUG) .'</p>';
    }

    /**
     * render setting to display advanced js file
     *
     * @since 1.2.3
     */
    public function render_settings_advanced_js(){
        $options = Advanced_Ads::get_instance()->options();
        $checked = (!empty($options['advanced-js'])) ? 1 : 0;

        echo '<input id="advanced-ads-advanced-js" type="checkbox" value="1" name="advancedads[advanced-js]" '.checked($checked, 1, false).'>';
        echo '<p class="description">'. sprintf(__('Only enable this if you can and want to use the advanced JavaScript functions described <a href="%s">here</a>.', ADVADS_SLUG), 'http://wpadvancedads.com/javascript-functions/') .'</p>';
    }

    /**
     * save a global array with ad injection information
     * runs every time for all ads a single ad is saved (but not on autosave)
     *
     * @since 1.1.0
     */
    public function update_global_injection_array(){
        // get all public ads
        $ad_posts = Advanced_Ads::get_ads();

        // merge ad injection settings by type (place => ad id)
        $all_injections = array();
        if(is_array($ad_posts)) foreach($ad_posts as $_ad){
            // load the ad
            $ad = new Advads_Ad($_ad->ID);
            // get injection post meta
            $injection_options = $ad->options('injection');
            // add injection settings to global array
            if(isset($injection_options)) foreach($injection_options as $_iokey => $_io){
                $all_injections[$_iokey][] = $_ad->ID;
            }
        }

        // save global injection array to WP options table or remove it
        if(is_array($all_injections) && count($all_injections) > 0)
            update_option('advads-ads-injections', $all_injections);
        else
            delete_option ('advads-ads-injections');
    }

}
