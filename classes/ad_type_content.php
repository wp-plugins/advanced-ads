<?php
/**
 * Advanced Ads Content Ad Type
 *
 * @package   Advanced_Ads
 * @author    Thomas Maier <thomas.maier@webgilde.com>
 * @license   GPL-2.0+
 * @link      http://webgilde.com
 * @copyright 2014 Thomas Maier, webgilde GmbH
 *
 * Class containing information about the content ad type
 * this should also work as an example for other ad types
 *
 * see also includes/ad-type-abstract.php for basic object
 *
 */
class Advads_Ad_Type_Content extends Advads_Ad_Type_Abstract{

    /**
     * ID - internal type of the ad type
     *
     * must be static so set your own ad type ID here
     * use slug like format, only lower case, underscores and hyphens
     *
     * @since 1.0.0
     */
    public $ID = 'content';

    /**
     * set basic attributes
     *
     * @since 1.0.0
     */
    public function __construct() {
        $this->title = __('Rich Content', ADVADS_SLUG);
        $this->description = __('The full content editor from WordPress with all features like image upload or styling, but also simple text/html mode for scripts and code.', ADVADS_SLUG);
        $this->parameters = array(
            'content' => ''
        );
    }

    /**
     * output for the ad parameters metabox
     *
     * this will be loaded using ajax when changing the ad type radio buttons
     * echo the output right away here
     * name parameters must be in the "advanced_ads" array
     *
     * @param obj $ad ad object
     * @since 1.0.0
     */
    public function render_parameters($ad){
        // load tinymc content exitor
        $content = (isset($ad->content)) ? $ad->content : '';
        /**
         * build the tinymc editor
         * @link http://codex.wordpress.org/Function_Reference/wp_editor
         */
        $args = array(
            'textarea_name' => 'advanced_ad[content]',
            'textarea_rows' => 10,
            'drag_drop_upload' => true
        );
        wp_editor($content, 'advanced-ad-parameters-content', $args);
    }

    /**
     * sanitize content field on save
     *
     * @param str $content ad content
     * @return str $content sanitized ad content
     * @since 1.0.0
     */
    public function sanitize_content($content = ''){

        // remove slashes from content
        $content = wp_unslash($content);

        // use WordPress core content filter
        return $content = apply_filters('content_save_pre', $content);
    }

}