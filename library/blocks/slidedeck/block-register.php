<?php

if ( isset($GLOBALS['SlideDeckPlugin']) && is_object($GLOBALS['SlideDeckPlugin']) ){
	$class_file = __DIR__ . '/slidedeck.php';
	$icons = array(
		'path' => __DIR__ . '/',
		'url' => upfront_url() . '/library/blocks/slidedeck'
	);
	upfront_register_block('UpFrontSlideDeckBlock', upfront_url() . '/library/blocks/slidedeck', $class_file, $icons);
}
