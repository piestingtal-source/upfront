<?php

class UpFrontTextBlock extends UpFrontBlockAPI {


	public $id;
	public $name;
	public $options_class;
	public $description;
	public $categories;
	public $inline_editable;


	function __construct(){

		$this->id = 'text';
		$this->name = __('Text', 'upfront');
		$this->options_class = 'UpFrontTextBlockOptions';
		$this->description = __('Verwende den integrierten Rich-Text-Editor, um Titel, Text und mehr einzufügen!', 'upfront');
		$this->categories 	= array('core','content','typografie');		
		$this->inline_editable = array('block-title', 'block-subtitle', 'content');

	}


	function content($block) {

		$content = parent::get_setting($block, 'content');	

		echo '<div class="entry-content content">';
			if ( $content != null )
				echo do_shortcode(stripslashes($content));
			else
				echo '<p class="content">' . __('Es ist kein Inhalt vorhanden um ihn anzuzeigen.', 'upfront') . '</p>';
		echo '</div>';

	}


	function setup_elements() {

		$this->register_block_element(array(
			'id' => 'text',
			'name' => __('Text', 'upfront'),
			'selector' => '.entry-content',
			'properties' => array('fonts', 'padding'),
			'inspectable' => false
		));

		$this->register_block_element(array(
			'id' => 'strong',
			'parent' => 'text',
			'name' => __('Fettgedruckter Text', 'upfront'),
			'description' => '&lt;strong&gt;',
			'selector' => 'div.entry-content strong'
		));

		$this->register_block_element(array(
			'id' => 'emphasized',
			'parent' => 'text',
			'name' => __('Kursiver Text', 'upfront'),
			'selector' => 'div.entry-content em'
		));

		$this->register_block_element(array(
			'id' => 'paragraphs',
			'name' => __('Absätze', 'upfront'),
			'selector' => '.entry-content p'
		));

		$this->register_block_element(array(
			'id' => 'paragraphs-first',
			'parent' => 'paragraphs',
			'name' => __('Erste Absätze', 'upfront'),
			'selector' => '.entry-content p:first-of-type',
			'inspectable' => false
		));

		$this->register_block_element(array(
			'id' => 'paragraphs-last',
			'parent' => 'paragraphs',
			'name' => __('Letzte Absätze', 'upfront'),
			'selector' => '.entry-content p:last-of-type',
			'inspectable' => false
		));

		$this->register_block_element(array(
			'id' => 'hyperlinks',
			'name' => __('Hyperlinks', 'upfront'),
			'selector' => '.entry-content a',
			'properties' => array('fonts'),
			'states' => array(
				'Hover' => '.entry-content a:hover', 
				'Clicked' => '.entry-content a:active'
			)
		));

		$this->register_block_element(array(
			'id' => 'heading',
			'name' => __('Überschriften', 'upfront'),
			'description' => '&lt;H3&gt;, &lt;H2&gt;, &lt;H1&gt;',
			'selector' => '.entry-content h3, div.entry-content h2, div.entry-content h1'
		));

		$this->register_block_element(array(
			'id' => 'heading-h1',
			'parent' => 'heading',
			'name' => __('Überschrift 1', 'upfront'),
			'description' => '&lt;H1&gt;',
			'selector' => 'div.entry-content h1'
		));

		$this->register_block_element(array(
			'id' => 'heading-h2',
			'parent' => 'heading',
			'name' => __('Überschrift 2', 'upfront'),
			'description' => '&lt;H2&gt;',
			'selector' => 'div.entry-content h2'
		));

		$this->register_block_element(array(
			'id' => 'heading-h3',
			'parent' => 'heading',
			'name' => __('Überschrift 3', 'upfront'),
			'description' => '&lt;H3&gt;',
			'selector' => 'div.entry-content h3'
		));

		$this->register_block_element(array(
			'id' => 'sub-heading',
			'name' => __('Unterüberschrift', 'upfront'),
			'description' => '&lt;H4&gt;',
			'selector' => '.entry-content h4'
		));

		$this->register_block_element(array(
			'id' => 'image',
			'name' => __('Bilder', 'upfront'),
			'selector' => 'div.entry-content img'
		));

		$this->register_block_element(array(
			'id' => 'form',
			'name' => __('Formulare', 'upfront'),
			'selector' => 'div.entry-content form'
		));

		$this->register_block_element(array(
			'id' => 'buttons',
			'name' => __('Schaltflächen', 'upfront'),
			'parent' => 'form',
			'selector' => '
				.entry-content input[type="submit"],
				.entry-content input[type="button"],
				.entry-content button,
				.entry-content .button',
			'states' => array(
				'Hover' => '
					.entry-content input[type="submit"]:hover,
					.entry-content input[type="button"]:hover,
					.entry-content button:hover,
					.entry-content .button:hover',
				'Active' => '
					.entry-content input[type="submit"]:active,
					.entry-content input[type="button"]:active,
					.entry-content button:active,
					.entry-content .button:active',
			)
		));

		$this->register_block_element(array(
			'id' => 'inputs',
			'name' => __('Eingaben', 'upfront'),
			'parent' => 'form',
			'selector' => '
				.entry-content input[type="text"],
				.entry-content input[type="password"],
				.entry-content input[type="email"],
				.entry-content textarea,
				.entry-content select',
			'states' => array(
				'Focus' => '
					.entry-content input[type="text"]:focus,
					.entry-content input[type="password"]:focus,
					.entry-content input[type="email"]:focus,
					.entry-content textarea:focus'
			)
		));


	}


}


class UpFrontTextBlockOptions extends UpFrontBlockOptionsAPI {

	public $tabs;
	public $inputs;

	function __construct($block_type_object){

		parent::__construct($block_type_object);

		$this->tabs = array(
			'content' => __('Inhalt', 'upfront')
		);

		$this->inputs = array(
			'content' => array(
				'content' => array(
					'type' => 'wysiwyg',
					'name' => 'content',
					'label' => __('Inhalt', 'upfront'),
					'default' => null
				)
			)
		);
	}

}