<?php
class UpFrontDataPortability {


	public static function export_skin(array $info, $ret = false) {

		global $wpdb;

		do_action('upfront_before_export_skin');

		$wp_options_prefix = 'pu_|template=' . UpFrontOption::$current_skin . '|_';

		$skin = array(
			'pu-version' 			=> UPFRONT_VERSION,
			'name' 					=> upfront_get('name', $info, 'Unnamed'),
			'author' 				=> upfront_get('author', $info),
			'image-url' 			=> upfront_get('image-url', $info),
			'version' 				=> upfront_get('version', $info),
			'data_wp_options' 		=> $wpdb->get_results($wpdb->prepare("SELECT * FROM $wpdb->options WHERE option_name LIKE '%s'", $wp_options_prefix . '%'), ARRAY_A),
			'data_wp_postmeta' 		=> $wpdb->get_results($wpdb->prepare("SELECT * FROM $wpdb->postmeta WHERE meta_key LIKE '%s'", '_pu_|template=' . UpFrontOption::$current_skin . '|_%'), ARRAY_A),
			'data_pu_layout_meta' 	=> $wpdb->get_results($wpdb->prepare("SELECT * FROM $wpdb->pu_layout_meta WHERE template = '%s'", UpFrontOption::$current_skin), ARRAY_A),
			'data_pu_wrappers' 		=> $wpdb->get_results($wpdb->prepare("SELECT * FROM $wpdb->pu_wrappers WHERE template = '%s'", UpFrontOption::$current_skin), ARRAY_A),
			'data_pu_blocks' 		=> $wpdb->get_results($wpdb->prepare("SELECT * FROM $wpdb->pu_blocks WHERE template = '%s'", UpFrontOption::$current_skin), ARRAY_A)
		);

		/* Spit the file out */
		$filename = 'UpFront Template - ' . upfront_get('name', $info, 'Unnamed');

		if ( upfront_get('version', $info) ) {
			$filename .= ' ' . upfront_get('version', $info);
		}

		return self::to_json($filename, 'skin', $skin, $ret);

	}


	public static function install_skin(array $skin) {

		if(upfront_get('pu-version', $skin)){

			if(version_compare(upfront_get('pu-version', $skin), '0.0.17', '<')){
				return array('error' => 'Dies ist keine gültige UpFront-Vorlage');
			}

		}elseif (upfront_get('bt-version', $skin)) {

			if(version_compare(upfront_get('bt-version', $skin), '1.0.0', '<')){
				return array('error' => 'Blox templates before 1.0.0 versions are not supported');
			}

		}elseif (upfront_get('hw-version', $skin)) {

			if(version_compare(upfront_get('hw-version', $skin), '3.7', '<')){
				return array('error' => 'Headway templates from pre-3.7 versions are not supported');
			}

		}else{
			return array('error' => 'Dies ist keine gültige UpFront-Vorlage');
		}

		$skins = UpFrontOption::get_group('skins');

		/* Remove image definitions */
			if ( isset($skin['image-definitions']) )
				unset($skin['image-definitions']);

		/* Skin ID ... Truncate the skin ID to 12 characters due to varchar limit in wp_options */
			$original_skin_id 		= substr(strtolower(str_replace(' ', '-', $skin['name'])), 0, 12);
			$skin_id 				= $original_skin_id;
			$skin_name 				= $skin['name'];
			$skin_unique_id_counter = 0;

		/* Check if skin already exists.  If it does, change ID and skin name */
			while ( UpFrontOption::get($skin_id, 'skins') || get_option('pu_|template=' . $skin_id . '|_option_group_general') ) {

				$skin_unique_id_counter++;
				$skin_id 	= $original_skin_id . '-' . $skin_unique_id_counter;
				$skin_name 	= $skin['name'] . ' ' . $skin_unique_id_counter;

			}

		/* Send skin to DB */
			$skin['id'] 			= $skin_id;
			$skin['name'] 			= $skin_name;
			$skin_with_info_only 	= $skin;

			$data_to_remove_from_saved_skin = array(
				'data_wp_options',
				'data_wp_postmeta',
				'data_pu_layout_meta',
				'data_pu_wrappers',
				'data_pu_blocks',
				'templates',
				'layouts',
				'element-data'
			);

			foreach ( $data_to_remove_from_saved_skin as $key_to_remove ) {

				if ( !isset($skin_with_info_only[$key_to_remove]) )
					continue;

				unset($skin_with_info_only[$key_to_remove]);

			}

			UpFrontOption::set($skin['id'], $skin_with_info_only, 'skins');

		/* Change current skin ID to the newly added skin so we can populate data */
			UpFrontOption::$current_skin = $skin['id'];
			UpFrontLayoutOption::$current_skin = $skin['id'];

		/**
		 *
		 * Grid CSS started with UpFront 1.3.9+
		 *
		 */			
			if(version_compare(upfront_get('pu-version', $skin), '1.3.9', '<')){

				foreach ($skin['data_pu_wrappers'] as $key => $wrapper) {
					if( empty($wrapper['settings']['grid-system']) ){
						$skin['data_pu_wrappers'][$key]['settings']['grid-system'] = 'legacy';
					}

				}
			}

		/* Process the install */
			if(isset($skin['pu-version'])){
				$skin = self::process_install_skin($skin);


			// Headway themes support
			}elseif (isset($skin['hw-version'])) {

				$skin = self::convert_skin_hw_to_upfront($skin);				
				$skin = self::process_install_skin($skin);

			// Blox theme support
			}elseif (isset($skin['bt-version'])) {

				$skin = self::convert_skin_blox_to_upfront($skin);
				$skin = self::process_install_skin($skin);

			// Not supported old Headway < 3.7
			}elseif (!upfront_get('hw-version', $skin) || version_compare(upfront_get('hw-version', $skin), '3.7', '<') ) {
				return array('error' => 'Headway-Vorlagen aus Versionen vor Version 3.7 werden nicht unterstützt');
			}


		/* Change $current_skin back just to be safe */
			UpFrontOption::$current_skin 			= UpFrontTemplates::get_active_id();
			UpFrontLayoutOption::$current_skin 	= UpFrontTemplates::get_active_id();



		return $skin;

	}


