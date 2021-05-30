<?php

$class_file = __DIR__ . '/embed.php';
$icons = array(
	'path' => __DIR__ . '/',
	'url' => upfront_url() . '/library/blocks/embed'
);
upfront_register_block('UpFrontEmbedBlock', upfront_url() . '/library/blocks/embed', $class_file, $icons);