<?php

class UpFrontCustomCodeBlock extends UpFrontBlockAPI {


	public $id;
	public $name;
	public $options_class;
	public $description;
	public $categories;
	public $inline_editable;


	function __construct(){

		$this->id 				= 'custom-code';	
		$this->name 			= __('Benutzerdefinierter Code', 'upfront');
		$this->options_class 	= 'UpFrontCustomCodeBlockOptions';
		$this->description 		= __('Füge in diesen Block benutzerdefinierte HTML-, PHP- oder sogar WordPress-Shortcodes ein.', 'upfront');
		$this->categories 		= array('core','code','content');
		$this->inline_editable 	= array('block-title', 'block-subtitle', 'content');

	}


	function content($block) {

		$content = parent::get_setting($block, 'content');

		if ( $content != null )

			echo '<div class="custom-code-content content">'.upfront_parse_php(do_shortcode(stripslashes($content))).'</div>';			

		else
			echo '<p class="content">' . __('Es ist kein benutzerdefinierter Code zum angzeigen vorhanden.', 'upfront') .'</p>';

	}


	public function setup_elements() {

		$this->register_block_element(array(
			'id' => 'content',			
			'name' => __('Inhalt', 'upfront'),
			'selector' => '.custom-code-content p',
		));

		$this->register_block_element(array(
			'id' => 'content-h1',
			'name' => __('Inhalt H1', 'upfront'),
			'selector' => '.custom-code-content h1',
		));

		$this->register_block_element(array(
			'id' => 'content-h2',
			'name' => __('Inhalt H2', 'upfront'),
			'selector' => '.custom-code-content h2',
		));

		$this->register_block_element(array(
			'id' => 'content-h3',
			'name' => __('Inhalt H3', 'upfront'),
			'selector' => '.custom-code-content h3',
		));

		$this->register_block_element(array(
			'id' => 'content-h4',
			'name' => __('Inhalt H4', 'upfront'),
			'selector' => '.custom-code-content h4',
		));

		$this->register_block_element(array(
			'id' => 'content-h5',
			'name' => __('Inhalt H5', 'upfront'),
			'selector' => '.custom-code-content h5',
		));

		$this->register_block_element(array(
			'id' => 'content-h6',
			'name' => __('Inhalt H6', 'upfront'),
			'selector' => '.custom-code-content h6',
		));

		$this->register_block_element(array(
			'id' => 'content-p',
			'name' => __('Inhalt p', 'upfront'),
			'selector' => '.custom-code-content span',
		));

		$this->register_block_element(array(
			'id' => 'content-a',
			'name' => __('Inhalt a', 'upfront'),
			'selector' => '.custom-code-content a',
		));

		$this->register_block_element(array(
			'id' => 'content-ul',
			'name' => __('Inhalt ul', 'upfront'),
			'selector' => '.custom-code-content ul',
		));

		$this->register_block_element(array(
			'id' => 'content-ul-li',
			'name' => __('Inhalt ul li', 'upfront'),
			'selector' => '.custom-code-content ul li',
		));
	}

}


class UpFrontCustomCodeBlockOptions extends UpFrontBlockOptionsAPI {

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
					'type' 		=> 'code',
					'mode' 		=> 'html',
					'name' 		=> __('Inhalt', 'upfront'),
					'label' 	=> __('Inhalt', 'upfront'),
					'default' 	=> null,
					'tooltip' => __('Schreibe hier Deinen benutzerdefinierten Code. Um die PHP-Ausführung zu aktivieren, füge bitte define(\'UPFRONT_DISABLE_PHP_PARSING\', false); zu Deiner wp-config.php hinzu', 'upfront')
				),
			),
		);
	}


	public function modify_arguments( $args = false ) {

		if ( defined('UPFRONT_DISABLE_PHP_PARSING') && UPFRONT_DISABLE_PHP_PARSING === true ){

			$this->tab_notices['content'] = __('Das PHP-Parsing ist derzeit deaktiviert. Um die PHP-Ausführung zu aktivieren, füge bitte Folgendeszu Deiner wp-config.php hinzu: <br><pre>define(\'UPFRONT_DISABLE_PHP_PARSING\', false);</pre><br>', 'upfront');

		}

	}

}