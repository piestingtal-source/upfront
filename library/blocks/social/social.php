<?php

class UpFrontSocialBlock extends UpFrontBlockAPI {

	public $id;
	public $name;
	public $options_class;
	public $fixed_height;
	public $html_tag;
	public $description;
	public $categories;
	protected $show_content_in_grid;


	function __construct(){

		$this->id = 'social';	
		$this->name = 'Social';		
		$this->options_class = 'UpFrontSocialBlockOptions';	
		$this->fixed_height = true;	
		$this->html_tag = 'section';
		$this->description = __('Zeige eine Reihe von sozialen Symbolen an', 'upfront');
		$this->categories = array('social');
		$this->show_content_in_grid = false;

	}


	public function init() {

		add_filter( 'upload_mimes', array($this, 'add_uploader_svg_mime' ));

	}

	public function setup_elements() {

		$this->register_block_element(array(
			'id' => 'icons-wrapper',
			'name' => __('Symbol Container', 'upfront'),
			'selector' => 'ul.social-icons '
		));

		$this->register_block_element(array(
			'id' => 'icon',
			'name' => __('Symbol Container', 'upfront'),
			'selector' => 'li'
		));

		$this->register_block_element(array(
			'id' => 'icon-first',
			'name' => __('First Icon', 'upfront'),
			'selector' => 'li:first-child'
		));

		$this->register_block_element(array(
			'id' => 'icon-last',
			'name' => __('Last Icon', 'upfront'),
			'selector' => 'li:last-child'
		));

		$this->register_block_element(array(
			'id' => 'image',
			'name' => __('Image', 'upfront'),
			'selector' => 'img'
		));

		$this->register_block_element(array(
			'id' => 'image-link',
			'name' => __('Image Link', 'upfront'),
			'selector' => 'img a',
			'states' => array(
				'Hover' => 'img a:hover',
				'Clicked' => 'img a:active'
			)
		));

	}


	public static function dynamic_css($block_id, $block = false) {

		if ( !$block )
			$block = UpFrontBlocksData::get_block($block_id);

		$position = parent::get_setting($block, 'icons-position', '');
		$orientation = parent::get_setting($block, 'orientation', 'vertical');

		$css = '';

		/* Stack vertical add only bottom margin */
	  	if ( $orientation === 'vertical' ) {

	  		$css .= '
	  			#block-' . $block_id . ' ul.social-icons li { 
	  				margin-bottom: '. parent::get_setting($block, 'vertical-spacing', '10') .'px
	  			}

	  			#block-' . $block_id . ' ul.social-icons li:last-child { 
	  				margin-bottom: 0;
	  			}
	  		';


	  	}

		/* Float horizontal images and add right margin on all but last*/
		if ( $orientation === 'horizontal' ) {

	  		$css .= '
	  			#block-' . $block_id . ' ul.social-icons li {
	  			    display: inline-block;
	  				margin-right: '. parent::get_setting($block, 'horizontal-spacing', '10') .'px
	  			}

	  			#block-' . $block_id . ' ul.social-icons li:last-child { 
	  				margin-right: 0;
	  			}
	  		';

	  	}


		if ( $position ) {

	    $position_fragments = explode('_', $position);

           $horizontal_position = $position_fragments[1];
           $vertical_position = str_replace('center', 'middle', $position_fragments[0]);

	    $css .= '
	        #block-' . $block_id . ' div.social-icons-container {
	            display: table;
	            width: 100%;
	            height: 100%;
	        }

               #block-' . $block_id . ' ul.social-icons {
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

			echo '<div class="alert alert-yellow"><p>' . __('There are no icons to display.', 'upfront') . '</p></div>';

			return;

		}


		echo '<div class="social-icons-container">';
		echo '<ul class="social-icons">';

			$i = 0;
		  	foreach ( $icons as $icon ) {

		  		if ( !upfront_get('image', $icon) && !upfront_get('network', $icon) )
		  			continue;

		  		if ($icon_set == 'custom') {
		  			$img_url = $icon['image'];
		  		} else {
		  			$img_url = upfront_url().'/library/blocks/social/icons/' . $icon_set . '/' . upfront_fix_data_type(upfront_get('network', $icon));
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
		  				'target' => upfront_fix_data_type(upfront_get('link-target', $icon, false)) ? ' target="_blank"' : null
		  			)
		  		);

		  			echo '<li>';

		  			/* Open hyperlink if user added one for image */
		  			if ( $output['hyperlink']['href'] )
		  				echo '<a href="' . $output['hyperlink']['href'] . '"' . $output['hyperlink']['target'] . $output['hyperlink']['alt'] . '>';

				  			/* Don't forget to display the ACTUAL IMAGE */
				  			echo '<img src="' . $output['image']['src'] . '"' . $output['image']['alt'] . $output['image']['title'] . ' class="img-' . $i . '" ' . $svg_width . ' />';

		  			/* Closing tag for hyperlink */
		  			if ( $output['hyperlink']['href'] )
		  				echo '</a>';

		  			echo '</li>';

		  	}

	  	echo '</ul>';
		echo '</div>';

	}

	public function add_uploader_svg_mime( $mimes ){
		$mimes['svg'] = 'image/svg+xml';
		return $mimes;
	}

}

