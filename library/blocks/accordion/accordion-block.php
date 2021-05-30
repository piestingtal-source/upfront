<?php

class UpFrontAccordionBlock extends UpFrontBlockAPI {

	
	public $id;
	public $name;
	public $options_class;
	public $description;
	public $categories;
	public function __construct() {

		$this->id            = 'accordion';
		$this->name          = __( 'Akkordeon', 'upfront' );
		$this->options_class = 'UpFrontAccordionBlockOptions';
		$this->description   = __( 'Ermöglicht das Erstellen von Blöcken mit verstecktem Inhalt - Spoiler (Toggles). Versteckte Inhalte werden angezeigt, wenn auf den Blocktitel geklickt wird. Du kannst für jeden Spoiler unterschiedliche Symbole angeben oder sogar unterschiedliche Stile verwenden. ', 'upfront' );
		$this->categories    = array( 'box','content' );

	}

	
	public function init() {

		if ( ! class_exists( 'UpFront_Shortcodes' ) ) {
			return false;
		}

	}

	/**
	 * Setup Visual Editor elements.
	 */
	public function setup_elements() {

		$this->register_block_element(
			array(
				'id'       => 'spoiler',
				'name'     => __( 'Spoiler', 'upfront' ),
				'selector' => '.su-spoiler',
			)
		);
		$this->register_block_element(
			array(
				'id'       => 'spoiler-title',
				'name'     => __( 'Spoiler Titel', 'upfront' ),
				'selector' => '.su-accordion .su-spoiler-title',
			)
		);
		$this->register_block_element(
			array(
				'id'       => 'spoiler-icon',
				'name'     => __( 'Spoiler Symbol', 'upfront' ),
				'selector' => '.su-accordion .su-spoiler-icon',
			)
		);
		$this->register_block_element(
			array(
				'id'       => 'spoiler-title',
				'name'     => __( 'Spoiler Titel', 'upfront' ),
				'selector' => '.su-accordion .su-spoiler-title',
			)
		);
		$this->register_block_element(
			array(
				'id'       => 'spoiler-content',
				'name'     => __( 'Spoiler Inhalt', 'upfront' ),
				'selector' => '.su-accordion .su-spoiler-content',
			)
		);
		$this->register_block_element(
			array(
				'id'       => 'spoiler-content-p',
				'name'     => __( 'Spoiler Inhalt Absatz', 'upfront' ),
				'selector' => '.su-accordion .su-spoiler-content p',
			)
		);
		$this->register_block_element(
			array(
				'id'       => 'spoiler-h1',
				'name'     => __( 'Spoiler h1', 'upfront' ),
				'selector' => '.su-accordion .su-spoiler-content h1',
			)
		);
		$this->register_block_element(
			array(
				'id'       => 'spoiler-h2',
				'name'     => __( 'Spoiler h2', 'upfront' ),
				'selector' => '.su-accordion .su-spoiler-content h2',
			)
		);
		$this->register_block_element(
			array(
				'id'       => 'spoiler-h3',
				'name'     => __( 'Spoiler h3', 'upfront' ),
				'selector' => '.su-accordion .su-spoiler-content h3',
			)
		);
		$this->register_block_element(
			array(
				'id'       => 'spoiler-h2',
				'name'     => __( 'Spoiler h2', 'upfront' ),
				'selector' => '.su-accordion .su-spoiler-content h2',
			)
		);
		$this->register_block_element(
			array(
				'id'       => 'spoiler-h4',
				'name'     => __( 'Spoiler h4', 'upfront' ),
				'selector' => '.su-accordion .su-spoiler-content h4',
			)
		);
		$this->register_block_element(
			array(
				'id'       => 'spoiler-h5',
				'name'     => __( 'Spoiler h5', 'upfront' ),
				'selector' => '.su-accordion .su-spoiler-content h5',
			)
		);
		$this->register_block_element(
			array(
				'id'       => 'spoiler-h6',
				'name'     => __( 'Spoiler h6', 'upfront' ),
				'selector' => '.su-accordion .su-spoiler-content h6',
			)
		);
		$this->register_block_element(
			array(
				'id'       => 'spoiler-ul',
				'name'     => __( 'Spoilerliste', 'upfront' ),
				'selector' => '.su-accordion .su-spoiler-content ul',
			)
		);
		$this->register_block_element(
			array(
				'id'       => 'spoiler-ol',
				'name'     => __( 'Spoilerliste', 'upfront' ),
				'selector' => '.su-accordion .su-spoiler-content ol',
			)
		);
		$this->register_block_element(
			array(
				'id'       => 'spoiler-li',
				'name'     => __( 'Spoiler Listenelement', 'upfront' ),
				'selector' => '.su-accordion .su-spoiler-content li',
			)
		);
		$this->register_block_element(
			array(
				'id'       => 'spoiler-span',
				'name'     => __( 'Spoiler Spanne', 'upfront' ),
				'selector' => '.su-accordion .su-spoiler-content span',
			)
		);
		$this->register_block_element(
			array(
				'id'       => 'spoiler-a',
				'name'     => __( 'Spoiler Link', 'upfront' ),
				'selector' => '.su-accordion .su-spoiler-content a',
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

		$accordion_class = parent::get_setting( $block, 'accordion-class', array() );

		if ( empty( $accordion_class ) ) {
			$accordion_class = '';
		}

		$spoilers  = parent::get_setting( $block, 'spoilers', array() );
		$shortcode = '[su_accordion class=' . $accordion_class . ']';

		foreach ( $spoilers as $spoiler => $params ) {

			$title   = isset( $params['title'] ) ? $params['title'] : '';
			$open    = isset( $params['open'] ) ? $params['open'] : '';
			$style   = isset( $params['style'] ) ? $params['style'] : '';
			$icon    = isset( $params['icon'] ) ? $params['icon'] : '';
			$anchor  = isset( $params['anchor'] ) ? $params['anchor'] : '';
			$content = isset( $params['content'] ) ? $params['content'] : '';

			if ( is_null( $title ) ) {
				$title = __( 'Titel', 'upfront' );
			}

			if ( is_null( $open ) ) {
				$open = 'no';
			}

			if ( is_null( $style ) ) {
				$style = 'default';
			}

			if ( is_null( $icon ) ) {
				$icon = 'plus';
			}

			if ( is_null( $anchor ) ) {
				$anchor = 'none';
			}

			$shortcode .= sprintf(
				'[su_spoiler title="%s" open="%s" style="%s" icon="%s" anchor="%s" class=""]%s[/su_spoiler]',
				$title,
				$open,
				$style,
				$icon,
				$anchor,
				$content
			);
		}

		$shortcode .= '[/su_accordion]';

		$html = do_shortcode( $shortcode );

		// remove inline CSS for color.
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
	}

}

class UpFrontAccordionBlockOptions extends UpFrontBlockOptionsAPI {

	
	public $tabs;
	public $sets;
	public $inputs;
	public function __construct() {

		$this->tabs = array(
			'general' => __( 'Akkordion', 'upfront' ),
		);

		$this->sets = array();

		$this->inputs = array(
			'general' => array(
				'accordion-class' => array(
					'name'    => 'accordion-class',
					'type'    => 'text',
					'label'   => __( 'CSS Akkordeon Klasse', 'upfront' ),
					'tooltip' => __( 'Zusätzliche CSS-Klassennamen, die durch Leerzeichen getrennt sind', 'upfront' ),
				),
				'spoilers'        => array(
					'type'     => 'repeater',
					'name'     => 'spoilers',
					'label'    => __( 'Akkordeon', 'upfront' ),
					'tooltip'  => __( 'Akkordeon mit versteckten Inhalten', 'upfront' ),
					'inputs'   => array(
						array(
							'type'  => 'text',
							'name'  => 'title',
							'label' => __( 'Titel', 'upfront' ),
						),
						array(
							'type'    => 'select',
							'name'    => 'open',
							'label'   => __( 'Offen', 'upfront' ),
							'options' => array(
								'yes' => __( 'Ja', 'upfront' ),
								'no'  => __( 'Nein', 'upfront' ),
							),
							'default' => 'no',
						),
						array(
							'name'    => 'style',
							'type'    => 'select',
							'label'   => __( 'Stil', 'upfront' ),
							'default' => 'default',
							'options' => array(
								'default' => __( 'Standard', 'upfront' ),
								'fancy'   => __( 'Fancy', 'upfront' ),
								'simple'  => __( 'Einfach', 'upfront' ),
							),
							'tooltip' => __( 'Wähle den Stil für diesen Spoiler', 'upfront' ),
						),
						array(
							'name'    => 'icon',
							'type'    => 'select',
							'label'   => __( 'Symbol', 'upfront' ),
							'default' => 'plus',
							'options' => array(
								'plus'           => 'Plus',
								'plus-cicle'     => 'Plus-cicle',
								'plus-square-1'  => 'Plus-square-1',
								'plus-square-2'  => 'Plus-square-2',
								'arrow'          => 'Arrow',
								'arrow-circle-1' => 'Arrow-circle-1',
								'arrow-circle-2' => 'Arrow-circle-1e',
								'chevron'        => 'Chevron',
								'chevron-circle' => 'Chevron-circle',
								'caret'          => 'Caret',
								'caret-square'   => 'Caret-square',
								'folder-1'       => 'Folder-1',
								'folder-2'       => 'Folder-2',
							),
							'tooltip' => 'Wähle den Stil für diesen Spoiler',
						),
						array(
							'type'    => 'text',
							'name'    => 'anchor',
							'label'   => __( 'Anker', 'upfront' ),
							'tooltip' => __( 'Du kannst einen eindeutigen Anker für diese Registerkarte verwenden, um mit einem Hash in der Seiten-URL darauf zuzugreifen. Beispiel: Verwende Hallo und navigiere zu einer URL wie http://example.com/page-url#Hallo. Diese Registerkarte wird aktiviert und gescrollt. ', 'upfront' ),
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
