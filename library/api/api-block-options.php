<?php

class UpFrontBlockOptionsAPI extends UpFrontVisualEditorPanelAPI {


	public $block_type_object;
	public $block 		= false;
	public $block_id 	= false;


	public function __construct( $block_type_object ) {

		/* Accept the block type as an argument that way its properties are available for use in this class */
		$this->block_type_object = $block_type_object;

	}


	public function register() {

		return true;

	}


	public function display($block, $layout) {

		debug($block);
		//Set block properties
		$this->block = $block;

		//Args for modify_arguments and block_content
		$args = array(
			'block' => $this->block,
			'blockID' => $this->block['id'],
			'layoutID' => $this->block['layout'],

			/* Backwards Compatibility */
			'block_id' => $this->block['id']
		);

		//Allow developers to modify the properties of the class and use functions since doing a property 
		//outside of a function will not allow you to.
		$this->modify_arguments($args);

		//Add the standard block tabs
		$this->add_standard_block_config();
		$this->add_standard_block_import_export();

		if ( UpFrontResponsiveGrid::is_enabled() ) {
			$this->add_standard_block_responsive();
		}

		$this->add_developer_tab($args);

		//Display it
		$this->panel_content($args);

	}

	/**
	 * Add developer tab to VE panel
	 *
	 * @param array $args Args to panel tab.
	 * @return void
	 */
	public function add_developer_tab( $args ) {

		if ( ! isset( $this->tabs ) ) {
			$this->tabs = array();
		}

		// Add the developer tab.
		$this->tabs['developer']         = 'SHORTCODE';
		$shortcode_txt                   = '[upfront-block id=\'' . $args['block']['id'] . '\']';
		$this->tab_notices['developer']  = __( '<strong>Verwende diesen Block überall.</strong><p>Verwende diesen Shortcode, um diesen Block in einen Beitrag oder Seite einzufügen:<p>', 'upfront' );
		$this->tab_notices['developer'] .= '<input class="shortcode-anywhere" value="' . $shortcode_txt . '">';

		if ( UpFrontOption::get( 'upfront-blocks-as-gutenberg-blocks' ) ) {
			$this->inputs['anywhere']['show-as-gutenberg-block'] = array(
				'name' => 'show-as-gutenberg-block',
				'type' => 'checkbox',
				'label' => 'Als Gutenberg Block anzeigen',
				'default' => false
			);
		}

		$hooks = array (
			'upfront_before_block' => array(
				'params'      => array( '$block' ),
				'description' => __( 'Bevor der Block geöffnet wird.', 'upfront' ),
			),
			'upfront_before_block_' . $args['block']['id'] => array(
				'params'      => array( '$block' ),
				'description' => __( 'Bevor DIESER Block geöffnet wird.', 'upfront' ),
			),
			'upfront_block_open' => array(
				'params'      => array( '$block' ),
				'description' => __( 'Gleich nach dem Block Offen Tag.', 'upfront' ),
			),
			'upfront_block_open_' . $args['block']['id'] => array(
				'params'      => array( '$block' ),
				'description' => __( 'Direkt nach DIESEM Block Tag öffnen', 'upfront' ),
			),
			'upfront_block_content_open' => array(
				'params'      => array( '$block' ),
				'description' => __( 'Vor dem Inhalt', 'upfront' ),
			),
			'upfront_block_content_open_' . $args['block']['id'] => array(
				'params'      => array( '$block' ),
				'description' => __( 'Vor DIESEM Blockinhalt', 'upfront' ),
			),
			'upfront_block_content_' . $args['block']['type'] => array(
				'params'      => array( '$block' ),
				'description' => __( 'Wenn dieser Blocktyp ausgeführt wird', 'upfront' ),
			),
			'upfront_block_content_close' => array(
				'params'      => array( '$block' ),
				'description' => __( 'Nach Blockinhalt.', 'upfront' ),
			),
			'upfront_block_content_close_' . $args['block']['id'] => array(
				'params'      => array( '$block' ),
				'description' => __( 'Nach DIESEM Blockinhalt', 'upfront' ),
			),
			'upfront_block_close' => array(
				'params'      => array( '$block' ),
				'description' => __( 'Bevor der Block schließt.', 'upfront' ),
			),
			'upfront_block_close_' . $args['block']['id'] => array(
				'params'      => array( '$block' ),
				'description' => __( 'Bevor DIESER Block geschlossen wird.', 'upfront' ),
			),
			'upfront_block_open' => array(
				'params'      => array( '$block' ),
				'description' => __( 'Kurz nach dem Block schließen.', 'upfront' ),
			),
			'upfront_block_open_' . $args['block']['id'] => array(
				'params'      => array( '$block' ),
				'description' => __( 'Kurz nach DIESEM Block schließen', 'upfront' ),
			),
		);

		$hooks = apply_filters( 'upfront_developer_tab_hooks', $hooks );

		$this->tab_notices['developer'] .= '<br><hr><br>';
		foreach ( $hooks as $name => $args ) {

			$example = apply_filters( 'upfront_developer_tab_hook_example', __( 'Bitte installiere das UpFront Shortcodes Plugin, um das Beispiel zu sehen.', 'upfront' ), $name, $args );

			/* translators:  %1$s: Hook name. %2$s: Hook description. */
			$this->tab_notices['developer'] .= '<br>';
			$this->tab_notices['developer'] .= sprintf( __( '<strong>%1$s</strong>: %2$s<pre class="language-php"><code class="language-php">%3$s</code></pre>', 'upfront' ), $name, $args['description'], $example );

		}

	}


