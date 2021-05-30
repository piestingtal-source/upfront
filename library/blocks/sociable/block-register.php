<?php

$class_file = __DIR__ . '/sociable-block.php';
$icons = array(
	'path' => __DIR__ . '/',
	'url' => upfront_url() . '/library/blocks/sociable'
);
upfront_register_block('UpFrontSociableBlock', upfront_url() . '/library/blocks/sociable', $class_file, $icons);