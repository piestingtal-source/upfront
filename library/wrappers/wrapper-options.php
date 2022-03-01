<?php
class UpFrontWrapperOptions extends UpFrontVisualEditorPanelAPI {



	public $id;
	public $name;
	public $mode;	
	public $tabs;
	public $inputs;

	function __construct(){		

		$this->id = 'wrapper-options';
		$this->name = __('Container Optionen', 'upfront');
		$this->mode = 'grid';

		$this->tabs = array(
			'setup' => __('Gitter &amp; Ränder', 'upfront'),
			'positioning' => __('Sticky Positionierung', 'upfront'),
			'config' => __('Spiegelung &amp; Konfigurieren', 'upfront'),
			'responsive' => __('Responsiv', 'upfront'),
			'import-export' => __('Import/Export', 'upfront'),
		);

		$this->inputs = array(
			'setup' => array(		
				'grid-setup-heading' => array(
					'type' => 'heading',
					'name' => 'grid-setup-heading',
					'label' => __('Gitter', 'upfront')
				),

					'column-count' => array(
						'type' => 'slider',
						'name' => 'columns',
						'label' => __('Spalten', 'upfront'),
						'default' => 24,
						'tooltip' => __('Anzahl der Spalten im Gitter. Vorgeschlagene Werte 9, 12, 16 und 24.<br /><br /><strong>Hinweis:</strong> Der Container muss vor dem Ändern der Spaltenanzahl leer sein. Verschiebe die Blöcke entweder in einen anderen Container oder lösche sie, wenn sie nicht benötigt werden.', 'upfront'),
						'slider-min' => 6,
						'slider-max' => 24,
						'slider-interval' => 1,
						'callback' => 'wrapperOptionCallbackColumnCount(input, value);'
					),

					'use-independent-grid' => array(
						'type' => 'checkbox',
						'name' => 'use-independent-grid',
						'label' => __('Verwende Unabhängiges Gitter', 'upfront'),
						'tooltip' => __('Aktiviere diese Option, wenn dieser Container andere Rastereinstellungen als die globalen Rastereinstellungen haben soll.', 'upfront'),
						'callback' => 'wrapperOptionCallbackIndependentGrid(input, value);',
						'toggle' => array(
							'true' => array(
								'show' => array(
									'#input-column-width',
									'#input-gutter-width',
									'#input-grid-width'
								)
							),
							'false' => array(
								'hide' => array(
									'#input-column-width',
									'#input-gutter-width',
									'#input-grid-width'
								)
							)
						)
					),

					'column-width' => array(
						'type' => 'slider',
						'name' => 'column-width',
						'label' => __('Spaltenbreite', 'upfront'),
						'default' => 26,
						'tooltip' => __('Die Spaltenbreite ist der Platz innerhalb jeder Spalte. Dies wird durch die grauen Bereiche im Gitter dargestellt.', 'upfront'),
						'unit' => 'px',
						'slider-min' => 10,
						'slider-max' => 120,
						'slider-interval' => 1,
						'callback' => 'wrapperOptionCallbackColumnWidth(input, value);'
					),

					'gutter-width' => array(
						'type' => 'slider',
						'name' => 'gutter-width',
						'label' => __('Rinnenbreite', 'upfront'),
						'default' => 22,
						'tooltip' => __('Die Rinnenbreite ist der Abstand zwischen den einzelnen Spalten. Dies ist der Abstand zwischen den einzelnen grauen Bereichen im Gitter.', 'upfront'),
						'unit' => 'px',
						'slider-min' => 0,
						'slider-max' => 60,
						'slider-interval' => 1,
						'callback' => 'wrapperOptionCallbackGutterWidth(input, value);'
					),

					'grid-width' => array(
						'type' => 'integer',
						'unit' => 'px',
						'default' => 1130,
						'name' => 'grid-width',
						'label' => __('Rasterbreite', 'upfront'),
						'readonly' => true
					),

				'wrapper-margins-heading' => array(
					'type' => 'heading',
					'name' => 'wrapper-margins-heading',
					'label' => __('Container-Ränder', 'upfront')
				),

					'wrapper-margin-top' => array(
						'type' => 'slider',
						'name' => 'wrapper-margin-top',
						'label' => __('Oberer Rand', 'upfront'),
						'default' => 30,
						'tooltip' => __('Platz zwischen dem oberen Rand dieses Wrappers und dem oberen Rand der Seite oder dem Container darüber.', 'upfront'),
						'unit' => 'px',
						'slider-min' => 0,
						'slider-max' => 200,
						'slider-interval' => 1,
						'callback' => 'wrapperOptionCallbackMarginTop(input, value);',
						'data-handler-callback' => 'dataSetDesignEditorProperty({
							element: "wrapper", 
							property: "margin-top", 
							value: args.value.toString(), 
							specialElementType: "instance", 
							specialElementMeta: "wrapper-" + args.wrapper.id
						});'
					),

					'wrapper-margin-bottom' => array(
						'type' => 'slider',
						'name' => 'wrapper-margin-bottom',
						'label' => __('Unterer Rand', 'upfront'),
						'default' => 0,
						'tooltip' => __('Platz zwischen diesem Container und dem unteren Rand der Seite.', 'upfront'),
						'unit' => 'px',
						'slider-min' => 0,
						'slider-max' => 200,
						'slider-interval' => 1,
						'callback' => 'wrapperOptionCallbackMarginBottom(input, value);',
						'data-handler-callback' => 'dataSetDesignEditorProperty({
							element: "wrapper", 
							property: "margin-bottom", 
							value: args.value.toString(), 
							specialElementType: "instance", 
							specialElementMeta: "wrapper-" + args.wrapper.id
						});'
					)
			),

			'positioning' => array(
				'enable-sticky-positioning' => array(
					'type' => 'checkbox',
					'name' => 'enable-sticky-positioning',
					'label' => __('Aktiviere die Sticky-Positionierung', 'upfront'),
					'default' => false,
					'tooltip' => '',
					'toggle' => array(
						'true' => array(
							'show' => array(
								'#input-sticky-position-top-offset',
								'#input-enable-shrink-on-scroll'
							)
						),
						'false' => array(
							'hide' => array(
								'#input-sticky-position-top-offset',
								'#input-enable-shrink-on-scroll'
							)
						)
					)
				),

				'sticky-position-top-offset' => array(
					'type' => 'slider',
					'name' => 'sticky-position-top-offset',
					'label' => __('Top Offset', 'upfront'),
					'slider-min' => 0,
					'slider-max' => 200,
					'slider-interval' => 1,
					'unit' => 'px',
					'default' => '0'
				),


				'enable-shrink-on-scroll' => array(
					'type' => 'checkbox',
					'name' => 'enable-shrink-on-scroll',
					'label' => __('Aktiviere Schrumpfen beim Scrollen', 'upfront'),
					'default' => false,
					'tooltip' => '',
					'toggle' => array(
						'true' => array(
							'show' => array(
								'#input-shrink-on-scroll-ratio',
								'#input-shrink-contained-elements',
								'#input-shrink-contained-images'
							),
						),
						'false' => array(
							'hide' => array(
								'#input-shrink-on-scroll-ratio',
								'#input-shrink-contained-elements',
								'#input-shrink-contained-images',
							),
						)
					)
				),

				'shrink-on-scroll-ratio' => array(
					'type' => 'slider',
					'name' => 'shrink-on-scroll-ratio',
					'label' => __('Schrumpfungsverhältnis', 'upfront'),
					'slider-min' => 0,
					'slider-max' => 100,
					'slider-interval' => 1,
					'unit' => '%',
					'default' => '50'
				),

				'shrink-contained-images' => array(
					'type' => 'checkbox',
					'name' => 'shrink-contained-images',
					'label' => __('Bilder verkleinern', 'upfront'),
					'tooltip' => __('Versuche enthaltene Bilder zu verkleinern', 'upfront'),
					'default' => true,
				),

				'shrink-contained-elements' => array(
					'type' => 'checkbox',
					'name' => 'shrink-contained-elements',
					'label' => __('Versuch mit untergeordneten Elementen', 'upfront'),
					'tooltip' => __('Versuche enthaltene Elemente zu verkleinern', 'upfront'),
					'default' => false,
				),
			),

			'config' => array(
				'mirror-wrapper' => array(
					'type' => 'select',
					'chosen' => true,
					'name' => 'mirror-wrapper',
					'label' => __('Spiegelblöcke von einem anderen Container', 'upfront'),
					'default' => '',
					'tooltip' => __('Mit dieser Option kannst Du einem Container anweisen, einen anderen Container und alle seine Blöcke zu "spiegeln".  Diese Option ist nützlich, wenn Du einen Container&mdash;wie einen Header&mdash;für Layouts auf Deiner Webseite freigeben möchtest. Wähle im Auswahlfeld rechts den Container aus, aus dem Du den Inhalt spiegeln möchtest.', 'upfront'),
					'options' => 'get_wrappers_select_options_for_mirroring()',
					'callback' => 'updateWrapperMirrorStatus(args.wrapper.id, value, input);'
				),

				'do-not-mirror-wrapper-styles' => array(
					'type' => 'checkbox',
					'chosen' => false,
					'name' => 'do-not-mirror-wrapper-styles',
					'label' => __('Stile nicht spiegeln', 'upfront'),
					'default' => '',
					'tooltip' => __('Verwende diese Option, um die Spiegelung von Stilen zu verhindern', 'upfront')
				),

				'alias' => array(
					'type' => 'text',
					'name' => 'alias',
					'label' => __('Container Alias', 'upfront'),
					'default' => '',
					'tooltip' => __('Gib einen leicht erkennbaren Namen für den Container-Alias ein, der im gesamten Seiten-Administrator verwendet wird. Aliase werden im Design-Editor und im Spiegelungsmenü verwendet und sind eine hervorragende Möglichkeit, einen bestimmten Container im Auge zu behalten.', 'upfront')
				),

				'css-classes' => array(
					'type' => 'text',
					'name' => 'css-classes',
					'callback' => 'updateWrapperCustomClasses(args.wrapper.id, value);',
					'label' => __('Benutzerdefinierte CSS-Klasse(n)', 'upfront'),
					'default' => '',
					'tooltip' => __('Benötigst Du mehr Kontrolle? Gib hier die benutzerdefinierten CSS-Klassenselektoren ein, die dem Klassenattribut der Container hinzugefügt werden. <strong>NICHT</strong> reguläres CSS hier einfügen. Verwende dazu den Live CSS-Editor.', 'upfront')
				)
			),

			'responsive' => array(

				array(
					'type' => 'repeater',
					'name' => 'responsive-wrapper-options',
					'label' => __('Haltepunkte konfigurieren.', 'upfront'),
					'inputs' => array(

						array(
							'type' => 'select',
							'name' => 'breakpoint',
							'label' => __('Haltepunkt setzen', 'upfront'),
							'options' => array(
								'off' => __('Aus - Kein Haltepunkt', 'upfront'),
								'custom' 	=> __('Benutzerdefinierte Breite', 'upfront'),
								'1920px' 	=> __('1920px - Sehr große Bildschirme', 'upfront'),
								'1824px' 	=> __('1824px - Großbildschirme', 'upfront'),
								'1224px' 	=> __('1224px - Desktop und Laptop', 'upfront'),
								'1024px' 	=> __('1024px - Beliebte Tablet-Landschaft', 'upfront'),
								'812px' 	=> __('812px - iPhone X Landschaft', 'upfront'),
								'768px' 	=> __('768px - Beliebtes Tablet-Porträt', 'upfront'),
								'736px' 	=> __('736px - iPhone 6+ & 7+ & 8+ Landschaft', 'upfront'),
								'667px' 	=> __('667px - iPhone 6 & 7 & 8 & Android Landschaft', 'upfront'),
								'600px' 	=> __('600px - Beliebter Haltepunkt in UpFront', 'upfront'),
								'568px' 	=> __('568px - iPhone 5 Landschaft', 'upfront'),
								'480px' 	=> __('480px - iPhone 3 & 4 Landschaft', 'upfront'),
								'414px' 	=> __('414px - iPhone 6+ & 7+ & 8+ Landschaft', 'upfront'),
								'375px' 	=> __('375px - iPhone 6 & 7 & 8 & X & Android Porträt', 'upfront'),
								'320px' 	=> __('320px - iPhone 3 & 4 & 5 & Android Porträt', 'upfront'),
							),
							'toggle'    => array(
								'off' => array(
									'hide' => array(
										'#input-max-width'
									),
								),
								'custom' => array(
									'show' => array(
										'#input-max-width'
									),
								),
								'1920px' => array(
									'hide' => array(
										'#input-max-width'
									),
								),
								'1824px' => array(
									'hide' => array(
										'#input-max-width'
									),
								),
								'1224px' => array(
									'hide' => array(
										'#input-max-width'
									),
								),
								'1024px' => array(
									'hide' => array(
										'#input-max-width'
									),
								),
								'812px' => array(
									'hide' => array(
										'#input-max-width'
									),
								),
								'768px' => array(
									'hide' => array(
										'#input-max-width'
									),
								),
								'736px' => array(
									'hide' => array(
										'#input-max-width'
									),
								),
								'600px' => array(
									'hide' => array(
										'#input-max-width'
									),
								),
								'568px' => array(
									'hide' => array(
										'#input-max-width'
									),
								),
								'480px' => array(
									'hide' => array(
										'#input-max-width'
									),
								),
								'414px' => array(
									'hide' => array(
										'#input-max-width'
									),
								),
								'375px' => array(
									'hide' => array(
										'#input-max-width'
									),
								),
								'320px' => array(
									'hide' => array(
										'#input-max-width'
									),
								)
							),
							'tooltip' => __('Wähle eine Bildschirmbreite aus, damit diese Änderungen wirksam werden.', 'upfront'),
							'default' => ''
						),

						array(
							'type' => 'text',
							'name' => 'max-width',
							'label' => __('Benutzerdefinierte Breite', 'upfront'),
							'tooltip' => __('Füge auch den px-Wert hinzu. ZB: 600px', 'upfront')
						),

						array(
							'type' => 'select',
							'name' => 'breakpoint-min-or-max',
							'label' => __('Min oder Max Breite', 'upfront'),
							'options' => array(
								'min' => __('Min. Breite (gilt für Bildschirme, die breiter als der Haltepunkt sind)', 'upfront'),
								'max' => __('Maximale Breite (gilt für Bildschirme, die schmaler als der Haltepunkt sind)', 'upfront')
							),
							'default' => 'max'
						),

						array(
							'name' => 'adaptive-heading',
							'type' => 'heading',
							'label' => __('Adaptive Optionen', 'upfront')
						),

						array(
							'type' => 'checkbox',
							'name' => 'stretch',
							'label' => __('Stretchblöcke für Handys', 'upfront'),
							'default' => false,
							'tooltip' => __('Aktiviere diese Option, damit alle Blöcke in diesem Container auf kleineren Bildschirmen die gesamte Wrapperbreite dehnen. Nebeneinander angeordnete Blöcke sehen auf kleineren Bildschirmen möglicherweise nicht gut aus, da sie um den horizontalen Raum kämpfen. Wenn Du diese Option einstellst, wird jeder Block am eingestellten Haltepunkt in voller Breite ausgeführt.', 'upfront')
						),

						array(
							'type' => 'checkbox',
							'name' => 'auto-center',
							'label' => __('Versuche Elemente zu zentrieren', 'upfront'),
							'default' => false,
							'tooltip' => __('Dadurch wird versucht, die Elemente in diesem Block am festgelegten Haltepunkt zu zentrieren. HINWEIS: This will not work for all elements but give it a try and if it works for you then great. Für komplexere HTML-ähnliche Menüs ist benutzerdefinierter Code erforderlich.', 'upfront')
						),

						array(
							'type' => 'checkbox',
							'name' => 'hide-wrapper',
							'label' => __('Verstecke diesen Container', 'upfront'),
							'default' => false,
							'tooltip' => __('Dadurch wird dieser Container für den festgelegten Haltepunkt ausgeblendet.', 'upfront')
						)

					),
					'sortable' => true,
					'limit' => false,
					'callback' => ''
				)

			),

			/**
			 *
			 * Import / Export Wrappers
			 *
			 */
			'import-export' => array(
				'import-heading' => array(
					'name' => 'import-heading',
					'type' => 'heading',
					'label' => __('Container-Einstellungen importieren', 'upfront')
				),

				'wrapper-import-settings-file' => array(
					'type' => 'import-file',
					'name' => 'wrapper-import-settings-file',
					'button-label' => __('Wähle zu importierende Datei', 'upfront'),
					'no-save' => true
				),

				/*
				'wrapper-import-include-options' => array(
					'type' => 'checkbox',
					'name' => 'wrapper-import-settings-include-options',
					'label' => 'Include Container Options',
					'default' => true,
					'no-save' => true
				),
				'wrapper-import-include-design' => array(
					'type' => 'checkbox',
					'name' => 'wrapper-import-settings-include-design',
					'label' => 'Include Container Design',
					'default' => true,
					'no-save' => true
				),*/

				'wrapper-import-settings' => array(
					'type' => 'button',
					'name' => 'wrapper-import-settings',
					'button-label' => __('Container-Einstellungen importieren', 'upfront'),
					'no-save' => true,
					'callback' => 'initiateWrapperSettingsImport(args);'
				),

				'export-heading' => array(
					'name' => 'export-heading',
					'type' => 'heading',
					'label' => __('Container-Einstellungen exportieren', 'upfront')
				),

				'wrapper-export-settings' => array(
					'type' => 'button',
					'name' => 'wrapper-export-settings',
					'button-label' => __('Exportdatei herunterladen', 'upfront'),
					'no-save' => true,
					'callback' => 'exportWrapperSettingsButtonCallback(args);'
				)
			)

		);
	}


	public function register() {

		return true;

	}


	public function display($wrapper, $layout) {

		//Blockeigenschaften festlegen
		$this->wrapper = $wrapper;

		//Argumente einrichten
		$args = array(
			'wrapper' => $this->wrapper,
			'layoutID' => $layout
		);

		//Get und Display Panel
		$this->modify_arguments($args);
		$this->panel_content($args);

	}

	function modify_arguments($args = false) {

		/* Die Registerkarte Container-Setup wird im Entwurfsmodus nicht angezeigt */
		if ( upfront_post('mode') == 'design')  {

			unset($this->tabs['setup']);
			unset($this->inputs['setup']);

			return;

		}

		/* Standardeinstellungen für Rastereinstellungen */
			$this->inputs['setup']['column-width']['default'] = UpFrontWrappers::$default_column_width; 
			$this->inputs['setup']['gutter-width']['default'] = UpFrontWrappers::$default_gutter_width; 
		/* Ende Standardeinstellungen für Rastereinstellungen */

		/* Ränder */
			$wrapper_instance_id = 'wrapper-' . $args['wrapper']['id'];

			$this->inputs['setup']['wrapper-margin-top']['value'] = UpFrontElementsData::get_special_element_property('wrapper', 'instance', $wrapper_instance_id, 'margin-top', UpFrontWrappers::$default_wrapper_margin_top, 'structure'); 
			$this->inputs['setup']['wrapper-margin-bottom']['value'] = UpFrontElementsData::get_special_element_property('wrapper', 'instance', $wrapper_instance_id, 'margin-bottom', UpFrontWrappers::$default_wrapper_margin_bottom, 'structure'); 
		/* Ränder beenden */

		/* Container Spiegelwert */
		$this->inputs['config']['mirror-wrapper']['value'] = UpFrontWrappersData::is_wrapper_mirrored($args['wrapper']);

	}


	public function get_wrappers_select_options_for_mirroring() {

		$wrappers 	= UpFrontWrappersData::get_all_wrappers();
		$options 	= array('' => '&ndash; Nicht spiegeln &ndash;');

		//Wenn keine zu spiegelnden Container vorhanden sind, gib einfach die Option Nicht spiegeln zurück.
		if ( empty($wrappers) || !is_array($wrappers) )
			return $options;

		foreach ( $wrappers as $wrapper_id => $wrapper ) {

			/* Wenn wir keinen Namen für das Layout bekommen können, sehen die Dinge wahrscheinlich nicht gut aus. Überspringe einfach diesen Container. */
			if ( !($layout_name = UpFrontLayout::get_name($wrapper['layout'])) )
				continue;

			/* Überprüft hier die Spiegelung */
			if ( UpFrontWrappersData::is_wrapper_mirrored($wrapper) )
				continue;

			if ( isset($this->wrapper['id']) && $this->wrapper['id'] && $wrapper_id == $this->wrapper['id'] )
				continue;

			$current_layout_suffix = ( $this->wrapper['layout'] == $wrapper['layout'] ) ? ' (Warnung: Gleiches Layout)' : null;
			$wrapper_alias = upfront_get('alias', $wrapper['settings']) ? ' &ndash; ' . upfront_get('alias', $wrapper['settings']) : null;

			/* Erstellt Informationen, die anzeigen, ob der Container fest oder flüssig ist, da ein Container möglicherweise keinen Alias hat. Dies kann verwirrend sein, wenn nur immer wieder "Container - Some Layout" angezeigt wird */
			$wrapper_info = array();

			if ( upfront_fix_data_type($wrapper['settings']['fluid']) )
				$wrapper_info[] = 'Flüssig';

			if ( upfront_fix_data_type($wrapper['settings']['fluid-grid']) )
				$wrapper_info[] = 'Flüssiger Gitter';

			$wrapper_info_str = $wrapper_info ? ' &ndash; (' . implode( ', ', $wrapper_info ) . ')' : '';

			if ( ! isset( $options[ $layout_name ] ) ) {
				$options[ $layout_name ] = array();
			}

			//Ruft den Alias ab, falls vorhanden, andernfalls verwende den Standardnamen
			$options[$layout_name][$wrapper_id] = 'Container' . $wrapper_alias . $wrapper_info_str  . $current_layout_suffix;

		}

		//Entfernt den aktuellen Container aus der Liste
		if ( isset($this->wrapper['id']) && $this->wrapper['id'] )
			unset($options[$this->wrapper['id']]);

		return $options;

	}


}