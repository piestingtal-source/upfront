<?php
/**
 * Post Data Block
 */
class UpFrontBlockPostData extends UpFrontBlockAPI {

	
	public $id;
	public $name;
	public $options_class;
	public $description;
	public $categories;
	public function __construct() {
		$this->id            = 'postdata';
		$this->name          = __( 'Content Elemente', 'upfront' );
		$this->options_class = 'UpFrontBlockPostDataOptions';
		$this->description   = __( 'Ermöglicht die Anzeige verschiedener Beitragsfelder, einschließlich Beitragstitel, Beitragsinhalt, Änderungsdatum usw.', 'upfront' );
		$this->categories    = array( 'content', 'elemente' );
	}

	/**
	 * Init
	 */
	public function init() {

		if ( session_status() !== PHP_SESSION_ACTIVE ) {
			session_start();
		}
	}

	/**
	 * Setup Visual Editor elements.
	 */
	public function setup_elements() {

		$this->register_block_element(
			array(
				'id'       => 'content',
				'name'     => __( 'Inhalt', 'upfront' ),
				'selector' => '.ve-postdata',			
			)
		);

		$this->register_block_element(
			array(
				'id'       => 'text',
				'name'     => __( 'Text', 'upfront' ),
				'selector' => '.ve-postdata p',
			)
		);

		$this->register_block_element(
			array(
				'id'       => 'content-h1',
				'name'     => __( 'Inhalt h1', 'upfront' ),
				'selector' => '.ve-postdata h1',
			)
		);

		$this->register_block_element(
			array(
				'id'       => 'content-h2',
				'name'     => __( 'Inhalt h2', 'upfront' ),
				'selector' => '.ve-postdata h2',
			)
		);

		$this->register_block_element(
			array(
				'id'       => 'content-h3',
				'name'     => __( 'Inhalt h3', 'upfront' ),
				'selector' => '.ve-postdata h3',
			)
		);

		$this->register_block_element(
			array(
				'id'       => 'content-h4',
				'name'     => __( 'Inhalt h4', 'upfront' ),
				'selector' => '.ve-postdata h4',
			)
		);

		$this->register_block_element(
			array(
				'id'       => 'content-h5',
				'name'     => __( 'Inhalt h5', 'upfront' ),
				'selector' => '.ve-postdata h5',
			)
		);

		$this->register_block_element(
			array(
				'id'       => 'content-h6',
				'name'     => __( 'Inhalt h6', 'upfront' ),
				'selector' => '.ve-postdata h6',
			)
		);

		$this->register_block_element(
			array(
				'id'       => 'content-li',
				'name'     => __( 'Inhalt li', 'upfront' ),
				'selector' => '.ve-postdata li',
			)
		);

		$this->register_block_element(
			array(
				'id'       => 'content-a',
				'name'     => __( 'Inhalt Link', 'upfront' ),
				'selector' => '.ve-postdata a',
			)
		);

		$this->register_block_element(
			array(
				'id'       => 'content-image',
				'name'     => __( 'Inhalt Bild', 'upfront' ),
				'selector' => '.ve-postdata image',
			)
		);

		$this->register_block_element(
			array(
				'id'       => 'content-figure',
				'name'     => __( 'Inhalt Zahl', 'upfront' ),
				'selector' => '.ve-postdata figure',
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

		global $post;

		$session_id = 've-postdata-post-id-' . $block['id'];

		if ( ! isset( $_SESSION[ $session_id ] ) && empty( $_SESSION[ $session_id ] ) ) {
			if ( $post->ID && is_null( $_SESSION[ $session_id ] ) ) {
				$_SESSION[ 've-postdata-post-id-' . $block['id'] ] = $post->ID;
			}
		}

		$field   = trim( parent::get_setting( $block, 'field', 'post_title' ) );
		$default = parent::get_setting( $block, 'default' );
		$before  = parent::get_setting( $block, 'before' );
		$after   = parent::get_setting( $block, 'after' );
		$post_id = ( parent::get_setting( $block, 'post-id' ) ) ? parent::get_setting( $block, 'post-id' ) : $post->ID;

		if ( ! $post_id && ! is_null( $_SESSION[ 've-postdata-post-id-' . $block['id'] ] ) ) {
			$post_id = $_SESSION[ 've-postdata-post-id-' . $block['id'] ];
		}

		$shortcode = '[su_post field="' . $field . '" post_id="' . $post_id . '"]';

		$html = '<div class="ve-postdata">' . do_shortcode( $shortcode ) . '</div>';

		echo $html;

	}
}
/**
 * Options class for block
 */
class UpFrontBlockPostDataOptions extends UpFrontBlockOptionsAPI {

	
	public $tabs;
	public $sets;
	public $inputs;
	public function __construct() {

		$this->tabs = array(
			'general' => __( 'Content Elemente', 'upfront' ),
		);

		$this->sets = array();

		$this->inputs = array(
			'general' => array(

				'field'   => array(
					'name'    => 'field',
					'type'    => 'select',
					'label'   => __( 'Feld', 'upfront' ),
					'default' => 'post_title',
					'options' => array(
						''                      => '',
						'ID'                    => __( 'Beitrags ID', 'upfront' ),
						'post_author'           => __( 'Beitragsautor', 'upfront' ),
						'post_date'             => __( 'Beitragsdatum', 'upfront' ),
						'post_date_gmt'         => __( 'Beitragsdatum GMT', 'upfront' ),
						'post_content'          => __( 'Beitragsinhalt', 'upfront' ),
						'post_title'            => __( 'Beitragstitel', 'upfront' ),
						'post_excerpt'          => __( 'Beitragsauszug', 'upfront' ),
						'post_status'           => __( 'Beitragsstatus', 'upfront' ),
						'comment_status'        => __( 'Kommentarstatus', 'upfront' ),
						'ping_status'           => __( 'Ping-Status', 'upfront' ),
						'post_name'             => __( 'Beitragsname', 'upfront' ),
						'post_modified'         => __( 'Beitrag geändert', 'upfront' ),
						'post_modified_gmt'     => __( 'Beitrag geändert GMT', 'upfront' ),
						'post_content_filtered' => __( 'Gefilterter Beitragsinhalt', 'upfront' ),
						'post_parent'           => __( 'Beitrag Elternteil', 'upfront' ),
						'guid'                  => 'GUID',
						'menu_order'            => __( 'Menüreihenfolge', 'upfront' ),
						'post_type'             => __( 'Beitragstyp', 'upfront' ),
						'post_mime_type'        => __( 'Beitrag-Mime-Typ', 'upfront' ),
						'comment_count'         => __( 'Kommentarzähler', 'upfront' ),
					),
					'tooltip' => __( 'Name des Beitragsdatenfelds', 'upfront' ),
				),
				'default' => array(
					'name'    => 'default',
					'type'    => 'text',
					'label'   => __( 'Standard', 'upfront' ),
					'tooltip' => __( 'Dieser Text wird angezeigt, wenn keine Daten gefunden werden', 'upfront' ),
				),
				'before'  => array(
					'name'    => 'before',
					'type'    => 'text',
					'label'   => __( 'Bevor', 'upfront' ),
					'tooltip' => __( 'Dieser Inhalt wird vor dem Wert angezeigt', 'upfront' ),
				),
				'after'   => array(
					'name'    => 'after',
					'type'    => 'text',
					'label'   => __( 'Dannach', 'upfront' ),
					'tooltip' => __( 'Dieser Inhalt wird nach dem Wert angezeigt', 'upfront' ),
				),
				'post-id' => array(
					'name'    => 'post-id',
					'type'    => 'text',
					'label'   => __( 'Beitrag ID', 'upfront' ),
					'tooltip' => __( 'Du kannst eine benutzerdefinierte Beitrag-ID angeben. Beitrags Slug ist ebenfalls erlaubt. Lasse dieses Feld leer, um die ID des aktuellen Beitrags zu verwenden. Die aktuelle Beitrag-ID funktioniert möglicherweise nicht im Live-Vorschaumodus', 'upfront' ),
				),
			),

		);
	}
}
