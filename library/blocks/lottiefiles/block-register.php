<?php

$class_file = __DIR__ . '/lottiefiles-block.php';
$icons = array(
	'path' => __DIR__ . '/',
	'url' => upfront_url() . '/library/blocks/lottiefiles'
);
upfront_register_block('UpFrontLottieFilesBlock', upfront_url() . '/library/blocks/lottiefiles', $class_file, $icons);