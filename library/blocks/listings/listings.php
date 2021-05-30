<?php

class UpFrontListingsBlock extends UpFrontBlockAPI {


	public $id;
	public $name;
	public $options_class;
	public $description;
	static $block = null;
	public $categories;


	function __construct(){

		$this->id = 'listings';
		$this->name = __('Auflistungen', 'upfront');
		$this->options_class = 'UpFrontListingsBlockOptions';
		$this->description = __('Listet Deine Beiträge, benutzerdefinierten Beitragstypen, Kategorien, Tags, benutzerdefinierten Taxonomien, Autoren, Seiten und Kommentare auf.', 'upfront');		
		$this->categories = array('core','content');
	}


	function init() {

		require_once 'block-options.php';
		require_once UPFRONT_LIBRARY_DIR . '/blocks/listings/content-display.php';		
	}

	function setup_elements() {

		$this->register_block_element(array(
			'id' => 'list-items',
			'name' => __('Listencontainer', 'upfront'),
			'selector' => 'ul.list-items'
		));

		$this->register_block_element(array(
			'id' => 'list-item',
			'name' => __('Listenelement', 'upfront'),
			'selector' => 'ul.list-items li'
		));

		$this->register_block_element(array(
			'id' => 'list-item-link',
			'name' => __('Listenelement Link', 'upfront'),
			'selector' => 'ul.list-items li a'
		));

	}

	function content($block) {

		$listing_block_display = new UpFrontListingBlockDisplay($block);
		$listing_block_display->display();

	}

}