		public static function process_install_skin_pre37(array $skin) {

			/* Set up skin options that way when it's activated it looks right */
				/* Install templates */
				if ( $skin_templates = upfront_get('templates', $skin) )
					UpFrontSkinOption::set_group('templates', $skin_templates);

					/* Assign templates */
						if ( !empty($skin['templates']['assignments']) ) {

							foreach ( $skin['templates']['assignments'] as $layout_id => $template_id ) {

								/* Change layout ID separators */
								if ( strpos($layout_id, 'template-') !== 0 )
									$layout_id = str_replace('-', UpFrontLayout::$sep, $layout_id);

								UpFrontLayoutOption::set($layout_id, 'template', $template_id);

							}

						}

				/* Install layouts (blocks, wrappers, and flags */
					$wrapper_id_mapping = array();
					$block_id_mapping = array();

					foreach ( $skin['layouts'] as $layout_id => $layout_data ) {

						/* Change layout ID separators */
							if ( strpos($layout_id, 'template-') !== 0 )
								$layout_id = str_replace('-', UpFrontLayout::$sep, $layout_id);

						/* Install Wrappers */
							foreach ( $layout_data['wrappers'] as $wrapper_id => $wrapper_data ) {

								$wrapper_data['position'] = array_search($wrapper_id, array_keys($layout_data['wrappers']));

								$wrapper_data['settings'] = array(
									'fluid' => upfront_get('fluid', $wrapper_data),
									'fluid-grid' => upfront_get('fluid-grid', $wrapper_data),
									'columns' => upfront_get('columns', $wrapper_data),
									'column-width' => upfront_get('column-width', $wrapper_data),
									'gutter-width' => upfront_get('gutter-width', $wrapper_data),
									'use-independent-grid' => upfront_get('use-independent-grid', $wrapper_data),
								);

								$new_wrapper = UpFrontWrappersData::add_wrapper($layout_id, $wrapper_data);

								if ( $new_wrapper && !is_wp_error($new_wrapper)  ) {
									$wrapper_id_mapping[UpFrontWrappers::format_wrapper_id($wrapper_id)] = $new_wrapper;
								}

							}

						/* Install Blocks */
							foreach ( $layout_data['blocks'] as $block_id => $block_data ) {

								$block_data['wrapper'] = upfront_get(UpFrontWrappers::format_wrapper_id(upfront_get('wrapper', $block_data)), $wrapper_id_mapping);

								$new_block = UpFrontBlocksData::add_block($layout_id, $block_data);

								if ( $new_block && !is_wp_error($new_block) ) {
									$block_id_mapping[$block_id] = $new_block;
								}

							}

					}

				/* Setup mirroring */
					foreach ( $skin['layouts'] as $layout_id => $layout_data ) {

						/* Change layout ID separators */
						if (strpos($layout_id, 'template-') !== 0)
							$layout_id = str_replace('-', UpFrontLayout::$sep, $layout_id);

						foreach ($layout_data['wrappers'] as $wrapper_id => $wrapper_data) {

							$wrapper_to_update = $wrapper_id_mapping[UpFrontWrappers::format_wrapper_id($wrapper_id)];

							if (!$mirror_id = upfront_get('mirror-wrapper', $wrapper_data))
								continue;

							$mirror_id = upfront_get(UpFrontWrappers::format_wrapper_id($mirror_id), $wrapper_id_mapping);

							UpFrontWrappersData::update_wrapper($wrapper_to_update, array(
								'mirror_id' => $mirror_id
							));

						}

						foreach ( $layout_data['blocks'] as $block_id => $block_data ) {

							if ( !isset($block_id_mapping[$block_id]) )
								continue;

							$block_to_update = $block_id_mapping[$block_id];

							if ( !$mirror_id = upfront_get('mirror-block', upfront_get('settings', $block_data, array())) )
								continue;

							$mirror_id = upfront_get($mirror_id, $block_id_mapping);

							UpFrontBlocksData::update_block($block_to_update, array(
								'mirror_id' => $mirror_id
							));

						}

					}

			/* Install design data */
				/* Sort the block and wrapper mappings by descending number that way when we do a simple recursive find and replace the small block IDs won't mess up the larger block IDs.
				   Example: Replacing block-1 before block-11 is replaced would be bad news */
				krsort($block_id_mapping);
				krsort($wrapper_id_mapping);

				foreach ( $block_id_mapping as $old_block_id => $new_block_id ) {
					$skin['element-data'] = upfront_str_replace_json('block-' . $old_block_id, 'block-' . $new_block_id, $skin['element-data']);
				}

				foreach ( $wrapper_id_mapping as $old_wrapper_id => $new_wrapper_id ) {
					$skin['element-data'] = upfront_str_replace_json('wrapper-' . $old_wrapper_id, 'wrapper-' . $new_wrapper_id, $skin['element-data']);
				}

				$skin['element-data'] = upfront_preg_replace_json( "/-layout-[\w-]*/", '', $skin['element-data'] );

				UpFrontSkinOption::set('properties', $skin['element-data'], 'design');
				UpFrontSkinOption::set('live-css', stripslashes($skin['live-css']));

			/* Set merge flag that way the next time they save it won't screw up the styling */
				UpFrontSkinOption::set('merged-default-design-data-core', true, 'general');

			/* Set wrapper defaults */
				if ( !empty($skin['wrapper-defaults']) && is_array($skin['wrapper-defaults']) ) {

					UpFrontSkinOption::set('columns', upfront_get('columns', $skin['wrapper-defaults'], UpFrontWrappers::$default_columns));
					UpFrontSkinOption::set('columns-width', upfront_get('columns', $skin['wrapper-defaults'], UpFrontWrappers::$default_columns));
					UpFrontSkinOption::set('gutter-width', upfront_get('columns', $skin['wrapper-defaults'], UpFrontWrappers::$default_columns));

				}

			return $skin;

		}



