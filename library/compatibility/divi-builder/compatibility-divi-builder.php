<?php

class UpFrontCompatibilityDiviBuilder {

	/**
	 * Constructor
	 */
	private function __construct() {

	}

	/**
	 * Gets the instance of the class
	 */
	public static function init() {
		/*

		commented due DIVI Builder 4.2+ works with UpFront.

		if(!class_exists('ET_Builder_Plugin')){
			return;
		}

		add_action('upfront_whitewrap_open', array(__CLASS__, 'upfront_whitewrap_close_whitewraps_tag'));
		add_action('upfront_whitewrap_close', array(__CLASS__, 'upfront_whitewrap_open_whitewraps_tag'));
		*/

	}

	public static function upfront_whitewrap_close_whitewraps_tag(){
		echo '</div>';
	}
	public static function upfront_whitewrap_open_whitewraps_tag(){
		echo '<div>';	
	}

}	