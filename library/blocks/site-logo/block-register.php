<?php

$class_file = __DIR__ . '/site-logo.php';
$icons = array(
	'path' => __DIR__ . '/',
	'url' => upfront_url() . '/library/blocks/site-logo'
);
upfront_register_block('UpFrontSiteLogoBlock', upfront_url() . '/library/blocks/site-logo', $class_file, $icons);