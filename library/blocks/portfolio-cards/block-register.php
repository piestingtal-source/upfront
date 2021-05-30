<?php

$class_file = __DIR__ . '/portfolio-cards-block.php';
$icons = array(
	'path' => __DIR__ . '/',
	'url' => upfront_url() . '/library/blocks/portfolio-cards'
);
upfront_register_block('UpFrontBlockPortfolioCards', upfront_url() . '/library/blocks/portfolio-cards', $class_file, $icons);
