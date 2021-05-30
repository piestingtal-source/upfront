<?php
if ( class_exists( 'UpFront_Shortcodes' ) ) {
$class_file = __DIR__ . '/content-to-tabs-block.php';
$icons = array(
	'path' => __DIR__ . '/',
	'url' => upfront_url() . '/library/blocks/content-to-tabs'
);
upfront_register_block('UpFrontBlockContentToTabs', upfront_url() . '/library/blocks/content-to-tabs', $class_file, $icons);
}