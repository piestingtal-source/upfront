<?php

class UpFrontAdminBar {


	public static function init() {

		add_action('admin_bar_menu', array(__CLASS__, 'add_admin_bar_nodes'), 75);

	}


	public static function remove_admin_bar() {

		show_admin_bar(false);
		remove_action('wp_head', '_admin_bar_bump_cb');

	}


	public static function add_admin_bar_nodes() {

		if ( !UpFrontCapabilities::can_user_visually_edit() )
			return;

		global $wp_admin_bar;

		$default_visual_editor_mode = current_theme_supports('upfront-grid') ? 'grid' : 'design';


		//UpFront Root
		$wp_admin_bar->add_menu(array(
			'id' 		=> 'upfront', 
			'title' 	=> UpFrontSettings::get('menu-name'), 
			'href' 		=> 	add_query_arg(array(
											'visual-editor' 		=> 'true',
											'visual-editor-mode' 	=> $default_visual_editor_mode,
											've-layout' 			=> urlencode(UpFrontLayout::get_current())), 
										home_url())
		));


		//Visual Editor
		$wp_admin_bar->add_menu(array(
			'parent' 	=> 'upfront',
			'id' 		=> 'upfront-ve', 
			'title' 	=> __('Visueller Editor', 'upfront'),  
			'href' 		=>  add_query_arg(array(
											'visual-editor' 		=> 'true',
											'visual-editor-mode' 	=> $default_visual_editor_mode,
											've-layout' 			=> urlencode( UpFrontLayout::get_current() )),
											home_url())
		));


		//Gitter
		if ( current_theme_supports('upfront-grid') ) {

			$wp_admin_bar->add_menu(array(
				'parent' 	=> 'upfront-ve',
				'id' 		=> 'upfront-ve-grid', 
				'title' 	=> __('Gitter', 'upfront'),  
				'href' 		=>  add_query_arg(array(
												'visual-editor' 		=> 'true',
												'visual-editor-mode' 	=> 'grid',
												've-layout' 			=> urlencode( UpFrontLayout::get_current() )),
												 home_url())
			));

		}


		//Design Editor
		$wp_admin_bar->add_menu(array(
			'parent' 	=> 'upfront-ve',
			'id' 		=> 'upfront-ve-design', 
			'title' 	=> __('Design', 'upfront'), 
			'href' 		=> add_query_arg(array(
											'visual-editor' 		=> 'true',
											'visual-editor-mode' 	=>
											'design', 've-layout' 	=> urlencode( UpFrontLayout::get_current() )),
											 home_url())
		));

		//Templates
		$wp_admin_bar->add_menu(array(
			'parent' 	=> 'upfront',
			'id' 		=> 'upfront-admin-templates',
			'title' 	=> __('Vorlagen', 'upfront'),
			'href' 		=> admin_url('admin.php?page=upfront-templates')
		));

		//Admin Options
		$wp_admin_bar->add_menu(array(
			'parent' 	=> 'upfront',
			'id' 		=> 'upfront-admin-options', 
			'title' 	=> __('Optionen', 'upfront'),  
			'href' 		=> admin_url('admin.php?page=upfront-options')
		));

					$wp_admin_bar->add_menu(array(
						'parent' => 'upfront-admin-options',
						'id' => 'upfront-admin-options-general', 
						'title' => __('Allgemeines', 'upfront'),  
						'href' => admin_url('admin.php?page=upfront-options#tab-general')
					));

					$wp_admin_bar->add_menu(array(
						'parent' => 'upfront-admin-options',
						'id' => 'upfront-admin-options-seo', 
						'title' => __('Suchmaschinenoptimierung', 'upfront'),  
						'href' => admin_url('admin.php?page=upfront-options#tab-seo')
					));

					$wp_admin_bar->add_menu(array(
						'parent' => 'upfront-admin-options',
						'id' => 'upfront-admin-options-scripts',
						'title' => __('Skripte/Analytics', 'upfront'),  
						'href' => admin_url('admin.php?page=upfront-options#tab-scripts')
					));

					$wp_admin_bar->add_menu(array(
						'parent' => 'upfront-admin-options',
						'id' => 'upfront-admin-options-visual-editor',
						'title' => __('Visueller Editor', 'upfront'),  
						'href' => admin_url('admin.php?page=upfront-options#tab-visual-editor')
					));

					$wp_admin_bar->add_menu(array(
						'parent' => 'upfront-admin-options',
						'id' => 'upfront-admin-options-advanced',
						'title' => __('Fortgeschritten', 'upfront'),  
						'href' => admin_url('admin.php?page=upfront-options#tab-advanced')
					));

			//Admin Tools
				$wp_admin_bar->add_menu(array(
					'parent' => 'upfront',
					'id' => 'upfront-admin-tools', 
					'title' => __('Werkzeuge', 'upfront'),  
					'href' => admin_url('admin.php?page=upfront-tools')
				));

					$wp_admin_bar->add_menu(array(
						'parent' => 'upfront-admin-tools',
						'id' => 'upfront-admin-tools-system-info', 
						'title' => __('Systeminformationen', 'upfront'),  
						'href' => admin_url('admin.php?page=upfront-tools#tab-system-info')
					));

					if(is_admin()){
						$wp_admin_bar->add_menu(array(
							'parent' => 'upfront-admin-tools',
							'id' => 'upfront-admin-tools-clear-cache', 
							'title' => __('Cache leeren', 'upfront'),  
							'href' => 'javascript:;'
						));						
					}

					$wp_admin_bar->add_menu(array(
						'parent' => 'upfront-admin-tools',
						'id' => 'upfront-admin-tools-reset', 
						'title' => __('Zurücksetzen', 'upfront'),  
						'href' => admin_url('admin.php?page=upfront-tools#tab-reset')
					));

	}


}