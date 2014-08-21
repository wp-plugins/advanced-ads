<?php
/**
 * Advanced Ads Plain Ad Type
 *
 * @package   Advanced_Ads
 * @author    Thomas Maier <thomas.maier@webgilde.com>
 * @license   GPL-2.0+
 * @link      http://webgilde.com
 * @copyright 2014 Thomas Maier, webgilde GmbH
 *
 * Class containing information about the plain text/code ad type
 *
 * see ad-type-content.php for a better sample on ad type
 *
 */
class Advads_Ad_Type_Plain extends Advads_Ad_Type_Abstract{

    /**
     * ID - internal type of the ad type
     *     *
     * @since 1.0.0
     */
    public $ID = 'plain';

    /**
     * set basic attributes
     *
     * @since 1.0.0
     */
    public function __construct() {
        $this->title = __('Plain Text and Code', Advanced_Ads::TD);
        $this->description = __('Simple text editor without any filters. You might use it to display unfiltered content, php code or javascript. Shortcodes and other WordPress content field magic does not work here.', Advanced_Ads::TD);
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
        ?><p class="description"><?php _e('Insert plain text or code into this field.', Advanced_Ads::TD); ?></p>
        <textarea id="advads-ad-content-plain" cols="40" rows="10" name="advanced_ad[content]"><?php echo $content; ?></textarea>
        <?php
    }

    /**
     * prepare the ads frontend output
     *
     * @param obj $ad ad object
     * @return str $content ad content prepared for frontend output
     * @since 1.0.0
     */
    public function prepare_output($ad){
        // evaluate the code as php
        ob_start();
        eval('?>'.$ad->content);
        $content = ob_get_clean();
        return $content;
    }

}