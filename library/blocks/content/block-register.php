<?php

$class_file = __DIR__ . '/content.php';
$icons = array(
	'path' => __DIR__ . '/',
	'url' => upfront_url() . '/library/blocks/content'
);
upfront_register_block('UpFrontContentBlock', upfront_url() . '/library/blocks/content', $class_file, $icons);