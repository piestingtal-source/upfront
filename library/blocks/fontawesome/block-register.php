<?php

$class_file = __DIR__ . '/fontawesome-block.php';
$icons = array(
	'path' => __DIR__ . '/',
	'url' => upfront_url() . '/library/blocks/fontawesome'
);
upfront_register_block('UpFrontFontAwesomeBlock', upfront_url() . '/library/blocks/fontawesome', $class_file, $icons);