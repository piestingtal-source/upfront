<?php

$class_file = __DIR__ . '/image.php';
$icons = array(
	'path' => __DIR__ . '/',
	'url' => upfront_url() . '/library/blocks/image'
);
upfront_register_block('UpFrontImageBlock', upfront_url() . '/library/blocks/image', $class_file, $icons);