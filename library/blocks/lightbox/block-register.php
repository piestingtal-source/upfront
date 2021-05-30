<?php
if ( class_exists( 'UpFront_Shortcodes' ) ) {
$class_file = __DIR__ . '/lightbox-block.php';
$icons = array(
	'path' => __DIR__ . '/',
	'url' => upfront_url() . '/library/blocks/lightbox'
);
upfront_register_block('UpFrontLightboxBlock', upfront_url() . '/library/blocks/lightbox', $class_file, $icons);
}
