<?php

class UpFrontNavigationBlock extends UpFrontBlockAPI {

	public $id;
	public $name;
	public $options_class;
	public $fixed_height;
	public $html_tag;
	public $attributes;
	public $description;
	public $categories;
	protected $show_content_in_grid;

	/* Use this to pass the block from static function to static function */
	static public $block = null;
	static private $menu_sub_check_cache = array();
	static private $wp_nav_menu_cache = array();



	function __construct(){

		$this->id = 'navigation';
		$this->name = 'Navigation';
		$this->options_class = 'UpFrontNavigationBlockOptions';
		$this->fixed_height = false;
		$this->html_tag = 'nav';
		$this->attributes = array(
			'itemscope' => '',
			'itemtype' => 'http://schema.org/SiteNavigationElement'
		);
		$this->description = 'Die Navigation ist das Menü, in dem alle Seiten Deiner Webseite angezeigt werden.';
		$this->categories = array('core','navigation');
		$this->show_content_in_grid = true;

	}


	public static function init() {

		if ( is_admin() ) {
			return;
		}

		wp_register_script( 'jquery-hoverintent', upfront_url() . '/library/media/js/jquery.hoverintent.js', array( 'jquery' ));
		wp_enqueue_style('upfront-navigation-block', upfront_url() . '/library/blocks/navigation/css/navigation.css');

	}


	public static function init_action( $block_id, $block = false ) {

		if ( ! $block ) {
			$block = UpFrontBlocksData::get_block( $block_id );
		}

		$name = UpFrontBlocksData::get_block_name( $block ) . ' &mdash; ' . 'Layout: ' . UpFrontLayout::get_name( $block['layout'] );

		register_nav_menu( 'navigation_block_' . $block_id, $name );



	}


	public static function enqueue_action( $block_id, $block, $original_block = null ) {

		$dependencies = array();

		/* Handle sub menus with super fish */
		if ( self::does_menu_have_subs( $block ) ) {

			$dependencies[] = 'jquery';

			if ( parent::get_setting( $block, 'hover-intent', true ) ) {
				$dependencies[] = 'jquery-hoverintent';
			}

			wp_enqueue_script( 'upfront-superfish', upfront_url() . '/library/blocks/navigation/js/jquery.superfish.js', array_unique( $dependencies ) );

		}

		/* SelectNav... Responsive Select */
		if ( UpFrontResponsiveGrid::is_active() && parent::get_setting( $block, 'responsive-select', true ) ) {

			switch ( parent::get_setting($block, 'responsive-method', 'select') ) {


				case 'vertical':
					wp_enqueue_script( 'upfront-slicknav', upfront_url() . '/library/media/js/jquery.slicknav.js', array( 'jquery' ) );
					wp_enqueue_style( 'upfront-slicknav', upfront_url() . '/library/media/css/slicknav.css' );
					break;

				case 'slide-out':
					wp_enqueue_script( 'upfront-pushy', upfront_url() . '/library/media/js/pushy.js', array( 'jquery' ) );
					wp_enqueue_style( 'upfront-pushy', upfront_url() . '/library/media/css/pushy.css' );
					break;

				default:
					wp_enqueue_script( 'upfront-selectnav', upfront_url() . '/library/blocks/navigation/js/selectnav.js', array( 'jquery' ) );
					break;
			}

		}

	}