		public static function process_install_skin(array $skin) {

			return UpFrontDataSnapshots::process_rollback($skin, $skin['id']);

		}


	public static function export_block_settings($block_id) {

		/* Set up variables */
			$block = UpFrontBlocksData::get_block($block_id);

		/* Check if block exists */
			if ( !$block )
				die('Fehler: Blockeinstellungen konnten nicht exportiert werden.');

		/* Spit the file out */
			return self::to_json('Block Einstellungen - ' . UpFrontBlocksData::get_block_name($block), 'block-settings', array(
				'id' => $block_id,
				'type' => $block['type'],
				'settings' => $block['settings'],
				'styling' => UpFrontBlocksData::get_block_styling($block)
			));

	}


	public static function export_wrapper_settings($wrapper_id) {

		/* Set up variables */
			$wrapper = UpFrontWrappersData::get_wrapper($wrapper_id);

		/* Check if block exists */
			if ( !$wrapper )
				die('Fehler: Container-Einstellungen konnten nicht exportiert werden.');

		/* Spit the file out */
			return self::to_json('Container Einstellungen - ' . $wrapper['id'], 'wrapper-settings', array(
				'id' => $wrapper['id'],
				'settings' => $wrapper['settings']
			));

	}


	public static function export_layout($layout_id) {

		/* Set up variables */
			if ( !$layout_name = UpFrontLayout::get_name($layout_id) )
				die('Error: Invalid layout.');

			$layout = array(
				'name' => $layout_name,
				'blocks' => UpFrontBlocksData::get_blocks_by_layout($layout_id, false, true),
				'wrappers' => UpFrontWrappersData::get_wrappers_by_layout($layout_id, true)
			);

		/* Spit the file out */
		return self::to_json('UpFront Layout - ' . $layout_name, 'layout', $layout);

	}

