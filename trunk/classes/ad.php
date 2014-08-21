<?php

/**
 * Advanced Ads.
 *
 * @package   Advads_Ad
 * @author    Thomas Maier <thomas.maier@webgilde.com>
 * @license   GPL-2.0+
 * @link      http://webgilde.com
 * @copyright 2013 Thomas Maier, webgilde GmbH
 */

/**
 * an ad object
 *
 * @package Advads_Ad
 * @author  Thomas Maier <thomas.maier@webgilde.com>
 */
class Advads_Ad {

    /**
     * id of the post type for this ad
     */
    protected $id = 0;

    /**
     * true, if this is an Advanced Ads Ad post type
     */
    protected $is_ad = false;

    /**
     * ad type
     */
    public $type = 'content';

    /**
     * object of current ad type
     */
    protected $type_obj;

    /**
     * content of the ad
     *
     *  only needed for ad types using the post content field
     */
    public $content = '';

    /**
     * conditions of the ad display
     */
    public $conditions = array();

    /**
     * status of the ad (e.g. publish, pending)
     */
    public $status = array();

    /**
     * array with meta field options aka parameters
     */
    protected $options = array();

    /**
     * name of the meta field to save options to
     */
    static $options_meta_field = 'advanced_ads_ad_options';

    /**
     * init ad object
     *
     * @param int $id id of the ad (= post id)
     */
    public function __construct($id) {
        global $advanced_ads_ad_conditions;
        $id = absint($id);
        $this->id = $id;

        if(!empty($id)) $this->load($id);

        // dynamically add sanitize filters for condition types
        $_types = array();
        foreach($advanced_ads_ad_conditions as $_condition) {
            $_types[] = $_condition['type'];
        }
        $_types = array_unique($_types);
        foreach($_types as $_type) {
            $method_name = 'sanitize_condition_'. $_type;
            if(method_exists($this, $method_name)) {
                add_filter('advanced-ads-sanitize-condition-' . $_type, array($this, $method_name), 10, 1);
            } elseif(function_exists('advads_sanitize_condition_' . $_type)) {
                // check for public function to sanitize this
                add_filter('advanced-ads-sanitize-condition-' . $_type, 'advads_sanitize_condition_' . $_type, 10, 1);

            }
        }
    }

    /**
     * load an ad object by id based on its ad type
     *
     * @since 1.0.0
     */
    private function load($id = 0){

        $_data = get_post($id);
        if($_data == null) return false;

        // return, if not an ad
        if($_data->post_type != Advanced_Ads::POST_TYPE_SLUG) {
            return false;
        } else {
            $this->is_ad = true;
        }

        $this->type = $this->options('type');
        /* load ad type object */
        $types = Advanced_Ads::get_instance()->ad_types;
        if(isset($types[$this->type])){
            $this->type_obj = $types[$this->type];
        } else {
            $this->type_obj = new Advads_Ad_Type_Abstract;
        }

        $this->conditions = $this->options('conditions');
        $this->status = $_data->post_status;

        // load content based on ad type
        $this->content = $this->type_obj->load_content($_data);
    }

    /**
     * get options from meta field and return specific field
     *
     * @param string $field post meta key to be returned
     * @return mixed meta field content
     * @since 1.0.0
     * @todo check against default values
     */
    public function options($field = ''){
        // retrieve options, if not given yet
        if($this->options == array()) {
            $this->options = get_post_meta($this->id, self::$options_meta_field, true);
        }

        // return specific option
        if($field != '') {
            if(!empty($this->options[$field]))
                return $this->options[$field];
        } else { // return all options
            if(!empty($this->options))
                return $this->options;
        }
    }

    /**
     * set an option of the ad
     *
     * @since 1.1.0
     * @param string $option name of the option
     * @param mixed $value value of the option
     */
    public function set_option($option = '', $value = ''){
        if($option == '') return;

        // get current options
        $options = $this->options();

        // set options
        $options[$option] = $value;

        // save options
        $this->options = $options;

    }


    /**
     * return ad content for frontend output
     */
    public function output(){
        if(!$this->is_ad) return '';

        $output = $this->prepare_frontend_output();
        return $output;
    }

    /**
     * check if the ad can be displayed in frontend due to its conditions
     *
     * @return bool $can_display true if can be displayed in frontend
     */
    public function can_display(){

        if($this->can_display_by_conditions()
                && $this->can_display_by_visitor())
            return true;
        else return false;
    }

