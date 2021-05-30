<?php

$class_file = __DIR__ . '/slider.php';
$icons = array(
	'path' => __DIR__ . '/',
	'url' => upfront_url() . '/library/blocks/slider'
);
upfront_register_block('UpFrontSliderBlock', upfront_url() . '/library/blocks/slider', $class_file, $icons);