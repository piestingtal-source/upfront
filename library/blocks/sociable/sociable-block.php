<?php

class UpFrontSociableBlock extends UpFrontBlockAPI {
	
	public $id;					
	public $name;					
	public $options_class;	
	public $fixed_height;	
	public $html_tag;
	public $description;
	public $categories;

	function __construct(){

		$this->id = 'sociable';	
		$this->name = 'Sociable';		
		$this->options_class = 'UpFrontSociableBlockOptions';	
		$this->fixed_height = true;
		$this->description = __('Zeige eine Reihe von Social Network Symbolen an.', 'upfront');
		$this->categories = array('social');

	}

	protected $show_content_in_grid = false;


	
	public function init() {

		add_filter( 'upload_mimes', array($this, 'add_uploader_svg_mime' ));

	}
	
	public function setup_elements() {

		$this->register_block_element(array(
			'id' => 'icons-wrapper',
			'name' => 'Symbol Container',
			'selector' => 'ul.sociable-icons '
		));

		$this->register_block_element(array(
			'id' => 'icon',
			'name' => 'Symbol Container ',
			'selector' => 'li'
		));

		$this->register_block_element(array(
			'id' => 'icon-first',
			'name' => 'Erstes Symbol',
			'selector' => 'li:first-child'
		));

		$this->register_block_element(array(
			'id' => 'icon-last',
			'name' => 'Letztes Symbol',
			'selector' => 'li:last-child'
		));
		
		$this->register_block_element(array(
			'id' => 'image',
			'name' => 'Bild',
			'selector' => 'img'
		));

		$this->register_block_element(array(
			'id' => 'image-link',
			'name' => 'Bild-Link',
			'selector' => 'img a',
			'states' => array(
				'Hover' => 'img a:hover',
				'Geklickt' => 'img a:active'
			)
		));

		$this->register_block_element(array(
			'id' => 'icon paragraph',
			'name' => 'Symbol Absatz',
			'selector' => 'li p'
		));

		$this->register_block_element(array(
			'id' => 'icon link',
			'name' => 'Symbol Link',
			'selector' => 'li a'
		));

		$this->register_block_element(array(
			'id' => 'icon span',
			'name' => 'Symbol span',
			'selector' => 'li span'
		));

		$this->register_block_element(array(
			'id' => 'icon h1',
			'name' => 'Symbol H1',
			'selector' => 'li h1'
		));

		$this->register_block_element(array(
			'id' => 'icon h2',
			'name' => 'Symbol H2',
			'selector' => 'li h2'
		));

		$this->register_block_element(array(
			'id' => 'icon h3',
			'name' => 'Symbol H3',
			'selector' => 'li h3'
		));

		$this->register_block_element(array(
			'id' => 'icon h4',
			'name' => 'Symbol H4',
			'selector' => 'li h4'
		));

		$this->register_block_element(array(
			'id' => 'icon h5',
			'name' => 'Symbol H5',
			'selector' => 'li h5'
		));

		$this->register_block_element(array(
			'id' => 'icon h6',
			'name' => 'Symbol H6',
			'selector' => 'li h6'
		));
		
	}


