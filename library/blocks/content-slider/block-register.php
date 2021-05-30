<?php

$class_file = __DIR__ . '/content-slider-block.php';
$icons = array(
	'path' => __DIR__ . '/',
	'url' => upfront_url() . '/library/blocks/content-slider'
);
upfront_register_block('UpFrontContentSliderBlock', upfront_url() . '/library/blocks/content-slider', $class_file, $icons);