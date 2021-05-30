<?php
/**
 * Headway Compatibility Headway
 */
class UpFrontCompatibilityHeadway {

	/**
	 * Init Method
	 *
	 * @return void
	 */
	public static function init() {

		if ( ! UpFrontOption::get( 'headway-support' ) ) {
			return;
		}

		self::load();
	}

	/**
	 * Load
	 *
	 * @return void
	 */
	public static function load() {

		$GLOBALS['headway_default_element_data'] = $GLOBALS['upfront_default_element_data'];

		self::upfront_define_headway_constants();

		UpFront::load(
			array(
				'abstract/api-admin-meta-box',
				'abstract/api-box',
				'admin/admin-write' => true,
				'admin/admin-pages',
				'admin/api-admin-inputs',
			)
		);

		require UPFRONT_LIBRARY_DIR . '/compatibility/headway/functions.php';
		require UPFRONT_LIBRARY_DIR . '/compatibility/headway/abstract.php';

		add_action(
			'after_setup_theme',
			function() {
				UpFrontCompatibilityHeadway::upfront_declare_headway_classes();
				Headway::init();
			}
		);
	}

	/**
	 * Headway constants.
	 *
	 * @return void
	 */
	public static function upfront_define_headway_constants(){

		define( 'HEADWAY_VERSION', '3.8.9' );
		define( 'HEADWAY_DIR', UPFRONT_DIR );
		define( 'HEADWAY_LIBRARY_DIR', UPFRONT_LIBRARY_DIR );
		define( 'HEADWAY_SITE_URL', UPFRONT_SITE_URL );
		define( 'HEADWAY_DASHBOARD_URL', UPFRONT_DASHBOARD_URL );
		define( 'HEADWAY_EXTEND_URL', UPFRONT_EXTEND_URL );
		define( 'HEADWAY_DEFAULT_SKIN', UPFRONT_DEFAULT_SKIN );
		define( 'HEADWAY_CHILD_THEME_ACTIVE', UPFRONT_CHILD_THEME_ACTIVE );
		define( 'HEADWAY_CHILD_THEME_DIR', UPFRONT_CHILD_THEME_DIR );
		define( 'HEADWAY_UPLOADS_DIR', UPFRONT_UPLOADS_DIR );
		define( 'HEADWAY_CACHE_DIR', UPFRONT_CACHE_DIR );
	}

	/**
	 * Declare Headway Classes
	 *
	 * @return void
	 */
	public static function upfront_declare_headway_classes() {

		$upfront_core_classes = array(
			'UpFrontUpdater',
			'UpFrontLifeSaver',
			'UpFrontLifeSaver\helpers\Plugin',
			'UpFrontLifeSaver\helpers\json',
			'UpFrontAdminMetaBoxAPI',
			'UpFrontBlockAPI',
			'UpFrontVisualEditorBoxAPI',
			'UpFrontVisualEditorPanelAPI',
		);

		$upfront_classes_array = array();

		foreach ( get_declared_classes() as $key => $upfront_class ) {

			if ( strpos( $upfront_class, 'UpFront' ) !== false ) {

				if ( in_array( $upfront_class, $upfront_core_classes, true ) ) {
					continue;
				}

				$upfront_classes_array[ $upfront_class ] = get_class_methods( $upfront_class );
			}
		}

		foreach ( $upfront_classes_array as $upfront_class => $methods ) {

			$headway_classname = str_replace( 'UpFront', 'Headway', $upfront_class );
			if ( ! class_exists( $headway_classname ) ) {
				class_alias( $upfront_class, $headway_classname );
			}
		}
	}
}