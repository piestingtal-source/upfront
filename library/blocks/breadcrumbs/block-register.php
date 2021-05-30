<?php

$class_file = __DIR__ . '/breadcrumbs.php';
$icons = array(
	'path' => __DIR__ . '/',
	'url' => upfront_url() . '/library/blocks/breadcrumbs'
);
upfront_register_block('UpFrontBreadcrumbsBlock', upfront_url() . '/library/blocks/breadcrumbs', $class_file, $icons);
