<?php
upfront_register_visual_editor_box('UpFrontGridManagerBox');
class UpFrontGridManagerBox extends UpFrontVisualEditorBoxAPI {

	/**
	 *	Slug/ID of panel.  Will be used for HTML IDs and whatnot.
	 **/
	protected $id = 'grid-manager';


	/**
	 * Name of panel.  This will be shown in the title.
	 **/
	protected $title;

	protected $description;


	/**
	 * Which mode to put the panel on.
	 **/
	protected $mode = 'grid';

	protected $center = false;

	protected $width = 600;

	protected $height = 450;

	protected $closable = true;

	protected $draggable = false;

	protected $resizable = false;

	protected $black_overlay = true;

	protected $black_overlay_opacity = 0.3;

	protected $black_overlay_iframe = true;

	protected $load_with_ajax = true;

	protected $load_with_ajax_callback = 'afterGridManagerLoad();';


	function __construct(){
		$this->title = __('Gitter Manager', 'upfront');
		$this->description = __('Wähle eine Voreinstellung oder eine Seite zum Klonen', 'upfront');
	}
	public function content() {

		$current_layout = upfront_post('layout');

		$pages_to_clone_select_options = self::clone_pages_options();
		$templates_to_assign_select_options = self::templates_to_assign_select_options();

?>
		<ul id="grid-manager-tabs" class="tabs">
			<?php			
			if ( $pages_to_clone_select_options !== '' || $templates_to_assign_select_options !== '' ) {

				echo '<li><a href="#grid-manager-tab-clone-page">Vorhandenes Layout klonen</a></li>';
				echo '<li><a href="#grid-manager-tab-presets">Vorlagen</a></li>';

			} else {

				echo '<li><a href="#grid-manager-tab-presets">Vorlagen</a></li>';

			}

			if ( $templates_to_assign_select_options !== '' && strpos($current_layout, 'template-') === false ){
				echo '<li><a href="#grid-manager-tab-assign-template">Verwende freigegebenes Layout</a></li>';
			}

			echo '<li><a href="#grid-manager-tab-import-export">Import/Export</a></li>';
			?>
		</ul>

		<div id="grid-manager-tab-presets" class="tab-content">

			<div id="grid-manager-presets-step-1">	
				<div class="grid-manager-presets-row">
					<span class="layout-preset layout-preset-selected" id="layout-right-sidebar" title="Inhalt | Seitenleiste">
						<img src="<?php echo upfront_url() . '/library/visual-editor/images/layouts/layout-right-sidebar.png'; ?>" alt="" />
					</span>

					<span class="layout-preset" id="layout-left-sidebar" title="Seitenleiste | Inhalt">
						<img src="<?php echo upfront_url() . '/library/visual-editor/images/layouts/layout-left-sidebar.png'; ?>" alt="" />
					</span>

					<span class="layout-preset" id="layout-two-right" title="Inhalt | Seitenleiste 1 | Seitenleiste 2">
						<img src="<?php echo upfront_url() . '/library/visual-editor/images/layouts/layout-two-right.png'; ?>" alt="" />
					</span>
				</div>

				<div class="grid-manager-presets-row">
					<span class="layout-preset" id="layout-two-both" title="Seitenleiste 1 | Inhalt | Seitenleiste 2">
						<img src="<?php echo upfront_url() . '/library/visual-editor/images/layouts/layout-two-both.png'; ?>" alt="" />
					</span>

					<span class="layout-preset" id="layout-all-content" title="Inhalt">
						<img src="<?php echo upfront_url() . '/library/visual-editor/images/layouts/layout-all-content.png'; ?>" alt="" />
					</span>
				</div>
			</div><!-- #grid-manager-presets-step-1 -->

			<div id="grid-manager-presets-step-2">

				<h4>Select Which Blocks to Mirror</h4>

				<p class="grid-manager-info">Um Zeit zu sparen, kannst Du mit UpFront Deine Blöcke "spiegeln". Wenn Du bereits einen Widget-Bereich oder eine Seitenleiste konfiguriert hast, kannst Du diese mithilfe der folgenden Auswahlfelder verwenden.</p>

				<div id="grid-manager-presets-mirroring-column-1" class="grid-manager-presets-mirroring-column">
					<div id="grid-manager-presets-mirroring-select-header">
						<h5>Header</h5>

						<div class="select-container">
							<select>
								<option value="">&mdash; Nicht spiegeln &mdash;</option>
								<?php
								echo self::get_blocks_select_options_for_mirroring('header');
								?>
							</select>
						</div><!-- .select-container -->
					</div>

					<div id="grid-manager-presets-mirroring-select-navigation">
						<h5>Navigation</h5>

						<div class="select-container">
							<select>
								<option value="">&mdash; Nicht spiegeln &mdash;</option>
								<?php
								echo self::get_blocks_select_options_for_mirroring('navigation');
								?>
							</select>
						</div><!-- .select-container -->
					</div>

					<div id="grid-manager-presets-mirroring-select-content">
						<h5>Content</h5>

						<div class="select-container">
							<select>
								<option value="">&mdash; Nicht spiegeln &mdash;</option>
								<?php
								echo self::get_blocks_select_options_for_mirroring('content');
								?>
							</select>
						</div><!-- .select-container -->
					</div>
				</div>

				<div id="grid-manager-presets-mirroring-column-2" class="grid-manager-presets-mirroring-column">
					<div id="grid-manager-presets-mirroring-select-sidebar-1">
						<h5>Sidebar 1</h5>

						<div class="select-container">
							<select>
								<option value="">&mdash; Nicht spiegeln &mdash;</option>
								<?php
								echo self::get_blocks_select_options_for_mirroring('widget-area');
								?>
							</select>
						</div><!-- .select-container -->
					</div>

					<div id="grid-manager-presets-mirroring-select-sidebar-2">
						<h5>Sidebar 2</h5>

						<div class="select-container">
							<select>
								<option value="">&mdash; Nicht spiegeln &mdash;</option>
								<?php
								echo self::get_blocks_select_options_for_mirroring('widget-area');
								?>
							</select>
						</div><!-- .select-container -->
					</div>

					<div id="grid-manager-presets-mirroring-select-footer">
						<h5>Footer</h5>

						<div class="select-container">
							<select>
								<option value="">&mdash; Nicht spiegeln &mdash;</option>
								<?php
								echo self::get_blocks_select_options_for_mirroring('footer');
								?>
							</select>
						</div><!-- .select-container -->
					</div>
				</div>

			</div><!-- #grid-manager-presets-step-2 -->

			<div class="grid-manager-buttons">
				<span class="grid-manager-use-empty-grid">Verwende leeres Gitter</span>

				<?php
				if ( $pages_to_clone_select_options !== '' ) {

					$next_button_style = null;
					$use_button_style = ' style="display: none;"';

				} else {

					$next_button_style = ' style="display: none;"';
					$use_button_style = null;

				}

				echo '<span id="grid-manager-button-preset-next" class="button grid-manager-button-next"' . $next_button_style . '>Nächste &rarr;</span>';
				echo '<span id="grid-manager-button-preset-use-preset" class="button grid-manager-button-next"' . $use_button_style . '>Fertigstellen &rarr;</span>';
				echo '<span id="grid-manager-button-preset-previous" class="button grid-manager-button-previous" style="display: none;">&larr; Vorherige</span>';
				?>
			</div>

		</div><!-- #grid-manager-tab-presets -->

		<?php
		if ( $pages_to_clone_select_options !== '' || $templates_to_assign_select_options !== '' ) {
		?>
		<div id="grid-manager-tab-clone-page" class="tab-content">

			<h4>Wähle ein zu klonendes Layout</h4>

			<?php
			echo '<div class="select-container"><select id="grid-manager-pages-to-clone">';

				echo '<optgroup label="&mdash; Seiten &mdash;">';

				echo $pages_to_clone_select_options;

                echo '</optgroup>';

                echo '<optgroup label="&mdash; Gemeinsame Layouts &mdash;">';

				echo $templates_to_assign_select_options;

                echo '</optgroup>';

            echo '</select></div><!-- .select-container -->';
			?>

			<div class="grid-manager-buttons">
				<span class="grid-manager-use-empty-grid">Verwende leeres Gitter</span>

				<span id="grid-manager-button-clone-page" class="button grid-manager-button-next">Layout klonen &rarr;</span>
			</div>

		</div><!-- #grid-manager-tab-clone-page -->
		<?php
		}


		if ( $templates_to_assign_select_options !== '' && strpos($current_layout, 'template-') === false ) {
		?>
		<div id="grid-manager-tab-assign-template" class="tab-content">

			<h4>Choose a Shared Layout</h4>

			<?php
			echo '<div class="select-container"><select id="grid-manager-assign-template">';

				echo '<option value="" disabled="disabled">&mdash; Wähle gemeinsames Layout &mdash;</option>';

				echo $templates_to_assign_select_options;

			echo '</select></div><!-- .select-container -->';
			?>

			<div class="grid-manager-buttons">
				<span class="grid-manager-use-empty-grid">Verwende leeres Gitter</span>

				<span id="grid-manager-button-assign-template" class="button grid-manager-button-next">Layout zuweisen &rarr;</span>
			</div>

		</div><!-- #grid-manager-tab-assign-template -->
		<?php
		}
		?>

		<div id="grid-manager-tab-import-export" class="tab-content">

			<div id="grid-manager-import" class="grid-manager-buttons grid-manager-import-export-group">
				<h4>Layout importieren</h4>
				<p>Wähle die UpFront-Layoutdatei aus, die Du importieren möchtest.<br /><br /><strong>Note:</strong> Wenn Du zu einer Datei unterhalb der importierten Layoutblöcke navigierst und diese auswählst, werden sie automatisch zum aktuellen Layout hinzugefügt.</p>
				<input type="file" />
				<span class="button" id="grid-manager-import-select-file">Datei wählen &amp; Importieren</span>
			</div><!-- #grid-manager-import -->

			<div id="grid-manager-export" class="grid-manager-buttons grid-manager-import-export-group">
				<h4>Aktuelles Layout exportieren</h4>
				<p>Durch Klicken auf die Schaltfläche unten werden das aktuelle Layout und seine Blöcke in eine Datei gepackt, die später gespeichert und importiert werden soll.</p>
				<span class="button" id="grid-manager-export-download-file">Exportdatei herunterladen</span>
			</div><!-- #grid-manager-export -->

		</div><!-- #grid-manager-tab-import-export -->

	<?php
	}


