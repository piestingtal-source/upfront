<?php
function upfront_storefront_mp_register_elements() {

	UpFrontElementAPI::register_element(array(
		'group' => 'blocks',
		'id' => 'block-content-wc-page-title',
		'parent' => 'block-content',
		'name' => __('Shop Title', 'upfront'),
		'description' => 'Storefront: WooCommerce',
		'selector' => '.woocommerce .block-type-content h1.page-title'
	));

	UpFrontElementAPI::register_element(array(
		'group' => 'blocks',
		'id' => 'block-content-wc-result-count',
		'parent' => 'block-content',
		'name' => __('Result Count', 'upfront'),
		'description' => 'Storefront: WooCommerce',
		'selector' => '.woocommerce .block-type-content p.woocommerce-result-count'
	));

	UpFrontElementAPI::register_element(array(
		'group' => 'blocks',
		'id' => 'block-content-wc-ordering',
		'parent' => 'block-content',
		'name' => __('Ordering', 'upfront'),
		'description' => __('Storefront: WooCommerce', 'upfront'),
		'selector' => '.woocommerce .block-type-content form.woocommerce-ordering'
	));

	UpFrontElementAPI::register_element(array(
		'group' => 'blocks',
		'id' => 'block-content-wc-breadcrumbs',
		'parent' => 'block-content',
		'name' => __('Breadcrumbs', 'upfront'),
		'description' => __('Storefront: WooCommerce', 'upfront'),
		'selector' => '.woocommerce .block-type-content .woocommerce-breadcrumb'
	));


	/* Product Listings */
		UpFrontElementAPI::register_element(array(
			'group' => 'blocks',
			'id' => 'block-content-wc-listings-product-containers',
			'parent' => 'block-content',
			'name' => __('Product Listings', 'upfront'),
			'description' => 'Storefront: WooCommerce',
			'selector' => '.woocommerce .block-type-content ul.products li.product'
		));

		UpFrontElementAPI::register_element(array(
			'group' => 'blocks',
			'id' => 'block-content-wc-listings-product-names',
			'parent' => 'block-content-wc-listings-product-containers',
			'name' => __('Product Listings: Names', 'upfront'),
			'description' => __('Storefront: WooCommerce', 'upfront'),
			'selector' => '.woocommerce .block-type-content ul.products li.product h3'
		));

		UpFrontElementAPI::register_element(array(
			'group' => 'blocks',
			'id' => 'block-content-wc-listings-product-images',
			'parent' => 'block-content-wc-listings-product-containers',
			'name' => __('Product Listings: Images', 'upfront'),
			'description' => __('Storefront: WooCommerce', 'upfront'),
			'selector' => '.woocommerce .block-type-content ul.products li.product img'
		));

		UpFrontElementAPI::register_element(array(
			'group' => 'blocks',
			'id' => 'block-content-wc-listings-product-onsale',
			'parent' => 'block-content-wc-listings-product-containers',
			'name' => __('Product Listings: Onsale', 'upfront'),
			'description' => __('Storefront: WooCommerce', 'upfront'),
			'selector' => '.woocommerce .block-type-content ul.products li.product span.onsale'
		));
		UpFrontElementAPI::register_element(array(
			'group' => 'blocks',
			'id' => 'block-content-wc-listings-product-price',
			'parent' => 'block-content-wc-listings-product-containers',
			'name' => __('Product Listings: Price', 'upfront'),
			'description' => __('Storefront: WooCommerce', 'upfront'),
			'selector' => '.woocommerce .block-type-content ul.products li.product span.price'
		));
		UpFrontElementAPI::register_element(array(
			'group' => 'blocks',
			'id' => 'block-content-wc-listings-product-prices',
			'parent' => 'block-content-wc-listings-product-containers',
			'name' => __('Product Listings: Prices', 'upfront'),
			'description' => __('Storefront: WooCommerce', 'upfront'),
			'selector' => '.woocommerce .block-type-content ul.products li.product span.amount'
		));
	/* End Product Listings */


	/* Product Pages */
		UpFrontElementAPI::register_element(array(
			'group' => 'blocks',
			'id' => 'block-content-wc-product-page-name',
			'parent' => 'block-content',
			'name' => __('Product Page: Name', 'upfront'),
			'description' => 'Storefront: WooCommerce',
			'selector' => '.woocommerce .block-type-content div.product div.summary h1.product_title'
		));

		UpFrontElementAPI::register_element(array(
			'group' => 'blocks',
			'id' => 'block-content-wc-product-page-image',
			'parent' => 'block-content',
			'name' => __('Product Page: Image', 'upfront'),
			'indent-in-selector' => true,
			'description' => 'Storefront: WooCommerce',
			'selector' => '.woocommerce .block-type-content div.product div.images img'
		));

		UpFrontElementAPI::register_element(array(
			'group' => 'blocks',
			'id' => 'block-content-wc-product-page-price',
			'parent' => 'block-content',
			'name' => __('Product Page: Price', 'upfront'),
			'indent-in-selector' => true,
			'description' => 'Storefront: WooCommerce',
			'selector' => '.woocommerce .block-type-content div.product div.summary p.price'
		));

		UpFrontElementAPI::register_element(array(
			'group' => 'blocks',
			'id' => 'block-content-wc-product-page-short-description',
			'parent' => 'block-content',
			'name' => __('Product Page: Short Description', 'upfront'),
			'indent-in-selector' => true,
			'description' => __('Storefront: WooCommerce', 'upfront'),
			'selector' => '.woocommerce .block-type-content div.product div.summary div[itemprop="description"]'
		));

		UpFrontElementAPI::register_element(array(
			'group' => 'blocks',
			'id' => 'block-content-wc-product-page-short-description-details',
			'parent' => 'block-content',
			'name' => __('Product Page: Short Description', 'upfront'),
			'indent-in-selector' => true,
			'description' => __('Storefront: WooCommerce Description', 'upfront'),
			'selector' => '.woocommerce .block-type-content div.product div.woocommerce-product-details__short-description'
		));

		UpFrontElementAPI::register_element(array(
			'group' => 'blocks',
			'id' => 'block-content-wc-product-page-short-description-details-ul',
			'parent' => 'block-content',
			'name' => __('Product Page: Short Description List', 'upfront'),
			'indent-in-selector' => true,
			'description' => __('Storefront: WooCommerce Description List', 'upfront'),
			'selector' => '.woocommerce .block-type-content div.product div.woocommerce-product-details__short-description ul'
		));

		UpFrontElementAPI::register_element(array(
			'group' => 'blocks',
			'id' => 'block-content-wc-product-page-short-description-details-ul-li',
			'parent' => 'block-content',
			'name' => __('Product Page: Short Description List Item', 'upfront'),
			'indent-in-selector' => true,
			'description' => __('Storefront: WooCommerce Description List', 'upfront'),
			'selector' => '.woocommerce .block-type-content div.product div.woocommerce-product-details__short-description ul li'
		));

		UpFrontElementAPI::register_element(array(
			'group' => 'blocks',
			'id' => 'block-content-wc-product-page-short-description-details-a',
			'parent' => 'block-content',
			'name' => __('Product Page: Short Description Link', 'upfront'),
			'indent-in-selector' => true,
			'description' => __('Storefront: WooCommerce Description List', 'upfront'),
			'selector' => '.woocommerce .block-type-content div.product div.woocommerce-product-details__short-description a'
		));

		UpFrontElementAPI::register_element(array(
			'group' => 'blocks',
			'id' => 'block-content-wc-product-page-short-description-out-of-stock',
			'parent' => 'block-content',
			'name' => __('Product Page: Short Description Stock', 'upfront'),
			'indent-in-selector' => true,
			'description' => __('Storefront: WooCommerce Stock', 'upfront'),
			'selector' => '.woocommerce .block-type-content div.product .stock.out-of-stock'
		));
		
		UpFrontElementAPI::register_element(array(
			'group' => 'blocks',
			'id' => 'block-content-wc-product-page-short-description-meta',
			'parent' => 'block-content',
			'name' => __('Product Page: Short Description Meta', 'upfront'),
			'indent-in-selector' => true,
			'description' => __('Storefront: WooCommerce Meta', 'upfront'),
			'selector' => '.woocommerce .block-type-content div.product .product_meta'
		));

		UpFrontElementAPI::register_element(array(
			'group' => 'blocks',
			'id' => 'block-content-wc-product-page-short-description-meta-a',
			'parent' => 'block-content',
			'name' => __('Product Page: Short Description Meta Link', 'upfront'),
			'indent-in-selector' => true,
			'description' => __('Storefront: WooCommerce Meta link', 'upfront'),
			'selector' => '.woocommerce .block-type-content div.product .product_meta a'
		));
		
		UpFrontElementAPI::register_element(array(
			'group' => 'blocks',
			'id' => 'block-content-wc-product-page-button',
			'parent' => 'block-content',
			'name' => __('Product Page: Button', 'upfront'),
			'indent-in-selector' => true,
			'description' => 'Storefront: WooCommerce',
			'selector' => '.woocommerce .button a.product_type_simple.add_to_cart_button.ajax_add_to_cart'
		));

		UpFrontElementAPI::register_element(array(
			'group' => 'blocks',
			'id' => 'block-content-wc-product-page-button-item',
			'parent' => 'block-content',
			'name' => __('Product Page: Button', 'upfront'),
			'indent-in-selector' => true,
			'description' => __('Storefront: WooCommerce Button', 'upfront'),
			'selector' => '.woocommerce ul.products li.product .button'
		));

		UpFrontElementAPI::register_element(array(
			'group' => 'blocks',
			'id' => 'block-content-wc-product-page-button-text',
			'parent' => 'block-content',
			'name' => __('Product Page: Button text', 'upfront'),
			'indent-in-selector' => true,
			'description' => 'Storefront: WooCommerce',
			'selector' => '.woocommerce a.button.product_type_simple.add_to_cart_button.ajax_add_to_cart'
		));

		UpFrontElementAPI::register_element(array(
			'group' => 'blocks',
			'id' => 'block-content-wc-product-page-product-title',
			'parent' => 'block-content',
			'name' => __('Product Page: Product Title', 'upfront'),
			'indent-in-selector' => true,
			'description' => 'Storefront: WooCommerce',
			'selector' => '.woocommerce ul.products li.product h2.woocommerce-loop-product__title'
		));

		UpFrontElementAPI::register_element(array(
			'group' => 'blocks',
			'id' => 'block-content-wc-product-page-single-button',
			'parent' => 'block-content',
			'name' => __('Product Page: Single Button', 'upfront'),
			'indent-in-selector' => true,
			'description' => 'Storefront: WooCommerce',
			'selector' => '.woocommerce .button.single_add_to_cart_button.button.alt'
		));

		UpFrontElementAPI::register_element(array(
			'group' => 'blocks',
			'id' => 'block-content-wc-product-page-single-button-text',
			'parent' => 'block-content',
			'name' => __('Product Page: Single Button Text', 'upfront'),
			'indent-in-selector' => true,
			'description' => 'Storefront: WooCommerce',
			'selector' => '.woocommerce a.button.single_add_to_cart_button.button.alt'
		));

		UpFrontElementAPI::register_element(array(
			'group' => 'blocks',
			'id' => 'block-content-wc-product-page-input-text',
			'parent' => 'block-content',
			'name' => __('Product Page: Input Text', 'upfront'),
			'indent-in-selector' => true,
			'description' => 'Storefront: WooCommerce',
			'selector' => '.woocommerce .input-text.qty.text'
		));

		UpFrontElementAPI::register_element(array(
			'group' => 'blocks',
			'id' => 'block-content-wc-product-page-woocommerce-message',
			'parent' => 'block-content',
			'name' => __('Product Page: Woocommerce Message', 'upfront'),
			'indent-in-selector' => true,
			'description' => 'Storefront: WooCommerce',
			'selector' => '.woocommerce .woocommerce-message'
		));
	/* End Product Pages */


	/* Related Products */
		UpFrontElementAPI::register_element(array(
			'group' => 'blocks',
			'id' => 'block-content-wc-related-products',
			'parent' => 'block-content',
			'name' => __('Related Products Container', 'upfront'),
			'description' => 'Storefront: WooCommerce',
			'selector' => '.woocommerce .block-type-content div.related'
		));

		UpFrontElementAPI::register_element(array(
			'group' => 'blocks',
			'id' => 'block-content-wc-related-products-heading',
			'parent' => 'block-content',
			'name' => __('Related Products Heading', 'upfront'),
			'indent-in-selector' => true,
			'description' => 'Storefront: WooCommerce',
			'selector' => '.woocommerce .block-type-content div.related h2'
		));

		UpFrontElementAPI::register_element(array(
			'group' => 'blocks',
			'id' => 'block-content-wc-related-products-product',
			'parent' => 'block-content',
			'name' => __('Related Product Container', 'upfront'),
			'indent-in-selector' => true,
			'description' => 'Storefront: WooCommerce',
			'selector' => '.woocommerce .block-type-content div.related li.product'
		));
	/* End Related Products */

	/*	Place order	*/
		UpFrontElementAPI::register_element(array(
			'group' => 'blocks',
			'id' => 'block-content-wc-place-order-single-button',
			'parent' => 'block-content',
			'name' => __('Place order: Single Button', 'upfront'),
			'indent-in-selector' => true,
			'description' => 'Storefront: WooCommerce',
			'selector' => '.woocommerce button#place_order.button'
		));
		UpFrontElementAPI::register_element(array(
			'group' => 'blocks',
			'id' => 'block-content-wc-info',
			'parent' => 'block-content',
			'name' => __('Woocommerce Info', 'upfront'),
			'indent-in-selector' => true,
			'description' => 'Storefront: WooCommerce',
			'selector' => '.woocommerce .woocommerce-info'
		));
	/*	End Place order	*/


}
