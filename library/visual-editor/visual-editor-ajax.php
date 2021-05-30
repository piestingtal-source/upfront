<?php
class UpFrontVisualEditorAJAX {


	private static function json_encode($data) {

		header('content-type:application/json');

		if ( upfront_get('callback') )
			echo upfront_get('callback') . '(';

		echo json_encode($data);

		if ( upfront_get('callback') )
			echo ')';

	}


	/* Skin Methoden */
	public static function secure_method_switch_skin() {

		global $wpdb;

		if ( UpFrontTemplates::get(upfront_post('skin')) && UpFrontOption::set('current-skin', upfront_post('skin')) ) {

			do_action('upfront_switch_skin');

			UpFront::set_autoload( upfront_post( 'skin' ) );

			echo 'success';

		}

	}

	public static function secure_method_delete_skin() {

		global $wpdb;

		$skin_to_delete = upfront_post('skin');

		if ( $skin_to_delete == UpFrontOption::get('current-skin') || $skin_to_delete == 'base' ) {
			echo 'Fehler: Aktuelle Vorlage kann nicht gelöscht werden';
			return;
		}

		/* Durchlaufe die WordPress-Optionen und lösche die Skin-Optionen */
			$wpdb->query($wpdb->prepare( "DELETE FROM $wpdb->options WHERE option_name LIKE '%s'", 'pu_|template=' . upfront_post( 'skin' ) . '|%' ));

			UpFrontLayoutOption::delete_by_template($skin_to_delete);

		/* Löscht Blöcke und Wrapper */
			UpFrontBlocksData::delete_by_template($skin_to_delete);
			UpFrontWrappersData::delete_by_template($skin_to_delete);

		/* Snapshots löschen */
			UpFrontDataSnapshots::delete_by_template($skin_to_delete);

		/* Entferne den Skin aus dem UpFront-Skins-Katalog */
			UpFrontOption::delete($skin_to_delete, 'skins');

		echo 'Erfolgreich';

	}

	public static function secure_method_add_blank_skin() {

		$blank_skin_name = upfront_post('skinName');

		if ( empty($blank_skin_name) )
			return;

		$original_skin_id = substr(strtolower(str_replace(' ', '-', $blank_skin_name)), 0, 12);

		$skin_id = $original_skin_id;
		$skin_name = $blank_skin_name;

		$skin_unique_id_counter = 0;

		/* Check if skin already exists.  If it does, change ID and skin name */
			while ( UpFrontOption::get($skin_id, 'skins') ) {

				$skin_unique_id_counter++;
				$skin_id = $original_skin_id . '-' . $skin_unique_id_counter;
				$skin_name = $blank_skin_name . ' ' . $skin_unique_id_counter;

			}

			$skin['id'] = $skin_id;
			$skin['name'] = $skin_name;

		/* Send skin to DB */
			UpFrontOption::set($skin['id'], $skin, 'skins');

		self::json_encode($skin);

	}


	/* Snapshot methods */
	public static function secure_method_save_snapshot() {

		self::json_encode(UpFrontDataSnapshots::save_snapshot());

	}

	public static function secure_method_rollback_to_snapshot() {

		self::json_encode(UpFrontDataSnapshots::rollback(upfront_post('snapshot_id')));

	}

	public static function secure_method_delete_snapshot() {

		self::json_encode( UpFrontDataSnapshots::delete( upfront_post( 'snapshot_id' ) ) );

	}


	/* Saving methods */
	public static function secure_method_save_options() {

		$options_json = upfront_post( 'options' );

		
		if ( function_exists('wp_magic_quotes') ) {
			$options_json = stripslashes( upfront_post( 'options' ) );
		}			
		

		$options = json_decode($options_json, ARRAY_A);

		self::json_encode(UpFrontVisualEditor::save($options));

	}


	/* Layout Selector */
	public static function method_get_layout_children() {

		UpFront::load('visual-editor/layout-selector');

		self::json_encode(UpFrontLayoutSelector::get_layout_children(upfront_post('layout'), upfront_post('offset')));

	}


