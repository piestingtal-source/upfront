<?php

class UpFrontListingsBlockOptions extends UpFrontBlockOptionsAPI {

	public $tabs;
	public $inputs;


	function __construct($block_type_object){

		parent::__construct($block_type_object);

		$this->tabs = array(
			'listing-type' => __('Wähle Auflistungstyp', 'upfront'),
			'posts-pages-filters' => __('Beiträge &amp; Seitenfilter', 'upfront'),
			'taxonomy-options' => __('Taxonomieoptionen', 'upfront')
		);

		$this->inputs = array(
			'listing-type' => array(

				'listing-type' => array(
					'type' => 'select',
					'name' => 'listing-type',
					'label' => __('Liste?', 'upfront'),
					'tooltip' => __('Wähle einen Typ der Listenausgabe aus und konfiguriere ihn mit den Optionen auf der linken Seite.', 'upfront'),
					'options' => array(
						'taxonomy' => __('Taxonomie (Kategorie, Tag usw.)', 'upfront'),
						'content' => __('Beiträge oder Seiten (benutzerdefinierte Beiträge)', 'upfront'),
						'authors' => __('Autoren', 'upfront')
					),
					'default' => 'taxonomy',
					'toggle'    => array(
						'taxonomy' => array(
							'show' => array(
								'#sub-tab-taxonomy-options'
							),
							'hide' => array(
								'#sub-tab-posts-pages-filters'
							)
						),
						'content' => array(
							'hide' => array(
								'#sub-tab-taxonomy-options'
							),
							'show' => array(
								'#sub-tab-posts-pages-filters'
							)
						)
					)
				)

			),

			'taxonomy-options'	=> array(

				'terms-select-taxonomy-heading' => array(
					'name' => 'terms-select-taxonomy-heading',
					'type' => 'heading',
					'label' => __('Wähle Taxonomie', 'upfront')
				),

				'select-taxonomy' => array(
					'label' => __('Wähle Taxonomie zum Anzeigen aus', 'upfront'),
					'type' => 'select',
					'name' => 'select-taxonomy',
					'options' => 'get_taxonomies()',
					'default' => 'category',
				),

				'terms-options-sorting-heading' => array(
					'name' => 'terms-options-sorting-heading',
					'type' => 'heading',
					'label' => __('Taxonomie sortieren', 'upfront')
				),

				'terms-orderby' => array(
					'type' => 'select',
					'name' => 'terms-orderby',
					'label' => __('Sortieren nach?', 'upfront'),
					'tooltip' => __('Sortiere den Begriff alphabetisch, nach eindeutiger Begriffs-ID oder nach der Anzahl der Elemente in diesem Begriff', 'upfront'),
					'options' => array(
						'none' => __('Nichts', 'upfront'),
						'ID' => 'ID',
						'name' => __('Name', 'upfront'),
						'slug' => __('Slug', 'upfront'),
						'count' => __('Zähler', 'upfront'),
						//'term_group' => 'Term Group'
					),
					'default' => 'name'
				),

				'terms-order' => array(
					'type' => 'select',
					'name' => 'terms-order',
					'label' => __('Anordnung?', 'upfront'),
					'tooltip' => __('Sortierreihenfolge für Begriff (entweder aufsteigend oder absteigend).', 'upfront'),
					'options' => array(
						'DESC' => __('Absteigend', 'upfront'),
						'ASC' => __('Aufsteigend', 'upfront')
					),
					'default' => 'ASC'
				),

				'terms-options-filter-heading' => array(
					'name' => 'terms-options-filter-heading',
					'type' => 'heading',
					'label' => __('Filtertaxonomie', 'upfront')
				),

				'terms-number' => array(
					'type' => 'slider',
					'slider-min' => 0,
					'slider-max' => 30,
					'slider-interval' => 1,
					'name' => 'terms-number',
					'label' => __('Anzahl der Begriffe', 'upfront'),
					'default' => '10',
					'tooltip' => __('Legt die Anzahl der anzuzeigenden Begriffe fest. Standard 0 für keine Begrenzung.', 'upfront')
				),

				'terms-child-of' => array(
					'type' => 'select',
					'name' => 'terms-child-of',
					'label' => __('Child von', 'upfront'),
					'options' => 'get_listing_terms()',
					'default' => '',
					'tooltip' => __('Zeige nur Begriffe an, die untergeordnet sind, was Du hier angibst.', 'upfront')
				),

				'terms-exclude' => array(
					'type' => 'multi-select',
					'name' => 'terms-exclude',
					'label' => __('Ausschließen', 'upfront'),
					'options' => 'get_listing_terms()',
					'default' => '',
					'tooltip' => __('Schließe einen oder mehrere Begriffe aus den Ergebnissen aus.', 'upfront')
				),

				'terms-include' => array(
					'type' => 'multi-select',
					'name' => 'terms-include',
					'label' => __('Einschließen', 'upfront'),
					'options' => 'get_listing_terms()',
					'default' => '',
					'tooltip' => __('Nimm nur bestimmte Begriffe in die Liste auf.', 'upfront')
				),

				'terms-slug' => array(
					'name' => 'terms-slug',
					'type' => 'text',
					'label' => 'Slug',
					'tooltip' => __('Gibt Begriffe zurück, deren "Slug" diesem Wert entspricht. Standard ist eine leere Zeichenfolge.', 'upfront')
				),

				'terms-options-display-heading' => array(
					'name' => 'terms-options-display-heading',
					'type' => 'heading',
					'label' => __('Taxonomie anzeigen', 'upfront')
				),

				'terms-hide-empty' => array(
					'type' => 'checkbox',
					'name' => 'terms-hide-empty', 
					'label' => __('Leer verstecken?', 'upfront'),
					'tooltip' => __('Schaltet die Anzeige des Begriffs ohne Beiträge um.', 'upfront'),
					'default' => true
				),

				'terms-hierarchical' => array(
					'type' => 'checkbox',
					'name' => 'terms-hierarchical', 
					'label' => __('Hierarchisch?', 'upfront'),
					'tooltip' => __('Gibt an, ob Begriffe mit nicht leeren Nachkommen enthalten sein sollen.', 'upfront'),
					'default' => true
				)

			),

			'posts-pages-filters' => array(

				'number-of-posts' => array(
					'type' => 'integer',
					'name' => 'number-of-posts',
					'label' => __('Anzahl der Beiträge', 'upfront'),
					'tooltip' => '',
					'default' => 5
				),

				'posts-pages-post-type-heading' => array(
					'name' => 'posts-pages-post-type-heading',
					'type' => 'heading',
					'label' => __('Inhalt filtern', 'upfront')
				),

				'post-type' => array(
					'type' => 'select',
					'name' => 'post-type',
					'label' => __('Beitrags-Typ', 'upfront'),
					'tooltip' => '',
					'options' => 'get_post_types()',
					'toggle'    => array(
						'0' => array(
							'hide' => array(
								'#input-post-taxonomy-filter',
								'#input-terms'
							)
						)
					),
					'callback' => 'reloadBlockOptions()'
				),

				'post-taxonomy-filter' => array(
					'label' => __('Wähle Taxonomie zum Filtern aus', 'upfront'),
					'type' => 'select',
					'name' => 'post-taxonomy-filter',
					'options' => 'get_taxonomies()',
					'default' => 'category',
					'toggle'    => array(
						'0' => array(
							'hide' => array(
								'#input-terms'
							)
						)
					),
					'callback' => '
						reloadBlockOptions()'
				),

				'terms' => array(
					'type' => 'multi-select',
					'name' => 'terms',
					'tooltip' => ''
				),

				'author' => array(
					'type' => 'multi-select',
					'name' => 'author',
					'label' => __('Autor', 'upfront'),
					'tooltip' => '',
					'options' => 'get_authors()'
				),

				'offset' => array(
					'type' => 'integer',
					'name' => 'offset',
					'label' => __('Versatz', 'upfront'),
					'tooltip' => __('Der Versatz ist die Anzahl der Einträge oder Beiträge, die Du überspringen möchtest. Wenn der Versatz 1 ist, wird der erste Beitrag übersprungen.', 'upfront'),
					'default' => 0
				),

				'posts-pages-sort-heading' => array(
					'name' => 'posts-pages-sort-heading',
					'type' => 'heading',
					'label' => __('Inhalt sortieren', 'upfront')
				),

				'order-by' => array(
					'type' => 'select',
					'name' => 'order-by',
					'label' => __('Sortieren nach', 'upfront'),
					'tooltip' => '',
					'options' => array(
						'date' => __('Datum', 'upfront'),
						'title' => __('Titel', 'upfront'),
						'rand' => __('Zufällig', 'upfront'),
						'comment_count' => __('Anzahl Kommentare', 'upfront'),
						'ID' => 'ID'
					)
				),

				'order' => array(
					'type' => 'select',
					'name' => 'order',
					'label' => __('Anordnung', 'upfront'),
					'tooltip' => '',
					'options' => array(
						'desc' => __('Absteigend', 'upfront'),
						'asc' => __('Aufsteigend', 'upfront'),
					)
				)
			),
		);

	}