	public static function dynamic_css($block_id, $block = false) {

		if ( !$block )
			$block = UpFrontBlocksData::get_block($block_id);

		$position 		= parent::get_setting($block, 'icons-position', '');
		$orientation 	= parent::get_setting($block, 'orientation', 'vertical');

		$css = '';

		/* Stack vertical add only bottom margin */
	  	if ( $orientation === 'vertical' ) {

	  		$css .= '
	  			#block-' . $block_id . ' ul.sociable-icons li { 
	  				margin-bottom: '. parent::get_setting($block, 'vertical-spacing', '10') .'px
	  			}

	  			#block-' . $block_id . ' ul.sociable-icons li:last-child { 
	  				margin-bottom: 0;
	  			}
	  		';


	  	}

		/* Float horizontal images and add right margin on all but last*/
		if ( $orientation === 'horizontal' ) {

	  		$css .= '
	  			#block-' . $block_id . ' ul.sociable-icons li {
	  			    display: inline-block;
	  				margin-right: '. parent::get_setting($block, 'horizontal-spacing', '10') .'px
	  			}

	  			#block-' . $block_id . ' ul.sociable-icons li:last-child { 
	  				margin-right: 0;
	  			}
	  		';

	  	}


		if ( $position ) {

	    $position_fragments = explode('_', $position);

           $horizontal_position = $position_fragments[1];
           $vertical_position = str_replace('center', 'middle', $position_fragments[0]);

	    $css .= '
	        #block-' . $block_id . ' div.sociable-icons-container {
	            display: table;
	            width: 100%;
	            height: 100%;
	        }

               #block-' . $block_id . ' ul.sociable-icons {
                   display: table-cell;
                   text-align: ' . $horizontal_position . ';
                   vertical-align: ' . $vertical_position . ';
               }
           ';

       }

		return $css;
		
	}
	
	public function content($block) {

		$icon_set 	= UpFrontBlockAPI::get_setting($block, 'icon-set', 'peel-icons');
		$use_svg 	= parent::get_setting($block, 'use-svg', false);
		$svg_width 	= ($use_svg && parent::get_setting($block, 'svg-width')) ? 'width="' . parent::get_setting($block, 'svg-width') . '"' : '';

		if ($icon_set == 'custom') {
			$icons = parent::get_setting($block, 'icons' , array());
		} else {
			$icons = parent::get_setting($block, 'icons'.$icon_set , array());
		}

		$block_width 	= UpFrontBlocksData::get_block_width($block);
		$block_height 	= UpFrontBlocksData::get_block_height($block);			
		$has_icons 		= false;

		foreach ( $icons as $icon ) {

			if ( upfront_get('image', $icon) || upfront_get('network', $icon) ) {
				$has_icons = true;
				break;
			}

		}

		if ( !$has_icons) {

			echo '<div class="alert alert-yellow"><p>There are no icons to display.</p></div>';
			
			return;

		}

		echo '<div class="sociable-icons-container">';
		echo '<ul class="sociable-icons">';

			$i = 0;
		  	foreach ( $icons as $icon ) {

		  		if ( !upfront_get('image', $icon) && !upfront_get('network', $icon) )
		  			continue;

		  		if ($icon_set == 'custom') {
		  			$img_url = $icon['image'];
		  		} else {
		  			$img_url = upfront_url() . '/library/blocks/sociable/icons/' . $icon_set . '/' . upfront_fix_data_type(upfront_get('network', $icon));
		  		}

		  		if(upfront_get('icon-size', $icon, false) != ''){
		  			$size 		= upfront_get('icon-size', $icon, false);
		  			$img_url 	.= '-' . $size . '.png';
		  		}else{
		  			if ($icon_set != 'custom') {
						$img_url	.= '-64x64.png';
					}
		  		}

		  		$i++;
		  		$output = array(
		  			'image' => array(
		  				'src' => $img_url,
		  				'alt' => upfront_fix_data_type(upfront_get('image-alt', $icon, false)) ? ' alt="' . upfront_fix_data_type(upfront_get('image-alt', $icon, false)) . '"' : null,
		  				'title' => upfront_fix_data_type(upfront_get('image-title', $icon)) ? ' title="' . upfront_fix_data_type(upfront_get('image-title', $icon)) . '"' : null,
		  			),

		  			'hyperlink' => array(
		  				'href' => upfront_fix_data_type(upfront_get('link-url', $icon)),
		  				'alt' => upfront_fix_data_type(upfront_get('link-alt', $icon, false)) ? ' alt="' . upfront_fix_data_type(upfront_get('link-alt', $icon, false)) . '"' : null,
		  				'target' => upfront_fix_data_type(upfront_get('link-target', $icon, false)) ? ' target="_blank"' : null,
		  				'rel' => upfront_fix_data_type(upfront_get('link-rel', $icon, 'noreferrer')) ? upfront_get('link-rel', $icon, 'noreferrer') : 'noreferrer',
		  			)
		  		);

		  			echo '<li>';

		  			if( isset( $icon['before-icon'] ) && !empty( $icon['before-icon'] ) ){
		  				echo trim($icon['before-icon']);
		  			}

		  			/* Open hyperlink if user added one for image */
		  			if ( $output['hyperlink']['href'] ){
		  				echo '<a href="' . $output['hyperlink']['href'] . '"' . $output['hyperlink']['target'] . $output['hyperlink']['alt']. 'rel="'.$output['hyperlink']['rel'] .'" ' . '>';
		  			}
		  			
		  			/* Don't forget to display the ACTUAL IMAGE */
		  			echo '<img src="' . $output['image']['src'] . '"' . $output['image']['alt'] . $output['image']['title'] . ' class="img-' . $i . '" ' . $svg_width . ' />';

		  			/* Closing tag for hyperlink */
		  			if ( $output['hyperlink']['href'] ){
		  				echo '</a>';
		  			}


		  			if( isset( $icon['after-icon'] ) && !empty( $icon['after-icon'] ) ){
		  				echo trim($icon['after-icon']);
		  			}

		  			echo '</li>';
		  		
		  	}
	  
	  	echo '</ul>';
		echo '</div>';
		
	}

	public function add_uploader_svg_mime( $mimes ){
		$mimes['svg'] = 'image/svg+xml';
		return $mimes;
	}

	public static function enqueue_action($block_id, $block = false) {
			
			/* CSS */
			wp_enqueue_style('upfront-sociable', upfront_url() . '/library/blocks/sociable/css/sociable.css');

			/* JS */
			wp_enqueue_script('upfront-sociable', upfront_url() . '/library/blocks/sociable/js/sociable.js', array('jquery'));

		}
	
}
class UpFrontSociableBlockOptions extends UpFrontBlockOptionsAPI {
	
