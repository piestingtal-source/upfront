<?php

class UpFrontSliderBlock extends UpFrontBlockAPI {


	public $id;
	public $name;
	public $options_class;
	public $fixed_height;
	public $description;
	public $categories;


	function __construct(){

		$this->id = 'slider';	
		$this->name = 'Slider';		
		$this->options_class = 'UpFrontSliderBlockOptions';	
		$this->fixed_height = false;
		$this->description = __('Erstelle effektive Diashows mit reaktionsschnellen Bildern.', 'upfront');
		$this->categories = array('core','content', 'medien');

	}


	public static function enqueue_action($block_id, $block) {

		$images = parent::get_setting($block, 'images', array());

		wp_enqueue_style('flexslider', upfront_url() . '/library/blocks/slider/assets/flexslider.css');

		//If there are no images or only 1 image, do not load FlexSlider JS.
		if ( count($images) <= 1 )
			return false;

		wp_enqueue_script('flexslider', upfront_url() . '/library/blocks/slider/assets/jquery.flexslider-min.js', array('jquery'));

	}


	public static function dynamic_js($block_id, $block) {

		$images = parent::get_setting($block, 'images', array());

		//If there are no images or only 1 image, do not load FlexSlider.
		if ( count($images) <= 1 )
			return false;

		return '
jQuery(window).load(function(){
	jQuery(\'#block-' . $block['id'] . ' .flexslider\').flexslider({
	   animation: "' . (parent::get_setting($block, 'animation', 'slide-horizontal') == 'fade' ? 'fade' : 'slide') . '",
	   direction: "' . (parent::get_setting($block, 'animation', 'slide-horizontal') == 'slide-vertical' ? 'vertical' : 'horizontal') . '",
	   slideshow: ' . (parent::get_setting($block, 'slideshow', true) ? 'true' : 'false') . ',
	   slideshowSpeed: ' . (parent::get_setting($block, 'animation-timeout', 6) * 1000) . ',
	   animationSpeed: ' . (parent::get_setting($block, 'animation-speed', 500)) . ', 
	   randomize: false,     
	   controlNav: ' . (parent::get_setting($block, 'show-pager-nav', true) ? 'true' : 'false') . ',
	   directionNav: ' . (parent::get_setting($block, 'show-direction-nav', true) ? 'true' : 'false') . ',
	   randomize: ' . (parent::get_setting($block, 'randomize-order', false) ? 'true' : 'false') . '
	});
});' . "\n";

	}


	function content($block) {

		$images = parent::get_setting($block, 'images', array());

		$block_width = UpFrontBlocksData::get_block_width($block);
		$block_height = UpFrontBlocksData::get_block_height($block);

		$has_images = false;

		foreach ( $images as $image ){
			if ( $image['image'] ) {
				$has_images = true;
				break;
			}
		}

		if ( !$has_images ) {

			echo '<div class="alert alert-yellow"><p>' . __('Es können keine Bilder angezeigt werden.', 'upfront') . '</p></div>';

			return;

		}

		$no_slide_class = count($images) === 1 ? ' flexslider-no-slide' : '';

		echo '<div class="flexslider' . $no_slide_class . '">';

			/* Put in viewport div for sliders that only have 1 image and don't slide */
			if ( count($images) === 1 )
				echo '<div class="flex-viewport">';

			echo '<ul class="slides">';

			  	foreach ( $images as $image ) {

			  		if ( !$image['image'] )
			  			continue;

			  		$output = array(
			  			'image' => array(
			  				'src' => parent::get_setting($block, 'crop-resize-images', true) ? upfront_resize_image($image['image'], $block_width, $block_height) : $image['image'],
			  				'alt' => upfront_fix_data_type(upfront_get('image-alt', $image)),
			  				'title' => upfront_fix_data_type(upfront_get('image-title', $image)),
			  				'caption' => upfront_fix_data_type(upfront_get('image-description', $image))
			  			),

			  			'hyperlink' => array(
			  				'href' => upfront_fix_data_type(upfront_get('image-hyperlink', $image)),
			  				'target' => upfront_fix_data_type(upfront_get('image-open-link-in-new-window', $image, false)) ? ' target="_blank"' : null
			  			)
			  		);

			  		echo '<li>';

			  			/* Open hyperlink if user added one for image */
			  			if ( $output['hyperlink']['href'] )
			  				echo '<a href="' . $output['hyperlink']['href'] . '"' . $output['hyperlink']['target'] . '>';

			  			/* Don't forget to display the ACTUAL IMAGE */
			  			echo '<img src="' . $output['image']['src'] . '" alt="' . $output['image']['alt'] . '" title="' . $output['image']['title'] . '" />';

			  			/* Closing tag for hyperlink */
			  			if ( $output['hyperlink']['href'] )
			  				echo '</a>';

			  			/* Caption */
				  		if ( !empty($output['image']['caption']) )
				  			echo '<p class="flex-caption">' . $output['image']['caption'] . '</p>';

			  		echo '</li>';

			  	}

		  	echo '</ul>';

		  	/* Put in viewport div for sliders that only have 1 image and don't slide */
		  	if ( count($images) === 1 )
		  		echo '</div>';

		echo '</div>';

	}


	function setup_elements() {

		$this->register_block_element(array(
			'id' => 'slider-container',
			'name' => __('Slider Container', 'upfront'),
			'description' => __('Enthält Ansichtsfenster, Paging', 'upfront'),
			'selector' => '.flexslider',
			'properties' => array('background', 'borders', 'padding', 'corners', 'box-shadow', 'advanced', 'transition', 'outlines')
		));

		$this->register_block_element(array(
			'id' => 'slider-viewport',
			'name' => __('Slider Ansichtsfenster', 'upfront'),
			'description' => __('Enthält Bilder', 'upfront'),
			'selector' => '.flex-viewport',
			'properties' => array('background', 'borders', 'padding', 'corners', 'box-shadow', 'overflow', 'advanced', 'transition', 'outlines')
		));

		$this->register_block_element(array(
			'id' => 'slider-caption',
			'name' => __('Slider-Beschriftung', 'upfront'),
			'selector' => '.flex-caption',
			'properties' => array('background', 'padding', 'fonts')
		));

		$this->register_block_element(array(
			'id' => 'slider-direction-nav-link',
			'name' => __('Slider Navigation Link', 'upfront'),
			'selector' => '.flex-direction-nav a',
			'properties' => array('background', 'borders', 'padding', 'corners', 'box-shadow')
		));

		$this->register_block_element(array(
			'id' => 'slider-direction-nav-next',
			'name' => __('Slider Richtung Weiter', 'upfront'),
			'selector' => '.flex-direction-nav a.flex-next',
			'properties' => array('background', 'borders', 'padding', 'corners', 'box-shadow')
		));

		$this->register_block_element(array(
			'id' => 'slider-direction-nav-prev',
			'name' => __('Slider Richtung Zurück', 'upfront'),
			'selector' => '.flex-direction-nav a.flex-prev',
			'properties' => array('background', 'borders', 'padding', 'corners', 'box-shadow')
		));

		$this->register_block_element(array(
			'id' => 'slider-paging',
			'name' => __('Slider Paging', 'upfront'),
			'selector' => '.flex-control-nav',
			'properties' => array('background', 'borders', 'padding', 'corners', 'box-shadow')
		));

		$this->register_block_element(array(
			'id' => 'slider-paging-link',
			'name' => __('Slider Paging Link', 'upfront'),
			'selector' => '.flex-control-paging li a',
			'properties' => array('background', 'borders', 'padding', 'corners', 'box-shadow'),
			'states' => array(
				'Hover' => '.flex-control-paging li a:hover', 
				'Active' => '.flex-control-paging li a.flex-active'
			)
		));

	}


}