	public static function method_query_layouts() {

		UpFront::load( 'visual-editor/layout-selector' );
		self::json_encode( UpFrontLayoutSelector::query_layouts( upfront_post( 'query' ) ) );

	}


	public static function method_query_posts() {

		$post_type = explode('||', upfront_post( 'content' ));

		$query = new WP_Query( array( 's' => upfront_post( 'query' ) ) );
		$posts = array();
		foreach ($query->posts as $key => $post) {
			$posts[$post->ID] = array(
				'id' => $post->ID,
				'post_title' => $post->post_title,
			);
		};	
		self::json_encode($posts);
		return $query->posts;

	}


	public static function method_get_content_children() {

		$post_type = explode('||', upfront_post( 'content' ))[1];		
		$query = new WP_Query( array( 'post_type' => $post_type ) );
		$posts = array();
		foreach ($query->posts as $key => $post) {
			$posts[$post->ID] = array(
				'id' => $post->ID,
				'name' => $post->post_title,
				'url' => get_post_permalink($post->ID),
			);
		};
		self::json_encode($posts);

	}


	/* Block Methoden */
	public static function method_get_layout_blocks_in_json() {

		$layout = upfront_post('layout', false);
		$layout_status = UpFrontLayout::get_status($layout);

		if ( $layout_status['customized'] != true )
			return false;

		self::json_encode(array(
			'blocks' => UpFrontBlocksData::get_blocks_by_layout($layout, false, true),
			'wrappers' => UpFrontWrappersData::get_wrappers_by_layout($layout, true)
		));

	}


	public static function method_load_block_content() {

		/* Überprüfe den sicheren Gitter Modus */
			if ( UpFrontOption::get('grid-safe-mode', false, false) ) {

				echo '<div class="alert alert-red block-safe-mode"><p>' . __('Gitter Safe-Modus aktiviert. Blockinhalt nicht ausgegeben.', 'upfront') . '</p></div>';

				return;

			}

		/* Los */
		$layout = upfront_post('layout');
		$block_origin = upfront_post('block_origin');
		$block_default = upfront_post('block_default', false);

		$unsaved_block_settings = upfront_post('unsaved_block_settings', false);

		/* Wenn der Blockursprung eine Zeichenfolge oder ID ist, rufe das Objekt aus der Datenbank ab. */
		if ( is_numeric($block_origin) || is_string($block_origin) )
			$block = UpFrontBlocksData::get_block($block_origin);

		/* Andernfalls verwende das Objekt */
		else
			$block = $block_origin;

		/* Wenn der Block nicht vorhanden ist, verwende die Standardeinstellung als Ursprung. Wenn die Standardeinstellung nicht existiert ... Wir sind am Arsch. */
		if ( !$block && $block_default )
			$block = $block_default;

		/* Wenn es sich bei den Blockeinstellungen um ein Array handelt, führe diese mit dem Ursprung zusammen. Stelle jedoch zunächst sicher, dass die Einstellungen für den Ursprung vorhanden sind. */
		if ( !isset($block['settings']) )
			$block['settings'] = array();

		if ( is_array($unsaved_block_settings) && count($unsaved_block_settings) && isset($unsaved_block_settings['settings']) ) {

			$block = upfront_array_merge_recursive_simple($block, $unsaved_block_settings);

		}

		/* Wenn der Block auf Spiegeln eingestellt ist, hole diesen Block. */
		if ( $mirrored_block = UpFrontBlocksData::get_block_mirror($block) ) {

			$original_block = $block;

			$block = $mirrored_block;
			$block['original'] = $original_block;

		}

		/* Füge dem Block ein Flag hinzu, damit wir überprüfen können, ob dies vom visuellen Editor stammt. */
		$block['ve-live-content-query'] = true;

		/* Zeige den Inhalt */
		do_action('upfront_block_content_' . $block['type'], $block);

		/* Dynamisches JS und CSS ausgeben */
			if ( upfront_post('mode') != 'grid' ) {

				$block_types = UpFrontBlocks::get_block_types();

				/* Dynamisches CSS */
					if ( method_exists($block_types[$block['type']]['class'], 'dynamic_css') ) {

						echo '<style type="text/css">';
							echo call_user_func(array($block_types[$block['type']]['class'], 'dynamic_css'), $block['id'], $block);
						echo '</style><!-- AJAX Block Content Dynamic CSS -->';

					}

				/* Führe die Enqueue-Aktion aus und drucke sofort */
					if ( method_exists($block_types[$block['type']]['class'], 'enqueue_action') ) {

						/* Entferne alle anderen in die Warteschlange gestellten Skripte, um Konflikte zu reduzieren */
							global $wp_scripts;
							$wp_scripts = null;
							remove_all_actions('wp_print_scripts');

						/* Entferne alle anderen in die Warteschlange gestellten Stile, um Konflikte zu reduzieren */
							global $wp_styles;
							$wp_styles = null;
							remove_all_actions('wp_print_styles');

						echo call_user_func(array($block_types[$block['type']]['class'], 'enqueue_action'), $block['id'], $block);
						wp_print_scripts();
						wp_print_footer_scripts(); /* Dies wird nicht wirklich benötigt, aber es ist hier für N3rd-Power */

					}

				/* Dynamisches JS ausgeben */
					if ( method_exists($block_types[$block['type']]['class'], 'dynamic_js') ) {

						echo '<script type="text/javascript">';
							echo call_user_func(array($block_types[$block['type']]['class'], 'dynamic_js'), $block['id'], $block);
						echo '</script><!-- AJAX Block Content Dynamic JS -->';

					}

			}
		/* Beendet die Ausgabe von dynamischem JS und CSS */

	}