	public $tabs = array(
		'general' 			=> 'Social Symbole',
		'custom-icons-set' 	=> 'Benutzerdefinierte Symbole'
	);

	public $sets = array(
		'custom' => array(
						'hide' => array(
							'li[id*="-set"]:not(#sub-tab-custom-icons-set)'
						),
						'show' => array(
							'li#sub-tab-custom-icons-set'
						)
					),
		'filled-outline-by-roundicons' => array(
											'hide' => array(
												'li[id*="-set"]:not(#sub-tab-filled-outline-by-roundicons-set)'
											),
											'show' => array(
												'li#sub-tab-filled-outline-by-roundicons-set'
											)
										),
		'flat-by-pixan' => array(
											'hide' => array(
												'li[id*="-set"]:not(#sub-tab-flat-by-pixan-set)'
											),
											'show' => array(
												'li#sub-tab-flat-by-pixan-set'
											)
										),
		'glyph-by-betterwork' => array(
											'hide' => array(
												'li[id*="-set"]:not(#sub-tab-glyph-by-betterwork-set)'
											),
											'show' => array(
												'li#sub-tab-glyph-by-betterwork-set'
											)
										),
		'handdrawn-by-side-project' => array(
											'hide' => array(
												'li[id*="-set"]:not(#sub-tab-handdrawn-by-side-project-set)'
											),
											'show' => array(
												'li#sub-tab-handdrawn-by-side-project-set'
											)
										),
		'outline-by-roundicons' => array(
											'hide' => array(
												'li[id*="-set"]:not(#sub-tab-outline-by-roundicons-set)'
											),
											'show' => array(
												'li#sub-tab-outline-by-roundicons-set'
											)
										),

		'capsocial-square-flat-by-litvin' => array(
											'hide' => array(
												'li[id*="-set"]:not(#sub-tab-capsocial-square-flat-by-litvin-set)'
											),
											'show' => array(
												'li#sub-tab-capsocial-square-flat-by-litvin-set'
											)
										),
	);

