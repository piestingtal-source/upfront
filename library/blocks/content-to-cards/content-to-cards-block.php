<?php
/**
 * Content to Cards Block
 */
class UpFrontBlockContentToCards extends UpFrontBlockAPI {

	public $id;
	public $name;
	public $options_class;
	public $description;
	public $categories;
	public function __construct() {
		$this->id            = 'content-to-cards';
		$this->name          = __( 'Inhalt zu Karten', 'upfront' );
		$this->options_class = 'UpFrontBlockContentToCardsOptions';
		$this->description   = __( 'Ermöglicht das Anzeigen erweiterbarer Beiträge.', 'upfront' );
		$this->categories    = array( 'box', 'elemente' );
	}


	/**
	 * Setup Visual Editor elements.
	 */
	public function setup_elements() {

		$this->register_block_element(
			array(
				'id'       => '.ve-card-posts-container',
				'name'     => __( 'Container', 'upfront' ),
				'selector' => '.ve-card-posts-container',
			)
		);

		$this->register_block_element(
			array(
				'id'       => '.ve-card-posts-container-item',
				'name'     => __( 'Element', 'upfront' ),
				'selector' => '.ve-card-posts-container li',
			)
		);

		$this->register_block_element(
			array(
				'id'       => '.content-wrapper',
				'name'     => __( 'Inhalt', 'upfront' ),
				'selector' => '.ve-card-posts-container .content-wrapper',
			)
		);

		$this->register_block_element(
			array(
				'id'       => '.cd-title',
				'name'     => __( 'Titel', 'upfront' ),
				'selector' => '.ve-card-posts-container .cd-title h2',
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
	public static function dynamic_css( $block_id, $block ) {

		if ( ! $block ) {
			$block = \UpFrontBlocksData::get_block( $block_id );
		}

		$posts = \UpFrontQuery::get_posts( $block );

		$css    = '';
		$total  = count( $posts );
		$offset = 100 / ( $total + 1 );

		$offset_incremental = $offset;

		$counter = 1;

		foreach ( $posts as $key => $post ) {

			$image = get_the_post_thumbnail_url( $post->ID );
			$css  .= '.single-post.post-id-' . $post->ID . ' .cd-title::before { background-image: url(' . $image . '); }';

			if ( $counter > 1 ) {
				$css .= '.ve-card-posts-container .single-post.post-id-' . $post->ID . '{';
				$css .= '-webkit-transform: translateY(' . $offset_incremental . '%);';
				$css .= '-moz-transform: translateY(' . $offset_incremental . '%);';
				$css .= '-ms-transform: translateY(' . $offset_incremental . '%);';
				$css .= '-o-transform: translateY(' . $offset_incremental . '%);';
				$css .= 'transform: translateY(' . $offset_incremental . '%);';
				$css .= '}';
			}

			$offset_incremental += $offset;
			++$counter;
		}

		$css .= '.cd-title{ height:' . $offset . '%; }';
		return $css;

	}

	/**
	 * UpFront Content Method
	 *
	 * @param object $block Block.
	 * @return void
	 */
	public function content( $block ) {

		$scroll_text = parent::get_setting( $block, 'scroll-text', 'Scroll down' );

		$posts = \UpFrontQuery::get_posts( $block );

		$html  = '<button class="cd-nav-trigger"><span aria-hidden="true" class="cd-icon"></span></button>';
		$html .= '<div class="ve-card-posts-container">';
		$html .= '<ul>';

		foreach ( $posts as $key => $post ) {

			$id     = $post->ID;
			$image  = get_the_post_thumbnail_url( $post->ID );
			$desc   = $post->post_excerpt;
			$title  = $post->post_title;
			$url    = get_post_permalink( $post->ID );
			$date   = date( 'M d, Y', strtotime( $post->post_date ) );
			$author = get_the_author_meta( 'display_name', $post->post_author );

			$post_link = get_permalink( $id );

			$html .= '<li class="single-post post-id-' . $id . '">';
			$html .= '<div class="cd-title">';
			$html .= '<h2>' . $title . '</h2>';
			$html .= '</div>';
			$html .= '<div class="ve-card-post-info">';
			$html .= '<button class="ve-card-scroll">' . $scroll_text . '</button>';
			$html .= '<div class="content-wrapper">';
			$html .= $post->post_content;
			$html .= '</div>';
			$html .= '</div>';
			$html .= '</li>';

		}

		$html .= '</ul>';
		$html .= '</div>';

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


		// CSS.
		wp_enqueue_style( 'upfront-ve-content-to-cards', upfront_url() . '/library/blocks/content-to-cards/content-to-cards.css', array(), UPFRONT_VERSION );

		/* JS */
		wp_enqueue_script( 'upfront-ve-content-to-cards', upfront_url() . '/library/blocks/content-to-cards/content-to-cards.js', array( 'jquery' ), UPFRONT_VERSION, true );
	}

}
class UpFrontBlockContentToCardsOptions extends UpFrontBlockOptionsAPI {

	
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
				'scroll-text' => array(
					'name'    => 'scroll-text',
					'type'    => 'text',
					'label'   => __( 'Bildlauf ', 'upfront' ),
					'tooltip' => __( 'Bildlauf ', 'upfront' ),
					'default' => 'Scroll down',
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
					'tooltip' => __( 'Der Versatz ist die Anzahl der Einträge oder Beiträge, die Du überspringen möchtest. Wenn der Versatz 1 ist, wird der erste Beitrag übersprungen.', 'upfront' ),
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