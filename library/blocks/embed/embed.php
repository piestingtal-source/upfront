<?php

class UpFrontEmbedBlock extends UpFrontBlockAPI {


	public $id;
	public $name;
	public $options_class;
	public $description;
	public $categories;

	function __construct(){

		$this->id = 'embed';	
		$this->name = __('Einbetten', 'upfront');
		$this->options_class = 'UpFrontEmbedBlockOptions';	
		$this->description = __('Mit dem Einbettungsblock kannst Du YouTube, Vimeo oder einen anderen beliebten von oEmbed unterstützten Dienst einbetten.', 'upfront');
		$this->categories = array('core','medien', 'elemente');
	}


	function init() {

		add_filter('oembed_result', array(__CLASS__, 'add_embed_wmode_transparent'));
		add_filter('oembed_result', array(__CLASS__, 'add_iframe_wmode_transparent'));

	}


	function content($block) {

		if ( $embed_url = parent::get_setting($block, 'embed-url', false) ) {

			$block_width = UpFrontBlocksData::get_block_width($block);
			$block_height = UpFrontBlocksData::get_block_height($block);	

			$embed_code = wp_oembed_get($embed_url, array(
				'width' => $block_width,
				'height' => $block_height,
			));

			//Make the width and height exactly what the block's dimensions are.
			$embed_code = preg_replace(array('/width="\d+"/i', '/height="\d+"/i'), array('width="' . $block_width . '"', 'height="' . $block_height . '"'), $embed_code);

			echo $embed_code;

		} else {

			echo '<div class="alert alert-yellow"><p>' . __('Es ist kein Inhalt anzuzeigen. Bitte gib eine gültige Einbettungs-URL in den visuellen Editor ein.', 'upfront') . '</p></div>';

		}

	}


	/**
	 * Added to fix the issue of Flash appearing over drop down menus.
	 **/
	public static function add_embed_wmode_transparent($html) {

		//If no <embed> exists, don't do anything.
		if ( strpos($html, '<embed ') === false )
			return $html;

		return str_replace('</param><embed', '</param><param name="wmode" value="transparent"></param><embed wmode="transparent" ', $html);	

	}


	/**
	 * If the oEmbed HTML is using an iframe instead of <embed>, add a query var to the URL of the iframe to tell it to use wmode=transparent. 
	 **/
	public static function add_iframe_wmode_transparent($html) {

		//If no iframe exists, don't do anything.
		if ( strpos($html, '<iframe') === false )
			return $html;

		$url_search = preg_match_all('/src=[\'\"](.*?)[\'\"]/', $html, $url);		
		$url = $url[1][0];

		//Add the query var
		$url = add_query_arg(array('wmode' => 'transparent'), $url);

		//Place the URL back in
		return preg_replace('/src=[\'\"](.*?)[\'\"]/', 'src="' . $url . '"', $html);

	}

	function setup_elements() {

		$this->register_block_element(array(
			'id' => 'iframe',
			'name' => 'iframe',
			'selector' => '.fluid-width-video-wrapper iframe',
		));

		$this->register_block_element(array(
			'id' => 'object',
			'name' => 'Object',
			'selector' => '.fluid-width-video-wrapper object',
		));

		$this->register_block_element(array(
			'id' => 'embed',
			'name' => 'embed',
			'selector' => '.fluid-width-video-wrapper embed',
		));

		$this->register_block_element(array(
			'id' => 'div',
			'name' => 'div',
			'selector' => 'div',
		));

	}


}


class UpFrontEmbedBlockOptions extends UpFrontBlockOptionsAPI {

	public $tabs;
	public $inputs;

	function __construct($block_type_object){

		parent::__construct($block_type_object);

		$this->tabs = array(
			'embed-options' => __('Einbettungsoptionen', 'upfront')
		);

		$this->inputs = array(
			'embed-options' => array(
				'embed-notice' => array(
					'name' => 'embed-notice',
					'type' => 'notice',
					'notice' => __('Gib die URL <strong>(kein HTML)</strong> zu den Medien ein, die Du einbetten möchtest. Wir unterstützen die meisten wichtigen Video- und Fotoseiten, einschließlich (aber nicht beschränkt auf) YouTube, Vimeo, Flickr, blip.tv, Hulu und mehr. <em>Benötigst Du weitere Informationen zu oEmbed?  <a href="http://codex.wordpress.org/Embeds" target="_blank">Weiterlesen &rarr;</a></em>', 'upfront')
				),

				'embed-url' => array(
					'type' => 'text',
					'name' => 'embed-url',
					'label' => __('Einbettungs-URL', 'upfront'),
					'default' => null,
					'placeholder' => __('URL des Mediums', 'upfront')
				)
			)
		);

	}

}