<?php

/**
 * PortfolioCards Block
 */
class UpFrontBlockPortfolioCards extends UpFrontBlockAPI {

	
	public $id;
	public $name;
	public $options_class;
	public $description;
	public $categories;
	public function __construct() {
		$this->id            = 've-portfolio-cards';	
		$this->name          = __( 'Portfolio-Karten', 'upfront' );
		$this->options_class = 'UpFrontBlockPortfolioCardsOptions';	
		$this->description   = __( 'Ermöglicht das Erstellen von Blöcken mit versteckten Posts. Versteckte Inhalte werden angezeigt, wenn auf den Blocktitel geklickt wird. Du kannst für jeden Spoiler unterschiedliche Symbole angeben oder sogar unterschiedliche Stile verwenden.', 'upfront' );
		$this->categories    = array( 'box', 'content', 'elemente' );
	}

	/**
	 * Add scripts to admin
	 *
	 * @return void
	 */
	public static function portfolio_admin_scripts() {

		

		/* JS */
		wp_enqueue_script( 'upfront-ve-portfolio-cards', upfront_url() . '/library/blocks/portfolio-cards/js/portfolio-cards.js', array( 'jquery' ), UPFRONT_VERSION, true );

	}

	/**
	 * UpFront Content Method
	 *
	 * @param object $block Block.
	 * @return void
	 */
	public function content( $block ) {

		$html_content        = '';
		$portfolio_classes   = '';
		$columns 			 = parent::get_setting( $block, 'columns', 4 );
		$content_to_show     = parent::get_setting( $block, 'content-to-show', 'excerpt' );
		$post_type           = ( isset( $block['settings']['post-type'] ) ) ? $block['settings']['post-type'] : ['post'];
		$posts               = \UpFrontQuery::get_posts( $block );
		$categories_in_posts = array();

		$custom_length        = ( ! empty( $block['settings']['custom-length'] ) ) ? $block['settings']['custom-length'] : 'no';
		$custom_length_number = ( ! empty( $block['settings']['custom-length-number'] ) ) ? $block['settings']['custom-length-number'] : 15;

		// Columns.
		$portfolio_classes .= 'portfolio-' . $columns;

		$html_content .= '<div id="portfolio-cards" class="portfolio-cards">';

		$alt_counter = 1;
		foreach ( $posts as $key => $post ) {

			$id     = $post->ID;
			$image  = get_the_post_thumbnail_url( $post->ID );
			$desc   = $post->post_excerpt;
			$title  = $post->post_title;
			$url    = get_permalink( $post->ID );
			$date   = date( 'M d, Y', strtotime( $post->post_date ) );
			$author = get_the_author_meta( 'display_name', $post->post_author );

			switch ( $content_to_show ) {

				case 'content':
					$shortcode = $post->post_content;
					break;

				case 'excerpt':
					$shortcode = $post->post_excerpt;
					break;

				case 'none':
					$shortcode = '';
					break;

				default:
					$shortcode = $post->post_content;
					break;
			}

			if ( 'yes' === $custom_length ) {
				$content = wp_trim_words( do_shortcode( $shortcode ), $custom_length_number );
			} else {
				$content = do_shortcode( $shortcode );
			}

			// Categories.
			$item_classes = '';

			if ( 'product' === $post_type || in_array( 'product', $post_type, true ) ) {
				$post_categories = wp_get_post_terms( $id, 'product_cat' );
			} else {
				$post_categories = get_the_category( $id );
			}

			// save categories ids to use later.
			foreach ( $post_categories as $key => $term ) {
				$categories_in_posts[] = $term->term_id;
			}

			foreach ( $post_categories as $key => $category ) {
				$item_classes .= 'pf-' . $category->slug;
			}

			++$alt_counter;

			$html_content .= '<div class="' . $portfolio_classes . '">';
			$html_content .= '	<a class="portfolio-item" href="' . $url . '" style="background-image:url(' . $image . ')">';
			$html_content .= '		<span class="caption">';
			$html_content .= '      	<span class="caption-content">';
			$html_content .= '        		<h2>' . $title . '</h2>';
			$html_content .= '        		<p class="">' . $content . '</p>';
			$html_content .= '      	</span>';
			$html_content .= '    	</span>';
			$html_content .= '  </a>';
			$html_content .= '</div>';

		}

		$html_content .= '</div>';

		echo $html_content;
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

		/* JS */
		wp_enqueue_script( 'upfront-ve-portfolio-cards', upfront_url() . '/library/blocks/portfolio-cards/js/portfolio-cards.js', array( 'jquery' ), UPFRONT_VERSION, true );

		/* CSS */
		wp_enqueue_style( 'upfront-ve-portfolio-cards', upfront_url() . '/library/blocks/portfolio-cards/css/portfolio-cards.css', array(), UPFRONT_VERSION, 'all' );
	}

}
class UpFrontBlockPortfolioCardsOptions extends UpFrontBlockOptionsAPI {

	
	public $tabs;
	public $sets;
	public $inputs;
	public function __construct() {

		$this->tabs = array(
			'general'       => __( 'Portfolio-Karten', 'upfront' ),
			'query-filters' => __( 'Abfragefilter', 'upfront' ),
		);

		$this->sets = array();

		$this->inputs = array(

			'general'       => array(

				'columns' => array(
					'type'       => 'slider',
					'name'       => 'columns',
					'label'      => __( 'Spalten', 'upfront' ),
					'tooltip'    => __( 'Anzahl der Portfolio-Spalten.', 'upfront' ),
					'unit'       => null,
					'default'    => 4,
					'slider-min' => 1,
					'slider-max' => 6,
					'toggle'     => array(
						'1' => array(
							'show' => array(
								'#input-alternate-content',
								'#input-full-width-image',
								'#input-show-open-button',
								'#input-open-button-text',
							),
							'hide' => array(
								'#input-title-overlay',
								'#input-show-margin',
							),

						),
						'2' => array(
							'show' => array(
								'#input-title-overlay',
								'#input-show-margin',
							),
							'hide' => array(
								'#input-alternate-content',
								'#input-full-width-image',
								'#input-show-open-button',
								'#input-open-button-text',
							),
						),
						'3' => array(
							'show' => array(
								'#input-title-overlay',
								'#input-show-margin',
							),
							'hide' => array(
								'#input-alternate-content',
								'#input-full-width-image',
								'#input-show-open-button',
								'#input-open-button-text',
							),
						),
						'4' => array(
							'show' => array(
								'#input-title-overlay',
								'#input-show-margin',
							),
							'hide' => array(
								'#input-alternate-content',
								'#input-full-width-image',
								'#input-show-open-button',
								'#input-open-button-text',
							),
						),
						'5' => array(
							'show' => array(
								'#input-title-overlay',
								'#input-show-margin',
							),
							'hide' => array(
								'#input-alternate-content',
								'#input-full-width-image',
								'#input-show-open-button',
								'#input-open-button-text',
							),
						),
						'6' => array(
							'show' => array(
								'#input-title-overlay',
								'#input-show-margin',
							),
							'hide' => array(
								'#input-alternate-content',
								'#input-full-width-image',
								'#input-show-open-button',
								'#input-open-button-text',
							),
						),
					),
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
