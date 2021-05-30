<?php
if ( class_exists( 'UpFront_Shortcodes' ) ) {
$class_file = __DIR__ . '/dummy-image-block.php';
$icons = array(
	'path' => __DIR__ . '/',
	'url' => upfront_url() . '/library/blocks/dummy-image'
);
upfront_register_block('UpFrontVisualElementsBlockDummyImage', upfront_url() . '/library/blocks/dummy-image', $class_file, $icons);
}