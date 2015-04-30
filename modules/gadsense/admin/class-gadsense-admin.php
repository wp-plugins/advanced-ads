<?php

class Gadsense_Admin {

	private $data;
	private $nonce;
	private static $instance = null;
	protected $notice = null;
        private $settings_page_hook = 'advanced-ads-adsense-settings-page';

	private function __construct() {
		$this->data = Gadsense_Data::get_instance();
		add_action( 'advanced-ads-settings-init', array($this, 'settings_init') );
		// add_action( 'advanced-ads-additional-settings-form', array($this, 'settings_init') );
                add_filter('advanced-ads-setting-tabs', array($this, 'setting_tabs'));

		add_action( 'admin_enqueue_scripts', array($this, 'enqueue_scripts') );
		add_action( 'admin_print_scripts', array($this, 'print_scripts') );
		add_action( 'admin_init', array($this, 'init') );
		add_filter( 'advanced-ads-list-ad-size', array($this, 'ad_details_column'), 10, 2 );
	}

	public function ad_details_column($size, $the_ad) {
		if ( 'adsense' == $the_ad->type ) {
			$content = json_decode( $the_ad->content );
			if ( 'responsive' == $content->unitType ) { $size = __( 'Responsive', ADVADS_SLUG ); }
		}
		return $size;
	}

	public function print_scripts() {
		global $pagenow, $post_type;
		if (
				('post-new.php' == $pagenow && Advanced_Ads::POST_TYPE_SLUG == $post_type) ||
				('post.php' == $pagenow && Advanced_Ads::POST_TYPE_SLUG == $post_type && isset($_GET['action']) && 'edit' == $_GET['action'])
		) {
			$db = Gadsense_Data::get_instance();
			$pub_id = $db->get_adsense_id();
			?>
			<script type="text/javascript">
				var gadsenseData = {
					pubId : '<?php echo $pub_id; ?>',
					msg : {
						unknownAd : '<?php esc_attr_e( "The ad details couldn't be retrieved from the ad code", ADVADS_SLUG ); ?>',
						pubIdMismatch : '<?php _e( 'Warning : The AdSense account from this code does not match the one set with the Advanced Ads Plugin. This ad might cause troubles when used in the front end.', ADVADS_SLUG ); ?>',
						missingPubId : '<?php _e( 'Warning : You have not yet entered an AdSense account ID. The plugin won’t work without that', ADVADS_SLUG ); ?>',
					}
				};
			</script>
			<?php
		}
	}

	public function init() {
		if ( isset($_POST['gadsense-nonce']) ) {
			$this->form_treatment();
		}
		$this->nonce = wp_create_nonce( 'gadsense_nonce' );

		// admin notices
		if ( isset($_COOKIE['gadsense_admin_notice']) ) {
			// passing the actual message by cookie would enable an injection
			if ( $_COOKIE['gadsense_admin_notice'] > 0 ) {
				// error
				$msg = __( 'The Publisher ID has an incorrect format. (must start with "pub-")', ADVADS_SLUG );
				$css = 'error';
			} else {
				// success
				$msg = __( 'Data updated', ADVADS_SLUG );
				$css = 'updated';
			}
			$this->notice = array(
				'msg' => $msg,
				'class' => $css,
			);
			setcookie( 'gadsense_admin_notice', null, -1 );
		}
	}

	public function form_treatment() {
		if ( 1 === wp_verify_nonce( $_POST['gadsense-nonce'], 'gadsense_nonce' ) ) {
			switch ( $_POST['gadsense-form-name'] ) {
				case 'cred-form' :
					$id = strtolower( trim( wp_unslash( $_POST['adsense-id'] ) ) );
					$limit = (isset($_POST['limit-per-page']))? true : false;
					$isError = false;
					if ( 0 === strpos( $id, 'pub-' ) ) {
						$this->data->set_adsense_id( $id );
						$this->data->set_limit_per_page( $limit );
					} else {
						$isError = true;
					}
					setcookie( 'gadsense_admin_notice', (string) (int) $isError, time() + 900 ); // only display for 15 minutes
					break;
				default :
			}
		}

		// redirect
		wp_redirect( $_SERVER['PHP_SELF'] . '?' . $_SERVER['QUERY_STRING'] );
		die();
	}

