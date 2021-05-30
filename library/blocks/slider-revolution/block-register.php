<?php
if( class_exists('RevSlider')) {
$class_file = __DIR__ . '/slider-revolution-block.php';
$icons = array(
	'path' => __DIR__ . '/',
	'url' => upfront_url() . '/library/blocks/slider-revolution'
);
upfront_register_block('UpFrontSliderRevolution', upfront_url() . '/library/blocks/slider-revolution', $class_file, $icons);
}