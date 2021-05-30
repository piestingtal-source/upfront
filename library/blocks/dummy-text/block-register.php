<?php
if ( class_exists( 'UpFront_Shortcodes' ) ) {
$class_file = __DIR__ . '/dummy-text-block.php';
$icons = array(
	'path' => __DIR__ . '/',
	'url' => upfront_url() . '/library/blocks/dummy-text'
);
upfront_register_block('UpFrontDummyTextBlock', upfront_url() . '/library/blocks/dummy-text', $class_file, $icons);
}