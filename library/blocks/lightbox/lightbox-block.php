<?php

/**
 * Lightbox Block
 */
class UpFrontLightboxBlock extends UpFrontBlockAPI {

	
	public $id;
	public $name;
	public $options_class;
	public $description;
	public $categories;
	public $inline_editable;
	public $inline_editable_equivalences;
	public function __construct() {
		$this->id            = 'lightbox';
		$this->name          = __( 'Lightbox', 'upfront' );
		$this->options_class = 'UpFrontLightboxBlockOptions';	
		$this->description   = __( 'Ermöglicht die Anzeige verschiedener Elemente in einem Popup-Fenster. Du kannst ein Bild, eine Webseite oder einen beliebigen HTML-Inhalt anzeigen.', 'upfront' );
		$this->categories    = array( 'content', 'medien' );

		$this->inline_editable = array( 'block-title', 'block-subtitle', 'su-lightbox' );	

		$this->inline_editable_equivalences = array( 'su-lightbox' => 'title' );
	}


	/**
	 * Setup Visual Editor elements.
	 */
	public function setup_elements() {

		$this->register_block_element(
			array(
				'id'       => 'title',
				'name'     => __( 'Titel', 'upfront' ),
				'selector' => '.su-lightbox',
			)
		);

		$this->register_block_element(
			array(
				'id'       => 'title',
				'name'     => __( 'Titel', 'upfront' ),
				'selector' => '.su-lightbox',
			)
		);

		$this->register_block_element(
			array(
				'id'       => 'content',
				'name'     => __( 'Inhalt', 'upfront' ),
				'selector' => 'div.su-lightbox-content',
			)
		);

		$this->register_block_element(
			array(
				'id'       => 'content-text',
				'name'     => __( 'Inhaltstext', 'upfront' ),
				'selector' => '.su-lightbox-content p',
			)
		);

		$this->register_block_element(
			array(
				'id'       => 'content-h1',
				'name'     => __( 'Inhalt H1', 'upfront' ),
				'selector' => '.su-lightbox-content h1',
			)
		);

		$this->register_block_element(
			array(
				'id'       => 'content-h2',
				'name'     => __( 'Inhalt H2', 'upfront' ),
				'selector' => '.su-lightbox-content h2',
			)
		);

		$this->register_block_element(
			array(
				'id'       => 'content-h3',
				'name'     => __( 'Inhalt H3', 'upfront' ),
				'selector' => '.su-lightbox-content h3',
			)
		);

		$this->register_block_element(
			array(
				'id'       => 'content-h4',
				'name'     => __( 'Inhalt H4', 'upfront' ),
				'selector' => '.su-lightbox-content h4',
			)
		);

		$this->register_block_element(
			array(
				'id'       => 'content-h5',
				'name'     => __( 'Inhalt H5', 'upfront' ),
				'selector' => '.su-lightbox-content h5',
			)
		);

		$this->register_block_element(
			array(
				'id'       => 'content-h6',
				'name'     => __( 'Inhalt H6', 'upfront' ),
				'selector' => '.su-lightbox-content h6',
			)
		);

		$this->register_block_element(
			array(
				'id'       => 'content-li',
				'name'     => __( 'Inhalt li', 'upfront' ),
				'selector' => '.su-lightbox-content li',
			)
		);

		$this->register_block_element(
			array(
				'id'       => 'content-a',
				'name'     => __( 'Inhaltslink', 'upfront' ),
				'selector' => '.su-lightbox-content a',
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

		$type   = parent::get_setting( $block, 'type', 'image' );
		$title  = parent::get_setting( $block, 'title' );
		$image  = parent::get_setting( $block, 'image' );
		$iframe = parent::get_setting( $block, 'iframe' );
		$inline = parent::get_setting( $block, 'inline' );

		$shortcode = '[su_lightbox ';

		switch ( $type ) {
			case 'image':
				$shortcode .= 'type="image" src="' . $image . '" class="title"]' . $title . '[/su_lightbox]';
				break;

			case 'iframe':
				$shortcode .= 'type="iframe" src="' . $iframe . '" class="title"]' . $title . '[/su_lightbox]';
				break;

			case 'inline':
				$shortcode .= 'type="inline" src="#' . $block['id'] . '" class="title"] ' . $title . ' [/su_lightbox]';
				$shortcode .= '[su_lightbox_content id="#' . $block['id'] . '"]' . $inline . '[/su_lightbox_content]';
				break;

			default:
				$content = 'none';
				break;
		}

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
	public static function enqueue_action( $block_id, $block ) {

		if ( ! $block ) {
			$block = \UpFrontBlocksData::get_block( $block_id );
		}

		

		/* CSS */
		UpFrontCompiler::register_file(
			array(
				'name'         => 've-lightbox-css',
				'format'       => 'css',
				'fragments'    => array(
					upfront_url() . '/library/blocks/lightbox/assets/lightbox.css',
				),
				'dependencies' => array(),
				'enqueue'      => true,
			)
		);
	}
}
class UpFrontLightboxBlockOptions extends UpFrontBlockOptionsAPI {

	
	public $tabs;
	public $sets;
	public $inputs;
	public function __construct() {

		$this->tabs = array(
			'general' => __( 'Lightbox Optionen', 'upfront' ),
			'image'   => __( 'Image', 'upfront' ),
			'iframe'  => __( 'Iframe', 'upfront' ),
			'inline'  => __( 'Inline', 'upfront' ),
		);

		$this->sets = array();

		$this->inputs = array(
			'general' => array(

				'type'  => array(
					'name'    => 'type',
					'type'    => 'select',
					'label'   => __( 'Typ', 'upfront' ),
					'default' => 'image',
					'options' => array(
						'image'  => __( 'Bild', 'upfront' ),
						'iframe' => __( 'Iframe', 'upfront' ),
						'inline' => __( 'Inline', 'upfront' ),
					),
					'toggle'  => array(
						'image'  => array(
							'show' => array(
								'li#sub-tab-image',
							),
							'hide' => array(
								'li#sub-tab-iframe',
								'li#sub-tab-inline',
							),
						),
						'iframe' => array(
							'show' => array(
								'li#sub-tab-iframe',
							),
							'hide' => array(
								'li#sub-tab-image',
								'li#sub-tab-inline',
							),
						),
						'inline' => array(
							'show' => array(
								'li#sub-tab-inline',
							),
							'hide' => array(
								'li#sub-tab-iframe',
								'li#sub-tab-image',
							),
						),
					),
					'tooltip' => __( 'Wähle den Typ des Inhalts der Lightbox aus', 'upfront' ),
				),

				'title' => array(
					'name'    => 'title',
					'type'    => 'text',
					'label'   => __( 'Titel', 'upfront' ),
					'tooltip' => __( 'Text für den Titel', 'upfront' ),
				),
			),

			'image'   => array(
				'image' => array(
					'name'    => 'image',
					'type'    => 'image',
					'label'   => __( 'Bild', 'upfront' ),
					'tooltip' => __( 'Wähle das anzuzeigende Bild aus', 'upfront' ),
				),
			),

			'iframe'  => array(
				'iframe' => array(
					'name'    => 'iframe',
					'type'    => 'text',
					'label'   => __( 'URL', 'upfront' ),
					'tooltip' => __( 'URL zum Anzeigen', 'upfront' ),
				),
			),

			'inline'  => array(
				'inline' => array(
					'name'    => 'inline',
					'type'    => 'wysiwyg',
					'label'   => __( 'Inhalt', 'upfront' ),
					'tooltip' => __( 'Inhalt zum Anzeigen', 'upfront' ),
				),
			),
		);
	}
}
