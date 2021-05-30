<?php
if ( class_exists( 'UpFront_Shortcodes' ) ) {
$class_file = __DIR__ . '/dailymotion-block.php';
$icons = array(
	'path' => __DIR__ . '/',
	'url' => upfront_url() . '/library/blocks/dailymotion'
);
upfront_register_block('UpFrontDailymotionBlock', upfront_url() . '/library/blocks/dailymotion', $class_file, $icons);
}
