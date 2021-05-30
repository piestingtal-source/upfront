<?php
class UpFrontMaintenance {

	public static $available_upgrades = array(
		'1.3.2'
	);

	/**
	 * Im Laufe der Zeit können Probleme auftreten, die zwischen Aktualisierungen behoben werden müssen, oder Namenskonventionen, die zwischen Aktualisierungen geändert werden müssen.
     * All das wird hier verarbeitet.
	 **/
	static function do_upgrades($version_to_upgrade = false) {

		$upfront_settings = get_option('upfront', array('version' => 0));
		$db_version = $upfront_settings['version'];

		if ( get_option('upfront_upgrading') == 'upgrading' ) {

			if ( !is_admin() ) {
				return wp_die('Webseiten-Upgrade läuft. Bitte versuche es bald wieder!');
			} else {
				return false;
			}

		}

		self::setup_upgrade_environment();
		UpFrontMaintenance::output_status('Aktuelle DB-Version ist ' . $db_version);

		if ( $db_version == UPFRONT_VERSION ) {
			return false;
		}

		/* Füge Upgrades die aktuelle Version hinzu, wenn diese nicht vorhanden ist, sodass die grundlegende Upgrade-Routine weiterhin ausgeführt wird */
		if ( !in_array(UPFRONT_VERSION, self::$available_upgrades) ) {
			self::$available_upgrades[] = UPFRONT_VERSION;
		}

		if ( !$version_to_upgrade ) {

			foreach ( self::$available_upgrades as $possible_upgrade ) {

				if ( version_compare( $db_version, $possible_upgrade, '<' ) ) {

					$version_to_upgrade = $possible_upgrade;
					break;

				}

			}

		}

		/* Do specified upgrade routine */
		if ( $upgrade_in_progress = $version_to_upgrade ) {

			$version_filename = str_replace( '.', '', $upgrade_in_progress );

			if ( version_compare( $db_version, $upgrade_in_progress, '<' ) ) {

				self::start_upgrade($upgrade_in_progress);

				if ( file_exists(UPFRONT_LIBRARY_DIR . '/maintenance/upgrade-' . $version_filename . '.php') ) {
					require_once UPFRONT_LIBRARY_DIR . '/maintenance/upgrade-' . $version_filename . '.php';
				}

				do_action('upfront_do_upgrade_' . $version_filename);

				self::after_upgrade($upgrade_in_progress);

			}

		}

		return true;

	}


	public static function setup_upgrade_environment() {

		global $wpdb;

		@ignore_user_abort( true );
		@set_time_limit( 0 );

		if ( function_exists('apc_clear_cache') ) {
			apc_clear_cache();
		}

		$wpdb->flush();
		$wpdb->query("SET SESSION query_cache_type=0;");

	}


	public static function output_status( $text ) {

		if ( function_exists('getmypid') ) {

			if ( $pid = @getmypid() ) {
				error_log('UpFront-Upgrade-Status (PID = ' . $pid . '): ' . $text);
				return true;
			}

		}

		error_log('UpFront-Upgrade-Status: ' . $text);
		return true;

	}


	public static function start_upgrade($version) {

		update_option( 'upfront_upgrading', 'upgrading' );

		self::output_status('Derzeit wird ein Upgrade durchgeführt auf ' . $version );

	}


	public static function after_upgrade($version) {

		/* Aktualisiere die Version hier. */
		$upfront_settings            = get_option( 'upfront', array( 'version' => 0 ) );
		$upfront_settings['version'] = $version;
		

		update_option( 'upfront', $upfront_settings );
		delete_option( 'upfront_upgrading' );

		UpFrontMaintenance::output_status( 'DB-Version einstellen auf ' . $version );

		/* Flush caches */
		do_action( 'upfront_db_upgrade' );

		if (UpFrontOption::get('headway-support')) {
			do_action('headway_db_upgrade');
		}

		if (UpFrontOption::get('bloxtheme-support')) {
			do_action('blox_db_upgrade');
		}

		UpFront::set_autoload();

		/* Führe das nächste Upgrade aus, falls verfügbar */
		$index_of_current_version = array_search($version, self::$available_upgrades);

		if ( isset(self::$available_upgrades[$index_of_current_version + 1]) ) {

			$next_upgrade = self::$available_upgrades[$index_of_current_version + 1];

			return self::do_upgrades($next_upgrade);

		} else {

			UpFront::db_dbdelta();
			UpFrontElementsData::merge_core_default_design_data();

			if ( current_user_can('manage_options') && !is_front_page() ) {
				wp_safe_redirect( admin_url(), 302 );
			} else {
				wp_safe_redirect( home_url(), 302 );
			}

			die();

		}

	}

}
