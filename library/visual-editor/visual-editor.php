<?php

class UpFrontVisualEditor {


	protected static $modes 			= array();	
	protected static $default_mode 		= 'grid';
	protected static $default_layout 	= 'index';


	public static function init() {

		if ( !UpFrontCapabilities::can_user_visually_edit() )
			return;

		//Wenn kein Child Theme aktiv ist oder wenn ein Child Theme aktiv ist und das Gitter unterstützt wird, verwende den Rastermodus.
		if ( current_theme_supports('upfront-grid') )
			self::$modes['Grid'] = __('Füge Blöcke hinzu und ordne Deine Webseiten-Struktur', 'upfront');

		self::$modes['Design'] = __('Wähle Schriftarten, Farben und andere Stile', 'upfront');

		//Wenn das Gitter deaktiviert ist, lege Design als Standardmodus fest.
		if ( !current_theme_supports('upfront-grid') )
			self::$default_mode = 'design';

		UpFrontSettings::set_visual_editor_settings();

		//In Aktion setzen, damit wir Funktionen der obersten Ebene ausführen können
		do_action('upfront_visual_editor_init');

		//Visual Editor AJAX		
		add_action('wp_ajax_upfront_visual_editor', array(__CLASS__, 'ajax'));

		if ( UpFrontOption::get('debug-mode') ) {
			add_action('wp_ajax_nopriv_upfront_visual_editor', array(__CLASS__, 'ajax'));
		}

		//Cache-Ablehnung
		global $cache_rejected_uri;

		if ( ! is_array( $cache_rejected_uri ) ) {
			$cache_rejected_uri = array();
		}

		$cache_rejected_uri[] = 'visual\-editor\=true';
		$cache_rejected_uri[] = 've\-iframe\=true';

		//Iframe-Handhabung
		add_action('upfront_body_close', array(__CLASS__, 'iframe_load_flag'));
		add_action('upfront_grid_iframe_footer', array(__CLASS__, 'iframe_load_flag'));

		add_action('upfront_grid_iframe_footer', array(__CLASS__, 'iframe_tooltip_container'));
		add_action('upfront_body_close', array(__CLASS__, 'iframe_tooltip_container'));

        wp_enqueue_media();

		upfront_register_web_font_provider('UpFrontTraditionalFonts');

		if( ! UpFrontOption::get('do-not-use-google-fonts') ){
			upfront_register_web_font_provider('UpFrontGoogleFonts');
		}			


	}


	public static function ajax() {

		if ( ! defined( 'DONOTCACHEDB' ) ) {
			define( 'DONOTCACHEDB', true );
		}

		if ( ! defined( 'DONOTCACHCEOBJECT' ) ) {
			define( 'DONOTCACHCEOBJECT', true );
		}

		UpFront::load('visual-editor/display', 'VisualEditorDisplay');
		UpFront::load('visual-editor/visual-editor-ajax');

		//Nonce authentifizieren
		check_ajax_referer('upfront-visual-editor-ajax', 'security');

		$method = upfront_post('method') ? upfront_post('method') : upfront_get('method');

		//Suche zuerst nach einer nicht sicheren AJAX-Anforderung (die keine Daten speichert) (lasse die Authentifizierung im Debug-Modus durchlaufen).
		if ( method_exists('UpFrontVisualEditorAJAX', 'method_' . $method) && UpFrontCapabilities::can_user_visually_edit() ) {
			do_action('upfront_visual_editor_ajax_pre_' . $method);
			call_user_func(array('UpFrontVisualEditorAJAX', 'method_' . $method));
			do_action('upfront_visual_editor_ajax_post_' . $method);
		}

		//Suche nach einer sicheren AJAX-Anforderung (die Daten speichert) und erfordere eine echte Authentifizierung
		elseif ( method_exists('UpFrontVisualEditorAJAX', 'secure_method_' . $method) && UpFrontCapabilities::can_user_visually_edit(true) ) {
			do_action('upfront_visual_editor_ajax_pre_' . $method);
			call_user_func(array('UpFrontVisualEditorAJAX', 'secure_method_' . $method));
			do_action('upfront_visual_editor_ajax_post_' . $method);
		}

		die();

	}


