<?php
/**
 * Hauptfunktionsdatei des UpFront-Themes
 *
 * @since        1.0.0
 *
 * @package      UpFront
 * @subpackage   UpFront/header
 */

/* Verhindert direkten Zugriff auf diese Datei */
if ( ! defined( 'WP_CONTENT_DIR' ) ) {
	die( 'Bitte greife nicht direkt auf diese Datei zu.' );
}

UpFrontDisplay::html_open();

wp_head();

UpFrontDisplay::body_open();
