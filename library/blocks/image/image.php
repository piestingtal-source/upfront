<?php

class UpFrontImageBlock extends UpFrontBlockAPI {

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

		$this->id 				= 'image';
		$this->name 			= __('Bild', 'upfront');
		$this->options_class 	= 'UpFrontImageBlockOptions';	
		$this->fixed_height 	= true;	
		$this->html_tag 		= 'figure';
		$this->attributes 		= array(
										'itemscope' => '',
										'itemtype' => 'http://schema.org/ImageObject'
									);
		$this->description 	= __('Zeige ein Bild an', 'upfront');
		$this->categories 		= array('core','medien','content');		
		$this->show_content_in_grid = true;

	}

	function setup_elements() {

		$this->register_block_element(array(
			'id' => 'image',
			'name' => __('Bild', 'upfront'),
			'selector' => 'img',
			'properties' => array('background', 'borders', 'padding', 'corners', 'box-shadow', 'animation', 'sizes', 'advanced', 'transition', 'outlines', 'filter')
		));

		$this->register_block_element(array(
			'id' => 'image-link',
			'name' => __('Bild Link', 'upfront'),
			'selector' => 'a img',
			'states' => array(
				'Hover' => 'a:hover img',
				'Clicked' => 'a:active img'
			)
		));

	}

	public static function dynamic_css($block_id, $block = false) {

		if ( !$block )
			$block = UpFrontBlocksData::get_block($block_id);

		if ( !$position = parent::get_setting($block, 'image-position') )
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
			#block-' . $block['id'] . ' img {
				margin: auto;
			    position: absolute;  
			    ' . upfront_get($position, $position_properties) . '
			}
		';

		return $css;

	}

	function content($block) {

		//Display image if there is one
		if ( $image_src = parent::get_setting($block, 'image') ) {

			$url = parent::get_setting($block, 'link-url');
			$alt = parent::get_setting($block, 'image-alt');
			$title = parent::get_setting($block, 'image-title');
			$target = parent::get_setting($block, 'link-target', false) ? $target = 'target="_blank"' : '';
			$rel = parent::get_setting($block, 'link-rel', false) ? $rel = 'noreferrer' : '';

			if ( parent::get_setting($block, 'resize-image', true) ) {

				$block_width = UpFrontBlocksData::get_block_width($block);
				$block_height = UpFrontBlocksData::get_block_height($block);

				$image_url = upfront_resize_image($image_src, $block_width, $block_height);

			} else {

				$image_url = $image_src;

			}

			if ( $image_src = parent::get_setting($block, 'link-image', false) ) {

				echo '<a href="' . $url . '" rel="' . $rel . '" class="image" '.$target.'><img src="' . upfront_format_url_ssl($image_url) . '" alt="' . $alt . '" title="' . $title . '" itemprop="contentURL"/></a>';

			} else {

				echo '<img src="' . upfront_format_url_ssl($image_url) . '" alt="' . $alt . '" title="' . $title . '" itemprop="contentURL"/>';

			}

		} else {

			echo '<div style="margin: 5px;" class="alert alert-yellow"><p>' . __('Du hast noch kein Bild hinzugefügt. Bitte lade ein Bild hoch und wende es an.', 'upfront') . '</p></div>';
		}

		/* Output position styling for Grid mode */
			if ( upfront_get('ve-live-content-query', $block) && upfront_post('mode') == 'grid' ) {
				echo '<style type="text/css">';
					echo self::dynamic_css(false, $block);
				echo '</style>';
			}
	}

}


class UpFrontImageBlockOptions extends UpFrontBlockOptionsAPI {


	public $tabs;
	public $inputs;


