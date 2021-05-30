<?php
class UpFrontDisplay {


	public static $plugin_template_generic_content = null;

	public static function init() {

		if ( is_admin() ) {
			return;
		}

		UpFront::load(array(
			'display/head' => true,
			'display/grid-renderer',
			'display/layout-renderer'
		));

		add_filter('body_class', array(__CLASS__, 'body_class'));

		if ( UpFrontRoute::is_visual_editor_iframe() ) {

			header( 'cache-control: private, max-age=0, no-cache' );

			UpFront::load('visual-editor', 'VisualEditor');
            UpFront::load('visual-editor/dummy-content', 'IframeDummyContent');

			UpFrontAdminBar::remove_admin_bar();

		}

		/* If it's a plugin template, then route all of the content to the content block */
		add_action('get_header', array(__CLASS__, 'handle_plugin_template'), 1);

	}


	public static function layout() {

		get_header();

		self::grid();

		get_footer();

	}


	public static function grid() {

		if ( current_theme_supports('upfront-grid') ) {

			$layout = new UpFrontLayoutRenderer();
			$layout->display();

		} else {

			echo '<div class="alert alert-yellow"><p>' . __('Das UpFront-Gitter wird in diesem Child Theme nicht unterstützt.', 'upfront') . '</p></div>';

		}

	}

	/**
	 * Plugin Template handling system.
	 * 
	 * If the template file isn't UpFront's index.php, then fetch the contents and put them into the Content Block 
	 **/
	public static function handle_plugin_template() {

		if ( ! self::is_plugin_template() ) {
			return false;
		}

		add_action('upfront_whitewrap_open', array(__CLASS__, 'upfront_whitewrap_open_ob_start'));
		add_action('wp_footer', array(__CLASS__, 'upfront_close_ob_get_clean'), -99999);

	}


	public static function is_plugin_template() {

		global $template;

		/* Replace backslashes with forward slashes for Windows compatibility */
		if ( strpos(str_replace('\\', '/', $template), WP_PLUGIN_DIR) !== false || !$template )
			return true;

		return false;

	}


	public static function upfront_whitewrap_open_ob_start() {
		ob_start();
	}


	public static function upfront_close_ob_get_clean() {

		self::$plugin_template_generic_content = ob_get_clean();

		/* Hook generic content */
			add_action('generic_content', array(__CLASS__, 'display_generic_content'));

		/* Display grid in between header and footer */
			self::grid();

	}


	public static function display_generic_content() {
		echo self::$plugin_template_generic_content;
	}


	/**
	 *
	 * This methond allow to load the plugin template into the content block, the template need to be related to a CPT
	 *
	 * @param mixed $template Plugin template.
	 * @return mixed
	 */
	public static function load_plugin_template( $template ) {

		global $post;

		if ( ! $post ) {
			return $template;
		}

		$template_id = get_post_meta( $post->ID, '_wp_page_template', true );

		if ( ! $template_id ) {
			return $template;
		}

		if ( ! UpFrontOption::get( 'allow-plugin-templates', false ) ) {
			return $template;
		}

		if ( ! self::is_plugin_template() ) {
			return $template;
		}

		if ( ! file_exists( $template ) ) {
			return $template;
		}

		add_action( 'upfront_whitewrap_open', array( __CLASS__, 'upfront_whitewrap_open_ob_start' ) );
		add_action( 'wp_footer', array( __CLASS__, 'upfront_close_ob_get_clean' ), -99999 );

		load_template( $template );

		return $template;

	}

	/* End Plugin Template Handling System */



