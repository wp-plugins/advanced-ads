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
class Advanced_Ads_Ad_Type_Content extends Advanced_Ads_Ad_Type_Abstract{

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
		$this->title = __( 'Rich Content', ADVADS_SLUG );
		$this->description = __( 'The full content editor from WordPress with all features like shortcodes, image upload or styling, but also simple text/html mode for scripts and code.', ADVADS_SLUG );
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
		 *
		 * don’t build it when ajax is used; display message and buttons instead
		 */
		if ( defined( 'DOING_AJAX' ) ){
			?><p><?php _e( 'Please <strong>save the ad</strong> before changing it to the content type.', ADVADS_SLUG ); ?></p><?php
			$status = get_post_status( $ad->id );
if ( 'publish' != $status && 'future' != $status && 'pending' != $status ) { ?>
                <input <?php if ( 'private' == $status ) { ?>style="display:none"<?php } ?> type="submit" name="save" id="save-post" value="<?php esc_attr_e( 'Save Draft' ); ?>" class="button" />
                <?php } else {
		?><input name="original_publish" type="hidden" id="original_publish" value="<?php esc_attr_e( 'Update' ) ?>" />
		<input name="save" type="submit" class="button button-primary button-large" id="publish" accesskey="p" value="<?php esc_attr_e( 'Update' ) ?>" /><?php
}
if ( ! empty($ad->content) ) : ?><textarea id="advads-ad-content-plain" style="display:none;" cols="1" rows="1" name="advanced_ad[content]"><?php
echo $ad->content; ?></textarea><br class="clear"/><?php endif;
		} else {
			$args = array(
				'textarea_name' => 'advanced_ad[content]',
				'textarea_rows' => 10,
				'drag_drop_upload' => true
			);
			wp_editor( $content, 'advanced-ad-parameters-content', $args );
		}
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
		$content = wp_unslash( $content );

		// use WordPress core content filter
		return $content = apply_filters( 'content_save_pre', $content );
	}

	/**
	 * prepare the ads frontend output
	 *
	 * @param obj $ad ad object
	 * @return str $content ad content prepared for frontend output
	 * @since 1.0.0
	 */
	public function prepare_output($ad){

		// apply functions normally running through the_content filter
		// the_content filter not used here because it created an infinite loop (ads within ads)

		$output = wptexturize( $ad->content );
		$output = convert_smilies( $output );
		$output = convert_chars( $output );
		$output = wpautop( $output );
		$output = shortcode_unautop( $output );
		$output = do_shortcode( $output );
		$output = prepend_attachment( $output );

		return $output;
	}

}