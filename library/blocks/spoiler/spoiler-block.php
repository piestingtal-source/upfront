<?php

/**
 * Spoiler Block
 */
class UpFrontBlockSpoiler extends UpFrontBlockAPI {

	
	public $id;
	public $name;
	public $options_class;
	public $description;
	public $categories;
	public function __construct() {
		$this->id            = 'visual-elements-spoiler';	
		$this->name          = __( 'Spoiler', 'upfront' );
		$this->options_class = 'UpFrontBlockSpoilerOptions';
		$this->description   = __( 'Allows you to create blocks with hidden content – spoilers (toggles). Hidden content will be shown when block title will be clicked. You can specify different icons or even use different styles for each spoiler . ', 'upfront' );
		$this->categories    = array( 'box' );
	}


	/**
	 * Setup Visual Editor elements.
	 */
	public function setup_elements() {
		$this->register_block_element(
			array(
				'id'       => 'spoiler',
				'name'     => __( 'spoiler', 'upfront' ),
				'selector' => '.su-spoiler',
			)
		);
	}

	/**
	 * UpFront Content Method
	 *
	 * @param object $block Block.
	 * @return void
	 */
	public function content( $block ) {

		$spoilers  = parent::get_setting( $block, 'spoilers', array() );
		$shortcode = '';

		foreach ( $spoilers as $spoiler => $params ) {

			$title   = isset( $params['title'] ) ? $params['title'] : '';
			$open    = isset( $params['open'] ) ? $params['open'] : '';
			$style   = isset( $params['style'] ) ? $params['style'] : '';
			$icon    = isset( $params['icon'] ) ? $params['icon'] : '';
			$anchor  = isset( $params['anchor'] ) ? $params['anchor'] : '';
			$content = isset( $params['content'] ) ? $params['content'] : '';

			if ( is_null( $title ) ) {
				$title = 'Title';
			}

			if ( is_null( $open ) ) {
				$open = 'no';
			}

			if ( is_null( $style ) ) {
				$style = 'default';
			}

			if ( is_null( $icon ) ) {
				$icon = 'plus';
			}

			if ( is_null( $anchor ) ) {
				$anchor = 'none';
			}

			$html = do_shortcode( '[su_spoiler title="' . $title . '" open="' . $open . '" style="' . $style . '" icon="' . $icon . '" anchor="' . $anchor . '" class=""]' . $content . '[/su_spoiler]' );

			// remove inline CSS for color.
			$html = preg_replace( '(style=("|\Z)(.*?)("|\Z))', '', $html );

			$shortcode .= $html;

		}

		echo $shortcode;	

	}

}
/**
 * Options class for block
 */
class UpFrontBlockSpoilerOptions extends UpFrontBlockOptionsAPI {

	
	public $tabs;
	public $sets;
	public $inputs;
	public function __construct() {

		$this->tabs = array(
			'general' => __( 'Allgemeines', 'upfront' ),
		);

		$this->sets = array();

		$this->inputs = array(
			'general' => array(

				'spoilers' => array(
					'type'    => 'repeater',
					'name'    => 'spoilers',
					'label'   => __( 'Spoiler', 'upfront' ),
					'tooltip' => __( 'Spoiler with hidden content', 'upfront' ),
					'inputs'  => array(
						array(
							'type'  => 'text',
							'name'  => 'title',
							'label' => __( 'Titel', 'upfront' ),
						),

						array(
							'type'    => 'select',
							'name'    => 'open',
							'label'   => __( 'Open', 'upfront' ),
							'options' => array(
								'yes' => __( 'Ja', 'upfront' ),
								'no'  => __( 'Nein', 'upfront' ),
							),
							'default' => 'no',
						),

						array(
							'name'    => 'style',
							'type'    => 'select',
							'label'   => __( 'Stil', 'upfront' ),
							'default' => 'default',
							'options' => array(
								'default' => __( 'Default', 'upfront' ),
								'fancy'   => __( 'Fancy', 'upfront' ),
								'simple'  => __( 'Simple', 'upfront' ),
							),
							'tooltip' => __( 'Wähle den Stil für diesen Spoiler', 'upfront' ),
						),

						array(
							'name'    => 'icon',
							'type'    => 'select',
							'label'   => __( 'Symbol', 'upfront' ),
							'default' => 'plus',
							'options' => array(
								'plus'           => 'Plus',
								'plus-cicle'     => 'Plus-cicle',
								'plus-square-1'  => 'Plus-square-1',
								'plus-square-2'  => 'Plus-square-2',
								'arrow'          => 'Arrow',
								'arrow-circle-1' => 'Arrow-circle-1',
								'arrow-circle-2' => 'Arrow-circle-1e',
								'chevron'        => 'Chevron',
								'chevron-circle' => 'Chevron-circle',
								'caret'          => 'Caret',
								'caret-square'   => 'Caret-square',
								'folder-1'       => 'Folder-1',
								'folder-2'       => 'Folder-2',
							),
							'tooltip' => __( 'Wähle den Stil für diesen Spoiler', 'upfront' ),
						),

						array(
							'type'    => 'text',
							'name'    => 'anchor',
							'label'   => __( 'Anchor', 'upfront' ),
							'tooltip' => __( 'You can use unique anchor for this tab to access it with hash in page url. For example: use Hello and then navigate to url like http://example.com/page-url#Hello. This tab will be activated and scrolled in . ', 'upfront' ),
						),

						array(
							'type'    => 'wysiwyg',
							'name'    => 'content',
							'label'   => __( 'Inhalt', 'upfront' ),
							'default' => null,
						),
					),
				),
				'sortable' => true,
				'limit'    => 100,
			),
		);
	}
}