	public $inputs = array(
		'general' => array(

			'icon-set' => array(
				'type' => 'select',
				'name' => 'icon-set',
				'label' => 'Symbol Set',
				'default' => 'peel-icons',
				'options' => 'get_icon_sets()',
				'tooltip' => 'Wähle Benutzerdefiniert, um Deine eigenen Symbole hinzuzufügen, oder wähle eines dieser Sets aus',
				'toggle'  => array(),
				'callback' => 'reloadBlockOptions()'
				
			),

			'layout-heading' => array(
				'name' => 'layout-heading',
				'type' => 'heading',
				'label' => 'Layout',
				'tooltip' => 'Lege die Position aller Symbole im Block und die Ausrichtung fest, bevor Du Deine Symbole hinzufügst.'
			),

			'icons-position' => array(
				'name' => 'icons-position',
				'label' => 'Positioniere die Symbole im Behälter',
				'type' => 'select',
				'tooltip' => 'Du kannst die sozialen Symbole in Bezug auf den Block mithilfe der angegebenen Positionen positionieren',
				'default' => 'none',
				'options' => array(
					'' => 'None',
					'top_left' => 'Oben links',
					'top_center' => 'Oben Mitte',
					'top_right' => 'Oben rechts',
					'center_left' => 'Mitte links',
					'center_center' => 'Mittig zentriert',
					'center_right' => 'Mitte rechts',
					'bottom_left' => 'Unten links',
					'bottom_center' => 'Unten in der Mitte',
					'bottom_right' => 'Unten rechts'
				)
			),

			'orientation' => array(
				'type' => 'select',
				'name' => 'orientation',
				'label' => 'Orientierung',
				'tooltip' => '',
				'options' => array(
					'vertical' => 'Vertikal',
					'horizontal' => 'Horizontal'
				),
				'toggle'    => array(
					'vertical' => array(
						'show' => array(
							'#input-vertical-spacing'
						),
						'hide' => array(
							'#input-horizontal-spacing'
						),
					),
					'horizontal' => array(
						'show' => array(
							'#input-horizontal-spacing'
						),
						'hide' => array(
							'#input-vertical-spacing'
						),
					)
				),
				'tooltip' => 'Artikel übereinander (vertikal) oder nebeneinander als Raster (horizontal) anzeigen'
			),

			'horizontal-spacing' => array(
				'type' => 'text',
				'name' => 'horizontal-spacing',
				'label' => 'Horizontaler Abstand',
				'default' => '10',
				'unit' => 'px',
				'tooltip' => 'Stelle den horizontalen px-Abstand zwischen den Symbolen ein.'
			),

			'vertical-spacing' => array(
				'type' => 'text',
				'name' => 'vertical-spacing',
				'label' => 'Vertikaler Abstand',
				'default' => '10',
				'unit' => 'px',
				'tooltip' => 'Stelle den vertikalen px-Abstand zwischen den Symbolen ein.'
			),

			'svg-heading' => array(
				'name' => 'svg-heading',
				'type' => 'heading',
				'label' => 'SVG-Bilder',
				'tooltip' => 'Ermöglicht das Hochladen von SVG-Bildern. Viele Symbole werden mit SVG-Versionen der Symbole geliefert. Durch die Verwendung einer SVG-Datei ist die Größe der Symbole einfacher. Bei Bildern wie .png und .gif musst Du die Größe in einem Grafikprogramm manuell ändern.'
			),

			'use-svg' => array(
				'name' => 'use-svg',
				'label' => 'SVG verwenden?',
				'type' => 'checkbox',
				'tooltip' => 'Wenn Du SVG-Bilder hochladen möchtest, aktiviere diese Option',
				'default' => false,
				'toggle'    => array(
					'true' => array(
						'show' => array(
							'#input-svg-width',
							'#input-svg-height'
						)
					),
					'false' => array(
						'hide' => array(
							'#input-svg-width',
							'#input-svg-height'
						)
					)
				),
			),

			'svg-width' => array(
				'type' => 'text',
				'name' => 'svg-width',
				'label' => 'SVG-Bildbreite',
				'tooltip' => 'Stelle die Breite aller SVGs im Block ein. Dies steuert auch die Breite mit einem Verhältnis von 1:1'
			)

		),

		'custom-icons-set' => array(
			'icons' => array(
				'type' => 'repeater',
				'name' => 'icons',
				'label' => 'Symbole',
				'inputs' => array(
					array(
						'type' => 'image',
						'name' => 'image',
						'label' => 'Bild',
						'default' => null
					),

					array(
						'type' => 'text',
						'name' => 'image-title',
						'label' => '"title"',
						'tooltip' => 'Dies wird als "title"-Attribut für das Bild verwendet. Das title-Attribut ist für SEO (Search Engine Optimization) von Vorteil und ermöglicht Deinen Besuchern, die Maus über das Bild zu bewegen und darüber zu lesen.'
					),

					array(
						'type' => 'text',
						'name' => 'image-alt',
						'label' => '"alt"',
						'tooltip' => 'Dies wird als "alt"-Attribut für das Bild verwendet. Das alt-Attribut ist <em>äußerst</em> vorteilhaft für SEO (Search Engine Optimization) und für die allgemeine Zugänglichkeit.'
					),

					array(
						'name' => 'link-heading',
						'type' => 'heading',
						'label' => 'Bild Link'
					),

					array(
						'name' => 'link-url',
						'label' => 'Link URL?',
						'type' => 'text',
						'tooltip' => 'Lege die URL für das Bild fest, zu dem ein Link erstellt werden soll'
					),

					array(
						'name' => 'link-alt',
						'label' => '"alt"',
						'type' => 'text',
						'tooltip' => 'Lege alternativen Text für den Link fest'
					),

					array(
						'name' => 'link-target',
						'label' => 'Neues Fenster?',
						'type' => 'checkbox',
						'tooltip' => 'Wenn Du den Link in einem neuen Fenster öffnen möchtest, aktiviere diese Option',
						'default' => false,
					),

					array(
						'name' => 'link-rel',
						'label' => 'Rel',
						'type'	=> 'text',
						'tooltip' => 'Hier kannst Du einen Wert für das rel-Attribut hinzufügen. Beispielwerte: noreferrer, noopener, nofollow, lightbox',
						'default' => 'noreferrer',
					),

					array(
						'name' => 'before-icon',
						'label' => 'Vor dem Symbol',
						'type'	=> 'wysiwyg',
						'tooltip' => 'Füge Inhalte vor dem Symbol hinzu',
					),

					array(
						'name' => 'after-icon',
						'label' => 'Nach dem Symbol',
						'type'	=> 'wysiwyg',
						'tooltip' => 'Füge Inhalte nach dem Symbol hinzu',
					),

				),
				'tooltip' => 'Lade die Bilder hoch, die Du dem Bildblock hinzufügen möchtest.',
				'sortable' => true,
				'limit' => false
			),
		),
	);


