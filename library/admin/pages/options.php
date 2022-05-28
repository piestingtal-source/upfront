<h2 class="nav-tab-wrapper big-tabs-tabs">
	<a class="nav-tab" href="#tab-general"><?php _e('Allgemeines', 'upfront'); ?></a>
	<a class="nav-tab" href="#tab-seo"><?php _e('Suchmaschinenoptimierung', 'upfront'); ?></a>
	<a class="nav-tab" href="#tab-scripts"><?php _e('Skripte/Analytics', 'upfront'); ?></a>
	<a class="nav-tab" href="#tab-visual-editor"><?php _e('Visueller Editor', 'upfront'); ?></a>
	<a class="nav-tab" href="#tab-advanced"><?php _e('Fortgeschritten', 'upfront'); ?></a>
	<a class="nav-tab" href="#tab-compatibility"><?php _e('Kompatibilität', 'upfront'); ?></a>
	<a class="nav-tab" href="#tab-mobile"><?php _e('Mobil', 'upfront'); ?></a>
	<a class="nav-tab" href="#tab-fonts"><?php _e('Schriften', 'upfront'); ?></a>
</h2>

<?php do_action('upfront_admin_save_message'); ?>
<?php do_action('upfront_admin_save_error_message'); ?>

<form method="post">
	<input type="hidden" value="<?php echo wp_create_nonce('upfront-admin-nonce'); ?>" name="upfront-admin-nonce" id="upfront-admin-nonce" />


	<div class="big-tabs-container">

		<div class="big-tab" id="tab-general-content">

			<!-- General -->
			<div id="tab-general-content" class="postbox-container upfront-postbox-container">
				<div id="" class="postbox upfront-admin-options-group">

					<button type="button" class="handlediv" aria-expanded="false">
						<span class="screen-reader-text">Toggle panel: General</span>
						<span class="toggle-indicator" aria-hidden="true"></span>
					</button>


					<h2 class="hndle"><span>Allgemeines</span></h2>

					<?php
					$form = array(
						array(
							'id' => 'favicon',
							'size' => 'large',
							'type' => 'text',
							'label' => 'Favicon URL',
							'value' => UpFrontOption::get('favicon'),
							'description' => __('Ein Favicon ist das kleine Bild, das sich neben Deiner Web-Adresse im Favoritenmenü und auf Registerkarten befindet. Wenn Du nicht weist, wie Du ein Bild als Symbol speichern kannst, kannst Du auf <a href="http://www.favicon.cc/" target="_blank">favicon.cc</a> ein Bild zeichnen oder importieren.', 'upfront')
						),

						array(
							'id' => 'feed-url',
							'size' => 'large',
							'type' => 'text',
							'label' => 'Feed URL',
							'description' => __('Wenn Du einen Dienst wie <a href="http://feedburner.google.com/" target="_blank">FeedBurner</a> nutzt, gib hier die Feed-URL ein.', 'upfront'),
							'value' => UpFrontOption::get('feed-url')
						)
					);

					UpFrontAdminInputs::admin_field_generate($form);

					?>
				</div>
			</div>

			<!-- Admin Preferences -->
			<div id="tab-general-content" class="postbox-container upfront-postbox-container">		
				<div id="" class="postbox upfront-admin-options-group">

					<button type="button" class="handlediv" aria-expanded="false">
						<span class="screen-reader-text"><?php _e('Administratoreinstellungen', 'upfront'); ?></span>
						<span class="toggle-indicator" aria-hidden="true"></span>
					</button>


					<h2 class="hndle"><span>Admin Preferences</span></h2>

					<?php
					$form = array(
						array(
							'id' 		=> 'menu-setup',
							'type' 		=> 'radio',
							'label' 	=> __('Standard-Admin-Seite', 'upfront'),
							'value' 	=> UpFrontOption::get('menu-setup', false, 'getting-started'),
							'radios' 	=> array(
								array(
									'value' => 'getting-started',
									'label' => __('Erste Schritte', 'upfront')
								),

								array(
									'value' => 'visual-editor',
									'label' => __('Visueller Editor', 'upfront')
								),

								array(
									'value' => 'options',
									'label' => __('Optionen', 'upfront')
								)
							),
							'description' => __('Wähle die Administrationsseite aus, zu der Du weitergeleitet werden möchtest, wenn Du im WordPress-Administrator auf "UpFront" klickst.', 'upfront')
						),
						array(
							'type' 	=> 'checkbox',
							'label' => __('Plugin-Installation nicht empfehlen', 'upfront'),
							'checkboxes' => array(
								array(
									'id' 		=> 'do-not-recommend-plugin-installation',
									'label' 	=> __('Empfohlenes Plugin-Hinweise ausblenden', 'upfront'),
									'checked' 	=> UpFrontOption::get('do-not-recommend-plugin-installation', false, false)
								)
							),
							'description' => __('Wenn diese Option aktiviert ist, empfiehlt UpFront nicht, die Plugins "Updater" und "Services" zu installieren', 'upfront')
						),
						array(
							'type' => 'checkbox',
							'label' => __('Versionsnummer', 'upfront'),
							'checkboxes' => array(
								array(
									'id' => 'hide-menu-version-number',
									'label' => __('UpFront-Versionsnummer im Menü ausblenden', 'upfront'),
									'checked' => UpFrontOption::get('hide-menu-version-number', false, true)
								)
							),
							'description' => sprintf( __('Aktiviere diese Option, wenn im Menü "UpFront" anstelle von "UpFront %s" angezeigt werden soll.', 'upfront'), UPFRONT_VERSION )
						),
					);

					UpFrontAdminInputs::admin_field_generate($form);

					?>
				</div>
			</div>
		</div>

		<div class="big-tab" id="tab-seo-content">

			<?php
			if ( UpFrontSEO::is_disabled() ) {

				switch ( UpFrontSEO::plugin_active() ) {

					case 'aioseop':
						echo '<div class="alert alert-yellow"><p>' . __('UpFront hat festgestellt, dass Du das All In One SEO Pack-Plugin verwendest. Um Konflikte zu reduzieren und Ressourcen zu sparen, wurde die SEO-Funktionalität von UpFront deaktiviert.', 'upfront') . '</p></div>';
					break;

					case 'wpseo':
						echo '<div class="alert alert-yellow"><p>' . __('UpFront hat festgestellt, dass Du das WordPress SEO-Plugin von Yoast verwendest. Um Konflikte zu reduzieren und Ressourcen zu sparen, wurde die SEO-Funktionalität von UpFront deaktiviert.', 'upfront') . '</p></div>';
					break;

					default:
						echo '<div class="alert alert-yellow"><p>' . __('Die SEO-Funktionalität von UpFront ist deaktiviert.', 'upfront') . '</p></div>';
						break;

				}

			} else {
			?>

				<h3 class="title" id="seo-templates-title"><?php _e('SEO-Vorlagen', 'upfront'); ?></h3>

				<div id="seo-templates">
					<div id="seo-templates-hidden-inputs">
						<?php
						/* SETUP THE TYPES OF SEO TEMPLATE INPUTS */
						$seo_template_inputs = array(
							'title',
							'description',
							'noindex',
							'nofollow',
							'noarchive',
							'nosnippet',
							'noodp',
							'noydir'
						);

						/* GENERATE HIDDEN INPUTS */
						$seo_options = UpFrontOption::get('seo-templates', 'general', array());

						foreach (UpFrontSEO::output_layouts_and_defaults() as $page => $defaults) {

							foreach ($seo_template_inputs as $input) {

								$name_attr = 'name="upfront-admin-input[seo-templates][' . $page . '][' . $input . ']"';

								$default = isset($defaults[$input]) ? $defaults[$input] : null;

								$page_options = upfront_get($page, $seo_options, array());
								$value = upfront_get($input, $page_options, $default);

								echo '<input type="hidden" id="seo-' . $page . '-' . $input . '"' . $name_attr . ' value="' . stripslashes(esc_attr($value)) . '" />';

							}

						}
						?>
					</div>

					<div id="seo-templates-header">
						<span><?php _e('Vorlage auswählen:', 'upfront'); ?></span>
						<select>
							<option value="index"><?php _e('Blog-Index', 'upfront'); ?></option>

							<?php
							if ( get_option('show_on_front') == 'page' )
								echo '<option value="front_page">' . __('Startseite', 'upfront') . '</option>';
							?>

							<optgroup label="Single">
								<?php
								$post_types = get_post_types(array('public' => true), 'objects');

								foreach($post_types as $post_type)
									echo '<option value="single-' . $post_type->name . '">' . $post_type->label . '</option>';
								?>
							</optgroup>

							<optgroup label="Archive">
								<option value="archive-category"><?php _e('Kategorie', 'upfront'); ?></option>
								<option value="archive-search"><?php _e('Suche', 'upfront'); ?></option>
								<option value="archive-date"><?php _e('Datum', 'upfront'); ?></option>
								<option value="archive-author"><?php _e('Autor', 'upfront'); ?></option>
								<option value="archive-post_tag"><?php _e('Post Tag', 'upfront'); ?></option>
								<option value="archive-post_type"><?php _e('Post Typ', 'upfront'); ?></option>
								<option value="archive-taxonomy"><?php _e('Taxonomie', 'upfront'); ?></option>
							</optgroup>

							<option value="four04">404</option>

						</select>
					</div><!-- #seo-templates-header -->

					<div id="seo-templates-inputs">

						<?php
						$form = array(
							array(
								'id' => 'title',
								'type' => 'text',
								'size' => 'large',
								'label' => __('Titel', 'upfront'),
								'description' => __('Der Titel ist der Haupttext, der die Seite beschreibt. Es ist das wichtigste On-Page-SEO-Element (hinter dem Gesamtinhalt). Der Titel wird beim Anzeigen der Seite oben im Webbrowser, in Browser-Registerkarten, Suchmaschinenergebnissen und auf externen Webseiten angezeigt. <strong>WebMasterTipp:</strong> Es ist am besten, wenn der Titel unter 70 Zeichen bleibt.<br /><br /><a href="http://www.seomoz.org/learn-seo/title-tag" target="_blank">Erfahre mehr über Titel &raquo;</a>', 'upfront'),
								'no-submit' => true
							),

							array(
								'id' => 'description',
								'type' => 'paragraph',
								'cols' => 60,
								'rows' => 3,
								'label' => '<code>&lt;meta&gt;</code> ' . __('Beschreibung', 'upfront'),
								'description' => __('Meta-Beschreibungs-Tags sind zwar für Suchmaschinen-Rankings nicht wichtig, aber äußerst wichtig, um Benutzer-Click-through von Suchmaschinen-Ergebnisseiten (SERPs) zu erzielen. Diese kurzen Absätze bieten Dir die Möglichkeit, Inhalte für Suchende zu bewerben und sie genau darüber zu informieren, was die angegebene Seite in Bezug auf das, was sie suchen, enthält. <strong>WebMasterTipp:</strong> Eine gute Beschreibung besteht aus ca. 150 Zeichen.<br /><br /><a href="http://www.seomoz.org/learn-seo/meta-description" target="_blank">Lerne mehr über &lt;meta&gt; Beschreibungen &raquo;</a>', 'upfront'),
								'no-submit' => true
							)
						);

						UpFrontAdminInputs::generate($form);
						?>

						<div class="hr"></div>

						<p><strong><?php _e('Du kannst die folgenden Variablen in den obigen Eingaben für Titel und Beschreibung verwenden:', 'upfront'); ?></strong></p>

						<ul>
							<li><code>%title%</code> &mdash; <?php _e('Ruft den Titel des Beitrags, Archivs oder der Seite ab, der angezeigt wird.', 'upfront'); ?></li>
							<li><code>%sitename%</code> &mdash; <?php echo sprintf( __('Ruft den Namen der Webseite ab. Dies kann eingestellt werden in <a href="%s" target="_blank">Einstellungen &raquo; Allgemeines</a>.', 'upfront'), admin_url('options-general.php') ); ?></li>

							<li><code>%tagline%</code> &mdash; <?php echo sprintf( __('Ruft den Slogan/die Beschreibung der Webseite ab. Dies kann eingestellt werden in <a href="%s" target="_blank">Einstellungen &raquo; Allgemeines</a>.', 'upfront'), admin_url('options-general.php') ); ?></li>

							<li><code>%meta%</code> &mdash; <?php _e('Wird nur in Taxonomiearchiven verwendet, um den Begriff Namen anzuzeigen.', 'upfront'); ?></li>
						</ul>

						<h3 id="seo-templates-advanced-options-title" class="title title-hr"><?php _e('Erweiterte Optionen <span>Anzeigen &darr;</span>', 'upfront'); ?></h3>

						<div id="seo-templates-advanced-options">
							<?php
							$form = array(
								array(
									'type' => 'checkbox',
									'label' => __('Seitenindizierung', 'upfront'),
									'checkboxes' => array(
										array(
											'id' => 'noindex',
											'label' => 'Aktiviere <code>noindex</code>',
											'no-submit' => true
										)
									),
									'description' => __('Index/NoIndex teilt den Engines mit, ob die Seite gecrawlt und zum Abrufen im Engines-Index aufbewahrt werden soll. Wenn Du dieses Kontrollkästchen aktivierst, um <code>noindex</code> zu wählen, wird die Seite von den Engines ausgeschlossen. <strong>Hinweis:</strong> Wenn Du nicht sicher bist, was dies bewirkt, aktiviere dieses Kontrollkästchen nicht.', 'upfront')
								),

								array(
									'type' => 'checkbox',
									'label' => __('Link folgen', 'upfront'),
									'checkboxes' => array(
										array(
											'id' => 'nofollow',
											'label' => 'Aktiviere <code>nofollow</code>',
											'no-submit' => true
										)
									),
									'description' => __('Follow/NoFollow teilt den Engines mit, ob Links auf der Seite gecrawlt werden sollen. Wenn Du dieses Kontrollkästchen aktivierst, um "nofollow" zu verwenden, ignorieren die Engines die Links auf der Seite sowohl für Erkennungs- als auch für Rankingzwecke. <strong>Hinweis:</strong> Wenn Du nicht sicher bist, was dies bewirkt, aktiviere dieses Kontrollkästchen nicht.', 'upfront')
								),

								array(
									'type' => 'checkbox',
									'label' => __('Seitenarchivierung', 'upfront'),
									'checkboxes' => array(
										array(
											'id' => 'noarchive',
											'label' => __('Aktiviere <code>noarchive</code>', 'upfront'),
											'no-submit' => true
										)
									),
									'description' => __('Noarchive wird verwendet, um zu verhindern, dass Suchmaschinen eine zwischengespeicherte Kopie der Seite speichern. Standardmäßig behalten die Suchmaschinen sichtbare Kopien aller von ihnen indizierten Seiten bei, auf die Suchende über den "zwischengespeicherten" Link in den Suchergebnissen zugreifen können. Aktiviere dieses Kontrollkästchen, um zu verhindern, dass Suchmaschinen zwischengespeicherte Kopien dieser Seite speichern.', 'upfront')
								),

								array(
									'type' => 'checkbox',
									'label' => __('Effekte', 'upfront'),
									'checkboxes' => array(
										array(
											'id' => 'nosnippet',
											'label' => __('Aktiviere <code>nosnippet</code>', 'upfront'),
											'no-submit' => true
										)
									),
									'description' => __('Nosnippet informiert die Suchmaschinen darüber, dass sie keinen beschreibenden Textblock neben dem Titel und der URL der Seite in den Suchergebnissen anzeigen sollen.', 'upfront')
								),

								array(
									'type' => 'checkbox',
									'label' => __('Open Directory Project (DMOZ)', 'upfront'),
									'checkboxes' => array(
										array(
											'id' => 'noodp',
											'label' => __('Aktiviere <code>NoODP</code>', 'upfront'),
											'no-submit' => true
										)
									),
									'description' => __('NoODP ist ein spezielles Tag, das den Suchmaschinen anweist, kein beschreibendes Snippet über eine Seite aus dem Open Directory-Projekt (DMOZ) zur Anzeige in den Suchergebnissen abzurufen.', 'upfront')
								),

								array(
									'type' => 'checkbox',
									'label' => __('Yahoo! Verzeichnis', 'upfront'),
									'checkboxes' => array(
										array(
											'id' => 'noydir',
											'label' => __('Aktiviere <code>NoYDir</code>', 'upfront'),
											'no-submit' => true
										)
									),
									'description' => __('NoYDir ist wie NoODP spezifisch für Yahoo! und informiert diese Engine darüber, dass Yahoo! Verzeichnisbeschreibung einer Seite/Webseite in den Suchergebnissen.', 'upfront')
								)
							);

							UpFrontAdminInputs::generate($form);
							?>
						</div><!-- #seo-templates-advanced-options -->

					</div><!-- #seo-templates-inputs -->
				</div><!-- #seo-templates-content -->

				<div id="seo-description" class="alert alert-yellow"><p>
					<?php _e('Nicht mit <em>Suchmaschinenoptimierung</em> vertraut?', 'upfront'); ?>  <a href="http://www.seomoz.org/beginners-guide-to-seo/" target="_blank"><?php _e('Erfahre mehr', 'upfront'); ?> &raquo;</a></p>
				</div>


				<!-- Content <code>nofollow</code> Links -->
				<div id="tab-general-content" class="postbox-container upfront-postbox-container">		
					<div id="" class="postbox upfront-admin-options-group">

						<button type="button" class="handlediv" aria-expanded="false">
							<span class="screen-reader-text"><?php _e('Content <code>nofollow</code> Links', 'upfront'); ?></span>
							<span class="toggle-indicator" aria-hidden="true"></span>
						</button>


						<h2 class="hndle"><span><?php _e('Content <code>nofollow</code> Links', 'upfront'); ?></span></h2>

						<?php
						$form = array(
							array(
								'type' => 'checkbox',
								'label' => 'Kommentarautoren URLs',
								'checkboxes' => array(
									array(
										'id' => 'nofollow-comment-author-url',
										'label' => __('Nofollow zu Kommentarautoren URLs hinzufügen', 'upfront'),
										'checked' => UpFrontOption::get('nofollow-comment-author-url', 'general', false)
									)
								),
								'description' => __('Durch Hinzufügen von nofollow zu den URLs der Autoren des Kommentars werden Suchmaschinen angewiesen, ihre Webseite nicht zu besuchen und auf Deiner zu bleiben. Viele Blogger runzeln die Stirn, was Kommentare manchmal entmutigen kann. Aktiviere diese Option nur, wenn Du zu 100% sicher bist, dass Du dies möchtest.', 'upfront')
							)
						);

						UpFrontAdminInputs::admin_field_generate($form);

						?>
					</div>
				</div>


				<!-- Deaktiviere Unterstützung von Schema.org -->
				<div id="tab-general-content" class="postbox-container upfront-postbox-container">		
					<div id="" class="postbox upfront-admin-options-group">

						<button type="button" class="handlediv" aria-expanded="false">
							<span class="screen-reader-text"><?php _e('Deaktiviere Unterstützung von Schema.org', 'upfront'); ?></span>
							<span class="toggle-indicator" aria-hidden="true"></span>
						</button>


						<h2 class="hndle"><span><?php _e('Deaktiviere Unterstützung von Schema.org', 'upfront'); ?></span></h2>

						<?php
						$form = array(
							array(
								'type' => 'checkbox',
								'label' => __('Deaktiviere Mikrodaten-Markup', 'upfront'),
								'checkboxes' => array(
									array(
										'id' => 'disable-schema-support',
										'label' => __('Füge keine ld+json Daten hinzu', 'upfront'),
										'checked' => UpFrontOption::get('disable-schema-support', 'general', false)
									)
								),
								'description' => __('Schema.org ist ein Vokabular von Mikrodaten-Markups, das Such-Crawlern das Verständnis der Inhalte einer Webseite erleichtern soll.', 'upfront')
							)
						);

						UpFrontAdminInputs::admin_field_generate($form);

						?>
					</div>
				</div>				

			<?php
			}
			?>
		</div>

		<div class="big-tab" id="tab-scripts-content">

			<!-- Skripte/Analytics -->
			<div id="tab-general-content" class="postbox-container upfront-postbox-container">		
				<div id="" class="postbox upfront-admin-options-group">

					<button type="button" class="handlediv" aria-expanded="false">
						<span class="screen-reader-text"><?php _e('Skripte/Analytics', 'upfront'); ?></span>
						<span class="toggle-indicator" aria-hidden="true"></span>
					</button>


					<h2 class="hndle"><span><?php _e('Skripte/Analytics', 'upfront'); ?></span></h2>

					<?php
					$form = array(
						array(
							'id' => 'header-scripts',
							'type' => 'paragraph',
							'cols' => 90,
							'rows' => 8,
							'label' => __('Header Skripte', 'upfront'),
							'description' => 'Alles hier wird in den <code>&lt;head&gt;</code> der Webseite eingefügt. Wenn Du <a href="http://google.com/analytics" target="_blank">Google Analytics</a> verwendest, füge den benötigten Code hier ein. <strong>Platziere keinen Klartext darin!</strong>',
							'allow-tabbing' => true,
							'value' => UpFrontOption::get('header-scripts')
						),

						array(
							'id' => 'footer-scripts',
							'type' => 'paragraph',
							'cols' => 90,
							'rows' => 8,
							'label' => __('Footer Skripte', 'upfront'),
							'description' => __('Alles hier wird vor dem <code>&lt;/body&gt;</code> Tag der Webseite eingefügt. <strong>Platziere keinen Klartext darin!</strong>', 'upfront'),
							'allow-tabbing' => true,
							'value' => UpFrontOption::get('footer-scripts')
						)
					);

					UpFrontAdminInputs::admin_field_generate($form);

					?>
				</div>
			</div>
		</div>

		<div class="big-tab" id="tab-visual-editor-content">

			<!-- Visual Editor -->
			<div id="tab-general-content" class="postbox-container upfront-postbox-container">		
				<div id="" class="postbox upfront-admin-options-group">

					<button type="button" class="handlediv" aria-expanded="false">
						<span class="screen-reader-text"><?php _e('Visueller Editor', 'upfront'); ?></span>
						<span class="toggle-indicator" aria-hidden="true"></span>
					</button>


					<h2 class="hndle"><span><?php _e('Visueller Editor', 'upfront'); ?></span></h2>

					<?php
					$form = array(
						array(
							'type' => 'checkbox',
							'label' => __('Tooltips', 'upfront'),
							'checkboxes' => array(
								array(
									'id' => 'disable-visual-editor-tooltips',
									'label' => __('Deaktiviere Tooltips im visuellen Editor', 'upfront'),
									'checked' => UpFrontOption::get('disable-visual-editor-tooltips', false, false)
								)
							),
							'description' => __('Wenn Du jemals das Gefühl hast, dass die QuickInfos im visuellen Editor zu invasiv sind, kannst Du sie hier deaktivieren. Tooltips sind die schwarzen Sprechblasen, die Dir helfen, wenn Du nicht sicher bist, was eine Option ist oder wie sie funktioniert.', 'upfront')
						),
						array(
							'type' => 'checkbox',
							'label' => __('Editor-Stil', 'upfront'),
							'checkboxes' => array(
								array(
									'id' => 'disable-editor-style',
									'label' => __('Editorstil deaktivieren', 'upfront'),
									'checked' => UpFrontOption::get('disable-editor-style', false, false)
								)
							),
							'description' => __('Standardmäßig übernimmt UpFront alle Einstellungen im Design-Editor und fügt sie <a href="http://codex.wordpress.org/TinyMCE" target="_blank">WordPress\' TinyMCE Editor</a> Stil hinzu. Verwende diese Option, um dies zu verhindern.', 'upfront')
						),
						array(
							'type' => 'checkbox',
							'label' => __('Versteckte Container im Designmodus anzeigen', 'upfront'),
							'checkboxes' => array(
								array(
									'id' => 'show-hidden-wrappers-on-design-mode',
									'label' => __('Versteckte Container im Designmodus anzeigen', 'upfront'),
									'checked' => UpFrontOption::get('show-hidden-wrappers-on-design-mode', false, false)
								)
							),
							'description' => __('Versteckte Container im Designmodus anzeigen. Wenn Du einen Haltepunkt konfigurierst, um den Container auszublenden, fordert diese Option Visual Editor auf, den zu gestaltenden Container sichtbar zu lassen.', 'upfront')
						)
					);

					UpFrontAdminInputs::admin_field_generate($form);

					?>
				</div>
			</div>
		</div>

		<div class="big-tab" id="tab-advanced-content">

			<!-- Advanced -->
			<div class="postbox-container upfront-postbox-container">
				<div id="" class="postbox upfront-admin-options-group">

					<button type="button" class="handlediv" aria-expanded="false">
						<span class="screen-reader-text"><?php _e('Automatische Updates', 'upfront'); ?></span>
						<span class="toggle-indicator" aria-hidden="true"></span>
					</button>


					<h2 class="hndle"><span><?php _e('Automatische Updates', 'upfront'); ?></span></h2>

					<?php
					$form = array(
						array(
							'type' => 'checkbox',
							'label' => __('Deaktiviere automatische Kernaktualisierungen', 'upfront'),
							'checkboxes' => array(
								array(
									'id' => 'disable-automatic-core-updates',
									'label' => __('Deaktiviere automatische Kernaktualisierungen', 'upfront'),
									'checked' => UpFrontOption::get('disable-automatic-core-updates', false, false)
								)
							),
							'description' => __('Standardmäßig versucht UpFront, automatisch zu aktualisieren. Wenn diese Option aktiviert ist, werden keine automatischen Aktualisierungen durchgeführt. Diese Option erfordert das UpFront Updater Plugin.', 'upfront')
						),
						array(
							'type' => 'checkbox',
							'label' => __('Deaktiviere automatische Plugin-Updates', 'upfront'),
							'checkboxes' => array(
								array(
									'id' => 'disable-automatic-plugin-updates',
									'label' => __('Deaktiviere automatische Plugin-Updates', 'upfront'),
									'checked' => UpFrontOption::get('disable-automatic-plugin-updates', false, false)
								)
							),
							'description' => __('Standardmäßig versucht das Updater-Plugin, UpFront-Plugins automatisch zu aktualisieren. Wenn diese Option aktiviert ist, werden keine automatischen Updates für Plugins durchgeführt. Diese Option erfordert das UpFront Updater Plugin.', 'upfront')
						),
					);

					UpFrontAdminInputs::admin_field_generate($form);

					?>
				</div>
			</div>

			<!-- Caching &amp; Compression -->
			<div id="tab-general-content" class="postbox-container upfront-postbox-container">		
				<div id="" class="postbox upfront-admin-options-group">

					<button type="button" class="handlediv" aria-expanded="false">
						<span class="screen-reader-text"><?php _e('Caching &amp; Komprimierung', 'upfront'); ?></span>
						<span class="toggle-indicator" aria-hidden="true"></span>
					</button>


					<h2 class="hndle"><span><?php _e('Caching &amp; Komprimierung', 'upfront'); ?></span></h2>

					<?php
					$form = array(
						array(
							'type' => 'checkbox',
							'label' => __('Asset-Caching', 'upfront'),
							'checkboxes' => array(
								array(
									'id' => 'disable-caching',
									'label' => __('Deaktiviere UpFront-Caching', 'upfront'),
									'checked' => UpFrontOption::get('disable-caching', false, false)
								)
							),
							'description' => __('Standardmäßig versucht UpFront, das gesamte generierte CSS und JavaScript zwischenzuspeichern. Es kann jedoch selten vorkommen, dass das Deaktivieren des Caches bei bestimmten Problemen hilfreich ist.<br /><br /><em> <strong> Wichtig:</strong> Das Deaktivieren des UpFront-Cache führt zu einer <strong>Erhöhung der Seitenladezeiten</strong> und <strong>erhöhen die Belastung Deines Webservers</strong> bei jedem Laden der Seite.', 'upfront')
						),

						array(
							'type' => 'checkbox',
							'label' => __('Variablen für Abhängigkeitsabfragen', 'upfront'),
							'checkboxes' => array(
								array(
									'id' => 'remove-dependency-query-vars',
									'label' => __('Entferne Abfragevariablen aus Abhängigkeits-URLs', 'upfront'),
									'checked' => UpFrontOption::get('remove-dependency-query-vars', false, false)
								)
							),
							'description' => __('Um das Browser-Caching zu nutzen, kann UpFront WordPress anweisen, keine Abfragevariablen auf statische Assets wie CSS- und JavaScript-Dateien zu setzen.', 'upfront')
						),

						array(
							'type' => 'checkbox',
							'label' => __('Kompatibilität mit mod_pagespeed', 'upfront'),
							'checkboxes' => array(
								array(
									'id' => 'compatibility-mod_pagespeed',
									'label' => __('Kompatibilität mit mod_pagespeed', 'upfront'),
									'checked' => UpFrontOption::get('compatibility-mod_pagespeed', false, false)
								)
							),
							'description' => __('Entfernt ID- und Medienattribute von Stylesheet-Tags, sodass die Seitengeschwindigkeit sie richtig kombinieren kann. Wenn Du mod_pagespeed nicht auf Deinem Server verwendest, wird diese Funktion nichts für Dich tun.', 'upfront')
						),

						array(
							'type' => 'checkbox',
							'label' => __('HTTP/2 Server Push', 'upfront'),
							'checkboxes' => array(
								array(
									'id' => 'http2-server-push',
									'label' => __('HTTP/2 Server Push', 'upfront'),
									'checked' => UpFrontOption::get('http2-server-push', false, false)
								)
							),
							'description' => __('Ermöglicht WordPress das Senden eines Links: <...> rel = "prefetch" -Header für jedes in die Warteschlange gestellte Skript und jeden Stil, während WordPress sie in die Seitenquelle ausgibt. Benötigt einen Webserver, der HTTP/2 unterstützt. <strong>Wichtig:</strong> Diese Funktion ist experimentell.', 'upfront')
						)
					);

					UpFrontAdminInputs::admin_field_generate($form);

					?>
				</div>
			</div>
		</div>

		<div class="big-tab" id="tab-compatibility-content">

		<!-- Developer -->
		<div class="postbox-container upfront-postbox-container">
				<div id="" class="postbox upfront-admin-options-group">

					<button type="button" class="handlediv" aria-expanded="false">
						<span class="screen-reader-text"><?php _e('Entwickler', 'upfront'); ?></span>
						<span class="toggle-indicator" aria-hidden="true"></span>
					</button>


					<h2 class="hndle"><span><?php _e('Entwickler', 'upfront'); ?></span></h2>

					<?php
					$form = array(
						array(
							'type' => 'checkbox',
							'label' => __('Verwende die UpFront Developer-Version', 'upfront'),
							'checkboxes' => array(
								array(
									'id' => 'use-developer-version',
									'label' => __('Erlaube die Installation der Edge-Version', 'upfront'),
									'checked' => UpFrontOption::get('use-developer-version', false, false)
								)
							),
							'description' => __('Diese Option ist für Entwickler gedacht. Verwende diese Option nur, wenn Du weist, was Du tust. UpFront Theme und Plugins werden auf die Testversion aktualisiert. <strong>NICHT auf Produktivwebseiten verwenden.</strong> Diese Option erfordert das UpFront Updater Plugin. Sobald diese Option aktiviert ist, kannst Du Deine Webseite auf die neueste Version aktualisieren.', 'upfront')
						)
					);

					UpFrontAdminInputs::admin_field_generate($form);

					?>
				</div>
			</div>

			<!-- Debugging -->
			<div class="postbox-container upfront-postbox-container">
				<div id="" class="postbox upfront-admin-options-group">

					<button type="button" class="handlediv" aria-expanded="false">
						<span class="screen-reader-text"><?php _e('Debugging', 'upfront'); ?></span>
						<span class="toggle-indicator" aria-hidden="true"></span>
					</button>


					<h2 class="hndle"><span><?php _e('Debugging', 'upfront'); ?></span></h2>

					<?php
					$form = array(
						array(
							'type' => 'checkbox',
							'label' => __('Debug Modus', 'upfront'),
							'checkboxes' => array(
								array(
									'id' => 'debug-mode',
									'label' => __('Aktiviere Debug Modus', 'upfront'),
									'checked' => UpFrontOption::get('debug-mode', false, false)
								)
							),
							'description' => __('Wenn der Debug-Modus aktiviert ist, kann das UpFront Themes-Team zu Supportzwecken auf den visuellen Editor zugreifen <strong>Änderungen können nicht gespeichert werden</strong>.', 'upfront')
						)
					);

					UpFrontAdminInputs::admin_field_generate($form);

					?>
				</div>
			</div>
		</div>

		<div class="big-tab" id="tab-compatibility-content">

			<!-- Plugin-Vorlagen -->
			<div id="tab-general-content" class="postbox-container upfront-postbox-container">		
				<div id="" class="postbox upfront-admin-options-group">

					<button type="button" class="handlediv" aria-expanded="false">
						<span class="screen-reader-text"><?php _e('Plugin-Vorlagen', 'upfront'); ?></span>
						<span class="toggle-indicator" aria-hidden="true"></span>
					</button>


					<h2 class="hndle"><span><?php _e('Plugin-Vorlagen', 'upfront'); ?></span></h2>

					<?php
					$form = array(
						array(
							'type' 	=> 'checkbox',
							'label' => __('Plugin-Vorlagen', 'upfront'),
							'checkboxes' => array(
								array(
									'id' 		=> 'allow-plugin-templates',
									'label' 	=> __('Plugin-Vorlagen zulassen', 'upfront'),
									'checked' 	=> UpFrontOption::get('allow-plugin-templates', false, false)
								)
							),
							'description' => __('Zulassen von Plugin-Vorlagen für benutzerdefinierte Beitragstypen anstelle von UpFront Layout', 'upfront')
						)
					);

					UpFrontAdminInputs::admin_field_generate($form);

					?>
				</div>
			</div>


			<!-- Headway -->
			<div id="tab-general-content" class="postbox-container upfront-postbox-container">		
				<div id="" class="postbox upfront-admin-options-group">

					<button type="button" class="handlediv" aria-expanded="false">
						<span class="screen-reader-text"><?php _e('Headway', 'upfront'); ?></span>
						<span class="toggle-indicator" aria-hidden="true"></span>
					</button>


					<h2 class="hndle"><span><?php _e('Headway', 'upfront'); ?></span></h2>

					<?php
					$form = array(
						array(
							'type' 	=> 'checkbox',
							'label' => __('Headway Unterstützung', 'upfront'),
							'checkboxes' => array(
								array(
									'id' 		=> 'headway-support',
									'label' 	=> __('Aktiviere die Unterstützung für Headway-Klassen', 'upfront'),
									'checked' 	=> UpFrontOption::get('headway-support', false, false)
								)
							),
							'description' => __('Wenn diese Option aktiviert ist, versucht UpFront, alle PHP-Klassen zu unterstützen, die sich auf Headway beziehen. Auf diese Weise kannst Du Blöcke wie Headway Rocket und ähnliches verwenden. <strong>Wichtig:</strong> Diese Funktion ist experimentell.', 'upfront')
						)
					);

					UpFrontAdminInputs::admin_field_generate($form);

					?>
				</div>
			</div>
            <!-- Bloxtheme -->
			<div class="postbox-container upfront-postbox-container">
				<div id="" class="postbox upfront-admin-options-group">

					<button type="button" class="handlediv" aria-expanded="false">
						<span class="screen-reader-text"><?php _e('Bloxtheme', 'upfront'); ?></span>
						<span class="toggle-indicator" aria-hidden="true"></span>
					</button>


					<h2 class="hndle"><span><?php _e('Bloxtheme', 'upfront'); ?></span></h2>

					<?php
					$form = array(
						array(
							'type' 	=> 'checkbox',
							'label' => __('Bloxtheme support', 'upfront'),
							'checkboxes' => array(
								array(
									'id' 		=> 'bloxtheme-support',
									'label' 	=> __('Enable Bloxtheme classes support', 'upfront'),
									'checked' 	=> UpFrontOption::get('bloxtheme-support', false, false)
								)
							),
							'description' => __('If on, UpFront will attempt support all PHP classes related to Bloxtheme. This allows to you use blocks like Blox Rocket and similar. <strong>Important:</strong> This feature is Experimental.', 'upfront')
						)
					);

					UpFrontAdminInputs::admin_field_generate($form);

					?>
				</div>
			</div>

			<!-- Gutenberg -->
			<div id="tab-general-content" class="postbox-container upfront-postbox-container">		
				<div id="" class="postbox upfront-admin-options-group">

					<button type="button" class="handlediv" aria-expanded="false">
						<span class="screen-reader-text"><?php _e('Gutenberg', 'upfront'); ?></span>
						<span class="toggle-indicator" aria-hidden="true"></span>
					</button>


					<h2 class="hndle"><span><?php _e('Gutenberg', 'upfront'); ?></span></h2>

					<?php
					$form = array(
						array(
							'type' 	=> 'checkbox',
							'label' => __('UpFront-Blöcke in Gutenberg anzeigen', 'upfront'),
							'checkboxes' => array(
								array(
									'id' 		=> 'upfront-blocks-as-gutenberg-blocks',
									'label' 	=> __('UpFront-Blöcke als Gutenberg-Blöcke anzeigen', 'upfront'),
									'checked' 	=> UpFrontOption::get('upfront-blocks-as-gutenberg-blocks', false, false)
								)
							),
							'description' => __('Wenn diese Option aktiviert ist, können mit UpFront UpFront-Blöcke als Gutenberg-Blöcke verwendet werden. Gehe zu "Blockoptionen> Überall", um es zu aktivieren. <strong>Wichtig:</strong> Diese Funktion ist experimentell.', 'upfront')
						)
					);

					UpFrontAdminInputs::admin_field_generate($form);

					?>
				</div>
			</div>
		</div>

		<div class="big-tab" id="tab-mobile-content">

			<!-- Responsive options -->
			<div id="tab-general-content" class="postbox-container upfront-postbox-container">		
				<div id="" class="postbox upfront-admin-options-group">

					<button type="button" class="handlediv" aria-expanded="false">
						<span class="screen-reader-text"><?php _e('Responsivoptionen', 'upfront'); ?></span>
						<span class="toggle-indicator" aria-hidden="true"></span>
					</button>


					<h2 class="hndle"><span><?php _e('Responsivoptionen', 'upfront'); ?></span></h2>

					<?php
					$form = array(
						array(
							'type' 	=> 'checkbox',
							'label' => __('Mobiles Zoomen zulassen', 'upfront'),
							'checkboxes' => array(
								array(
									'id' 		=> 'allow-mobile-zooming',
									'label' 	=> __('Mobiles Zoomen zulassen', 'upfront'),
									'checked' 	=> UpFrontOption::get('allow-mobile-zooming', false, false)
								)
							),
							'description' => __('Fügt das Ansichtsfenster-Meta-Tag mit Zoom-Berechtigung hinzu, um Deinen Benutzern die Möglichkeit zu geben, Deine Webseite mit mobilen Browsern zu vergrößern.', 'upfront')
						)
					);

					UpFrontAdminInputs::admin_field_generate($form);

					?>
				</div>
			</div>
		</div>

		<div class="big-tab" id="tab-fonts-content">

			<!-- Schriftarten options -->
			<div id="tab-general-content" class="postbox-container upfront-postbox-container">		
				<div id="" class="postbox upfront-admin-options-group">

					<button type="button" class="handlediv" aria-expanded="false">
						<span class="screen-reader-text"><?php _e('Fonts', 'upfront'); ?></span>
						<span class="toggle-indicator" aria-hidden="true"></span>
					</button>


					<h2 class="hndle"><span><?php _e('Fonts', 'upfront'); ?></span></h2>

					<?php

					$form = array(
						array(
							'type' 	=> 'checkbox',
							'label' => __('Google-Schriftarten', 'upfront'),
							'checkboxes' => array(
								array(
									'id' 		=> 'do-not-use-google-fonts',
									'label' 	=> __('Verwende keine Google-Schriftarten', 'upfront'),
									'checked' 	=> UpFrontOption::get('do-not-use-google-fonts', false, false)
								)
							),
							'description' => __('Wenn diese Option aktiviert ist, verwendet UpFront keine Google-Schriftarten.', 'upfront')
						)
					);

					UpFrontAdminInputs::admin_field_generate($form);


					$form = array(
						array(
							'type' 	=> 'checkbox',
							'label' => __('Lade Google Schriftarten asynchron', 'upfront'),
							'checkboxes' => array(
								array(
									'id' 		=> 'load-google-fonts-asynchronously',
									'label' 	=> __('Lade Google Schriftarten asynchron', 'upfront'),
									'checked' 	=> UpFrontOption::get('load-google-fonts-asynchronously', false, false)
								)
							),
							'description' => __('Wenn diese Option aktiviert ist, lädt UpFront Schriftarten asynchron, um zu vermeiden, dass bei Verwendung von Google-Schriftarten die Schriftart zum Rendern blockiert wird.', 'upfront')
						)
					);

					UpFrontAdminInputs::admin_field_generate($form);


					$form = array(
						array(
							'id' 		=> 'google-fonts-display',
							'type' 	=> 'select',
							'label' => __('Google-Schriftarten Anzeige Zeitleiste', 'upfront'),
							'options' => array(								
								'auto' 		=> 'Auto',
								'block' 	=> 'Block',
								'swap' 		=> 'Swap',
								'fallback' 	=> 'Fallback',
								'optional' 	=> 'Optional',								
							),
							'value' => UpFrontOption::get('google-fonts-display', false, 'swap'),
							'description' => __('Legt fest, wie eine Schriftart angezeigt wird, basierend darauf, ob und wann sie heruntergeladen und für Dich bereit ist', 'upfront')
						)
					);

					UpFrontAdminInputs::admin_field_generate($form);


					$form = array(
						array(
							'id' 		=> 'google-fonts-preload',
							'type' 	=> 'checkbox',
							'label' => __('Google Schriftarten vorladen', 'upfront'),
							'checkboxes' => array(
								array(
									'id' 		=> 'google-fonts-preload',
									'label' 	=> __('Google Schriftarten vorladen', 'upfront'),
									'checked' 	=> UpFrontOption::get('google-fonts-preload', false, false)
								)
							),
							'description' => __('Wenn diese Option aktiviert ist, weist diese Option den Webbrowser an, Google Schriftarten frühzeitig abzurufen', 'upfront')
						)
					);

					UpFrontAdminInputs::admin_field_generate($form);

					?>
				</div>
			</div>
		</div>




	<div class="hr hr-submit" style="display: none;"></div>

	<p class="submit" style="display: none;">
		<input type="submit" name="upfront-submit" value="<?php _e('Änderungen speichern', 'upfront'); ?>" class="button-primary upfront-save" />
	</p>

</form>
