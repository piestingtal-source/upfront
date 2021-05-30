<?php

class UpFrontAdmin {

	public static function init() {

		self::setup_hooks();

		UpFront::load(array(
			'abstract/api-admin-meta-box',
			'admin/admin-write' => true,
			'admin/admin-pages',
			'admin/api-admin-inputs'
		));


	}


	public static function setup_hooks() {

		/* Actions */
		add_action('admin_init', array(__CLASS__, 'activation'), 1);
		add_action('admin_init', array(__CLASS__, 'enqueue'));
		add_action('admin_init', array(__CLASS__, 'visual_editor_redirect'), 12);

		add_action('init', array(__CLASS__, 'form_action_save'), 12); // Init runs before admin_menu; admin_menu runs before admin_init
		add_action('init', array(__CLASS__, 'form_action_reset'), 12);
		add_action('init', array(__CLASS__, 'form_action_delete_snapshots'), 12);
		add_action('init', array(__CLASS__, 'form_action_replace_url'), 12);

		add_action('admin_menu', array(__CLASS__, 'add_menus'));

		add_action('upfront_admin_save_message', array(__CLASS__, 'save_message'));
		add_action('upfront_admin_save_error_message', array(__CLASS__, 'save_error_message'));

		add_action('admin_notices', array(__CLASS__, 'notice_no_widgets_or_menus'));
		add_action('admin_notices', array(__CLASS__, 'theme_install_template_notice'));
        add_action('admin_notices', array(__CLASS__, 'responsive_grid_notice'));

        add_action('wp_ajax_upfront_dismiss_admin_notice', array(__CLASS__, 'ajax_dismiss_admin_notice'));
        add_action('wp_ajax_upfront_enable_responsive_grid', array(__CLASS__, 'ajax_enable_responsive_grid'));

		add_filter('page_row_actions', array(__CLASS__, 'row_action_visual_editor'), 10, 2);
		add_filter('post_row_actions', array(__CLASS__, 'row_action_visual_editor'), 10, 2);
		add_filter('tag_row_actions', array(__CLASS__, 'row_action_visual_editor'), 10, 2);

		add_filter('mce_buttons_2', array(__CLASS__, 'tiny_mce_buttons'));
		add_filter('tiny_mce_before_init', array(__CLASS__, 'tiny_mce_formats'));

	}


	public static function form_action_save() {

		//Form action for all UpFront configuration panels.  Not in function/hook so it can load before everything else.
		if ( !upfront_post('upfront-submit', false))
			return false;

		if ( !wp_verify_nonce(upfront_post('upfront-admin-nonce', false), 'upfront-admin-nonce') ) {

			global $upfront_admin_save_message;
			$upfront_admin_save_message = 'Sicherheits-Nonce stimmte nicht überein.';

			return false;

		}

		foreach ( upfront_post('upfront-admin-input', array()) as $option => $value ) {

			UpFrontOption::set($option, $value);

			// Automatic Updates
			if($option == 'disable-automatic-core-updates'){				
				update_option('upfront-disable-automatic-core-updates',$value);
			}
			if($option == 'disable-automatic-plugin-updates'){				
				update_option('upfront-disable-automatic-plugin-updates',$value);
			}

			// Developer version			
			if($option == 'use-developer-version'){				
				update_option('upfront-use-developer-version',$value);
			}

		}

		global $upfront_admin_save_message;
		$upfront_admin_save_message = 'Settings saved.';

		return true;

	}

	public static function form_action_delete_snapshots() {

		global $wpdb;

		if ( ! upfront_post( 'upfront-delete-snapshots', false ) ) {
			return false;
		}

		if ( ! wp_verify_nonce( upfront_post( 'upfront-delete-snapshots-nonce', false ), 'upfront-delete-snapshots-nonce' ) ) {

			$GLOBALS['upfront_admin_save_message'] = 'Sicherheits-Nonce stimmte nicht überein.';

			return false;

		}

		/* Loop through WordPress options and delete the skin options */
		$wpdb->query( "TRUNCATE TABLE $wpdb->pu_snapshots" );

		do_action( 'upfront_delete_all_snapshots' );

		$GLOBALS['upfront_admin_save_message'] = 'Snapshots erfolgreich gelöscht.';

		return true;

	}