	public static function method_load_block_editable_field_content() {

		/* Los */
		$block_id = upfront_post('block_id');
		$field = upfront_post('field');
		$block = UpFrontBlocksData::get_block($block_id);
		$block_types = UpFrontBlocks::get_block_types();
		$block_type_settings = upfront_get($block['type'], $block_types, array());
		$editable_fields = upfront_get('inline-editable', $block_type_settings);

		foreach (explode(',', $editable_fields) as $key => $value) {
			if($value == $field){
				echo $block['settings'][$field];
			}
		}
	
	}


	public static function method_save_block_editable_field_content() {

		/* Los */
		$block_id = upfront_post('block_id');
		$field = upfront_post('field');
		$content = upfront_post('content');

		// Blockdaten laden
		$block = UpFrontBlocksData::get_block($block_id);
		$block_types = UpFrontBlocks::get_block_types();
		$block_type_settings = upfront_get($block['type'], $block_types, array());
		// Hole welche Blockeinstellungen Inline bearbeiten können
		$editable_fields = explode(',', upfront_get('inline-editable', $block_type_settings));

		// Ist bearbeitbares Feld?
		if( ! in_array($field, $editable_fields))
			return;


		// Ist das Feld in das Äquivalenzarray von class => field
		if( $block_type_settings['inline-editable-equivalences'][$field] )
			$field = $block_type_settings['inline-editable-equivalences'][$field];


		// Einstellung ersetzen
		$block['settings'][$field] = $content;

		// Speichere neue Blockdaten in der Datenbank
		UpFrontBlocksData::update_block($block_id, $block);

		// Blockdaten neu laden
		$block = UpFrontBlocksData::get_block($block_id);

		// Echo Einstellung
		echo $block['settings'][$field];

	}


	public static function method_save_block_animation_rules() {

		/* Los */
		$block_id = upfront_post('block_id');
		$selector = upfront_post('selector');
		$rule = upfront_post('rule');


		if( empty($selector) || empty($rule) )
			return;

		// Blockdaten laden
		$block = UpFrontBlocksData::get_block($block_id);
		
		// Füge Animationsregeln hinzu
		if( $rule === 'initial' ){
			$block['settings']['animation-rules'][$selector] = 'running';
		}else{
			$block['settings']['animation-rules'][$selector] = $rule;
		}

		// Speichere neue Blockdaten in der Datenbank
		return UpFrontBlocksData::update_block($block_id, $block);

	}