	/**
	 *
	 * Allow convert Headway Skins to UpFront
	 *
	 */	
	public static function convert_skin_hw_to_upfront($hwskin){

		$upfrontSkin 	= array();

		$upfrontSkin['pu-version'] 			= UPFRONT_VERSION;
		$upfrontSkin['hw-version'] 			= $hwskin['hw-version'];
		$upfrontSkin['name'] 					= $hwskin['name'];
		$upfrontSkin['author'] 				= $hwskin['author'];
		$upfrontSkin['image-url'] 			= $hwskin['image-url'];
		$upfrontSkin['version'] 				= $hwskin['version'];
		$upfrontSkin['data_wp_options'] 		= self::data_serialize($hwskin['data_wp_options']);
		$upfrontSkin['data_wp_postmeta'] 		= self::data_serialize($hwskin['data_wp_postmeta']);
		$upfrontSkin['data_pu_layout_meta'] 	= self::data_serialize($hwskin['data_hw_layout_meta']);
		$upfrontSkin['data_pu_wrappers'] 		= self::data_serialize($hwskin['data_hw_wrappers']);
		$upfrontSkin['data_pu_blocks'] 		= self::data_serialize($hwskin['data_hw_blocks']);
		$upfrontSkin['data-type'] 			= $hwskin['data-type'];
		$upfrontSkin['imported-images'] 		= $hwskin['imported-images'];
		$upfrontSkin['id'] 					= $hwskin['id'];

		return $upfrontSkin;
	}


	/**
	 *
	 * Allow convert Blox Skins to UpFront
	 *
	 */	
	public static function convert_skin_blox_to_upfront($bloxSkin){

		$upfrontSkin 	= array();

		$upfrontSkin['pu-version'] 			= '0.0.17'; // First UpFront version to support skins well
		$upfrontSkin['bt-version'] 			= $bloxSkin['bt-version'];
		$upfrontSkin['name'] 					= $bloxSkin['name'];
		$upfrontSkin['author'] 				= $bloxSkin['author'];
		$upfrontSkin['image-url'] 			= $bloxSkin['image-url'];
		$upfrontSkin['version'] 				= $bloxSkin['version'];
		$upfrontSkin['data_wp_options'] 		= self::data_serialize($bloxSkin['data_wp_options']);
		$upfrontSkin['data_wp_postmeta'] 		= self::data_serialize($bloxSkin['data_wp_postmeta']);
		$upfrontSkin['data_pu_layout_meta'] 	= self::data_serialize($bloxSkin['data_bt_layout_meta']);
		$upfrontSkin['data_pu_wrappers'] 		= self::data_serialize($bloxSkin['data_bt_wrappers']);
		$upfrontSkin['data_pu_blocks'] 		= self::data_serialize($bloxSkin['data_bt_blocks']);
		$upfrontSkin['data-type'] 			= $bloxSkin['data-type'];
		$upfrontSkin['imported-images'] 		= $bloxSkin['imported-images'];
		$upfrontSkin['id'] 					= $bloxSkin['id'];

		return $upfrontSkin;
	}



	/**
	 *
	 * String Headway/Blox replace
	 *
	 */	
	private static function convert_skin_string_replace($string){

        $search_for = array(

        	// Headway
            '/(hw)/',
            '/(headway\_)/',
            '/(\/headway\/)/',
            '/(\-headway\-)/',

            // Bloxtheme
            '/(bt)/',
            '/(bloxtheme\_)/',
            '/(\/bloxtheme\/)/',
            '/(\-bloxtheme\-)/',
        );

        $replace_for = array(

            'pu',
            'pu_',
            '/upfront/',
            '-upfront-',

            'pu',
            'upfront_',
            '/upfront/',
            '-upfront-',

        );

        return preg_replace($search_for, $replace_for, $string);

    }


