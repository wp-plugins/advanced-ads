<?php
/**
 * container class for admin notices
 *
 * @package WordPress
 * @subpackage Advanced Ads Plugin
 * @since 1.4.5
 */
class AdvAds_Admin_Notices {

	/**
	 * array with new versions
	 */
	public $new_versions = array();

	/**
	 * array with update notices
	 */
	public $update_notices = array();

	/**
	 * handle update notices
	 *
	 * @param str $old_version previous installed version
	 * @param str $new_version new version
	 * @param arr $updated_versions array with updated versions
	 */
	public function __construct($old_version = 0, $new_version = 0, $updated_versions = array()){

		if ( $updated_versions == array() && $old_version == 0 ) { return; }

		// load previous messages, if still existing
		if ( is_array( $updated_versions ) ){
			$this->new_versions = $updated_versions;
		}

		// load new messages
		if ( $old_version ){
			if ( version_compare( $old_version, '1.4.5' ) == -1 ){
				$this->new_versions[] = '1.4.5';
			}
			// put notices into session to be able to give if back
			if ( $this->new_versions !== $updated_versions ) {
				$_SESSION['advanced_ads_version_notices'] = $this->new_versions; }
		}

		if ( $this->new_versions == array() ) { return; }

		// load update notices
		// not ready for translation, since there will always be change
		$this->update_notices = array(
			'1.4.5' => 'Advanced Ads 1.4.5 changes the behavior of some display conditions. Please read this <a href="'.ADVADS_URL.'advanced-ads-1-4-5/" target="_blank">update post</a> to learn if this change should concern you.',
		);

		// register update notices
		add_action( 'admin_notices', array($this, 'show_update_admin_notices' ) );
	}

	/**
	 *
	 * display update notice
	 *
	 */
	public function show_update_admin_notices(){

		if ( $this->new_versions == array() ) { return; }

		?><div class="error advads-admin-notices"><?php
foreach ( $this->new_versions as $_version ) :
	if ( isset($this->update_notices[$_version]) ) :
		?><p><?php echo $this->update_notices[$_version]; ?></p><?php
	endif;
			endforeach;
			$admin_url = admin_url( '?page=advanced-ads&advads-remove-notices=1' );
			?><a class="button-primary" href="<?php echo $admin_url; ?>"><?php _e( 'Dismiss all notices', ADVADS_SLUG ); ?></a><br class="clear"/></div><?php
	}

}