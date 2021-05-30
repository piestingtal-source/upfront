<?php

class UpFrontSearchBlock extends UpFrontBlockAPI {


	public $id;
	public $name;
	public $fixed_height;
	public $description;
	public $options_class;
	public $categories;


	function __construct(){

		$this->id = 'search';
		$this->name = __('Suche', 'upfront');
		$this->fixed_height = false;
		$this->description = __('Dadurch wird das Standardsuchformular ausgegeben', 'upfront');
		$this->options_class = 'UpFrontSearchBlockOptions';
		$this->categories 	= array('core','content', 'formular');		

	}

	function setup_elements() {

		$this->register_block_element(array(
			'id' => 'search_wrap',
			'name' => __('Suchformular', 'upfront'),
			'selector' => '.search-form'
		));

		$this->register_block_element(array(
			'id' => 'search_input',
			'name' => __('Sucheingabe', 'upfront'),
			'selector' => '.search-form input.field'
		));

		$this->register_block_element(array(
			'id' => 'search_button',
			'name' => __('Suchschaltfläche', 'upfront'),
			'selector' => '.search-form .submit'
		));

	}


	function content($block) {

		$swp_engine = $this->get_setting( $block, 'swp-engine' );

		if ( $swp_engine && function_exists('SWP') ) {

			$search_query = isset( $_REQUEST[ 'swpquery_' . $swp_engine ] ) ? sanitize_text_field( $_REQUEST[ 'swpquery_' . $swp_engine ] ) : '';
			$action = get_permalink();
			$input_name = 'swpquery_' . $swp_engine;

		} else {
			$search_query = get_search_query();
			$action = home_url( '/' );
			$input_name = 's';

		}

		$button_hidden_class = parent::get_setting( $block, 'show-button', true ) ? 'search-button-visible' : 'search-button-hidden';

		echo '<form method="get" id="searchform-' . $block['id'] . '" class="search-form ' . $button_hidden_class . '" action="' . esc_html( $action ) . '">' . "\n";

			if ( parent::get_setting( $block, 'show-button', true ) ) {
				echo '<input type="submit" class="submit" name="submit" id="searchsubmit-' . $block['id'] . '" value="' . esc_attr( parent::get_setting( $block, 'search-button', __('Suche', 'upfront') ) ) . '" />' . "\n";
			}

			printf('<div><input id="search-' . $block['id'] . '" class="field" type="text" name="%1$s" value="%2$s" placeholder="%3$s" /></div>' . "\n",
				$input_name,
				$search_query ? esc_attr($search_query) : '',
				esc_attr(parent::get_setting($block, 'search-placeholder', __('Suchbegriff eingeben und Eingabetaste drücken.', 'upfront') ) )
			);

		echo '</form>' . "\n";

	}

}


class UpFrontSearchBlockOptions extends UpFrontBlockOptionsAPI {

	public $tabs;
	public $inputs;


	function __construct($block_type_object){

		parent::__construct($block_type_object);

		$this->tabs = array(
			'general' => 'Suche-Einstellungen'
		);

		$this->inputs = array(
			'general' => array(
				'search-placeholder' => array(
					'name' => 'search-placeholder',
					'label' => __('Platzhalter für Eingabetext', 'upfront'),
					'type' => 'text',
					'tooltip' => __('Der Platzhalter ist Text, der in der Sucheingabe angezeigt und sofort entfernt wird, nachdem Du mit der Eingabe der Sucheingabe begonnen hast.', 'upfront'),
					'default' => __('Suchbegriff eingeben und Eingabetaste drücken.', 'upfront')
				),

				'show-button' => array(
					'name'    => 'show-button',
					'label'   => __('Suchschaltfläche anzeigen', 'upfront'),
					'type'    => 'checkbox',
					'default' => true,
					'toggle' => array(
						'true' => array(
							'show' => '#input-search-button'
						),
						'false' => array(
							'hide' => '#input-search-button'
						)
					)
				),

				'search-button' => array(
					'name' => 'search-button',
					'label' => __('Schaltflächentext', 'upfront'),
					'type' => 'text',
					'tooltip' => 'Dadurch wird der Text der Schaltfläche "Suchen" aktualisiert.',
					'default' => 'Suche'
				)
			)
		);
	}

	public function modify_arguments( $args = false ) {

		if ( class_exists( 'SWP_Query' ) ) {

			$this->inputs['general']['swp-engine'] = array(
					'type'    => 'select',
					'name'    => 'swp-engine',
					'label'   => __('SearchWP Engine', 'upfront'),
					'options' => 'get_swp_engines()',
					'tooltip' => __('Wenn Du die Ergebnisse einer ergänzten SearchWP-Engine anzeigen möchtest, wähle die Engine hier aus.', 'upfront'),
					'default' => ''
			);

		}

	}

	function get_swp_engines() {

		$options = array( __('&ndash; Wähle eine Engine aus &ndash;', 'upfront') );

		if ( ! function_exists( 'SWP' ) ) {
			return $options;
		}

		$searcbtp = SWP();

		if ( ! is_array( $searcbtp->settings['engines'] ) ) {
			return $options;
		}

		foreach ( $searcbtp->settings['engines'] as $engine => $engine_settings ) {

			if ( empty( $engine_settings['searcbtp_engine_label'] ) ) {
				continue;
			}

			$options[ $engine ] = $engine_settings['searcbtp_engine_label'];

		}

		return $options;

	}


}