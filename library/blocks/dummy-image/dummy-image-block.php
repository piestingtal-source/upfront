<?php

class UpFrontVisualElementsBlockDummyImage extends UpFrontBlockAPI {

	
	public $id;
	public $name;
	public $options_class;
	public $description;
	public $categories;
	public function __construct() {
		$this->id            = 'visual-elements-dummy-image';
		$this->name          = __( 'Dummy-Bild', 'upfront' );
		$this->options_class = 'UpFrontVisualElementsBlockDummyImageOptions';
		$this->description   = __( 'Ermöglicht die Anzeige eines Dummy-Bildes. Du kannst das Bildtheme und die Größe ändern.', 'upfront' );
		$this->categories    = array( 'medien','content' );
	}

	

	/**
	 * Setup Visual Editor elements.
	 */
	public function setup_elements() {
		$this->register_block_element(
			array(
				'id'       => 'dummy-image',
				'name'     => 'dummy-image',
				'selector' => '.su-dummy-image',
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

		$width  = parent::get_setting( $block, 'width' );
		$height = parent::get_setting( $block, 'height' );
		$theme  = parent::get_setting( $block, 'theme' );

		if ( is_null( $width ) ) {
			$width = 500;
		}

		if ( $width < 10 ) {
			$width = 10;
		}

		if ( $width > 1600 ) {
			$width = 1600;
		}

		if ( is_null( $height ) ) {
			$height = 300;
		}

		if ( $height < 10 ) {
			$height = 10;
		}

		if ( $height > 1600 ) {
			$height = 1600;
		}

		if ( is_null( $theme ) ) {
			$theme = 'any';
		}

		$html = do_shortcode( '[su_dummy_image width="' . $width . '" height="' . $height . '" theme="' . $theme . '" class=""]' );

		// remove inline CSS for color.
		$html = preg_replace( '(style=("|\Z)(.*?)("|\Z))', '', $html );

		echo $html;

	}

}
class UpFrontVisualElementsBlockDummyImageOptions extends UpFrontBlockOptionsAPI {

	
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
				'width'  => array(
					'name'    => 'width',
					'type'    => 'integer',
					'label'   => __( 'Breite', 'upfront' ),
					'tooltip' => __( 'Bildbreite', 'upfront' ),
					'default' => 500,
				),

				'height' => array(
					'name'    => 'height',
					'type'    => 'integer',
					'label'   => __( 'Höhe', 'upfront' ),
					'tooltip' => __( 'Bildhöhe', 'upfront' ),
					'default' => 300,
				),
				'theme'  => array(
					'name'    => 'theme ',
					'type'    => 'select',
					'label'   => __( 'Theme ', 'upfront' ),
					'default' => 'any',
					'options' => array(
						'any'       => __( 'Irgendein', 'upfront' ),
						'abstract'  => __( 'Abstrakt', 'upfront' ),
						'animals'   => __( 'Tiere', 'upfront' ),
						'business'  => __( 'Business', 'upfront' ),
						'cats'      => __( 'Katzen', 'upfront' ),
						'city'      => __( 'Stadt', 'upfront' ),
						'food'      => __( 'Essen', 'upfront' ),
						'nightlife' => __( 'Nachtleben', 'upfront' ),
						'fashion'   => __( 'Mode', 'upfront' ),
						'people'    => __( 'Menschen', 'upfront' ),
						'nature'    => __( 'Natur', 'upfront' ),
						'sports'    => __( 'Sport', 'upfront' ),
						'technics'  => __( 'Technik', 'upfront' ),
						'transport' => __( 'Transport', 'upfront' ),

					),
					'tooltip' => 'Wähle das Theme für dieses Bild.',
				),
			),
		);
	}
}