class UpFrontSliderBlockOptions extends UpFrontBlockOptionsAPI {

	public $tabs;
	public $inputs;


	function __construct($block_type_object){

		parent::__construct($block_type_object);

		$this->tabs = array(
			'slider-images' => __('Slider Bilder', 'upfront'),
			'animation' => __('Animation', 'upfront'),
			'ui' => __('Benutzeroberfläche', 'upfront'),
		);

		$this->inputs = array(
			'slider-images' => array(
				'images' => array(
					'type' => 'repeater',
					'name' => 'images',
					'label' => __('Bilder', 'upfront'),
					'tooltip' => __('Lade hier die Bilder hoch, die Du dem Bildrotator hinzufügen möchtest. Du kannst die Bilder sogar per Drag & Drop verschieben, um die Reihenfolge zu ändern.', 'upfront'),
					'inputs' => array(
						array(
							'type' => 'image',
							'name' => 'image',
							'label' => __('Bild', 'upfront'),
							'default' => null
						),

						array(
							'type' => 'text',
							'name' => 'image-hyperlink',
							'label' => __('Hyperlink', 'upfront'),
							'default' => null
						),

						array(
							'type' => 'checkbox',
							'name' => 'image-open-link-in-new-window',
							'label' => __('Öffne Link in neuen Fenster', 'upfront'),
							'default' => false
						),

						array(
							'type' => 'text',
							'name' => 'image-description',
							'label' => __('Bildbeschriftung', 'upfront'),
							'placeholder' => __('Beschreibe das Bild', 'upfront'),
							'tooltip' => __('Dies wird unter dem Bild angezeigt.', 'upfront')
						),

						array(
							'type' => 'text',
							'name' => 'image-title',
							'label' => __('"title" Attribut', 'upfront'),
							'tooltip' => __('Dies wird als "title"-Attribut für das Bild verwendet. Das title-Attribut ist für SEO (Search Engine Optimization) von Vorteil und ermöglicht Deinen Besuchern, die Maus über das Bild zu bewegen und darüber zu lesen.', 'upfront')
						),

						array(
							'type' => 'text',
							'name' => 'image-alt',
							'label' => __('"alt" Attribut', 'upfront'),
							'tooltip' => __('Dies wird als "alt"-Attribut für das Bild verwendet. Das alt-Attribut ist <em>äußerst</em> vorteilhaft für SEO (Search Engine Optimization) und für die allgemeine Zugänglichkeit.', 'upfront')
						)
					),
					'sortable' => true,
					'limit' => false
				),

				'randomize-order' => array(
					'type' => 'checkbox',
					'name' => 'randomize-order',
					'label' => __('Bildreihenfolge zufällig auswählen', 'upfront'),
					'default' => false
				),

				'image-sizing-header' => array(
					'type' => 'heading',
					'name' => 'image-sizing-header',
					'label' => __('Bildgröße', 'upfront')
				),

					'crop-resize-images' => array(
						'type' => 'checkbox',
						'name' => 'crop-resize-images',
						'label' => __('Bilder zuschneiden und ihre Größe ändern', 'upfront'),
						'default' => true,
						'tooltip' => __('Der Slider-Block kann die Größe automatisch ändern und Bilder zuschneiden, damit sie in den Slider passen, wenn die Bilder zu groß sind. Dies verbessert die Ladezeiten und passt das Bild besser an den Slider an. <br/> <br/> Wenn Du nicht möchtest, dass der Slider dies tut, deaktiviere diese Option, und der Sliderblock fügt Deine ursprünglich hochgeladenen Bilder in den Slider ein. <strong>Bitte beachte:</strong> Auch wenn diese Option nicht aktiviert ist, wird die Größe der Bilder mit CSS geändert.', 'upfront')
					),

				'content-types-heading' => array(
					'type' => 'heading',
					'name' => 'content-types-heading',
					'label' => __('Andere Inhaltstypen', 'upfront'),
				),

					'content-types-text' => array(
						'type' => 'notice',
						'name' => 'content-types-text',
						'notice' => __('Dieser Slider-Block kann nur Bilder anzeigen.', 'upfront')
					)
			),

			'animation' => array(
				'animation' => array(
					'type' => 'select',
					'name' => 'animation',
					'label' => __('Animation', 'upfront'),
					'default' => 'slide-horizontal',
					'options' => array(
						'slide-horizontal' => __('Horizontal schieben', 'upfront'),
						'slide-vertical' => __('Vertikal schieben', 'upfront'),
						'fade' => 'Fade'
					)
				),

				'animation-speed' => array(
					'type' => 'slider',
					'name' => 'animation-speed',
					'label' => __('Animations Geschwindigkeit', 'upfront'),
					'default' => 500,
					'slider-min' => 50,
					'slider-max' => 5000,
					'slider-interval' => 10,
					'tooltip' => __('Passe dies an, um zu ändern, wie lange die Animation beim Überblenden zwischen Bildern dauert.', 'upfront'),
					'unit' => 'ms'
				),

				'slideshow' => array(
					'type' => 'checkbox',
					'name' => 'slideshow',
					'label' => __('Automatischer Folienvorschub', 'upfront'),
					'default' => true,
					'tooltip' => __('Als Diashow fungieren und automatisch zur nächsten Folie wechseln.', 'upfront')
				),

				'animation-timeout' => array(
					'type' => 'slider',
					'name' => 'animation-timeout',
					'label' => __('Zeit zwischen Folien', 'upfront'),
					'default' => 6,
					'slider-min' => 1,
					'slider-max' => 20,
					'slider-interval' => 1,
					'tooltip' => __('Dies ist die Zeit, die jedes Bild sichtbar bleibt.', 'upfront'),
					'unit' => 's'
				)
			),

			'ui' => array(
				'show-pager-nav' => array(
					'type' => 'checkbox',
					'name' => 'show-pager-nav',
					'label' => __('Pager-Navigation anzeigen', 'upfront'),
					'default' => true,
					'tooltip' => __('Zeige Punkte unter dem Schieberegler an, um bestimmte Folien auszuwählen.', 'upfront')
				),

				'show-direction-nav' => array(
					'type' => 'checkbox',
					'name' => 'show-direction-nav',
					'label' => __('Nächste/Vorherige Pfeile anzeigen', 'upfront'),
					'default' => true,
					'tooltip' => __('Zeige die Pfeile an, um zur nächsten/vorherigen Folie zu gelangen.', 'upfront')
				)
			),

		);
	}

}