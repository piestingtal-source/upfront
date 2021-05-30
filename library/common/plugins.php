<?php

class UpFrontPlugins{

	public static function init() {

		/**
		 *
		 * If option "Do not recommend plugin installation" is on, UpFront will not recommended installation of plugins "Updater" and "Services"
		 *
		 */

		if(UpFrontOption::get('do-not-recommend-plugin-installation')){
			return;
		}

		require_once get_template_directory() . '/library/common/lib/class-tgm-plugin-activation.php';

		add_action( 'tgmpa_register', array(__CLASS__,'upfront_register_required_plugins'));

	}

	/**
	 * Register the required plugins for this theme.
	 *	
	 * This function is hooked into `tgmpa_register`, which is fired on the WP `init` action on priority 10.
	 */
	public static function upfront_register_required_plugins() {
		/*
		 * Array of plugin arrays. Required keys are name and slug.
		 * If the source is NOT from the .org repo, then source is also required.
		 */
		$plugins = array(

			array(
				'name'               => 'UpFront Shortcodes Plugin', 
				'slug'               => 'upfront-shortcodes', 
				'source'             => get_template_directory() . '/plugins/upfront-shortcodes.zip', 
				'required'           => false, 
				'version'            => '1.0.2', // E.g. 1.0.0. If set, the active plugin must be this version or higher. If the plugin version is higher than the plugin version installed, the user will be notified to update the plugin.
				'force_activation'   => false, // If true, plugin is activated upon theme activation and cannot be deactivated until theme switch.
				'force_deactivation' => false, // If true, plugin is deactivated upon theme switch, useful for theme-specific plugins.
				//'external_url'       => '', // If set, overrides default API URL and points to an external URL.
				'is_callable'        => true, // If set, this callable will be be checked for availability to determine if a plugin is active.
					),
			);

		/*
		 * Array of configuration settings. Amend each line as needed.
		 *
		 * TGMPA will start providing localized text strings soon. If you already have translations of our standard
		 * strings available, please help us make TGMPA even better by giving us access to these translations or by
		 * sending in a pull-request with .po file(s) with the translations.
		 *
		 * Only uncomment the strings in the config array if you want to customize the strings.
		 */
		$config = array(
			'id'           => 'upfront',                 // Unique ID for hashing notices for multiple instances of TGMPA.
			'default_path' => '',                      // Default absolute path to bundled plugins.
			'menu'         => 'tgmpa-install-plugins', // Menu slug.
			'parent_slug'  => 'themes.php',            // Parent menu slug.
			'capability'   => 'edit_theme_options',    // Capability needed to view plugin install page, should be a capability associated with the parent menu used.
			'has_notices'  => true,                    // Show admin notices or not.
			'dismissable'  => true,                    // If false, a user cannot dismiss the nag message.
			'dismiss_msg'  => '',                      // If 'dismissable' is false, this message will be output at top of nag.
			'is_automatic' => true,                   // Automatically activate plugins after installation or not.
			'message'      => '',                      // Message to output right before the plugins table.
		);

		tgmpa( $plugins, $config );
	}


}



