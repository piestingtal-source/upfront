<?php

class UpFrontGravityFormsBlock extends UpFrontBlockAPI {


	public $id;
	public $name;
	public $options_class;
	public $categories;


	function __construct(){

		$this->id = 'gravity-forms';	
		$this->name = 'Gravity Forms';	
		$this->options_class = 'UpFrontGravityFormsBlockOptions';
		$this->categories 	= array('formular');		
	}			


	public static function enqueue_action($block_id) {

		$block = UpFrontBlocksData::get_block($block_id);

		return gravity_form_enqueue_scripts(parent::get_setting($block, 'form-id', null), parent::get_setting($block, 'use-ajax', false));

	}


	function content($block) {

		$form_id = parent::get_setting($block, 'form-id', null);

		//If no form ID is present, display the message and stop this function.
		if ( !$form_id ) {

			echo __('<p>Es ist kein Formular zum Anzeigen vorhanden.</p>', 'upfront');

			return;

		}

		$display_title = parent::get_setting($block, 'display-title', true);
		$display_description = parent::get_setting($block, 'display-description', true);
		$force_display = true;
		$field_values = null;
		$use_ajax = parent::get_setting($block, 'use-ajax', false);

		echo RGForms::get_form($form_id, $display_title, $display_description, $force_display, null, $use_ajax);

	}


}


class UpFrontGravityFormsBlockOptions extends UpFrontBlockOptionsAPI {


	public $tabs;
	public $inputs;


	function __construct($block_type_object){

		parent::__construct($block_type_object);
		

		$this->tabs = array(
			'form-setup' => __('Formular einrichten', 'upfront')
		);

		$this->inputs = array(
			'form-setup' => array(

				'form-id' => array(
					'type' => 'select',
					'name' => 'form-id',
					'label' => __('Formular zum Anzeigen', 'upfront'),
					'default' => '',
					'tooltip' => __('Wähle aus welches Formular dieser Block anzeigen soll.', 'upfront'),
					'options' => 'get_forms()'
				),

				'display-title' => array(
					'type' => 'checkbox',
					'name' => 'display-title',
					'label' => __('Formulartitel anzeigen', 'upfront'),
					'default' => true
				),

				'display-description' => array(
					'type' => 'checkbox',
					'name' => 'display-description',
					'label' => __('Formularbeschreibung anzeigen', 'upfront'),
					'default' => true
				),

				'use-ajax' => array(
					'type' => 'checkbox',
					'name' => 'use-ajax',
					'label' => __('Benutze AJAX', 'upfront'),
					'default' => false,
					'tooltip' => __('AJAX ist eine Technologie, die eine schnellere Übermittlung Ihrer Formulare ermöglicht.', 'upfront')
				),

			)
		);
	}


	function get_forms() {

		$forms = RGFormsModel::get_forms();

		$options = array('' => __('&ndash; Wähle ein Formular aus &ndash;', 'upfront') );

		foreach ( $forms as $form ) {

			$options[$form->id] = $form->title;

		}

		return $options;

	}


}