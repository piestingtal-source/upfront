<?php

$class_file = __DIR__ . '/video.php';
$icons = array(
	'path' => __DIR__ . '/',
	'url' => upfront_url() . '/library/blocks/video'
);
upfront_register_block('UpFrontVideoBlock', upfront_url() . '/library/blocks/video', $class_file, $icons);