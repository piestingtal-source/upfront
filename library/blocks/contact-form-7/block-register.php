<?php
if ( class_exists('WPCF7')){
$class_file = __DIR__ . '/contact-form-7.php';
$icons = array(
	'path' => __DIR__ . '/',
	'url' => upfront_url() . '/library/blocks/contact-form-7'
);
upfront_register_block('UpFrontContactForm7Block', upfront_url() . '/library/blocks/contact-form-7', $class_file, $icons);
}
