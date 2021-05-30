<?php

//Check that Gravity Forms is even enabled
if ( class_exists('RGForms')){
	$class_file = __DIR__ . '/gravity-forms.php';
	$icons = array(
		'path' => __DIR__ . '/',
		'url' => upfront_url() . '/library/blocks/gravity-forms'
	);
	upfront_register_block('UpFrontGravityFormsBlock', upfront_url() . '/library/blocks/gravity-forms', $class_file, $icons);	
}