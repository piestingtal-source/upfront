<?php
add_action('upfront_register_elements', 'upfront_register_default_elements');
function upfront_register_default_elements() {

	UpFrontElementAPI::register_group('default-elements', __('Globales Styling', 'upfront') );

	UpFrontElementAPI::register_element(array(
		'id' => 'default-text',
		'name' => __('Text', 'upfront'),
		'description' => __('&lt;body&gt;', 'upfront'),
		'properties' => array('fonts'),
		'default-element' => true,
		'selector' => 'body'
	));

	UpFrontElementAPI::register_element(array(
		'id' => 'default-paragraph',
		'name' => __('Absatz', 'upfront'),
		'description' => __('Alle &lt;p&gt; Elemente', 'upfront'),
		'properties' => array('margins'),
		'default-element' => true,
		'selector' => 'body p'
	));

	UpFrontElementAPI::register_element(array(
		'id' => 'default-hyperlink',
		'name' => __('Hyperlink', 'upfront'),
		'default-element' => true,
		'selector' => 'a'
	));

	UpFrontElementAPI::register_element(array(
		'id' => 'default-headings',
		'name' => __('Überschriften', 'upfront'),
		'description' => '&lt;H3&gt;, &lt;H2&gt;, &lt;H1&gt;',
		'default-element' => true,
		'selector' => 'h1, h2, h3'
	));

	UpFrontElementAPI::register_element(array(
		'id' => 'default-heading-h1',
		'name' => __('Überschrift 1', 'upfront'),
		'description' => '&lt;H1&gt;',
		'default-element' => true,
		'selector' => 'h1',
		'parent' => 'default-headings'
	));

	UpFrontElementAPI::register_element(array(
		'id' => 'default-heading-h2',
		'name' => __('Überschrift 2', 'upfront'),
		'description' => '&lt;H2&gt;',
		'default-element' => true,
		'selector' => 'h2',
		'parent' => 'default-headings'
	));

	UpFrontElementAPI::register_element(array(
		'id' => 'default-heading-h3',
		'name' => __('Überschrift 3', 'upfront'),
		'description' => '&lt;H3&gt;',
		'default-element' => true,
		'selector' => 'h3',
		'parent' => 'default-headings'
	));

	UpFrontElementAPI::register_element(array(
		'id' => 'default-sub-headings',
		'name' => __('Unterüberschriften', 'upfront'),
		'description' => '&lt;H4&gt;, &lt;H5&gt;, &lt;H6&gt;',
		'default-element' => true,
		'selector' => 'h4, h5, h6'
	));

	UpFrontElementAPI::register_element(array(
		'id' => 'default-sub-heading-h4',
		'parent' => 'default-sub-headings',
		'name' => __('Überschrift 4', 'upfront'),
		'description' => '&lt;H4&gt;',
		'default-element' => true,
		'selector' => 'h4'
	));

	UpFrontElementAPI::register_element(array(
		'id' => 'default-sub-heading-h5',
		'parent' => 'default-sub-headings',
		'name' => __('Überschrift 5', 'upfront'),
		'description' => '&lt;H5&gt;',
		'default-element' => true,
		'selector' => 'h5'
	));

	UpFrontElementAPI::register_element(array(
		'id' => 'default-sub-heading-h6',
		'parent' => 'default-sub-headings',
		'name' => __('Überschrift 6', 'upfront'),
		'description' => '&lt;H6&gt;',
		'default-element' => true,
		'selector' => 'h6'
	));

	UpFrontElementAPI::register_element(array(
		'id' => 'default-form',
		'name' => __('Formular', 'upfront'),
		'description' => '&lt;form&gt;',
		'default-element' => true,
		'selector' => 'form'
	));

	UpFrontElementAPI::register_element(array(
		'id' => 'default-form-label',
		'name' => __('Beschriftung', 'upfront'),
		'description' => __('Formularbeschriftung', 'upfront'),
		'default-element' => true,
		'selector' => 'form label',
		'parent' => 'default-form'
	));

	UpFrontElementAPI::register_element(array(
		'id' => 'default-form-input',
		'name' => __('Eingabe', 'upfront'),
		'description' => __('Eingaben & Textbereiche', 'upfront'),
		'default-element' => true,
		'selector' => 'input[type="text"], input[type="password"], input[type="email"], input[type="tel"], input[type="number"], input[type="month"], input[type="time"], input[type="url"], input[type="week"], textarea, select',
		'states' => array(
			'Focus' => 'input[type="text"]:focus, input[type="password"]:focus, input[type="email"]:focus, input[type="tel"]:focus, input[type="number"]:focus, input[type="month"]:focus, input[type="time"]:focus, input[type="url"]:focus, input[type="week"]:focus, textarea:focus'
		),
		'parent' => 'default-form'
	));

	UpFrontElementAPI::register_element(array(
		'id' => 'default-form-button',
		'name' => __('Schaltfläche', 'upfront'),
		'description' => __('Schaltflächen & Eingaben senden', 'upfront'),
		'default-element' => true,
		'selector' => 'input[type="submit"], input[type="button"], button, .button',
		'states' => array(
			'Hover' => 'input[type="submit"]:hover, input[type="button"]:hover, button:hover',
			'Active' => 'input[type="submit"]:active, input[type="button"]:active, button:active'
		),
		'parent' => 'default-form'
	));

	UpFrontElementAPI::register_element(array(
		'id' => 'default-blockquote',
		'name' => __('Blockquote', 'upfront'),
		'properties' => array('background', 'borders', 'fonts', 'padding', 'corners', 'box-shadow', 'overflow'),
		'default-element' => true,
		'selector' => 'blockquote'
	));

	UpFrontElementAPI::register_element(array(
		'id' => 'default-block',
		'name' => __('Block', 'upfront'),
		'properties' => array('background', 'borders', 'fonts', 'padding', 'corners', 'box-shadow', 'overflow'),
		'default-element' => true,
		'selector' => '.block'
	));

	UpFrontElementAPI::register_element(array(
		'id' => 'block-title',
		'name' => __('Block Titel', 'upfront'),
		'selector' => '.block-title',
		'default-element' => true
	));

	UpFrontElementAPI::register_element(array(
		'id' => 'block-title-inner',
		'name' => __('Blocktitel Inner', 'upfront'),
		'selector' => '.block-title span',
		'default-element' => true,
		'parent' => 'block-title'
	));

	UpFrontElementAPI::register_element(array(
		'id' => 'block-title-link',
		'name' => __('Blocktitel-Link', 'upfront'),
		'selector' => '.block-title a',
		'default-element' => true,
		'parent' => 'block-title'
	));

	UpFrontElementAPI::register_element(array(
		'id' => 'block-subtitle',
		'name' => __('Block Untertitel', 'upfront'),
		'selector' => '.block-subtitle',
		'default-element' => true
	));

}