<?php

class UpFrontVideoBlock extends UpFrontBlockAPI {

	public $id;
	public $name;
	public $options_class;
	public $fixed_height;
	public $html_tag;
	public $attributes;
	public $description;
	public $categories;	
	protected $show_content_in_grid;


	function __construct(){

		$this->id = 'video';	
		$this->name	= 'Video';		
		$this->options_class = 'UpFrontVideoBlockOptions';	
		$this->fixed_height = true;	
		$this->html_tag = 'div';
		$this->attributes = array(
								'itemscope' => '',
								'itemtype' => 'http://schema.org/VideoObject'
							);
		$this->description = __('Ein Video anzeigen', 'upfront');
		$this->categories = array('core','medien');

		$this->show_content_in_grid = false;
	}


	function setup_elements() {

		$this->register_block_element(array(
			'id' => 'video',
			'name' => 'Video',
			'selector' => 'video',
			'properties' => array('background', 'borders', 'padding', 'corners', 'box-shadow', 'animation', 'sizes', 'advanced', 'transition', 'outlines')
		));

		$this->register_block_element(array(
			'id' => 'video-container',
			'name' => 'Video container',
			'selector' => 'div.video'
		));


	}

	public static function dynamic_css($block_id, $block = false) {

		if ( !$block )
			$block = UpFrontBlocksData::get_block($block_id);

		if ( !$position = parent::get_setting($block, 'video-position') )
			return;

		$position_properties = array(
			'top_left' => 'left: 0; top: 0;',
			'top_center' => 'left: 0; top: 0; right: 0;',
			'top_right' => 'top: 0; right: 0;',

			'center_center' => 'bottom: 0; left: 0; top: 0; right: 0;',
			'center_left' => 'bottom: 0; left: 0; top: 0;',
			'center_right' => 'bottom: 0; top: 0; right: 0;',

			'bottom_left' => 'bottom: 0; left: 0;',
			'bottom_center' => 'bottom: 0; left: 0; right: 0;',
			'bottom_right' => 'bottom: 0;right: 0;'
		);

		$position_fragments = explode('_', $position);
		$position_horizontal = $position_fragments[1];

		$css = '
			#block-' . $block['id'] . ' .block-content { position: relative; text-align: ' . $position_horizontal . '; }
			#block-' . $block['id'] . ' div.video video {
				margin: auto;
			    position: absolute;  
			    ' . upfront_get($position, $position_properties) . '
			}
		';

