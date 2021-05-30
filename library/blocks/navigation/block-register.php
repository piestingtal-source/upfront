<?php

$class_file = __DIR__ . '/navigation.php';
$icons = array(
	'path' => __DIR__ . '/',
	'url' => upfront_url() . '/library/blocks/navigation'
);
upfront_register_block( 'UpFrontNavigationBlock', upfront_url() . '/library/blocks/navigation', $class_file, $icons);