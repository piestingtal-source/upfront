<?php
class UpFrontResponsiveGrid {


	public static function init() {

		if ( !self::is_enabled() )
			return false;

		add_action('upfront_head_extras', array(__CLASS__, 'add_meta_viewport'));
		add_action('init', array(__CLASS__, 'cookie_baker'));

	}


	/**
	 * Checks if the responsive grid is active or not.
	 * 
	 * Will check against the main option that's set in the Gitter mode of the visual editor 
	 * and the cookie that disables the responsive grid if the visitor wishes to do so.
	 **/
	public static function is_active() {

		//If the responsive grid isn't enabled then don't bother.
		if ( !self::is_enabled() )
			return false;

		//If the user has clicked on the full site link in the footer block then it'll set this cookie that's being checked.
		if ( self::is_user_disabled() )
			return false;

		//If it's the visual editor or the visual editor iframe
		if ( UpFrontRoute::is_visual_editor() || upfront_get('visual-editor-open') )
			return false;

		return true;

	}


	public static function is_user_disabled() {

		if ( upfront_get('full-site') != 'false' )
			if ( upfront_get('upfront-full-site', $_COOKIE) == 1 || upfront_get('full-site') == 'true' )
				return true;

		return false;

	}


	public static function is_enabled() {

		//If the theme doesn't support the responsive grid, then disable it.
		if ( !current_theme_supports('upfront-grid') || !current_theme_supports('upfront-responsive-grid') )
			return false;

		return UpFrontSkinOption::get('enable-responsive-grid', false, true);

	}


	public static function add_meta_viewport() {

		if ( !self::is_active() )
			return false;

		if(UpFrontOption::get('allow-mobile-zooming')){
			echo '<meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=5.0, user-scalable=yes" />' . "\n";
		}else{
			echo '<meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no" />' . "\n";
		}

	}


	public static function cookie_baker() {

		/* If headers were already sent, then don't follow through with this function or it will err. */
		if ( headers_sent() )
			return false;

		if ( upfront_get('full-site') == 'true' )
			return setcookie('upfront-full-site', 1, time() + 60 * 60 * 24 * 7, '/');

		if ( upfront_get('full-site') == 'false' )
			return setcookie('upfront-full-site', false, time() - 3600, '/');

		// If there is not cookie, site should be showed normal
		if( null == upfront_get('full-site') )
			return setcookie('upfront-full-site', false, time() - 3600, '/');

		return false;

	}


}