	public static function form_action_replace_url() {

		global $wpdb;

		if ( ! upfront_post( 'upfront-replace-url', false ) ) {
			return false;
		}
		
		if ( ! wp_verify_nonce( upfront_post( 'upfront-replace-url-nonce', false ), 'upfront-replace-url-nonce' ) ) {

			$GLOBALS['upfront_admin_save_message'] = 'Sicherheits-Nonce stimmte nicht überein.';
			return false;

		}

	
		$from = ! empty( upfront_post('from')) ? upfront_post('from') : '';
		$to = ! empty( upfront_post('to')) ? upfront_post('to') : '';

		try {
			if( upfront_replace_urls( $from, $to ) ){
				$GLOBALS['upfront_admin_save_message'] = 'URL erfolgreich ersetzt.';
				return true;		
			}else{
				return false;
			}

		} catch ( \Exception $e ) {
			wp_send_json_error( $e->getMessage() );
			return false;
		}

	}


	public static function form_action_reset() {

		global $wpdb;

		if ( !defined('UPFRONT_ALLOW_RESET') || UPFRONT_ALLOW_RESET !== true )
			return false;

		//Form action for all UpFront configuration panels.  Not in function/hook so it can load before everything else.
		if ( !upfront_post('reset-upfront', false) )
			return false;

		//Verify the nonce so other sites can't maliciously reset a UpFront installation.
		if ( !wp_verify_nonce(upfront_post('upfront-reset-nonce', false), 'upfront-reset-nonce') ) {

			$GLOBALS['upfront_admin_save_message'] = 'Sicherheits-Nonce stimmte nicht überein.';

			return false;

		}

		/* Loop through WordPress options and delete the skin options */
		$wpdb->query( "DELETE FROM $wpdb->options WHERE option_name = 'upfront'" );
		$wpdb->query( "DELETE FROM $wpdb->options WHERE option_name LIKE 'upfront_%'" );

		$wpdb->query( "DELETE FROM $wpdb->options WHERE option_name LIKE '_transient_pu_%'" );

		/* Remove UpFront post meta */
		$wpdb->query( "DELETE FROM $wpdb->postmeta WHERE meta_key LIKE '_pu_%'" );

		/* Drop UpFront tables */
		UpFront::db_drop_tables();

		/* Flush WP cache */
		wp_cache_flush();

		do_action('upfront_global_reset');

		$GLOBALS['upfront_admin_save_message'] = 'UpFront wurde erfolgreich zurückgesetzt.';

		//This will hide the reset box if set to true.
		$GLOBALS['upfront_reset_success'] = true;

		return true;

	}


	public static function activation() {

		if ( !is_admin() || !upfront_get('activated') )
			return false;

		global $pagenow;

		if ( $pagenow !== 'themes.php' )
			return false;

		//Since they may be upgrading and files may change, let's clear the cache
		do_action('upfront_activation');

		self::activation_redirect();

	}


	public static function activation_redirect() {

		do_action('upfront_activation_redirect');

		//If a child theme has been activated rather than UpFront, then don't redirect.
		//Let the child theme developer redirect if they want by using the hook above.
		if ( UPFRONT_CHILD_THEME_ACTIVE === true )
			return false;

		$parent_menu = self::parent_menu();

		//If header were sent, then don't do the redirect
		if ( headers_sent() )
			return false;

		//We're all good, redirect now
		wp_safe_redirect(admin_url('admin.php?page=upfront-' . $parent_menu['id']));
		die();

	}


	public static function visual_editor_redirect() {

		if ( isset($_GET['page']) && strpos($_GET['page'], 'upfront-visual-editor') !== false && !headers_sent() )
			wp_safe_redirect(home_url() . '/?visual-editor=true');

	}


	public static function add_admin_separator($position){

		global $menu;

		$menu[$position] = array('', 'read', 'separator-upfront', '', 'wp-menu-separator upfront-separator');

		ksort($menu);

	}


