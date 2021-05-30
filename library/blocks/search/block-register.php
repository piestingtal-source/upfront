<?php

$class_file = __DIR__ . '/search.php';
$icons = array(
	'path' => __DIR__ . '/',
	'url' => upfront_url() . '/library/blocks/search'
);
upfront_register_block('UpFrontSearchBlock', upfront_url() . '/library/blocks/search', $class_file, $icons);