<?php
global $wpdb, $post;
?>
<h2 class="nav-tab-wrapper big-tabs-tabs">
	<a class="nav-tab" href="#tab-system-info"><?php _e('Systeminformationen', 'upfront'); ?></a>
	<a class="nav-tab" href="#tab-replace-url"><?php _e('Url ersetzen', 'upfront'); ?></a>
	<a class="nav-tab" href="#tab-snapshots"><?php _e('Snapshots', 'upfront'); ?></a>
	<a class="nav-tab" href="#tab-reset"><?php _e('Zurücksetzen', 'upfront'); ?></a>
</h2>

<?php do_action('upfront_admin_save_message'); ?>


<div class="big-tabs-container">

	<div class="big-tab" id="tab-system-info-content">

		<div id="system-info">

			<h3 class="title" style="margin-bottom: 10px;"><strong><?php _e('Systeminformationen', 'upfront'); ?></strong></h3>


<?php
if ( apply_filters( 'replace_editor', false, $post ) === true ) {

}?>

			<p class="description">
				<?php _e('Kopiere diese Informationen und füge sie auf Anfrage in Support/Foren ein.', 'upfront'); ?>
				<br /><br />
				<strong><?php _e('Bitte kopiere den gesamten Inhalt in den Textbereich unten und füge ihn unverändert in die gewünschte Forumsdiskussion ein.', 'upfront'); ?></strong>
			</p>

			<?php
			$browser = upfront_get_browser();

			$post_count = wp_count_posts('post');
			$page_count = wp_count_posts('page');

			$snapshots_info = UpFrontDataSnapshots::get_table_info();
			?>

<textarea readonly="readonly" id="system-info-textarea" title="<?php _e('Um die Systeminformationen zu kopieren, klicke unten und drücke Strg + C (PC) oder Cmd + C (Mac).', 'upfront'); ?>">

    ### Begin System Info ###

    Server Time: 		<?php echo date("Y-m-d H:i:s") . "\n" ?>
    Operating system: 	<?php echo (defined('PHP_OS') ? PHP_OS : 'unbekannt') . "\n";?>

	Child Theme:		<?php echo UPFRONT_CHILD_THEME_ACTIVE ? wp_get_theme() . "\n" : "N/A\n" ?>

    Multi-site: 		<?php echo is_multisite() ? 'Ja' . "\n" : 'Nein' . "\n" ?>

    SITE_URL:  			<?php echo site_url() . "\n"; ?>
    HOME_URL:			<?php echo home_url() . "\n"; ?>

    UpFront Version:  	<?php echo UPFRONT_VERSION . "\n"; ?>
    WordPress Version:	<?php echo get_bloginfo('version') . "\n"; ?>

    PHP Version:		<?php echo PHP_VERSION . "\n"; ?>
    MySQL Version:		<?php echo $wpdb->db_version() . "\n"; ?>
    Web Server Info:	<?php echo $_SERVER['SERVER_SOFTWARE'] . "\n"; ?>
    GD Support:			<?php echo function_exists('gd_info') ? "Ja\n" : "***WARNUNG*** Nein\n"; ?>

    PHP Memory Limit:	<?php echo ini_get('memory_limit') . "\n"; ?>
    PHP Post Max Size:	<?php echo ini_get('post_max_size') . "\n"; ?>

    WP_DEBUG: 			<?php echo defined('WP_DEBUG') ? WP_DEBUG ? 'Aktiv' . "\n" : 'Inaktiv' . "\n" : 'Nicht festgelegt' . "\n" ?>
	SCRIPT_DEBUG: 		<?php echo defined('SCRIPT_DEBUG') ? SCRIPT_DEBUG ? 'Aktiv' . "\n" : 'Inaktiv' . "\n" : 'Nicht festgelegt' . "\n" ?>
    Debug Mode: 		<?php echo UpFrontOption::get('debug-mode', false, false) ? 'Aktiv' . "\n" : 'Inaktiv' . "\n" ?>

	Show On Front: 		<?php echo get_option('show_on_front') . "\n" ?>
	Page On Front: 		<?php echo get_option('page_on_front') . "\n" ?>
	Page For Posts: 	<?php echo get_option('page_for_posts') . "\n" ?>

	Number of Posts:	~<?php echo $post_count->publish . "\n" ?>
	Number of Pages:	~<?php echo $page_count->publish . "\n" ?>
	Number of Blocks: 	~<?php echo count(UpFrontBlocksData::get_all_blocks()) . "\n" ?>

	Snapshots:          <?php echo $snapshots_info['count']; ?> snapshots taking up <?php echo $snapshots_info['size']; ?> of disk space.

	Responsive Gitter: 	<?php echo UpFrontResponsiveGrid::is_enabled() ? 'Aktiv' . "\n" : 'Inaktiv' . "\n" ?>

    Caching Allowed: 	<?php echo UpFrontCompiler::can_cache() ? 'Ja' . "\n" : 'Nein' . "\n"; ?>
    Caching Enabled: 	<?php echo UpFrontCompiler::caching_enabled() ? 'Ja' . "\n" : 'Nein' . "\n"; ?>
    Caching Plugin: 	<?php echo UpFrontCompiler::is_plugin_caching() ? UpFrontCompiler::is_plugin_caching() . "\n" : 'Kein Caching-Plugin aktiv' . "\n" ?>

	SEO Plugin: 		<?php echo UpFrontSEO::plugin_active() ? UpFrontSEO::plugin_active() . "\n" : 'Kein SEO-Plugin aktiv' . "\n" ?>

    Operating System:	<?php echo ucwords($browser['platform']) . "\n"; ?>
    Browser:			<?php echo $browser['name'] . "\n"; ?>
    Browser Version:	<?php echo $browser['version'] . "\n"; ?>

    Full User Agent:
    <?php echo $browser['userAgent'] . "\n"; ?>


    WEB FONTS IN USE:
