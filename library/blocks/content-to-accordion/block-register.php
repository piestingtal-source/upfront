<?php

$class_file = __DIR__ . '/content-to-accordion-block.php';
$icons = array(
	'path' => __DIR__ . '/',
	'url' => upfront_url() . '/library/blocks/content-to-accordion'
);
upfront_register_block('UpFrontContentToAccordionBlock', upfront_url() . '/library/blocks/content-to-accordion', $class_file, $icons);