		return $css;

	}

	public static function dynamic_js($block_id, $block = false) {

		if ( !$block )
			$block = UpFrontBlocksData::get_block($block_id);

		$js = '';

		if(parent::get_setting($block, 'width-dynamic') || parent::get_setting($block, 'height-dynamic')){

			$js 		= "jQuery(document).ready(function() {";
			$js_resize 	= "jQuery( window ).on( 'orientationchange resize load', function( event ) {";
			$js_load 	= "";

			if(parent::get_setting($block, 'width-dynamic')){
				$js_resize 	.= "jQuery( 'div#block-". $block_id ." video' ).attr('width', jQuery( 'div#block-". $block_id ."' ).width());";
				$js_load  	.= "if(window.innerWidth < ".$block['settings']['width']."){
					jQuery( 'div#block-". $block_id ." video' ).attr('width', window.innerWidth);
				}";
			}

			if(parent::get_setting($block, 'height-dynamic')){
				$js_resize .= "jQuery( 'div#block-". $block_id ." video' ).attr('height', jQuery( 'div#block-". $block_id ."' ).height());";
				$js_load  	.= "if(window.innerHeight < ".$block['settings']['height']."){
					jQuery( 'div#block-". $block_id ." video' ).attr('height', window.innerHeight);
				}";
			}
			$js_resize .= "});";

			$js .= $js_resize;						
			$js .= $js_load;						
			$js .= "});";
		}

		return $js;

	}

	function content($block) {

		//Video anzeigen, falls vorhanden
		if (parent::get_setting($block, 'video-mp4')||parent::get_setting($block, 'video-ogg')||parent::get_setting($block, 'video-webm') || parent::get_setting( $block, 'video-custom-url')  ) {

			$video_mp4 	  = parent::get_setting($block, 'video-mp4');
			$video_ogg 	  = parent::get_setting($block, 'video-ogg');
			$video_webm   = parent::get_setting($block, 'video-webm');
			$video_custom = parent::get_setting($block, 'video-custom-url');

			$videoHTML = '<div class="video"><video playsinline ';

			if(parent::get_setting($block, 'autoplay'))
				$videoHTML .= ' autoplay';

			if(parent::get_setting($block, 'loop'))
				$videoHTML .= ' loop';

			switch (parent::get_setting($block, 'preload')) {

				case 'none':
					$videoHTML .= ' preload="none"';
					break;

				case 'metadata':
					$videoHTML .= ' preload="metadata"';
					break;

				case 'auto':
					$videoHTML .= ' preload="auto"';
					break;

				default:					
					break;
			}

			if(parent::get_setting($block, 'controls'))
				$videoHTML .= ' controls';

			if(parent::get_setting($block, 'muted'))
				$videoHTML .= ' muted';

			if(parent::get_setting($block, 'poster'))
				$videoHTML .= ' poster="' . upfront_format_url_ssl(parent::get_setting($block, 'poster')) . '"';

			if(parent::get_setting($block, 'width'))
				$videoHTML .= ' width="'.parent::get_setting($block, 'width').'"';

			if(parent::get_setting($block, 'height'))
				$videoHTML .= ' height="'.parent::get_setting($block, 'height').'"';


			$videoHTML .= '>';

			if(parent::get_setting($block, 'video-mp4')) {
				$videoHTML .= '<source src="' . upfront_format_url_ssl($video_mp4) . '" type="video/mp4">';
			}
			if(parent::get_setting($block, 'video-ogg')) {
				$videoHTML .= '<source src="' . upfront_format_url_ssl($video_ogg) . '" type="video/ogg">';
			}
			if(parent::get_setting($block, 'video-webm')) {
				$videoHTML .= '<source src="' . upfront_format_url_ssl($video_webm) . '" type="video/webm">';
			}
			if(parent::get_setting($block, 'video-custom-url')) {
				$videoHTML .= '<source src="' . padma_format_url_ssl($video_custom) . '">';
			}
			$videoHTML .= 'Dein Browser unterstützt das Video-Tag nicht.';
			$videoHTML .= '</video></div>';

			echo $videoHTML;


		} else {

			echo '<div style="margin: 5px;" class="alert alert-yellow"><p>' . __('Du hast noch kein Video hinzugefügt. Bitte lade ein Video hoch und wende es an.', 'upfront') . '</p></div>';
		}

		/* Output position styling for Grid mode */
			if ( upfront_get('ve-live-content-query', $block) && upfront_post('mode') == 'grid' ) {
				echo '<style type="text/css">';
					echo self::dynamic_css(false, $block);
				echo '</style>';
			}


	}

}


class UpFrontVideoBlockOptions extends UpFrontBlockOptionsAPI {

	public $tabs;
	public $inputs;


