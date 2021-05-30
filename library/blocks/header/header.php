<?php

class UpFrontHeaderBlock extends UpFrontBlockAPI {


	public $id;
	public $name;
	public $options_class;
	public $fixed_height;
	public $html_tag;
	public $attributes;
	public $description;
	public $allow_titles;
	protected $show_content_in_grid;
	public $categories;


	function __construct(){

		$this->id = 'header';	
		$this->name = __('Header', 'upfront');
		$this->options_class = 'UpFrontHeaderBlockOptions';	
		$this->fixed_height = true;	
		$this->html_tag = 'header';	
		$this->attributes = array(
			'itemscope' => '',
			'itemtype' => 'http://schema.org/WPHeader'
		);
		$this->description = __('Zeige Deinen Banner, Dein Logo oder Deinen Webseiten-Titel und Deinen Slogan an. Dies befindet sich normalerweise oben auf Deiner Webseite.', 'upfront');
		$this->allow_titles = false;	
		$this->show_content_in_grid = true;
		$this->categories = array('core','content');

	}

	function setup_elements() {

		$this->register_block_element(array(
			'id' => 'site-title',
			'name' => __('Webseitentitel', 'upfront'),
			'selector' => 'span.banner a',
			'states' => array(
				'Hover' => 'span.banner a:hover',
				'Clicked' => 'span.banner a:active',
				'Shrinked' => 'span.banner a.is_shrinked',
			)
		));

		$this->register_block_element(array(
			'id' => 'banner-image',
			'name' => __('Banner/Logo Link', 'upfront'),
			'selector' => 'a.banner-image',
			'states' => array(
				'Clicked' => 'a.banner-image:active',
				'Hover' => 'a.banner-image:hover',
				'Shrinked' => 'a.banner-image.is_shrinked',
			)
		));

		$this->register_block_element(array(
			'id' => 'banner-image-img',
			'name' => __('Banner Bild', 'upfront'),
			'selector' => 'a.banner-image img',
			'states' => array(
				'Shrinked' => 'a.banner-image img.is_shrinked',
			)
		));

		$this->register_block_element(array(
			'id' => 'site-tagline',
			'name' => __('Webseiten-Slogan', 'upfront'),
			'selector' => '.tagline'
		));

	}


	function content($block) {

		//Use header image if there is one	
		if ( $header_image_src = parent::get_setting($block, 'header-image') ) {

			do_action('upfront_before_header_link');

			if ( parent::get_setting($block, 'resize-header-image', true) ) {

				$block_width = UpFrontBlocksData::get_block_width($block);
				$block_height = UpFrontBlocksData::get_block_height($block);

				$header_image_url = upfront_resize_image($header_image_src, $block_width, $block_height);

			} else {

				$header_image_url = $header_image_src;

			}

			$link = apply_filters('upfront_header_link', home_url() );

			echo '<a href="' . $link . '" class="banner-image"><img src="' . upfront_format_url_ssl($header_image_url) . '" alt="' . get_bloginfo('name') . '" /></a>';

			do_action('upfront_after_header_link');


		//No image present	
		} else {

			do_action('upfront_before_header_link');

			$link = apply_filters('upfront_header_link', home_url() );

			echo '<span class="banner" itemprop="headline"><a href="' . $link . '">' . get_bloginfo('name') . '</a></span>';

			do_action('upfront_after_header_link');

			if ( !parent::get_setting($block, 'hide-tagline', false) ) {

				if ( (is_front_page() || is_home()) && get_option('show_on_front') != 'page' ) {

					echo '<h1 class="tagline" itemprop="headline">' . get_bloginfo('description') . '</h1>' . "\n";

				} else {

					echo '<span class="tagline" itemprop="description">' . get_bloginfo('description') . '</span>' . "\n";

				}

				do_action('upfront_after_tagline');

			}

		}

	}

	
	public static function dynamic_css( $block_id, $block, $original_block = null ) {

		$selector = '#block-' . UpFrontBlocksData::get_legacy_id($block);

		/* If this block is a mirror, then pull the settings from the block that's mirroring that way the dimensions are correct */
		if ( is_array( $original_block ) ) {

			$block_id = $original_block['id'];
			$block = $original_block;

			$selector .= '.block-original-' . UpFrontBlocksData::get_legacy_id($block);

		}

		
		$css = $selector . ' {
				max-height: 100%;
				transition-property: all;
				transition-duration: 500ms;
				transition-timing-function: ease-out;
			}';
		$css .= $selector . ' img{
				transition-property: all;
				transition-duration: 500ms;
				transition-timing-function: ease-out;
			}';

		return $css;

	}

}


class UpFrontHeaderBlockOptions extends UpFrontBlockOptionsAPI {

	public $tabs;
	public $inputs;


	function __construct($block_type_object){

		parent::__construct($block_type_object);

		$this->tabs = array(
			'general' => 'Header-Optionen'
		);

		$this->inputs = array(
			'general' => array(
				'header-image' => array(
					'type' => 'image',
					'name' => 'header-image',
					'label' => __('Banner/Logo', 'upfront'),
					'default' => null
				),

				'resize-header-image' => array(
					'name' => 'resize-header-image',
					'label' => __('Ändert die Größe des Header-Bilds automatisch', 'upfront'),
					'type' => 'checkbox',
					'tooltip' => __('Wenn Du möchtest, dass UpFront Dein Header-Bild automatisch skaliert und auf die richtigen Abmessungen zuschneidet, lasse dies aktiviert. <br /><br /> <em><strong>Wichtig:</strong> Damit die Größe des Bilds geändert werden kann und beschnitten muss es <strong>vom Computer</strong> hochgeladen werden. <strong>NICHT von URL</strong>.</em>', 'upfront'),
					'default' => true
				),

				'hide-tagline' => array(
					'name' => 'hide-tagline',
					'label' => __('Webseiten-Slogan verbergen', 'upfront'),
					'type' => 'checkbox',
					'tooltip' => __('Aktiviere diese Option, um den Slogan in Deinem Header auszublenden. Der Slogan befindet sich unter Deinem Webseiten-Titel. <br /><br /> <em><strong>Wichtig:</strong> Der Slogan zeigt <strong>NICHT</strong> an, ob ein Header-Bild hinzugefügt wurde.</em>', 'upfront'),
					'default' => false
				)
			)
		);
	}

}