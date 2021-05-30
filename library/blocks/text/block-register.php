<?php

$class_file = __DIR__ . '/text.php';
$icons = array(
	'path' => __DIR__ . '/',
	'url' => upfront_url() . '/library/blocks/text'
);
upfront_register_block('UpFrontTextBlock', upfront_url() . '/library/blocks/text', $class_file, $icons);