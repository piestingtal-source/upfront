<?php

$class_file = __DIR__ . '/pin-board.php';
$icons = array(
	'path' => __DIR__ . '/',
	'url' => upfront_url() . '/library/blocks/pin-board'
);
upfront_register_block('UpFrontPinBoardCoreBlock', upfront_url() . '/library/blocks/pin-board', $class_file, $icons);