    /**
     * check display conditions
     *
     * @since 1.1.0 moved here from can_display()
     * @return bool $can_display true if can be displayed in frontend
     */
    public function can_display_by_conditions(){
        global $post;

        if(empty($this->options['conditions']) ||
                !is_array($this->options['conditions'])) return true;

        $conditions = $this->options['conditions'];
        foreach($conditions as $_cond_key => $_cond_value) {
            switch($_cond_key){
                // check for post ids
                case 'postids' :
                    // included posts
                    if(!empty($_cond_value['include'])){
                        $post_ids = explode(',', $_cond_value['include']);
                        if(is_array($post_ids)
                                && isset($post->ID)
                                && !in_array($post->ID, $post_ids))
                                return false;
                    }
                    // excluded posts
                    if(!empty($_cond_value['exclude'])){
                        $post_ids = explode(',', $_cond_value['exclude']);
                        if(is_array($post_ids)
                                && isset($post->ID)
                                && in_array($post->ID, $post_ids))
                                return false;
                    }
                break;
                // check for category ids
                case 'categoryids' :
                    // included
                    if(!empty($_cond_value['include'])){
                        $category_ids = explode(',', $_cond_value['include']);
                        // check if currently in a post (not post page, but also posts in loops)
                        if(is_array($category_ids) && isset($post->ID)
                            && !in_category($category_ids, $post)) {
                                return false;
                        }
                    }
                    // check for excluded category ids
                    if(!empty($_cond_value['exclude'])){
                        $category_ids = explode(',', $_cond_value['exclude']);
                        // check if currently in a post (not post page, but also posts in loops)
                        if(is_array($category_ids) && isset($post->ID)
                            && in_category($category_ids, $post) ) {
                                // being only in one excluded category is enough to not display the ad
                                return false;
                        }
                    }
                break;
                // check for included category archive ids
                // @link http://codex.wordpress.org/Conditional_Tags#A_Category_Page
                case 'categoryarchiveids' :
                    if(!empty($_cond_value['include'])){
                        $category_ids = explode(',', $_cond_value['include']);
                        if(is_array($category_ids) && !is_category($category_ids))
                            return false;
                    }
                    // check for excluded category archive ids
                    if(!empty($_cond_value['exclude'])){
                        $category_ids = explode(',', $_cond_value['exclude']);
                        if(is_array($category_ids) && is_category($category_ids))
                            return false;
                    }
                break;
                // check for included post types
                case 'posttypes' :
                    if(!empty($_cond_value['include'])){
                    $post_types = explode(',', $_cond_value['include']);
                        // check if currently in a post (not post page, but also posts in loops)
                        if(is_array($post_types) && !in_array(get_post_type(), $post_types)) {
                            return false;
                        }
                    }
                    // check for excluded post types
                    if(!empty($_cond_value['include'])){
                        $post_types = explode(',', $_cond_value['exclude']);
                        // check if currently in a post (not post page, but also posts in loops)
                        if(is_array($post_types) && in_array(get_post_type(), $post_types)) {
                            return false;
                        }
                    }
                break;
                // check is_front_page
                // @link https://codex.wordpress.org/Conditional_Tags#The_Front_Page
                case 'is_front_page' :
                    if(($_cond_value == 1 && !is_front_page())
                            || ($_cond_value == 0 && is_front_page()))
                        return false;
                break;
                // check is_singular
                // @link https://codex.wordpress.org/Conditional_Tags#A_Post_Type
                case 'is_singular' :
                    if(($_cond_value == 1 && !is_singular())
                            || ($_cond_value == 0 && is_singular()))
                        return false;
                break;
                // check is_archive
                // @link https://codex.wordpress.org/Conditional_Tags#Any_Archive_Page
                case 'is_archive' :
                    if(($_cond_value == 1 && !is_archive())
                            || ($_cond_value == 0 && is_archive()))
                        return false;
                break;
                // check is_search
                // @link https://codex.wordpress.org/Conditional_Tags#A_Search_Result_Page
                case 'is_search' :
                    if(($_cond_value == 1 && !is_search())
                            || ($_cond_value == 0 && is_search()))
                        return false;
                break;
                // check is_404
                // @link https://codex.wordpress.org/Conditional_Tags#A_404_Not_Found_Page
                case 'is_404' :
                    if(($_cond_value == 1 && !is_404())
                            || ($_cond_value == 0 && is_404()))
                        return false;
                break;
                // check is_attachment
                // @link https://codex.wordpress.org/Conditional_Tags#An_Attachment
                case 'is_attachment' :
                    if(($_cond_value == 1 && !is_attachment())
                            || ($_cond_value == 0 && is_attachment()))
                        return false;
                break;
            }
        }

        return true;
    }