	public function modify_arguments($args = false) {

		foreach ( self::get_icon_sets() as $icon_set => $icon_set_name ) {

			if ( $icon_set == 'custom' )
				continue;

			$this->inputs['general']['icon-set']['toggle'] = $this->sets;

			$this->tabs[$icon_set . '-set'] 	= ucwords(str_replace('-', ' ', $icon_set));
			$this->inputs[$icon_set . '-set'] 	= array(

				'icons'.$icon_set => array(
					'type' => 'repeater',
					'name' => 'icons' . $icon_set,
					'label' => 'Icons',
					'inputs' => array(
						array(
							'type' => 'select',
							'name' => 'network',
							'label' => 'Netzwerk',
							'default' => null,
							'options' => self::get_icons( $icon_set )
						),

						array(
							'type' => 'select',
							'name' => 'icon-size',
							'label' => 'Symbolgröße',
							'options' => array(
								'64x64' 	=> '64 x 64',
								'128x128' => '128 x 128',
								'256x256' => '256 x 256',
								'512x512' => '512 x 512',
							)
						),

						array(
							'type' => 'text',
							'name' => 'image-title',
							'label' => '"title"',
							'tooltip' => 'Dies wird als "title"-Attribut für das Bild verwendet. Das title-Attribut ist für SEO (Search Engine Optimization) von Vorteil und ermöglicht Deinen Besuchern, die Maus über das Bild zu bewegen und darüber zu lesen.'
						),

						array(
							'type' => 'text',
							'name' => 'image-alt',
							'label' => '"alt"',
							'tooltip' => 'Dies wird als "alt"-Attribut für das Bild verwendet. Das alt-Attribut ist <em>äußerst</em> vorteilhaft für SEO (Search Engine Optimization) und für die allgemeine Zugänglichkeit.'
						),

						array(
							'name' => 'link-heading',
							'type' => 'heading',
							'label' => 'Link Bild'
						),

						array(
							'name' => 'link-url',
							'label' => 'Link URL?',
							'type' => 'text',
							'tooltip' => 'Lege die URL für das Bild fest, zu dem ein Link erstellt werden soll'
						),

						array(
							'name' => 'link-alt',
							'label' => '"alt"',
							'type' => 'text',
							'tooltip' => 'Lege alternativen Text für den Link fest'
						),

						array(
							'name' => 'link-target',
							'label' => 'Neues Fenster?',
							'type' => 'checkbox',
							'tooltip' => 'Wenn Du den Link in einem neuen Fenster öffnen möchtest, aktiviere diese Option',
							'default' => false,
						),

						array(
							'name' => 'link-rel',
							'label' => 'Rel',
							'type'	=> 'text',
							'tooltip' => 'Hier kannst Du einen Wert für das rel-Attribut hinzufügen. Beispielwerte: noreferrer, noopener, nofollow, lightbox',
							'default' => 'noreferrer',
						),

						array(
							'name' => 'before-icon',
							'label' => 'Vor dem Symbol',
							'type'	=> 'wysiwyg',
							'tooltip' => 'Füge Inhalte vor dem Symbol hinzu',
						),

						array(
							'name' => 'after-icon',
							'label' => 'Nach dem Symbol',
							'type'	=> 'wysiwyg',
							'tooltip' => 'Füge Inhalte nach dem Symbol hinzu',
						),
						/*
						array(
							'name' => 'icon-preview',
							'label' => 'Icon preview',
							'type' => 'checkbox',
						),*/

						/*
						array(
							'name' 		=> 'author',
							'label' 	=> 'About the author <p style="margin-left: 20px;margin-top: 10px;">'.self::get_author_data( $icon_set ).'</p>',
							'type' 		=> 'heading',
							'tooltip' 	=> 'Information about the author of the icon set',
						)*/

					),
					'tooltip' => 'Lade die Bilder hoch, die Du dem Bildblock hinzufügen möchtest.',
					'sortable' => true,
					'limit' => false
				)
			);

		}

	}

