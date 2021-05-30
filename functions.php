<?php
/**
 * Hauptfunktionsdatei des UpFront-Themas
 *
 * @since 1.0.0
 * @package UpFront
 *
 * - Original von DerN3rd - WMS N@W
 * - UpFront vom WMS N@W Team - PSOURCE
 */

require 'vendor/theme-update-checker.php';
$MyThemeUpdateChecker = new ThemeUpdateChecker(
	'upfront', 
	'https://n3rds.work//wp-update-server/?action=get_metadata&slug=upfront' 
);

/**
 * Automatic Updates
 * Must go before UpFront::init();
 */
if ( get_option( 'upfront-disable-automatic-core-updates' ) !== '1' ) {

	add_filter( 'auto_update_theme', '__return_true' );

}

/**
 *
 * Lade UpFront
 */

/* Verhindert direkten Zugriff auf diese Datei */
if ( ! defined( 'WP_CONTENT_DIR' ) ) {
	die( 'Bitte greife nicht direkt auf diese Datei zu.' );
}

/* Stelle sicher, dass PHP 7.0 oder neuer installiert ist und WordPress 3.4 oder neuer installiert ist. */
require_once get_template_directory() . '/library/common/compatibility-checks.php';

/* Load required packages */
require_once get_template_directory() . '/vendor/autoload.php';

/* Lade UpFront! */
require_once get_template_directory() . '/library/common/functions.php';
require_once get_template_directory() . '/library/common/parse-php.php';
require_once get_template_directory() . '/library/common/settings.php';
require_once get_template_directory() . '/library/loader.php';

UpFront::init();


/**
 *
 * Unterstützung für Plugin-Vorlagen
 */

add_filter(
	'template_include',
	function( $template ) {
		return UpFrontDisplay::load_plugin_template( $template );
	}
);
