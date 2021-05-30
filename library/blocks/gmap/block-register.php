<?php
if ( class_exists( 'UpFront_Shortcodes' ) ) {
$class_file = __DIR__ . '/gmap-block.php';
$icons = array(
	'path' => __DIR__ . '/',
	'url' => upfront_url() . '/library/blocks/gmap'
);
upfront_register_block('UpFrontBlockGmap', upfront_url() . '/library/blocks/gmap', $class_file, $icons);
}