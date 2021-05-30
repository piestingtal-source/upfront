<?php

class UpFrontSiteLogoBlock extends UpFrontBlockAPI {

	public $id;
	public $name;
	public $options_class;
	public $attributes;
	public $description;
	public $categories;

	protected $show_content_in_grid;


	function __construct(){

		$this->id 				= 'site-logo';
		$this->name 			= __('Webseiten-Logo', 'upfront');
		$this->options_class 	= 'UpFrontSiteLogoBlockOptions';			
		$this->attributes 		= array(
										'itemscope' => '',
										'itemtype' => 'https://schema.org/ImageObject'
									);
		$this->description 	= __('Benutzerdefiniertes Webseiten-Logo anzeigen', 'upfront');
		$this->categories 		= array('core','medien');

		$this->show_content_in_grid = false;

	}


	function setup_elements() {

		$this->register_block_element(array(
			'id' => 'site-logo',
			'name' => __('Webseiten-Logo', 'upfront'),
			'selector' => 'img.site-logo',			
		));

		$this->register_block_element(array(
			'id' => 'site-logo-img',
			'name' => __('Webseiten-Logobild', 'upfront'),
			'selector' => 'img.site-logo',		
			'states' => array(
				'Shrinked' => 'img.site-logo.is_shrinked',
			)
		));


	}

	public static function dynamic_css($block_id, $block = false) {

	}

	public static function dynamic_js($block_id, $block = false) {

	}

	function content($block) {

		$blog_id = (is_multisite()) ? get_current_blog_id(): 0;
		$custom_logo_id = get_theme_mod( 'custom_logo' );
		$site_image = wp_get_attachment_image_src( $custom_logo_id , 'full' );

		echo '<a href="' . home_url() . '" class="site-logo-link"><img class="site-logo" src="'.$site_image[0].'" alt="' . get_bloginfo('name') . '" /></a>';

	}

}


class UpFrontSiteLogoBlockOptions extends UpFrontBlockOptionsAPI {

	public $tabs;
	public $inputs;

	function __construct($block_type_object){

		parent::__construct($block_type_object);

		$this->tabs = array(
			'site-logo-content' => 'Content'
		);

		$this->inputs = array(
		);
	}

	function modify_arguments($args = false) {

		$this->tab_notices['site-logo-content'] = sprintf( __('Um das benutzerdefinierte Webseiten-Logo festzulegen, gehe zu <a href="%s" target="_blank">"Darstellung > Anpassen > Webseiten-Identität"</a>', 'upfront'), admin_url('customize.php') );

	}

}