	public static function add_admin_submenu($name, $id, $callback) {

		$parent_menu = self::parent_menu();

		return add_submenu_page('upfront-' . $parent_menu['id'], $name, $name, 'manage_options', $id, $callback);

	}


	public static function add_menus(){

		//If the hide menus constant is set to true, don't hide the menus!
		if (defined('UPFRONT_HIDE_MENUS') && UPFRONT_HIDE_MENUS === true)
		 	return false;

		//If user cannot access the admin panels, then don't bother running these functions
		if ( !UpFrontCapabilities::can_user_visually_edit() )
			return false;

		$menu_name = ( UpFrontOption::get('hide-menu-version-number', false, true) == true ) ? UpFrontSettings::get('menu-name') : UpFrontSettings::get('menu-name') . ' ' . UPFRONT_VERSION;

		$icon = (version_compare($GLOBALS['wp_version'], '3.8', '>=') && get_user_option('admin_color') != 'light') ? 'upfront-32-grey.png' : 'upfront-16.png';
		$icon_url = upfront_url() . '/library/admin/images/' . $icon;

		$parent_menu = self::parent_menu();

		self::add_admin_separator(48);

		add_menu_page($parent_menu['name'], $menu_name, 'manage_options', 'upfront-' . $parent_menu['id'], $parent_menu['callback'], $icon_url, 49);

			switch ( $parent_menu['id'] ) {

				case 'getting-started':
					self::add_admin_submenu( __('Erste Schritte', 'upfront'), 'upfront-getting-started', array('UpFrontAdminPages', 'getting_started'));
					self::add_admin_submenu( __('Visueller Editor', 'upfront'), 'upfront-visual-editor', array('UpFrontAdminPages', 'visual_editor'));
					self::add_admin_submenu( __('Vorlagen', 'upfront'), 'upfront-templates', array('UpFrontAdminPages', 'templates'));
					self::add_admin_submenu( __('Optionen', 'upfront'), 'upfront-options', array('UpFrontAdminPages', 'options'));
					self::add_admin_submenu( __('Werkzeuge', 'upfront'), 'upfront-tools', array('UpFrontAdminPages', 'tools'));
				break;

				case 'visual-editor':
					self::add_admin_submenu( __('Visueller Editor', 'upfront'), 'upfront-visual-editor', array('UpFrontAdminPages', 'visual_editor'));
					self::add_admin_submenu( __('Vorlagen', 'upfront'), 'upfront-templates', array('UpFrontAdminPages', 'templates'));
					self::add_admin_submenu( __('Optionen', 'upfront'), 'upfront-options', array('UpFrontAdminPages', 'options'));
					self::add_admin_submenu( __('Werkzeuge', 'upfront'), 'upfront-tools', array('UpFrontAdminPages', 'tools'));
				break;

				case 'options':
					self::add_admin_submenu( __('Optionen', 'upfront'), 'upfront-options', array('UpFrontAdminPages', 'options'));
					self::add_admin_submenu( __('Visueller Editor', 'upfront'), 'upfront-visual-editor', array('UpFrontAdminPages', 'visual_editor'));
					self::add_admin_submenu( __('Vorlagen', 'upfront'), 'upfront-templates', array('UpFrontAdminPages', 'templates'));
					self::add_admin_submenu( __('Werkzeuge', 'upfront'), 'upfront-tools', array('UpFrontAdminPages', 'tools'));
				break;

			}

	}


	public static function parent_menu() {

		$menu_setup = UpFrontOption::get('menu-setup', false, 'getting-started');

		/* Figure out the primary page */
		switch ( $menu_setup ) {

			case 'getting-started':
				$parent_menu = array(
					'id' => 'getting-started',
					'name' => 'Erste Schritte',
					'callback' => array('UpFrontAdminPages', 'getting_started')
				);
			break;

			case 'options':
				$parent_menu = array(
					'id' => 'options',
					'name' => 'Optionen',
					'callback' => array('UpFrontAdminPages', 'options')
				);
			break;

			default:
				$parent_menu = array(
					'id' => 'visual-editor',
					'name' => 'Visueller Editor',
					'callback' => array( 'UpFrontAdminPages', 'visual_editor' )
				);
			break;

		}

		return $parent_menu;

	}