	/**
	 * Assembles the classes for the body element.
	 **/
	public static function body_class($c) {

		global $wp_query, $authordata;

		$c[] = 'custom';

		/* User Agents */
			if ( !UpFrontCompiler::is_plugin_caching() ) {

				$user_agent = (isset($_SERVER['HTTP_USER_AGENT'])) ? $_SERVER['HTTP_USER_AGENT'] : '';

				/* IE */
				if ( $ie_version = upfront_is_ie() ) {

					$c[] = 'ie';
					$c[] = 'ie' . $ie_version;

				}

				/* Modern Browsers */
				if ( stripos($user_agent, 'Safari') !== false )
					$c[] = 'safari';

				elseif ( stripos($user_agent, 'Firefox') !== false )
					$c[] = 'firefox';

				elseif ( stripos($user_agent, 'Chrome') !== false )
					$c[] = 'chrome';

				elseif ( stripos($user_agent, 'Opera') !== false )
					$c[] = 'opera';

				/* Rendering Engines */
				if ( stripos($user_agent, 'WebKit') !== false )
					$c[] = 'webkit';

				elseif ( stripos($user_agent, 'Gecko') !== false )
					$c[] = 'gecko';

				/* Mobile */
				if ( stripos($user_agent, 'iPhone') !== false )
					$c[] = 'iphone';

				elseif ( stripos($user_agent, 'iPod') !== false )
					$c[] = 'ipod';

				elseif ( stripos($user_agent, 'iPad') !== false )
					$c[] = 'ipad';

				elseif ( stripos($user_agent, 'Android') !== false )
					$c[] = 'android';

			}
		/* End User Agents */		

		/* Responsive Grid */
			if ( UpFrontResponsiveGrid::is_enabled() )
				$c[] = 'responsive-grid-enabled';

			if ( UpFrontResponsiveGrid::is_active() )
				$c[] = 'responsive-grid-active';

		/* Pages */			
			if ( is_page() && isset($wp_query->post) && isset($wp_query->post->ID) ) {

				$c[] = 'pageid-' . $wp_query->post->ID;
				$c[] = 'page-slug-' . $wp_query->post->post_name;

			}

		/* Posts & Pages */
			if ( is_singular() && isset($wp_query->post) && isset($wp_query->post->ID)  ) {

				//Add the custom classes from the meta box
				if ( $custom_css_class = UpFrontLayoutOption::get($wp_query->post->ID, 'css-class', null, true) ) {

					$custom_css_classes = str_replace('  ', ' ', str_replace(',', ' ', esc_attr(strip_tags($custom_css_class))));

					$c = array_merge($c, array_filter(explode(' ', $custom_css_classes)));

				}

			}

		/* Layout IDs, etc */
		$c[] = 'layout-' . str_replace(UpFrontLayout::$sep, '-', UpFrontLayout::get_current());
		$c[] = 'layout-using-' . str_replace( UpFrontLayout::$sep, '-', UpFrontLayout::get_current_in_use());

		if ( UpFrontRoute::is_visual_editor_iframe() ) {
			$c[] = 've-iframe';
		}

		if ( upfront_get('ve-iframe-mode') && UpFrontRoute::is_visual_editor_iframe() )
			$c[] = 'visual-editor-mode-' . upfront_get('ve-iframe-mode');

		if ( !current_theme_supports('upfront-design-editor') )
			$c[] = 'design-editor-disabled';

		$c = array_unique(array_filter($c));

		return $c;

	}


	public static function html_open() {

		echo apply_filters('upfront_doctype', '<!DOCTYPE HTML>');
		echo '<html '; language_attributes(); echo '>';

		do_action('upfront_html_open');

		echo '<head>';
		echo '<meta charset="' . get_bloginfo('charset') . '" />';
		echo '<link rel="profile" href="http://gmpg.org/xfn/11" />';

	}


	public static function html_close() {		

		do_action('upfront_html_close');
		echo '</html>';

	}


	public static function body_open() {	

		echo '</head>';
		echo '<body '; body_class(); echo ' itemscope itemtype="http://schema.org/WebPage">';

		do_action('upfront_body_open');

		echo '<div id="whitewrap">';

		do_action('upfront_whitewrap_open');
		do_action('upfront_page_start');

	}


	public static function body_close() {

		do_action( 'upfront_whitewrap_close' );
		echo '</div>';
		do_action( 'upfront_body_close' );
		echo '</body>';
	}
}