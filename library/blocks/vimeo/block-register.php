<?php
if ( class_exists( 'UpFront_Shortcodes' ) ) {
$class_file = __DIR__ . '/vimeo-block.php';
$icons = array(
	'path' => __DIR__ . '/',
	'url' => upfront_url() . '/library/blocks/vimeo'
);
upfront_register_block('UpFrontBlockVimeo', upfront_url() . '/library/blocks/vimeo', $class_file, $icons);
}
