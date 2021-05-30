<?php
if ( class_exists( 'UpFront_Shortcodes' ) ) {
$class_file = __DIR__ . '/button-block.php';
$icons = array(
	'path' => __DIR__ . '/',
	'url' => upfront_url() . '/library/blocks/button'
);
upfront_register_block('UpFrontButtonBlock', upfront_url() . '/library/blocks/button', $class_file, $icons);
}