	public static function method_load_block_options() {

		$layout = upfront_post('layout');
		$block_id = upfront_post('block_id');
		$unsaved_options = upfront_post('unsaved_block_options', array());

		if ( upfront_post('duplicate_of') ) {
			$block = UpFrontBlocksData::get_block(upfront_post('duplicate_of'));
			$block['id'] = $block_id;
		} else {
			$block = UpFrontBlocksData::get_block($block_id);
		}

		//Wenn der Block neu ist, richte die Grundlagen ein
		if ( !$block ) {

			$block = array(
				'type' => upfront_post('block_type'),
				'new' => true,
				'id' => $block_id,
				'layout' => $layout
			);

		}


		/* Nicht gespeicherte Optionen in zusammenführen */
		if ( is_array($unsaved_options) )
			$block['settings'] = is_array(upfront_get('settings', $block)) ? array_merge($block['settings'], $unsaved_options) : $unsaved_options;

		do_action('upfront_block_options_' . $block['type'], $block, $layout);

	}


	/* Wrapper-Methoden */
	public static function method_load_wrapper_options() {

		$layout_id = upfront_post('layout');
		$wrapper_id = upfront_post('wrapper_id');
		$unsaved_options = upfront_post('unsaved_wrapper_options', array());

		$wrapper = UpFrontWrappersData::get_wrapper($wrapper_id);

		if ( !$wrapper ) {

			$wrapper = array(
				'id' => $wrapper_id,
				'layout' => $layout_id,
				'new' => true
			);

		}

		/* Nicht gespeicherte Optionen in zusammenführen */
			if ( is_array($unsaved_options) )
				$wrapper = array_merge($wrapper, $unsaved_options);

		do_action('upfront_wrapper_options', $wrapper, $layout_id);

	}


	/* Box-Methoden */
	public static function method_load_box_ajax_content() {

		$layout = upfront_post('layout');
		$box_id = upfront_post('box_id');

		do_action('upfront_visual_editor_ajax_box_content_' . $box_id);

	}


	/* Layoutmethoden */
	public static function method_get_layout_name() {

		$layout = upfront_post('layout');

		echo UpFrontLayout::get_name($layout);

	}


	public static function secure_method_revert_layout() {

		$layout = upfront_post('layout_to_revert');

		//Lösche Wrapper, Blöcke und Designeinstellungen
		UpFrontLayout::delete_layout($layout);

		do_action('upfront_visual_editor_reset_layout');

		echo 'Erfogreich';

	}


	/* Entwurfseditor-Methoden */
	public static function method_get_element_inputs() {

		$element = upfront_post('element');
		$special_element_type = upfront_post('specialElementType', false);
		$special_element_meta = upfront_post('specialElementMeta', false);
		$group = $element['group'];

		$unsaved_values = upfront_post('unsavedValues', false);

		/* Stelle sicher, dass die Bibliothek geladen ist */
		UpFront::load('visual-editor/panels/design/property-inputs');

		/* Werte abrufen */
			if ( !$special_element_type && !$special_element_meta ) {

				$property_values = UpFrontElementsData::get_element_properties($element['id']);
				$property_values_excluding_defaults = UpFrontElementsData::get_element_properties($element['id'], true);

			} else {

				$property_values_args = array(
					'element' => $element['id'],
					'se_type' => $special_element_type,
					'se_meta' => $special_element_meta
				);

				$property_values = UpFrontElementsData::get_special_element_properties($property_values_args);
				$property_values_excluding_defaults = UpFrontElementsData::get_special_element_properties(array_merge($property_values_args, array('exclude_default_data' => true)));

			}

		/* Führt die nicht gespeicherten Werte zusammen */
			$property_values = is_array($unsaved_values) ? array_merge($property_values, $unsaved_values) : $property_values;
			$property_values_excluding_defaults = is_array($unsaved_values) ? array_merge($property_values_excluding_defaults, $unsaved_values) : $property_values_excluding_defaults;

		/* Zeige die entsprechenden Eingaben und Werte je nach Element an */
		UpFrontPropertyInputs::display($element, $special_element_type, $special_element_meta, $property_values, $property_values_excluding_defaults);

	}