	public static function enqueue() {

		global $pagenow;

		/* Global */
		wp_enqueue_style('upfront_admin_global', upfront_url() . '/library/admin/css/admin-upfront-global.css');
        wp_enqueue_script('upfront_admin_js', upfront_url() . '/library/admin/js/admin-upfront.js', array('jquery'));



		wp_localize_script('upfront_admin_js', 'UpFront', array(
			'ajaxURL' 			=> admin_url('admin-ajax.php'),				
			'security' 			=> wp_create_nonce('upfront-visual-editor-ajax'),				
		));

        /* General UpFront admin CSS/JS */
		if ( strpos(upfront_get('page'), 'upfront') !== false ) {

			wp_enqueue_script('upfront_jquery_scrollto', upfront_url() . '/library/admin/js/jquery.scrollto.js', array('jquery'));
			wp_enqueue_script('upfront_jquery_tabby', upfront_url() . '/library/admin/js/jquery.tabby.js', array('jquery'));
			wp_enqueue_script('upfront_jquery_qtip', upfront_url() . '/library/admin/js/jquery.qtip.js', array('jquery'));
            wp_enqueue_script('upfront_admin_js', upfront_url() . '/library/admin/js/admin-upfront.js', array('jquery', 'upfront_jquery_qtip'));

            wp_enqueue_style('upfront_admin', upfront_url() . '/library/admin/css/admin-upfront.css');
			wp_enqueue_style('upfront_alerts', upfront_url() . '/library/media/css/alerts.css');

		}

		/* Templates */
		if ( upfront_get('page') == 'upfront-templates' ) {

			wp_enqueue_script('upfront_knockout', upfront_url() . '/library/admin/js/knockout.js', array('jquery'));
			wp_enqueue_script('upfront_admin_templates', upfront_url() . '/library/admin/js/admin-templates.js', array('jquery'));

			wp_localize_script('upfront_admin_templates', 'UpFront', array(

				'ajaxURL' 			=> admin_url('admin-ajax.php'),
				//'apiURL' 			=> UPFRONT_API_URL,
				'security' 			=> wp_create_nonce('upfront-visual-editor-ajax'),
				'templates' 		=> UpFrontTemplates::get_all(),
				'templateActive' 	=> UpFrontTemplates::get_active(),
				'viewModels' 		=> array()
			));

			add_thickbox();
			wp_enqueue_media();

		}


		/* Meta Boxes */
		wp_enqueue_style('upfront_admin_write', upfront_url() . '/library/admin/css/admin-write.css');
		wp_enqueue_style('upfront_alerts', upfront_url() . '/library/media/css/alerts.css');
		wp_enqueue_script('upfront_admin_write', upfront_url() . '/library/admin/js/admin-write.js', array('jquery'));
                $css_src = includes_url('css/') . 'editor.css';
                wp_register_style('tinymce_css', $css_src);
                wp_enqueue_style('tinymce_css');


		/* Auto Updater */
		if ( $pagenow === 'update-core.php' ) {

			wp_enqueue_style('upfront_admin', upfront_url() . '/library/admin/css/admin-upfront.css');
			wp_enqueue_style('upfront_alerts', upfront_url() . '/library/media/css/alerts.css');

		}

	}


	public static function save_message() {

		global $upfront_admin_save_message;

		if ( !isset($upfront_admin_save_message) || $upfront_admin_save_message == false )
			return false;

		echo '<div id="setting-error-settings_updated" class="updated settings-error"><p>' . $upfront_admin_save_message . '</p></div>';

	}


	public static function save_error_message() {

		global $upfront_admin_save_error_message;

		if ( !isset($upfront_admin_save_error_message) || $upfront_admin_save_error_message == false )
			return false;

		echo '<div id="setting-error-settings_error" class="error settings-error"><p>' . $upfront_admin_save_error_message . '</p></div>';

	}


