<?php

class UpFrontFooterBlock extends UpFrontBlockAPI {


	public $id;
	public $name;
	public $options_class;
	public $html_tag;
	public $attributes;
	public $description;
	public $allow_titles;
	protected $show_content_in_grid;
	public $categories;
	public $inline_editable;


	function __construct(){

		$this->id = 'footer';	
		$this->name = __('Footer', 'upfront');
		$this->options_class = 'UpFrontFooterBlockOptions';	
		$this->html_tag = 'footer';	
		$this->attributes = array(
			'itemscope' => '',
			'itemtype' => 'http://schema.org/WPFooter'
		);
		$this->description = __('Dies befindet sich normalerweise am Ende Deiner Webseite und zeigt das Urheberrecht und verschiedene Links an.', 'upfront');
		$this->allow_titles = false;	
		$this->show_content_in_grid = true;
		$this->categories 	= array('core','content');
		$this->inline_editable = array('custom-copyright');

	}


	function setup_elements() {

		$this->register_block_element(array(
			'id' => 'copyright',
			'name' => __('Copyright', 'upfront'),
			'selector' => 'p.copyright',
			'properties' => array('fonts', 'animation')
		));

		$this->register_block_element(array(
			'id' => 'upfront-attribution',
			'name' => __('UpFront-Zuordnung', 'upfront'),
			'selector' => 'p.footer-upfront-link',
			'properties' => array('fonts', 'animation')
		));

		$this->register_block_element(array(
			'id' => 'administration-panel',
			'name' => __('Administrationsbereich', 'upfront'),
			'selector' => 'a.footer-admin-link',
			'properties' => array('fonts', 'animation')
		));

		$this->register_block_element(array(
			'id' => 'go-to-top',
			'name' => __('Gehe nach oben-Link', 'upfront'),
			'selector' => 'a.footer-go-to-top-link',
			'states' => array(
				'Hover' => 'a.footer-go-to-top-link:hover'
			)
		));

		$this->register_block_element(array(
			'id' => 'responsive-grid-link',
			'name' => __('Responsivgitter Umschaltlink', 'upfront'),
			'selector' => 'a.footer-responsive-grid-link',
			'properties' => array('fonts', 'animation')
		));

	}


	function content($block) {

		//Add action for footer
		do_action('upfront_before_footer');

		echo '<div class="footer-container">';

		echo '<div class="footer">';

		do_action('upfront_footer_open');

		//UpFront Attribution
		if ( parent::get_setting($block, 'hide-upfront-attribution', false) == false )
			self::show_upfront_link();

		//Go To Top Link
		if ( parent::get_setting($block, 'show-go-to-top-link', true) == true ){
			$go_to_top_text = parent::get_setting($block, 'custom-go-to-top-text', 'Zurück nach oben');

			if( ! $go_to_top_text )
				$go_to_top_text = 'Zurück nach oben';

			self::show_go_to_top_link($go_to_top_text);
		}

		//Admin Link
		if ( parent::get_setting($block, 'show-admin-link', true) == true )
			self::show_admin_link();

		//Copyright
		if ( parent::get_setting($block, 'show-copyright', true) == true )
			self::show_copyright(parent::get_setting($block, 'custom-copyright'));

		// Show or hide "Show full site" on mobile
		if ( parent::get_setting($block, 'show-responsive-grid-link', true) == false )
			self::show_responsive_grid_toggle_link();

		do_action('upfront_footer_close');

		echo '</div>';

		echo '</div>';

		do_action('upfront_after_footer');

	}


	/**
	 * Displays an admin link or admin login.
	 * 
	 * @uses UpFrontOption::get()
	 *
	 * @return void
	 **/
	public static function show_admin_link() {

		if ( is_user_logged_in() )
		    echo apply_filters('upfront_admin_link', '<a href="' . admin_url() . '" class="footer-right footer-admin-link footer-link">'.__('Administrationsbereich', 'upfront') . '</a>');
		else
		    echo apply_filters('upfront_admin_link', '<a href="' . admin_url() . '" class="footer-right footer-admin-link footer-link">'.__('Administrationsbereich', 'upfront') . '</a>');

	}


	/**
	 * Echos the Unlimited by UpFront.
	 * 
	 * @uses UpFrontOption::get()
	 *
	 * @param string $text The name of the program to be displayed.  Defaults to UpFront (obviously).
	 * 
	 * @return mixed
	 **/
	public static function show_upfront_link() {

		$upfront_location = 'https://n3rds.work/';
		echo apply_filters('upfront_link', '<p class="footer-left footer-upfront-link footer-link">' . ' <a href="' . $upfront_location . '" title="UpFront von WMS N@W">' . __('UpFront von WMS N@W', 'upfront') . '</a></p>' );

	}


