<?php
class UpFrontWrappers {


	public static $default_wrappers = array(
		'default' => array(
			'id' => 'default',
			'position' => 0,
			'settings' => array(
				'fluid' => false,
				'fluid-grid' => false,
				'columns' => null,
				'column-width' => null,
				'gutter-width' => null
			)
		)
	);

	public static $default_wrapper_id = 'default';

	public static $default_columns = 24;

	public static $default_column_width = 20;

	public static $default_gutter_width = 20;

	public static $default_wrapper_margin_top = 30;

	public static $default_wrapper_margin_bottom = 30;


	public static $global_grid_column_width = null;

	public static $global_grid_gutter_width = null;


	public static function init() {

		/* Set defaults */
			self::$default_columns = UpFrontSkinOption::get('columns', false, self::$default_columns);
			self::$global_grid_column_width = UpFrontSkinOption::get('column-width', false, self::$default_column_width);
			self::$global_grid_gutter_width = UpFrontSkinOption::get('gutter-width', false, self::$default_gutter_width);

			self::$default_wrappers['default']['settings']['use-independent-grid'] = false;
			self::$default_wrappers['default']['settings']['columns'] = self::$default_columns;
			self::$default_wrappers['default']['settings']['column-width'] = self::$default_column_width;
			self::$default_wrappers['default']['settings']['gutter-width'] = self::$default_gutter_width;

		/* Setup hooks */
		add_action('upfront_register_elements_instances', array(__CLASS__, 'register_wrapper_instances'), 11);
		add_action('upfront_wrapper_options', array(__CLASS__, 'options_panel'), 10, 2);

		add_action('wp_head', array(__CLASS__, 'sticky_wrapper_js'));
		add_action('wp_head', array(__CLASS__, 'shrink_wrapper_js'));

	}


	public static function sticky_wrapper_js() {

		$layout_wrappers = UpFrontWrappersData::get_wrappers_by_layout( UpFrontLayout::get_current_in_use() );
		$sticky_wrappers = array();

		foreach ( $layout_wrappers as $wrapper ) {

            if ( $mirrored_wrapper = UpFrontWrappersData::get_wrapper_mirror($wrapper) ) {
                $original_wrapper = $wrapper;

                $wrapper = $mirrored_wrapper;
                $wrapper['id'] = upfront_get('id', $original_wrapper);
                $wrapper['legacy_id'] = upfront_get('legacy_id', $original_wrapper);
            }

			$wrapper_settings = upfront_get('settings', $wrapper, array());

			if ( upfront_get('enable-sticky-positioning', $wrapper_settings) ) {

				$sticky_wrappers['#wrapper-' . UpFrontWrappersData::get_legacy_id( $wrapper )] = array(
					'offset_top' => upfront_get( 'sticky-position-top-offset', $wrapper_settings, 0 )
				);

			}


		}

		if ( !$sticky_wrappers ) {
			return false;
		}

		wp_enqueue_script( 'upfront-sticky', upfront_url() . '/library/media/js/sticky.js', array( 'jquery' ) );
		wp_localize_script( 'upfront-sticky', 'UpFrontStickyWrappers', $sticky_wrappers );


	}

	public static function shrink_wrapper_js() {

		$layout_wrappers = UpFrontWrappersData::get_wrappers_by_layout( UpFrontLayout::get_current_in_use() );
		$shrink_wrappers = array();

		foreach ( $layout_wrappers as $wrapper ) {

            if ( $mirrored_wrapper = UpFrontWrappersData::get_wrapper_mirror($wrapper) ) {
                $original_wrapper = $wrapper;

                $wrapper = $mirrored_wrapper;
                $wrapper['id'] = upfront_get('id', $original_wrapper);
                $wrapper['legacy_id'] = upfront_get('legacy_id', $original_wrapper);
            }

			$wrapper_settings = upfront_get('settings', $wrapper, array());

			if ( upfront_get('enable-shrink-on-scroll', $wrapper_settings) ) {

				$shrink_wrappers['#wrapper-' . UpFrontWrappersData::get_legacy_id( $wrapper )] = array(
					'shrink_ratio' => upfront_get( 'shrink-on-scroll-ratio', $wrapper_settings, 50 ),
					'shrink_images' => upfront_get( 'shrink-contained-images', $wrapper_settings, false ),
					'shrink_elements' => upfront_get( 'shrink-contained-elements', $wrapper_settings, false ),
				);

			}


		}

		if ( !$shrink_wrappers ) {
			return false;
		}

		wp_enqueue_script( 'upfront-shrink-on-scroll', upfront_url() . '/library/media/js/shrink-on-scroll.js', array( 'jquery' ) );
		wp_localize_script( 'upfront-shrink-on-scroll', 'UpFrontShrinkWrappers', $shrink_wrappers );


	}


	public static function format_wrapper_id($wrapper_id) {

		return str_replace('wrapper-', '', $wrapper_id);

	}


