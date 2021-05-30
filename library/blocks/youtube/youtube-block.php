<?php

class UpFrontYoutubeBlock extends UpFrontBlockAPI {

	
	public $id;
	public $name;
	public $options_class;
	public $description;
	public $categories;
	public function __construct() {
		$this->id            = 'youtube';
		$this->name          = 'YouTube';
		$this->options_class = 'UpFrontYoutubeBlockOptions';
		$this->description   = __( 'Ermöglicht das Einfügen von YouTube-Videos. Du kannst Wiedergabelisten mit dem Youtube Advanced-Block erstellen.', 'upfront' );
		$this->categories    = array( 'medien' );
	}


	/**
	 * Setup Visual Editor elements.
	 */
	public function setup_elements() {
		$this->register_block_element(
			array(
				'id'       => 'youtube',
				'name'     => 'Youtube',
				'selector' => 'div.su-youtube',
			)
		);
	}
	public function content( $block ) {

		$url        = parent::get_setting( $block, 'url' );
		$width      = parent::get_setting( $block, 'width' );
		$height     = parent::get_setting( $block, 'height' );
		$responsive = parent::get_setting( $block, 'responsive' );
		$autoplay   = parent::get_setting( $block, 'autoplay' );
		$mute       = parent::get_setting( $block, 'mute' );

		if ( ! $responsive ) {
			$responsive = 'yes';
		}

		if ( $width < 200 ) {
			$width = 200;
		}

		if ( $width > 1600 ) {
			$width = 1600;
		}

		if ( $height < 200 ) {
			$height = 200;
		}

		if ( $height > 1600 ) {
			$height = 1600;
		}

		if ( ! $autoplay ) {
			$autoplay = 'yes';
		}

		if ( ! $mute ) {
			$mute = 'no';
		}

		echo do_shortcode( '[su_youtube url="' . $url . '" responsive="' . $responsive . '" autoplay="' . $autoplay . '" mute="' . $mute . '" width="' . $width . '" height="' . $height . '" ]' );

	}

}
class UpFrontYoutubeBlockOptions extends UpFrontBlockOptionsAPI {

	
	public $tabs;
	public $sets;
	public $inputs;
	public function __construct() {

		$this->tabs = array(
			'general' => __( 'YouTube', 'upfront' ),
		);

		$this->sets = array();

		$this->inputs = array(
			'general' => array(

				'url'        => array(
					'name'    => 'url',
					'label'   => __( 'Url', 'upfront' ),
					'type'    => 'text',
					'default' => '',
					'tooltip' => __( 'URL der YouTube-Seite mit Video. Ex: http://youtube.com/watch?v=XXXXXX', 'upfront' ),
				),

				'width'      => array(
					'name'    => 'width',
					'type'    => 'integer',
					'label'   => __( 'Breite', 'upfront' ),
					'default' => 600,
					'tooltip' => __( 'Breite', 'upfront' ),
				),

				'height'     => array(
					'name'    => 'height',
					'type'    => 'integer',
					'label'   => __( 'Höhe', 'upfront' ),
					'default' => 400,
					'tooltip' => __( 'Höhe', 'upfront' ),
				),

				'responsive' => array(
					'name'    => 'responsive',
					'type'    => 'select',
					'label'   => __( 'Responsiv', 'upfront' ),
					'default' => 'yes',
					'options' => array(
						'yes' => __( 'Ja', 'upfront' ),
						'no'  => __( 'Nein', 'upfront' ),
					),
					'tooltip' => __( 'Ignoriere die Parameter für Breite und Höhe und sorge dafür, dass der Player reagiert', 'upfront' ),
				),

				'autoplay'   => array(
					'name'    => 'autoplay',
					'type'    => 'select',
					'label'   => __( 'Autoplay', 'upfront' ),
					'default' => 'yes',
					'options' => array(
						'yes' => __( 'Ja', 'upfront' ),
						'no'  => __( 'Nein', 'upfront' ),
					),
					'tooltip' => __( 'Video automatisch abspielen, wenn eine Seite geladen wird. Bitte beachte, dass in modernen Browsern die Autoplay-Option nur mit aktivierter Stummschaltoption funktioniert', 'upfront' ),
				),

				'mute'       => array(
					'name'    => 'mute',
					'type'    => 'select',
					'label'   => __( 'Stumm', 'upfront' ),
					'default' => 'no',
					'options' => array(
						'yes' => __( 'Ja', 'upfront' ),
						'no'  => __( 'Nein', 'upfront' ),
					),
					'tooltip' => __( 'Schalte Player stumm', 'upfront' ),
				),
			),
		);
	}

}