	public static function method_get_design_editor_elements() {

		$current_layout = upfront_post('layout');
		$all_elements = UpFrontElementAPI::get_all_elements();
		$groups = UpFrontElementAPI::get_groups();
		$customized_element_data = UpFrontElementsData::get_all_elements();
		$elements = array('groups' => $groups);

		/* Baue die Arrays zusammen */
		foreach ( $all_elements as $element_id => $element_settings ) {

			$elements[$element_id] = array(
				'selector' => $element_settings['selector'],
				'id' => $element_settings['id'],
				'parent' => upfront_get('parent', $element_settings),
				'name' => $element_settings['name'],
				'description' => upfront_get('description', $element_settings),
				'properties' => $element_settings['properties'],
				'group' => $element_settings['group'],
				'states' => upfront_get('states', $element_settings, array()),
				'instances' => upfront_get('instances', $element_settings, array()),
				'disallow-nudging' => upfront_get('disallow-nudging', $element_settings, false),
				'inspectable' => upfront_get('inspectable', $element_settings),
				'customized' => count( upfront_get('properties', upfront_get( $element_settings['id'], $customized_element_data), array()) ) ? true : false,
				'tooltip' => $element_settings['tooltip'],
			);

			/* Durchlaufe die Hauptelementinstanzen und füge bei Bedarf ein benutzerdefiniertes Flag hinzu*/
				foreach ( $elements[$element_id]['instances'] as $element_instance_id => $element_instance_settings ) {

					if ( isset($customized_element_data[$element_settings['id']]['special-element-instance'][$element_instance_id]) )
						$elements[$element_id]['instances'][$element_instance_id]['customized'] = true;

				}

		}

		/* Spuck alles aus */
		self::json_encode($elements);

	}

	public static function method_get_design_editor_element_data() {

		self::json_encode(UpFrontElementsData::get_all_elements(true));

	}


	/* Vorlagenmethoden */
	public static function secure_method_add_template() {

		//Sende die Vorlagen-ID zurück an JavaScript, damit sie der Liste hinzugefügt werden kann
		self::json_encode(UpFrontLayout::add_template(upfront_post('template_name')));

	}

	public static function secure_method_rename_layout_template() {

		//Vorlagen abrufen
		$templates = UpFrontSkinOption::get( 'list', 'templates', array() );

		//Vorlage zum Umbenennen abrufen
		$id = str_replace('template-', '', upfront_post( 'layout' ));

		//Umbenennen
		if ( isset( $templates[ $id ] ) ) {

			$templates[ $id ] = upfront_post( 'newName' );

			//Zurück zur Datenbank senden
			UpFrontSkinOption::set( 'list', $templates, 'templates' );

			do_action( 'upfront_visual_editor_rename_template' );

			echo 'Erfolgreich';

		} else {

			echo 'Fehlgeschlagen';

		}

	}

	public static function secure_method_delete_template() {

		//Vorlagen abrufen
		$templates = UpFrontSkinOption::get('list', 'templates', array());

		//Deaktiviere die gelöschte ID
		$id = upfront_post('template_to_delete');

		//Lösche die Vorlage, falls vorhanden, und sende das Array an die Datenbank zurück
		if ( isset($templates[$id]) ) {

			unset($templates[$id]);

			//Lösche Blöcke, Wrapper und DE-Einstellungen für die aktuelle Skin
			UpFrontLayout::delete_layout('template-' . $id);

			//Vorlage aus Vorlagenliste löschen
			UpFrontSkinOption::set('list', $templates, 'templates');

			do_action('upfront_visual_editor_delete_template');

			echo 'Erfolgreich';

		} else {

			echo 'Fehlgeschlagen';

		}

	}

	public static function secure_method_assign_template() {

		$layout = upfront_post('layout');
		$template = str_replace('template-', '', upfront_post('template'));

		//Fügt das Vorlagenflag hinzu
		UpFrontLayoutOption::set($layout, 'template', $template);

		//Fügt den globalen Vorlagenzuweisungen ein Vorlagenflag hinzu, um den Import/Export von Skins zu vereinfachen
			$template_assignments = UpFrontSkinOption::get('assignments', 'templates', array());
			$template_assignments[$layout] = $template;

			UpFrontSkinOption::set('assignments', $template_assignments, 'templates');

		do_action('upfront_visual_editor_assign_template');

		echo UpFrontLayout::get_name('template-' . $template);

	}