	function content( $block ) {


		self::$block = $block;

		/* Variables */
		$vertical = parent::get_setting( $block, 'vert-nav-box', false );
		$alignment = parent::get_setting( $block, 'alignment', 'left' );

		$search = parent::get_setting( $block, 'enable-nav-search', false );
		$search_position = parent::get_setting( $block, 'nav-search-position', 'right' );


		/* Classes */
		$nav_classes = array();

		$nav_classes[] = $vertical ? 'nav-vertical' : 'nav-horizontal';
		$nav_classes[] = 'nav-align-' . $alignment;
		$nav_classes[] = 'responsive-menu-align-' . parent::get_setting($block, 'responsive-menu-label-position', 'right');


		if ( $search && ! $vertical ) {

			$nav_classes[] = 'nav-search-active';
			$nav_classes[] = 'nav-search-position-' . $search_position;

		}

		// This run only when the VE just load, after the refreshBlock is not necesary
		if( UpFrontRoute::is_visual_editor_iframe() && parent::get_setting($block, 'responsive-method', 'select') == 'slide-out'){
			
			echo '<script type="text/javascript">';
			echo $this->dynamic_js( $block['id'], $block );
			echo '</script>';
			
			echo '<style>';
			echo $this->dynamic_css( $block['id'], $block );
			echo '</style>';
		}
		


		$nav_classes = trim( implode( ' ', array_unique( $nav_classes ) ) );


		/* Use legacy ID */
		$block['id'] = UpFrontBlocksData::get_legacy_id( $block );

		$nav_location = 'navigation_block_' . $block['id'];

		echo '<div class="' . $nav_classes . '">';

		echo self::get_wp_nav_menu( $block );

		if ( parent::get_setting($block, 'responsive-method', 'select') == 'slide-out' ) {

			switch ( parent::get_setting($block, 'responsive-menu-label-position', 'right') ) {
				case 'right':
					$toggle_class = ' pushy-menu-toggle-right';
					break;

				case 'left':
					$toggle_class = ' pushy-menu-toggle-left';
					break;

				case 'center':
					$toggle_class = ' pushy-menu-toggle-center';
					break;
			}


			echo '<span class="pushy-menu-toggle' . $toggle_class . '">
					<span class="pushy-menu-toggle-text">' . parent::get_setting($block, 'responsive-menu-label', __('Navigation', 'upfront')) . '</span>
					<span class="pushy-menu-toggle-icon">
                        <span class="pushy-menu-toggle-icon-bar"></span>
                        <span class="pushy-menu-toggle-icon-bar"></span>
                        <span class="pushy-menu-toggle-icon-bar"></span>
                    </span>
				</span>';
		}

		if ( $search && ! $vertical ) {

			echo '<div class="nav-search">';

			echo upfront_get_search_form( parent::get_setting( $block, 'nav-search-placeholder', null ) );

			echo '</div>';

		}

		echo '</div>';

	}


	public static function dynamic_css( $block_id, $block, $original_block = null ) {

		$selector = '#block-' . UpFrontBlocksData::get_legacy_id($block);

		/* If this block is a mirror, then pull the settings from the block that's mirroring that way the dimensions are correct */
		if ( is_array( $original_block ) ) {

			$block_id = $original_block['id'];
			$block = $original_block;

			$selector .= '.block-original-' . UpFrontBlocksData::get_legacy_id($block);

		}

		$item_height = parent::get_setting($block, 'item-height', null) ? parent::get_setting($block, 'item-height', null) : UpFrontBlocksData::get_block_height($block);

		$css = $selector . ' .nav-horizontal {
				line-height: ' . $item_height . 'px;
				float: left;
				width: 100%;
			}

			' . $selector . ' .nav-horizontal ul.menu {
				line-height: ' . $item_height . 'px;
				width: 100%;
			}

			' . $selector . ' .nav-horizontal ul.menu > li > a, 
			' . $selector . ' .nav-search-active .nav-search { 
				height: ' . $item_height . 'px;
				line-height: ' . $item_height . 'px;
			}';


		$use_breakpoint = parent::get_setting($block, 'use-responsive-menu-breakpoint', true);
		$breakpoint = parent::get_setting($block, 'responsive-menu-breakpoint', 600);

		switch ( parent::get_setting($block, 'responsive-method', 'select') ) {
			case 'vertical':
				$css .= "\n\n";

				if ( $use_breakpoint ) {
					$css .= '@media only screen and (max-width: ' . $breakpoint . 'px) {';
					$css .= $selector . ' ul.menu {
							display: none; 
						}

						' . $selector . ' .slicknav_menu {
							display: block;
							background-color: #fff;
						}';


					$css .= '}';
				}

				$css .= "\n\n";

				break;

			case 'slide-out':


				$css .= "\n\n";
				$overlay_color = ( !empty( $block['settings']['slide-out-overlay-color'] )) ? $block['settings']['slide-out-overlay-color']: 'rgba(0, 0, 0, 0.5)';
				$css .= 'body.pushy-open-right .pushy-site-overlay {';
				$css .= '	background-color:' . $overlay_color;
				$css .= '}';

				if ( $use_breakpoint ) {
					$css .= '@media only screen and (max-width: ' . $breakpoint . 'px) {';
					$css .= $selector . ' ul.menu {
					    display: none;
					  }

					  ' . $selector . ' .pushy-menu-toggle {
					    display: inline-block;
					  }';

