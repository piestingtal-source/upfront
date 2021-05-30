<?php
if ( class_exists( 'UpFront_Shortcodes' ) ) {
$class_file = __DIR__ . '/box-block.php';
$icons = array(
	'path' => __DIR__ . '/',
	'url' => upfront_url() . '/library/blocks/box'
);
upfront_register_block('UpFrontBoxBlock', upfront_url() . '/library/blocks/box', $class_file, $icons);
}