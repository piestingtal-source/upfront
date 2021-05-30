<?php

$class_file = __DIR__ . '/header.php';
$icons = array(
	'path' => __DIR__ . '/',
	'url' => upfront_url() . '/library/blocks/header'
);
upfront_register_block('UpFrontHeaderBlock', upfront_url() . '/library/blocks/header', $class_file, $icons);