class UpFrontSocialBlockOptions extends UpFrontBlockOptionsAPI {

	public $tabs;
	public $inputs;


	function __construct($block_type_object){

		parent::__construct($block_type_object);

		$this->tabs = array(
			'general' => 'Allgemeines',
			'custom-icons-set' => __('Custom Icons', 'upfront')
		);

		$this->inputs = array(
			'general' => array(

				'icon-set' => array(
					'type' => 'select',
					'name' => 'icon-set',
					'label' => __('Icon Set', 'upfront'),
					'default' => 'peel-icons',
					'options' => 'get_icon_sets()',
					'tooltip' => __('Select custom to add your own icons or select one of these sets', 'upfront'),
					'toggle'    => array(
						'custom' => array(
							'hide' => array(
								'li[id*="-set"]:not(#sub-tab-custom-icons-set)'
							),
							'show' => array(
								'li#sub-tab-custom-icons-set'
							)
						),
						'peel-icons' => array(
							'hide' => array(
								'li[id*="-set"]:not(#sub-tab-peel-icons-set)'
							),
							'show' => array(
								'li#sub-tab-peel-icons-set'
							)
						),
						'soft-social' => array(
							'hide' => array(
								'li[id*="-set"]:not(#sub-tab-soft-social-set)'
							),
							'show' => array(
								'li#sub-tab-soft-social-set'
							)
						)
					),
					'callback' => '
						reloadBlockOptions()'

				),

				'layout-heading' => array(
					'name' => 'layout-heading',
					'type' => 'heading',
					'label' => __('Layout', 'upfront'),
					'tooltip' => __('Set the position of all icons in the block and the orientation before you add your icons.', 'upfront')
				),

				'icons-position' => array(
					'name' => 'icons-position',
					'label' => __('Position icons inside container', 'upfront'),
					'type' => 'select',
					'tooltip' => __('You can position the social icons in relation to the block using the positions provided', 'upfront'),
					'default' => 'none',
					'options' => array(
						'' => 'None',
						'top_left' => __('Oben links', 'upfront'),
						'top_center' => __('Oben Mitte', 'upfront'),
						'top_right' => __('Oben rechts', 'upfront'),
						'center_left' => __('Mitte links', 'upfront'),
						'center_center' => __('Mittig zentriert', 'upfront'),
						'center_right' => __('Mitte rechts', 'upfront'),
						'bottom_left' => __('Unten links', 'upfront'),
						'bottom_center' => __('Unten Mitte', 'upfront'),
						'bottom_right' => __('Unten rechts', 'upfront')
					)
				),

				'orientation' => array(
					'type' => 'select',
					'name' => 'orientation',
					'label' => __('Orientation', 'upfront'),
					'tooltip' => '',
					'options' => array(
						'vertical' => 'Vertical',
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
					'tooltip' => __('Display articles on top of each other (vertical) or side by side as a grid (horizontal)', 'upfront')
				),

				'horizontal-spacing' => array(
					'type' => 'text',
					'name' => 'horizontal-spacing',
					'label' => __('Horizontal Spacing', 'upfront'),
					'default' => '10',
					'unit' => 'px',
					'tooltip' => __('Set the px horizontal spacing between the icons.', 'upfront')
				),

				'vertical-spacing' => array(
					'type' => 'text',
					'name' => 'vertical-spacing',
					'label' => __('Vertical Spacing', 'upfront'),
					'default' => '10',
					'unit' => 'px',
					'tooltip' => __('Set the px vertical spacing between the icons.', 'upfront')
				),

				'svg-heading' => array(
					'name' => 'svg-heading',
					'type' => 'heading',
					'label' => __('SVG Images', 'upfront'),
					'tooltip' => __('Allows you to upload SVG Images. Many icons come with SVG versions of the icons. Using an SVG means it is easier to size the icons. With images like .png and .gif you need to manually size them in a graphics program.', 'upfront')
				),

				'use-svg' => array(
					'name' => 'use-svg',
					'label' => __('Use SVG?', 'upfront'),
					'type' => 'checkbox',
					'tooltip' => __('If you would like to upload SVG images check this option', 'upfront'),
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
					'label' => __('SVG Image Width', 'upfront'),
					'tooltip' => __('Set the width of all SVG\'s in the block. This also controls the width with a 1:1 ratio', 'upfront')
				)

			),

			'custom-icons-set' => array(
				'icons' => array(
					'type' => 'repeater',
					'name' => 'icons',
					'label' => __('Icons', 'upfront'),
					'inputs' => array(
						array(
							'type' => 'image',
							'name' => 'image',
							'label' => __('Image', 'upfront'),
							'default' => null
						),

						array(
							'type' => 'text',
							'name' => 'image-title',
							'label' => '"title"',
							'tooltip' => __('This will be used as the "title" attribute for the image.  The title attribute is beneficial for SEO (Search Engine Optimization) and will allow your visitors to move their mouse over the image and read about it.', 'upfront')
						),

						array(
							'type' => 'text',
							'name' => 'image-alt',
							'label' => '"alt"',
							'tooltip' => __('This will be used as the "alt" attribute for the image.  The alt attribute is <em>hugely</em> beneficial for SEO (Search Engine Optimization) and for general accessibility.', 'upfront')
						),

						array(
							'name' => 'link-heading',
							'type' => 'heading',
							'label' => __('Link Image', 'upfront')
						),

						array(
							'name' => 'link-url',
							'label' => __('Link URL?', 'upfront'),
							'type' => 'text',
							'tooltip' => __('Set the URL for the image to link to', 'upfront')
						),

						array(
							'name' => 'link-alt',
							'label' => '"alt"',
							'type' => 'text',
							'tooltip' => __('Set alternative text for the link', 'upfront')
						),

						array(
							'name' => 'link-target',
							'label' => __('New window?', 'upfront'),
							'type' => 'checkbox',
							'tooltip' => __('If you would like to open the link in a new window check this option', 'upfront'),
							'default' => false,
						)

					),
					'tooltip' => __('Upload the images that you would like to add to the image block.', 'upfront'),
					'sortable' => true,
					'limit' => false
				),
			),
		);

	}

	public function modify_arguments($args = false) {

		foreach ( self::get_icon_sets() as $icon_set => $icon_set_name ) {

			if ( $icon_set == 'custom' )
				continue;

			$this->tabs[$icon_set . '-set'] = ucwords(str_replace('-', ' ', $icon_set));

			$this->inputs[$icon_set . '-set'] = array(
				'icons'.$icon_set => array(
					'type' => 'repeater',
					'name' => 'icons' . $icon_set,
					'label' => __('Icons', 'upfront'),
					'inputs' => array(
						array(
							'type' => 'select',
							'name' => 'network',
							'label' => __('Network', 'upfront'),
							'default' => null,
							'options' => self::get_icons( $icon_set )
						),

						array(
							'type' => 'text',
							'name' => 'image-title',
							'label' => '"title"',
							'tooltip' => __('This will be used as the "title" attribute for the image.  The title attribute is beneficial for SEO (Search Engine Optimization) and will allow your visitors to move their mouse over the image and read about it.', 'upfront')
						),

						array(
							'type' => 'text',
							'name' => 'image-alt',
							'label' => '"alt"',
							'tooltip' => __('This will be used as the "alt" attribute for the image.  The alt attribute is <em>hugely</em> beneficial for SEO (Search Engine Optimization) and for general accessibility.', 'upfront')
						),

						array(
							'name' => 'link-heading',
							'type' => 'heading',
							'label' => __('Link Image', 'upfront')
						),

						array(
							'name' => 'link-url',
							'label' => __('Link URL?', 'upfront'),
							'type' => 'text',
							'tooltip' => __('Set the URL for the image to link to', 'upfront')
						),

						array(
							'name' => 'link-alt',
							'label' => '"alt"',
							'type' => 'text',
							'tooltip' => __('Set alternative text for the link', 'upfront')
						),

						array(
							'name' => 'link-target',
							'label' => __('New window?', 'upfront'),
							'type' => 'checkbox',
							'tooltip' => __('If you would like to open the link in a new window check this option', 'upfront'),
							'default' => false,
						)

					),
					'tooltip' => __('Upload the images that you would like to add to the image block.', 'upfront'),
					'sortable' => true,
					'limit' => false
				)
			);

		}

	}

	public static function get_icon_sets() {

		$path 			= UPFRONT_LIBRARY_DIR.'/blocks/social/icons';
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

		$icons_options['custom'] = __('Custom Icons', 'upfront');

		return $icons_options;

	}

	public static function get_icons( $icon_set ) {

		if ( $icon_set != 'custom' ) {

			$path = UPFRONT_LIBRARY_DIR.'/blocks/social/icons/' . $icon_set . '/';

			$results = scandir($path);

			$icons = array();

			foreach ($results as $result) {
		    	if ($result === '.' or $result === '..' or $result === '.DS_Store') continue;

			    if (!is_dir($path . '/' . $result)) {

			        $icons[$result] = preg_replace("/\\.[^.\\s]{3,4}$/", "", $result);

			    }
			}

			return $icons;

		}
	}

}