					$css .= '}';
				}

				$css .= "\n\n";

				break;
		}

		return $css;

	}


	public static function dynamic_js( $block_id, $block, $original_block = null ) {

		$js = null;

		$selector = ! is_array( $original_block ) ? '#block-' . $block_id : '.block-original-' . $original_block['id'];

		/* Superfish */
		if ( self::does_menu_have_subs( $block ) ) {

			switch ( parent::get_setting( $block, 'effect', 'fade' ) ) {
				case 'none':
					$animation = '{height:"show"}';
					$speed = '0';
					break;

				case 'fade':
					$animation = '{opacity:"show"}';
					$speed = "'fast'";
					break;

				case 'slide':
					$animation = '{height:"show"}';
					$speed = "'fast'";
					break;
			}

			$js .= 'jQuery(function() {

					if ( typeof jQuery().superfish != "function" )
						return false;

					jQuery("' . $selector . '").find("ul.menu").superfish({
						delay: 200,
						animation: ' . $animation . ',
						speed: ' . $speed . ',
						onBeforeShow: function() {
							var parent = jQuery(this).parent();

							var subMenuParentLink = jQuery(this).siblings(\'a\');
							var subMenuParents = jQuery(this).parents(\'.sub-menu\');

							if ( subMenuParents.length > 0 || jQuery(this).parents(\'.nav-vertical\').length > 0 ) {
								jQuery(this).css(\'marginLeft\',  parent.outerWidth());
								jQuery(this).css(\'marginTop\',  -subMenuParentLink.outerHeight());
							}
						}
					});		
				});' . "\n\n";

		}

		/* SelectNav */
		if ( UpFrontResponsiveGrid::is_active() && parent::get_setting( $block, 'responsive-select', true ) ) {

			if ( upfront_get('upfront-trigger') == 'load-block-content' ) {
				$js .= 'jQuery(document).ready(function($){
					$(".pushy").remove();						
					$(".pushy-site-overlay").remove();
					$(".block-type-navigation .slicknav_menu").remove();
				});';
			}

			switch ( parent::get_setting($block, 'responsive-method', 'select') ) {
				case 'vertical':
					switch ( parent::get_setting($block, 'responsive-menu-label-position', 'right') ) {
						case 'right':
							$toggle_class = ' slicknav_btn-right';
							$menu_class = ' slicknav-right';
							break;

						case 'left':
							$toggle_class = ' slicknav_btn-left';
							$menu_class = ' slicknav-left';
							break;

						case 'center':
							$toggle_class = ' slicknav_btn-center';
							$menu_class = ' slicknav-center';
							break;
					}

					$js .= 'jQuery(document).ready(function($){

							$("' . $selector . ' ul.menu").slicknav({
								prependTo: "' . $selector . ' .block-content",
								label: "' . parent::get_setting($block, 'responsive-menu-label', 'Navigation') . '",
								additionalBtnClass: "' . $toggle_class . '",
								additionalMenuClass: "' . $menu_class . '"
							});

						});' . "\n\n";

					break;

				case 'slide-out':
					$slide_out_pos = parent::get_setting($block, 'slide-out-menu-position', 'left');

					$js .= 'jQuery(document).ready(function($){

						if ( typeof window.UpFrontPushy != "function" )
							return false;


						var $pushyMenu = $("' . $selector . '").find("ul.menu").first().clone();
							$pushyMenu.addClass("pushy pushy-'  . $slide_out_pos . '").removeClass("menu");

						var id = "slide-out-" + "'  . $slide_out_pos . '";

						$pushyMenu.attr("id", id);

						$pushyMenu.find("li").addClass("pushy-link");
						$pushyMenu.find("ul").each(function() {
							$(this).removeAttr("style");
							$(this).siblings("a").attr("href", "#");
							$(this).closest("li").addClass("pushy-submenu");
						});

						$(".pushy-site-overlay").remove();
						$(\'<div class="pushy-site-overlay" />\').appendTo("body");

						$(".pushy").remove();
						$pushyMenu.prependTo(\'body\');

						$("#wpadminbar").appendTo("body");

						window.UpFrontPushy();

						});' . "\n\n";

					break;

				default:

					$js .= 'jQuery(document).ready(function($){

						if ( typeof window.selectnav != "function" )
							return false;

						selectnav($("' . $selector . '").find("ul.menu")[0], {
							label: "' . parent::get_setting($block, 'responsive-menu-label', 'Navigation') . '",
							nested: true,
							indent: "-",
							activeclass: "current-menu-item"
						});

						$("' . $selector . '").find("ul.menu").addClass("selectnav-active");

						});' . "\n\n";


					break;
			}

		}
		return $js;

	}


	public static function get_wp_nav_menu( $block ) {

		$nav_location = 'navigation_block_' . UpFrontBlocksData::get_legacy_id( $block );

		if ( upfront_get( $nav_location, self::$wp_nav_menu_cache ) !== null ) {
			return upfront_get( $nav_location, self::$wp_nav_menu_cache );
		}

		/* Add filter to add home link */
		self::$block = $block;

		add_filter( 'wp_nav_menu_items', array( __CLASS__, 'home_link_filter' ));
		add_filter( 'wp_list_pages', array( __CLASS__, 'home_link_filter' ) );
		add_filter( 'wp_page_menu', array( __CLASS__, 'fix_legacy_nav' ) );

		$nav_menu_args = array(
			'theme_location' => $nav_location,
			'container' => false,
			'echo' => false
		);

		if ( upfront_get( 've-live-content-query', $block ) ) {

			$nav_menu_args['link_before'] = '<span>';
			$nav_menu_args['link_after'] = '</span>';

		}

		self::$wp_nav_menu_cache[ $nav_location ] = wp_nav_menu( apply_filters( 'upfront_navigation_block_query_args', $nav_menu_args, $block ) );

		/* Remove filter for home link so other non-navigation blocks are modified */
		remove_filter( 'wp_nav_menu_items', array( __CLASS__, 'home_link_filter' ) );
		remove_filter( 'wp_list_pages', array( __CLASS__, 'home_link_filter' ) );
		remove_filter( 'wp_page_menu', array( __CLASS__, 'fix_legacy_nav' ) );

		return self::$wp_nav_menu_cache[ $nav_location ];

	}


	public static function does_menu_have_subs( $block ) {

		$nav_location = 'navigation_block_' . UpFrontBlocksData::get_legacy_id( $block );

		/*
		 * Running wp_nav_menu() is a little taxing when not needed.
		 * Sometimes self::does_menu_have_subs() is called multiple times on the same location and this is wasting resources.
		 * This is what the cache is here to resolve.
		 */
		if ( upfront_get( $nav_location, self::$menu_sub_check_cache ) !== null ) {
			return upfront_get( $nav_location, self::$menu_sub_check_cache );
		}

		$menu = self::get_wp_nav_menu( $block );

		$result = false;

		if ( preg_match( '/class=[\'"]sub-menu[\'"]/', $menu ) || preg_match( '/class=[\'"]children[\'"]/', $menu ) ) {
			$result = true;
		}

		self::$menu_sub_check_cache[ $nav_location ] = $result;

		return self::$menu_sub_check_cache[ $nav_location ];

	}


	function setup_elements() {

		$this->register_block_element( array(
			'name' => __('Menücontainer', 'upfront'),
			'selector' => '.nav-horizontal'
		) );

		$this->register_block_element( array(
			'name' => __('Menücontainer - Vertikal', 'upfront'),
			'selector' => '.nav-vertical'
		) );

		$this->register_block_element( array(
			'name' => __('Navigation', 'upfront'),
			'selector' => 'ul.menu'
		) );

		$this->register_block_element( array(
			'name' => __('Menüpunkt', 'upfront'),
			'selector' => 'ul.menu li'
		) );

		$this->register_block_element(array(
			'name' => __('Menüpunkt verkleinert', 'upfront'),
			'selector' => 'ul.menu li',
			'states' => array(
				'Geschrumpft' => 'ul.menu li.is_shrinked',
			)
		));

		$this->register_block_element( array(
			'name' => __('Menüelement Link', 'upfront'),
			'selector' => 'ul.menu li a'
		) );

		$this->register_block_element( array(
			'name' => __('Menüpunkt - Aktiv', 'upfront'),
			'selector' => 'ul.menu li.current-menu-item a'
		) );

		$this->register_block_element( array(
			'name' => __('Untermenü', 'upfront'),
			'selector' => 'ul.sub-menu'
		) );

		$this->register_block_element( array(
			'name' => __('Untermenüpunkt', 'upfront'),
			'selector' => 'ul.sub-menu li'
		) );

		$this->register_block_element( array(
			'name' => __('Untermenüelement Link', 'upfront'),
			'selector' => 'ul.sub-menu li a'
		) );

		$this->register_block_element( array(
			'name' => __('Sucheingabe', 'upfront'),
			'selector' => '#searchform input[type="text"]'
		) );

		$this->register_block_element( array(
			'name' => __('Horizontales SlideOut-Tag', 'upfront'),
			'selector' => '.nav-horizontal .pushy-menu-toggle',
			'states' => array(
				'Hover' => '.nav-horizontal .pushy-menu-toggle:hover', 
				'Clicked' => '.nav-horizontal .pushy-menu-toggle:active'
			)
		) );

		$this->register_block_element( array(
			'name' => __('Horizontales SlideOut-Symbol', 'upfront'),
			'selector' => '.nav-horizontal .pushy-menu-toggle .pushy-menu-toggle-icon',
			'states' => array(
					'Hover' => '.nav-horizontal .pushy-menu-toggle .pushy-menu-toggle-icon:hover', 
					'Clicked' => '.nav-horizontal .pushy-menu-toggle .pushy-menu-toggle-icon:active'
				)
		) );

		$this->register_block_element( array(
			'name' => __('Horizontale SlideOut-Symbollinie', 'upfront'),
			'selector' => '.nav-horizontal .pushy-menu-toggle .pushy-menu-toggle-icon .pushy-menu-toggle-icon-bar',
			'states' => array(
				'Hover' => '.nav-horizontal .pushy-menu-toggle .pushy-menu-toggle-icon .pushy-menu-toggle-icon-bar:hover', 
				'Clicked' => '.nav-horizontal .pushy-menu-toggle .pushy-menu-toggle-icon .pushy-menu-toggle-icon-bar:active'
			)
		) );

		$this->register_pre_4_elements();

		/**
		 *
		 * Pushy menu / Slide out
		 *
		 */
		$this->register_block_element( array(
			'name' => __('Herausziehen: Überlagerung', 'upfront'),			
			'selector' => '\html > body > div.pushy-site-overlay',			
		) );
		$this->register_block_element( array(
			'id' => 'slide-out-container',
			'name' => __('Slide-out: Menu Container', 'upfront'),			
			'selector' => '\ul.pushy',
		) );
		
		$this->register_block_element( array(
			'name' => __('Slide-out: Menu Item', 'upfront'),			
			'selector' => '\ul.pushy li',
		) );
		
		$this->register_block_element( array(
			'name' => __('Slide-out: Menu Item Link', 'upfront'),			
			'selector' => '\ul.pushy li a',
			'states' => array(
				'Hover' => '\ul.pushy li a:hover', 				
			),
		) );
		
		

	}


	function register_pre_4_elements() {

		$this->register_block_element( array(
				'legacy-only' => true,
				'id' => 'menu-item',
				'name' => __('Menüpunkt', 'upfront'),
				'selector' => 'ul.menu li > a',
				'states' => array(
						'Selected' => '
					ul.menu li.current_page_item > a,
					ul.menu li.current_page_parent > a,
					ul.menu li.current_page_ancestor > a,
					ul.menu li.current_page_item > a:hover,
					ul.menu li.current_page_parent > a:hover,
					ul.menu li.current_page_ancestor > a:hover,
					ul.menu li.current-menu-item > a,
					ul.menu li.current-menu-parent > a,
					ul.menu li.current-menu-ancestor > a,
					ul.menu li.current-menu-item > a:hover,
					ul.menu li.current-menu-parent > a:hover,
					ul.menu li.current-menu-ancestor > a:hover
				',
						'Hover' => 'ul.menu li > a:hover',
						'Clicked' => 'ul.menu li > a:active',
						'Dropdown Open' => 'ul.menu li.sfHover > a',
						'Shrinked' => 'ul.menu li.is_shrinked',
			
				)
		) );


		$this->register_block_element( array(
				'legacy-only' => true,
				'id' => 'sub-nav-menu',
				'name' => __('Untermenü', 'upfront'),
				'selector' => 'ul.sub-menu'
		) );


		$this->register_block_element( array(
				'legacy-only' => true,
				'id' => 'sub-menu-item',
				'name' => __('Untermenüpunkt', 'upfront'),
				'selector' => 'ul.sub-menu li > a',
				'states' => array(
						'Selected' => '
					ul.sub-menu li.current_page_item > a,
					ul.sub-menu li.current_page_parent > a,
					ul.sub-menu li.current_page_ancestor > a,
					ul.sub-menu li.current_page_item > a:hover,
					ul.sub-menu li.current_page_parent > a:hover,
					ul.sub-menu li.current_page_ancestor > a:hover
				',
						'Hover' => 'ul.sub-menu li > a:hover',
						'Clicked' => 'ul.sub-menu li > a:active',
						'Dropdown Open' => 'ul.sub-menu li.sfHover > a',
						'Shrinked' => 'ul.menu li.is_shrinked',
				)
		) );

		$this->register_block_element( array(
				'legacy-only' => true,
				'id' => 'search-input',
				'name' => __('Sucheingabe', 'upfront'),
				'selector' => '#searchform input[type="text"]',
				'states' => array(
						'Focused' => '#searchform input[type="text"]:focus'
				)
		) );

	}


	public static function home_link_filter( $menu ) {

		$block = self::$block;

		if ( parent::get_setting( $block, 'hide-home-link' ) ) {
			return $menu;
		}

		if ( get_option( 'show_on_front' ) == 'posts' ) {

			$current = ( is_home() || is_front_page() ) ? ' current-menu-item current_page_item' : null;
			$home_text = ( parent::get_setting( $block, 'home-link-text' ) ) ? parent::get_setting( $block, 'home-link-text' ) : __('Startseite', 'upfront');

			/* If it's not the grid, then do not add the extra <span>'s */
			if ( ! upfront_get( 've-live-content-query', $block ) ) {
				$home_link = '<li class="menu-item-home upfront-home-link' . $current . '"><a href="' . home_url() . '">' . $home_text . '</a></li>';
			} /* If it IS the grid, add extra <span>'s so it can be automatically vertically aligned */
			else {
				$home_link = '<li class="menu-item-home upfront-home-link' . $current . '"><a href="' . home_url() . '"><span>' . $home_text . '</span></a></li>';
			}

		} else {

			$home_link = null;

		}

		return $home_link . $menu;

	}


	public static function fix_legacy_nav( $menu ) {

		$menu = preg_replace( '/<ul class=[\'"]children[\'"]/', '<ul class="sub-menu"', trim( $menu ) ); //Change sub menu class
		$menu = preg_replace( '/<div class=[\'"]menu[\'"]>/', '', $menu, 1 ); //Remove opening <div>
		$menu = str_replace( '<ul>', '<ul class="menu">', $menu ); //Add menu class to main <ul>
		$menu = str_replace( 'current_page_item', 'current_page_item current-menu-item', $menu ); //Add current-menu-item wherever current_page_item is to make legacy nav more consistent with wp_nav_menu()

		return substr( trim( $menu ), 0, - 6 ); //Remove the closing </div>

	}


}