	function __construct($block_type_object){

		parent::__construct($block_type_object);

		$this->tabs = array(
			'general' => 'Allgemeines'
		);

		$this->inputs = array(
			'general' => array(

				'video-heading' => array(
					'name' => 'video-heading',
					'type' => 'heading',
					'label' => __('Füge ein Video hinzu', 'upfront')
				),

				'video-mp4' => array(
					'type' => 'video',
					'name' => 'video-mp4',
					'label' => 'Video MP4',
					'default' => null
				),

				'video-ogg' => array(
					'type' => 'video',
					'name' => 'video-ogg',
					'label' => 'Video OGG',
					'default' => null
				),

				'video-webm' => array(
					'type' => 'video',
					'name' => 'video-webm',
					'label' => 'Video WebM',
					'default' => null
				),

				'video-custom-url' => array(
					'type' => 'text',
					'name' => 'video-custom-url',
					'label' => 'Benutzerdefinierte Video-URL',
					'default' => null
				),

				'autoplay' => array(
					'name' => 'autoplay',
					'label' => 'Autoplay',
					'type' => 'checkbox',
					'default' => false,
					'tooltip' => __('Gibt an, dass das Video abgespielt wird, sobald es fertig ist', 'upfront')
				),

				'loop' => array(
					'name' => 'loop',
					'label' => __('Loop', 'upfront'),
					'type' => 'checkbox',
					'default' => false,
					'tooltip' => __('Gibt an, dass das Video jedes Mal neu gestartet wird, wenn es fertig ist', 'upfront')
				),

				'preload' => array(
					'name' => 'preload',
					'label' => __('Vorladen', 'upfront'),
					'type' => 'select',
					'default' => 'none',
					'options' => array(
						''		=> 'none',
						'auto'		=> 'Auto',
						'metadata'	=> 'Metadata',
					),
					'tooltip' => __('Gibt an, ob und wie der Autor der Meinung ist, dass das Video beim Laden der Seite geladen werden soll', 'upfront')
				),

				'controls' => array(
					'name' => 'controls',
					'label' => __('Kontrollen', 'upfront'),
					'type' => 'checkbox',
					'default' => false,
					'tooltip' => __('Gibt an, dass Videosteuerelemente angezeigt werden sollen (z.B. eine Wiedergabe-/Pause-Taste usw.).', 'upfront')
				),

				'muted' => array(
					'name' => 'muted',
					'label' => __('Stumm geschaltet', 'upfront'),
					'type' => 'checkbox',
					'default' => false,
					'tooltip' => __('Gibt an, dass die Audioausgabe des Videos stummgeschaltet werden soll', 'upfront')
				),

				'poster' => array(
					'name' => 'poster',
					'label' => __('Poster-URL', 'upfront'),
					'type' => 'image',
					'tooltip' => __('Gibt ein Bild an, das angezeigt werden soll, während das Video heruntergeladen wird oder bis der Benutzer die Wiedergabetaste drückt', 'upfront')
				),

				'width' => array(
					'name' => 'width',
					'label' => __('Breite', 'upfront'),
					'type' => 'integer',
					'tooltip' => __('Legt die Breite des Videoplayers fest', 'upfront')
				),

				'width-dynamic' => array(
					'name' => 'width-dynamic',
					'label' => __('Dynamische Breite zulassen', 'upfront'),
					'type' => 'checkbox',
					'default' => true,
					'tooltip' => __('Ändere die Videobreite automatisch, wenn die Fenstergröße geändert wird', 'upfront')
				),

				'height' => array(
					'name' => 'height',
					'label' => __('Höhe', 'upfront'),
					'type' => 'integer',
					'tooltip' => __('Legt die Höhe des Videoplayers fest', 'upfront')
				),

				'height-dynamic' => array(
					'name' => 'height-dynamic',
					'label' => __('Dynamische Höhe zulassen', 'upfront'),
					'type' => 'checkbox',
					'default' => true,
					'tooltip' => __('Ändere die Videohöhe automatisch, wenn die Fenstergröße ändern', 'upfront')
				),

				'position-heading' => array(
					'name' => 'position-heading',
					'type' => 'heading',
					'label' => __('Video Position', 'upfront')
				),

				'video-position' => array(
					'name' => 'video-position',
					'label' => __('Positioniere das Video im Behälter', 'upfront'),
					'type' => 'select',
					'tooltip' => __('Du kannst dieses Video anhand der angegebenen Positionen in Bezug auf den Block positionieren', 'upfront'),
					'default' => 'none',
					'options' => array(
						'' => 'None',
						'top_left' => __('Oben links', 'upfront'),
						'top_center' => __('Oben Mitte', 'upfront'),
						'top_right' => __('Oben rechts', 'upfront'),
						'center_left' => __('Mitte links', 'upfront'),
						'center_center' => __('Mittig zentriert', 'upfront'),
						'center_right' => __('Mitte rechts', 'upfront'),
						'bottom_left' => __('Unten links', 'upfront'),
						'bottom_center' => __('Unten Mitte', 'upfront'),
						'bottom_right' => __('Unten rechts', 'upfront')
					)
				)

			)
		);
	}

}