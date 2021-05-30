<?php

$class_file = __DIR__ . '/portfolio-block.php';
$icons = array(
	'path' => __DIR__ . '/',
	'url' => upfront_url() . '/library/blocks/portfolio'
);
upfront_register_block('UpFrontPortfolioBlocks', upfront_url() . '/library/blocks/portfolio', $class_file, $icons);