	public static function secure_method_remove_template_from_layout() {

		$layout = upfront_post('layout');

		//Entferne das Vorlagenflag
		if ( !UpFrontLayoutOption::set($layout, 'template', false) ) {
			echo 'failure';

			return;
		}

		//Entfernt das Vorlagenflag aus den globalen Vorlagenzuweisungen, um den Import/Export von Skins zu vereinfachen
			$template_assignments = UpFrontSkinOption::get('assignments', 'templates', array());
			unset($template_assignments[$layout]);

			UpFrontSkinOption::set('assignments', $template_assignments, 'templates');

		do_action('upfront_visual_editor_unassign_template');

		echo 'Erfolgreich';

	}


	/* Verschiedene Methoden */
	public static function method_clear_cache() {

        try {

            UpFrontCompiler::flush_cache(true);
            UpFrontBlocks::clear_block_actions_cache();

            echo 'Erfolgreich';

        } catch ( Exception $e ) {

            echo 'Fehlgeschlagen';

        }

	}

	public static function method_ran_tour() {

		$mode = upfront_post('mode');

		UpFrontOption::set('ran-tour-' . $mode, true);

	}

	public static function method_fonts_list() {

		return do_action('upfront_fonts_ajax_list_fonts_' . upfront_post('provider'));

	}


	/* Datenportabilität */
		/* Allgemeine Datenübertragbarkeit */
			public static function method_import_image() {

				UpFront::load('data/data-portability');

				/* Variablen einrichten */
					$image_id = upfront_post('imageID');
					$image_contents = upfront_post('imageContents');

				/* Bild von der Seite laden */
					self::json_encode(UpFrontDataPortability::decode_image_to_uploads($image_contents['base64_contents']));

			}

			public static function method_import_images() {

					UpFront::load('data/data-portability');

					/* Variablen einrichten */
						$import_file = upfront_post('importFile');
						$image_definitions = upfront_get('image-definitions', $import_file, array());

						$imported_images = array();

					/* Durchlaufe base64-Bilder und verschiebe sie in das Upload-Verzeichnis */
						foreach ( $image_definitions as $image_id => $image )
							$imported_images[$image_id] = UpFrontDataPortability::decode_image_to_uploads($image['base64_contents']);

					/* Ersetze Bildvariablen in der Importdatei */
						foreach ( $imported_images as $imported_image_id => $imported_image ) {

							/* Behandele Seitenladefehler */
							if ( upfront_get('error', $imported_image) ) {

								/* Ersetze das gesamte Array durch einen Fehler, um den Import von Einstellungen zu stoppen */
								$import_file = array(
									'error' => upfront_get('error', $imported_image)
								);

							} else if ( upfront_get('url', $imported_image) ) {

								$import_file = self::import_images_recursive_replace($imported_image_id, $imported_image['url'], $import_file);

							}

						}

					/* Entferne riesige Bilddefinitionen aus der Importdatei */
						unset($import_file['image-definitions']);

					/* Sende die Importdatei mit den ersetzten Bildern zurück an den Visual Editor */
						self::json_encode($import_file);

			}

					public static function replace_imported_images_variables($import_array) {

						/* Suche nach importierten Bildern */
							if ( empty($import_array['imported-images']) || !is_array($import_array['imported-images']) )
								return $import_array;

						/* Ersetze Bildvariablen in der Importdatei */
							foreach ( $import_array['imported-images'] as $imported_image_id => $imported_image ) {

								if ( upfront_get('url', $imported_image) ) {

									$import_array = self::import_images_recursive_replace($imported_image_id, $imported_image['url'], $import_array);

								/* Ändere die fehlerhafte Bildvariable so, dass sie auf ein 404-Bild zeigt */
								} else {

									$import_array = self::import_images_recursive_replace($imported_image_id, 'IMAGE_NOT_UPLOADED', $import_array);

								}

							}

						return $import_array;

					}

