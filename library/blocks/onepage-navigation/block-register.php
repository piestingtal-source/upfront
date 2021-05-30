<?php

$class_file = __DIR__ . '/onepage-navigation.php';
$icons = array(
	'path' => __DIR__ . '/',
	'url' => upfront_url() . '/library/blocks/onepage-navigation'
);
upfront_register_block( 'UpFrontOnePageNavBlock', upfront_url() . '/library/blocks/onepage-navigation', $class_file, $icons );