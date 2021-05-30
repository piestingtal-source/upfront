<?php

$class_file = __DIR__ . '/accordion-block.php';
$icons = array(
	'path' => __DIR__ . '/',
	'url' => upfront_url() . '/library/blocks/accordion'
);
upfront_register_block('UpFrontAccordionBlock', upfront_url() . '/library/blocks/accordion', $class_file, $icons);

