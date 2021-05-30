<?php

class UpFrontContactForm7Block extends UpFrontBlockAPI {

	public $id;
	public $name;
	public $options_class;
	public $description;
	public $categories;


	function __construct(){

		$this->id = 'contact-form-7';	
		$this->name = 'Contact Form 7';		
		$this->options_class = 'UpFrontContactForm7BlockOptions';
		$this->description = __('Zeige Contact Form 7 Formular', 'upfront');
		$this->categories = array( 'formular' );

	}

	public function init() {

		if(!class_exists('WPCF7'))
			return false;
	}
	

	function setup_elements() {

		$this->register_block_element(array(
			'id' => 'wpcf7',
			'name' => __('Formularcontainer', 'upfront'),
			'selector' => '.wpcf7',
		));

		$this->register_block_element(array(
			'id' => 'form-paragraph',
			'name' => __('Formular Absatz', 'upfront'),
			'selector' => '.wpcf7 form p',
		));

		$this->register_block_element(array(
			'id' => 'form-h1',
			'name' => __('Formular H1', 'upfront'),
			'selector' => '.wpcf7 form h1',
		));

		$this->register_block_element(array(
			'id' => 'form-h2',
			'name' => __('Formular H2', 'upfront'),
			'selector' => '.wpcf7 form h2',
		));

		$this->register_block_element(array(
			'id' => 'form-h3',
			'name' => __('Formular H3', 'upfront'),
			'selector' => '.wpcf7 form h3',
		));

		$this->register_block_element(array(
			'id' => 'form-h4',
			'name' => __('Formular H4', 'upfront'),
			'selector' => '.wpcf7 form h4',
		));

		$this->register_block_element(array(
			'id' => 'form-h5',
			'name' => __('Formular H5', 'upfront'),
			'selector' => '.wpcf7 form h5',
		));

		$this->register_block_element(array(
			'id' => 'form-h6',
			'name' => __('Formular H6', 'upfront'),
			'selector' => '.wpcf7 form h6',
		));

		$this->register_block_element(array(
			'id' => 'form-new-line',
			'name' => __('Formular neue Zeile', 'upfront'),
			'selector' => '.wpcf7 form br',
		));

		$this->register_block_element(array(
			'id' => 'form-label',
			'name' => __('Formularetikett', 'upfront'),
			'selector' => '.wpcf7 form label',
		));

		$this->register_block_element(array(
			'id' => 'form-span',
			'name' => __('Formularspanne', 'upfront'),
			'selector' => '.wpcf7 form span',
		));

		$this->register_block_element(array(
			'id' => 'form-input',
			'name' => __('Formulareingabe', 'upfront'),
			'selector' => '.wpcf7 form input',
		));

		$this->register_block_element(array(
			'id' => 'form-select',
			'name' => __('Formular Auswahl', 'upfront'),
			'selector' => '.wpcf7 form select',
		));

		$this->register_block_element(array(
			'id' => 'form-textarea',
			'name' => __('Formular Textbereich', 'upfront'),
			'selector' => '.wpcf7 form textarea',
		));

		$this->register_block_element(array(
			'id' => 'form-submit',
			'name' => __('Formular Senden', 'upfront'),
			'selector' => '.wpcf7 form input[type="submit"]',
		));
	}


	public static function dynamic_css($block_id, $block = false) {

	}


	public static function dynamic_js($block_id, $block = false) {

	}

	public function content($block) {

		$form_id = parent::get_setting($block, 'form-id', '');		
		echo do_shortcode('[contact-form-7 id="'.$form_id.'" title="'.$this->get_form_title($form_id).'"]');
	}

	public static function enqueue_action($block_id, $block = false) {

	}


	function get_form_title($form_id){

		$args = array('post_type' => 'wpcf7_contact_form', 'posts_per_page' => -1, );

		return get_post($form_id, OBJECT, 'raw')->post_title;
	}

}


class UpFrontContactForm7BlockOptions extends UpFrontBlockOptionsAPI {

	public $tabs;	
	public $sets;
	public $inputs;

	function __construct($block_type_object){

		parent::__construct($block_type_object);

		$this->tabs = array(
			'general' => 'Allgemeines'
		);

		$this->sets = array(

		);

		$this->inputs = array(
			'general' => array(
				'form-id' => array(
					'type' => 'select',
					'name' => 'form-id',
					'label' => __('Formular auswählen', 'upfront'),
					'options' => 'get_forms()',
					'tooltip' => '',
				),
			)
		);
	}

	public function modify_arguments($args = false) {
	}


	function get_forms() {

		$args = array('post_type' => 'wpcf7_contact_form', 'posts_per_page' => -1);
		$forms = array(
			'0' => __('Wähle ein Formular aus', 'upfront')
		);

		if( $data = get_posts($args)){

			foreach($data as $key){
				$forms[$key->ID] = $key->post_title;
			}

		}

		return $forms;
	}


}