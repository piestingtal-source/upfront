<?php

/**
 * Parses PHP using eval.
 *
 * @param string $content PHP to be parsed.
 * 
 * @return mixed PHP that has been parsed.
 **/
function upfront_parse_php($content) {

	/* If UpFront PHP parsing is disabled, then return the content now. */
	if ( defined('UPFRONT_DISABLE_PHP_PARSING') && UPFRONT_DISABLE_PHP_PARSING === true )
		return $content;

	/* If it's a WordPress Network setup and the current site being viewed isn't the main site, 
	   then don't parse unless UPFRONT_ALLOW_NETWORK_PHP_PARSING is true. */
	if ( !is_main_site() && (!defined('UPFRONT_ALLOW_NETWORK_PHP_PARSING') || UPFRONT_ALLOW_NETWORK_PHP_PARSING === false) )
		return $content;

	if ( empty( $content ))
		return $content;

	ob_start();

	$eval = eval("?>$content<?php ;");

	if ( $eval === null ) {

		$parsed = ob_get_contents();		

	} else {

		$error 	= error_get_last();
		$parsed = '<p>' . sprintf( __('<strong>Error while parsing PHP:</strong> %s', 'upfront'), $error['message']) . '</p>';

	}

	ob_end_clean();

	return $parsed;

}