	public function add_standard_block_config() {

		if ( !isset($this->tabs) )
			$this->tabs = array();

		if ( !isset($this->inputs) )
			$this->inputs = array();

		//Add the tab
		$this->tabs['config'] = 'Konfiguration';

		/* Add the inputs */

		$this->inputs['config']['mirror-block'] = array(
			'type' => 'select',
			'name' => 'mirror-block',
			'label' => 'Spiegelblock',
			'chosen' => true,
			'default' => '',
			'tooltip' => __('Mit dieser Option kannst Du einem Block anweisen, einen anderen Block und dessen Inhalt zu "spiegeln". Diese Option ist nützlich, wenn Du einen Block&mdash;wie einen Header&mdash;für Layouts auf Deiner Webseite freigeben möchtest. Wähle im Auswahlfeld rechts den Block aus, aus dem Du den Inhalt spiegeln möchtest.', 'upfront'),
			'options' => 'get_blocks_select_options_for_mirroring()',
			'callback' => 'updateBlockMirrorStatus(input, block.id, value);',
			'value' => UpFrontBlocksData::is_block_mirrored($this->block)
		);

		$this->inputs['config']['alias'] = array(
			'type' => 'text',
			'name' => 'alias',
			'label' => 'Block Alias',
			'default' => '',
			'callback' => 'var $block = $i("#block-" + block.id); $block.data("alias", value); updateBlockContentCover($block);',
			'tooltip' => __('Gib einen leicht erkennbaren Namen für den Blockalias ein, der im gesamten Webseiten-Administrator verwendet wird. Wenn Du beispielsweise einem Widget-Bereichsblock einen Alias hinzufügst, wird dieser Alias im Widgets-Bedienfeld verwendet.', 'upfront'),
		);

		$this->inputs['config']['css-classes'] = array(
			'type' => 'text',
			'name' => 'css-classes',
			'callback' => 'updateBlockCustomClasses(input, block.id, value);',
			'label' => 'Benutzerdefinierte CSS-Klasse(n)',
			'default' => '',
			'tooltip' => __('Benötigst Du mehr Kontrolle? Gib hier die benutzerdefinierten CSS-Klassenselektoren ein, die dem Klassenattribut des Blocks hinzugefügt werden. <strong>NICHT</strong> reguläres CSS hier einfügen. Verwende dazu den Live CSS-Editor.', 'upfront'),
		);

		$this->inputs['config']['css-classes-bubble'] = array(
			'type' => 'checkbox',
			'name' => 'css-classes-bubble',
			'label' => '<em style="color: #666; font-style: italic;">Fortgeschrittene:</em> Füge der Zeile/Spalte benutzerdefinierte CSS-Klassen hinzu',
			'default' => '',
			'tooltip' => __('Kopiere alle benutzerdefinierten CSS-Klassen, die diesem Block hinzugefügt wurden, und füge sie der übergeordneten Zeile und Spalte hinzu &lt;section&gt;\'s', 'upfront'),
		);

		/* Titles */		
			if ( isset($this->block_type_object->allow_titles) && $this->block_type_object->allow_titles ) {

				$this->inputs['config']['titles-heading'] = array(
					'name' => 'titles-heading',
					'type' => 'heading',
					'label' => 'Block Titel'
				);

					$this->inputs['config']['block-title'] = array(
						'name' => 'block-title',
						'type' => 'text',
						'label' => 'Block Titel',
						'tooltip' => __('Füge über dem Blockinhalt einen benutzerdefinierten Titel hinzu.', 'upfront')
					);

					$this->inputs['config']['block-title-tag'] = array(
						'name' => 'block-title-tag',
						'type' => 'select',
						'options' => array(
							'h1' => 'H1',
							'h2' => 'H2',
							'h3' => 'H3',
							'h4' => 'H4',
							'h5' => 'H5',
							//'h6' => 'H6',
						),
						'label' => 'Block Titel-Tag',
						'tooltip' => __('Benutzerdefiniertes Titel-Tag.', 'upfront')
					);


					$this->inputs['config']['block-subtitle'] = array(
						'name' => 'block-subtitle',
						'type' => 'text',
						'label' => 'Block Untertitel',
						'tooltip' => __('Füge einen benutzerdefinierten Untertitel über dem Blockinhalt und unter dem Blocktitel hinzu.', 'upfront')
					);


					$this->inputs['config']['block-subtitle-tag'] = array(
						'name' => 'block-subtitle-tag',
						'type' => 'select',
						'options' => array(
							//'h1' => 'H1',
							'h2' => 'H2',
							'h3' => 'H3',
							'h4' => 'H4',
							'h5' => 'H5',
							'h6' => 'H6',
						),
						'label' => 'Block Untertitel-Tag',
						'tooltip' => __('Benutzerdefiniertes Untertitel-Tag.', 'upfront')
					);

					$this->inputs['config']['block-title-link-check'] = array(
						'name' => 'block-title-link-check',
						'type' => 'checkbox',
						'label' => 'Link Block Titel?',
						'tooltip' => __('Wähle aus, ob der Blocktitel ein Link sein soll oder nicht', 'upfront'),
						'default' => false,
						'toggle' => array(
							'true' => array(
								'show' => array(
									'#input-block-title-link-url',
									'#input-block-title-link-target',
									'#input-block-title-link-rel',
								)
							),
							'false' => array(
								'hide' => array(
									'#input-block-title-link-url',
									'#input-block-title-link-target',
									'#input-block-title-link-rel',
								)
							)
						)
					);

					$this->inputs['config']['block-title-link-url'] = array(
						'name' => 'block-title-link-url',
						'type' => 'text',
						'label' => 'Blocktitel-Link-URL',
						'tooltip' => __('Füge eine URL für den Blocktitel hinzu', 'upfront')
					);

					$this->inputs['config']['block-title-link-target'] = array(
						'name' => 'block-title-link-target',
						'type' => 'checkbox',
						'label' => 'In einem neuen Fenster öffnen?',
						'tooltip' => __('Wenn Du den Link in einem neuen Fenster öffnen möchtest, aktiviere diese Option', 'upfront'),
						'default' => false
					);

					$this->inputs['config']['block-title-link-rel'] = array(
						'name' => 'block-title-link-rel',
						'type'	=> 'text',
							'tooltip' => 'Hier kannst Du einen Wert für das rel-Attribut hinzufügen. Beispielwerte: noreferrer, noopener, nofollow, lightbox',
							'default' => 'noreferrer',
					);

			}
		/* End Titles */

	}

