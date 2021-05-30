<?php
if ( class_exists( 'UpFront_Shortcodes' ) ) {
$class_file = __DIR__ . '/youtube-block.php';
$icons = array(
	'path' => __DIR__ . '/',
	'url' => upfront_url() . '/library/blocks/youtube'
);
upfront_register_block('UpFrontYoutubeBlock', upfront_url() . '/library/blocks/youtube', $class_file, $icons);
}