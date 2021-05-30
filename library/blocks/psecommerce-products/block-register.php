<?php
if ( class_exists('PSeCommerce')){
$class_file = __DIR__ . '/psecommerce-products-block.php';
$icons = array(
	'path' => __DIR__ . '/',
	'url' => upfront_url() . '/library/blocks/psecommerce-products'
);
upfront_register_block('UpFrontPSeCommerceProductsBlock', upfront_url() . '/library/blocks/psecommerce-products', $class_file, $icons);
}