	private static function data_serialize($data){

        if(is_object($data))
            $data = (array)$data;

        if(is_serialized($data))
            $data = unserialize($data);

        if(is_array($data)){

            $new_data = array();
            foreach ($data as $key => $value) {                
                $new_key            = self::convert_skin_string_replace($key);
                $new_data[$new_key] = self::data_serialize($value);
            }
            return $new_data;

        }else{        	
            return self::convert_skin_string_replace($data);                
        }

    }
	/**
	 * Convert array to JSON file and force download.
	 *
	 * Images will be converted to base64 via UpFrontDataPortability::encode_images()
	 **/
	public static function to_json($filename, $data_type = null, $array, $ret = false) {

		if ( !$array['data-type'] = $data_type )
			die('Missing data type for UpFrontDataPortability::to_json()');

		$array['image-definitions'] = self::encode_images($array);

		header('Content-Disposition: attachment; filename="' . $filename . '.json"');
		header('Content-Type: application/json');
		header('Pragma: no-cache');

		if($ret === false){
			echo json_encode($array);
			return $filename;
		}else{
			return json_encode($array);
		}


	}


		/**
		 * Convert all images to base64.
		 *
		 * This method is recursive.
		 **/
		public static function encode_images(&$array, $images = null) {

			if ( !$images )
				$images = array();

			foreach ( $array as $key => $value ) {

				$is_serialized = is_serialized($value);

				if ( is_array($value) || $is_serialized ) {

					if ( $is_serialized ) {

						$value = upfront_maybe_unserialize($value);

						if ( !is_array($value) ) {
							continue;
						}

						$array[$key] = $value;

					}

					$images = array_merge($images, self::encode_images($array[$key], $images));

					continue;

				} else if ( is_string($value) ) {

					$image_matches = array();

					/* PREG_SET_ORDER makes the $image_matches array make more sense */
					preg_match_all('/([a-z\-_0-9\/\:\.]*\.(jpg|jpeg|png|gif))/i', $value, $image_matches, PREG_SET_ORDER);

					/* Go through each image in the string and download it then base64 encode it and replace the URL with variable */
					foreach ( $image_matches as $image_match ) {

						if ( !count($image_match) )
							continue;

						$image_request = wp_remote_get($image_match[0]);

						if ( $image_request && $image_contents = wp_remote_retrieve_body($image_request) ) {

							$image = array(
								'base64_contents' => base64_encode($image_contents),
								'mime_type' => $image_request['headers']['content-type']
							);

							/* Add base64 encoded image to image definitions. */
								/* Make sure that the image isn't already in the definitions.  If it is, $possible_duplicate will be the key/ID to the image */
								if ( !$possible_duplicate = array_search($image, $images) )
									$images['%%IMAGE_REPLACEMENT_' . (count($images) + 1) . '%%'] = $image;

							/* Replace the URL with variable that way it can be replaced with uploaded image on import.  If $possible_duplicate isn't null/false, then use it! */
								$variable = $possible_duplicate ? $possible_duplicate : '%%IMAGE_REPLACEMENT_' . (count($images)) . '%%';
								$array[$key] = str_replace($image_match[0], $variable, $array[$key]);

						}

					}

				}

			}

			return $images;

		}


	/**
	 * Convert base64 encoded image into a file and move it to proper WP uploads directory.
	 **/
	public static function decode_image_to_uploads($base64_string) {

		/* Make sure user has permissions to edit in the Visual Editor */
			if ( !UpFrontCapabilities::can_user_visually_edit() )
				return;

		/* Create a temporary file and decode the base64 encoded image into it */
			$temporary_file = wp_tempnam();
			file_put_contents($temporary_file, base64_decode($base64_string));

		/* Use wp_check_filetype_and_ext() to figure out the real mimetype of the image.  Provide a bogus extension and then we'll use the 'proper_filename' later. */
			$filename = 'upfront-imported-image.jpg';
			$file_information = wp_check_filetype_and_ext($temporary_file, $filename);

		/* Construct $file array which is similar to a PHP $_FILES array.  This array must be a variable since wp_handle_sideload() requires a variable reference argument. */
			if ( upfront_get('proper_filename', $file_information) )
				$filename = $file_information['proper_filename'];

			$file = array(
				'name' => $filename,
				'tmp_name' => $temporary_file
			);

		/* Let WordPress move the image and spit out the file path, URL, etc.  Set test_form to false that way it doesn't verify $_POST['action'] */
			$upload = wp_handle_sideload($file, array('test_form' => false));

			/* If there's an error, be sure to unlink/delete the temporary file in case wp_handle_sideload() doesn't. */
			if ( isset($upload['error']) )
				@unlink($temporary_file);

			return $upload;

	}


}