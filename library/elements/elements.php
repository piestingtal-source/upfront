<?php
class UpFrontElements {


	public static function init() {

		UpFront::load(array(
			'elements/properties',
			'elements/js-properties' => 'JSProperties',
			'data/data-elements' => 'ElementsData',
			'api/api-element' => 'ElementAPI'
		));

		add_action('upfront_elements_init', array(__CLASS__, 'load_elements'));
		add_action('upfront_flush_cache', array(__CLASS__, 'flush_element_selector_cache'));

		do_action('upfront_elements_init');

	}


	public static function load_elements() {

		include_once 'default-elements.php';
		include_once 'structural-elements.php';

	}


	public static function get_element_selectors() {

		$cached_element_selectors = get_transient( 'pu_element_selectors_template_' . UpFrontOption::$current_skin );

		if ( ! $cached_element_selectors ) {

			$cached_element_selectors = array();

			UpFrontElementAPI::register_elements_hook();
			UpFrontElementAPI::register_elements_instances_hook();

			foreach ( UpFrontElementAPI::get_all_elements() as $element ) {

				$cached_element_selectors[ $element['id'] ] = $element['selector'];

				if ( isset( $element['states'] ) ) {
					foreach ( $element['states'] as $element_state ) {
						$cached_element_selectors[ $element['id'] . '||state||' . $element_state['id'] ] = $element_state['selector'];
					}
				}

				if ( isset( $element['instances'] ) ) {
					foreach ( $element['instances'] as $element_instance ) {
						$cached_element_selectors[ $element['id'] . '||instance||' . $element_instance['id'] ] = $element_instance['selector'];
					}
				}

			}

			set_transient( 'pu_element_selectors_template_' . UpFrontOption::$current_skin, $cached_element_selectors );

		}

		return $cached_element_selectors;

	}


	public static function flush_element_selector_cache() {

		return delete_transient( 'pu_element_selectors_template_' . UpFrontOption::$current_skin );

	}


}