					public static function import_images_recursive_replace($variable, $replace, $array) {

						if ( !is_array($array) )
							return str_replace($variable, $replace, $array);

						$processed_array = array();

						foreach ( $array as $key => $value )
							$processed_array[$key] = self::import_images_recursive_replace($variable, $replace, $value);

						return $processed_array;

					}


		/* Skinportabilität */
			public static function method_export_skin() {

				UpFront::load('data/data-portability');

				parse_str(upfront_get('skin-info'), $skin_info);

				return UpFrontDataPortability::export_skin($skin_info['skin-export-info']);

			}


		/* Speichere Skin in Cloud */
			public static function method_save_skin_on_cloud() {

				if(class_exists('upfrontServices')){

					UpFront::load('data/data-portability');
					parse_str(upfront_post('skin-info'), $skin_info);

					$skin 	= UpFrontDataPortability::export_skin($skin_info['skin-save-on-cloud-info'],true);

					$templateData = array(
						'name' 			=> $skin_info['skin-save-on-cloud-info']['name'],
						'description' 	=> $skin_info['skin-save-on-cloud-info']['description'],
						'author' 		=> $skin_info['skin-save-on-cloud-info']['author'],
						'version' 		=> $skin_info['skin-save-on-cloud-info']['version'],
						'image' 		=> $skin_info['skin-save-on-cloud-info']['image-url'],
						'visibility' 	=> $skin_info['skin-save-on-cloud-info']['visibility'],
						'price' 		=> $skin_info['skin-save-on-cloud-info']['price'],
						'preview' 		=> $skin_info['skin-save-on-cloud-info']['preview'],
					);					

					$upfrontServices = new upfrontServices();
					$response = $upfrontServices->saveTemplateOnCloud($skin,$templateData);
					if($response) {
						$response = (array)$response;
						if($response['error']){
							return self::json_encode(array(
								'error' => $response['error']
							));
						}else{
							return self::json_encode(array(
								'ok' => 'Vorlage gespeichert.'
							));
						}
					}else{
						return self::json_encode(array(
							'error' => 'Fehler beim Speichern der Vorlage.'
						));
					}

				}else{
					return self::json_encode(array(
						'error' => 'Fehler beim speichern der UpFront Vorlage.'
					));
				}

			}

			public static function method_install_skin() {

				UpFront::load('data/data-portability');

				$skin_data = json_decode(stripslashes(upfront_post('skin')), true);

				if ( !is_array($skin_data) ) {
					return self::json_encode(array(
						'error' => 'Vorlage konnte nicht installiert werden.'
					));
				}

				$skin = self::replace_imported_images_variables($skin_data);

				return self::json_encode(UpFrontDataPortability::install_skin($skin));

			}


		/* Layout-Portabilität */
			public static function method_export_layout() {

				UpFront::load('data/data-portability');

				$layout = upfront_get('layout', false);

				return UpFrontDataPortability::export_layout($layout);

			}


		/* Portabilität der Blockeinstellungen */
			public static function method_export_block_settings() {

				UpFront::load('data/data-portability');

				return UpFrontDataPortability::export_block_settings(upfront_get('block-id'));

			}

		/* Portabilität der Wrapper-Einstellungen */
			public static function method_export_wrapper_settings() {

				UpFront::load('data/data-portability');

				return UpFrontDataPortability::export_wrapper_settings(upfront_get('wrapper-id'));

			}

	/* Effekte */

		public static function method_get_effect_content() {

			global $wp_filesystem;
			WP_Filesystem();

			$tagName 	= upfront_post('tagName');
			$effect 	= upfront_post('effect');
			$selector 	= upfront_post('selector');

			switch ($tagName) {
				case 'IMG':
					$path 		= UPFRONT_LIBRARY_DIR . '/visual-editor/effects-css/' . $effect . '.txt';					
					$selector 	= preg_replace('/\ img/', '', $selector);
					break;

				default:
					$path = false;
					break;
			}

			if($path !== false && file_exists($path)){				
				$data = preg_replace("/%selector%/", $selector, $wp_filesystem->get_contents($path));				
				return self::json_encode($data);
			}else{
				return self::json_encode(array(
					'error' => 'Ungültiger Effekt für diesen Inhalt.'
				));
			}


		}



}