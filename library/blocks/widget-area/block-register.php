<?php

$class_file = __DIR__ . '/widget-area.php';
$icons = array(
	'path' => __DIR__ . '/',
	'url' => upfront_url() . '/library/blocks/widget-area'
);
upfront_register_block('UpFrontWidgetAreaBlock', upfront_url() . '/library/blocks/widget-area', $class_file, $icons);