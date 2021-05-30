<?php

/**
 * Vimeo Block
 */
class UpFrontBlockVimeo extends UpFrontBlockAPI {
	
	
	public $id;
	public $name;
	public $options_class;
	public $description;
	public $categories;
	public function __construct() {
		$this->id            = 'visual-elements-vimeo';
		$this->name          = __( 'Vimeo', 'upfront' );
		$this->options_class = 'UpFrontBlockVimeoOptions';
		$this->description   = __( 'Ermöglicht das Einfügen von Vimeo-Videos.', 'upfront' );
		$this->categories    = array( 'medien' );
	}

	/**
	 * Setup Visual Editor elements.
	 */
	public function setup_elements() {
		$this->register_block_element(
			array(
				'id'       => 'vimeo',
				'name'     => 'Vimeo',
				'selector' => 'div.su-vimeo',
			)
		);
	}

	/**
	 * UpFront Content Method
	 *
	 * @param object $block Block.
	 * @return void
	 */
	public function content( $block ) {

		$url        = parent::get_setting( $block, 'url' );
		$width      = parent::get_setting( $block, 'width' );
		$height     = parent::get_setting( $block, 'height' );
		$responsive = parent::get_setting( $block, 'responsive' );
		$autoplay   = parent::get_setting( $block, 'autoplay' );
		$dnt        = parent::get_setting( $block, 'dnt' );

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

		if ( ! $dnt ) {
			$dnt = 'no';
		}

		echo do_shortcode( '[su_vimeo url="' . $url . '" responsive="' . $responsive . '" autoplay="' . $autoplay . '" dnt="' . $dnt . '" width="' . $width . '" height="' . $height . '" ]' );

	}

}
class UpFrontBlockVimeoOptions extends UpFrontBlockOptionsAPI {

	
	public $tabs;
	public $sets;
	public $inputs;
	public function __construct() {

		$this->tabs = array(
			'general' => __( 'Allgemeines', 'upfront' ),
		);

		$this->sets = array();

		$this->inputs = array(
			'general' => array(

				'url'        => array(
					'name'    => 'url',
					'label'   => __( 'Url', 'upfront' ),
					'type'    => 'text',
					'default' => '',
					'tooltip' => __( 'URL der Vimeo-Seite mit Video. Ex: http://vimeo.com/watch?v=XXXXXX', 'upfront' ),
				),

				'width'      => array(
					'name'    => 'width',
					'type'    => 'integer',
					'label'   => __( 'Breite', 'upfront' ),
					'default' => 600,
					'tooltip' => __( 'Playerbreite', 'upfront' ),
				),

				'height'     => array(
					'name'    => 'height',
					'type'    => 'integer',
					'label'   => __( 'Höhe', 'upfront' ),
					'default' => 400,
					'tooltip' => __( 'Playerhöhe', 'upfront' ),
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
					'tooltip' => __( 'Ignoriere die Parameter für Breite und Höhe und sorge dafür, dass der Player responsiv reagiert', 'upfront' ),
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
					'tooltip' => __( 'Video automatisch abspielen, wenn eine Seite geladen wird.', 'upfront' ),
				),

				'dnt'        => array(
					'name'    => 'dnt',
					'type'    => 'select',
					'label'   => __( 'DNT', 'upfront' ),
					'default' => 'no',
					'options' => array(
						'yes' => __( 'Ja', 'upfront' ),
						'no'  => __( 'Nein', 'upfront' ),
					),
					'tooltip' => __( 'Wenn Du diesen Parameter auf JA setzen, kann der Player keine Wiedergabesitzungsdaten verfolgen. Hat den gleichen Effekt wie das Aktivieren eines Do Not Track-Headers in Deinem Browser', 'upfront' ),
				),
			),
		);
	}

}