	public static function notice_no_widgets_or_menus() {

		global $pagenow;

		if ( $pagenow != 'widgets.php' && $pagenow != 'nav-menus.php' )
			return false;

		$grid_mode_url = add_query_arg(array('visual-editor' => 'true', 'visual-editor-mode' => 'grid'), home_url());

		//Show the widgets message if no widget blocks exist.
		if ( $pagenow == 'widgets.php' ) {

			$widget_area_blocks = UpFrontBlocksData::get_blocks_by_type('widget-area');

			if ( !empty($widget_area_blocks) )
				return;

			if ( !current_theme_supports('upfront-grid') )
				return;

			echo '<div class="updated" style="margin-top: 15px;">
			       <p>UpFront hat festgestellt, dass Du keine Widget-Bereichsblöcke hast. Wenn Du das WordPress-Widgets-System mit UpFront verwenden möchtest, füge bitte einen Widget-Bereich-Block hinzu <a href="' . $grid_mode_url . '" target="_blank">Visueller Editor: Gitter</a>.</p>

					<style type="text/css">
						div.error.below-h2 { display: none; }
						div.error.below-h2 + p { display: none; }
					</style>
			    </div>';

		}

		//Show the navigation menus message if no navigation blocks exist.
		if ( $pagenow == 'nav-menus.php' ) {

			$navigation_blocks = UpFrontBlocksData::get_blocks_by_type('navigation');

			if ( !empty($navigation_blocks) )
				return;

			if ( !current_theme_supports('upfront-grid') )
				return;

			echo '<div class="updated">
			       <p>' . sprintf( __('UpFront hat festgestellt, dass Du keine Navigationsblöcke hast. Wenn Du das WordPress-Menüsystem mit UpFront verwenden möchtest, füge bitte einen Navigationsblock in das Feld <a href="%s" target="_blank">Visueller Editor: Gitter</a> hinzu.', 'upfront'), $grid_mode_url ) . '</p>
			    </div>';

		}

	}


	public static function theme_install_template_notice() {

		global $pagenow;

		if ( $pagenow != 'theme-install.php' )
			return false;

		echo '<div class="error">
				<h3>' . __('Versuchst Du, eine UpFront-Vorlage zu installieren?', 'upfront') . '</h3>
			  	 <p>' . sprintf( __('Bitte gehe zu <a href="%s">UpFront &rsaquo; Vorlagen</a> um Vorlagen installieren.', 'upfront'), admin_url('admin.php?page=upfront-templates') ) . '</p>
			</div>';


	}


    public static function responsive_grid_notice() {

        $dismissed_notices = UpFrontOption::get('dismissed-notices', false, array());

        if ( UpFrontSkinOption::get('enable-responsive-grid', false, true) || in_array('responsive-grid', $dismissed_notices) ) {
            return false;
        }

        echo '<div id="upfront-responsive-grid-notice" data-upfront-notice="responsive-grid" class="notice notice-warning is-dismissible" style="padding-top: 0.5em;padding-bottom: 0.5em;">
				<h3 style="margin: 0.5em 0">' . __('Wichtig! Deine Webseite ist derzeit nicht für Handys geeignet.', 'upfront') . '</h3>
                <p>' . __('Google bestraft jetzt Webseiten, die nicht für Handys geeignet sind. Durch Aktivieren des Responsivegitters wird Deine Webseite in den meisten Fällen mobilfreundlich.', 'upfront') . '</p>
                <p>' . __('<strong>Bitte beachte:</strong> Das Aktivieren des Responsivegitters kann bei einigen Webseiten zu Änderungen des Stils und des Layouts führen. Du kannst Responsivegitter im Visuellen Editor jederzeit im Gitter-Modus deaktivieren.', 'upfront') . '</p>
                <p><button class="button-primary">' . __('Responsivegitter aktivieren', 'upfront') . '</button>&emsp;&emsp;<button class="button-secondary upfront-dismiss-notice">' . __('Verwerfen', 'upfront') . '</button></p>
			</div>';

    }


	public static function show_header($title = false) {

		echo '<div class="wrap upfront-page">';

		if ( $title )
			echo '<h2>' . $title . '</h2>';

	}


