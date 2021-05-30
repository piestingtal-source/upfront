<?php
/**
 * All settings of UpFront Unlimited and UpFront Lite
 *
 * @package UpFront
 * @author UpFront Unlimited Team
 *
 **/

class UpFrontSettings {

	private static $settings = array(
		'upfront-branch' => 'unlimited',
		'menu-name' => 'UpFront Theme',
		'slug' => 'upfront',
	);

	/**
	 *
	 * Construct
	 *
	 */
	function __construct(){
	}


	/**
	 *
	 * return the setting
	 *
	 */

	public static function get($key){
		return self::$settings[$key];
	}


	/**
	 *
	 * Settings from application class
	 *
	 */
	public static function set_enviroment(){

		/*	Errors	*/
		error_reporting(E_ALL & ~E_DEPRECATED & ~E_STRICT);
		@ini_set('display_errors', 'Off');

	}

	/**
	 *
	 * Visual Editor settings
	 *
	 */
	public static function set_visual_editor_settings(){

		//Attempt to raise memory limit to max
		@ini_set('memory_limit', apply_filters('upfront_memory_limit', WP_MAX_MEMORY_LIMIT));

	}


}