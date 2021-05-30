<?php
/**
 * Tabs Block
 */
class UpFrontVisualElementsBlockTabs extends UpFrontBlockAPI {
	
	
	public $id;
	public $name;
	public $options_class;
	public $description;
	public $categories;

	/**
	 * Constructor
	 */
	public function __construct() {
		$this->id            = 'visual-elements-tabs';
		$this->name          = __( 'Registerkarten', 'upfront' );
		$this->options_class = 'UpFrontVisualElementsBlockTabsOptions';
		$this->description   = __( 'Ermöglicht das Teilen Deines Inhalts durch horizontale oder vertikale Registerkarten. Du kannst festlegen, welche Registerkarte standardmäßig ausgewählt wird, und jede Registerkarte in einen Link verwandeln. Du kannst jeden HTML-Code oder sogar andere Shortcodes als Inhalt verwenden. ', 'upfront' );
		$this->categories    = array( 'box','elemente' );
	}

	/**
	 * Setup Visual Editor elements.
	 */
	public function setup_elements() {

		$this->register_block_element(
			array(
				'id'       => 'tabs',
				'name'     => __( 'Tabs', 'upfront' ),
				'selector' => 'div.su-tabs',
			)
		);

		$this->register_block_element(
			array(
				'id'       => 'navs',
				'name'     => __( 'Navs', 'upfront' ),
				'selector' => 'div.su-tabs .su-tabs-nav',
			)
		);

		$this->register_block_element(
			array(
				'id'       => 'title',
				'name'     => __( 'Titel', 'upfront' ),
				'selector' => 'div.su-tabs .su-tabs-nav span',
			)
		);

		$this->register_block_element(
			array(
				'id'       => 'panes',
				'name'     => __( 'Karten', 'upfront' ),
				'selector' => 'div.su-tabs .su-tabs-panes',
			)
		);

		$this->register_block_element(
			array(
				'id'       => 'pane',
				'name'     => __( 'Karte', 'upfront' ),
				'selector' => 'div.su-tabs .su-tabs-panes .su-tabs-pane',
			)
		);

		$this->register_block_element(
			array(
				'id'       => 'p',
				'name'     => __( 'Text', 'upfront' ),
				'selector' => 'div.su-tabs .su-tabs-panes .su-tabs-pane p',
			)
		);

		$this->register_block_element(
			array(
				'id'       => 'a',
				'name'     => __( 'Link', 'upfront' ),
				'selector' => 'div.su-tabs .su-tabs-panes .su-tabs-pane a',
			)
		);

		$this->register_block_element(
			array(
				'id'       => 'h1',
				'name'     => __( 'Überschrift H1', 'upfront' ),
				'selector' => 'div.su-tabs .su-tabs-panes .su-tabs-pane 1',
			)
		);

		$this->register_block_element(
			array(
				'id'       => 'h2',
				'name'     => __( 'Überschrift H2', 'upfront' ),
				'selector' => 'div.su-tabs .su-tabs-panes .su-tabs-pane 2',
			)
		);

		$this->register_block_element(
			array(
				'id'       => 'h3',
				'name'     => __( 'Überschrift H3', 'upfront' ),
				'selector' => 'div.su-tabs .su-tabs-panes .su-tabs-pane 3',
			)
		);

		$this->register_block_element(
			array(
				'id'       => 'h4',
				'name'     => __( 'Überschrift H4', 'upfront' ),
				'selector' => 'div.su-tabs .su-tabs-panes .su-tabs-pane 4',
			)
		);

		$this->register_block_element(
			array(
				'id'       => 'h5',
				'name'     => __( 'Überschrift H5', 'upfront' ),
				'selector' => 'div.su-tabs .su-tabs-panes .su-tabs-pane 5',
			)
		);

		$this->register_block_element(
			array(
				'id'       => 'h6',
				'name'     => __( 'Überschrift H6', 'upfront' ),
				'selector' => 'div.su-tabs .su-tabs-panes .su-tabs-pane 6',
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

		$style    = ( parent::get_setting( $block, 'style' ) ) ? parent::get_setting( $block, 'style' ) : 'default';
		$active   = ( parent::get_setting( $block, 'active' ) ) ? parent::get_setting( $block, 'active' ) : 1;
		$vertical = parent::get_setting( $block, 'vertical' );
		$tabs     = parent::get_setting( $block, 'tabs', array() );

		if ( 'yes' === $vertical ) {
			$shortcode = '[su_tabs vertical="' . $vertical . '"]';
		} else {
			$shortcode = '[su_tabs]';
		}

		foreach ( $tabs as $tab => $params ) {

			$title    = isset( $params['title'] ) ? $params['title'] : '';
			$disabled = isset( $params['disabled'] ) ? $params['disabled'] : '';
			$anchor   = isset( $params['anchor'] ) ? $params['anchor'] : '';
			$url      = isset( $params['url'] ) ? $params['url'] : '';
			$target   = isset( $params['target'] ) ? $params['target'] : '';
			$content  = isset( $params['content'] ) ? $params['content'] : '';

			$shortcode .= '[su_tab ';
			$shortcode .= 'title="' . $title . '" ';
			$shortcode .= 'disabled="' . $disabled . '" ';
			$shortcode .= 'anchor="' . $anchor . '" ';
			$shortcode .= 'url="' . $url . '" ';
			$shortcode .= 'target="' . $target . '" ';
			$shortcode .= ']';
			$shortcode .= $content;
			$shortcode .= '[/su_tab]';

		}

		$shortcode .= '[/su_tabs]';

		echo do_shortcode( $shortcode );

	}

}
/**
 * Options class for block
 */
class UpFrontVisualElementsBlockTabsOptions extends UpFrontBlockOptionsAPI {


	public $tabs;
	public $sets;
	public $inputs;
	public function __construct() {

		$this->tabs = array(
			'general' => __( 'Registerkarten', 'upfront' ),
		);

		$this->sets = array();

		$this->inputs = array(
			'general' => array(
				'active'   => array(
					'name'    => 'active',
					'label'   => __( 'Aktiv', 'upfront' ),
					'type'    => 'integer',
					'tooltip' => __( 'Welche Registerkarte ist standardmäßig geöffnet? Nummer von 1 bis 100.', 'upfront' ),
					'default' => 1,
				),

				'vertical' => array(
					'name'    => 'vertical',
					'label'   => __( 'Vertikal', 'upfront' ),
					'type'    => 'select',
					'default' => 'no',
					'options' => array(
						'yes' => __( 'Ja', 'upfront' ),
						'no'  => __( 'Nein', 'upfront' ),
					),
					'tooltip' => __( 'Richte die Registerkarten vertikal aus', 'upfront' ),
				),

				'tabs'     => array(
					'type'     => 'repeater',
					'name'     => 'tabs',
					'label'    => __( 'Registerkarten', 'upfront' ),
					'tooltip'  => __( 'Inhalt für Deine Registerkarten.', 'upfront' ),
					'inputs'   => array(

						array(
							'type'  => 'text',
							'name'  => 'title',
							'label' => __( 'Titel', 'upfront' ),
						),

						array(
							'type'    => 'select',
							'name'    => 'disabled',
							'label'   => __( 'Deaktiviert', 'upfront' ),
							'options' => array(
								'yes' => __( 'Ja', 'upfront' ),
								'no'  => __( 'Nein', 'upfront' ),
							),
							'default' => 'no',
						),

						array(
							'type'    => 'text',
							'name'    => 'anchor',
							'label'   => __( 'Anker', 'upfront' ),
							'tooltip' => __( 'Du kannst einen eindeutigen Anker für diese Registerkarte verwenden, um mit einem Hash in der Seiten-URL darauf zuzugreifen. Beispiel: Verwende Hello und navigiere zu einer URL wie http://example.com/page-url#Hello. Diese Registerkarte wird aktiviert und gescrollt.', 'upfront' ),
						),

						array(
							'type'    => 'text',
							'name'    => 'url',
							'label'   => __( 'Url', 'upfront' ),
							'tooltip' => __( 'Registerkarte mit einer beliebigen Webseite verknüpfen. Verwende die vollständige URL, um den Tabulatortitel in einen Link umzuwandeln.', 'upfront' ),
						),

						array(
							'name'    => 'target',
							'type'    => 'select',
							'label'   => __( 'Ziel', 'upfront' ),
							'default' => 'blank',
							'options' => array(
								'self'  => __( 'Im selben Browser-Tab öffnen', 'upfront' ),
								'blank' => __( 'In neuem Browser-Tab öffnen', 'upfront' ),
							),
							'tooltip' => __( 'Wähle aus, wie der benutzerdefinierte Tab-Link geöffnet werden soll', 'upfront' ),
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
}
