<?php
/**
 * Google Map Block
 */
class UpFrontBlockGmap extends UpFrontBlockAPI {

	
	public $id;
	public $name;
	public $options_class;
	public $description;
	public $categories;
	public function __construct() {
		$this->id            = 'visual-elements-gmap';
		$this->name          = __( 'Google Map', 'upfront' );
		$this->options_class = 'UpFrontBlockGmapOptions';
		$this->description   = __( 'Hilft bei der einfachen Anzeige von Google Maps.', 'upfront' );
		$this->categories    = array( 'medien', 'content', 'elemente' );
	}


	/**
	 * Setup Visual Editor elements.
	 */
	public function setup_elements() {
		$this->register_block_element(
			array(
				'id'       => 'gmap',
				'name'     => 'Gmap',
				'selector' => 'div.su-gmap',
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

		$address    = parent::get_setting( $block, 'address' );
		$responsive = parent::get_setting( $block, 'responsive' );
		$zoom       = parent::get_setting( $block, 'zoom' );
		$width      = parent::get_setting( $block, 'width' );
		$height     = parent::get_setting( $block, 'height' );

		if ( ! $address ) {
			$address = 'San José, Costa Rica';
		}

		if ( ! $responsive ) {
			$responsive = 'yes';
		}

		if ( $zoom < 0 ) {
			$zoom = 0;
		}

		if ( $zoom > 21 ) {
			$zoom = 21;
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

		echo do_shortcode( '[su_gmap address="' . $address . '" responsive="' . $responsive . '" zoom="' . $zoom . '" width="' . $width . '" height="' . $height . '" ]' );

	}

}
/**
 * Options class for block
 */
class UpFrontBlockGmapOptions extends UpFrontBlockOptionsAPI {

	
	public $tabs;
	public $sets;
	public $inputs;
	public function __construct() {

		$this->tabs = array(
			'general' => __( 'Google Map', 'upfront' ),
		);

		$this->sets = array();

		$this->inputs = array(
			'general' => array(
				'address'    => array(
					'name'    => 'address',
					'label'   => __( 'Addresse', 'upfront' ),
					'type'    => 'text',
					'default' => '',
					'tooltip' => __( 'Adresse für den Marker. Du kannst sie in jeder Sprache eingeben', 'upfront' ),
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
					'tooltip' => __( 'Ignoriere die Parameter für Breite und Höhe und sorge dafür, dass die Karte reagiert', 'upfront' ),
				),

				'zoom'       => array(
					'name'    => 'zoom',
					'type'    => 'integer',
					'label'   => __( 'Zoom', 'upfront' ),
					'default' => 0,
					'tooltip' => __( 'Zoom legt die anfängliche Zoomstufe der Karte fest. Akzeptierte Werte reichen von 1 (die ganze Welt) bis 21 (einzelne Gebäude). Verwende 0 (Null), um die Zoomstufe abhängig vom angezeigten Objekt einzustellen (automatisch).', 'upfront' ),
				),

				'width'      => array(
					'name'    => 'width',
					'type'    => 'integer',
					'label'   => __( 'Breite', 'upfront' ),
					'default' => 600,
					'tooltip' => __( 'Kartenbreite', 'upfront' ),
				),

				'height'     => array(
					'name'    => 'height',
					'type'    => 'integer',
					'label'   => __( 'Höhe', 'upfront' ),
					'default' => 400,
					'tooltip' => __( 'Kartenhöhe', 'upfront' ),
				),
			),
		);
	}
}
