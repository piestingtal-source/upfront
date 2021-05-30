<?php

class UpFrontCompatibilityBlox {


	public static function init() {

		if(!UpFrontOption::get('bloxtheme-support'))
			return;

		self::load();

	}

	public static function load(){

		$GLOBALS['blox_default_element_data'] = $GLOBALS['upfront_default_element_data'];

		UpFrontCompatibilityBlox::upfront_define_bloxtheme_constants();

		UpFront::load(array(
			'abstract/api-admin-meta-box',
			'abstract/api-box',
			'admin/admin-write' => true,
			'admin/admin-pages',
			'admin/api-admin-inputs'
		));

		require UPFRONT_LIBRARY_DIR . '/compatibility/blox/functions.php';	
		require UPFRONT_LIBRARY_DIR . '/compatibility/blox/abstract.php';	

		add_action('after_setup_theme',function(){

			UpFrontCompatibilityBlox::upfront_declare_bloxtheme_classes();
			Blox::init();

		});

	}

	public static function upfront_define_bloxtheme_constants(){

		define('BLOX_VERSION', 				"1.0.6");
		define('BLOX_DIR', 					UPFRONT_DIR);
		define('BLOX_LIBRARY_DIR', 			UPFRONT_LIBRARY_DIR);
		define('BLOX_SITE_URL', 			UPFRONT_SITE_URL);
		define('BLOX_DASHBOARD_URL', 		UPFRONT_DASHBOARD_URL);
		define('BLOX_EXTEND_URL', 			UPFRONT_EXTEND_URL);
		define('BLOX_DEFAULT_SKIN', 		UPFRONT_DEFAULT_SKIN);
		define('BLOX_CHILD_THEME_ACTIVE', 	UPFRONT_CHILD_THEME_ACTIVE);
		define('BLOX_CHILD_THEME_DIR', 		UPFRONT_CHILD_THEME_DIR);
		define('BLOX_UPLOADS_DIR', 			UPFRONT_UPLOADS_DIR);
		define('BLOX_CACHE_DIR', 			UPFRONT_CACHE_DIR);	

	}


	public static function upfront_declare_bloxtheme_classes(){

		$upfrontClassArray = array();

		foreach (get_declared_classes() as $key => $upfrontClass) {

			if (strpos($upfrontClass, 'UpFront') !== false) {

				if(
					$upfrontClass == 'UpFrontUpdater' ||
					$upfrontClass == 'UpFrontLifeSaver' ||
					$upfrontClass == 'UpFrontLifeSaver\helpers\Plugin' ||
					$upfrontClass == 'UpFrontLifeSaver\helpers\json' ||
					$upfrontClass == 'UpFrontAdminMetaBoxAPI' ||
					$upfrontClass == 'UpFrontBlockAPI' ||
					$upfrontClass == 'UpFrontVisualEditorBoxAPI' ||
					$upfrontClass == 'UpFrontVisualEditorPanelAPI' 
					)
					continue;

				$upfrontClassArray[$upfrontClass] = get_class_methods($upfrontClass);

			}
		}

		foreach ($upfrontClassArray as $upfrontClass => $methods) {

			$bloxClassName 	= str_replace('UpFront', 'Blox', $upfrontClass);
			if ( ! class_exists( $bloxClassName ) ){
				$status = class_alias($upfrontClass, $bloxClassName);
				if (!$status) {
					error_log('Can\'t create class: ' . $bloxClassName);
				}
			}
		}
	}
}