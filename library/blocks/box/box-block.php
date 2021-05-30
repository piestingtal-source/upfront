<?php

class UpFrontBoxBlock extends UpFrontBlockAPI {

	
	public $id;
	public $name;
	public $options_class;
	public $description;
	public $categories;
	public $inline_editable;
	public $inline_editable_equivalences;
	public function __construct() {

		$this->id              = 've-box';
		$this->name            = __( 'Box', 'upfront' );
		$this->options_class   = 'UpFrontBoxBlockOptions';
		$this->description     = __( 'Ermöglicht das Erstellen von Feldern mit farbenfrohen Titeln. Du kannst das Erscheinungsbild der Box leicht ändern. Du kannst auch beliebigen HTML-Code oder sogar andere Shortcodes darin platzieren. ', 'upfront' );
		$this->categories      = array( 'content', 'box' );
		$this->inline_editable = array( 'block-title', 'block-subtitle', 'su-box-title' );

		$this->inline_editable_equivalences = array( 'su-box-title' => 'title' );
	}

	/**
	 * Setup Visual Editor elements.
	 */
	public function setup_elements() {

		$this->register_block_element(
			array(
				'id'       => 'box',
				'name'     => __( 'Box', 'upfront' ),
				'selector' => 'div.su-box',
			)
		);

		$this->register_block_element(
			array(
				'id'       => 'title',
				'name'     => __( 'Titel', 'upfront' ),
				'selector' => '.su-box-title',
			)
		);

		$this->register_block_element(
			array(
				'id'       => 'content',
				'name'     => __( 'Inhalt', 'upfront' ),
				'selector' => '.su-box-content',
			)
		);

		$this->register_block_element(
			array(
				'id'       => 'content-text',
				'name'     => __( 'Inhaltstext', 'upfront' ),
				'selector' => '.su-box-content p',
			)
		);

		$this->register_block_element(
			array(
				'id'       => 'content-h1',
				'name'     => __( 'Inhalt H1', 'upfront' ),
				'selector' => '.su-box-content h1',
			)
		);

		$this->register_block_element(
			array(
				'id'       => 'content-h2',
				'name'     => __( 'Inhalt H2', 'upfront' ),
				'selector' => '.su-box-content h2',
			)
		);

		$this->register_block_element(
			array(
				'id'       => 'content-h3',
				'name'     => __( 'Inhalt H3', 'upfront' ),
				'selector' => '.su-box-content h3',
			)
		);

		$this->register_block_element(
			array(
				'id'       => 'content-h4',
				'name'     => __( 'Inhalt H4', 'upfront' ),
				'selector' => '.su-box-content h4',
			)
		);

		$this->register_block_element(
			array(
				'id'       => 'content-h5',
				'name'     => __( 'Inhalt H5', 'upfront' ),
				'selector' => '.su-box-content h5',
			)
		);

		$this->register_block_element(
			array(
				'id'       => 'content-h6',
				'name'     => __( 'Inhalt H6', 'upfront' ),
				'selector' => '.su-box-content h6',
			)
		);

		$this->register_block_element(
			array(
				'id'       => 'content-li',
				'name'     => __( 'Inhalt li', 'upfront' ),
				'selector' => '.su-box-content li',
			)
		);

		$this->register_block_element(
			array(
				'id'       => 'content-a',
				'name'     => __( 'Inhaltslink', 'upfront' ),
				'selector' => '.su-box-content a',
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
			$block = \UpFrontBlocksData::get_block($block_id);
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

		$style   = parent::get_setting( $block, 'style', 'default' );
		$title   = parent::get_setting( $block, 'title' );
		$content = parent::get_setting( $block, 'content' );
		$radius  = parent::get_setting( $block, 'radius' );

		if ( $radius < 0 ) {
			$radius = 1;
		}

		if ( $radius > 20 ){
			$radius = 20;
		}

		$shortcode  = '[su_box title="' . $title . '" style="' . $style . '" radius="' . $radius . '" class="title"]';
		$shortcode .= $content;
		$shortcode .= '[/su_box]';

		$html = do_shortcode( $shortcode );

		// remove inline CSS.
		$html = preg_replace( '(style=("|\Z)(.*?)("|\Z))', '', $html );

		echo $html;

	}


	/**
	 * Register styles and scripts
	 *
	 * @param string  $block_id Block ID.
	 * @param boolean $block Is Block?.
	 * @return void
	 */
	public static function enqueue_action( $block_id, $block = false ) {

		if ( ! $block ) {
			$block = \UpFrontBlocksData::get_block( $block_id );
		}

		/* CSS */
		\UpFrontCompiler::register_file(
			array(
				'name'         => 've-box-css',
				'format'       => 'css',
				'fragments'    => array(
					upfront_url() . '/library/blocks/box/box.css',
				),
				'dependencies' => array(),
				'enqueue'      => true,
			)
		);
	}
}
class UpFrontBoxBlockOptions extends UpFrontBlockOptionsAPI {

	
	public $tabs;
	public $sets;
	public $inputs;
	public function __construct() {

		$this->tabs = array(
			'general' => __( 'Box-Block', 'upfront' ),
		);

		$this->sets = array();

		$this->inputs = array(
			'general' => array(
				'style'   => array(
					'name'    => 'style',
					'type'    => 'select',
					'label'   => __( 'Stil', 'upfront' ),
					'default' => 'default',
					'options' => array(
						'default' => 'Standard',
						'soft'    => 'Sanft',
						'glass'   => 'Glas',
						'bubbles' => 'Blasen',
						'noise'   => 'Laut',
					),
					'tooltip' => __( 'Box Stil Voreinstellung', 'upfront' ),
				),

				'radius'  => array(
					'name'    => 'radius',
					'type'    => 'text',
					'label'   => __( 'Radius', 'upfront' ),
					'tooltip' => __( 'Radius der Box-Kanten', 'upfront' ),
					'default' => 3,
				),

				'title'   => array(
					'name'    => 'title',
					'type'    => 'text',
					'label'   => __( 'Titel', 'upfront' ),
					'tooltip' => __( 'Text für den Feldtitel', 'upfront' ),
				),

				'content' => array(
					'name'    => 'content',
					'type'    => 'wysiwyg',
					'label'   => __( 'Inhalt', 'upfront' ),
					'tooltip' => __( 'Box-Inhalt', 'upfront' ),
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