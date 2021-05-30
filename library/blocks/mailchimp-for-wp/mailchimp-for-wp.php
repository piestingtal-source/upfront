<?php

class UpFrontMailchimpForWPBlock extends UpFrontBlockAPI {

	public $id;
	public $name;
	public $options_class;
	public $description;
	public $categories;


	function __construct(){

		$this->id = 'mailchimp-for-wp';
		$this->name	= __('Mailchimp', 'upfront');
		$this->options_class = 'UpFrontMailchimpForWPBlockOptions';
		$this->description = __('Mailchimp anzeigen', 'upfront');
		$this->categories = array('core','formular','marketing');

	}


	public function init() {

		if(!class_exists('mc4wp'))
			return false;

	}

	function setup_elements() {

		$this->register_block_element(array(			
			'id' => 'mc4wp-form',			
			'name' => __('Formular', 'upfront'),
			'description' => __('Formular', 'upfront'),
			'selector' => '.mc4wp-form'
		));

		$this->register_block_element(array(			
			'id' => 'mc4wp-form',			
			'name' => __('Formularcontainer', 'upfront'),
			'description' => __('Formularcontainer', 'upfront'),
			'selector' => '.mc4wp-form .mc4wp-form-fields'
		));

		$this->register_block_element(array(
			'id' => 'mc4wp-form-p',			
			'name' => __('Absatz', 'upfront'),
			'description' => __('Absatz', 'upfront'),
			'selector' => '.mc4wp-form .mc4wp-form-fields p'
		));

		$this->register_block_element(array(
			'id' => 'mc4wp-form-label',			
			'name' => __('Beschriftung', 'upfront'),
			'description' => __('Beschriftung', 'upfront'),
			'selector' => '.mc4wp-form .mc4wp-form-fields label'
		));

		$this->register_block_element(array(
			'id' => 'mc4wp-form-input',			
			'name' => __('Eingabe', 'upfront' ),
			'description' => __('Eingabe', 'upfront' ),
			'selector' => '.mc4wp-form .mc4wp-form-fields input'
		));

		$this->register_block_element(array(
			'id' => 'form-submit',
			'name' => __('Formular senden', 'upfront'),
			'selector' => '.mc4wp-form .mc4wp-form-fields input[type="submit"]',
		));

	}


	public static function dynamic_css($block_id, $block = false) {

	}


	public static function dynamic_js($block_id, $block = false) {

	}

	public function content($block) {

		$form_id = parent::get_setting($block, 'form-id', '');		
		echo do_shortcode('[mc4wp_form id="'.$form_id.'" title="'.$this->get_form_title($form_id).'"]');
	}

	public static function enqueue_action($block_id, $block = false) {

	}


	function get_form_title($form_id){

		$args = array('post_type' => 'mc4wp-form', 'posts_per_page' => -1, );

		return get_post($form_id, OBJECT, 'raw')->post_title;
	}

}


class UpFrontMailchimpForWPBlockOptions extends UpFrontBlockOptionsAPI {


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
					'label' => 'Select form',
					'options' => 'get_forms()',
					'tooltip' => '',
				),
			)
		);

	}

	public function modify_arguments($args = false) {
	}


	function get_forms() {

		$args = array('post_type' => 'mc4wp-form', 'posts_per_page' => -1);
		$forms = array(
			'0' => 'Select a form'
		);

		if( $data = get_posts($args)){

			foreach($data as $key){
				$forms[$key->ID] = $key->post_title;
			}

		}

		return $forms;
	}


}