<?php

class UpFrontBasicHeadingBlock extends UpFrontBlockAPI {

	
	public $id;
	public $name;
	public $options_class;
	public $description;
	public $categories;
	public $inline_editable;
	public function __construct() {

		$this->id              = 've-basic-heading';
		$this->name            = __( 'Einfache Überschrift', 'upfront' );
		$this->options_class   = 'UpFrontBasicHeadingBlockOptions';
		$this->description     = __( 'Eine Überschrift kann als Titel, Abschnittsüberschrift und/oder Unterüberschrift dienen. Du kannst jeder Überschrift eine relative Wichtigkeit von H1 bis H6 zuweisen. Tipp: Suchmaschinen (und Personen!) Verwenden Überschriften, um die wichtigsten Themen und Themen Deiner Inhalte zu ermitteln. ', 'upfront' );
		$this->categories      = array( 'content', 'core', 'typografie' );
		$this->inline_editable = array( 'block-title', 'block-subtitle', 'basic-heading' );
	}

	/**
	 * Init
	 */
	public function init() {}

	/**
	 * Setup Visual Editor elements.
	 */
	public function setup_elements() {

		$this->register_block_element(
			array(
				'id'       => 'basic-heading-h1',
				'name'     => __( 'Einfache Überschrift H1', 'upfront' ),
				'selector' => 'h1',
				'states'   => array(
					'Hover' => 'h1:hover',
				),
			)
		);
		$this->register_block_element(
			array(
				'id'       => 'basic-heading-h2',
				'name'     => __( 'Einfache Überschrift H2', 'upfront' ),
				'selector' => 'h2',
				'states'   => array(
					'Hover' => 'h2:hover',
				),
			)
		);
		$this->register_block_element(
			array(
				'id'       => 'basic-heading-h3',
				'name'     => __( 'Einfache Überschrift H3', 'upfront' ),
				'selector' => 'h3',
				'states'   => array(
					'Hover' => 'h3:hover',
				),
			)
		);
		$this->register_block_element(
			array(
				'id'       => 'basic-heading-h4',
				'name'     => __( 'Einfache Überschrift H4', 'upfront' ),
				'selector' => 'h4',
				'states'   => array(
					'Hover' => 'h4:hover',
				),
			)
		);
		$this->register_block_element(
			array(
				'id'       => 'basic-heading-h5',
				'name'     => __( 'Einfache Überschrift H5', 'upfront' ),
				'selector' => 'h5',
				'states'   => array(
					'Hover' => 'h5:hover',
				),
			)
		);
		$this->register_block_element(
			array(
				'id'       => 'basic-heading-h6',
				'name'     => __( 'Einfache Überschrift H6', 'upfront' ),
				'selector' => 'h6',
				'states'   => array(
					'Hover' => 'h6:hover',
				),
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

		$text = parent::get_setting( $block, 'basic-heading' );
		$tag  = parent::get_setting( $block, 'tag', 'h1' );

		echo sprintf( '<%s class="basic-heading" >%s</%s>', $tag, $text, $tag );

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
class UpFrontBasicHeadingBlockOptions extends UpFrontBlockOptionsAPI {

	
	public $tabs;
	public $sets;
	public $inputs;
	public function __construct() {

		$this->tabs   = array(
			'general' => __( 'Überschrift', 'upfront' ),
		);
		$this->sets   = array();
		$this->inputs = array(
			'general' => array(
				'basic-heading' => array(
					'name'  => 'basic-heading',
					'type'  => 'text',
					'label' => __( 'Überschriftstext', 'upfront' ),
				),
				'tag'           => array(
					'name'    => 'tag',
					'type'    => 'select',
					'options' => array(
						'h1' => 'H1',
						'h2' => 'H2',
						'h3' => 'H3',
						'h4' => 'H4',
						'h5' => 'H5',
						'h6' => 'H6',
					),
					'label'   => __( 'Tag', 'upfront' ),
					'tooltip' => __( 'Zu verwendendes HTML-Tag. ', 'upfront' ),
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
