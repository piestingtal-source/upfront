<?php

class UpFrontPortfolioBlocks extends \UpFrontBlockAPI {

	public $id;
	public $name;
	public $options_class;
	public $description;
	public $categories;
	public function __construct() {
		$this->id            = 'portfolio';
		$this->name          = __( 'Portfolio', 'upfront' );
		$this->options_class = 'UpFrontPortfolioBlocksOptions';
		$this->description   = __( 'Ermöglicht das Erstellen von Blöcken mit versteckten Posts. Versteckte Inhalte werden angezeigt, wenn auf den Blocktitel geklickt wird. Du kannst für jeden Spoiler unterschiedliche Symbole angeben oder sogar unterschiedliche Stile verwenden.', 'upfront' );
		$this->categories    = array( 'box', 'content', 'elemente' );
	}



	/**
	 * Add scripts to admin
	 *
	 * @return void
	 */
	public static function portfolio_admin_scripts() {

		//$path = str_replace( '/blocks', '', plugin_dir_url( __FILE__ ) );

		// JS.
		wp_enqueue_script( 'upfront-isotope', upfront_url() . '/library/blocks/portfolio/js/isotope.js', array( 'jquery' ), UPFRONT_VERSION, true );
		wp_enqueue_script( 'upfront-portfolio', upfront_url() . '/library/blocks/portfolio/js/portfolio.js', array( 'jquery', 'upfront-ve-isotope' ), UPFRONT_VERSION, true );

	}