class UpFrontNavigationBlockOptions extends UpFrontBlockOptionsAPI {

	public $tabs;
	public $inputs;


	function __construct($block_type_object){

		parent::__construct($block_type_object);

		$this->tabs = array(
			'nav-menu-content' => __('Inhalt', 'upfront'),
			'setup' => __('Einrichten', 'upfront'),
			'home-link' => __('Startseite-Link', 'upfront'),
			'search' => __('Suche', 'upfront'),
			'orientation' => __('Orientierung', 'upfront'),
			'dropdowns' => __('Dropdowns', 'upfront'),
		);

		$this->inputs = array(
			'setup' => array(
				'item-height' => array(
					'type' => 'slider',
					'name' => 'item-height',
					'label' => __('Höhe des Navigationselements', 'upfront'),
					'default' => 40,
					'slider-min' => 0,
					'slider-max' => 250,
					'slider-interval' => 1,
					'unit' => 'px'
				),

				'responsiveness-notice' => array(
					'name' => 'responsiveness-notice',
					'type' => 'notice',
					'notice' => __('Du musst Responsivgitter aktiviert haben, um diese Optionen nutzen zu können. Responsivgitter kann unter Setup & raquo; Responsivgitter im Gitter-Modus.', 'upfront')
				),

				'responsive-method' => array(
					'type' => 'select',
					'name' => 'responsive-method',
					'label' => __('Responsivmethode', 'upfront'),
					'default' => 'vertical',
					'options' => array(
						'vertical' => __('Vertikale Navigation', 'upfront'),
						'slide-out' => __('Horizontaler Auszug', 'upfront'),
						'select' => __('Basis Auswahleingabe', 'upfront')
					),
					'toggle' => array(
						'vertical' => array(
							'show' => array(
								'#input-responsive-menu-label',
								'#input-responsive-menu-label-position',
								'#input-use-responsive-menu-breakpoint'
							),
							'hide' => array(
								'#input-slide-out-menu-position',
								'#input-responsive-select',
								'#input-slide-out-overlay-color',
							)
						),
						'slide-out' => array(
							'show' => array(
								'#input-responsive-menu-label',
								'#input-responsive-menu-label-position',
								'#input-use-responsive-menu-breakpoint',
								'#input-slide-out-menu-position',
								'#input-slide-out-overlay-color',
							),
							'hide' => array(
								'#input-responsive-select',
							)
						),
						'select' => array(
							'show' => array(
								'#input-responsive-select',
							),
							'hide' => array(
								'#input-responsive-menu-label',
								'#input-responsive-menu-label-position',
								'#input-use-responsive-menu-breakpoint',
								'#input-responsive-menu-breakpoint',
								'#input-slide-out-menu-position',
								'#input-slide-out-overlay-color',
							)
						)
					)
				),

				'responsive-menu-label' => array(
					'type' => 'text',
					'name' => 'responsive-menu-label',
					'label' => 'Responsivmenü Bezeichnung',
					'default' => __('Navigation', 'upfront')
				),

				'responsive-menu-label-position' => array(
					'type' => 'select',
					'name' => 'responsive-menu-label-position',
					'label' => __('Responsivmenü Beschriftungsposition', 'upfront'),
					'options' => array(
						'left' => __('Links', 'upfront'),
						'right' => __('Rechts', 'upfront'),
						'center' => __('Zentriert', 'upfront')
					),
					'default' => 'right'
				),

				'slide-out-menu-position' => array(
					'type' => 'select',
					'name' => 'slide-out-menu-position',
					'label' => __('Herausschieben Position', 'upfront'),
					'options' => array(
						'left' => __('Links', 'upfront'),
						'right' => __('Rechts', 'upfront')
					),
					'default' => 'left'
				),

				'use-responsive-menu-breakpoint' => array(
					'type' => 'checkbox',
					'name' => 'use-responsive-menu-breakpoint',
					'label' => __('Verwende Responsivmenü Haltepunkt', 'upfront'),
					'tooltip' => __('Wenn diese Option nicht aktiviert ist, wird für alle Geräte die Folie oder vertikale Navigation angezeigt.', 'upfront'),
					'default' => true,
					'toggle' => array(
						'true' => array(
							'show' => '#input-responsive-menu-breakpoint'
						),
						'false' => array(
							'hide' => '#input-responsive-menu-breakpoint'
						)
					)
				),

				'responsive-menu-breakpoint' => array(
					'type' => 'slider',
					'name' => 'responsive-menu-breakpoint',
					'label' => __('Navigation Haltepunkt', 'upfront'),
					'tooltip' => __('Dies ist die Gerätebreite, bei der der Navigationsblock seine eigene Navigation ausblenden und die Folie oder vertikale Navigation anzeigen soll.', 'upfront'),
					'unit' => 'px',
					'default' => 600,
					'slider-min' => 200,
					'slider-max' => 1200
				),

				'responsiveness-notice' => array(
					'name' => 'responsiveness-notice',
					'type' => 'notice',
					'notice' => __('Du musst Responsivgitter aktiviert haben, um diese Optionen nutzen zu können. Responsivgitter kann unter Standardeinstellungen & raquo; Responsivitter im Gitter-Modus.', 'upfront')
				),

				'responsive-select' => array(
					'type' => 'checkbox',
					'name' => 'responsive-select',
					'label' => __('Responsiv Auswahl', 'upfront'),
					'default' => true,
					'tooltip' => __('Wenn diese Option aktiviert ist, wird Deine Navigation zu einem mobilfreundlichen Auswahlmenü, wenn Deine Besucher Deine Webseite auf einem mobilen Gerät anzeigen (Telefone, keine Tablets).', 'upfront')
				),

				'slide-out-overlay-color' => array(
					'type' => 'colorpicker',
					'name' => 'slide-out-overlay-color',
					'label' => __('Überlagerungsfarbe', 'upfront'),
					'default' => '#000000',
					'tooltip' => __('Überlagerungsfarbe bei geöffnetem Menü.', 'upfront')
				)
			),

			'home-link' => array(
				'hide-home-link' => array(
					'type' => 'checkbox',
					'name' => 'hide-home-link',
					'label' => __('Startseitenlink ausblenden', 'upfront'),
					'default' => false,
					'tooltip' => __('Wenn Du keine statische Seite als Startseite hast, fügt UpFront dem Navigationsmenü standardmäßig ein Startelement hinzu.', 'upfront'),
				),
				'home-link-text' => array(
					'name' => 'home-link-text',
					'label' => __('Startseitenlink Text', 'upfront'),
					'type' => 'text',
					'tooltip' => __('Wenn Du möchtest, dass der Link zu Deiner Startseite etwas anderes als <em>Startseite</em> sagt, gib es hier ein!', 'upfront'),
					'default' => __('Home', 'upfront')
				)
			),

			'search' => array(
				'enable-nav-search' => array(
					'type' => 'checkbox',
					'name' => 'enable-nav-search',
					'label' => __('Aktiviere Navigationssuche', 'upfront'),
					'default' => false,
					'tooltip' => __('Wenn Du ein einfaches Suchformular in der Navigationsleiste haben möchtest, aktiviere dieses Kontrollkästchen. <em><strong>Hinweis:</strong> Das Suchformular wird nicht angezeigt, wenn die Option Vertikale Navigation für diesen Block aktiviert ist.</em>', 'upfront')
				),

				'nav-search-position' => array(
					'type' => 'select',
					'name' => 'nav-search-position',
					'label' => __('Suchposition', 'upfront'),
					'default' => 'right',
					'options' => array(
						'left' => __('Links', 'upfront'),
						'right' => __('Rechts', 'upfront')
					),
					'tooltip' => __('Wenn Du möchtest, dass die Navigationssuche nach links anstatt nach rechts ausgerichtet wird, kannst Du diese Option verwenden.', 'upfront')
				),

				'nav-search-placeholder' => array(
					'type' => 'text',
					'name' => 'nav-search-placeholder',
					'label' => __('Suche Platzhalter', 'upfront'),
					'default' => __('Gib zum Suchen ein und drücke die Eingabetaste', 'upfront'),
					'tooltip' => __('Dies ist der Text in der Sucheingabe, der dem Besucher sagt, wie er mit der Sucheingabe interagieren soll.', 'upfront')
				)
			),

			'orientation' => array(
				'alignment' => array(
					'type' => 'select',
					'name' => 'alignment',
					'label' => __('Ausrichtung', 'upfront'),
					'default' => 'left',
					'options' => array(
						'left' => __('Links', 'upfront'),
						'right' => __('Rechts', 'upfront'),
						'center' => __('Zentriert', 'upfront')
					)
				),

				'vert-nav-box' => array(
					'type' => 'checkbox',
					'name' => 'vert-nav-box',
					'label' => __('Vertikale Navigation', 'upfront'),
					'default' => false,
					'tooltip' => __('Anstatt die Navigation horizontal anzuzeigen, kannst Du die Navigation vertikal anzeigen. <em><strong>Hinweis:</strong> Möglicherweise musst Du die Größe des Blocks ändern, damit die Navigationselemente richtig passen.</em>', 'upfront')
				)
			),

			'dropdowns' => array(
				'effect' => array(
					'type' => 'select',
					'name' => 'effect',
					'label' => __('Dropdown-Effekt', 'upfront'),
					'default' => 'fade',
					'options' => array(
						'none' => __('Kein Effekt', 'upfront'),
						'fade' => __('Verblassen', 'upfront'),
						'slide' => __('Gleiten', 'upfront')
					),
					'tooltip' => __('Dies ist der Effekt, der verwendet wird, wenn die Dropdowns angezeigt und ausgeblendet werden.', 'upfront')
				),

				'hover-intent' => array(
					'type' => 'checkbox',
					'name' => 'hover-intent',
					'label' => __('Hover Intent', 'upfront'),
					'default' => true,
					'tooltip' => __('Hover Intent macht es so, dass, wenn ein Navigationselement mit einem Dropdown-Menü bewegt wird, das Dropdown-Menü nur angezeigt wird, wenn der Besucher länger als den Bruchteil einer Sekunde mit der Maus über das Element fährt.<br /><br />Dadurch wird verhindert, dass Dropdowns sporadisch angezeigt werden, wenn der Besucher schnelle Bewegungen über die Navigation ausführt.', 'upfront')
				)
			),
		);
	}


	function modify_arguments( $args = false ) {

		$this->tab_notices['nav-menu-content'] = sprintf( __('Um Elemente zu diesem Navigationsmenü hinzuzufügen, gehe zu <a href="%s" target="_blank"> WordPress Admin &raquo; Aussehen &raquo; Menüs </a>. Erstelle dann ein Menü und weise es <em>%s</em> im Feld <strong>Themenpositionen</strong> zu.', 'upfront'), admin_url( 'nav-menus.php' ), UpFrontBlocksData::get_block_name( $args['blockID'] ));

		if ( $block_height = UpFrontBlocksData::get_block_height( $args['blockID'] ) ) {
			$this->inputs['setup']['item-height']['default'] = $block_height;
		}

		if ( UpFrontResponsiveGrid::is_enabled() ) {
			unset( $this->inputs['setup']['responsiveness-notice'] );
		}

	}

}