	public static function ajax_error_handler($errno, $errstr, $errfile, $errline) {

		if ( !defined( 'E_STRICT' ) )
			define( 'E_STRICT', 2048 );

		if ( !defined( 'E_RECOVERABLE_ERROR' ) )
			define( 'E_RECOVERABLE_ERROR', 4096 );

		if ( !defined( 'E_DEPRECATED' ) )
			define( 'E_DEPRECATED', 8192 );

		if ( !defined( 'E_USER_DEPRECATED' ) )
			define( 'E_USER_DEPRECATED', 16384 );

		$severity =
			1 * E_ERROR |
			1 * E_WARNING |
			0 * E_PARSE |
			0 * E_NOTICE |
			0 * E_CORE_ERROR |
			0 * E_CORE_WARNING |
			0 * E_COMPILE_ERROR |
			0 * E_COMPILE_WARNING |
			0 * E_USER_ERROR |
			0 * E_USER_WARNING |
			0 * E_USER_NOTICE |
			0 * E_STRICT |
			0 * E_RECOVERABLE_ERROR |
			0 * E_DEPRECATED |
			0 * E_USER_DEPRECATED;

		$error_ex = new ErrorException( $errstr, 0, $errno, $errfile, $errline );

		if ( ( $error_ex->getSeverity() & $severity ) != 0 ) {
			throw $error_ex;
		}

	}


