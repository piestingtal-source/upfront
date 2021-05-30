<?php

class UpFrontPSeCommerceProductsBlock extends UpFrontBlockAPI {

	
	public $id;
	public $name;
	public $options_class;
	public $description;
	public $categories;
	public function __construct() {
		$this->id            = 'psecommerce-products';
		$this->name          = 'PSeCommerce Produkte';
		$this->options_class = 'UpFrontPSeCommerceProductsBlockOptions';
		$this->description   = __( 'Allows you to display products by post ID, SKU, categories, attributes, with support for pagination, random sorting, and product tags', 'upfront' );
		$this->categories    = array( 'shop', 'content', 'dynamischer-content' );
	}

	/**
	 * Init
	 */
	public function init() {

		if ( ! class_exists( 'PSeCommerce' ) ) {
			return false;
		}

	}

	/**
	 * Setup Visual Editor elements.
	 */
	public function setup_elements() {

		$this->register_block_element(
			array(
				'id'       => 'products-container',
				'name'     => 'Products container',
				'selector' => '.psecommerce',
			)
		);

		$this->register_block_element(
			array(
				'id'       => 'notices',
				'name'     => 'Notices',
				'selector' => '.psecommerce-notices-wrapper',
			)
		);

		$this->register_block_element(
			array(
				'id'       => 'result-count',
				'name'     => 'Result count',
				'selector' => '.psecommerce-result-count',
			)
		);

		$this->register_block_element(
			array(
				'id'       => 'ordering',
				'name'     => 'Ordering',
				'selector' => '.psecommerce-ordering',
			)
		);

		$this->register_block_element(
			array(
				'id'       => 'products-on-page-form',
				'name'     => 'Products on page',
				'selector' => '.psecommerce > div > form',
			)
		);

		$this->register_block_element(
			array(
				'id'       => 'ordering-select',
				'name'     => 'Ordering select',
				'selector' => '.psecommerce-ordering select',
			)
		);

		$this->register_block_element(
			array(
				'id'       => 'products',
				'name'     => 'Products',
				'selector' => '.products',
			)
		);

		$this->register_block_element(
			array(
				'id'       => 'single-product',
				'name'     => 'Single product',
				'selector' => '.products .product',
				'states'   => array(
					'Product Hover'        => '.products .product:hover',
					'Button when Hover'    => '.products .product:hover a:not(.added_to_cart)',
					'Button added to cart' => '.products .product a.added_to_cart',
				),
			)
		);

		$this->register_block_element(
			array(
				'id'       => 'single-product-button',
				'name'     => 'Button',
				'selector' => '.product a.button',
				'states'   => array(
					'Hover' => '.product a.button:hover',
				),
			)
		);

		$this->register_block_element(
			array(
				'id'       => 'onsale',
				'name'     => 'Sale',
				'selector' => '.products .product .onsale',
			)
		);

		$this->register_block_element(
			array(
				'id'       => 'image',
				'name'     => 'Image',
				'selector' => '.products .product img',
			)
		);

		$this->register_block_element(
			array(
				'id'       => 'title',
				'name'     => 'Title',
				'selector' => '.products .product h2',
			)
		);

		$this->register_block_element(
			array(
				'id'       => 'price',
				'name'     => 'Price',
				'selector' => '.products .product .price',
			)
		);

		$this->register_block_element(
			array(
				'id'       => 'amount',
				'name'     => 'Amount',
				'selector' => '.products .product .amount',
			)
		);

		$this->register_block_element(
			array(
				'id'       => 'currency-symbol',
				'name'     => 'Currency symbol',
				'selector' => '.products .product .woocommerce-Price-currencySymbol',
			)
		);

		$this->register_block_element(
			array(
				'id'       => 'pagination',
				'name'     => 'Pagination',
				'selector' => '.woocommerce-pagination',
			)
		);

		$this->register_block_element(
			array(
				'id'       => 'page-numbers',
				'name'     => 'Page numbers',
				'selector' => '.page-numbers',
			)
		);

		$this->register_block_element(
			array(
				'id'       => 'page-single-number',
				'name'     => 'Number',
				'selector' => '.page-numbers li',
			)
		);

		$this->register_block_element(
			array(
				'id'       => 'page-single-number-span',
				'name'     => 'Number span',
				'selector' => '.page-numbers li .page-numbers',
			)
		);

		$this->register_block_element(
			array(
				'id'       => 'page-single-number-span-current',
				'name'     => 'Current Number span',
				'selector' => '.page-numbers li .page-numbers.current',
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

		$style = parent::get_setting( $block, 'style', 'default' );

		$limit             = parent::get_setting( $block, 'limit', -1 );
		$columns           = parent::get_setting( $block, 'columns', 4 );
		$paginate          = parent::get_setting( $block, 'paginate', 'false' );
		$orderby           = parent::get_setting( $block, 'orderby', 'title' );
		$skus              = parent::get_setting( $block, 'skus', '' );
		$category          = parent::get_setting( $block, 'category', array() );
		$tag               = parent::get_setting( $block, 'tag', array() );
		$order             = parent::get_setting( $block, 'order', 'ASC' );
		$class             = parent::get_setting( $block, 'class', '' );
		$custom_retrieve   = parent::get_setting( $block, 'custom-retrieve', 'normal' );
		$attribute         = parent::get_setting( $block, 'attribute', '' );
		$terms             = parent::get_setting( $block, 'terms', '' );
		$terms_operator    = parent::get_setting( $block, 'terms-operator', 'IN' );
		$tag_operator      = parent::get_setting( $block, 'tag-operator', 'IN' );
		$visibility        = parent::get_setting( $block, 'visibility', 'visible' );
		$specific_category = parent::get_setting( $block, 'specific-category', '' );
		$specific_tag      = parent::get_setting( $block, 'specific-tag', '' );
		$cat_operator      = parent::get_setting( $block, 'cat-operator', 'IN' );
		$ids               = parent::get_setting( $block, 'ids', '' );

		$shortcode  = '[mp_list_products paginate ';
		$shortcode .= 'limit="' . $limit . '" ';
		$shortcode .= 'columns="' . $columns . '" ';

		if ( $paginate ) {
			$shortcode .= 'paginate="true" ';
		} else {
			$shortcode .= 'paginate="false" ';
		}

		$shortcode .= 'orderby="' . $orderby . '" ';

		if ( strlen( $skus ) > 0 ) {
			$shortcode .= 'skus="' . $skus . '" ';
		}

		$categories = '';
		foreach ( $category as $key => $value ) {
			$categories .= $value . ',';
		}

		if ( strlen( $categories ) > 0 ) {
			$categories = rtrim( $categories, ',' );
			$shortcode .= 'category="' . $categories . '" ';
		}

		$tags = '';
		if ( is_array( $tag ) ) {
			foreach ( $tag as $key => $value ) {
				$tags .= $value . ',';
			}
		}

		if ( strlen( $tags ) > 0 ) {

			$tags = rtrim( $tags, ',' );

			$shortcode .= 'tag="' . $tags . '" ';
			$shortcode .= 'tag_operator="' . $tag_operator . '" ';

		}

		$shortcode .= 'order="' . $order . '" ';

		if ( strlen( $class ) > 0 ) {
			$shortcode .= 'class="' . $class . '" ';
		}

		if ( 'normal' !== $custom_retrieve ) {

			switch ( $custom_retrieve ) {

				case 'on_sale':
					$shortcode .= 'on_sale="true" ';
					break;

				case 'best_selling':
					$shortcode .= 'best_selling="true" ';
					break;

				case 'top_rated':
					$shortcode .= 'top_rated="true" ';
					break;
			}
		}

		if ( strlen( $attribute ) > 0 && strlen( $terms ) > 0 ) {
			$shortcode .= 'attribute="' . $attribute . '" terms="' . $terms . '" ';
			$shortcode .= 'terms_operator="' . $terms_operator . '" ';
		}

		$shortcode .= 'visibility="' . $visibility . '" ';

		if ( strlen( $specific_category ) > 0 ) {
			$shortcode .= 'specific_category="' . $specific_category . '" ';
		}

		if ( strlen( $specific_tag ) > 0 ) {
			$shortcode .= 'specific_tag="' . $specific_tag . '" ';
		}

		$shortcode .= 'cat_operator="' . $cat_operator . '" ';

		if ( strlen( $ids ) > 0 ) {
			$shortcode .= 'ids="' . $ids . '" ';
		}

		$shortcode .= ']';

		echo do_shortcode( $shortcode );
	}

}
class UpFrontPSeCommerceProductsBlockOptions extends UpFrontBlockOptionsAPI {
	


	/**
	 * Block tabs for options.
	 *
	 * @var array $tabs
	 */
	public $tabs;

	/**
	 * Block sets for options.
	 *
	 * @var array $sets
	 */
	public $sets;

	/**
	 * Inputs for each tab.
	 *
	 * @var array $inputs
	 */
	public $inputs;

	/**
	 * Init block options
	 */
	public function __construct() {

		$this->tabs = array(
			'general'       => __( 'Allgemeines', 'upfront' ),
			'query-filters' => __( 'Abfragefilter', 'upfront' ),
		);

		$this->sets = array();

		$this->inputs = array(

			'general'       => array(
				'style' => array(
					'name'    => 'style',
					'type'    => 'select',
					'label'   => __( 'Stil', 'upfront' ),
					'default' => 'default',
					'options' => array(
						'default' => 'Default',
					),
					'tooltip' => __( 'Choose style', 'upfront' ),
				),
			),

			'query-filters' => array(

				'display-product-attributes' => array(
					'name'  => 'display-product-attributes',
					'type'  => 'heading',
					'label' => __( 'Display Product Attributes', 'upfront' ),
				),

				'limit'                      => array(
					'type'    => 'integer',
					'name'    => 'limit',
					'label'   => __( 'Limit', 'upfront' ),
					'tooltip' => __( 'The number of products to display. Defaults to and -1 (display all)  when listing products, and -1 (display all) for categories.', 'upfront' ),
					'default' => -1,
				),

				'columns'                    => array(
					'type'    => 'select',
					'name'    => 'columns',
					'label'   => __( 'Columns', 'upfront' ),
					'tooltip' => __( 'The number of columns to display. Defaults to 4.', 'upfront' ),
					'options' => array(
						1 => 1,
						2 => 2,
						3 => 3,
						4 => 4,
						5 => 5,
						6 => 6,
					),
					'default' => 4,
				),

				'paginate'                   => array(
					'type'    => 'select',
					'name'    => 'paginate',
					'label'   => __( 'Paginate', 'upfront' ),
					'tooltip' => __( 'Toggles pagination on. Use in conjunction with limit. Defaults to false set to true to paginate .', 'upfront' ),
					'options' => array(
						'true'  => __( 'True', 'upfront' ),
						'false' => __( 'False', 'upfront' ),
					),
				),

				'orderby'                    => array(
					'type'    => 'select',
					'name'    => 'orderby',
					'label'   => __( 'Sortieren nach', 'upfront' ),
					'tooltip' => __( 'Sorts the products displayed by the entered option. One or more options can be passed by adding both slugs with a space between them.', 'upfront' ),
					'options' => array(
						'date'       => __( 'Datum', 'upfront' ),
						'id'         => 'ID',
						'menu_order' => __( 'Menu Order', 'upfront' ),
						'popularity' => __( 'Popularity', 'upfront' ),
						'rand'       => __( 'Zufall', 'upfront' ),
						'title'      => __( 'Titel', 'upfront' ),
						'rating'     => __( 'Rating', 'upfront' ),
					),
					'default' => 'title',
				),

				'skus'                       => array(
					'type'    => 'text',
					'name'    => 'skus',
					'label'   => 'Skus',
					'tooltip' => __( 'Comma-separated list of product SKUs.', 'upfront' ),
				),

				'category'                   => array(
					'type'    => 'multi-select',
					'name'    => 'category',
					'label'   => __( 'Kategorien', 'upfront' ),
					'tooltip' => '',
					'options' => 'get_categories()',
				),

				'tag'                        => array(
					'type'    => 'multi-select',
					'name'    => 'tag',
					'label'   => __( 'Tags', 'upfront' ),
					'tooltip' => '',
					'options' => 'get_tags()',
				),

				'order'                      => array(
					'type'    => 'select',
					'name'    => 'order',
					'label'   => __( 'Sortierung', 'upfront' ),
					'tooltip' => __( 'States whether the product order is ascending (ASC) or descending (DESC), using the method set in orderby. Defaults to ASC.', 'upfront' ),
					'options' => array(
						'DESC' => __( 'Absteigend', 'upfront' ),
						'ASC'  => __( 'Aufsteigend', 'upfront' ),
					),
				),

				'class'                      => array(
					'name'    => 'class',
					'type'    => 'text',
					'label'   => __( 'CSS Div Class', 'upfront' ),
					'tooltip' => __( 'Adds an HTML wrapper class so you can modify the specific output with custom CSS.', 'upfront' ),
				),

				'custom-retrieve'            => array(
					'type'    => 'select',
					'name'    => 'custom-retrieve',
					'label'   => __( 'Custom Retrieve', 'upfront' ),
					'tooltip' => __( 'Retrieve on sale,  best selling or top rated products.', 'upfront' ),
					'options' => array(
						'normal'       => 'Normal',
						'on_sale'      => __( 'On Sale products', 'upfront' ),
						'best_selling' => __( 'Best Selling products', 'upfront' ),
						'top_rated'    => __( 'Top Rated products', 'upfront' ),
					),
				),

				'content-product-attributes' => array(
					'name'  => 'content-product-attributes',
					'type'  => 'heading',
					'label' => __( 'Content Product Attributes', 'upfront' ),
				),

				'attribute'                  => array(
					'type'    => 'select',
					'name'    => 'attribute',
					'label'   => __( 'Attribute', 'upfront' ),
					'tooltip' => 'Retrieves products using the specified attribute slug.',
					'options' => 'get_attribute()',
				),

				'terms'                      => array(
					'name'    => 'terms',
					'type'    => 'text',
					'label'   => __( 'Terms', 'upfront' ),
					'tooltip' => __( 'Comma-separated list of attribute terms to be used with attribute.', 'upfront' ),
				),

				'terms-operator'             => array(
					'type'    => 'select',
					'name'    => 'terms-operator',
					'label'   => __( 'Terms operator', 'upfront' ),
					'tooltip' => __( 'Operator to compare attribute terms.', 'upfront' ),
					'default' => 'IN',
					'options' => array(
						'AND'    => __( 'AND - Will display products from all of the chosen attributes.', 'upfront' ),
						'IN'     => __( 'IN - Will display products with the chosen attribute.', 'upfront' ),
						'NOT IN' => __( 'NOT IN - Will display products that are not in the chosen attributes.', 'upfront' ),
					),
				),

				'tag-operator'               => array(
					'type'    => 'select',
					'name'    => 'tag-operator',
					'label'   => __( 'Tag operator', 'upfront' ),
					'tooltip' => __( 'Operator to compare tags.', 'upfront' ),
					'default' => 'IN',
					'options' => array(
						'AND'    => __( 'AND - Will display products from all of the chosen tags.', 'upfront' ),
						'IN'     => __( 'IN - Will display products with the chosen tags.', 'upfront' ),
						'NOT IN' => __( 'NOT IN - Will display products that are not in the chosen tags.', 'upfront' ),
					),
				),

				'visibility'                 => array(
					'type'    => 'select',
					'name'    => 'visibility',
					'label'   => __( 'Visibility', 'upfront' ),
					'tooltip' => __( 'Will display products based on the selected visibility.', 'upfront' ),
					'default' => 'visible',
					'options' => array(
						'visible'  => __( 'Visible - Products visible on shop and search results.', 'upfront' ),
						'catalog'  => __( 'Catalog - Products visible on the shop only, but not search results.', 'upfront' ),
						'search'   => __( 'Search - Products visible in search results only, but not on the shop.', 'upfront' ),
						'hidden'   => __( 'Hidden - Products that are hidden from both shop and search, accessible only by direct URL.', 'upfront' ),
						'featured' => __( 'Featured - Products that are marked as Featured Products.', 'upfront' ),
					),
				),

				'specific-category'          => array(
					'name'    => 'specific-category',
					'type'    => 'text',
					'label'   => __( 'Specific category', 'upfront' ),
					'tooltip' => __( 'Retrieves products using the specified category slug.', 'upfront' ),
				),

				'specific-tag'               => array(
					'name'    => 'specific-tag',
					'type'    => 'text',
					'label'   => __( 'Specific tag', 'upfront' ),
					'tooltip' => __( 'Retrieves products using the specified tag slug.', 'upfront' ),
				),

				'cat-operator'               => array(
					'type'    => 'select',
					'name'    => 'cat-operator',
					'label'   => __( 'Tag operator', 'upfront' ),
					'tooltip' => __( 'Operator to compare category terms.', 'upfront' ),
					'default' => 'IN',
					'options' => array(
						'AND'    => __( 'AND - Will display products that belong in all of the chosen categories.', 'upfront' ),
						'IN'     => __( 'IN - Will display products within the chosen category.', 'upfront' ),
						'NOT IN' => __( 'NOT IN - Will display products that are not in the chosen category.', 'upfront' ),
					),
				),

				'ids'                        => array(
					'name'    => 'ids',
					'type'    => 'text',
					'label'   => __( 'Specific Ids', 'upfront' ),
					'tooltip' => __( 'Will display products based on a comma-separated list of Post IDs.', 'upfront' )
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

	/**
	 * Get posts attribute
	 *
	 * @return array
	 */
	public function get_attribute() {

		$attributes = array( '' => '' );

		if ( ! function_exists( 'mp_get_attribute_taxonomies' ) ) {
			return $attributes;
		}

		foreach ( \mp_get_attribute_taxonomies() as $key => $attribute ) {
			$attributes[ $attribute->attribute_name ] = $attribute->attribute_label;
		}
		return $attributes;
	}

}