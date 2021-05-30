<?php

$class_file = __DIR__ . '/audio.php';
$icons = array(
	'path' => __DIR__ . '/',
	'url' => upfront_url() . '/library/blocks/audio'
);
upfront_register_block('UpFrontAudioBlock', upfront_url() . '/library/blocks/audio', $class_file, $icons);
