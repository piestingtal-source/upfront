<?php
class GridSetupPanel extends UpFrontVisualEditorPanelAPI {

	public $id = 'setup';
	public $name;
	public $mode = 'grid';	
	public $tabs;	
	public $tab_notices;	
	public $inputs;

	function __construct(){

		$this->tabs = array(
			'grid' => 'Grid',
			'responsive-grid' => __('Responsivgitter', 'upfront')
		);

		$this->name = 'Standardeinstellungen';

		$this->tab_notices = array(

			'grid' => __('<strong>Hinweis:</strong> Der Inhalt im obigen Gitter gibt nicht wieder, wie Deine Webseite tatsächlich aussieht. Der Inhalt in den Blöcken dient als allgemeine Referenz, während Du das Layout für Deine Webseite erstellst und einrichtest. <br /><br />Die folgenden Einstellungen sind <strong>global</strong> und werden nicht individuell per Layout-Basis angepasst.', 'upfront'),

			'responsive-grid' => __('Das UpFront Responsivgitter ermöglicht die Anpassung des leistungsstarken Gitters in UpFront an das Gerät, von dem aus der Besucher die Webseite anzeigt. Bitte beachte: Bei einigen Webseiten kann das Responsivgitter aktiviert sein, bei anderen nicht. Als Designer der Webseite liegt es an Dir, zu entscheiden. Das Responsivegitter kann jederzeit aktiviert oder deaktiviert werden.', 'upfront')
		);

		$this->inputs = array(
			'grid' => array(
				'columns' => array(
					'type' => 'slider',
					'name' => 'columns',
					'label' => __('Standardspaltenanzahl', 'upfront'), /* Column count is default only because you can't change it on the fly */
					'default' => 24,
					'tooltip' => __('Die Spaltenanzahl ist die Anzahl der Spalten im Gitter. Dies wird durch die grauen Bereiche im Gitter dargestellt.<br /><br /><strong>Dies wirkt sich NICHT auf bereits erstellte Wrapper aus. Dies betrifft nur Wrapper, die erstellt werden, nachdem diese Einstellung geändert wurde.</strong>', 'upfront'),
					'slider-min' => 6,
					'slider-max' => 24,
					'slider-interval' => 1,
					'callback' => 'UpFront.defaultGridColumnCount = value.toString();updateGridWidthInput($(input).parents(".sub-tabs-content"));'
				),

				'column-width' => array(
					'type' => 'slider',
					'name' => 'column-width',
					'label' => __('Globale Spaltenbreite', 'upfront'),
					'default' => 26,
					'tooltip' => __('Die Spaltenbreite ist der Platz innerhalb jeder Spalte. Dies wird durch die grauen Bereiche im Gitter dargestellt.', 'upfront'),
					'unit' => 'px',
					'slider-min' => 10,
					'slider-max' => 120,
					'slider-interval' => 1,
					'callback' => 'UpFront.globalGridColumnWidth = value.toString();$i("div.wrapper:not(.independent-grid)").each(function() { $(this).upfrontGrid("updateGridCSS"); });updateGridWidthInput($(input).parents(".sub-tabs-content"));'
				),

				'gutter-width' => array(
					'type' => 'slider',
					'name' => 'gutter-width',
					'label' => __('Globale Rinnenbreite', 'upfront'),
					'default' => 22,
					'tooltip' => __('Die Rinnenbreite ist der Abstand zwischen den einzelnen Spalten. Dies ist der Abstand zwischen den einzelnen grauen Bereichen im Raster.', 'upfront'),
					'unit' => 'px',
					'slider-min' => 0,
					'slider-max' => 60,
					'slider-interval' => 1,
					'callback' => 'UpFront.globalGridGutterWidth = value.toString();$i("div.wrapper:not(.independent-grid)").each(function() { $(this).upfrontGrid("updateGridCSS"); });updateGridWidthInput($(input).parents(".sub-tabs-content"));'
				),

				'grid-width' => array(
					'type' => 'integer',
					'unit' => 'px',
					'default' => 1130,
					'name' => 'grid-width',
					'label' => __('Globale Gitterbreite', 'upfront'),
					'readonly' => true
				)
			),

			'responsive-grid' => array(
				'enable-responsive-grid' => array(
					'type' => 'checkbox',
					'name' => 'enable-responsive-grid',
					'label' => __('Aktiviere Responsivgitter', 'upfront'),
					'default' => true,
					'tooltip' => __('Wenn das Responsivgitter von UpFront aktiviert ist, wird das Raster automatisch abhängig vom Gerät des Besuchers (Computer, iPhone, iPad usw.) angepasst. Das Aktivieren des Responsivgitter kann für einige Webseiten äußerst vorteilhaft sein, für andere Webseiten jedoch möglicherweise nicht. Wenn das Responsivgitter aktiviert ist, hat der Benutzer immer die Möglichkeit, das Responsivgitter über einen Link im Fußzeilenblock zu deaktivieren. <br /><br /><strong>Bitte beachte:</strong> mit aktiviertem Reaktionsraster die genauen Pixelbreiten von Blöcken können sich geringfügig von denen unterscheiden, wenn sie deaktiviert sind.', 'upfront')
				),

				'responsive-video-resizing' => array(
					'type' => 'checkbox',
					'name' => 'responsive-video-resizing',
					'label' => __('Responsiv Video Größenänderung', 'upfront'),
					'default' => true,
					'tooltip' => __('Wenn das Responsivgitter aktiviert ist und der Benutzer die Webseite besucht, wenn YouTube, Vimeo oder andere Videos vorhanden sind, wird die Größe der Videos nur dann ordnungsgemäß geändert, wenn dies aktiviert ist.', 'upfront')
				)
			)
		);

	}
}
upfront_register_visual_editor_panel('GridSetupPanel');