    /**
     * check visitor conditions
     *
     * @since 1.1.0
     * @return bool $can_display true if can be displayed in frontend based on visitor settings
     */
    public function can_display_by_visitor(){

        if(empty($this->options['visitor']) ||
                !is_array($this->options['visitor'])) return true;

        $visitor_conditions = $this->options('visitor');

        // check mobile condition
        if(!empty($visitor_conditions['mobile'])){
            switch($visitor_conditions['mobile']){
                case 'only' :
                    if(!wp_is_mobile()) return false;
                    break;
                case 'no' :
                    if(wp_is_mobile()) return false;
                    break;
            }
        }

        return true;
    }

    /**
     * save an ad to the database
     * takes values from the current state
     */
    public function save(){
        global $wpdb;

        // remove slashes from content
        $content = $this->prepare_content_to_save();

        $where = array('ID' => $this->id);
        $wpdb->update( $wpdb->posts, array( 'post_content' => $content ), $where );

        // sanitize conditions
        // see sanitize_conditions function for example on using this filter
        $conditions = self::sanitize_conditions_on_save($this->conditions);

        // save other options to post meta field
        $options = $this->options();

        $options['type'] = $this->type;
        $options['conditions'] = $conditions;

        // filter to manipulate options or add more to be saved
        $options = apply_filters('advanced-ads-save-options', $options, $this);

        // update global settings
        $this->update_general_ad_conditions($conditions);

        update_post_meta($this->id, self::$options_meta_field, $options);

    }

    /**
     * native filter for content field before being saved
     *
     * @return string $content ad content
     * @since 1.0.0
     */
    public function prepare_content_to_save() {

        $content = $this->content;

        // load ad type specific parameter filter
        $content = $this->type_obj->sanitize_content($content);
        // apply a custom filter by ad type
        $content = apply_filters('advanced-ads-pre-ad-save-' . $this->type, $content);

        return $content;
    }

    /**
     * native filter for ad parameters before being saved
     *
     * @return arr $parameters sanitized parameters
     */
    public function prepare_parameters_to_save() {

        $parameters = $this->parameters;
        // load ad type specific parameter filter
        $parameters = $this->type_obj->sanitize_parameters($parameters);

        // apply native WP filter for content fields
        return $parameters;
    }

    /**
     * prepare ads output
     *
     * @param string $content ad content
     * @param obj $ad ad object
     */
    public function prepare_frontend_output(){

        // load ad type specific content filter
        $output = $this->type_obj->prepare_output($this);

        // apply a custom filter by ad type
        $output = apply_filters('advanced-ads-ad-output', $output, $this);

        return $output;
    }

    /**
     * sanitize ad display conditions when saving the ad
     *
     * @param array $conditions conditions array send via the dashboard form for an ad
     * @return array with sanitized conditions
     * @since 1.0.0
     */
    public function sanitize_conditions_on_save($conditions = array()){

        global $advanced_ads_ad_conditions;

        if(!is_array($conditions) || $conditions == array()) return array();

        foreach($conditions as $_key => $_condition){
            if(!is_array($_condition))
                $_condition = trim($_condition);
            if($_condition == '') {
                $conditions[$_key] = $_condition;
                continue;
            }
            $type = $advanced_ads_ad_conditions[$_key]['type'];
            if(empty($type)) continue;

            // dynamically apply filters for each condition used
            $conditions[$_key] = apply_filters('advanced-ads-sanitize-condition-' . $type, $_condition);
        }

        return $conditions;
    }

    /**
     * sanitize id input field(s) for pattern /1,2,3,4/
     *
     * @pararm array/string $cond input string/array
     * @return array/string $cond sanitized string/array
     */
    public static function sanitize_condition_idfield($cond = ''){
        // strip anything that is not comma or number
        if(is_array($cond)){
            foreach($cond as $_key => $_cond){
                $cond[$_key] = preg_replace('#[^0-9,]#', '', $_cond);
            }
        } else {
            $cond = preg_replace('#[^0-9,]#', '', $cond);
        }
        return $cond;
    }

    /**
     * sanitize radio input field
     *
     * @pararm string $string input string
     * @return string $string sanitized string
     */
    public static function sanitize_condition_radio($string = ''){
        // only allow 0, 1 and empty
        return $string = preg_replace('#[^01]#', '', $string);
    }

