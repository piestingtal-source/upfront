<?php

$class_file = __DIR__ . '/divider.php';
$icons = array(
	'path' => __DIR__ . '/',
	'url' => upfront_url() . '/library/blocks/divider'
);
upfront_register_block('UpFrontDividerBlock', upfront_url() . '/library/blocks/divider', $class_file, $icons);