	function modify_arguments($args = false) {

		$block = $args['block'];

		/* Content Options */
		$taxomomy = UpFrontBlockAPI::get_setting($block, 'post-taxonomy-filter');

		$terms = self::get_listing_terms($taxomomy);
		$label = self::get_taxonomy_label($taxomomy);
		$post_type = UpFrontBlockAPI::get_setting($block, 'post-type');
		$taxonomies = self::get_taxonomies($post_type);

		$this->inputs['posts-pages-filters']['terms']['options'] = $terms;
		$this->inputs['posts-pages-filters']['terms']['label'] = $label;
		$this->inputs['posts-pages-filters']['post-taxonomy-filter']['options'] = $taxonomies;

		/* Taxonomy Options */
		$this->inputs['taxonomy-options']['select-taxonomy']['options'] = self::get_taxonomies();

		$taxomomy = UpFrontBlockAPI::get_setting($block, 'select-taxonomy');

		$terms = self::get_listing_terms($taxomomy);
		$label = self::get_taxonomy_label($taxomomy);
		$this->inputs['taxonomy-options']['terms']['label'] = $label;
		$this->inputs['taxonomy-options']['terms-child-of']['options'] = $terms;
		$this->inputs['taxonomy-options']['terms-exclude']['options'] = $terms;
		$this->inputs['taxonomy-options']['terms-include']['options'] = $terms;

	}

