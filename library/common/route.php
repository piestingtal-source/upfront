<?php
class UpFrontRoute {


	public static function init() {

		/* For improved compatibility with Shopp and anything that modifies these hooks */
			add_action('after_setup_theme', array(__CLASS__, 'remove_parsing_hooks'));

		/* Parse request runs before 'wp', but does not have $post or query set up.  This speeds things up and keeps 404s from happening */
			add_action('parse_request', array(__CLASS__, 'maybe_run_trigger'), -1);

		/* We use 'wp' on this so $post is set up so we can query meta */
			add_action('wp', array(__CLASS__, 'maybe_redirect_301'));

		/* Direct to VE, theme preview warning, or grid */
			add_action('template_redirect', array(__CLASS__, 'direct'), 1);

	}


	/**
	 * To improve compatibility with plugins and prevent interference.
	 **/
	public static function remove_parsing_hooks() {

		if ( !self::is_visual_editor() && !self::is_visual_editor_iframe() ) {
			return;
		}

		remove_all_actions('parse_query');
		remove_all_actions('parse_request');

	}


	/**
	 * Direct index.php to the appropriate function
	 * 
	 * @return bool
	 **/
	public static function direct() {


		//If viewing the visual editor, stop the template loading and show the visual editor.
		if ( self::is_visual_editor() ) {

			//If user is logged in and can't visually edit, loop them back to normal template.
			if ( is_user_logged_in() && !UpFrontCapabilities::can_user_visually_edit() ) {

				wp_die('Du hast nicht genügend Berechtigungen, um den UpFront Visual Editor zu verwenden.<br /><br /><a href="' . home_url() . '">Zurück zur Startseite</a>');			

				return false;

			//If the user isn't logged in at all, log 'em in and loop back to visual editor as long as debug mode isn't active
			} elseif ( !is_user_logged_in() && !UpFrontOption::get('debug-mode') ) {

				return auth_redirect();

			} else if ( defined('FORCE_SSL_ADMIN') && FORCE_SSL_ADMIN && !is_ssl() ) {

				wp_safe_redirect(str_replace('http://', 'https://', upfront_get_current_url()));
				die();

			}

			UpFrontVisualEditor::display();
			die();

		//Theme Preview
		} elseif ( self::is_grid() ) {

			UpFront::load('visual-editor/iframe-grid');

			UpFrontVisualEditorIframeGrid::show();
			die();

		}

	}


	public static function maybe_run_trigger() {

		if ( !self::is_trigger() )
			return;

		add_filter('restricted_site_access_is_restricted', '__return_false');

		//Deactivate redirect so the weird 301's don't happen
		remove_action('template_redirect', 'redirect_canonical');
		add_filter('wp_redirect', '__return_false', 12);

		//Cycle through
		switch ( upfront_get('upfront-trigger') ) {

			case 'compiler':				
				UpFrontCompiler::output_trigger();
				break;

			case 'layout-redirect':
				self::redirect_to_layout();
				break;

			case 'media-uploader':

				if ( !UpFrontCapabilities::can_user_visually_edit() )
					die();

				UpFront::load('visual-editor/media-uploader');
				break;

			case 'code-mirror':
				if ( !UpFrontCapabilities::can_user_visually_edit() )
					die();

				UpFront::load('visual-editor/code-mirror');
				break;

			case 'content-editor':			
				if ( !UpFrontCapabilities::can_user_visually_edit() )
					die();

				UpFront::load('visual-editor/content-editor');
				break;

			case 'block-js':
					$UpFrontGutenbergBlocks = new UpFrontGutenbergBlocks();
					$UpFrontGutenbergBlocks->block_js();
					add_action('init',function(){
					});
				break;
		}

		exit;

	}


	/**
	 * If a post, page, or any other singular item has the 301 Redirect set, then do the redirect.
	 **/
	public static function maybe_redirect_301() {

		global $post;

		//Don't try redirecting if the headers are already sent.  Otherwise, it'll result in an error and no redirect.
		if ( headers_sent() )
			return false;

		//Make sure that it's a single post and that $post is a valid object.
		if ( !is_object($post) || !is_singular() )
			return false;

		//Do not try redirecting if it's the visual editor or admin
		if ( is_admin() || self::is_visual_editor() || self::is_visual_editor_iframe() )
			return false;

		//If the redirect URL isn't set, then don't try anything.
		if ( !($redirect_url = UpFrontLayoutOption::get($post->ID, 'redirect-301', null, true, 'seo')) )
			return false;

		//If there is no HTTP or HTTPS in the URL, add it.
		if ( strpos($redirect_url, 'http://') !== 0 && strpos($redirect_url, 'https://') !== 0 )
			$redirect_url = 'http://' . $redirect_url;

		wp_redirect($redirect_url, 301);
		die();

	}


	/**
	 * Determine whether or not the site is being viewed in normal display mode.
	 * 
	 * @return bool
	 **/
	public static function is_display() {

		if ( self::is_visual_editor() )
			return false;

		if ( self::is_trigger() )
			return false;

		if ( is_admin() )
			return false;

		return true;

	}


	/**
	 * Checks if the visual editor is open.
	 * 
	 * @return bool
	 **/
	public static function is_visual_editor() {

		return upfront_get('visual-editor', false);

	}


	public static function is_trigger() {

		return ( upfront_get('upfront-trigger') ) ? true : false;

	}


	public static function is_theme_preview() {

		return (upfront_get('preview') == 1 && upfront_get('preview_iframe') == 1) || upfront_post('wp_customize');

	}


	public static function is_visual_editor_iframe( $mode = null ) {

		if ( ! upfront_get('ve-iframe') || ! UpFrontCapabilities::can_user_visually_edit() )
			return false;

		if ( $mode ) {
			return upfront_get('ve-iframe-mode') == $mode;
		}

		return true;

	}


	public static function is_grid() {

		return self::is_visual_editor_iframe('grid');

	}


	/**
	 * Used for when a user clicks View Site in the Visual Editor
	 **/
	public static function redirect_to_layout() {

		remove_filter('wp_redirect', '__return_false', 12);

		if ( upfront_get('debug') && UpFrontCapabilities::can_user_visually_edit() )
			wp_die(UpFrontLayout::get_url(upfront_get('layout')));

		return wp_safe_redirect(UpFrontLayout::get_url(upfront_get('layout')));

	}


}