	static function get_blocks_select_options_for_mirroring($block_type) {

		$return = '';	

		$blocks = UpFrontBlocksData::get_blocks_by_type($block_type);

		//If there are no blocks, then just return the Nicht spiegeln option.
		if ( !isset($blocks) || !is_array($blocks) )
			return $return;

		foreach ( $blocks as $block_id => $block ) {

			//Get the block instance
			$block = UpFrontBlocksData::get_block($block_id);

			//If the block is mirrored, skip it
			if ( upfront_get('mirror-block', $block['settings'], false) )
				continue;

			//If the block is in the same layout as the current block, then do not allow it to be used as a block to mirror.
			if ( $block['layout'] == upfront_post('layout') )
				continue;

			//Create the default name by using the block type and ID
			$default_name = UpFrontBlocks::block_type_nice($block['type']);

			//If we can't get a name for the layout, then things probably aren't looking good.  Just skip this block.
			if ( !($layout_name = UpFrontLayout::get_name($block['layout'])) )
				continue;

			//Get alias if it exists, otherwise use the default name
			$return .= '<option value="' . $block['id'] . '">' . upfront_get('alias', $block['settings'], $default_name) . ' &ndash; ' . $layout_name . '</option>';  

		}

		return $return;

	}


	static function clone_pages_options() {

		$return = '';

		if ( !$customized_layouts = get_transient( 'pu_customized_layouts_template_' . UpFrontOption::$current_skin ) ) {
			return $return;
		}

		foreach ( $customized_layouts as $id ) {

			$name_prefix = UpFrontLayout::get_layout_parents_names($id);

			$return .= '<option value="' . $id . '">' . $name_prefix . UpFrontLayout::get_name($id) . '</option>';

		}

		return $return;		

	}


	static function templates_to_assign_select_options() {

		$templates = UpFrontLayout::get_templates();

		$return = '';

		foreach ( $templates as $id => $name) {

			$return .= '<option value="template-' . $id . '">' . $name . '</option>';

		}

		return $return;

	}


}