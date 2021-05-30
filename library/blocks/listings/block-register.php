<?php

$class_file = __DIR__ . '/listings.php';
$icons = array(
	'path' => __DIR__ . '/',
	'url' => upfront_url() . '/library/blocks/listings'
);
upfront_register_block('UpFrontListingsBlock', upfront_url() . '/library/blocks/listings', $class_file, $icons);