	public static function get_author_data($icon_set ){

		if ( $icon_set != 'custom' ) {

			$path 		= UPFRONT_LIBRARY_DIR.'/blocks/sociable/icons/' . $icon_set . '/author.txt';
			return 	file_get_contents($path);

		}

	}

	public static function get_icon_sets() {

		$path 			= UPFRONT_LIBRARY_DIR.'/blocks/sociable/icons/';
		$results 		= scandir($path);
		$icons_options 	= array();

		foreach ($results as $result) {

		    if ( $result === '.' || $result === '..' || $result === '.DS_Store') {
			    continue;
		    }

		    if ( is_dir($path . '/' . $result) ) {
		        $icons_options[$result] = ucwords(str_replace('-', ' ', $result));
		    }

		}

		$icons_options['custom'] = 'Custom Icons';

		return $icons_options;

	}

	public static function get_icons( $icon_set ) {

		if ( $icon_set != 'custom' ) {

			$path 		= UPFRONT_LIBRARY_DIR.'/blocks/sociable/icons/' . $icon_set . '/';
			$results 	= scandir($path);
			$icons 		= array();

			foreach ($results as $result) {


		    	if ($result === '.' or $result === '..' or $result === '.DS_Store' or $result === 'author.txt') continue;

			    if (!is_dir($path . '/' . $result)) {

					$icon_network = str_replace('512x512', '', $result);
					$icon_network = str_replace('256x256', '', $icon_network);
					$icon_network = str_replace('128x128', '', $icon_network);
					$icon_network = str_replace('64x64', '', $icon_network);
					$icon_network = preg_replace("/\\.[^.\\s]{3,4}$/", "", $icon_network);
					$icon_network = rtrim($icon_network,'-');

			        $icons[$icon_network] = $icon_network;

			    }
			}

			return $icons;

		}
	}
	
}