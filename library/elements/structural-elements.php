<?php
add_action('upfront_register_elements', 'upfront_register_structural_elements');
function upfront_register_structural_elements() {

	//Structure
	UpFrontElementAPI::register_group('structure', array(
		'name' => __('Struktur', 'upfront')
	));

		UpFrontElementAPI::register_element( array(
			'group'            => 'structure',
			'id'               => 'html',
			'name'             => __('HTML Dokument', 'upfront'),
			'selector'         => 'html',
			'disallow-nudging' => true,
			'properties' => array('fonts', 'background', 'borders', 'padding', 'corners', 'box-shadow', 'sizes', 'advanced', 'transition', 'outlines', 'animation', 'scroll')
		) );

		UpFrontElementAPI::register_element(array(
			'group' => 'structure',
			'id' => 'body',
			'name' => __('Body', 'upfront'),
			'selector' => 'body',
			'properties' => array('background', 'borders', 'padding', 'fonts'),
			'disallow-nudging' => true
		));

		UpFrontElementAPI::register_element(array(
			'group' => 'structure',
			'id' => 'wrapper',
			'name' => __('Wrapper', 'upfront'),
			'selector' => 'div.wrapper',
			'properties' => array('fonts', 'background', 'borders', 'padding', 'corners', 'box-shadow', 'sizes', 'advanced', 'transition', 'outlines', 'animation'),
			'states' => array(
				'Geschrumpft' => 'div.wrapper.is_shrinked',
				'Stuck' => 'div.wrapper.is_stuck',
			)
			
		));

	//Blocks
	UpFrontElementAPI::register_group('blocks', array(
		'name' => 'Blöcke',
		'description' => __('Einzelne Blocktypen und Blockelemente', 'upfront')
	));

}