	public function add_standard_block_responsive() {

		if ( !isset($this->tabs) )
			$this->tabs = array();

		if ( !isset($this->inputs) )
			$this->inputs = array();

		//Add the tab
		$this->tabs['responsive'] = 'Responsiv Kontrolle';

		/* Add the inputs */
		$this->inputs['responsive']['responsive-options'] = array(
			'type' => 'repeater',
			'name' => 'responsive-options',
			'label' => 'Haltepunkte konfigurieren.',
			'inputs' => array(

				array(
					'type' => 'select',
					'name' => 'blocks-breakpoint',
					'label' => 'Haltepunkt setzen',
					'options' => array(
						'off' => 'Aus - Kein Haltepunkt',
						'custom' => 'Benutzerdefinierte Breite',						
						'1920px' 	=> '1920px - Sehr große Bildschirme',
						'1824px' 	=> '1824px - Große Bildschirme',
						'1224px' 	=> '1224px - Desktop und Laptop',
						'1024px' 	=> '1024px - Populäre Tablet Landscape',
						'812px' 	=> '812px - iPhone X Landscape',
						'768px' 	=> '768px - Populäre Tablet Portrait',
						'736px' 	=> '736px - iPhone 6+ & 7+ & 8+ Landscape',
						'667px' 	=> '667px - iPhone 6 & 7 & 8 & Android Landscape',
						'600px' 	=> '600px - Populärer Haltepunkt in UpFront',
						'568px' 	=> '568px - iPhone 5 Landscape',
						'480px' 	=> '480px - iPhone 3 & 4 Landscape',
						'414px' 	=> '414px - iPhone 6+ & 7+ & 8+ Landscape',
						'375px' 	=> '375px - iPhone 6 & 7 & 8 & X & Android Portrait',
						'320px' 	=> '320px - iPhone 3 & 4 & 5 & Android Portrait',
					),
					'toggle' => array(
						'' => array(
							'hide' => array(
								'.input:not(#input-blocks-breakpoint)'
							)
						),
						'off' => array(
							'hide' => array(
								'.input:not(#input-blocks-breakpoint)'
							)
						),
						'custom' => array(
							'show' => array(
								'.input'
							)
						),						
						'1824px' => array(
							'show' => array(
								'.input:not(#input-max-width)'
							),
							'hide' => array(
								'#input-max-width'
							),
						),
						'1224px' => array(
							'show' => array(
								'.input:not(#input-max-width)'
							),
							'hide' => array(
								'#input-max-width'
							),
						),
						'1024px' => array(
							'show' => array(
								'.input:not(#input-max-width)'
							),
							'hide' => array(
								'#input-max-width'
							),
						),
						'768px' => array(
							'show' => array(
								'.input:not(#input-max-width)'
							),
							'hide' => array(
								'#input-max-width'
							),
						),
						'600px' => array(
							'show' => array(
								'.input:not(#input-max-width)'
							),
							'hide' => array(
								'#input-max-width'
							),
						),
						'568px' => array(
							'show' => array(
								'.input:not(#input-max-width)'
							),
							'hide' => array(
								'#input-max-width'
							),
						),
						'480px' => array(
							'show' => array(
								'.input:not(#input-max-width)'
							),
							'hide' => array(
								'#input-max-width'
							),
						),
						'320px' => array(
							'show' => array(
								'.input:not(#input-max-width)'
							),
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
					'label' => 'Benutzerdefinierte Breite',
					'default' => ''
				),

				array(
					'type' => 'select',
					'name' => 'breakpoint-min-or-max',
					'label' => 'Min oder Max Breite',
					'options' => array(
						'min' => __('Min. Breite (gilt für Bildschirme, die breiter als der Haltepunkt sind)', 'upfront'),
						'max' => __('Max. Breite (gilt für Bildschirme, die schmaler als der Haltepunkt sind)', 'upfront')
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
					'name' => 'disable-block-height',
					'label' => 'Blockhöhe deaktivieren',
					'tooltip'=> __('Deaktiviere die Höhe für kleinere Bildschirme, wenn der Block für kleinere Bildschirme zu hoch angezeigt wird', 'upfront'),
					'default' => false
				),

				array(
					'type' => 'checkbox',
					'name' => 'mobile-center-elements',
					'label' => __('Versuche Blockelemente zu zentrieren', 'upfront'),
					'default' => false
				),

				array(
					'type' => 'checkbox',
					'name' => 'griddify-lists',
					'label' => __('Griddify Listen', 'upfront'),
					'default' => false,
					'tooltip' => __('Jede Art von Liste, wie Kategorien, neueste Beiträge, sogar Menüs usw., funktioniert auf großen Bildschirmen in der Seitenleiste einwandfrei. Aber auf kleineren Bildschirmen, auf denen die Seitenleiste unter den Inhalt fällt. Die Listen können aufgrund der Masse der Leerzeichen leer aussehen. Dadurch werden die Listenelemente nebeneinander in zwei Spalten eingefügt.', 'upfront')
				),

				array(
					'type' => 'checkbox',
					'name' => 'hide-block',
					'label' => __('Verstecke diesen Block', 'upfront'),
					'default' => false,
					'tooltip' => __('Dadurch wird dieser Block für den festgelegten Haltepunkt ausgeblendet.', 'upfront')
				),
				array(
					'name' => 'grid-css-heading',
					'type' => 'heading',
					'label' => __('Gitter-CSS-Optionen', 'upfront')
				),
				array(
					'type' => 'select',
					'name' => 'grid-css-column-count',
					'label' => 'Spaltenanzahl für diesen Haltepunkt',
					'options' => 'get_block_column_count_options()'
				),
				array(
					'type' => 'select',
					'name' => 'grid-css-column-start',
					'label' => 'Startspalte für diesen Haltepunkt',
					'options' => 'get_block_column_start_options()'
				),
			),
			'sortable' => true,
			'limit' => false,
			'callback' => ''
		);


		if ( UpFrontBlocksData::get_block_setting($this->block, 'responsive-block-hiding') ) {

			$this->inputs['responsive']['responsive-block-hiding'] = array(
				'type' => 'multi-select',
				'name' => 'responsive-block-hiding',
				'label' => __('Legacy Responsive Grid Block Hiding', 'upfront'),
				'default' => '',
				'tooltip' => __('Wenn Du das Responsive Gitter aktiviert hast und der Benutzer Deine Webseite auf einem iPhone (oder einem gleichwertigen Gerät) anzeigt, ist das Gitter möglicherweise überfüllt, da sich so viele Blöcke in einem kleinen Bereich befinden. Wenn Du die auf Mobilgeräten angezeigten Blöcke einschränken möchtest, kannst Du mit dieser Einstellung bestimmte Blöcke für die von Dir ausgewählten Geräte ausblenden. <strong>Wenn keine Optionen ausgewählt sind, ist das Ausblenden des reaktionsfähigen Blocks für diesen Block nicht aktiv.</strong>', 'upfront'),
				'options' => array(
					'smartphones' => 'iPhone/Smartphones',
					'tablets-landscape' => 'iPad/Tablets (Landscape)',
					'tablets-portrait' => 'iPad/Tablets (Portrait)',
					'computers' => 'Laptops & Desktops (Nicht empfohlen)'
				)
			);

		}

	}

	public function add_standard_block_import_export() {

		if ( !isset($this->tabs) )
			$this->tabs = array();

		if ( !isset($this->inputs) )
			$this->inputs = array();

		//Add the tab
		$this->tabs['import-export'] = __('Importieren/Exportieren', 'upfront');

		/* Add the inputs */

		$this->inputs['import-export']['import-heading'] = array(
			'name' => 'import-heading',
			'type' => 'heading',
			'label' => __('Blockeinstellungen importieren', 'upfront')
		);

			$this->inputs['import-export']['block-import-settings-file'] = array(
				'type' => 'import-file',
				'name' => 'block-import-settings-file',
				'button-label' => __('Wähle zu importierende Datei', 'upfront'),
				'no-save' => true
			);

			$this->inputs['import-export']['block-import-include-options'] = array(
				'type' => 'checkbox',
				'name' => 'block-import-settings-include-options',
				'label' => __('Blockoptionen einschließen', 'upfront'),
				'default' => true,
				'no-save' => true
			);

			$this->inputs['import-export']['block-import-include-design'] = array(
				'type' => 'checkbox',
				'name' => 'block-import-settings-include-design',
				'label' => __('Blockdesign einschließen', 'upfront'),
				'default' => true,
				'no-save' => true
			);

			$this->inputs['import-export']['block-import-settings'] = array(
				'type' => 'button',
				'name' => 'block-import-settings',
				'button-label' => __('Blockeinstellungen importieren', 'upfront'),
				'no-save' => true,
				'callback' => 'initiateBlockSettingsImport(args);'
			);

		$this->inputs['import-export']['export-heading'] = array(
			'name' => 'export-heading',
			'type' => 'heading',
			'label' => __('Blockeinstellungen exportieren', 'upfront')
		);

			$this->inputs['import-export']['block-export-settings'] = array(
				'type' => 'button',
				'name' => 'block-export-settings',
				'button-label' => __('Exportdatei herunterladen', 'upfront'),
				'no-save' => true,
				'callback' => 'exportBlockSettingsButtonCallback(args);'
			);

	}

	public function get_blocks_select_options_for_mirroring() {

		$block_type = $this->block['type'];	

		$blocks = UpFrontBlocksData::get_blocks_by_type($block_type);

		$options = array('' => '&ndash; '. __('Nicht spiegeln', 'upfront') . ' &ndash;');

		//If there are no blocks, then just return the Nicht spiegeln option.
		if ( !isset($blocks) || !is_array($blocks) )
			return $options;

		foreach ( $blocks as $block_id => $block ) {

			if ( $this->block['id'] == $block_id ) {
				continue;
			}

			//If the block is mirrored, skip it
			if ( UpFrontBlocksData::is_block_mirrored( $block ) ) {
				continue;
			}

			/* Do not show block that's in a mirrored wrapper */
			if ( UpFrontWrappersData::is_wrapper_mirrored( UpFrontWrappersData::get_wrapper( upfront_get( 'wrapper_id', $block ) ) ) ) {
				continue;
			}

			//Create the default name by using the block type and ID
			$default_name = UpFrontBlocks::block_type_nice( $block['type'] ) . ' Block';

			//If we can't get a name for the layout, then things probably aren't looking good.  Just skip this block.
			if ( ! ( $layout_name = UpFrontLayout::get_name( $block['layout'] ) ) ) {
				continue;
			}

			//Make sure the block exists
			if ( ! UpFrontBlocksData::block_exists( $block['id'] ) ) {
				continue;
			}

			$layout_name = UpFrontLayout::get_layout_parents_names( $block['layout'] ) . $layout_name;

			if ( ! isset( $options[ $layout_name ] ) ) {
				$options[ $layout_name ] = array();
			}

			$options[ $layout_name ][ $block['id'] ] = upfront_get( 'alias', $block['settings'], $default_name );

		}

		return $options;

	}

	public function get_block_column_count_options(){


		$column_count 			= array();		
		$wrapper 				= UpFrontWrappersData::get_wrapper( $this->block['wrapper_id'] );
		$max_wrapper_columns 	= $wrapper['settings']['columns'];
		for ( $i = 1; $i <= $max_wrapper_columns; $i++) {
			if ( $i == 1)
				$column_count[ $i ] = $i . ' column';
			else
				$column_count[ $i ] = $i . ' columns';
		}

		return $column_count;

	}

	public function get_block_column_start_options(){


		$column_count 			= array();		
		$wrapper 				= UpFrontWrappersData::get_wrapper( $this->block['wrapper_id'] );
		$max_wrapper_columns 	= $wrapper['settings']['columns'];
		for ( $i = 1; $i <= $max_wrapper_columns; $i++) { 
			$column_count[ $i ] = 'Column ' . $i;
		}

		return $column_count;

	}

}