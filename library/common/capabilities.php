<?php
class UpFrontCapabilities {


	public static function init() {

		add_filter('members_get_capabilities', 'UpFrontCapabilities::register');

	}


	public static function register($capabilities) {

		$capabilities[] = 'upfront_visual_editor';

		return apply_filters('upfront_capabilities', $capabilities);

	}


	public static function can_user($capability) {

		if ( !function_exists('members_check_for_cap') )
			 return ( current_user_can('manage_options') || is_super_admin() );

		return current_user_can($capability);

	}


	/**
	 * Checks if the user can access the visual editor.
	 * 
	 * @uses upfront_user_level()
	 * @uses UpFrontOption::get()
	 *
	 * @return bool
	 **/
	public static function can_user_visually_edit($ignore_debug_mode = false) {

		if ( !$ignore_debug_mode && UpFrontOption::get('debug-mode') )
			return true;

		return is_user_logged_in() && self::can_user('upfront_visual_editor');

	}


}