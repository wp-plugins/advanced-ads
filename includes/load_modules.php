<?php

final class Advanced_Ads_ModuleLoader {

	protected static $loader;
	protected static $textdomains = array();
	protected static $modules = array();

	public static function getLoader()
	{
		if ( null === self::$loader ) {
			self::$loader = require_once ADVADS_BASE_PATH . 'vendor/autoload_52.php';
		}

		return self::$loader;
	}

	/**
	 * Module loader options:
	 * - array 'disabled': Pretty name by (module) dirname
	 *
	 * @param string $path    path to modules
	 * @param array  $options module loader options
	 */
	public static function loadModules($path, $options = array()) {
		$loader = self::getLoader();

		$disabledModules = isset($options['disabled']) ? (array) $options['disabled'] : array();

		// iterate modules
		foreach ( glob( $path . '*/main.php' ) as $module ) {
			$modulePath = dirname( $module );
			$moduleName = basename( $modulePath );

			// skip if disabled
			if ( isset( $disabledModules[$moduleName] ) ) {
				continue ;
			}

			self::$modules[$moduleName] = $modulePath;
			if ( file_exists( $modulePath . '/config.php' ) ) {
				$config = require $modulePath . '/config.php';
				// append autoload classmap
				if ( isset($config['classmap']) && is_array( $config['classmap'] ) ) {
					$loader->addClassmap( $config['classmap'] );
				}
				// append textdomain
				if ( isset($config['textdomain']) && $config['textdomain'] ) {
					self::$textdomains[$config['textdomain']] = "modules/$moduleName/languages";
				}
			}
		}

		// register textdomains if non-empty
		if ( self::$textdomains !== array() ) {
			add_action( 'plugins_loaded', array( 'Advanced_Ads_ModuleLoader', 'load_module_textdomains') );
		}

		// load modules
		foreach ( self::$modules as $name => $path ) {
			require_once $path . '/main.php';
		}
	}

	public static function load_module_textdomains() {
		foreach ( self::$textdomains as $slug => $rel_path ) {
			load_plugin_textdomain( ADVADS_SLUG, false, ADVADS_BASE_DIR . '/languages' );
		}
	}
}
