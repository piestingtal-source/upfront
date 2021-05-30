<?php


class UpFrontDummyTextBlock extends UpFrontBlockAPI {


	public $id;
	public $name;
	public $options_class;
	public $description;
	public $categories;
	public function __construct() {
		$this->id            = 'dummy-text';
		$this->name          = __( 'Dummy Text', 'upfront' );
		$this->options_class = 'UpFrontDummyTextBlockOptions';
		$this->description   = __( 'Mit diesem Shortcode kannst Du den Text „lorem ipsum“ anzeigen. Du kannst auswählen, wie viele Absätze oder Wörter generiert werden sollen.', 'upfront' );
		$this->categories    = array( 'content','typografie' );
	}


	/**
	 * Setup Visual Editor elements.
	 */
	public function setup_elements() {
		$this->register_block_element(
			array(
				'id'       => 'dummy-text',
				'name'     => 'dummy-text',
				'selector' => '.su-dummy-text',
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

		$what   = parent::get_setting( $block, 'what' );
		$amount = parent::get_setting( $block, 'amount' );
		$cache  = parent::get_setting( $block, 'cache' );

		if ( is_null( $what ) ) {
			$what = 'paras';
		}

		if ( is_null( $amount ) ) {
			$amount = 1;
		}

		if ( $amount < 1 ) {
			$amount = 1;
		}

		if ( $amount > 100 ) {
			$amount = 100;
		}

		if ( is_null( $cache ) ) {
			$cache = 'yes';
		}

		$html = do_shortcode( '[su_dummy_text what="' . $what . '" amount="' . $amount . '" cache="' . $cache . '" class=""]' );

		// remove inline CSS for color.
		$html = preg_replace( '(style=("|\Z)(.*?)("|\Z))', '', $html );

		echo $html;

	}

}
class UpFrontDummyTextBlockOptions extends UpFrontBlockOptionsAPI {

	
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
				'what'   => array(
					'name'    => 'what',
					'type'    => 'select',
					'label'   => __( 'Was', 'upfront' ),
					'tooltip' => __( 'Was zu generieren', 'upfront' ),
					'default' => 'paras',
					'options' => array(
						'paras' => __( 'Absätze', 'upfront' ),
						'words' => __( 'Wörter', 'upfront' ),
						'bytes' => __( 'Bytes', 'upfront' ),
					),
				),

				'amount' => array(
					'name'    => 'amount',
					'type'    => 'integer',
					'label'   => __( 'Anzahl', 'upfront' ),
					'tooltip' => __( 'Wie viele Elemente (Absätze oder Wörter) müssen generiert werden? Die minimale Wortmenge beträgt 5', 'upfront' ),
					'default' => 1,
				),

				'cache'  => array(
					'name'    => 'cache ',
					'type'    => 'select',
					'label'   => __( 'Cache ', 'upfront' ),
					'default' => 'yes',
					'options' => array(
						'yes' => __( 'Ja', 'upfront' ),
						'no'  => __( 'Nein', 'upfront' ),
					),
				),
			),
		);
	}

}
