<?php
if ( class_exists('WooCommerce')){
$class_file = __DIR__ . '/store-products-block.php';
$icons = array(
	'path' => __DIR__ . '/',
	'url' => upfront_url() . '/library/blocks/store-products'
);
upfront_register_block('UpFrontStoreBlockProducts', upfront_url() . '/library/blocks/store-products', $class_file, $icons);
}