<?php
class UpFrontLayoutRenderer {


	private $id;
	private $blocks;
	private $wrappers;


	public function __construct() {

		$this->id 		= UpFrontLayout::get_current_in_use();
		$this->blocks 	= UpFrontBlocksData::get_blocks_by_layout($this->id);		
		$this->wrappers = UpFrontWrappersData::get_wrappers_by_layout($this->id);

	}


	public function display() {

		if ( !$this->blocks )
			return $this->display_no_blocks_message();



		/*	Add Animation rules to layout?	*/		
		$animation_rules = array();

		foreach ( $this->wrappers as $wrapper_id => $wrapper ) {

			$wrapper_id_for_blocks 	= $wrapper_id;
			$wrapper_settings 		= upfront_get('settings', $wrapper, array());

			/* Check if mirroring.  If mirroring, change wrapper ID to the wrapper being mirrored and preserve original ID for a later class */
			if ( $wrapper_being_mirrored = UpFrontWrappersData::get_wrapper_mirror($wrapper) ) {

				$mirrored_wrapper_id 	= $wrapper_being_mirrored['id'];
				$wrapper_id_for_blocks 	= $mirrored_wrapper_id;

				foreach ( UpFrontBlocksData::get_blocks_by_wrapper($wrapper_being_mirrored['layout'], $mirrored_wrapper_id) as $block_from_mirrored_wrapper ){
					$this->blocks[$block_from_mirrored_wrapper['id']] = $block_from_mirrored_wrapper;
				}

			}

			/* Grab blocks belonging to this wrapper */
			$wrapper_blocks = array();

			foreach ( $this->blocks as $block_id => $block ) {

				if ( upfront_get('wrapper_id', $block, UpFrontWrappers::$default_wrapper_id) == $wrapper_id_for_blocks )
					$wrapper_blocks[$block_id] = $block;

				/* If there's only one wrapper and the block does not have a proper ID or is default, move it to that wrapper */
				if ( count($this->wrappers) === 1 && (upfront_get('wrapper_id', $block) === null || upfront_get('wrapper_id', $block) == 'wrapper-default' || !isset($this->wrappers[upfront_get('wrapper_id', $block)])) )
					$wrapper_blocks[$block_id] = $block;


				// Add animation rules ?
				if( !empty($block['settings']['animation-rules']) ){					
					foreach ($block['settings']['animation-rules'] as $key => $value) {
						$animation_rules[$key] = $value;
					}					
				}

			}

			/* Setup wrapper classes */
			$wrapper_id    			= UpFrontWrappersData::get_legacy_id( $wrapper );
			$wrapper['original-id'] = $wrapper['id'];
			$wrapper['id'] 			= UpFrontWrappersData::get_legacy_id( $wrapper );


			$wrapper_columns 		= UpFrontWrappers::get_columns($wrapper);
			$wrapper_column_width 	= UpFrontWrappers::get_column_width($wrapper);
			$wrapper_gutter_width 	= UpFrontWrappers::get_gutter_width($wrapper);

			$wrapper_classes 		= array('wrapper');

			$wrapper_classes[] 		= UpFrontWrappers::is_independent_grid($wrapper) ? 'independent-grid' : null;
			$wrapper_classes[] 		= UpFrontWrappers::is_fluid($wrapper) ? 'wrapper-fluid' : 'wrapper-fixed';
			$wrapper_classes[] 		= UpFrontWrappers::is_grid_fluid($wrapper) ? 'wrapper-fluid-grid' : 'wrapper-fixed-grid';
			$wrapper_classes[] 		= 'grid-' . (UpFrontWrappers::is_grid_fluid($wrapper) || UpFrontResponsiveGrid::is_enabled() ? 'fluid' : 'fixed') . '-' . $wrapper_columns . '-' . $wrapper_column_width . '-' . $wrapper_gutter_width;

			$wrapper_classes[] 		= UpFrontResponsiveGrid::is_active() ? 'responsive-grid' : null;
			$wrapper_classes[] 		= $wrapper_being_mirrored ? 'wrapper-mirroring-' . UpFrontWrappersData::get_legacy_id($wrapper_being_mirrored) : null;

			$last_wrapper_id 		= array_slice(array_keys($this->wrappers), -1, 1);
			$last_wrapper_id 		= $last_wrapper_id[0];

			$first_wrapper_id 		= array_keys($this->wrappers);
			$first_wrapper_id 		= $first_wrapper_id[0];

			if ( $last_wrapper_id == $wrapper['original-id'] )
				$wrapper_classes[] = 'wrapper-last';
			else if ( $first_wrapper_id == $wrapper['original-id'] )
				$wrapper_classes[] = 'wrapper-first';

			/* Custom wrapper classes */
			$custom_css_classes 	= str_replace('  ', ' ', str_replace(',', ' ', esc_attr(strip_tags(upfront_get('css-classes', $wrapper_settings, '')))));
			$wrapper_classes 		= array_merge($wrapper_classes, explode(' ', $custom_css_classes));

			/* Visual Editor Attributes */
			$wrapper_visual_editor_attributes = '';

			if ( UpFrontRoute::is_visual_editor_iframe() ) {
				$wrapper_visual_editor_attributes = ' data-id="' . $wrapper['original-id'] . '" data-custom-classes="' .  trim($custom_css_classes) . '"';
			}

			/* Display the wrapper */	
			do_action('upfront_before_wrapper');

			echo '<div id="wrapper-' . $wrapper_id . '" class="' . implode(' ', array_unique(array_filter($wrapper_classes))) . '" data-alias="' . esc_attr( upfront_get( 'alias', upfront_get( 'settings', $wrapper, array() )) ) . '"' . $wrapper_visual_editor_attributes . '>';

					do_action('upfront_wrapper_open');

						$wrapper = new UpFrontGridRenderer($wrapper_blocks, $wrapper_settings);
						$wrapper->render_grid();

					do_action('upfront_wrapper_close');

				echo '</div>';

				do_action('upfront_after_wrapper');
			/* End displaying wrapper */

		}



		if( !empty( $animation_rules ) ){
					
			wp_enqueue_script( 'upfront-animation-rules', upfront_url() . '/library/media/js/animation-rules.js', array( 'jquery' ) );
			wp_localize_script( 'upfront-animation-rules', 'UpFrontAnimationRulesSelectors', $animation_rules );

		}

	}


	private function display_no_blocks_message() {

		echo '<div class="wrapper wrapper-no-blocks wrapper-fixed" id="wrapper-default">';

			echo '<div class="block-type-content">';

				echo '<div class="entry-content">';

					echo '<h1 class="entry-title">' . __('Kein Inhalt zum Anzeigen', 'upfront') . '</h1>';

					$visual_editor_url = add_query_arg(array('visual-editor' => 'true', 'visual-editor-mode' => 'grid', 've-layout' => urlencode(UpFrontLayout::get_current())), home_url());

					if ( UpFrontCapabilities::can_user_visually_edit() ) {

						echo sprintf(__('<p>Es sind keine Blöcke zum anzeigen vorhanden. Füge einige hinzu, indem Du zum <a href="%s">UpFront Gitter</a> gehst!</p>', 'upfront'), $visual_editor_url);

					} else {

						echo sprintf(__('<p>Es ist kein Inhalt anzuzeigen. Bitte benachrichtige den Webseiten-Administrator oder <a href="%s">anmelden</a>.</p>', 'upfront'), $visual_editor_url);

					}

				echo '</div>';

			echo '</div>';

		echo '</div>';

		return false;

	}


}