	public static function register_wrapper_instances() {

		$all_wrappers = UpFrontWrappersData::get_all_wrappers();

		if ( !$all_wrappers )
			return false;

		$mirroring_wrappers_no_style = array();
		foreach ( $all_wrappers as $wrapper_id => $wrapper_options ) {

			/* Registriere NICHT die Standard-Container-Instanz */
			if ( $wrapper_id == 'default' )
				continue;

			/* Registriert keine Instanz für den gespiegelten Container */
			if ( UpFrontWrappersData::is_wrapper_mirrored($wrapper_options) ){
				if( !empty($wrapper_options['settings']['do-not-mirror-wrapper-styles']) && $wrapper_options['settings']['do-not-mirror-wrapper-styles'] == true){

					$original_wrapper = $wrapper_options['mirror_id'];
					$mirroring_wrappers_no_style[$original_wrapper] = $wrapper_id;					
				}
				continue;
			}

			$wrapper_id_for_selector    = UpFrontWrappersData::get_legacy_id( $wrapper_options );
			$wrapper_id_for_selector    = self::format_wrapper_id( $wrapper_id_for_selector);

			$wrapper_name = upfront_get('alias', upfront_get('settings', $wrapper_options, array())) ? 'Container: ' . upfront_get( 'alias', upfront_get( 'settings', $wrapper_options, array() ) ) : 'Container (unbenannt)';


			if( empty($mirroring_wrappers_no_style[$wrapper_id_for_selector]) ){
				$selector = '#wrapper-' . $wrapper_id_for_selector . ', div#whitewrap div.wrapper-mirroring-' . $wrapper_id_for_selector;
			}else{
				$selector = '#wrapper-' . $wrapper_id_for_selector;
			}


			UpFrontElementAPI::register_element_instance(array(
				'group' => 'structure',
				'element' => 'wrapper',
				'id' => 'wrapper-' . UpFrontWrappers::format_wrapper_id($wrapper_id),
				'name' => $wrapper_name,
				'selector' => $selector,
				'layout' => $wrapper_options['layout']
			));

		}

	}


	public static function is_fluid($wrapper) {

		return upfront_get('fluid', upfront_get('settings', $wrapper, array()), false, true);

	}


	public static function is_grid_fluid($wrapper) {

		$wrapper_settings = upfront_get('settings', $wrapper, array());

		return upfront_get('fluid', $wrapper_settings, false, true) && upfront_get('fluid-grid', $wrapper_settings, false, true);

	}


	public static function is_independent_grid($wrapper) {

		return upfront_get('use-independent-grid', upfront_get('settings', $wrapper, array()), false, true);

	}


	public static function get_columns($wrapper) {

		return upfront_get('columns', upfront_get('settings', $wrapper, array()), false, true);

	}


	public static function get_column_width($wrapper) {

		$wrapper_settings = upfront_get('settings', $wrapper, array());

		return upfront_get('use-independent-grid', $wrapper_settings, false, true) ? upfront_get('column-width', $wrapper_settings, false, true) : UpFrontWrappers::$global_grid_column_width;

	}


	public static function get_gutter_width($wrapper) {

		$wrapper_settings = upfront_get('settings', $wrapper, array());

		return upfront_get('use-independent-grid', $wrapper_settings, false, true) ? upfront_get('gutter-width', $wrapper_settings, false, true) : UpFrontWrappers::$global_grid_gutter_width;

	}


	public static function get_grid_width($wrapper) {

		if ( !is_array($wrapper) )
			return false;

		/* Wenn der Container gespiegelt ist, verwende die Einstellungen für das Gitter */
		if ( $potential_wrapper_mirror = UpFrontWrappersData::get_wrapper_mirror($wrapper) )
			$wrapper = $potential_wrapper_mirror;

		$columns = self::get_columns($wrapper);

		$column_width = self::get_column_width($wrapper);
		$gutter_width = self::get_gutter_width($wrapper);

		return ($column_width * $columns) + (($columns - 1) * $gutter_width);

	}


	public static function options_panel($wrapper, $layout) {

		require_once UPFRONT_LIBRARY_DIR . '/wrappers/wrapper-options.php';

		//Optionsklasse einleiten
		$options = new UpFrontWrapperOptions;
		$options->display($wrapper, $layout);

	}


	public static function get_layout_wrappers( $layout ) {

		_deprecated_function( __FUNCTION__, '3.7', 'UpFrontDataWrappers::get_wrappers_by_layout()' );

		$wrappers = UpFrontWrappersData::get_wrappers_by_layout( $layout );

		if ( ! $wrappers )
			return $wrappers;

		/* Führt das Einstellungsarray mit jedem Container zusammen, sodass es eine einzige Dimension aufweist */
		foreach ( $wrappers as $wrapper_id => $wrapper ) {

			$wrappers[ $wrapper_id ]['mirror-wrapper'] = upfront_get( 'mirror_id', $wrapper );

			$wrappers[ $wrapper_id ] = array_merge( $wrappers[ $wrapper_id ], upfront_get( 'settings', $wrappers[ $wrapper_id ], array() ) );

		}

		return $wrappers;

	}


	public static function get_all_wrappers() {

		_deprecated_function( __FUNCTION__, '3.7', 'UpFrontWrappersData::get_all_wrappers()' );

		$wrappers = UpFrontWrappersData::get_all_wrappers();

		return $wrappers;

	}


	public static function get_wrapper($wrapper_id, $deprecated = null) {

		_deprecated_function( __FUNCTION__, '3.7', 'UpFrontWrappersData::get_wrapper()' );

		return UpFrontWrappersData::get_wrapper($wrapper_id);

	}


}