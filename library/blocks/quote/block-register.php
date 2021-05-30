<?php
if ( class_exists( 'UpFront_Shortcodes' ) ) {
$class_file = __DIR__ . '/quote-block.php';
$icons = array(
	'path' => __DIR__ . '/',
	'url' => upfront_url() . '/library/blocks/quote'
);
upfront_register_block('UpFrontBlockQuote', upfront_url() . '/library/blocks/quote', $class_file, $icons);
}