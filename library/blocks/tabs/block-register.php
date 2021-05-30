<?php
if ( class_exists( 'UpFront_Shortcodes' ) ) {
$class_file = __DIR__ . '/tabs-block.php';
$icons = array(
	'path' => __DIR__ . '/',
	'url' => upfront_url() . '/library/blocks/tabs'
);
upfront_register_block('UpFrontVisualElementsBlockTabs', upfront_url() . '/library/blocks/tabs', $class_file, $icons);
}