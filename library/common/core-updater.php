<?php
/**
 * UpFront Updater main function file
 *
 * @since 1.4.0
 * @package UpFront/library
 */

/**
 * Updater class
 */
class UpFrontCoreUpdater {


	/**
	 *
	 * Detect CMS
	 */
	private static function detect_cms() {
		return ( function_exists( 'classicpress_version_short' ) ) ? 'ClassicPress' : 'WordPress';
	}


	/**
	 *
	 * UpFront Plugins
	 */
	public static function plugins() {
		return array(
			'upfront-example',
			'upfront-filter-gallery',
			'upfront-gallery',
			'upfront-lifesaver',
		);
	}


	/**
	 *
	 * Update UpFront plugins
	 */
	private static function update_upfront_plugins() {

		foreach ( self::plugins() as $key => $slug ) {
			$path = ABSPATH . 'wp-content/plugins/' . $slug;
			self::updater( $slug, $path, false );
		}
	}

	/**
	 *
	 * Is a UpFront Plugin?
	 *
	 * @param string $slug Plugin Slug.
	 * @return boolean
	 */
	public static function is_upfront_plugin( $slug ) {
		return in_array( $slug, self::plugins(), true );
	}

	/**
	 * Run the updater
	 *
	 * @return void
	 */
	public static function updater() {

		/**
		 *
		 * Use "developer" version or "production" version
		 */
		$package_type = ( get_option( 'upfront-use-developer-version' ) ) ? 'developer' : 'software';
		$target       = UPFRONT_DIR . '/functions.php';
		$token        = get_option( 'upfront_service_token' );
		$slug         = 'upfront';
		$url          = UPFRONT_CDN_URL . $package_type . '/?action=get_metadata&slug=' . $slug;

		if ( '' !== $token ) {
			$url .= '&token=' . $token;
		}
		$url .= '&cms=' . self::detect_cms();

		add_filter(
			'puc_is_slug_in_use-' . $slug,
			function() {
				return false;
			}
		);

		$update_checker = Puc_v4_Factory::buildUpdateChecker( $url, $target, $slug, 12 );
	}
}