	function get_taxonomies($post_type='') {

		if (!empty($post_type)) {
			$post_type = array($post_type);
			$args=array(
			  'object_type' => $post_type 
			);
		} else {
			$args = '';
		}

		$output = 'objects';
		$operator = 'and';

		$taxonomy_options = array('&ndash; Nicht filtern &ndash;');

		$taxonomy_select_query=get_taxonomies($args,$output,$operator);

		if  ($taxonomy_select_query) {
		  foreach ($taxonomy_select_query as $taxonomy)
			$taxonomy_options[$taxonomy->name] = $taxonomy->label;
		} 

		return $taxonomy_options;

	}

	function get_listing_terms($taxonomy='category') {

		if ( !$taxonomy )
			$taxonomy = 'category';

		$taxonomy_label = $this->get_taxonomy_label($taxonomy);

		$terms_options = array('&ndash; Wähle '. $taxonomy_label .' &ndash;');

		$terms = get_terms( $taxonomy, 'orderby=id&hide_empty=0' );

		if ( !$terms )
			return;

		foreach ($terms as $term)
			$terms_options[$term->term_id] = $term->name;

		return $terms_options;

	}

	function get_taxonomy_label($taxonomy) {

		if ( !$taxonomy )
			$taxonomy = 'category';

		$args = array(
		  'name' => $taxonomy
		);
		$output = 'objects'; // or objects		
		$taxonomy_select_query=get_taxonomies($args,$output);; 

		if  ($taxonomy_select_query) {
		  foreach ($taxonomy_select_query as $taxonomy)
			return $taxonomy->label;
		} 

	}

	function get_authors() {

		$author_options = array();

		$authors = get_users(array(
			'orderby' => 'post_count',
			'order' => 'desc',
			'who' => 'authors'
		));

		foreach ( $authors as $author )
			$author_options[$author->ID] = $author->display_name;

		return $author_options;

	}

	function get_pages() {

		$page_options = array('&ndash; Standard &ndash;');

		$page_select_query = get_pages();

		foreach ($page_select_query as $page)
			$page_options[$page->ID] = $page->post_title;

		return $page_options;

	}

	function get_post_types() {

		$post_type_options = array('&ndash; Alle Beitragstypen &ndash;');

		$post_types = get_post_types(false, 'objects'); 

		foreach($post_types as $post_type_id => $post_type){

			//Make sure the post type is not an excluded post type.
			if(in_array($post_type_id, array('revision', 'nav_menu_item'))) 
				continue;

			$post_type_options[$post_type_id] = $post_type->labels->name;

		}

		return $post_type_options;

	}

}