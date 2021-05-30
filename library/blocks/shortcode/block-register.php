<?php

$class_file = __DIR__ . '/shortcode-block.php';
$icons = array(
	'path' => __DIR__ . '/',
	'url' => upfront_url() . '/library/blocks/shortcode'
);
upfront_register_block('UpFrontShortcodesBlock', upfront_url() . '/library/blocks/shortcode', $class_file, $icons);