<?php
if ( class_exists( 'UpFront_Shortcodes' ) ) {
$class_file = __DIR__ . '/post-data-block.php';
$icons = array(
	'path' => __DIR__ . '/',
	'url' => upfront_url() . '/library/blocks/post-data'
);
upfront_register_block('UpFrontBlockPostData', upfront_url() . '/library/blocks/post-data', $class_file, $icons);
}