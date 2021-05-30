<?php

class UpFrontNotices extends UpFrontNotice{


	public function __construct(  ) {

	}

	public static function init() {

		if(get_option('upfront_deny_admin_notices'))
			return;

		// Support Us Notice	
		self::supportUsNotice();

	}

	private static function supportUsNotice(){

		self::notice( 'support-us', UPFRONT_LIBRARY_DIR . '/admin/partials/notices/support-us.php' );
		self::$defer_delay      = 7 * DAY_IN_SECONDS;
		self::$first_time_delay = 10 * MINUTE_IN_SECONDS; // 10 minutes

		add_action( 'load-plugins.php', array( __CLASS__, 'defer_first_time' ));
		add_action( 'admin_notices', array( __CLASS__, 'display_notice' ));
		add_action( 'admin_post_upfront_dismiss_notice', array( __CLASS__, 'dismiss_notice' ));

	}


	public static function display_notice() {

		// Make sure this is the Plugins screen
		/*
		if ( self::get_current_screen_id() !== 'plugins' ) {
			return;
		}*/

		// Check user capability
		if ( ! self::current_user_can_view() ) {
			return;
		}

		// Make sure the notice is not dismissed
		if ( self::is_dismissed() ) {
			return;
		}

		// Display the notice
		self::include_template();


	}




}