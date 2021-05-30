<?php

$class_file = __DIR__ . '/custom-code.php';
$icons = array(
	'path' => __DIR__ . '/',
	'url' => upfront_url() . '/library/blocks/custom-code'
);
upfront_register_block('UpFrontCustomCodeBlock', upfront_url() . '/library/blocks/custom-code', $class_file, $icons);