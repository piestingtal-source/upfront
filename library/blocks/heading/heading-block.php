<?php


class UpFrontVisualElementsBlockHeading extends UpFrontBlockAPI {

	
	public $id;
	public $name;
	public $options_class;
	public $description;
	public $categories;
	public $inline_editable;
	public $inline_editable_equivalences;
	public function __construct() {
		$this->id            = 'visual-elements-heading';
		$this->name          = __( 'Überschrift', 'upfront' );
		$this->options_class = 'UpFrontVisualElementsBlockHeadingOptions';
		$this->description   = __( 'Ermöglicht das Erstellen gestylter Überschriften mit anpassbarer Größe und Rand.', 'upfront' );
		$this->categories    = array( 'content' );

		$this->inline_editable = array( 'block-title', 'block-subtitle', 'su-heading-inner' );

		$this->inline_editable_equivalences = array( 'su-heading-inner' => 'heading-text' );
	}

	/**
	 * Setup Visual Editor elements.
	 */
	public function setup_elements() {
		$this->register_block_element(
			array(
				'id'       => 'heading',
				'name'     => 'heading',
				'selector' => '.su-heading',
			)
		);
		$this->register_block_element(
			array(
				'id'       => 'su-heading-inner',
				'name'     => 'Text',
				'selector' => '.su-heading-inner',
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

		$style        = parent::get_setting( $block, 'style' );
		$heading_text = parent::get_setting( $block, 'heading-text' );

		if ( ! $style ) {
			$style = 'default';
		}

		$html = do_shortcode( '[su_heading style="' . $style . '"]' . $heading_text . '[/su_heading]' );

		// remove inline CSS for color.
		$html = preg_replace( '(style=("|\Z)(.*?)("|\Z))', '', $html );

		echo $html;

	}
}
class UpFrontVisualElementsBlockHeadingOptions extends UpFrontBlockOptionsAPI {

	
	public $tabs;
	public $sets;
	public $inputs;
	public function __construct() {
		$this->tabs = array(
			'general' => __( 'Überschrift', 'upfront' ),
		);

		$this->sets = array();

		$this->inputs = array(
			'general' => array(
				'heading-text' => array(
					'name'  => 'heading-text',
					'type'  => 'text',
					'label' => __( 'Überschriftstext', 'upfront' ),
				),
			),
		);
	}
}
