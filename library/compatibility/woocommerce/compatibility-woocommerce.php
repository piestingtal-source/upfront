<?php
class UpFrontCompatibilityWooCommerce {


	public static function init() {

		/* Check requirements */
		if ( !self::check_requirements() )
			return;

		/* Load things */
		require_once UPFRONT_LIBRARY_DIR . '/compatibility/woocommerce/woocommerce-breadcrumbs.php';
		require_once UPFRONT_LIBRARY_DIR . '/compatibility/woocommerce/woocommerce-design-elements.php';
		require_once UPFRONT_LIBRARY_DIR . '/compatibility/woocommerce/woocommerce-design-defaults.php';

		/* Handle elements */
		add_action('upfront_register_elements', 'upfront_storefront_mp_register_elements', 50);
		add_filter('upfront_element_data_defaults', 'upfront_storefront_mp_design_defaults', 50);

		/* Remove WooCommerce Breadcrumbs */
		remove_action( 'woocommerce_before_main_content', 'woocommerce_breadcrumb', 20, 0 );

		/* Setup hooks */
		add_action('init', array(__CLASS__, 'disallow_edit_of_shop_page'));
		add_action('wp', array(__CLASS__, 'enqueue_styles'));

		/* Add theme support for WooCommerce */
		add_theme_support('woocommerce');

		/**
		 * https://github.com/woocommerce/woocommerce/wiki/Enabling-product-gallery-features-(zoom,-swipe,-lightbox)#enabling-the-gallery-in-themes-that-declare-wc-support
		 */
		add_theme_support( 'wc-product-gallery-zoom' );
		add_theme_support( 'wc-product-gallery-lightbox' );
		add_theme_support( 'wc-product-gallery-slider' );

	}


	public static function check_requirements() {

		if ( !class_exists('WooCommerce') )
			return false;

		return true;

	}


	public static function enqueue_styles() {

		if ( is_admin() )
			return;

		add_filter('upfront_general_css',function($general_css_fragments){
			$general_css_fragments['storefront-wooc'] = UPFRONT_LIBRARY_DIR . '/compatibility/woocommerce/upfront-storefront-wooc.css';
			return $general_css_fragments;
		});		

	}



	public static function disallow_edit_of_shop_page() {

		add_filter('upfront_layout_selector_no_edit_item_single-page-' . mp_get_page_id('shop'), '__return_true');

	}


}