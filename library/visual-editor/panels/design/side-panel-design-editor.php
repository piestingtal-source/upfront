<?php
class UpFrontSidePanelDesignEditor {


	public static function init() {

		if ( UpFrontVisualEditor::get_current_mode() != 'design' )
			return false;

		add_action('upfront_visual_editor_side_panel', array(__CLASS__, 'template'));

		add_action('upfront_visual_editor_footer', array(__CLASS__, 'live_css_textarea'));

	}


	public static function live_css_textarea() {

		echo '<textarea id="live-css-content" name="live-css" data-group="general" style="display:none;">' . esc_textarea(UpFrontSkinOption::get('live-css', false, null, false, false)) . '</textarea>';

	}


	public static function template() {

		echo '<div id="side-panel-top">';
			self::element_selector();
		echo '</div><!-- #side-panel-top -->';

		echo '<div id="side-panel-bottom">';
			self::editor();
		echo '</div><!-- #side-panel-bottom -->';

	}


	public static function element_selector() {

		echo '
			<ul id="element-selector-tabs">
				<li><a href="#design-editor-element-selector-container">Navigator</a></li>
				<li><a href="#design-editor-styles-container">Styles</a></li>

				<span id="side-panel-collapse-arrow" title="' . __('Design Editor umschalten', 'upfront') . '" class="tooltip-top-right"></span>
			</ul>
		';

		echo '<div id="design-editor-element-selector-container">';

			echo '<ul id="design-editor-element-selector">';

			echo '</ul><!-- #design-editor-element-selector -->';

			echo '<span class="button button-blue" id="element-selector-show-all-elements">' . __('Alle Elemente anzeigen', 'upfront') . '</span>';
			echo '<span class="button" id="element-selector-show-current-layout-elements">' . __('Aktuelle Layoutelemente anzeigen', 'upfront') . '</span>';

		echo '</div><!-- #design-editor-element-selector-container -->';


		echo '<div id="design-editor-styles-container">';


			echo '<div id="design-editor-styles-nothing-selected" class="design-editor-styles-message">';

				echo '<p>' . __('Du hast <strong>kein Element</strong> zum Bearbeiten ausgewählt.', 'upfront') . '</p>';
				echo '<p>' . __('Verwende den Inspektor, um ein Element zu untersuchen, das Du bearbeiten möchtest.', 'upfront') . '</p>';

				// Pending update docs
				//echo '<a href="http://docs.upfrontunlimited.com/article/49-the-inspector" target="_blank">Learn more about the inspector</a>';

			echo '</div><!-- #design-editor-styles-nothing-selected -->';


			echo '<div id="design-editor-styles-no-styles" class="design-editor-styles-message">';

				echo '<p>' . __('Dieses Element hat keine benutzerdefinierten Eigenschaften oder Instanzen.', 'upfront') . '</p>';

			echo '</div><!-- #design-editor-styles-nothing-selected -->';


			echo '<ul id="design-editor-styles">';

			echo '</ul><!-- #design-editor-styles -->';

		echo '</div><!-- #design-editor-styles-container -->';

	}


	public static function editor() {

		echo '
			<div class="design-editor-info" style="display: none;">
					<div class="design-editor-selection">
						<strong>' . __('Bearbeitung:', 'upfront') . '</strong>

						<span class="design-editor-selection-details">
							<strong class="design-editor-selected-element"></strong>
							für <strong class="design-editor-selection-details-layout">alle Layouts</strong>
							<span class="design-editor-selection-details-state-container"><span class="design-editor-selection-details-state-before"></span> <strong class="design-editor-selection-details-state"></strong></span>
						</span>

						<span class="button button-small design-editor-info-button customize-element-for-layout">' . __('An das aktuelle Layout anpassen', 'upfront') . '</span>
						<span class="button button-small design-editor-info-button customize-for-regular-element">' . __('Passe das reguläre Element an', 'upfront') . '</span>
					</div>
				</div><!-- .design-editor-info -->

			<div class="design-editor-options-filter">
				<input type="text" id="options-filter" placeholder="Filter" title="Filter options">
				<a class="options-filter-reset"><span>x</span></a>
				<a class="options-filter-only-modified">' . __('Nur geänderte Optionen anzeigen', 'upfront') . '<input type="checkbox" id="options-filter-only-modified"></a>
			</div>
			<div class="design-editor-options-container">

				<div class="design-editor-options" style="display:none;"></div><!-- .design-editor-options -->

			</div><!-- .design-editor-options-container -->
		';

	}

}