	/**
	 * Shows a simple copyright paragraph.
	 *
	 * @return mixed
	 **/
	public static function show_copyright($custom_copyright = false) {

		$default_copyright = __('Copyright', 'upfront') . ' &copy; ' . date('Y') . ' ' . get_bloginfo('name');

		$custom_copyright = preg_replace( '/%Y%/', date('Y'), $custom_copyright );  //Change %Y% for current year

		$copyright = $custom_copyright ? $custom_copyright : $default_copyright;

		echo apply_filters('upfront_copyright', upfront_parse_php('<p class="copyright footer-copyright custom-copyright">' . $copyright . '</p>'));

	}


	/**
	 * Shows a simple go to top link.
	 *
	 * @return mixed
	 **/
	public static function show_go_to_top_link($text) {

		echo apply_filters('upfront_go_to_top_link', '<a href="#" class="footer-right footer-go-to-top-link footer-link">' . __($text, 'upfront') . '</a>');

	}


	/**
	 * Shows a link to either view the full site or view the mobile site.
	 * 
	 * This will only show if the responsive grid is enabled.
	 **/
	public static function show_responsive_grid_toggle_link() {

		if ( !UpFrontResponsiveGrid::is_enabled() )
			return false;

		$current_url = upfront_get_current_url();	

		if ( UpFrontResponsiveGrid::is_active() ) {

			$url = add_query_arg(array('full-site' => 'true'), $current_url);
			$classes = 'footer-responsive-grid-link footer-responsive-grid-disable footer-link';

			echo apply_filters('upfront_responsive_disable_link', '<p class="footer-responsive-grid-link-container footer-responsive-grid-link-disable-container"><a href="' . $url . '" rel="nofollow" class="' . $classes . '">' . __('Vollständige Seite anzeigen', 'upfront') . '</a></p>');

		} elseif ( UpFrontResponsiveGrid::is_user_disabled() ) {

			$url = add_query_arg(array('full-site' => 'false'), $current_url);
			$classes = 'footer-responsive-grid-link footer-responsive-grid-enable footer-link';

			echo apply_filters('upfront_responsive_enable_link', '<p class="footer-responsive-grid-link-container footer-responsive-grid-link-enable-container"><a href="' . $url . '" rel="nofollow" class="' . $classes . '">' . __('Mobile Seite anzeigen', 'upfront') . '</a></p>');

		}

	}


}

class UpFrontFooterBlockOptions extends UpFrontBlockOptionsAPI {

	public $tabs;
	public $inputs;


	function __construct($block_type_object){

		parent::__construct($block_type_object);

		$this->tabs = array(
			'nav-menu-content' => __('Inhalt', 'upfront')
		);

		$this->inputs = array(
			'nav-menu-content' => array(
				'show-admin-link' => array(
					'type' => 'checkbox',
					'name' => 'show-admin-link',
					'label' => __('Admin Link/Login anzeigen', 'upfront'),
					'default' => true
				),

				'show-go-to-top-link' => array(
					'name' => 'show-go-to-top-link',
					'label' => __('Zeige "Gehe nach oben" Link', 'upfront'),
					'type' => 'checkbox',
					'default' => true,
					'toggle'    => array(
						'true' => array(
							'show' => array(
								'#input-custom-go-to-top-text'
							)
						),
						'false' => array(
							'hide' => array(
								'#input-custom-go-to-top-text'
							)
						)
					)
				),

				'custom-go-to-top-text' => array(
					'name' => 'custom-go-to-top-text',
					'label' => __('Benutzerdefinierter Text "Gehe nach oben"', 'upfront'),
					'type' => 'text',
					'tooltip' => __('Benutzerdefinierter Text "Gehe nach oben"', 'upfront')
				),

				'hide-upfront-attribution' => array(
					'name' => 'hide-upfront-attribution',
					'label' => __('UpFront Theme Zuordnung ausblenden', 'upfront'),
					'type' => 'checkbox',
					'default' => false
				),

				'show-copyright' => array(
					'name' => 'show-copyright',
					'label' => __('Zeige Copyright', 'upfront'),
					'type' => 'checkbox',
					'default' => true
				),

				'custom-copyright' => array(
					'name' => 'custom-copyright',
					'label' => __('Benutzerdefiniertes Copyright', 'upfront'),
					'type' => 'text',
					'tooltip' => __('Wenn Du das Urheberrecht in der Fußzeile ändern möchtest, um etwas anderes zu sagen, gib es hier ein. Verwende %Y% für das aktuelle Jahr.', 'upfront')
				),

				'show-responsive-grid-link' => array(
					'name' => 'show-responsive-grid-link',
					'label' => __('Blende den Link aus, um die gesamte Webseite auf Mobilgeräten anzuzeigen.', 'upfront'),
					'type' => 'checkbox',
					'tooltip' => __('Zeigt einen Link zum Anzeigen der vollständigen Seite oder der mobilen Seite an.', 'upfront'),
					'default' => false
				)
			)
		);
	}

}