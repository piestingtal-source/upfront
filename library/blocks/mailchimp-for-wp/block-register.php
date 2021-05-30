<?php
if( class_exists('mc4wp')) {
$class_file = __DIR__ . '/mailchimp-for-wp.php';
$icons = array(
	'path' => __DIR__ . '/',
	'url' => upfront_url() . '/library/blocks/mailchimp-for-wp'
);
upfront_register_block('UpFrontMailchimpForWPBlock', upfront_url() . '/library/blocks/mailchimp-for-wp', $class_file, $icons);
}