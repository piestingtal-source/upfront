<?php

$class_file = __DIR__ . '/social.php';
$icons = array(
	'path' => __DIR__ . '/',
	'url' => upfront_url() . '/library/blocks/social'
);
upfront_register_block('UpFrontSocialBlock', upfront_url() . '/library/blocks/social', $class_file, $icons);