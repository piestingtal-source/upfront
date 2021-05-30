<?php

class UpFrontLottieFilesBlock extends UpFrontBlockAPI {
	
	public $id;
	public $name;
	public $options_class;
	public $description;
	public $categories;
	
	function __construct(){

		$this->id 				= 'lottiefiles';	
		$this->name 			= 'Lottie Files';		
		$this->options_class 	= 'UpFrontLottieFilesBlockOptions';		
		$this->description 		= 'Zeigt Lottie-Animationen von https://lottiefiles.com/featured';
		$this->categories 		= array('animationen','medien');

	}

	
	public function init() {
	}
	
	public function setup_elements() {
	}


	public static function dynamic_css($block_id, $block = false) {

		if ( !$block )
			$block = UpFrontBlocksData::get_block($block_id);

		$css = 'lottie-player{width: 100%;}';

		return $css;
		
	}

	public static function dynamic_js($block_id, $block = false) {

		if ( !$block )
			$block = UpFrontBlocksData::get_block($block_id);

		/*

		debug($block);
		
		$origin = 'path';
		$loop = (isset($block['settings']['loop'])) ? $block['settings']['loop'] : 'true';

		if( isset($block['settings']['source']) && $block['settings']['source'] == 'url' ){
			
			$source = trim($block['settings']['animation-url']);		
		
		}elseif ( isset($block['settings']['source']) && $block['settings']['source'] == 'file' ) {

			$source = trim($block['settings']['animation-file']);
			
		}else{
			$source =  upfront_url() . '/library/blocks/lottiefiles/json/156-star-blast.json';
		}


		$container = 'svgContainer-' . $block['id'];

		$js = "jQuery(document).ready(function() {";		
		$js .= "var svgContainer = document.getElementById('".$container."');";
		$js .= "var animItem = bodymovin.loadAnimation({";
		$js .= "	wrapper: svgContainer,";
		$js .= "	animType: 'svg',";
		$js .= "	loop: ".$loop.",";
		$js .= "	" . $origin . ": '". $source ."'";
		$js .= "});";
		$js .= "});";

		return $js;*/
		
	}
	
	public function content($block) {

		$origin = 'path';
		
		$loop = ( isset($block['settings']['loop']) ) ? $block['settings']['loop'] : 'loop';
		if( $loop == 'yes' ){
			$loop = 'loop';
		}else{
			$loop = ' ';
		}
		
		$controls = ( isset($block['settings']['controls']) ) ? $block['settings']['controls'] : '';
		$autoplay = ( isset($block['settings']['autoplay']) ) ? $block['settings']['autoplay'] : 'autoplay';
		

		if( isset($block['settings']['source']) && $block['settings']['source'] == 'url' ){
			
			$source = trim($block['settings']['animation-url']);		
		
		}elseif ( isset($block['settings']['source']) && $block['settings']['source'] == 'file' ) {

			$source = trim($block['settings']['animation-file']);
			
		}else{
			$source =  upfront_url() . '/library/blocks/lottiefiles/json/156-star-blast.json';
		}

		$html = '<lottie-player ';
		$html .= 'src="' . $source . '" ';
		$html .= $loop . ' ';
		$html .= $controls . ' ';
		$html .= $autoplay . ' ';
        //style="width: 400px; --lottie-player-seeker-track-color: #ff3300; --lottie-player-seeker-thumb-color: #ffcc00;"                
      	$html .= '></lottie-player>';
      	echo $html;
	}
	
	public static function enqueue_action($block_id, $block = false) {
		
		if ( !$block )
			$block = UpFrontBlocksData::get_block($block_id);
				
		

		/* JS */		
		wp_enqueue_script( 'upfront-lottiefiles', upfront_url() . '/library/blocks/lottiefiles/js/lottie.min.js', [], false, true );
	}
}

class UpFrontLottieFilesBlockOptions extends UpFrontBlockOptionsAPI {
	
	public $tabs;
	public $sets;
	public $inputs;

	function __construct(){
		
		$this->tabs = array(
			'general' 			=> 'Lottie Files',
		);

		$this->sets = array();

		$this->inputs = array(
			'general' => array(

				'source' => array(
					'type' => 'select',
					'name' => 'source',
					'label' => 'Animationsquelle',
					'default' => 'url',
					'options' => array(
						'' => '',
						'file' => 'Datei',
						'url' => 'URL',
					),
					'toggle'    => array(
						'file' => array(
							'hide' => array(
								'#input-animation-url'
							),
							'show' => array(
								'#input-animation-file'
							)
						),
						'url' => array(
							'hide' => array(
								'#input-animation-file'
							),
							'show' => array(
								'#input-animation-url'
							)
						),
					)					
				),

				'animation-file' => array(
					'type' => 'json',
					'name' => 'animation-file',
					'label' => 'Animation JSON Datei',
					'default' => '',					
					'tooltip' => 'Lade die JSON-Datei hoch',
					'button-label' => __('Datei auswählen', 'upfront'),					
				),

				'animation-url' => array(
					'type' => 'text',
					'name' => 'animation-url',
					'label' => 'Animation URL',
					'default' => '',					
					'tooltip' => 'JSON-Datei-URL',
				),

				'loop' => array(
					'type' => 'select',
					'name' => 'loop',
					'label' => 'Schleife',
					'default' => 'true',
					'options' => array(
						'yes' => 'Ja',
						'no' => 'Nein',
					),				
				),

				'controls' => array(
					'type' => 'select',
					'name' => 'controls',
					'label' => 'Steuerung',
					'default' => 'false',
					'options' => array(
						'true' => 'Wahr',
						'false' => 'Falsch',
					),				
				),

				'autoplay' => array(
					'type' => 'select',
					'name' => 'autoplay',
					'label' => 'Autoplay',
					'default' => 'true',
					'options' => array(
						'true' => 'Wahr',
						'false' => 'Falsch',
					),				
				),
			),
		);
	}


	public function modify_arguments($args = false) {
		$this->tab_notices['general'] = sprintf( __('Hole Dir Animationen von <a href="%s" target="_blank">lottiefiles.com</a>', 'upfront'), 'https://lottiefiles.com/recent' );
	}
	
}