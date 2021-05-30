<?php

class UpFrontButtonBlock extends UpFrontBlockAPI {


	
	public $id;
	public $name;
	public $options_class;
	public $description;
	public $categories;
	public function __construct() {
		$this->id            = 'visual-elements-button';
		$this->name          = __( 'Button', 'upfront' );
		$this->options_class = 'UpFrontButtonBlockOptions';
		$this->description   = __( 'Ermöglicht das Erstellen hochgradig anpassbarer Schaltflächen. Du kannst den Stil, die Farben und die Größe der Schaltflächen ändern, ein Symbol oder eine Beschreibung hinzufügen. ', 'upfront' );
		$this->categories    = array( 'content','elemente' );
	}

	/**
	 * Setup Visual Editor elements.
	 */
	public function setup_elements() {

		$this->register_block_element(
			array(
				'id'       => 'button',
				'name'     => __( 'Schaltfläche', 'upfront' ),
				'selector' => 'a.su-button',
				'states'   => array(
					'Hover'   => 'a.su-button:hover',
					'Clicked' => 'a.su-button:active',
				),
			)
		);

		$this->register_block_element(
			array(
				'id'       => 'icon',
				'name'     => __( 'Symbol', 'upfront' ),
				'selector' => 'a.su-button span i',
			)
		);

		$this->register_block_element(
			array(
				'id'       => 'text',
				'name'     => __( 'Text', 'upfront' ),
				'selector' => 'a.su-button span small',
				'states'   => array(
					'Hover'   => 'a.su-button span small:hover',
					'Clicked' => 'a.a.su-button span small:active',
				),
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

		if ( ! $block ){
			$block = \UpFrontBlocksData::get_block( $block_id );
		}

		$css = '#block-' . $block_id . ' .su-button small{ opacity: 1 }';

		return $css;

	}

	/**
	 * UpFront Content Method
	 *
	 * @param object $block Block.
	 * @return void
	 */
	public function content( $block ) {

		$url     = parent::get_setting( $block, 'url' );
		$target  = parent::get_setting( $block, 'target' );
		$style   = parent::get_setting( $block, 'style' );
		$icon    = parent::get_setting( $block, 'icon' );
		$desc    = parent::get_setting( $block, 'desc' );
		$onclick = parent::get_setting( $block, 'onclick' );
		$rel     = parent::get_setting( $block, 'rel' );
		$title   = parent::get_setting( $block, 'title' );

		$shortcode  = '[su_button url="' . $url . '" target="' . $target . '"';
		$shortcode .= ' style="' . $style . '"';

		if ( $icon && ! filter_var( $icon, FILTER_VALIDATE_URL ) ){
			$icon = 'icon:' . $icon;
		}

		$shortcode .= ' icon="' . $icon . '" desc="' . $desc . '" onclick="' . $onclick . '" rel="' . $rel . '" title="' . $title . '" class="desc"]';

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

		$style = parent::get_setting( $block, 'style' );

		if ( 'none' !== $style ) {

			/* CSS */
			UpFrontCompiler::register_file(
				array(
					'name'         => 've-button-css',
					'format'       => 'css',
					'fragments'    => array(
						upfront_url() . '/library/blocks/button/button.css',
					),
					'dependencies' => array(),
					'enqueue'      => true,
				)
			);
		}
	}
}

class UpFrontButtonBlockOptions extends UpFrontBlockOptionsAPI {

	
	public $tabs;
	public $sets;
	public $inputs;
	public function __construct() {

		$this->tabs = array(
			'general' => __( 'Schaltfläche', 'upfront' ),
		);

		$this->sets = array();

		$this->inputs = array(
			'general' => array(

				'url'     => array(
					'name'    => 'url',
					'type'    => 'text',
					'label'   => __( 'Url', 'upfront' ),
					'tooltip' => __( 'Schaltflächenlink', 'upfront' ),
				),

				'target'  => array(
					'name'    => 'target',
					'type'    => 'select',
					'label'   => __( 'Ziel', 'upfront' ),
					'default' => 'self',
					'options' => array(
						'self'  => __( 'Im selben Browser-Tab öffnen', 'upfront' ),
						'blank' => __( 'In neuem Browser-Tab öffnen', 'upfront' ),
					),
					'tooltip' => __( 'Schaltflächenlink-Ziel', 'upfront' ),
				),

				'style'   => array(
					'name'    => 'style',
					'label'   => __( 'Stil', 'upfront' ),
					'type'    => 'select',
					'default' => 'default',
					'options' => array(
						'none'    => 'Keinen',
						'default' => 'Standard',
						'flat'    => 'Flach',
						'ghost'   => 'Geist',
						'soft'    => 'Sanft',
						'glass'   => 'Glas',
						'bubbles' => 'Blasen',
						'noise'   => 'Laut',
						'stroked' => 'Gestrichelt',
						'3d'      => '3D',
					),
					'tooltip' => __( 'Voreinstellung des Schaltflächenhintergrundstils', 'upfront' ),
				),
				'icon'    => array(
					'name'    => 'icon',
					'label'   => __( 'Symbol', 'upfront' ),
					'type'    => 'text',
					'tooltip' => __( 'Du kannst ein benutzerdefiniertes Symbol für diese Schaltfläche hochladen oder ein integriertes Symbol auswählen. FontAwesome Symbolname oder Symbolbild-URL. Beispiel: "star", http://example.com/icon.png', 'upfront' ),
				),
				'desc'    => array(
					'name'    => 'desc',
					'label'   => __( 'Beschreibung', 'upfront' ),
					'type'    => 'text',
					'tooltip' => __( 'Kleine Beschreibung unter Schaltflächentext. Diese Option ist nicht mit dem Symbol kompatibel. ', 'upfront' ),
				),
				'onclick' => array(
					'name'    => 'onclick',
					'label'   => __( 'onClick', 'upfront' ),
					'type'    => 'text',
					'tooltip' => __( 'Erweiterter JavaScript-Code für die onClick-Aktion. ', 'upfront' ),
				),
				'rel'     => array(
					'name'    => 'rel',
					'label'   => __( 'Rel', 'upfront' ),
					'type'    => 'text',
					'tooltip' => __( 'Hier kannst Du einen Wert für das rel-Attribut hinzufügen. Beispielwerte: nofollow, lightbox', 'upfront' ),
				),
				'title'   => array(
					'name'    => 'title',
					'label'   => __( 'Titel', 'upfront' ),
					'type'    => 'text',
					'tooltip' => __( 'Hier kannst Du einen Wert für das title-Attribut hinzufügen', 'upfront' ),
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

