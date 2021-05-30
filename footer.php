<?php
/**
 * UpFront Theme Hauptfunktionsdatei
 *
 * @since        1.0.0
 *
 * @package      UpFront
 * @subpackage   UpFront/footer
 */

/** Verhindert direkten Zugriff auf diese Datei */

if ( ! defined( 'WP_CONTENT_DIR' ) ) {

	die( 'Bitte greife nicht direkt auf diese Datei zu.' );
}

/* WordPress und viele Plugins erfordern die Funktion in dieser Datei, also müssen wir sie wohl verwenden :-(. */
wp_footer();

UpFrontDisplay::body_close();

UpFrontDisplay::html_close();