	public static function save($options, $current_layout = false, $mode = false) {

		set_error_handler(array(__CLASS__, "ajax_error_handler"));

		$output = array(
			'errors' => array()
		);

		if ( !$current_layout )
			$current_layout = upfront_post('layout');

		if ( !$mode )
			$mode = upfront_post('mode');

		$blocks 				= isset($options['blocks']) ? $options['blocks'] : null;
		$wrappers 				= isset($options['wrappers']) ? $options['wrappers'] : null;
		$layout_options 		= isset($options['layout-options']) ? $options['layout-options'] : null;
		$options_inputs 		= isset($options['options']) ? $options['options'] : null;
		$design_editor_inputs 	= isset($options['design-editor']) ? $options['design-editor'] : null;

		try {

			/* Füge Container hinzu*/
			if ( $wrappers ) {

				foreach ( $wrappers as $id => $methods ) {

					foreach ( $methods as $method => $value ) {

						switch ( $method ) {

							case 'new':

								if ( UpFrontWrappersData::get_wrapper($id) )
									continue 2;

								if ( isset($wrappers[$id]['delete']) )
									continue 2;

								$args = array(
									'position' => upfront_get('position', $wrappers[$id], 9999),
									'settings' => upfront_get('settings', $wrappers[$id], array())
								);

								if ( $wrappers[$id]['insert_id'] ) {
									$args['id'] = $wrappers[$id]['insert_id'];
								}

								$new_wrapper = UpFrontWrappersData::add_wrapper($current_layout, $args);

								if ( is_wp_error($new_wrapper) ) {
									$output['errors'][] = $new_wrapper->get_error_code() . ($new_wrapper->get_error_message() ? ' - ' . $new_wrapper->get_error_code() : '');
								} else {
									$output['wrapper-id-mapping'][$id] = $new_wrapper;
								}

							break;

						}

					}

				}

			}
			/* Ende Hinzufügen von Wrappern */


			/* Blöcke */
			if ( $blocks ) {

				foreach ( $blocks as $id => $methods ) {

					foreach ( $methods as $method => $value ) {

						switch ( $method ) {

							case 'new':

								if ( UpFrontBlocksData::get_block($id) )
									continue 2;

								if ( isset($blocks[$id]['delete']) )
									continue 2;

								$dimensions = explode(',', $blocks[$id]['dimensions']);
								$position = explode(',', $blocks[$id]['position']);

								$settings = isset($blocks[$id]['settings']) ? $blocks[$id]['settings'] : array();

								/* Überprüft ob die Container-ID für den Block temporär ist und ob sie die tatsächliche Block-ID erhält */
								if ( isset($output['wrapper-id-mapping']) && $added_wrapper_id = upfront_get(UpFrontWrappers::format_wrapper_id($blocks[$id]['wrapper']), $output['wrapper-id-mapping']) ) {
									$blocks[$id]['wrapper'] = $added_wrapper_id;
								}

								/* Wenn 'duplicateOf' im Array $ settings vorhanden ist, entferne diesen Schlüssel und ziehe die Optionen aus dem Block, der dupliziert wird */
								$duplicate = upfront_get('duplicateOf', $settings);

								if ( $duplicate ) {

									$duplicated_block = UpFrontBlocksData::get_block($duplicate);
									$settings = upfront_array_merge_recursive_simple(upfront_get('settings', $duplicated_block), $settings);

									unset($settings['duplicateOf']);

								}

								$args = array(
									'type' => $value,
									'wrapper' => $blocks[$id]['wrapper'],
									'position' => array(
										'left' => $position[0],
										'top' => $position[1]
									),
									'dimensions' => array(
										'width' => $dimensions[0],
										'height' => $dimensions[1]
									),
									'settings' => $settings
								);

								if ( $blocks[$id]['insert_id'] ) {
									$args['id'] = $blocks[$id]['insert_id'];
								}

								$new_block = UpFrontBlocksData::add_block($current_layout, $args);

								if ( is_wp_error($new_block) ) {
									$output['errors'][] = $new_block->get_error_code() . ($new_block->get_error_message() ? ' - ' . $new_block->get_error_code() : '');
								} else {

									/* Fügt bei Bedarf ein Styling für Duplikate hinzu */
									if ( $duplicate ) {

										$duplicated_block_styling = UpFrontBlocksData::get_block_styling($duplicated_block);

										/* Geht durch und verarbeitet das Styling */
										foreach ( $duplicated_block_styling as $instance_id => $instance ) {

											foreach ( upfront_get('properties', $instance, array()) as $property => $property_value ) {

												$instance_id = str_replace('block-' . $duplicated_block['id'], 'block-' . $new_block, $instance_id);

												UpFrontElementsData::set_special_element_property(null, $instance['element'], 'instance', $instance_id, $property, $property_value);

											}

										}

									}

									$output['block-id-mapping'][$id] = $new_block;

								}

								break;

							case 'delete':

								if ( isset($blocks[$id]['new']) )
									continue 2;

								UpFrontBlocksData::delete_block($id);

								break;

							case 'dimensions':

								if ( isset($blocks[$id]['new']) )
									continue 2;

								$dimensions = explode(',', $value);

								$args = array(
									'dimensions' => array(
										'width' => $dimensions[0],
										'height' => $dimensions[1]
									)
								);

								UpFrontBlocksData::update_block($id, $args);

								break;

							case 'position':

								if ( isset($blocks[$id]['new']) )
									continue 2;

								$position = explode(',', $value);

								$args = array(
									'position' => array(
										'left' => $position[0],
										'top' => $position[1]
									)
								);

								UpFrontBlocksData::update_block($id, $args);

								break;

							case 'wrapper':

								if ( isset($blocks[$id]['new']) )
									continue 2;

								/* Überprüft ob die Container-ID für den Block temporär ist und ob es sich um die echte Container-ID handelt */
								if ( isset($output['wrapper-id-mapping']) && upfront_get($value, $output['wrapper-id-mapping']) )
									$value = upfront_get($value, $output['wrapper-id-mapping']);

								$args = array(
									'wrapper' => $value
								);


								UpFrontBlocksData::update_block($id, $args);

								break;

							case 'settings':

								if ( isset($blocks[$id]['new']) )
									continue 2;

								//Holt sich den Block aus dem Layout
								$block = UpFrontBlocksData::get_block($id);

								// Blockeinstellungen abrufen
								$settings = upfront_get('settings', $block);

								if(!is_array($settings))
									$settings = $blocks[$id]['settings'];

								//Wenn kein Block existiert, können wir nichts tun.
								if ( !$block || !is_array($settings) )
									continue 2;

								//Wenn es keine Optionen gibt, tu auch nichts
								if ( !is_array($value) || count($value) === 0 )
									continue 2;

								$block['settings'] = array_merge($settings, $value);

								UpFrontBlocksData::update_block($id, $block);

								break;

						}

					}

				}

			}
			/* Ende Blöcke */


			/* Mach alles andere mit Wrappern. Grund dafür ist, dass die Container-IDs zum Hinzufügen von Blöcken eingerichtet werden müssen. Wenn wir jedoch einen Block aus einem Container verschieben und diesen Container löschen, möchten wir nicht, dass diese Blöcke gelöscht werden. */
			if ( $wrappers ) {

				foreach ( $wrappers as $id => $methods ) {

					foreach ( $methods as $method => $value ) {

						switch ( $method ) {

							case 'delete':

								if ( isset($wrappers[$id]['new']) )
									continue 2;

								UpFrontWrappersData::delete_wrapper($current_layout, $id);

								break;

							case 'position':

								if ( isset($wrappers[$id]['new']) )
									continue 2;

								$args = array(
									'position' => $value
								);

								UpFrontWrappersData::update_wrapper($id, $args);

								break;

							case 'settings':

								if ( isset($wrappers[$id]['new']) )
									continue 2;

								//Holt sich den Container aus dem Layout, damit wir die Einstellungen zusammenführen können
								$wrapper = UpFrontWrappersData::get_wrapper($id);

								//Wenn es keinen Container gibt, können wir nichts tun.
								if ( !$wrapper )
									continue 2;

								//Wenn es keine Optionen gibt, tu auch nichts
								if ( !is_array($value) || count($value) === 0 )
									continue 2;

								$wrapper['settings'] = array_merge($wrapper['settings'], $value);

								UpFrontWrappersData::update_wrapper($id, $wrapper);

								break;

						}

					}

				}

			}
			/* Beende alle anderen Container (Löschen und Optionen) */



			/* Layoutoptionen */
			if ( $layout_options ) {

				foreach ( $layout_options as $group => $options ) {

					foreach ( $options as $option => $value ) {
						UpFrontLayoutOption::set($current_layout, $option, $value, $group);
					}

				}

			}
			/* Layoutoptionen beenden */

			/* Optionen */
			if ( $options_inputs ) {

				foreach ( $options_inputs as $group => $options ) {

					foreach ( $options as $option => $value ) {
						UpFrontSkinOption::set($option, $value, $group);
					}

				}

			}
			/* Ende Optionen */

			/* Design Editor-Eingaben */
			if ( $design_editor_inputs ) {

				$design_editor_properties = UpFrontElementProperties::get_properties();


				/* Durchlaufe jedes Element und seine Eigenschaften */
				foreach ( $design_editor_inputs as $element_id => $element_data ) {

					if ( !is_array($element_data) )
						continue;

					$batch_special_element_data = array();

					//Versand je nach Art der Elementdaten
					foreach ( $element_data as $element_data_node => $element_data_node_data ) {

						//Behandelt verschiedene Knoten, je nachdem, was sie sind
						if ( $element_data_node == 'properties' ) {

							//Legt jede Eigenschaft für das reguläre Element fest							
							foreach ( $element_data_node_data as $property_id => $property_value ) {

								/**
								 *
								 * Erweiterte CSS-Unterstützung
								 *
								 */
								switch ($property_id) {

								 	//Unterstützung für CSS-Transformationen
									case 'skew':
										$property_id 	= 'transform';
										$property_value = 'skew('.$property_value.'deg)';
										break;

								 	//Margin Top Auto
									case 'margin-top-auto':
										$property_id 	= 'margin-top';
										break;

								 	//Margin Right Auto
									case 'margin-right-auto':
										$property_id 	= 'margin-right';
										break;

								 	//Margin Bottom Auto
									case 'margin-bottom-auto':
										$property_id 	= 'margin-bottom';
										break;

								 	//Margin Left Auto
									case 'margin-left-auto':
										$property_id 	= 'margin-left';
										break;
								}


								UpFrontElementsData::set_property( null, $element_id, $property_id, $property_value );

								if ( upfront_get( 'js-property', $design_editor_properties[ $property_id ] ) ) {
									UpFrontElementsData::set_js_property( $element_id , $property_id, $property_value );
								}
							}

							//Behandelt Instanzen, Zustände usw.
						} else if ( strpos($element_data_node, 'special-element-') === 0 ) {

							$special_element_type = str_replace('special-element-', '', $element_data_node);

							//Durchlaufe die speziellen Elemente						
							foreach ( $element_data_node_data as $special_element => $special_element_properties ) {

								/* Wenn eine Block-ID-Zuordnung vorhanden ist, stellen Sie sicher, dass keine der temporären IDs als Instanzen gespeichert wird. Dies dient hauptsächlich dazu, dass der Import von Blockeinstellungen funktioniert, wenn sie für einen Block ausgeführt werden, der noch nicht gespeichert wurde. */
								if ( isset($output['block-id-mapping']) && count($output['block-id-mapping']) ) {

									foreach ( $output['block-id-mapping'] as $old_block_id => $new_block_id ) {
										$special_element = str_replace('block-' . $old_block_id, 'block-' . $new_block_id, $special_element);
									}

								}

								/* Wenn eine Container-ID-Zuordnung vorhanden ist, gehe genauso vor wie bei der Block-ID-Zuordnung */
								if ( isset( $output['wrapper-id-mapping'] ) && count( $output['wrapper-id-mapping'] ) ) {

									foreach ( $output['wrapper-id-mapping'] as $old_wrapper_id => $new_wrapper_id ) {
										$special_element = str_replace( 'wrapper-' . UpFrontWrappers::format_wrapper_id($old_wrapper_id), 'wrapper-' . UpFrontWrappers::format_wrapper_id( $new_wrapper_id ), $special_element );
									}

								}

								//Lege jetzt die speziellen Elementeigenschaften fest								
								foreach ( $special_element_properties as $special_element_property => $special_element_property_value ) {

									/**
									 *
									 * Advanced CSS support
									 *
									 */									
									switch ($special_element_property) {

									 	//CSS transform support
										case 'skew':
											$special_element_property 		= 'transform';
											$special_element_property_value = 'skew('.$special_element_property_value.'deg)';
											break;

									 	//Margin Top Auto
										case 'margin-top-auto':
											$special_element_property 		= 'margin-top';
											break;

									 	//Margin Right Auto
										case 'margin-right-auto':
											$special_element_property 		= 'margin-right';
											break;

									 	//Margin Bottom Auto
										case 'margin-bottom-auto':
											$special_element_property 		= 'margin-bottom';
											break;

									 	//Margin Left Auto
										case 'margin-left-auto':
											$special_element_property 		= 'margin-left';
											break;

									}


									$batch_special_element_data[] = array(
										'element_id' => $element_id,
										'special_element_type' => $special_element_type,
										'special_element_meta' => $special_element,
										'property_id' => $special_element_property,
										'value' => $special_element_property_value
									);


									if ( upfront_get('js-property', $design_editor_properties[$special_element_property] ) ) {
										UpFrontElementsData::set_js_property($element_id . '||' . $special_element_type . '||' . $special_element, $special_element_property, $special_element_property_value);
									}
								}

							}

						}

					}
					UpFrontElementsData::batch_set_special_element_properties($batch_special_element_data);

				}
				/* Ende loop */

			}
			/* Eingaben für den Design-Editor beenden */

			/* Stellt das automatische Laden ein */
			UpFront::set_autoload();

			//Dieser Hook wird vom Cache-Leeren, von Plugins usw. verwendet. Wird beim Speichern der Vorschau nicht ausgelöst, da dadurch die Vorschauoptionen geleert werden
			if ( !upfront_get( 've-preview' ) ) {
				
				do_action( 'upfront_visual_editor_save' );

				if (UpFrontOption::get( 'headway-support' ) ) {
					do_action( 'headway_visual_editor_save' );
				}

				if (UpFrontOption::get( 'bloxtheme-support' ) ) {
					do_action( 'blox_visual_editor_save' );
				}
			}

			/* Snapshot speichern, falls zulässig */
			if ( !defined('UPFRONT_DISABLE_AUTO_SNAPSHOT') || UPFRONT_DISABLE_AUTO_SNAPSHOT !== true ) {
				$output['snapshot'] = UpFrontDataSnapshots::save_snapshot(true);
			}

		} catch (Exception $e) {

			/* Deaktiviere jetzt die Fehlerausgabe beim Speichern */

			/*
			if ( !isset($output['errors']) || !is_array($output['errors']) )
				$output['errors'] = array();

			$output['errors'][] = $e->getMessage() . '<br /><br/><pre style="overflow: scroll;user-select: all;max-width: 220px;-webkit-user-select: all;-moz-user-select:all;border: 1px solid rgba(255, 255, 255, 0.2);">' . $e->getTraceAsString() . '</pre>';
			*/

		}

		if ( !count($output['errors']) )
			unset($output['errors']);

		return $output;

	}


