<?php

$class_file = __DIR__ . '/post-slider-block.php';
$icons = array(
	'path' => __DIR__ . '/',
	'url' => upfront_url() . '/library/blocks/post-slider'
);
upfront_register_block('UpFrontPostSliderBlock', upfront_url() . '/library/blocks/post-slider', $class_file, $icons);