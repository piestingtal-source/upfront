<?php

$class_file = __DIR__ . '/footer.php';
$icons = array(
	'path' => __DIR__ . '/',
	'url' => upfront_url() . '/library/blocks/footer'
);
upfront_register_block('UpFrontFooterBlock', upfront_url() . '/library/blocks/footer', $class_file, $icons);