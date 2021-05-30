<?php

class UpFrontCompatibilityAMember {

	/**
	 * Constructor
	 */
	private function __construct() {

	}

	/**
	 * Gets the instance of the class
	 */
	public static function init() {


		if(!class_exists('am4PluginsManager')){
			return;
		}

		add_action('wp', array(__CLASS__, 'enqueue_styles'));

	}

	public static function enqueue_styles() {

		wp_enqueue_style('upfront-amember', upfront_url() . '/library/compatibility/amember/amember.css');

	}


}	