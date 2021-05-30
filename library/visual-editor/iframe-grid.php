<?php
class UpFrontVisualEditorIframeGrid {


	public static function display_grid_blocks($blocks, $wrapper) {

		echo '<div class="grid-container">';

			if ( is_array($blocks) ) {

				foreach ($blocks as $block_id => $block) {

					UpFrontBlocks::display_block($block, 'grid');

				}

			}

			/* Mirrored wrapper notice */
				$mirror_wrapper = UpFrontWrappersData::get_wrapper_mirror($wrapper);
				$mirror_wrapper_layout = $mirror_wrapper ? UpFrontLayout::get_name(upfront_get('layout', $mirror_wrapper)) : null;
				$mirror_wrapper_alias = upfront_get('alias', $mirror_wrapper) ? '(' . upfront_get('alias', $mirror_wrapper) . ')' : null;

				echo '<div class="wrapper-mirror-notice">
						<div>
						<h2>' . __('Wrapper gespiegelt', 'upfront') . '</h2>
						<p>' . 
						sprintf( 
							__('Dieser Wrapper spiegelt die Blöcke in einem Wrapper <span class="wrapper-mirror-notice-alias">%1$s</span> <span class="wrapper-mirror-notice-layout">von "%1$s" layout</span>', 'upfront'), 
							$mirror_wrapper_alias, 
							$mirror_wrapper_layout) 
						. '</p>
						<small>' . __('Die Spiegelung kann über die Wrapper-Optionen im Kontextmenü deaktiviert werden', 'upfront') . '</small>
						</div>
					</div><!-- .wrapper-mirror-notice -->';
			/* End mirrored wrapper notice */

		echo '</div><!-- .grid-container -->';

	}


	public static function display_canvas() {

		echo '<!DOCTYPE HTML>
		<html lang="en">

		<head>

			<meta charset="' . get_bloginfo('charset') . '" />
			<link rel="profile" href="http://gmpg.org/xfn/11" />

			<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
			<meta http-equiv="cache-control" content="no-cache" />

			<title>Visual Editor Gitter: ' . wp_title(false, false) . '</title>';

			do_action('upfront_grid_iframe_head');

		echo '</head><!-- /head -->

		<body class="visual-editor-iframe-grid ' . join(' ', get_body_class()) . '">';

			$wrappers = UpFrontWrappersData::get_wrappers_by_layout(UpFrontLayout::get_current_in_use());
			$blocks = UpFrontBlocksData::get_blocks_by_layout(UpFrontLayout::get_current_in_use());

			echo '<div id="whitewrap">';

			foreach ( $wrappers as $wrapper_id => $wrapper ) {

				/* Setup wrapper classes */
					$wrapper_settings = upfront_get('settings', $wrapper, array());
					$wrapper_classes = array('wrapper');

					$wrapper_classes[] = UpFrontWrappers::is_independent_grid($wrapper) ? 'independent-grid' : null;
					$wrapper_classes[] = UpFrontWrappers::is_fluid($wrapper) ? 'wrapper-fluid' : 'wrapper-fixed';
					$wrapper_classes[] = UpFrontWrappers::is_grid_fluid($wrapper) ? 'wrapper-fluid-grid' : 'wrapper-fixed-grid';

					if ( UpFrontWrappersData::is_wrapper_mirrored($wrapper) )
						$wrapper_classes[] = 'wrapper-mirrored';

				/* Populate wrapper with its blocks */
					$wrapper_blocks = array();

					foreach ( $blocks as $block_id => $block ) {

						/* Grab blocks belonging to this wrapper */
						if ( upfront_get('wrapper_id', $block, UpFrontWrappers::$default_wrapper_id) == $wrapper_id )
							$wrapper_blocks[$block_id] = $block;

						/* If last wrapper, grab all blocks on this layout with invalid wrapper IDs to make sure they're editable somewhere */
						$last_wrapper_id = array_slice(array_keys($wrappers), -1, 1);
						$last_wrapper_id = $last_wrapper_id[0];

						if ( $last_wrapper_id == $wrapper_id && !upfront_get(upfront_get('wrapper_id', $block, UpFrontWrappers::$default_wrapper_id), $wrappers) )
							$wrapper_blocks[$block_id] = $block;

					}

				/* Output the wrapper */
				echo '<div id="wrapper-' . UpFrontWrappers::format_wrapper_id($wrapper_id) . '" class="' . implode(' ', array_filter($wrapper_classes)) . '" data-wrapper-settings="' . esc_attr(json_encode($wrapper_settings)) . '" data-id="' . UpFrontWrappers::format_wrapper_id($wrapper_id) . '" data-alias="' . esc_attr(stripslashes(upfront_get('alias', $wrapper_settings))) . '">';

					echo '<div class="wrapper-mirror-overlay"></div><!-- .wrapper-mirror-overlay -->';

					self::display_grid_blocks($wrapper_blocks, $wrapper);

				echo '</div><!-- #wrapper-' . $wrapper_id . ' -->';

			}

		echo '<div id="wrapper-buttons-template">';

			echo '<div class="wrapper-handle wrapper-top-margin-handle wrapper-margin-handle" title="' . __('Ziehen um den oberen Rand des Wrappers zu ändern', 'upfront') . '"><span></span><span></span><span></span></div>';

			echo '<div class="wrapper-handle wrapper-drag-handle" title="' . __('Ziehen um die Wrapper-Reihenfolge zu ändern', 'upfront') . '"><span></span><span></span><span></span></div>';

			echo '<div class="wrapper-handle wrapper-bottom-margin-handle wrapper-margin-handle" title="' . __('Ziehen um den unteren Rand des Wrappers zu ändern', 'upfront') . '"><span></span><span></span><span></span></div>';

			echo '<div class="wrapper-options" title="' . __('Rechtsklick für Wrapperoptionen', 'upfront') . '"><span></span></div>';

		echo '</div><!-- .wrapper-buttons -->';


		do_action('upfront_grid_iframe_footer');

		echo '</div><!-- #whitewrap -->
		</body>
		</html>';

	}


