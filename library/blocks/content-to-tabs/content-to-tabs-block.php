<?php
/**
 * Content to Tabs Block
 */
class UpFrontBlockContentToTabs extends UpFrontBlockAPI {

	
	public $id;
	public $name;
	public $options_class;
	public $description;
	public $categories;
	public function __construct() {
		$this->id            = 'content-to-tabs';
		$this->name          = __( 'Inhalt zu Registerkarten', 'upfront' );
		$this->options_class = 'UpFrontBlockContentToTabsOptions';
		$this->description   = __( 'Ermöglicht das Teilen Ihres Inhalts durch horizontale oder vertikale Registerkarten. Du kannst festlegen, welche Registerkarte standardmäßig ausgewählt wird, und jede Registerkarte in einen Link verwandeln.', 'upfront' );
		$this->categories    = array( 'box', 'content', 'elemente' );
	}

	/**
	 * Setup Visual Editor elements.
	 */
	public function setup_elements() {
		$this->register_block_element(
			array(
				'id'       => 'tabs',
				'name'     => __( 'Registerkarten', 'upfront' ),
				'selector' => '.su-tabs',
			)
		);

		$this->register_block_element(
			array(
				'id'       => 'tabs-options',
				'name'     => __( 'Registerkarte Optionen', 'upfront' ),
				'selector' => '.su-tabs-nav',
			)
		);

		$this->register_block_element(
			array(
				'id'       => 'tabs-options-item',
				'name'     => __( 'Registerkartenoptionselement', 'upfront' ),
				'selector' => '.su-tabs-nav span',
			)
		);

		$this->register_block_element(
			array(
				'id'       => 'tab-panes',
				'name'     => __( 'Registerkarten-Panel', 'upfront' ),
				'selector' => '.su-tabs .su-tabs-panes',
			)
		);

		$this->register_block_element(
			array(
				'id'       => 'tab-panes-content',
				'name'     => __( 'Inhalt des Registerkartenbereichs', 'upfront' ),
				'selector' => '.su-tabs .su-tabs-pane',
			)
		);		

		$this->register_block_element(
			array(
				'id'       => 'tab-content-p',
				'name'     => __( 'Registerkarteninhalt Absatz', 'upfront' ),
				'selector' => '.su-tabs .su-tabs-pane p',
			)
		);

		$this->register_block_element(
			array(
				'id'       => 'tab-h1',
				'name'     => __( 'Registerkarte H1', 'upfront' ),
				'selector' => '.su-tabs .su-tabs-pane h1',
			)
		);

		$this->register_block_element(
			array(
				'id'       => 'tab-h2',
				'name'     => __( 'Registerkarte H2', 'upfront' ),
				'selector' => '.su-tabs .su-tabs-pane h2',
			)
		);

		$this->register_block_element(
			array(
				'id'       => 'tab-h3',
				'name'     => __( 'Registerkarte H3', 'upfront' ),
				'selector' => '.su-tabs .su-tabs-pane h3',
			)
		);

		$this->register_block_element(
			array(
				'id'       => 'tab-h2',
				'name'     => __( 'Registerkarte H2', 'upfront' ),
				'selector' => '.su-tabs .su-tabs-pane h2',
			)
		);

		$this->register_block_element(
			array(
				'id'       => 'tab-h4',
				'name'     => __( 'Registerkarte H4', 'upfront' ),
				'selector' => '.su-tabs .su-tabs-pane h4',
			)
		);

		$this->register_block_element(
			array(
				'id'       => 'tab-h5',
				'name'     => __( 'Registerkarte H5', 'upfront' ),
				'selector' => '.su-tabs .su-tabs-pane h5',
			)
		);

		$this->register_block_element(
			array(
				'id'       => 'tab-h6',
				'name'     => __( 'Registerkarte H6', 'upfront' ),
				'selector' => '.su-tabs .su-tabs-pane h6',
			)
		);

		$this->register_block_element(
			array(
				'id'       => 'tab-ul',
				'name'     => __( 'Registerkartenliste', 'upfront' ),
				'selector' => '.su-tabs .su-tabs-pane ul',
			)
		);

		$this->register_block_element(
			array(
				'id'       => 'tab-ol',
				'name'     => __( 'Registerkartenliste', 'upfront' ),
				'selector' => '.su-tabs .su-tabs-pane ol',
			)
		);

		$this->register_block_element(
			array(
				'id'       => 'tab-li',
				'name'     => __( 'Registerkartenelement', 'upfront' ),
				'selector' => '.su-tabs .su-tabs-pane li',
			)
		);

		$this->register_block_element(
			array(
				'id'       => 'tab-span',
				'name'     => __( 'Registerkarten-Spanne', 'upfront' ),
				'selector' => '.su-tabs .su-tabs-pane span',
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

		$vertical    = parent::get_setting( $block, 'vertical', '' );
		$tabs_class  = parent::get_setting( $block, 'tabs-class', '' );
		$item_class  = parent::get_setting( $block, 'item-class', '' );
		$style       = parent::get_setting( $block, 'style', array() );
		$icon        = parent::get_setting( $block, 'icon', '' );
		$active      = parent::get_setting( $block, 'active', 0 );
		$post_link   = parent::get_setting( $block, 'post_link', '' );
		$item_target = parent::get_setting( $block, 'item-target', '' );

		$posts = \UpFrontQuery::get_posts( $block );

		$shortcode = '[su_tabs class="' . $tabs_class . '" ';

		if ( 'yes' === $vertical ){
			$shortcode .= 'vertical="yes" ';
		}

		if ( ! $active || $active < 1 || ! is_numeric( $active ) ){
			$active = '1';
		}

		if ( $style ) {
			$shortcode .= 'style="' . $style . '" ';
		}

		$shortcode .= 'active="' . $active . '" ';
		$shortcode .= ']';

		foreach ( $posts as $key => $post ) {

			$id     = $post->ID;
			$image  = get_the_post_thumbnail_url( $post->ID );
			$desc   = $post->post_excerpt;
			$title  = $post->post_title;
			$url    = get_post_permalink( $post->ID );
			$date   = date( 'M d, Y', strtotime( $post->post_date ) );
			$author = get_the_author_meta( 'display_name', $post->post_author );

			// Open Tab.
			$shortcode .= '[su_tab title="' . $title . '" ';
			$shortcode .= 'anchor="' . $title . '" class="' . $item_class . '" ';

			if ( $post_link ) {
				$shortcode .= 'url="' . get_permalink( $id ) . '" ';
			}

			$shortcode .= 'target="' . $item_target . '"]';

			// Content.
			$shortcode .= $post->post_content;

			// Close Tab.
			$shortcode .= '[/su_tab]';

		}

		$shortcode .= '[/su_tabs]';
		echo do_shortcode( $shortcode );
	}

}
class UpFrontBlockContentToTabsOptions extends UpFrontBlockOptionsAPI {

	
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
				'vertical'    => array(
					'name'    => 'vertical',
					'label'   => __( 'Vertikal', 'upfront' ),
					'type'    => 'select',
					'default' => 'no',
					'options' => array(
						'yes' => __( 'Ja', 'upfront' ),
						'no'  => __( 'Nein', 'upfront' ),
					),
					'tooltip' => __( 'Richte Registerkarten vertikal aus', 'upfront' ),
				),

				'tabs-class'  => array(
					'name'    => 'tabs-class',
					'type'    => 'text',
					'label'   => __( 'CSS-Klasse', 'upfront' ),
					'tooltip' => __( 'Zusätzliche CSS-Klassennamen, die durch Leerzeichen getrennt sind', 'upfront' ),
				),

				'style'       => array(
					'name'    => 'style',
					'type'    => 'select',
					'label'   => __( 'Stil', 'upfront' ),
					'default' => 'default',
					'options' => array(
						'default'       => 'Standard',
						'carbon'        => 'Karbon',
						'sharp'         => 'Scharf',
						'grid'          => 'Raster',
						'wood'          => 'Holz',
						'fabric'        => 'Fabrik',
						'modern-dark'   => 'Modern: Dunkel',
						'modern-light'  => 'Modern: Hell',
						'modern-blue'   => 'Modern: Blau',
						'modern-orange' => 'Modern: Orange',
						'flat-dark'     => 'Flat: Dunkel',
						'flat-light'    => 'Flat: Hell',
						'flat-blue'     => 'Flat: Blau',
						'flat-green'    => 'Flat: Grün',
					),
					'tooltip' => __( 'Wähle den Stil für diese Registerkarten', 'upfront' ),
				),

				'active'      => array(
					'name'    => 'active',
					'type'    => 'integer',
					'label'   => __( 'Aktiv (1-100)', 'upfront' ),
					'default' => 1,
					'tooltip' => __( 'Wähle aus welche Registerkarte standardmäßig geöffnet ist', 'upfront' ),
				),

				'post-link'   => array(
					'type'    => 'checkbox',
					'name'    => 'url',
					'label'   => __( 'Beitrags-Link aktivieren', 'upfront' ),
					'tooltip' => __( 'Registerkarte "Link" zu einer beliebigen Webseite. Verwende die vollständige URL, um den Tabulatortitel in einen Link umzuwandeln', 'upfront' ),
					'default' => false,
				),
				'item-target' => array(
					'name'    => 'item-target',
					'type'    => 'select',
					'default' => 'self',
					'options' => array(
						'self'  => __( 'Öffne in derselben Registerkarte', 'upfront' ),
						'blank' => __( 'In neuem Tab öffnen', 'upfront' ),
					),
					'label'   => __( 'Ziel', 'upfront' ),
					'tooltip' => __( 'Wähle aus wie der benutzerdefinierte Registerkarten-Link geöffnet werden soll', 'upfront' ),
				),

				'item-class'  => array(
					'name'    => 'item-class',
					'type'    => 'text',
					'label'   => __( 'CSS-Klasse für die Artikel', 'upfront' ),
					'tooltip' => __( 'Zusätzliche CSS-Klassennamen, die durch Leerzeichen getrennt sind', 'upfront' ),
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
					'tooltip' => __( 'Aktiviere diese Option, damit der Tag-Filter angezeigt wird.', 'upfront' ),
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
					'tooltip' => __( 'Sowohl beim Einschließen als auch beim Ausschließen nach ID verwende eine durch Kommas getrennte Liste von IDs Deines Beitragstyps . ', 'upfront' ),
				),

				'byid-exclude'    => array(
					'type'    => 'text',
					'name'    => 'byid-exclude',
					'label'   => __( 'Nach ID ausschließen', 'upfront' ),
					'tooltip' => __( 'Sowohl beim Einschließen als auch beim Ausschließen nach ID verwende eine durch Kommas getrennte Liste von IDs Deines Beitragstyps . ', 'upfront' ),
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
