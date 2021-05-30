<?php

class UpFrontColumnsBlock extends UpFrontBlockAPI {

	
	public $id;
	public $name;
	public $options_class;
	public $description;
	public $categories;
	public function __construct() {

		$this->id            = 'columns';
		$this->name          = __( 'Spalten', 'upfront' );
		$this->options_class = 'UpFrontColumnsBlockOptions';
		$this->description   = __( 'Hilft Dir, den Seiteninhalt in Spalten zu unterteilen. ', 'upfront' );
		$this->categories    = array( 'box', 'content' );

	}

	/**
	 * Setup Visual Editor elements.
	 */
	public function setup_elements() {
		$this->register_block_element(
			array(
				'id'       => 'columns',
				'name'     => __( 'Spalten', 'upfront' ),
				'selector' => 'span.su-columns',
			)
		);
	}

	/**
	 * Dynamic_css function
	 *
	 * @param string  $block_id Block ID.
	 * @param boolean $block Block Object.
	 * @return string
	 */
	public static function dynamic_css( $block_id, $block = false ) {

		if ( ! $block ) {
			$block = \UpFrontBlocksData::get_block( $block_id );
		}

		return '';
	}

	/**
	 * UpFront Content Method
	 *
	 * @param object $block Block.
	 * @return void
	 */
	public function content( $block ) {

		$columns   = parent::get_setting( $block, 'columns', array() );
		$shortcode = '[su_row class=""]';

		foreach ( $columns as $column => $params ) {

			$size    = isset( $params['size'] ) ? $params['size'] : '';
			$center  = isset( $params['center'] ) ? $params['center'] : '';
			$class   = isset( $params['class'] ) ? $params['class'] : '';
			$content = isset( $params['content'] ) ? $params['content'] : '';

			$shortcode .= '[su_column ';
			$shortcode .= 'size="' . $size . '" ';
			$shortcode .= 'center="' . $center . '" ';
			$shortcode .= 'class="' . $class . '" ';
			$shortcode .= ']';
			$shortcode .= $content;
			$shortcode .= '[/su_column]';

		}

		$shortcode .= '[/su_row]';

		echo do_shortcode( $shortcode );

	}

	/**
	 * Register styles and scripts
	 *
	 * @param string  $block_id Block ID.
	 * @param boolean $block Is Block?.
	 * @return void
	 */
	public static function enqueue_action( $block_id, $block = false ) {}

}
class UpFrontColumnsBlockOptions extends UpFrontBlockOptionsAPI {

	
	public $tabs;
	public $sets;
	public $inputs;
	public function __construct() {

		$this->tabs = array(
			'general' => __( 'Spalten', 'upfront' ),
		);

		$this->sets = array();

		$this->inputs = array(
			'general' => array(
				'columns' => array(
					'type'     => 'repeater',
					'name'     => 'columns',
					'label'    => __( 'Spalten', 'upfront' ),
					'tooltip'  => __( 'Inhalt für Deine Spalten. ', 'upfront' ),
					'inputs'   => array(
						array(
							'name'    => 'size',
							'label'   => __( 'Größe', 'upfront' ),
							'type'    => 'select',
							'default' => 'one-half',
							'options' => array(
								'full-width'   => __( 'Volle Breite 1/1', 'upfront' ),
								'one-half'     => __( 'Eine Halbe 1/2', 'upfront' ),
								'one-third'    => __( 'Ein Drittel 1/3', 'upfront' ),
								'two-third'    => __( 'Zwei Drittel 2/3', 'upfront' ),
								'one-fourth'   => __( 'Ein Viertel 1/4', 'upfront' ),
								'three-fourth' => __( 'Drei Viertel 3/4', 'upfront' ),
								'one-fifth'    => __( 'Ein Fünftel 1/5', 'upfront' ),
								'two-fifth'    => __( 'Zwei Fünftel 2/5', 'upfront' ),
								'three-fifth'  => __( 'Drei Fünftel 3/5', 'upfront' ),
								'four-fifth'   => __( 'Vier Fünftel 4/5', 'upfront' ),
								'one-sixth'    => __( 'Ein Sechstel 1/6', 'upfront' ),
								'five-sixth'   => __( 'Fünf Sechstel 5/6', 'upfront' ),
							),
							'tooltip' => __( 'Spaltenbreite auswählen. Diese Breite wird abhängig von der Seitenbreite berechnet', 'upfront' ),
						),

						array(
							'type'    => 'select',
							'name'    => 'center',
							'label'   => __( 'Zentriert', 'upfront' ),
							'options' => array(
								'yes' => __( 'Ja', 'upfront' ),
								'no'  => __( 'Nein', 'upfront' ),
							),
							'default' => 'no',
							'tooltip' => __( 'Ist diese Spalte auf der Seite zentriert?', 'upfront' ),
						),

						array(
							'type'    => 'text',
							'name'    => 'class',
							'label'   => __( 'Klasse', 'upfront' ),
							'tooltip' => __( 'Zusätzliche CSS-Klassennamen, die durch Leerzeichen getrennt sind', 'upfront' ),
						),

						array(
							'type'    => 'wysiwyg',
							'name'    => 'content',
							'label'   => __( 'Inhalt', 'upfront' ),
							'default' => null,
						),
					),
					'sortable' => true,
					'limit'    => 100,
				),
			),
		);
	}



	/**
	 * Allow developers to modify the properties of the class and use functions since doing a property outside of a function will not allow you to.
	 *
	 * @param boolean $args Args.
	 * @return void
	 */
	public function modify_arguments( $args = false ) {}

}
