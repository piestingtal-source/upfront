<?php

class UpFrontSlideDeckBlock extends UpFrontBlockAPI {


	public $id;
	public $name;
	public $options_class;
	public $description;
	public $categories;


	function __construct(){

		$this->id = 'slidedeck';	
		$this->name = __('SlideDeck 2', 'upfront');
		$this->options_class = 'UpFrontSlideDeckBlockOptions';
		$this->description = __('Füge SlideDecks bequem an einer beliebigen Stelle in einem beliebigen Layout hinzu.', 'upfront'); 
		/* This will be shown in the block type selector */
		$this->categories 	= array('core','content', 'medien');		

	}

	/** 
	 * Anything in here will be displayed when the block is being displayed.
	 **/
	function content($block) {

		global $SlideDeckPlugin;

		/* Make sure SlideDeck is activated and working */
			if ( !is_object($SlideDeckPlugin) ) {

				echo '<div class="alert alert-red"><p>' . __('SlideDeck muss installiert und aktiviert sein, damit der SlideDeck-Block ordnungsgemäß funktioniert.', 'upfront') . '</p></div>';
				return;

			}

		/* Get the chosen SlideDeck ID */
			$slidedeck_id = parent::get_setting($block, 'slidedeck-id', null);

		/* Make sure that there's a selected SlideDeck */
			if ( empty($slidedeck_id) ) {

				echo '<div class="alert alert-red"><p>' . __('Bitte wähle ein SlideDeck aus, das angezeigt werden soll.', 'upfront') . '</p></div>';
				return;

			}

			$slidedeck_query = $SlideDeckPlugin->SlideDeck->get($slidedeck_id);

			if ( empty($slidedeck_query) ) {

				echo '<div class="alert alert-red"><p>' . __('Das zuvor ausgewählte Dia-Deck muss gelöscht oder an eine andere Stelle verschoben worden sein. Bitte wähle ein anderes Dia-Deck aus, das angezeigt werden soll.') . '</p></div>';
				return;

			}

		/* Setup arguments */
			$args = array(
				'id' => $slidedeck_id,
				'width' => null,
				'height' => null
			);

			if ( parent::get_setting($block, 'use-block-size', true) ) {

				$args['width'] = UpFrontBlocksData::get_block_width($block);
				$args['height'] = UpFrontBlocksData::get_block_height($block);
				$args['proportional'] = false;

			}


			if ( UpFrontRoute::is_visual_editor_iframe() )
				$args['iframe'] = true;

			if ( !UpFrontRoute::is_visual_editor_iframe() && UpFrontResponsiveGrid::is_active() )
				$args['ress'] = true;

			/* Work around for iframe dimensions */
				$GLOBALS['slidedeck-width'] = $args['width'];
				$GLOBALS['slidedeck-height'] = $args['height'];

				add_filter('slidedeck_dimensions', array(__CLASS__, 'modify_slidedeck_iframe_size_for_ajax'), 10, 5);
			/* End work around for iframe dimensions */

		/* Show the SlideDeck! */
			echo $SlideDeckPlugin->shortcode($args);

		/* Remove any filters if necessary */
			remove_filter('slidedeck_dimensions', array(__CLASS__, 'modify_slidedeck_iframe_size_for_ajax'));

			if ( isset($GLOBALS['slidedeck-width']) )
				unset($GLOBALS['slidedeck-width']);

			if ( isset($GLOBALS['slidedeck-height']) )
				unset($GLOBALS['slidedeck-height']);
		/* End removing filters */

	}


		public static function modify_slidedeck_iframe_size_for_ajax(&$width, &$height, &$outer_width, &$outer_height, &$slidedeck) {

			$width 			= $GLOBALS['slidedeck-width'];
			$height 		= $GLOBALS['slidedeck-height'];

			$outer_width 	= $GLOBALS['slidedeck-width'];
			$outer_height 	= $GLOBALS['slidedeck-height'];

			return true;

		}


}


class UpFrontSlideDeckBlockOptions extends UpFrontBlockOptionsAPI {


	public $tabs;
	public $inputs;


	function __construct($block_type_object){

		parent::__construct($block_type_object);

		$this->tabs = array(
			'settings-tab' => __('SlideDeck', 'upfront')
		);

		$this->inputs = array(
			'settings-tab' => array(
				'slidedeck-dashboard-link' => array(
					'type' => 'notice',
					'name' => 'slidedeck-dashboard-link',
					'notice' => ''
				),
				'slidedeck-id' => array(
					'type' => 'select',
					'name' => 'slidedeck-id', //This will be the setting you retrieve from the database.
					'label' => __('Wähle ein SlideDeck zum Anzeigen', 'upfront'),
					'default' => '',
					'options' => 'get_slidedecks()',
					'tooltip' => __('Wähle das SlideDeck aus, das Du anzeigen möchtest', 'upfront'),
				),

				'use-block-size' => array(
					'type' => 'checkbox',
					'name' => 'use-block-size',
					'label' => __('Verwende die Blockgröße für SlideDeck', 'upfront'),
					'default' => true,
					'tooltip' => __('Wähle aus, ob Du die Größe des Blocks verwenden möchtest, um die Größe des SlideDecks festzulegen. Wenn Du dies nicht tust, wird die in den Einstellungen von SlideDeck definierte Größe verwendet.', 'upfront')
				)
			)
		);
	}


	function get_slidedecks() {

		global $SlideDeckPlugin;

		$slidedecks = $SlideDeckPlugin->SlideDeck->get(null, 'post_title', 'ASC', 'publish');

		$options = array(
			'' => __('&ndash; Wähle ein SlideDeck &ndash;', 'upfront')
		);

		foreach ( $slidedecks as $slidedeck )
			$options[$slidedeck['id']] = $slidedeck['title'];

		return $options;

	}


	function modify_arguments($args = false) {

		/* Since we can't call functions when declaring a property, we must put in the admin links here that way we can use admin_url() */
			$this->inputs['settings-tab']['slidedeck-dashboard-link']['notice'] = '
			    <strong>' . __('SlideDeck Quick Links:', 'upfront') . '</strong>&nbsp;
				<a href="' . admin_url('admin.php?page=' . SLIDEDECK2_BASENAME) . '" target="_blank">' . __('SlideDecks hinzufügen/verwalten', 'upfront') . '</a> | 
				<a href="' . admin_url('admin.php?page=' . SLIDEDECK2_BASENAME . '/lenses') . '" target="_blank">' . __('Lenses', 'upfront') . '</a> | 
				<a href="' . admin_url('admin.php?page=' . SLIDEDECK2_BASENAME . '/options') . '" target="_blank">' . __('Erweiterte Optionen', 'upfront') . '</a>
			';

	}


}