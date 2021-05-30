<?php
if ( class_exists( 'UpFront_Shortcodes' ) ) {
$class_file = __DIR__ . '/content-to-cards-block.php';
$icons = array(
	'path' => __DIR__ . '/',
	'url' => upfront_url() . '/library/blocks/content-to-cards'
);
upfront_register_block('UpFrontBlockContentToCards', upfront_url() . '/library/blocks/content-to-cards', $class_file, $icons);
}