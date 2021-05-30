<?php

$class_file = __DIR__ . '/basic-heading-block.php';
$icons = array(
	'path' => __DIR__ . '/',
	'url' => upfront_url() . '/library/blocks/basic-heading'
);
upfront_register_block('UpFrontBasicHeadingBlock', upfront_url() . '/library/blocks/basic-heading', $class_file, $icons);