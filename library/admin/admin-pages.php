<?php

class UpFrontAdminPages {


	/**
	 * @see UpFrontAdmin::visual_editor_redirect
	 *
	 * Diese Funktion dient hier ausschließlich der Sicherung. Der PHP-Header-Speicherort sollte all dies ersetzen.
	 **/
	public static function visual_editor() {

		UpFrontAdmin::show_header('UpFront Visual Editor');

			echo sprintf( 
				__('<p>Du wirst jetzt umgeleitet. Wenn Du nicht innerhalb von 3 Sekunden umgeleitet wirst, klicke <a href="%s"><strong>hier</strong></a>.</p>', 'upfront'), 
				home_url() . '/?visual-editor=true'
			);

			echo '<meta http-equiv="refresh" content="3;URL=' . home_url() . '/?visual-editor=true">';

		UpFrontAdmin::show_footer();

	}


	public static function getting_started() {

		UpFrontAdmin::show_header();

			require_once UPFRONT_LIBRARY_DIR . '/admin/pages/getting-started.php';

		UpFrontAdmin::show_footer();

	}


	public static function templates() {

		UpFrontAdmin::show_header();

			require_once UPFRONT_LIBRARY_DIR . '/admin/pages/templates.php';

		UpFrontAdmin::show_footer();

	}


	public static function options() {

		UpFrontAdmin::show_header();

			require_once UPFRONT_LIBRARY_DIR . '/admin/pages/options.php';

		UpFrontAdmin::show_footer();

	}


	public static function tools() {

		UpFrontAdmin::show_header();

			require_once UPFRONT_LIBRARY_DIR . '/admin/pages/tools.php';

		UpFrontAdmin::show_footer();

	}

	public static function license() {

		UpFrontAdmin::show_header();

			require_once UPFRONT_LIBRARY_DIR . '/admin/pages/license.php';

		UpFrontAdmin::show_footer();

	}

}
