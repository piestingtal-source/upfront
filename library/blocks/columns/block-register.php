<?php
if ( class_exists( 'UpFront_Shortcodes' ) ) {
$class_file = __DIR__ . '/columns-block.php';
$icons = array(
	'path' => __DIR__ . '/',
	'url' => upfront_url() . '/library/blocks/columns'
);
upfront_register_block('UpFrontColumnsBlock', upfront_url() . '/library/blocks/columns', $class_file, $icons);
}