	public static function show() {

		//Prevent any type of caching on this page
		header( 'cache-control: private, max-age=0, no-cache' );

		if ( !defined('DONOTCACHEPAGE') ) {
			define('DONOTCACHEPAGE', true);
		}

		if ( ! defined( 'DONOTCACHEDB' ) ) {
			define( 'DONOTCACHEDB', true );
		}

		if ( ! defined( 'DONOTCACHCEOBJECT' ) ) {
			define( 'DONOTCACHCEOBJECT', true );
		}

		if ( !defined('DONOTMINIFY') ) { 
			define('DONOTMINIFY', true);
		}

		add_action('upfront_grid_iframe_head', array(__CLASS__, 'print_styles'), 12);
		add_action('upfront_grid_iframe_styles', array(__CLASS__, 'enqueue_canvas_assets'));

		self::display_canvas();

	}


	public static function enqueue_canvas_assets() {

		wp_enqueue_style( 'upfront-ve-iframe-grid', upfront_url() . '/library/visual-editor/css/iframe-grid.css' );
		wp_enqueue_style( 'upfront-ve-iframe-grid-night', upfront_url() . '/library/visual-editor/css/iframe-grid-night.css' );

		UpFrontCompiler::register_file(array(
			'name' => 've-iframe-grid-dynamic',
			'format' => 'css',
			'fragments' => array(
				array('UpFrontDynamicStyle', 'wrapper')
			),
			'dependencies' => array(
				UPFRONT_LIBRARY_DIR . '/media/dynamic/style.php'
			)
		));

	}


	public static function print_styles() {

		global $wp_styles;
		$wp_styles = null;

		do_action('upfront_grid_iframe_styles');

		wp_print_styles();

	}


}