	public static function display() {

		self::check_if_ie();

		UpFront::load('visual-editor/display', 'VisualEditorDisplay');
		UpFrontVisualEditorDisplay::display();

	}


	public static function check_if_ie() {

		/* Zeige dies nur bei IE-Versionen unter 9 an */
		if ( !upfront_is_ie() || (upfront_is_ie(9) || upfront_is_ie(10) || upfront_is_ie(11)) )
			return false;

		$message = '<span style="text-align: center;font-size: 26px;width: 100%;display: block;margin-bottom: 20px;">Error</span>';

		$message .= __('Leider funktioniert der UpFront Visual Editor aufgrund fehlender moderner Funktionen nicht mit Internet Explorer.', 'upfront') . '<br /><br />';

		$message .= __('Bitte aktualisiere auf einen modernen Browser wie <a href="http://www.google.com/chrome" target="_blank">Google Chrome</a> oder <a href="http://firefox.com" target="_blank">Mozilla Firefox</a>.', 'upfront') . '<br /><br />'; 

		$message .= __('Wenn diese Meldung nach dem Upgrade auf einen modernen Browser weiterhin angezeigt wird, besuche bitte unsere <a href="https://n3rds.work" target="_blank">Community</a>.', 'upfront');

		return wp_die($message);

	}


	public static function get_modes() {

		return apply_filters('upfront_visual_editor_get_modes', self::$modes);

	}	


	public static function get_current_mode() {

		$mode = upfront_get('visual-editor-mode');

		if ( $mode ) {

			if ( array_search(strtolower($mode), array_map('strtolower', array_keys(self::$modes))) ) {				
				return strtolower($mode);

			} 

		}

		return strtolower(self::$default_mode);

	}	


	public static function is_mode($mode) {

		if ( self::get_current_mode() === strtolower($mode) )
			return true;

		if ( !upfront_get('visual-editor-mode') && strtolower($mode) === strtolower(self::$default_mode) )
			return true;

		return false;

	}


	//////////////////    iframe handling   ///////////////////////
	public static function iframe_load_flag() {

		echo '<script type="text/javascript">
			/* Stelle den Iframe als geladen für den Iframe-Ladeprüfer ein */
			document.getElementsByTagName("body")[0].className += " iframe-loaded";
			//jQuery("body").addClass("iframe-loaded");
		</script>';

	}


	public static function iframe_tooltip_container() {

		echo '<div id="upfront-tooltip-container" style="position:fixed;top:0;left:0;width:100%;height:100%;background:transparent;z-index: 0;pointer-events:none;"></div>';

	}


}