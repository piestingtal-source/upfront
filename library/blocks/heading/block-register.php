<?php
if ( class_exists( 'UpFront_Shortcodes' ) ) {
$class_file = __DIR__ . '/heading-block.php';
$icons = array(
	'path' => __DIR__ . '/',
	'url' => upfront_url() . '/library/blocks/heading'
);
upfront_register_block('UpFrontVisualElementsBlockHeadingOptions', upfront_url() . '/library/blocks/heading', $class_file, $icons);
}