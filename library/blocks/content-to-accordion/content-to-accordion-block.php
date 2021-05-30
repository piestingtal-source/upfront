<?php

class UpFrontContentToAccordionBlock extends UpFrontBlockAPI {

	
	public $id;
	public $name;
	public $options_class;
	public $description;
	public $categories;
	public function __construct() {

		$this->id            = 'content-to-accordion';
		$this->name          = __( 'Content zu Akkordeon', 'upfront' );
		$this->options_class = 'UpFrontContentToAccordionBlockOptions';
		$this->description   = __( 'Ermöglicht das Erstellen von Blöcken mit Inhalten für versteckte Beiträge. Versteckte Inhalte werden angezeigt, wenn auf den Blocktitel geklickt wird. Du kannst für jeden Spoiler unterschiedliche Symbole angeben oder sogar unterschiedliche Stile verwenden. ', 'upfront' );
		$this->categories    = array( 'box', 'elemente' );

	}

	/**
	 * Init
	 */
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
				'id'       => 'accordion',
				'name'     => __( 'Akkordeon', 'upfront' ),
				'selector' => '.su-accordion',
			)
		);

		$this->register_block_element(
			array(
				'id'       => 'spoiler',
				'name'     => __( 'Spoiler', 'upfront' ),
				'selector' => '.su-accordion .su-spoiler',
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
				'name'     => __( 'Spoiler-Symbol', 'upfront' ),
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
				'name'     => 'Spoilerliste',
				'selector' => '.su-accordion .su-spoiler-content ol',
			)
		);

		$this->register_block_element(
			array(
				'id'       => 'spoiler-li',
				'name'     => 'Spoiler Listenelement',
				'selector' => '.su-accordion .su-spoiler-content li',
			)
		);

		$this->register_block_element(
			array(
				'id'       => 'spoiler-span',
				'name'     => 'Spoiler Spanne',
				'selector' => '.su-accordion .su-spoiler-content span',
			)
		);

		$this->register_block_element(
			array(
				'id'       => 'spoiler-a',
				'name'     => 'Spoiler Link',
				'selector' => '.su-accordion .su-spoiler-content a',
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

		$accordion_class = parent::get_setting( $block, 'accordion-class', '' );
		$item_class      = parent::get_setting( $block, 'item-class', '' );
		$style           = parent::get_setting( $block, 'style', 'default' );
		$icon            = parent::get_setting( $block, 'icon', '' );
		$open            = parent::get_setting( $block, 'open', 0 );

		$posts = \UpFrontQuery::get_posts( $block );

		$shortcode = '[su_accordion class="' . $accordion_class . '"]';

		$open_item = 1;
		foreach ( $posts as $key => $post ) {

			$id     = $post->ID;
			$image  = get_the_post_thumbnail_url( $post->ID );
			$desc   = $post->post_excerpt;
			$title  = apply_filters( 'upfront_content_to_accordion_title', $post->post_title, $post->ID );
			$url    = get_post_permalink( $post->ID );
			$date   = date( 'M d, Y', strtotime( $post->post_date ) );
			$author = get_the_author_meta( 'display_name', $post->post_author );

			// Open Spoiler.
			$shortcode .= '[su_spoiler title="' . $title . '" ';

			if ( $open_item === $open ) {
				$shortcode .= 'open="yes" ';
			} else {
				$shortcode .= 'open="no" ';
			}
			$shortcode .= 'style="' . $style . '" icon="' . $icon . '" anchor="' . $title . '" class="' . $item_class . '"]';

			// Content.
			$shortcode .= $post->post_content;

			// Close Spoiler.
			$shortcode .= '[/su_spoiler]';

			$open_item++;

		}

		$shortcode .= '[/su_accordion]';

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

class UpFrontContentToAccordionBlockOptions extends UpFrontBlockOptionsAPI {


	public $tabs;
	public $sets;
	public $inputs;
	public function __construct() {

		$this->tabs = array(
			'general'       => __( 'Allgemeines', 'upfront' ),
			'query-filters' => __( 'Abfragefilter', 'upfront' ),
		);

		$this->sets = array();

		$this->inputs = array(
			'general'       => array(
				'accordion-class' => array(
					'name'    => 'accordion-class',
					'type'    => 'text',
					'label'   => __( 'CSS-Klasse', 'upfront' ),
					'tooltip' => __( 'Zusätzliche CSS-Klassennamen, die durch Leerzeichen getrennt sind', 'upfront' ),
				),

				'item-class'      => array(
					'name'    => 'item-class',
					'type'    => 'text',
					'label'   => __( 'CSS-Klasse für die Elemente', 'upfront' ),
					'tooltip' => __( 'Zusätzliche CSS-Klassennamen, die durch Leerzeichen getrennt sind', 'upfront' ),
				),

				'style'           => array(
					'name'    => 'style',
					'type'    => 'select',
					'label'   => __( 'Stil', 'upfront' ),
					'default' => 'default',
					'options' => array(
						'default' => 'Standard',
						'fancy'   => 'Fancy',
						'simple'  => 'Einfach',
					),
					'tooltip' => __( 'Wähle den Stil für diesen Spoiler', 'upfront' ),
				),
				'icon'            => array(
					'name'    => 'icon',
					'type'    => 'select',
					'label'   => __( 'Symbol', 'upfront' ),
					'default' => 'plus',
					'options' => array(
						''               => '',
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
					'tooltip' => __( 'Wähle den Stil für diesen Spoiler', 'upfront' ),
				),
				'open'            => array(
					'name'    => 'open',
					'type'    => 'integer',
					'label'   => __( 'Standardmäßig geöffnetes Element', 'upfront' ),
					'default' => 0,
					'tooltip' => __( 'Spoiler-Element standardmäßig geöffnet', 'upfront' ),
				),
			),

			'query-filters' => array(

				'categories'      => array(
					'type'    => 'multi-select',
					'name'    => 'categories',
					'label'   => __( 'Kategorien', 'upfront' ),
					'tooltip' => '',
					'options' => 'get_categories()',
				),

				'categories-mode' => array(
					'type'    => 'select',
					'name'    => 'categories-mode',
					'label'   => __( 'Kategorienmodus', 'upfront' ),
					'tooltip' => '',
					'options' => array(
						'include' => __( 'Einschließen', 'upfront' ),
						'exclude' => __( 'Ausschließen', 'upfront' ),
					),
				),

				'enable-tags'     => array(
					'type'    => 'checkbox',
					'name'    => 'tags-filter',
					'label'   => __( 'Tags Filter', 'upfront' ),
					'tooltip' => __( 'Aktiviere diese Option, damit der Tag-Filter angezeigt wird. ', 'upfront' ),
					'default' => false,
					'toggle'  => array(
						'false' => array(
							'hide' => array(
								'#input-tags',
							),
						),
						'true'  => array(
							'show' => array(
								'#input-tags',
							),
						),
					),
				),

				'tags'            => array(
					'type'    => 'multi-select',
					'name'    => 'tags',
					'label'   => __( 'Tags', 'upfront' ),
					'tooltip' => '',
					'options' => 'get_tags()',
				),

				'post-type'       => array(
					'type'     => 'multi-select',
					'name'     => 'post-type',
					'label'    => __( 'Beitragstyp', 'upfront' ),
					'tooltip'  => '',
					'options'  => 'get_post_types()',
					'callback' => 'reloadBlockOptions()',
				),

				'post-status'     => array(
					'type'    => 'multi-select',
					'name'    => 'post-status',
					'label'   => __( 'Beitragsstatus', 'upfront' ),
					'tooltip' => '',
					'options' => 'get_post_status()',
				),

				'author'          => array(
					'type'    => 'multi-select',
					'name'    => 'author',
					'label'   => __( 'Autor', 'upfront' ),
					'tooltip' => '',
					'options' => 'get_authors()',
				),

				'number-of-posts' => array(
					'type'    => 'integer',
					'name'    => 'number-of-posts',
					'label'   => __( 'Anzahl der Beiträge', 'upfront' ),
					'tooltip' => '',
					'default' => 10,
				),

				'offset'          => array(
					'type'    => 'integer',
					'name'    => 'offset',
					'label'   => __( 'Versatz', 'upfront' ),
					'tooltip' => __( 'Der Versatz ist die Anzahl der Einträge oder Beiträge, die Du überspringen möchtest. Wenn der Versatz 1 ist, wird der erste Beitrag übersprungen. ', 'upfront' ),
					'default' => 0,
				),

				'order-by'        => array(
					'type'    => 'select',
					'name'    => 'order-by',
					'label'   => __( 'Sortieren nach', 'upfront' ),
					'tooltip' => __( 'Sortieren nach', 'upfront' ),
					'options' => array(
						'date'          => __( 'Datum', 'upfront' ),
						'title'         => __( 'Titel', 'upfront' ),
						'rand'          => __( 'Zufall', 'upfront' ),
						'comment_count' => __( 'Anzahl Kommentare', 'upfront' ),
						'ID'            => __( 'ID', 'upfront' ),
						'author'        => __( 'Autor', 'upfront' ),
						'type'          => __( 'Beitragstyp', 'upfront' ),
						'menu_order'    => __( 'Definierte Sortierung', 'upfront' ),
					),
				),

				'order'           => array(
					'type'    => 'select',
					'name'    => 'order',
					'label'   => __( 'Sortierung', 'upfront' ),
					'tooltip' => '',
					'options' => array(
						'desc' => __( 'Absteigend', 'upfront' ),
						'asc'  => __( 'Aufsteigend', 'upfront' ),
					),
				),

				'byid-include'    => array(
					'type'    => 'text',
					'name'    => 'byid-include',
					'label'   => __( 'Nach ID einschließen', 'upfront' ),
					'tooltip' => __( 'Sowohl beim Einschließen als auch beim Ausschließen nach ID verwende eine durch Kommas getrennte Liste von IDs Deines Beitragstyps. ', 'upfront' ),
				),

				'byid-exclude'    => array(
					'type'    => 'text',
					'name'    => 'byid-exclude',
					'label'   => __( 'Nach ID ausschließen', 'upfront' ),
					'tooltip' => __( 'Sowohl beim Einschließen als auch beim Ausschließen nach ID verwende eine durch Kommas getrennte Liste von IDs Deines Beitragstyps. ', 'upfront' ),
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

	/**
	 * Get posts categories
	 *
	 * @return array
	 */
	public function get_categories() {
		if ( isset( $this->block['settings']['post-type'] ) ) {
			return \UpFrontQuery::get_categories( $this->block['settings']['post-type'] );
		} else {
			return array();
		}
	}

	/**
	 * Get Tags
	 *
	 * @return array
	 */
	public function get_tags() {
		return \UpFrontQuery::get_tags();
	}

	/**
	 * Get Authors
	 *
	 * @return array
	 */
	public function get_authors() {
		return \UpFrontQuery::get_authors();
	}

	/**
	 * Get Post types
	 *
	 * @return array
	 */
	public function get_post_types() {
		return \UpFrontQuery::get_post_types();
	}

	/**
	 * Get taxonomies
	 *
	 * @return array
	 */
	public function get_taxonomies() {
		return \UpFrontQuery::get_taxonomies();
	}

	/**
	 * Get posts status
	 *
	 * @return array
	 */
	public function get_post_status() {
		return \UpFrontQuery::get_post_status();
	}
}