	public static function show_footer() {

		echo '</div><!-- #wrapper -->';

	}


	public static function row_action_visual_editor($actions, $item) {

		if ( !UpFrontCapabilities::can_user_visually_edit() )
			return $actions;

		/* Post */
		if ( isset($item->post_status) ) {

			if ( $item->post_status != 'publish' )
				return $actions;

			$post_type = get_post_type_object($item->post_type);

			if ( !$post_type->public )
				return $actions;

			$layout_id = 'single' . UpFrontLayout::$sep . $item->post_type . UpFrontLayout::$sep . $item->ID;

			if ( get_option('show_on_front') === 'page' ) {

				if ( $item->ID == get_option('page_on_front') )
					$layout_id = 'front_page';

				if ( $item->ID == get_option('page_for_posts') )
					$layout_id = 'index';

			}

		/* Category */
		} elseif ( isset($item->term_id) && $item->taxonomy == 'category' ) {

			$layout_id = 'archive' . UpFrontLayout::$sep . 'category' . UpFrontLayout::$sep . $item->term_id;

		/* Post Tag */
		} elseif ( isset($item->term_id) && $item->taxonomy == 'post_tag' ) {

			$layout_id = 'archive' . UpFrontLayout::$sep . 'post_tag' . UpFrontLayout::$sep . $item->term_id;

		/* Taxonomy */
		} elseif ( isset($item->term_id) ) {

			$layout_id = 'archive' . UpFrontLayout::$sep . 'taxonomy' . UpFrontLayout::$sep . $item->taxonomy . UpFrontLayout::$sep . $item->term_id;

		}

		$visual_editor_url = home_url('/?visual-editor=true&ve-layout=' . urlencode($layout_id));

		$actions['pu-visual-editor'] = '<a href="' . $visual_editor_url . '" title="' . __('Öffnen im UpFront Visuellen Editor', 'upfront') . '" rel="permalink" target="_blank">' . __('Öffnen im UpFront Visuellen Editor', 'upfront') . '</a>';

		return $actions;

	}


	public static function tiny_mce_buttons($buttons) {

		array_unshift( $buttons, 'styleselect' );
		return $buttons;

	}


	public static function tiny_mce_formats($init_array) {

		$style_formats = array(
			array(
				'title' => 'Alerts',
				'items' => array(
					array(
						'title' => 'Red',
						'block' => 'div',
						'classes' => 'alert alert-red',
						'wrapper' => true
					),

					array(
						'title' => 'Yellow',
						'block' => 'div',
						'classes' => 'alert alert-yellow',
						'wrapper' => true
					),

					array(
						'title' => 'Green',
						'block' => 'div',
						'classes' => 'alert alert-green',
						'wrapper' => true
					),

					array(
						'title' => 'Blue',
						'block' => 'div',
						'classes' => 'alert alert-blue',
						'wrapper' => true
					),

					array(
						'title' => 'Gray',
						'block' => 'div',
						'classes' => 'alert alert-gray',
						'wrapper' => true
					)
				)
			)
		);

		if ( !empty( $init_array['style_formats'] ) ) {

			// json decode wp array
			$jd_orig_array = json_decode( $init_array['style_formats'], true );

			// merge new array with wp array (json encoded)
			$new_merge = json_encode( array_merge( $jd_orig_array, $style_formats ) );

			// populate back into function
			$init_array['style_formats'] = $new_merge;

		} else {

			$init_array['style_formats'] = json_encode($style_formats);

		}

		return $init_array;

	}


    public static function ajax_dismiss_admin_notice() {

        $notice_to_dismiss 		= upfront_post('notice-to-dismiss');
        $dismissed_notices 		= UpFrontOption::get('dismissed-notices', false, array());
        $dismissed_notices[] 	= $notice_to_dismiss;

        return UpFrontOption::set('dismissed-notices', array_unique($dismissed_notices));

    }


    public static function ajax_enable_responsive_grid() {

        return UpFrontSkinOption::set('enable-responsive-grid', true);

    }


}