	/**
	 * Setup Visual Editor elements.
	 */
	public function setup_elements() {

		$this->register_block_element(
			array(
				'id'       => 'portfolio-filter',
				'name'     => __( 'Filter', 'upfront' ),
				'selector' => '.portfolio-filter',
			)
		);

		$this->register_block_element(
			array(
				'id'       => 'portfolio-filter-item',
				'parent'   => 'portfolio-filter',
				'name'     => __( 'Element filtern', 'upfront' ),
				'selector' => '.portfolio-filter li',
			)
		);

		$this->register_block_element(
			array(
				'id'       => 'portfolio-filter-link',
				'parent'   => 'portfolio-filter',
				'name'     => __( 'Link filtern', 'upfront' ),
				'selector' => '.portfolio-filter li a',
				'states'   => array(
					'Hover'   => '.portfolio-filter li a:hover',
					'Clicked' => '.portfolio-filter li a:active',
				),
			)
		);

		$this->register_block_element(
			array(
				'id'       => 'portfolio-active-item',
				'parent'   => 'portfolio-filter',
				'name'     => __( 'Aktives Element', 'upfront' ),
				'selector' => '.portfolio-filter li.activeFilter a',			
			)
		);

		$this->register_block_element(
			array(
				'id'       => 'portfolio',
				'name'     => __( 'Portfolio', 'upfront' ),
				'selector' => '.portfolio',
			)
		);

		$this->register_block_element(
			array(
				'id'       => 'article',
				'parent'   => 'portfolio',
				'name'     => __( 'Artikel', 'upfront' ),
				'selector' => '.portfolio article.portfolio-item',
			)
		);

		$this->register_block_element(
			array(
				'id'       => 'image',
				'parent'   => 'portfolio',
				'name'     => __( 'Bild', 'upfront' ),
				'selector' => '.portfolio .portfolio-image',
			)
		);

		$this->register_block_element(
			array(
				'id'       => 'left-icon',
				'parent'   => 'portfolio',
				'name'     => __( 'Linkes Symbol', 'upfront' ),
				'selector' => '.portfolio .portfolio-image .left-icon',
			)
		);

		$this->register_block_element(
			array(
				'id'       => 'right-icon',
				'parent'   => 'portfolio',
				'name'     => __( 'Rechtes Symbol', 'upfront' ),
				'selector' => '.portfolio .portfolio-image .right-icon',
			)
		);

		$this->register_block_element(
			array(
				'id'       => 'portfolio-desc',
				'parent'   => 'portfolio',
				'name'     => __( 'Beschreibung Container', 'upfront' ),
				'selector' => '.portfolio .portfolio-desc',
			)
		);

		$this->register_block_element(
			array(
				'id'       => 'title',
				'parent'   => 'portfolio',
				'name'     => __( 'Artikelüberschrift', 'upfront' ),
				'selector' => '.portfolio .portfolio-desc h3',
			)
		);

		$this->register_block_element(
			array(
				'id'       => 'content',
				'parent'   => 'portfolio',
				'name'     => __( 'Inhalt', 'upfront' ),
				'selector' => '.portfolio .portfolio-desc .description',
			)
		);

		$this->register_block_element(
			array(
				'id'       => 'content-h1',
				'parent'   => 'portfolio',
				'name'     => __( 'Inhalt H1', 'upfront' ),
				'selector' => '.portfolio .portfolio-desc .description h1',
			)
		);

		$this->register_block_element(
			array(
				'id'       => 'content-h2',
				'parent'   => 'portfolio',
				'name'     => __( 'Inhalt H2', 'upfront' ),
				'selector' => '.portfolio .portfolio-desc .description h2',
			)
		);

		$this->register_block_element(
			array(
				'id'       => 'content-h3',
				'parent'   => 'portfolio',
				'name'     => __( 'Inhalt H3', 'upfront' ),
				'selector' => '.portfolio .portfolio-desc .description h3',
			)
		);

		$this->register_block_element(
			array(
				'id'       => 'content-h4',
				'parent'   => 'portfolio',
				'name'     => __( 'Inhalt H4', 'upfront' ),
				'selector' => '.portfolio .portfolio-desc .description h4',
			)
		);

		$this->register_block_element(
			array(
				'id'       => 'content-h5',
				'parent'   => 'portfolio',
				'name'     => __( 'Inhalt H5', 'upfront' ),
				'selector' => '.portfolio .portfolio-desc .description h5',
			)
		);

		$this->register_block_element(
			array(
				'id'       => 'content-h6',
				'parent'   => 'portfolio',
				'name'     => __( 'Inhalt H6', 'upfront' ),
				'selector' => '.portfolio .portfolio-desc .description h6',
			)
		);

		$this->register_block_element(
			array(
				'id'       => 'content-p',
				'parent'   => 'portfolio',
				'name'     => __( 'Inhalt p', 'upfront' ),
				'selector' => '.portfolio .portfolio-desc .description p',
			)
		);

		$this->register_block_element(
			array(
				'id'       => 'content-a',
				'parent'   => 'portfolio',
				'name'     => __( 'Inhalt a', 'upfront' ),
				'selector' => '.portfolio .portfolio-desc .description a',
			)
		);

		$this->register_block_element(
			array(
				'id'       => 'content-ul',
				'parent'   => 'portfolio',
				'name'     => __( 'Inhalt ul', 'upfront' ),
				'selector' => '.portfolio .portfolio-desc .description ul',
			)
		);

		$this->register_block_element(
			array(
				'id'       => 'content-ul-li',
				'parent'   => 'portfolio',
				'name'     => __( 'Inhalt ul li', 'upfront' ),
				'selector' => '.portfolio .portfolio-desc .description ul li',
			)
		);

		$this->register_block_element(
			array(
				'id'       => 'button',
				'parent'   => 'portfolio',
				'name'     => __( 'Schaltfläche', 'upfront' ),
				'selector' => '.portfolio .portfolio-desc .button',
				'states'   => array(
					'Hover'   => '.portfolio .portfolio-desc .button:hover',
					'Clicked' => '.portfolio .portfolio-desc .button:active',
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

		$html         = '';
		$html_filter  = '';
		$html_content = '';

		$portfolio_classes = '';
		$columns           = parent::get_setting( $block, 'columns', 4 );
		$show_filter       = parent::get_setting( $block, 'show-filter', 'no' );
		$filter_style      = parent::get_setting( $block, 'filter-style', 'style-1' );
		$show_all_text     = parent::get_setting( $block, 'show-all-text', 'Show all' );
		$show_margin       = parent::get_setting( $block, 'show-margin', 'yes' );
		$alternate_content = parent::get_setting( $block, 'alternate-content', 'yes' );
		$full_width_image  = parent::get_setting( $block, 'full-width-image', 'no' );
		$content_to_show   = parent::get_setting( $block, 'content-to-show', 'excerpt' );
		$mode              = parent::get_setting( $block, 'mode', 'masonry' );
		$title_overlay     = parent::get_setting( $block, 'title-overlay', 'no' );
		$show_open_button  = parent::get_setting( $block, 'show-open-button', 'no' );
		$open_button_text  = parent::get_setting( $block, 'open-button-text', 'Open article' );

		$only_categories_with_posts	= parent::get_setting( $block, 'only-categories-with-posts', 'no' );

		$post_type           = ( isset( $block['settings']['post-type'] ) ) ? $block['settings']['post-type'] : 'post';
		$posts               = \UpFrontQuery::get_posts( $block );
		$categories_in_posts = array();

		// Columns.
		$portfolio_classes .= 'portfolio-' . $columns;

		// Full Width.
		if ( 'yes' === $full_width_image ) {
			$portfolio_classes .= ' portfolio-fullwidth';
		}

		// Layout mode.
		$data_atts = 'data-layout="' . $mode . '"';

		// No margin.
		if ( 'yes' !== $show_margin ) {
			$portfolio_classes .= ' portfolio-nomargin';
		}

		$html_content .= '<div id="portfolio" class="portfolio  ' . $portfolio_classes . ' grid-container clearfix" ' . $data_atts . '>';

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
				$item_classes .= ' pf-' . $category->slug;
			}

			// Alternate content.
			if ( 'yes' === $alternate_content && ( 0 === $alt_counter % 2 ) && $alt_counter > 1 ) {
				$item_classes .= ' alt';
			}
			++$alt_counter;

			/**
			 * Description structure
			 */
			$description  = '<div class="portfolio-desc">';
			$description .= '<h3><a href="' . $url . '">' . $title . '</a></h3>';

			if ( 'no' === $title_overlay || 1 === $columns ) {
				$description .= '<div class="description">' . do_shortcode( $shortcode ) . '</div>';
			}

			if ( 1 === $columns && 'yes' === $show_open_button ) {
				$description .= '<a href="' . $url . '" class="button button-3d noleftmargin">' . $open_button_text . '</a>';
			}

			$description .= '	</div>';

			/**
			 * Article structure
			 */
			$html_content .= '<article class="portfolio-item' . $item_classes . '">';
			$html_content .= '	<div class="portfolio-image">';
			$html_content .= '		<a href="' . $url . '">';
			$html_content .= '			<img src="' . $image . '" alt="Open Imagination">';
			$html_content .= '		</a>';
			$html_content .= '		<div class="portfolio-overlay">';

			if ( 'yes' === $title_overlay && $columns > 1 ) {
				$html_content .= $description;
			}

			$html_content .= '			<a href="' . $image . '" class="left-icon" data-lightbox="image"><i class="fas fa-plus"></i></a>';
			$html_content .= '			<a href="' . $url . '" class="right-icon"><i class="fas fa-ellipsis-h"></i></a>';
			$html_content .= '		</div>';
			$html_content .= '	</div>';

			if ( 'no' === $title_overlay || 1 === $columns ) {
				$html_content .= $description;
			}

			$html_content .= '</article>';

		}
		$html_content .= '</div>';

		/**
		 * Filter
		 */
		if ( 'yes' === $show_filter ) {

			$html_filter  = '<ul class="portfolio-filter ' . $filter_style . ' clearfix" data-container="#portfolio">';
			$html_filter .= '<li class="activeFilter"><a href="#" data-filter="*">' . $show_all_text . '</a></li>';

			$categories_mode = parent::get_setting( $block, 'categories-mode', 'include' );
			$categories      = parent::get_setting( $block, 'categories', array() );

			foreach ( \UpFrontQuery::get_categories( $post_type ) as $category_id => $category ) {

				if ( 'yes' === $only_categories_with_posts && ! in_array( $category_id, $categories_in_posts, true ) ) {
					continue;
				}

				if ( count( $categories ) > 0 ) {

					if ( ! in_array( $category_id, $categories, true ) && 'include' === $categories_mode ) {
						continue;
					}

					if ( in_array( $category_id, $categories, true ) && 'exclude' === $categories_mode ) {
						continue;
					}
				}

				$action_text  = strtolower( $category );
				$action_text  = preg_replace( '/\s+/', '-', $action_text );
				$html_filter .= '<li><a data-filter=".pf-' . $action_text . '">' . $category . '</a></li>';

			}

			$html_filter .= '</ul>';
			$html_filter .= '<div class="clear"></div>';

		}

		$html .= $html_filter;
		$html .= $html_content;

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


		/* JS */
		wp_enqueue_script( 'upfront-isotope', upfront_url() . '/library/blocks/portfolio/js/isotope.js', array( 'jquery' ), UPFRONT_VERSION, true );
		wp_enqueue_script( 'upfront-magnific', upfront_url() . '/library/blocks/portfolio/js/jquery.magnific.js', array( 'jquery' ), UPFRONT_VERSION, true );
		wp_enqueue_script( 'upfront-portfolio', upfront_url() . '/library/blocks/portfolio/js/portfolio.js', array( 'jquery', 'upfront-ve-isotope' ), UPFRONT_VERSION, true );

		/* CSS */
		wp_enqueue_style( 'upfront-magnific', upfront_url() . '/library/blocks/portfolio/css/magnific-popup.css', array(), UPFRONT_VERSION, 'all' );
		wp_enqueue_style( 'upfront-portfolio', upfront_url() . '/library/blocks/portfolio/css/portfolio.css', array(), UPFRONT_VERSION, 'all' );
		wp_enqueue_style( 'upfront-fontawesome', upfront_url() . '/library/blocks/portfolio/css/fontawesome.css', array(), UPFRONT_VERSION, 'all' );
	}
}
/**
 * Options class for block
 */
class UpFrontPortfolioBlocksOptions extends UpFrontBlockOptionsAPI {

	
	public $tabs;
	public $sets;
	public $inputs;
	public function __construct() {

		$this->tabs = array(
			'general'       => __( 'Portfolio', 'upfront' ),
			'query-filters' => __( 'Abfragefilter', 'upfront' ),
		);

		$this->sets = array();

		$this->inputs = array(

			'general'       => array(

				'columns'                    => array(
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

				'show-filter'                => array(
					'name'    => 'show-filter',
					'label'   => __( 'Filter anzeigen', 'upfront' ),
					'type'    => 'select',
					'default' => 'no',
					'options' => array(
						'yes' => __( 'Ja', 'upfront' ),
						'no'  => __( 'Nein', 'upfront' ),
					),
					'tooltip' => __( 'Filter anzeigen', 'upfront' ),
					'toggle'  => array(
						'yes' => array(
							'show' => array(
								'#input-filter-style',
								'#input-show-all-text',
							),
						),
						'no'  => array(
							'hide' => array(
								'#input-filter-style',
								'#input-show-all-text',
							),
						),
					),
				),

				'only-categories-with-posts' => array(
					'name'    => 'only-categories-with-posts',
					'label'   => __( 'Nur Kategorien mit Beiträgen anzeigen', 'upfront' ),
					'type'    => 'select',
					'default' => 'no',
					'options' => array(
						'yes' => __( 'Ja', 'upfront' ),
						'no'  => __( 'Nein', 'upfront' ),
					),
					'tooltip' => __( 'Wähle den Filterstil', 'upfront' ),
				),

				'filter-style'               => array(
					'name'    => 'filter-style',
					'label'   => __( 'Filterstil', 'upfront' ),
					'type'    => 'select',
					'default' => 'style-1',
					'options' => array(
						'style-1' => 'Stil 1',
						'style-2' => 'Stil 2',
						'style-3' => 'Stil 3',
						'style-4' => 'Stil 4',
					),
					'tooltip' => __( 'Wähle den Filterstil', 'upfront' ),
				),

				'show-all-text'              => array(
					'name'    => 'show-all-text',
					'label'   => __( 'Alle Texte anzeigen', 'upfront' ),
					'type'    => 'text',
					'default' => 'Show All',
					'tooltip' => __( 'Standardtext für die Schaltfläche "Alle anzeigen"', 'upfront' ),
				),

				'show-margin'                => array(
					'name'    => 'show-margin',
					'label'   => __( 'Rand anzeigen', 'upfront' ),
					'type'    => 'select',
					'default' => 'yes',
					'options' => array(
						'yes' => __( 'Ja', 'upfront' ),
						'no'  => __( 'Nein', 'upfront' ),
					),
					'tooltip' => __( 'Rand anzeigen', 'upfront' ),
				),

				'alternate-content'          => array(
					'name'    => 'alternate-content',
					'label'   => __( 'Alternativer Inhalt und Bild', 'upfront' ),
					'type'    => 'select',
					'default' => 'no',
					'options' => array(
						'yes' => __( 'Ja', 'upfront' ),
						'no'  => __( 'Nein', 'upfront' ),
					),
					'tooltip' => __( 'Alternativer Inhalt und Bild', 'upfront' ),
				),

				'full-width-image'           => array(
					'name'    => 'full-width-image',
					'label'   => __( 'Bild in voller Breite anzeigen', 'upfront' ),
					'type'    => 'select',
					'default' => 'no',
					'options' => array(
						'yes' => __( 'Ja', 'upfront' ),
						'no'  => __( 'Nein', 'upfront' ),
					),
					'tooltip' => __( 'Bild in voller Breite anzeigen', 'upfront' ),
				),

				'title-overlay'              => array(
					'name'    => 'title-overlay',
					'label'   => __( 'Titelüberlagerung', 'upfront' ),
					'type'    => 'select',
					'default' => 'no',
					'options' => array(
						'yes' => __( 'Ja', 'upfront' ),
						'no'  => __( 'Nein', 'upfront' ),
					),
					'tooltip' => __( 'Titel über dem Bild anzeigen', 'upfront' ),
				),

				'show-open-button'           => array(
					'name'    => 'show-open-button',
					'label'   => __( 'Schaltfläche zum Öffnen anzeigen', 'upfront' ),
					'type'    => 'select',
					'default' => 'no',
					'options' => array(
						'yes' => __( 'Ja', 'upfront' ),
						'no'  => __( 'Nein', 'upfront' ),
					),
					'tooltip' => __( 'Schaltfläche zum Öffnen anzeigen', 'upfront' ),
				),

				'open-button-text'           => array(
					'name'    => 'open-button-text',
					'label'   => __( 'Öffnen Schaltflächentext', 'upfront' ),
					'type'    => 'text',
					'default' => 'Open article',
					'tooltip' => __( 'Standardtext für die Schaltfläche zum Öffnen von Artikeln', 'upfront' ),
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