	public function enqueue_scripts() {
		global $gadsense_globals, $pagenow, $post_type;
		$screen = get_current_screen();
		$plugin = Advanced_Ads_Admin::get_instance();
		if (
				('post-new.php' == $pagenow && Advanced_Ads::POST_TYPE_SLUG == $post_type) ||
				('post.php' == $pagenow && Advanced_Ads::POST_TYPE_SLUG == $post_type && isset($_GET['action']) && 'edit' == $_GET['action'])
		) {
			$default_script = array(
				'path' => GADSENSE_BASE_URL . 'admin/assets/js/new-ad.js',
				'dep' => array('jquery'),
				'version' => null,
			);

			$scripts = array(
				'gadsense-new-ad' => $default_script,
			);

			// Allow modifications of script files to enqueue
			$scripts = apply_filters( 'advanced-ads-gadsense-ad-param-script', $scripts );

			foreach ( $scripts as $handle => $value ) {
				if ( empty($handle) ) {
					continue;
				}
				if ( ! empty($handle) && empty($value) ) {
					// Allow inclusion of WordPress's built-in script like jQuery
					wp_enqueue_script( $handle );
				} else {
					if ( ! isset($value['version']) ) { $value['version'] = null; }
					wp_enqueue_script( $handle, $value['path'], $value['dep'], $value['version'] );
				}
			}

			$styles = array();

			// Allow modifications of default style files to enqueue
			$styles = apply_filters( 'advanced-ads-gadsense-ad-param-style', $styles );

			foreach ( $styles as $handle => $value ) {
				if ( ! isset($value['path']) ||
						! isset($value['dep']) ||
						empty($handle)
				) {
					continue;
				}
				if ( ! isset($value['version']) ) {
					$value['version'] = null; }
				wp_enqueue_style( $handle, $value['path'], $value['dep'], $value['version'] );
			}
		}
	}

	public static function get_instance() {
		if ( null == self::$instance ) {
			self::$instance = new self;
		}
		return self::$instance;
	}

	public function settings_init() {

                // get settings page hook
		$hook = $this->settings_page_hook;

                register_setting( ADVADS_SLUG . '-adsense', ADVADS_SLUG . '-adsense', array($this, 'sanitize_settings') );

		// add new section
		add_settings_section(
                        'advanced_ads_adsense_setting_section',
                        __( 'AdSense', ADVADS_SLUG ),
                        array($this, 'render_settings_section_callback'),
                        $hook
		);

		// add setting field to disable ads
		add_settings_field(
			'adsense-id',
			__( 'AdSense ID', ADVADS_SLUG ),
			array($this, 'render_settings_adsense_id'),
			$hook,
			'advanced_ads_adsense_setting_section'
		);

		// add setting field for adsense limit
		add_settings_field(
			'adsense-limit',
			__( 'Limit to 3 ads', ADVADS_SLUG ),
			array($this, 'render_settings_adsense_limit'),
			$hook,
			'advanced_ads_adsense_setting_section'
		);

		// hook for additional settings from add-ons
		do_action( 'advanced-ads-adsense-settings-init', $hook );
	}

        /**
	 * render adsense settings section
	 *
	 * @since 1.5.1
	 */
	public function render_settings_section_callback(){
		// for whatever purpose there might come
	}

	/**
	 * render adsense id setting
	 *
	 * @since 1.5.1
	 */
	public function render_settings_adsense_id(){
                $adsense_id = $this->data->get_adsense_id();

                ?><input type="text" name="<?php echo GADSENSE_OPT_NAME; ?>[adsense-id]" id="adsense-id" size="32" value="<?php echo $adsense_id; ?>" />
                <p class="description"><?php _e( 'Your AdSense Publisher ID <em>(pub-xxxxxxxxxxxxxx)</em>', ADVADS_SLUG ) ?></p><?php
	}

	/**
	 * render adsense limit setting
	 *
	 * @since 1.5.1
	 */
	public function render_settings_adsense_limit(){
                $limit_per_page = $this->data->get_limit_per_page();

                ?><input type="checkbox" name="<?php echo GADSENSE_OPT_NAME; ?>[limit-per-page]" value="1" <?php checked( $limit_per_page ); ?> />
		<?php printf( __( 'Limit to %d AdSense ads', ADVADS_SLUG ), 3 ); ?>
                <p class="description">
		<?php
			printf(
				__( 'Currently, Google AdSense <a target="_blank" href="%s" title="Terms Of Service">TOS</a> imposes a limit of %d display ads per page. You can disable this limitation at your own risks.', ADVADS_SLUG ),
				esc_url( 'https://www.google.com/adsense/terms' ), 3
			); ?><br/><?php
						_e( 'Notice: Advanced Ads only considers the AdSense ad type for this limit.', ADVADS_SLUG );
	}

        /**
         * sanitize adsense settings
         *
         * @since 1.5.1
         * @param array $options all the options
         */
        public function sanitize_settings($options){

            // sanitize whatever option one wants to sanitize
            if(isset($options['adsense-id']) && $options['adsense-id'] != ''){
                if(0 !== strpos( $options['adsense-id'], 'pub-' )){
                    // add settings error
                    add_settings_error(
                            'adsense-limit',
                            'settings_updated',
                            __( 'The Publisher ID has an incorrect format. (must start with "pub-")', ADVADS_SLUG ));
                }
		// trim publisher id
		$options['adsense-id'] = trim($options['adsense-id']);
            }

            return $options;
        }

        /**
         * add adsense setting tab
         *
         * @since 1.5.1
         * @param arr $tabs existing setting tabs
         * @return arr $tabs setting tabs with AdSense tab attached
         */
        public function setting_tabs(array $tabs){

            $tabs['adsense'] = array(
                'page' => $this->settings_page_hook,
                'group' => ADVADS_SLUG . '-adsense',
                'tabid' => 'adsense',
                'title' => __( 'AdSense', ADVADS_SLUG )
            );

            return $tabs;
        }

}
