<?php

class UpFrontDailymotionBlock extends UpFrontBlockAPI {

	
	public $id;
	public $name;
	public $options_class;
	public $description;
	public $categories;
	public function __construct() {
		$this->id            = 'dailymotion';	
		$this->name          = 'Dailymotion';
		$this->options_class = 'UpFrontDailymotionBlockOptions';
		$this->description   = __( 'Ermöglicht das Einfügen von responsiven Dailymotion-Videos.', 'upfront' );
		$this->categories    = array( 'medien' );
	}


	/**
	 * Setup Visual Editor elements.
	 */
	public function setup_elements() {
		$this->register_block_element(
			array(
				'id'       => 'dailymotion',
				'name'     => 'Dailymotion',
				'selector' => 'div.su-dailymotion',
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

		$url        = parent::get_setting( $block, 'url' );
		$width      = parent::get_setting( $block, 'width' );
		$height     = parent::get_setting( $block, 'height' );
		$responsive = parent::get_setting( $block, 'responsive' );
		$autoplay   = parent::get_setting( $block, 'autoplay' );
		$background = parent::get_setting( $block, 'background' );
		$foreground = parent::get_setting( $block, 'foreground' );
		$highlight  = parent::get_setting( $block, 'highlight' );
		$logo       = parent::get_setting( $block, 'logo' );
		$quality    = parent::get_setting( $block, 'quality' );
		$related    = parent::get_setting( $block, 'related' );
		$info       = parent::get_setting( $block, 'info' );

		if ( $width < 200 ) {
			$width = 200;
		}

		if ( $width > 1600 ) {
			$width = 1600;
		}

		if ( $height < 200 ) {
			$height = 200;
		}

		if ( $height > 1600 ) {
			$height = 1600;
		}

		if ( ! $responsive ) {
			$responsive = 'yes';
		}

		if ( ! $autoplay ) {
			$autoplay = 'no';
		}

		if ( ! $background ) {
			$background = '#FFC300';
		}

		if ( ! $foreground ) {
			$foreground = '#F7FFFD';
		}

		if ( ! $highlight ) {
			$highlight = '#171D1B';
		}

		if ( ! $logo ) {
			$logo = 'yes';
		}

		if ( ! in_array( $quality, array( '240', '380', '480', '720', '1080' ), true ) || ! $quality ) {
			$quality = '380';
		}

		if ( ! $related ) {
			$related = 'yes';
		}

		if ( ! $info ) {
			$info = 'yes';
		}

		echo do_shortcode( '[su_dailymotion url="' . $url . '" width="' . $width . '" height="' . $height . '" responsive="' . $responsive . '" autoplay="' . $autoplay . '" background="' . $background . '" foreground="' . $foreground . '" highlight="' . $highlight . '" logo="' . $logo . '" quality="' . $quality . '" related="' . $related . '" info="' . $info . '" class=""]' );

	}

}

class UpFrontDailymotionBlockOptions extends UpFrontBlockOptionsAPI {

	
	public $tabs;
	public $sets;
	public $inputs;
	public function __construct() {
		$this->tabs = array(
			'general' => __( 'Dailymotion', 'upfront' ),
		);

		$this->sets = array();

		$this->inputs = array(
			'general' => array(

				'url'        => array(
					'name'    => 'url',
					'label'   => __( 'Url', 'upfront' ),
					'type'    => 'text',
					'default' => '',
					'tooltip' => __( 'URL von Dailymotion Seite mit Video', 'upfront' ),
				),

				'width'      => array(
					'name'    => 'width',
					'type'    => 'integer',
					'label'   => __( 'Breite', 'upfront' ),
					'default' => 600,
					'tooltip' => __( 'Videobreite', 'upfront' ),
				),

				'height'     => array(
					'name'    => 'height',
					'type'    => 'integer',
					'label'   => __( 'Höhe', 'upfront' ),
					'default' => 400,
					'tooltip' => __( 'Kartenhöhe', 'upfront' ),
				),

				'responsive' => array(
					'name'    => 'responsive',
					'type'    => 'select',
					'label'   => __( 'Responsiv', 'upfront' ),
					'default' => 'yes',
					'options' => array(
						'yes' => __( 'Ja', 'upfront' ),
						'no'  => __( 'Nein', 'upfront' ),
					),
					'tooltip' => __( 'Ignoriere die Parameter für Breite und Höhe und sorge dafür, dass der Player responsiv reagiert', 'upfront' ),
				),

				'autoplay'   => array(
					'name'    => 'autoplay',
					'type'    => 'select',
					'label'   => __( 'Autoplay', 'upfront' ),
					'default' => 'yes',
					'options' => array(
						'yes' => __( 'Ja', 'upfront' ),
						'no'  => __( 'Nein', 'upfront' ),
					),
					'tooltip' => __( 'Video automatisch abspielen, wenn eine Seite geladen wird. Bitte beachte, dass in modernen Browsern die Autoplay-Option nur mit aktivierter Stummschaltoption funktioniert', 'upfront' ),
				),

				'background' => array(
					'name'    => 'background',
					'type'    => 'text',
					'label'   => __( 'Background', 'upfront' ),
					'default' => '#FFC300',
					'tooltip' => __( 'HTML (HEX)-Farbe des Hintergrunds von Steuerelementelementen', 'upfront' ),
				),

				'foreground' => array(
					'name'    => 'foreground',
					'type'    => 'text',
					'label'   => __( 'Vordergrund', 'upfront' ),
					'default' => '#F7FFFD',
					'tooltip' => __( 'HTML (HEX)-Farbe des Vordergrunds der Steuerelemente', 'upfront' ),
				),

				'highlight'  => array(
					'name'    => 'highlight',
					'type'    => 'text',
					'label'   => __( 'Highlight', 'upfront' ),
					'default' => '#171D1B',
					'tooltip' => __( "HTML (HEX)-Farbe der Highlights der Steuerelemente", 'upfront' ),
				),

				'logo'       => array(
					'name'    => 'logo',
					'type'    => 'select',
					'label'   => __( 'Logo', 'upfront' ),
					'default' => 'yes',
					'options' => array(
						'yes' => __( 'Ja', 'upfront' ),
						'no'  => __( 'Nein', 'upfront' ),
					),
					'tooltip' => __( 'Ermöglicht das Ausblenden oder Anzeigen des Dailymotion-Logos', 'upfront' ),
				),

				'quality'    => array(
					'name'    => 'quality',
					'type'    => 'select',
					'label'   => __( 'Qualität', 'upfront' ),
					'default' => '380',
					'options' => array(
						'240'  => '240',
						'380'  => '380',
						'480'  => '480',
						'720'  => '720',
						'1080' => '1080',
					),
					'tooltip' => __( 'Legt die Qualität fest, die standardmäßig abgespielt werden muss, falls verfügbar', 'upfront' ),
				),

				'related'    => array(
					'name'    => 'related',
					'type'    => 'select',
					'label'   => __( 'Verwandte', 'upfront' ),
					'default' => 'yes',
					'options' => array(
						'yes' => __( 'Ja', 'upfront' ),
						'no'  => __( 'Nein', 'upfront' ),
					),
					'tooltip' => __( 'Zeige verwandte Videos am Ende des Videos an', 'upfront' ),
				),

				'info'       => array(
					'name'    => 'info',
					'type'    => 'select',
					'label'   => __( 'Info', 'upfront' ),
					'default' => 'yes',
					'options' => array(
						'yes' => __( 'Ja', 'upfront' ),
						'no'  => __( 'Nein', 'upfront' ),
					),
					'tooltip' => __( 'Videoinformationen (Titel/Autor) auf dem Startbildschirm anzeigen', 'upfront' ),
				),
			),
		);
	}
}