	function __construct($block_type_object){

		parent::__construct($block_type_object);
		
		$this->tabs = array(
			'general' => 'Bildoptionen'
		);

		$this->inputs = array(
			'general' => array(

				'image-heading' => array(
					'name' => 'image-heading',
					'type' => 'heading',
					'label' => __('Füge ein Bild hinzu', 'upfront')
				),

				'image' => array(
					'type' => 'image',
					'name' => 'image',
					'label' => __('Bild', 'upfront'),
					'default' => null
				),

				'resize-image' => array(
					'name' => 'resize-image',
					'label' => __('Bildgröße automatisch ändern', 'upfront'),
					'type' => 'checkbox',
					'tooltip' => __('Wenn Du möchtest, dass UpFront das Bild automatisch skaliert und auf die Blockabmessungen zuschneidet, lasse dies aktiviert. <br /><br /> <em><strong>Wichtig:</strong> Damit das Bild in der Größe geändert werden kann und beschnitten muss es <strong>vom Computer</strong> hochgeladen werden. <strong>NICHT von URL</strong>.</em>', 'upfront'),
					'default' => true
				),

				'image-title' => array(
					'name' => 'image-title',
					'label' => __('Bildtitel', 'upfront'),					
					'type' => 'text',
					'tooltip' => __('Dies wird als "Titel"-Attribut für das Bild verwendet. Das title-Attribut ist für SEO (Suchmaschinenoptimierung) von Vorteil und ermöglicht Deinen Besuchern, die Maus über das Bild zu bewegen und darüber zu lesen.', 'upfront'),
				),

				'image-alt' => array(
					'name' => 'image-alt',
					'label' => __('Alternativer Bildtext', 'upfront' ),					
					'type' => 'text',
					'tooltip' => __('Dies wird als "alt"-Attribut für das Bild verwendet. Das alt-Attribut ist <em>äußerst</em> vorteilhaft für SEO (Suchmaschinenoptimierung) und für die allgemeine Zugänglichkeit.', 'upfront'),
				),

				'link-heading' => array(
					'name' => 'link-heading',
					'type' => 'heading',
					'label' => __('Bildlink', 'upfront')
				),

				'link-image' => array(
					'name' => 'link-image',
					'label' => __('Bild verlinken?', 'upfront'),
					'type' => 'checkbox',
					'tooltip' => __('Wenn Du das Bild mit einer URL verknüpfen möchtest, aktiviere diese Einstellung. Muss zuerst http: // hinzufügen', 'upfront'),
					'default' => false,
					'toggle' => array(
						'true' => array(
							'show' => array(
								'#input-link-url',
								'#input-link-target',
								'#input-link-rel'
							)
						),
						'false' => array(
							'hide' => array(
								'#input-link-url',
								'#input-link-target',
								'#input-link-rel'
							)
						)
					)
				),

				'link-url' => array(
					'name' => 'link-url',
					'label' => __('Bildlink URL?', 'upfront'),
					'type' => 'text',
					'tooltip' => __('Lege die URL für das Bild fest, zu dem ein Link erstellt werden soll', 'upfront')
				),

				'link-target' => array(
					'name' => 'link-target',
					'label' => __('In einem neuen Fenster öffnen?', 'upfront'),
					'type' => 'checkbox',
					'tooltip' => __('Wenn Du den Link in einem neuen Fenster öffnen möchtest, aktiviere diese Option', 'upfront'),
					'default' => false,
				),

				'link-rel' => array(
					'name' => 'link-rel',
					'label' => 'Rel',
					'type' => 'text',
					'tooltip' => 'Hier kannst Du einen Wert für das rel-Attribut hinzufügen. Beispielwerte: Noreferrer, Noopener, Nofollow, Lightbox',
					'default' => 'noreferrer',
				),

				'position-heading' => array(
					'name' => 'position-heading',
					'type' => 'heading',
					'label' => __('Positioniere das Bild', 'upfront')
				),

				'image-position' => array(
					'name' => 'image-position',
					'label' => __('Positioniere das Bild im Behälter', 'upfront'),
					'type' => 'select',
					'tooltip' => __('Du kannst dieses Bild in Bezug auf den Block mithilfe der angegebenen Positionen positionieren', 'upfront'),
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