    /**
     * sanitize comma seperated text input field
     *
     * @pararm array/string $cond input string/array
     * @return array/string $cond sanitized string/array
     */
    public static function sanitize_condition_textvalues($cond = ''){
        // strip anything that is not comma, alphanumeric, minus and underscore
        if(is_array($cond)){
            foreach($cond as $_key => $_cond){
                $cond[$_key] = preg_replace('#[^0-9,A-Za-z-_]#', '', $_cond);
            }
        } else {
            $cond = preg_replace('#[^0-9,A-Za-z-_]#', '', $cond);
        }
        return $cond;
    }

    /**
     * update general ad conditions with conditions for the current ad
     *
     * @param array $conditions ad display conditions from ad form
     * @since 1.0.0
     * @todo make those condition checks extendible
     */
    public function update_general_ad_conditions($conditions){
        global $advanced_ads_ad_conditions;

        $plugin = Advanced_Ads::get_instance();
        $ads_by_conditions = $plugin->get_ads_by_conditions_array();
        $plugin_slug = $plugin->get_plugin_slug();

        // remove current ad from general ad condition array
        $ads_by_conditions = $this->remove_ad_from_general_ad_conditions($this->id, $ads_by_conditions);

        // only run conditions if ad is publically visible
        if($this->status == 'publish')
        // iterate through the ads display condition
        foreach($conditions as $_condition_key => $_condition){
            if(!isset($advanced_ads_ad_conditions[$_condition_key]['type'])) {
                $plugin->log(sprintf(__('A "%s" display condition does not exist', $plugin_slug), $_condition_key));
                return;
            }
            // add conditions based on type
            switch($advanced_ads_ad_conditions[$_condition_key]['type']){
                case 'idfield' :
                    if(isset($_condition['include']) && $_condition['include'] != ''){
                        $_ids = explode(',', $_condition['include']);
                        if(is_array($_ids)) foreach($_ids as $_id){
                            $ads_by_conditions[$_condition_key][$_id]['include'][] = $this->id;
                        }
                    }
                    if(isset($_condition['exclude']) && $_condition['exclude'] != ''){
                        $_ids = explode(',', $_condition['exclude']);
                        if(is_array($_ids)) foreach($_ids as $_id){
                            $ads_by_conditions[$_condition_key][$_id]['exclude'][] = $this->id;
                        }
                    }
                    break;
                case 'textvalues' :
                    if(isset($_condition['include']) && $_condition['include'] != ''){
                        $_ids = explode(',', $_condition['include']);
                        if(is_array($_ids)) foreach($_ids as $_id){
                            $ads_by_conditions[$_condition_key][$_id]['include'][] = $this->id;
                        }
                    }
                    if(isset($_condition['exclude']) && $_condition['exclude'] != ''){
                        $_ids = explode(',', $_condition['exclude']);
                        if(is_array($_ids)) foreach($_ids as $_id){
                            $ads_by_conditions[$_condition_key][$_id]['exclude'][] = $this->id;
                        }
                    }
                    break;
                case 'radio' :
                    if($_condition == 1)
                        $ads_by_conditions[$_condition_key]['include'][] = $this->id;
                    elseif($_condition == 0)
                        $ads_by_conditions[$_condition_key]['exclude'][] = $this->id;
                    break;
            } // switch
        } // forearch

        update_option('advads-ads-by-conditions', $ads_by_conditions);
    }

    /**
     * remove ad id from ad conditions array
     *
     * @param int $ad_id id of the ad (=post id)
     * @param arr $conditions array with the general, global ad conditions
     * @since 1.0.0
     */
    static function remove_ad_from_general_ad_conditions($ad_id = 0, $conditions = array()){
        $ad_id = absint($ad_id);
        if(empty($ad_id) || !is_array($conditions) || $conditions == array()) return;

        foreach($conditions as $_key => $_cond){
            // remove single elements
            if(!is_array($_cond) && $_cond == $ad_id){
                unset($conditions[$_key]);
            } elseif(empty($_cond)){
                unset($conditions[$_key]);
            }
            // check recursively
            elseif(is_array($_cond)){
                $new_cond = self::remove_ad_from_general_ad_conditions($ad_id, $_cond);

                if($new_cond == array() || $new_cond == ''){
                    // remove empty arrays
                   unset($conditions[$_key]);
                } else {
                    $conditions[$_key] = $new_cond;
                }
            }
        }

        return $conditions;
    }

}