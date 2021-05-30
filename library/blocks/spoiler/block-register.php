<?php
if ( class_exists( 'UpFront_Shortcodes' ) ) {
$class_file = __DIR__ . '/spoiler-block.php';
$icons = array(
	'path' => __DIR__ . '/',
	'url' => upfront_url() . '/library/blocks/spoiler'
);
upfront_register_block('UpFrontBlockSpoiler', upfront_url() . '/library/blocks/spoiler', $class_file, $icons);
}