<?php
$webfonts_in_use = UpFrontWebFontsLoader::get_fonts_in_use();

if ( is_array($webfonts_in_use) && count($webfonts_in_use) ) {

	foreach ( $webfonts_in_use as $provider => $fonts )
		foreach ( $fonts as $font )
			echo '    ' . $provider . ': ' . $font . "\n";

} else {

	echo '    None' . "\n";

}
?>


    ACTIVE PLUGINS:
<?php
$plugins = get_plugins();
$active_plugins = get_option('active_plugins', array());

if ( is_array($active_plugins) && count($active_plugins) ) {

	foreach ( $plugins as $plugin_path => $plugin ) {

		//If the plugin isn't active, don't show it.
		if ( !in_array($plugin_path, $active_plugins) )
			continue;

		echo '    ' . $plugin['Name'] . ' ' . $plugin['Version'] . "\n";

		if ( isset($plugin['PluginURI']) )
			echo '    ' . $plugin['PluginURI'] . "\n";

		echo "\n";

	}

} else {

	echo '    Keine' . "\n\n";

}
?>
    ### End System Info ###

</textarea>

		</div><!-- #system-info -->

	</div><!-- #tab-system-info-content -->

	<div class="big-tab" id="tab-replace-url-content">

		<h3 class="title" style="margin-bottom: 10px;"><strong><?php _e('Url ersetzen', 'upfront'); ?></strong></h3>

		<p class="description">
			<?php 
				echo __('<strong>Wichtig:</strong> Es wird dringend empfohlen, dass Du <a target="_blank" href="https://codex.wordpress.org/WordPress_Backups">Deine Datenbank sicherst</a> bevor Du Url ersetzen verwendest. Diese Option ändert nur die UpFront-Einstellungen.', 'upfront'); 
			?><br /><br />			
		</p>

		<form method="post" id="upfront-replace-url">
			
			<input type="text" name="from" placeholder="https://old-url.com" class="">
			<input type="text" name="to" placeholder="https://new-url.com" class="">

			<input type="hidden" value="<?php echo wp_create_nonce( 'upfront-replace-url-nonce' ); ?>" name="upfront-replace-url-nonce" id="upfront-replace-url-nonce" />
			<br>
			<input type="submit" value="Url ersetzen" class="button button-primary upfront-medium-button" name="upfront-replace-url" id="upfront-replace-url" />
		</form>
		<!-- #reset -->

	</div>


	<div class="big-tab" id="tab-snapshots-content">

		<h3 class="title" style="margin-bottom: 10px;"><strong><?php _e('Snapshots', 'upfront'); ?></strong></h3>

		<p class="description">
			<?php 
				echo sprintf( 
					__('Derzeit belegen %s Snapshots %s Speicherplatz.', 'upfront'), 
					$snapshots_info['count'], 
					$snapshots_info['size'] 
				); 
			?><br /><br />
			<?php _e('Du kannst einzelne Snapshots im visuellen Editor unter Snapshots löschen, wenn Du nicht alle Snapshots löschen möchtest.', 'upfront'); ?>
		</p>

		<form method="post" id="upfront-delete-snapshots">
			<input type="hidden" value="<?php echo wp_create_nonce( 'upfront-delete-snapshots-nonce' ); ?>" name="upfront-delete-snapshots-nonce" id="upfront-delete-snapshots-nonce" />

			<input type="submit" value="Alle Snapshots löschen" class="button button-primary upfront-medium-button" name="upfront-delete-snapshots" id="upfront-delete-snapshots" onclick="return confirm(<?php 

				_e('\'Vorsicht! Dadurch werden ALLE Snapshots gelöscht. Dies bedeutet, dass Du Deine Webseite erst dann zurücksetzen kannst, wenn Du neue Snapshots erstellst. OK zum Löschen, Abbrechen zum Stoppen\'', 'upfront'); 

				?>);" />
		</form>
		<!-- #reset -->

	</div>
	<!-- #tab-reset-content -->

	<div class="big-tab" id="tab-reset-content">

		<?php if ( defined('UPFRONT_ALLOW_RESET') && UPFRONT_ALLOW_RESET === true ): ?>
		<?php if ( !isset($GLOBALS['upfront_reset_success']) || $GLOBALS['upfront_reset_success'] == false ): ?>
		<div class="alert-red reset-alert alert">
			<h3><?php _e('Warnung', 'upfront'); ?></h3>

			<p><?php _e('Durch Klicken auf die Schaltfläche <em>Zurücksetzen</em> unten werden <strong>ALLE</strong> vorhandenen UpFront-Daten gelöscht, einschließlich, aber nicht beschränkt auf: Blöcke, Designeinstellungen und UpFront-Suchmaschinenoptimierungseinstellungen.', 'upfront'); ?></p>

			<form method="post" id="reset-upfront">
				<input type="hidden" value="<?php echo wp_create_nonce('upfront-reset-nonce'); ?>" name="upfront-reset-nonce" id="upfront-reset-nonce" />

				<input type="submit" value="Reset UpFront" class="button alert-big-button" name="reset-upfront" id="reset-upfront-submit" onclick="return confirm('Warnung! ALLE vorhandenen UpFront-Daten, einschließlich, aber nicht beschränkt auf: Blöcke, Designeinstellungen und UpFront-Suchmaschinenoptimierungseinstellungen, werden gelöscht. Das kann nicht rückgängig gemacht werden. \'OK\' zum löschen, \'Abbrechen\' zum stoppen');" />
			</form><!-- #reset -->
		</div>
		<?php endif; ?>
		<?php else: ?>
		<div class="alert-yellow reset-info alert">
			<h3><?php _e('Zurücksetzen des UpFront-Themas deaktiviert', 'upfront'); ?></h3>

			<p><?php _e('Aus Sicherheitsgründen ist das Zurücksetzen von UpFront Theme deaktiviert.', 'upfront'); ?></p>

			<p><?php _e('Wenn Du Deine UpFront-Installation zurücksetzen möchtest, bitte <span style="font-weight: 600;color: #fff;background: #2f2f2f; padding: 2px 4px;">Füge den folgenden Code zu Deiner Datei wp-config.php hinzu</span>. <p>Stelle sicher, dass Du den Code über dieser Zeile in Deine Datei wp.config.php einfügst: <code> /* That\'s all, stop editing! Happy blogging. */</code><br />Nicht sicher, wie Du Deine Datei wp-config.php bearbeiten sollst? Bitte lese <a href="http://codex.wordpress.org/Editing_wp-config.php" target="_blank">Bearbeite wp-config.php</a> in der offiziellen WordPress-Dokumentation.', 'upfront'); ?></p>

			<textarea class="code" style="width: 400px;height:45px;resize:none;margin: 10px 0 10px;" readonly="readonly">define('UPFRONT_ALLOW_RESET', true);</textarea>
		</div>
		<?php endif; ?>

	</div><!-- #tab-reset-content -->

</div>