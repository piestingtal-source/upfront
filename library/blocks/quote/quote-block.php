<?php
/**
 * Quote Block
 */
class UpFrontBlockQuote extends UpFrontBlockAPI {
	
	
	public $id;
	public $name;
	public $options_class;
	public $description;
	public $categories;
	public $inline_editable;
	public $inline_editable_equivalences;
	public function __construct() {
		$this->id            = 'quote';
		$this->name          = __( 'Zitat', 'upfront' );
		$this->options_class = 'UpFrontBlockQuoteOptions';
		$this->description   = __( 'Ermöglicht das Einfügen von Zitaten in Anführungszeichen in Deinen Inhalt. Du kannst den Autor und den Link des Angebots angeben.', 'upfront' );
		$this->categories    = array( 'content', 'elemente' );

		$this->inline_editable = array( 'block-title', 'block-subtitle', 'su-quote-inner' );

		$this->inline_editable_equivalences = array( 'su-quote-inner' => 'quote' );
	}


	/**
	 * Setup Visual Editor elements.
	 */
	public function setup_elements() {

		$this->register_block_element(
			array(
				'id'       => 'quote',
				'name'     => __( 'Zitat', 'upfront' ),
				'selector' => 'div.su-quote',			
			)
		);

		$this->register_block_element(
			array(
				'id'       => 'quote-pre-img',
				'name'     => __( 'Zitat pre-img', 'upfront' ),
				'selector' => 'div.su-quote:before',
			)
		);

		$this->register_block_element(
			array(
				'id'       => 'quote-post-img',
				'name'     => __( 'Zitat post-img', 'upfront' ),
				'selector' => 'div.su-quote:after',
			)
		);

		$this->register_block_element(
			array(
				'id'       => 'quote-content',
				'name'     => __( 'Zitat Inhalt', 'upfront' ),
				'selector' => 'div.su-quote .su-quote-inner',
			)
		);

		$this->register_block_element(
			array(
				'id'       => 'quote-cite',
				'name'     => __( 'Zitat zitieren', 'upfront' ),
				'selector' => 'div.su-quote .su-quote-inner .su-quote-cite',
			)
		);

		$this->register_block_element(
			array(
				'id'       => 'quote-cite-link',
				'name'     => __( 'Zitat zitieren Link', 'upfront' ),
				'selector' => 'div.su-quote .su-quote-inner .su-quote-cite a',
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

		$style = parent::get_setting( $block, 'style', 'default' );
		$url   = parent::get_setting( $block, 'url' );
		$cite  = parent::get_setting( $block, 'cite' );
		$quote = parent::get_setting( $block, 'quote' );

		$shortcode  = '[su_quote url="' . $url . '" style="' . $style . '" cite="' . $cite . '" class="quote"]';
		$shortcode .= $quote;
		$shortcode .= '[/su_quote]';

		$html = do_shortcode( $shortcode );

		echo $html;

	}
}
/**
 * Options class for block
 */
class UpFrontBlockQuoteOptions extends UpFrontBlockOptionsAPI {

	
	public $tabs;
	public $sets;
	public $inputs;
	public function __construct() {

		$this->tabs = array(
			'general' => __( 'Zitat', 'upfront' ),
		);

		$this->sets = array();

		$this->inputs = array(
			'general' => array(

				'cite'  => array(
					'name'    => 'cite',
					'type'    => 'text',
					'label'   => __( 'Zitierter', 'upfront' ),
					'tooltip' => __( 'Zitat Autor', 'upfront' ),
				),

				'quote' => array(
					'name'    => 'quote',
					'type'    => 'text',
					'label'   => __( 'Zitat', 'upfront' ),
					'tooltip' => __( 'Zitat Text', 'upfront' ),
				),

				'url'   => array(
					'name'    => 'url',
					'type'    => 'text',
					'label'   => __( 'Url', 'upfront' ),
					'tooltip' => __( 'URL des Zitatautors. Leer lassen, um den Link zu deaktivieren', 'upfront' ),
				),
			),
		);
	}
}
