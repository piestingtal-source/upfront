<?php

class UpFrontContentBlock extends UpFrontBlockAPI {


	public $id;
	public $name;
	public $options_class;
	public $description;
	public $categories;


	function __construct(){

		$this->id = 'content';	
		$this->name = __('Content', 'upfront');
		$this->options_class = 'UpFrontContentBlockOptions';
		$this->description 	= __('Hauptinhaltsbereich, um den Inhalt der aktuellen Seite oder die neuesten Beiträge anzuzeigen. Dies wird in anderen Themen als "Loop" bezeichnet.', 'upfront');
		$this->categories = array('core','content');

	}		


	function init() {

		/* Load dependencies */
		require_once UPFRONT_LIBRARY_DIR . '/blocks/content/content-display.php';

		/* Set up the comments template */
		add_filter('comments_template', array(__CLASS__, 'add_blank_comments_template'), 5);

		/* Set up editor style */
		add_filter('mce_css', array(__CLASS__, 'add_editor_style'));

		/* Add .comment class to all pingbacks */
		add_filter('comment_class', array(__CLASS__, 'add_comment_class_to_all_types'));

	}


	public static function add_blank_comments_template() {

		return UPFRONT_LIBRARY_DIR . '/blocks/content/comments-template.php';

	}


	public static function add_comment_class_to_all_types($classes) {

		if ( !is_array($classes) ) {
			$classes = implode(' ', trim($classes));
		}
		$classes[] = 'comment';

		return array_filter(array_unique($classes));

	}


	public static function add_editor_style($css) {

		if ( UpFrontOption::get('disable-editor-style', false, false) )
			return $css;

		if ( !current_theme_supports('editor-style') )
			return $css;

		if ( !current_theme_supports('upfront-design-editor') )
			return $css;

		UpFrontCompiler::register_file(array(
			'name' => 'editor-style',
			'format' => 'css',
			'fragments' => array(
				'upfront_content_block_editor_style'
			),
			'dependencies' => array(UPFRONT_LIBRARY_DIR . '/blocks/content/editor-style.php'),
			'enqueue' => false
		));

		return $css . ',' . UpFrontCompiler::get_url('editor-style');

	}

	public static function dynamic_css($block_id, $block) {

		$css = '';

		$featured_image_as_background = parent::get_setting( $block, 'featured-image-as-background', false);
		$overlay = parent::get_setting($block, 'featured-image-as-background-overlay');
		$overlay = parent::get_setting($block, 'featured-image-as-background-overlay');
		$overlay_hover = parent::get_setting($block, 'featured-image-as-background-overlay-hover', 'transparent');

		if( !empty( $overlay ) && $featured_image_as_background ){

			$css .= '#block-' . $block_id . ' article{';
			$css .= 'position: relative;';
			$css .= '}';

			$css .= '#block-' . $block_id . ' *{';
			$css .= 'position: relative;';
			$css .= 'z-index: 2;';	
			$css .= '}';

			$css .= '#block-' . $block_id . ' article:before{';
			$css .= 'content: " ";';
			$css .= 'background-color: ' . $overlay . ';';	
			$css .= 'position: absolute;';
			$css .= 'top: 0;';
			$css .= 'bottom: 0;';
			$css .= 'left: 0;';
			$css .= 'right: 0;';
			$css .= 'z-index: 1;';
			$css .= '}';
			$css .= '#block-' . $block_id . ' article:hover:before{';
			$css .= 'background-color: ' . $overlay_hover . ';';
			$css .= '}';
		}

		if ( parent::get_setting($block, 'enable-column-layout') ) {

			$gutter_width = parent::get_setting($block, 'post-gutter-width', '20');

			if ( UpFrontResponsiveGrid::is_enabled() ) {
				$css .= '@media only screen and (min-width: ' . UpFrontBlocksData::get_block_width($block) . 'px) {';
			}

				$css .= '#block-' . $block_id . ' .loop .entry-row .hentry {';

					$css .= 'margin-left: ' . self::width_as_percentage($gutter_width, $block) . '%;';
					$css .= 'width: ' . self::width_as_percentage(self::get_column_width($block), $block) . '%;';

				$css .= '}';


			if ( UpFrontResponsiveGrid::is_enabled() ) {
				$css .= '}';
			}

		}

		return $css . "\n";


	}

	static function get_column_width($block) {

		$block_width = UpFrontBlocksData::get_block_width($block);

		$columns = parent::get_setting($block, 'posts-per-row', '2');
		$gutter_width = parent::get_setting($block, 'post-gutter-width', '20');

		$total_gutter = $gutter_width * ($columns-1);

		$columns_width = (($block_width - $total_gutter) / $columns);

		return $columns_width; 
	}

	/* To make the layout responsive
	 * Works out a percentage value equivalent of the px value 
	 * using common responsive formula: target_width / container_width * 100
	 */	
	static function width_as_percentage($target = '', $block) {
		$block_width = UpFrontBlocksData::get_block_width($block);

		if ($block_width > 0 )
			return ($target / $block_width)*100;

		return false;
	}


	function setup_elements() {

		$this->register_block_element(array(
			'id' => 'article',
			'name' => __('Artikel', 'upfront'),
			'selector' => 'article',			
		));

		/* Classic Editor */
			$this->register_block_element(array(
				'id' => 'entry-container-hentry',
				'name' => __('Eingangscontainer', 'upfront'),
				'selector' => '.hentry',
				'properties' => array('background', 'borders', 'padding', 'corners', 'box-shadow', 'animation', 'transform', 'advanced', 'transition', 'outlines', 'filter')
			));

				$this->register_block_element(array(
					'id' => 'page-container',
					'name' => __('Seiteneintragscontainer', 'upfront'),
					'parent' => 'entry-container-hentry',
					'selector' => '.type-page'
				));

				$this->register_block_element(array(
					'id' => 'entry-container',
					'name' => __('Seiteneintragscontainer', 'upfront'),
					'parent' => 'entry-container-hentry',
					'selector' => '.type-post'
				));


			$this->register_block_element(array(
				'id' => 'entry-row',
				'name' => __('Eintragszeile', 'upfront'),
				'selector' => '.entry-row'
			));

			$this->register_block_element(array(
				'id' => 'title',
				'name' => __('Titel', 'upfront'),
				'selector' => '.entry-title',
				'states' => array(
					'Hover' => '.entry-title:hover', 
					'Clicked' => '.entry-title:active'
				)
			));

			$this->register_block_element(array(
				'id' => 'archive-title',
				'name' => __('Archivtitel', 'upfront'),
				'selector' => '.archive-title'
			));

			$this->register_block_element(array(
				'id' => 'entry-content',
				'name' => __('Hauptteil', 'upfront'),
				'description' => __('Der gesamte Text einschließlich &lt;p&gt; Elemente', 'upfront'),
				'selector' => 'div.entry-content, div.entry-content p'
			));

			$this->register_block_element(array(
				'id' => 'entry-content-hyperlinks',
				'name' => __('Hauptteil Hyperlinks', 'upfront'),
				'selector' => 'div.entry-content a',				
				'states' => array(
					'Hover' => 'div.entry-content a:hover', 
					'Clicked' => 'div.entry-content a:active'
				)
			));

			$this->register_block_element(array(
				'id' => 'entry-content-images',
				'name' => __('Bilder', 'upfront'),
				'selector' => 'div.entry-content img',
				'properties' => array('background', 'borders', 'padding', 'corners', 'box-shadow', 'animation', 'sizes', 'filter')
			));

			$this->register_block_element( array(
				'id'         => 'entry-content-image-captions',
				'name'       => __('Bildunterschriften', 'upfront'),
				'selector'   => 'div.entry-content .wp-caption',
				'properties' => array( 'background', 'borders', 'padding', 'corners', 'box-shadow', 'animation' )
			) );

				$this->register_block_element( array(
					'id'       => 'entry-content-image-caption-image',
					'parent'   => 'entry-content-image-captions',
					'name'     => __('Bilder in Bildunterschriften', 'upfront'),
					'selector' => 'div.entry-content .wp-caption img',
					'properties' => array( 'background', 'borders', 'padding', 'corners', 'box-shadow', 'animation', 'filter' )
				) );

				$this->register_block_element( array(
					'id'         => 'entry-content-image-caption-text',
					'parent'     => 'entry-content-image-captions',
					'name'       => __('Beschriftungstext', 'upfront'),
					'selector'   => 'div.entry-content .wp-caption .wp-caption-text'
				) );

			$this->register_block_element(array(
				'id' => 'entry-meta',
				'name' => __('Meta', 'upfront'),
				'selector' => 'div.entry-meta'
			));

				$this->register_block_element(array(
					'id' => 'entry-meta-above',
					'name' => __('Meta über Inhalt', 'upfront'),
					'selector' => 'div.entry-meta-above',
					'parent' => 'entry-meta'
				));			

				$this->register_block_element(array(
					'id' => 'entry-meta-below',
					'name' => __('Meta unter Inhalt', 'upfront'),
					'selector' => 'footer.entry-utility-below',
					'parent' => 'entry-meta'
				));			

				$this->register_block_element(array(
					'id' => 'entry-meta-links',
					'name' => __('Meta Hyperlinks', 'upfront'),
					'selector' => 'div.entry-meta a, footer.entry-meta a',
					'parent' => 'entry-meta',					
					'states' => array(
					'Hover' => 'div.entry-meta a:hover, footer.entry-meta a:hover', 
					'Clicked' => 'div.entry-meta a:active, footer.entry-meta a:active'
				)
				));

				$this->register_block_element(array(
					'id' => 'entry-meta-author',
					'name' => __('Autor Avatarbild', 'upfront'),
					'selector' => '.avatar',
					'parent' => 'entry-meta'
				));

				$this->register_block_element(array(
					'id' => 'entry-meta-publisher',
					'name' => __('Publisher-Logo-Container', 'upfront'),
					'selector' => '.publisher-img',
					'parent' => 'entry-meta'
				));

				$this->register_block_element(array(
					'id' => 'entry-meta-publisher-image-container',
					'name' => __('Bildcontainer mit Publisher-Logo', 'upfront'),
					'selector' => '.publisher-img .logo',
					'parent' => 'entry-meta'
				));

				$this->register_block_element(array(
					'id' => 'entry-meta-publisher-image-link',
					'name' => __('Publisher Logolink', 'upfront'),
					'selector' => '.publisher-img .logo a',
					'parent' => 'entry-meta'
				));

				$this->register_block_element(array(
					'id' => 'entry-meta-publisher-image-file',
					'name' => __('Publisher Logobild', 'upfront'),
					'selector' => '.publisher-img .logo a img',
					'parent' => 'entry-meta'
				));

				$this->register_block_element(array(
					'id' => 'entry-meta-publisher-meta',
					'name' => __('Metadaten des Publisher-Logos', 'upfront'),
					'selector' => '.publisher-img meta',
					'parent' => 'entry-meta'
				));

				$this->register_block_element(array(
					'id' => 'entry-date',
					'name' => __('Beitragsdatum Eintrag', 'upfront'),
					'parent' => 'entry-meta',
					'selector' => '.entry-date'
				));

			$this->register_block_element(array(
				'id' => 'heading',
				'name' => __('Überschrift', 'upfront'),
				'selector' => 'div.entry-content h3, div.entry-content h2, div.entry-content h1'
			));

				$this->register_block_element(array(
					'id' => 'heading-h1',
					'parent' => 'heading',
					'name' => 'H1',
					'selector' => 'div.entry-content h1',
					'parent' => 'heading'
				));

				$this->register_block_element(array(
					'id' => 'heading-h2',
					'parent' => 'heading',
					'name' => 'H2',
					'selector' => 'div.entry-content h2'
				));

				$this->register_block_element(array(
					'id' => 'heading-h3',
					'parent' => 'heading',
					'name' => 'H3',
					'selector' => 'div.entry-content h3'
				));

			$this->register_block_element(array(
				'id' => 'sub-heading',
				'name' => __('Unterüberschrift', 'upfront'),
				'selector' => 'div.entry-content h4, div.entry-content h5'
			));

				$this->register_block_element(array(
					'id' => 'sub-heading-h4',
					'parent' => 'sub-heading',
					'name' => 'H4',
					'selector' => 'div.entry-content h4'
				));

				$this->register_block_element(array(
					'id' => 'sub-heading-h5',
					'parent' => 'sub-heading',
					'name' => 'H5',
					'selector' => 'div.entry-content h5'
				));

				$this->register_block_element(array(
					'id' => 'content-ul-lists',
					'name' => __('Ungeordnete Listen', 'upfront'),
					'description' => '&lt;UL&gt;',
					'selector' => 'div.entry-content ul',
				));

				$this->register_block_element(array(
					'id' => 'content-ul-list-item',
					'name' => __('Ungeordnete Listenelemente', 'upfront'),
					'description' => '&lt;LI&gt;',
					'selector' => 'div.entry-content ul li',					
				));

				$this->register_block_element(array(
					'id' => 'content-ol-lists',
					'name' => __('Bestellte Listen', 'upfront'),
					'description' => '&lt;OL&gt;',
					'selector' => 'div.entry-content ol',					
				));

				$this->register_block_element(array(
					'id' => 'content-list-item',
					'name' => __('Bestellte Listeneinträge', 'upfront'),
					'description' => '&lt;LI&gt;',
					'selector' => 'div.entry-content ol li',					
				));

			$this->register_block_element(array(
				'id' => 'post-thumbnail-contanier',
				'name' => __('Ausgewähltes Bild Behälter', 'upfront'),
				'selector' => '.block-type-content a.post-thumbnail',				
			));

			$this->register_block_element(array(
				'id' => 'post-thumbnail',
				'name' => __('Ausgewähltes Bild', 'upfront'),
				'selector' => '.block-type-content a.post-thumbnail img',				
			));

			$this->register_block_element(array(
				'id' => 'more-link',
				'name' => __('Lese weiter Schaltfläche', 'upfront'),
				'selector' => 'div.entry-content a.more-link',
				'states' => array(
					'Hover' => 'div.entry-content a.more-link:hover',
					'Clicked' => 'div.entry-content a.more-link:active'
				)
			));

			$this->register_block_element(array(
				'id' => 'loop-navigation-link',
				'name' => __('Loop-Navigationstaste', 'upfront'),
				'selector' => 'div.loop-navigation div.nav-previous a, div.loop-navigation div.nav-next a',
				'states' => array(
					'Hover' => 'div.loop-navigation div.nav-previous a:hover, div.loop-navigation div.nav-next a:hover',
					'Clicked' => 'div.loop-navigation div.nav-previous a:active, div.loop-navigation div.nav-next a:active'
				)
			));

			$this->register_block_element(array(
				'id' => 'comments-wrapper',
				'name' => __('Kommentare', 'upfront'),
				'selector' => 'div#comments'
			));

			$this->register_block_element(array(
				'id' => 'comments-area',
				'name' => __('Kommentarbereich', 'upfront'),
				'selector' => 'ol.commentlist',
				'properties' => array('background', 'borders', 'padding', 'corners', 'box-shadow'),
				'parent' => 'comments-wrapper'
			));

			$this->register_block_element(array(
				'id' => 'comments-area-headings',
				'name' => __('Kommentare Bereichsüberschriften', 'upfront'),
				'selector' => 'div#comments h3',
				'parent' => 'comments-wrapper'
			));

			$this->register_block_element(array(
				'id' => 'comment-container',
				'name' => __('Kommentarbehälter', 'upfront'),
				'selector' => 'li.comment',
				'properties' => array('background', 'borders', 'padding', 'corners', 'box-shadow', 'animation'),
				'parent' => 'comments-wrapper'
			));

			$this->register_block_element(array(
				'id' => 'comments-textarea',
				'name' => __('Kommentar hinzufügen Textbereich', 'upfront'),
				'selector' => '#comment',
				'parent' => 'comments-wrapper'
			));

			$this->register_block_element(array(
				'id' => 'comment-author',
				'name' => __('Kommentar Autor', 'upfront'),
				'selector' => 'li.comment .comment-author',
				'parent' => 'comments-wrapper'
			));

			$this->register_block_element(array(
				'id' => 'comment-meta',
				'name' => __('Kommentar Meta', 'upfront'),
				'selector' => 'li.comment .comment-meta',
				'parent' => 'comments-wrapper'
			));

			$this->register_block_element(array(
				'id' => 'comment-meta-count',
				'name' => __('Kommentar Meta Zähler', 'upfront'),
				'selector' => 'a.entry-comments',
				'parent' => 'comments-wrapper'
			));

			$this->register_block_element(array(
				'id' => 'comment-body',
				'name' => __('Kommentar Inhaltsbereich', 'upfront'),
				'selector' => 'li.comment .comment-body p',
				'properties' => array('fonts'),
				'parent' => 'comments-wrapper'
			));

			$this->register_block_element(array(
				'id' => 'comment-reply-link',
				'name' => __('Kommentar Antworten Link', 'upfront'),
				'selector' => 'a.comment-reply-link',
				'states' => array(
					'Hover' => 'a.comment-reply-link:hover',
					'Clicked' => 'a.comment-reply-link:active'
				),
				'parent' => 'comments-wrapper'
			));

			$this->register_block_element(array(
				'id' => 'comment-form-input-label',
				'name' => __('Eingabeformular für Kommentarformular', 'upfront'),
				'selector' => 'div#respond label',
				'properties' => array('fonts'),
				'parent' => 'comments-wrapper'
			));

		/* Ende Classic Container */


		/*	Gutenberg */

			$this->register_block_element(array(
				'id' => 'gutenberg-audio-block',
				'name' => __('Gutenberg Audioblock', 'upfront'),
				'selector' => '.wp-block-audio',
			));

			$this->register_block_element(array(
				'id' => 'gutenberg-video-block',
				'name' => __('Gutenberg Videoblock', 'upfront'),
				'selector' => '.wp-block-video',
			));

			$this->register_block_element(array(
				'id' => 'gutenberg-file-block',
				'name' => __('Gutenberg Dateiblock', 'upfront'),
				'selector' => '.wp-block-file',
			));

			$this->register_block_element(array(
				'id' => 'gutenberg-image-block',
				'name' => __('Gutenberg Bildblock', 'upfront'),
				'selector' => '.wp-block-image',
			));

			$this->register_block_element(array(
				'id' => 'gutenberg-gallery-block',
				'name' => __('Gutenberg Galerieblock', 'upfront'),
				'selector' => '.wp-block-gallery',
			));

			$this->register_block_element(array(
				'id' => 'gutenberg-gallery-block-item',
				'name' => __('Gutenberg Galerieeintrag', 'upfront'),
				'selector' => '.wp-block-gallery-item',
			));

			$this->register_block_element(array(
				'id' => 'gutenberg-cover-block',
				'name' => __('Gutenberg Abdeckblock', 'upfront'),
				'selector' => '.wp-block-cover',
			));

			$this->register_block_element(array(
				'id' => 'gutenberg-code-block',
				'name' => __('Gutenberg Codeblock', 'upfront'),
				'selector' => '.wp-block-code',
			));

			$this->register_block_element(array(
				'id' => 'gutenberg-preformatted-block',
				'name' => __('Gutenberg preformatted block', 'upfront'),
				'selector' => '.wp-block-preformatted',
			));

			$this->register_block_element(array(
				'id' => 'gutenberg-pullquote-block',
				'name' => __('Gutenberg pullquote block', 'upfront'),
				'selector' => '.wp-block-pullquote',
			));

			$this->register_block_element(array(
				'id' => 'gutenberg-table-block',
				'name' => __('Gutenberg table block', 'upfront'),
				'selector' => '.wp-block-table',
			));

			$this->register_block_element(array(
				'id' => 'gutenberg-button-block',
				'name' => __('Gutenberg button block', 'upfront'),
				'selector' => '.wp-block-button',
			));

			$this->register_block_element(array(
				'id' => 'gutenberg-columns-block',
				'name' => __('Gutenberg columns block', 'upfront'),
				'selector' => '.wp-block-columns',
			));

			$this->register_block_element(array(
				'id' => 'gutenberg-media-text-block',
				'name' => __('Gutenberg media-text block', 'upfront'),
				'selector' => '.wp-block-media-text',
			));

			$this->register_block_element(array(
				'id' => 'gutenberg-separator-block',
				'name' => __('Gutenberg separator block', 'upfront'),
				'selector' => '.wp-block-separator',
			));

			$this->register_block_element(array(
				'id' => 'gutenberg-archives-block',
				'name' => __('Gutenberg archives block', 'upfront'),
				'selector' => '.wp-block-archives',
			));

			$this->register_block_element(array(
				'id' => 'gutenberg-categories-block',
				'name' => __('Gutenberg categories block', 'upfront'),
				'selector' => '.wp-block-categories',
			));

			$this->register_block_element(array(
				'id' => 'gutenberg-categories-block',
				'name' => __('Gutenberg categories block', 'upfront'),
				'selector' => '.wp-block-categories .cat-item',
			));

			$this->register_block_element(array(
				'id' => 'gutenberg-latest-comments-block',
				'name' => __('Gutenberg latest-comments block', 'upfront'),
				'selector' => '.wp-block-latest-comments',
			));			

			$this->register_block_element(array(
				'id' => 'gutenberg-categories-block',
				'name' => __('Gutenberg categories block', 'upfront'),
				'selector' => '.wp-block-categories',
			));			

			$this->register_block_element(array(
				'id' => 'gutenberg-embed-block',
				'name' => __('Gutenberg embed block', 'upfront'),
				'selector' => '.wp-block-embed',
			));			

		/*	End Gutenberg */


		/**
		 *
		 * Custom Fields
		 *
		 */
		$this->register_block_element(array(
			'id' => 'custom-fields',
			'name' => __('Container benutzerdefinierte Felder', 'upfront'),
			'selector' => '.custom-fields',			
		));
		$this->register_block_element(array(
			'id' => 'custom-fields-group',
			'name' => __('Benutzerdefinierte Feldgruppe', 'upfront'),
			'selector' => '.custom-fields-group',
		));

			$this->register_block_element(array(
				'id' => 'custom-fields-div',
				'name' => __('Benutzerdefiniertes Feldbild', 'upfront'),
				'selector' => '.custom-fields image',			
			));

			$this->register_block_element(array(
				'id' => 'custom-fields-div',
				'name' => __('Benutzerdefinierte Felder Div', 'upfront'),
				'selector' => '.custom-fields div',			
			));

			$this->register_block_element(array(
				'id' => 'custom-fields-p',
				'name' => __('Text benutzerdefinierte Felder', 'upfront'),
				'selector' => '.custom-fields p',
			));

			$this->register_block_element(array(
				'id' => 'custom-fields-a',
				'name' => __('Link zu benutzerdefinierten Feldern', 'upfront'),
				'selector' => '.custom-fields a',
			));

			$this->register_block_element(array(
				'id' => 'custom-fields-h1',
				'name' => __('Benutzerdefinierte Felder H1', 'upfront'),
				'selector' => '.custom-fields h1',
			));

			$this->register_block_element(array(
				'id' => 'custom-fields-h2',
				'name' => __('Benutzerdefinierte Felder H2', 'upfront'),
				'selector' => '.custom-fields h2',
			));

			$this->register_block_element(array(
				'id' => 'custom-fields-h3',
				'name' => __('Benutzerdefinierte Felder H3', 'upfront'),
				'selector' => '.custom-fields h3',
			));

			$this->register_block_element(array(
				'id' => 'custom-fields-h4',
				'name' => __('Benutzerdefinierte Felder H4', 'upfront'),
				'selector' => '.custom-fields h4',
			));

			$this->register_block_element(array(
				'id' => 'custom-fields-h5',
				'name' => __('Benutzerdefinierte Felder H5', 'upfront'),
				'selector' => '.custom-fields h5',
			));

			$this->register_block_element(array(
				'id' => 'custom-fields-h6',
				'name' => __('Benutzerdefinierte Felder H6', 'upfront'),
				'selector' => '.custom-fields h6',
			));

			$this->register_block_element(array(
				'id' => 'custom-fields-span',
				'name' => __('Benutzerdefinierte Felder Span', 'upfront'),
				'selector' => '.custom-fields span',
			));

	}


	function content($block) {

		$block['custom-fields'] = $this->get_custom_fields($block);
		$content_block_display = new UpFrontContentBlockDisplay($block);
		$content_block_display->display();

	}

	function get_custom_fields($block){

		$custom_fields_show = $custom_fields_label = $custom_fields_position = array();

		foreach ($block['settings'] as $name => $value) {

			$data = explode('-', $name);
			$post_type = (!empty($data[3])) ? $data[3]: null;

			if(is_null($post_type))
				continue;

			$custom_field = $name;
			$custom_field = str_replace('custom-field-show-' . $post_type . '-', '' , $custom_field);
			$custom_field = str_replace('custom-field-position-' . $post_type . '-', '' , $custom_field);
			$custom_field = str_replace('custom-field-label-' . $post_type . '-', '' , $custom_field);

			if ( strpos($name, 'custom-field-show') !== false ){				
				if($value){
					$custom_fields_show[$post_type][$custom_field] = $value;
				}				
			}

			if ( strpos($name, 'custom-field-position') !== false )
				$custom_fields_position[$post_type][$custom_field] = $value;

			if ( strpos($name, 'custom-field-label') !== false )
				$custom_fields_label[$post_type][$custom_field] = $value;

		}

		$data = array();

		foreach ($custom_fields_position as $post_type => $custom_fields) {
			foreach ($custom_fields as $field_name => $position) {
				if($custom_fields_show[$post_type][$field_name]){
					$label = $custom_fields_label[$post_type][$field_name];
					$data[$position][$post_type][$field_name] = $label;					
				}
			}
		}

		return $data;
	}


}


class UpFrontContentBlockOptions extends UpFrontBlockOptionsAPI {


	public $tab_notices;
	public $tabs;	
	public $inputs;

	function __construct($block_type_object){

		parent::__construct($block_type_object);

		$this->tab_notices = array(
			'mode' => __('Der Inhaltsblock ist äußerst vielseitig. Wenn der Standardmodus ausgewählt ist, wird er das tun, was Du erwartest. Wenn Du dies beispielsweise zu einer Seite hinzufügst, wird der Inhalt dieser Seite angezeigt. Wenn Du es zum Blog-Index-Layout hinzufügst, werden die Beiträge wie bei einer normalen Blog-Vorlage aufgelistet. Wenn Du dieses Feld zu einem Kategorielayout hinzufügest, werden Beiträge dieser Kategorie aufgelistet. Wenn Du die Anzeige des Inhaltsblocks ändern möchtest, ändere den Modus in <em>Benutzerdefinierte Abfrage</em> und verwende die Einstellungen auf der Registerkarte <em>Abfragefilter</em>.', 'upfront'),

			'query-setup' => __('Für mehr Kontrolle über Abfragen und wie die Abfrage angezeigt wird, funktioniert UpFront sofort mit <a href="https://ithemes.com/purchase/loopbuddy/" target="_blank">LoopBuddy</a>.', 'upfront'),

			'meta' => __('
				<p>Das Eintrags-Meta ist die Information, die unter dem Titel des Beitrags und unter dem Inhalt des Beitrags angezeigt wird. Standardmäßig enthält es Informationen über den Autor des Eintrags, die Kategorien und Kommentare.</p>
				<p><strong>Verfügbare Variablen:</strong></p>
				<p>%date% &bull; %modified_date% &bull; %time% &bull; %comments% &bull; %comments_no_link% &bull; %respond% &bull; %author% &bull; %author_no_link% &bull; %categories% &bull; %tags% &bull; %publisher% &bull; %publisher_img% &bull; %publisher_no_img%</p>
			', 'upfront')
		);


		$this->tabs = array(
			'mode' 				=> __('Modus', 'upfront'),
			'query-filters' 	=> __('Abfragefilter', 'upfront'),
			'display' 			=> __('Anzeige', 'upfront'),
			'custom-fields'		=> __('Benutzerdefinierte Felder', 'upfront'),
			'meta' 				=> __('Meta', 'upfront'),		
			'comments' 			=> __('Kommentare', 'upfront'),
			'post-thumbnails' 	=> __('Ausgewählte Bilder', 'upfront')
		);


		$this->inputs = array(

			'mode' => array(
				'mode' => array(
					'type' => 'select',
					'name' => 'mode',
					'label' => __('Abfragemodus', 'upfront'),
					'tooltip' => '',
					'options' => array(
						'default' => __('Standardverhalten', 'upfront'),
						'custom-query' => __('Benutzerdefinierte Abfrage', 'upfront')
					),
					'toggle'    => array(
						'custom-query' => array(
							'show' => array(
								'li#sub-tab-query-filters'
							)
						),
						'default' => array(
							'hide' => array(
								'li#sub-tab-query-filters'
							)
						)
					)
				)
			),

			'query-filters' => array(
				'page-fetch-query-heading' => array(
					'name' => 'page-fetch--query-heading',
					'type' => 'heading',
					'label' => __('Eine Seite abrufen', 'upfront')
				),

				'fetch-page-content' => array(
					'type' => 'select',
					'name' => 'fetch-page-content',
					'label' => __('Seiteninhalt abrufen', 'upfront'),
					'tooltip' => __('Abfrageoptionen haben keine Auswirkung, wenn Du eine Seite abrufen ausgewählt hast', 'upfront'),
					'options' => 'get_pages()'
				),

				'custom-query-heading' => array(
					'name' => 'custom-query-heading',
					'type' => 'heading',
					'label' => __('Abfragefilter', 'upfront'),
					'tooltip' => __('Abfrageoptionen haben keine Auswirkung, wenn Du den obigen Inhalt einer Seite abgerufen hast', 'upfront')
				),

				'categories' => array(
					'type' => 'multi-select',
					'name' => 'categories',
					'label' => __('Kategorien', 'upfront'),
					'tooltip' => '',
					'options' => 'get_categories()'
				),

				'categories-mode' => array(
					'type' => 'select',
					'name' => 'categories-mode',
					'label' => __('Kategorienmodus', 'upfront'),
					'tooltip' => '',
					'options' => array(
						'include' => __('Einschließen', 'upfront'),
						'exclude' => __('Ausschließen', 'upfront')
					)
				),

				'enable-tags' => array(
					'type' => 'checkbox',
					'name' => 'tags-filter',
					'label' => __('Tags Filter', 'upfront'),
					'tooltip' => __('Aktiviere diese Option, damit der Tag-Filter angezeigt wird.', 'upfront'),
					'default' => false,
					'toggle'    => array(
						'false' => array(
							'hide' => array(
								'#input-tags'
							)
						),
						'true' => array(
							'show' => array(
								'#input-tags'
							)
						)
					)
				),
				'tags' => array(
					'type' => 'multi-select',
					'name' => 'tags',
					'label' => __('Tags', 'upfront'),
					'tooltip' => '',
					'options' => 'get_tags()'
				),


				'post-type' => array(
					'type' => 'multi-select',
					'name' => 'post-type',
					'label' => __('Post-Typ', 'upfront'),
					'tooltip' => '',
					'options' => 'get_post_types()',
					'callback' => 'reloadBlockOptions(block.id)'
				),

				'post-status' => array(
					'type' => 'multi-select',
					'name' => 'post-status',
					'label' => __('Post Status', 'upfront'),
					'tooltip' => '',
					'options' => 'get_post_status()'
				),

				'author' => array(
					'type' => 'multi-select',
					'name' => 'author',
					'label' => __('Autor', 'upfront'),
					'tooltip' => '',
					'options' => 'get_authors()'
				),

				'number-of-posts' => array(
					'type' => 'integer',
					'name' => 'number-of-posts',
					'label' => __('Anzahl der Beiträge', 'upfront'),
					'tooltip' => '',
					'default' => 10
				),

				'offset' => array(
					'type' => 'integer',
					'name' => 'offset',
					'label' => __('Versatz', 'upfront'),
					'tooltip' => __('Der Versatz ist die Anzahl der Einträge oder Beiträge, die Du überspringen möchtest. Wenn der Versatz 1 ist, wird der erste Beitrag übersprungen.', 'upfront'),
					'default' => 0
				),

				'order-by' => array(
					'type' => 'select',
					'name' => 'order-by',
					'label' => __('Sortieren nach', 'upfront'),
					'tooltip' => '',
					'options' => array(
						'date' => __('Datum', 'upfront'),
						'title' => __('Titel', 'upfront'),
						'rand' => __('Zufällig', 'upfront'),
						'comment_count' => __('Anzahl Kommentare', 'upfront'),
						'ID' => 'ID',
						'author' => __('Autor', 'upfront'),
						'type' => __('Post-Typ', 'upfront'),
						'menu_order' => __('Benutzerdefinierte Sortierung', 'upfront')
					)
				),

				'order' => array(
					'type' => 'select',
					'name' => 'order',
					'label' => __('Anordnung', 'upfront'),
					'tooltip' => '',
					'options' => array(
						'desc' => __('Absteigend', 'upfront'),
						'asc' => __('Aufsteigend', 'upfront'),
					)
				),
				'byid-include' => array(
					'type' => 'text',
					'name' => 'byid-include',
					'label' => __('Nach ID einschließen', 'upfront'),
					'tooltip' => __('Sowohl beim Einschließen als auch beim Ausschließen nach ID verwende eine durch Kommas getrennte Liste von IDs Deines Beitragstyps.', 'upfront')
					),

				'byid-exclude' => array(
					'type' => 'text',
					'name' => 'byid-exclude',
					'label' => __('Nach ID ausschließen', 'upfront'),
					'tooltip' => __('Sowohl beim Einschließen als auch beim Ausschließen nach ID verwende eine durch Kommas getrennte Liste von IDs Deines Beitragstyps.', 'upfront')
				)
			),

			'display' => array(
				'read-more-text' => array(
					'type' => 'text',
					'label' => __('Lese mehr Text', 'upfront'),
					'name' => 'read-more-text',
					'default' => __('Weiterlesen', 'upfront'),
					'tooltip' => __('Wenn Auszüge angezeigt werden oder ein empfohlener Beitrag mit read more shortcode abgeschnitten wird, wird dieser nach dem Auszug oder dem abgeschnittenen Inhalt angezeigt.', 'upfront')
				),

				'show-titles' => array(
					'type' => 'checkbox',
					'name' => 'show-titles',
					'label' => __('Titel anzeigen', 'upfront'),
					'default' => true,
					'tooltip' => __('Wenn Du nur den Inhalt und das Meta des Eintrags anzeigen möchtest, kannst Du die Titel des Eintrags (Beitrag oder Seite) mit dieser Option ausblenden.', 'upfront')
				),

				'link-titles'  => array(
					'type' => 'checkbox',
					'name' => 'link-titles',
					'label' => __('Titel verlinken?', 'upfront'),
					'default' => true,
					'tooltip' => __('Wenn Du den Link zu Post-/Seitentiteln deaktivieren möchtest, deaktiviere diese Option', 'upfront')
				),

				'show-archive-title'  => array(
					'type' => 'checkbox',
					'name' => 'show-archive-title',
					'label' => __('Archivtitel anzeigen?', 'upfront'),
					'default' => true,
					'tooltip' => __('Wenn Du den Seitentitel in Archivlayouts (z.B. Kategorie, Tag usw.) deaktivieren möchtest, deaktiviere diese Option', 'upfront')
				),

				'show-archive-title-type' => array(
					'type' => 'select',
					'name' => 'show-archive-title-type',
					'default' => 'normal',
					'options' => array(
						'normal' => 'Normal',
						'only-archive-name' => __('Nur Archivname', 'upfront'),
						'show-custom-archive-title' => __('Benutzerdefinierter Titel', 'upfront'),
					),
					'label' => __('Typ des Archivtitels', 'upfront'),
					'tooltip' => __('Zeige den normalen Titel, nur das Archiv (Kategorie, Tag usw.) oder den benutzerdefinierten Archivtitel an', 'upfront'),
					'toggle' => array(
						'normal' => array(
							'hide' => array(
								'#input-custom-archive-title',							
							)
						),
						'only-archive-name' => array(
							'hide' => array(
								'#input-custom-archive-title',
							)
						),
						'show-custom-archive-title' => array(
							'show' => array(
								'#input-custom-archive-title',
							)
						),
					)


				),

				'custom-archive-title'  => array(
					'type' => 'text',
					'name' => 'custom-archive-title',
					'label' => __('Benutzerdefinierter Archivtitel', 'upfront'),
					'tooltip' => __('Verwende einen benutzerdefinierten Titel für das Archiv. Benutze %archive% für Kategorie, tag, etc: Beispiel: "Archiv der Kategorie: %archive%"', 'upfront')				
				),

				'show-readmore' => array(
					'type' => 'checkbox',
					'name' => 'show-readmore',
					'label' => __('Zeige Weiterlesen', 'upfront'),
					'default' => true,
					'tooltip' => __('Ein- und Ausblenden des Weiterlesens oder Lesen von mehr Text/Schaltfläche.', 'upfront')
				),

				'entry-content-display' => array(
					'type' => 'select',
					'name' => 'entry-content-display',
					'label' => __('Anzeige des Eintragsinhalts', 'upfront'),
					'tooltip' => __('Der Eintragsinhalt ist der eigentliche Hauptteil des Eintrags. Dies geben Sie beim Erstellen eines Eintrags (Beitrag oder Seite) in den Rich-Text-Bereich ein. Bei normaler Einstellung bestimmt UpFront anhand der Einstellung <em> Empfohlene Beiträge </ em> und der angezeigten Seite, ob vollständige Einträge oder Auszüge angezeigt werden sollen. <br /> <br /> <strong>Tipp:</strong> Setze dies auf <em>Eintragsinhalt ausblenden</em>, um eine einfache Liste der Beiträge zu erstellen.', 'upfront'),
					'default' => 'normal',
					'options' => array(
						'normal' => 'Normal',
						'full-entries' => __('Vollständige Einträge anzeigen', 'upfront'),
						'excerpts' => __('Auszüge anzeigen', 'upfront'),
						'hide' => __('Eintragsinhalt ausblenden', 'upfront')
					),
					'toggle' => array(
						'normal' => array(
							'show' => array(
								'#input-custom-excerpts-heading',
								'#input-custom-excerpts',
							)
						),
						'excerpts' => array(
							'show' => array(
								'#input-custom-excerpts-heading',
								'#input-custom-excerpts',
							)
						),
						'full-entries' => array(
							'hide' => array(
								'#input-custom-excerpts-heading',
								'#input-custom-excerpts',
							)
						),
						'hide' => array(
							'hide' => array(
								'#input-custom-excerpts-heading',
								'#input-custom-excerpts',
							)
						)
					)
				),

				'show-entry' => array(
					'type' => 'checkbox',
					'name' => 'show-entry',
					'label' => __('Eintrag anzeigen', 'upfront'),
					'default' => true,
					'tooltip' => __('Standardmäßig werden die Einträge immer angezeigt. Es kann jedoch bestimmte Fälle geben, in denen Du den Eintragsinhalt in einem Inhaltsblock anzeigen möchtest, die Kommentare jedoch in einem anderen. Mit dieser Option kannst Du das tun.', 'upfront')
				),

				'comments-visibility' => array(
					'type' => 'select',
					'name' => 'comments-visibility',
					'label' => __('Kommentare Sichtbarkeit', 'upfront'),
					'default' => 'auto',
					'options' => array(
						'auto' => 'Automatic',
						'hide' => __('Kommentare immer ausblenden', 'upfront'),
						'show' => __('Kommentare immer anzeigen', 'upfront')
					),
					'tooltip' => __('Bei der Einstellung "Automatisch" werden die Kommentare nur auf einzelnen Beitragsseiten angezeigt. Es kann jedoch vorkommen, dass Du die Sichtbarkeit von Kommentaren erzwingen möchtest, um Kommentare auf Seiten zuzulassen. Du kannst die Kommentare auch ausblenden, wenn Du sie überhaupt nicht sehen möchtest.<br /><br /><strong>Tipp:</strong> Erstelle eindeutige Layouts, indem Du diese Option in Verbindung mit der Option "Eintrag anzeigen" verwendest um den Eintragsinhalt in einem Inhaltsblock und die Kommentare in einem anderen Inhaltsblock anzuzeigen.', 'upfront')
				),

				'featured-posts' => array(
					'type' => 'integer',
					'name' => 'featured-posts',
					'label' => __('Beliebte Beiträge', 'upfront'),
					'default' => 1,
					'tooltip' => __('Empfohlene Beiträge sind die Beiträge, in denen der gesamte Inhalt angezeigt wird, sofern dies nicht durch die Verwendung des WordPress-Tags more eingeschränkt wird. Nachdem die vorgestellten Beiträge angezeigt wurden, wechselt der Inhalt automatisch zu automatisch abgeschnittenen Ausschnitten.', 'upfront')
				),

				'paginate' => array(
					'type' => 'checkbox',
					'name' => 'paginate',
					'label' => __('Ältere/Neuere Beiträge anzeigen Navigation', 'upfront'),
					'tooltip' => __('Zu Archivlayouts: Zeige Links am unteren Rand der Schleife an, damit der Besucher ältere oder neuere Beiträge anzeigen kann.', 'upfront'),
					'default' => true
				),

				'show-single-post-navigation' => array(
					'type' => 'checkbox',
					'name' => 'show-single-post-navigation',
					'label' => __('Single Beitrag Navigation anzeigen', 'upfront'),
					'default' => true,
					'tooltip' => __('Standardmäßig zeigt UpFront Links zu den vorherigen und nächsten Beiträgen unter einem Eintrag an, wenn jeweils nur ein Eintrag angezeigt wird. Du kannst diese Links mit dieser Option ausblenden.', 'upfront'),
					'toggle' => array(

						'true' => array(
							'show' => '#input-show-single-post-navigation-enable-tax'
							),
						'false' => array(
							'hide' => array(
							'#input-show-single-post-navigation-enable-tax',
							'#input-show-single-post-navigation-tax'
							)
						)
					),

				),

				'show-single-post-navigation-enable-tax' => array(
					'type' => 'checkbox',
					'name' => 'show-single-post-navigation-enable-tax',
					'label' => __('Single Beitrag Navigation: Gleiche Tax?', 'upfront'),
					'default' => false,
					'tooltip' => __('Wenn Du die Navigation für einzelne Beiträge anzeigen aktiviert hast, zeigt WordPress/UpFront standardmäßig Links zum nächsten und vorherigen Beitrag in chronologischer Reihenfolge an. Wenn Du möchtest, dass die nächsten/vorherigen Beiträge nur auf Beiträge in derselben Taxonomie wie der aktuelle Beitrag verweisen, aktiviere diese Option.', 'upfront'),
					'toggle' => array(

						'true' => array(
							'show' => '#input-show-single-post-navigation-tax'
							),
						'false' => array(
							'hide' => '#input-show-single-post-navigation-tax'
							)
					),
				),

				'show-single-post-navigation-tax' => array(
					'type' => 'select',
					'name' => 'show-single-post-navigation-tax',
					'label' => __('Taxonomie für die Navigation nach einer einzelnen Navigation', 'upfront'),
					'default' => 'category',
					'tooltip' => __('Wenn Du Gleiche Tax für Single Post Navigation aktiviert hast, kannst Du auswählen, auf welche Taxonomie sie angewendet werden soll. Standardmäßig gilt dies für die Kategorietaxonomie.', 'upfront'),
					'options' => 'get_taxonomies()'
				),

				'show-edit-link' => array(
					'type' => 'checkbox',
					'name' => 'show-edit-link',
					'label' => __('Bearbeitungslink anzeigen', 'upfront'),
					'default' => true,
					'tooltip' => __('Der Bearbeitungslink ist ein praktischer Link, der neben dem Beitragstitel angezeigt wird. Du gelangst direkt zum WordPress-Administrator, um den Eintrag zu bearbeiten.', 'upfront')
				),

				'custom-excerpts-heading' => array(
					'name' => 'custom-excerpts-heading',
					'type' => 'heading',
					'label' => __('Benutzerdefinierte Auszüge', 'upfront')
				),

				'custom-excerpts' => array(
					'type' => 'checkbox',
					'name' => 'custom-excerpts',
					'label' => __('Benutzerdefinierte Auszugslänge', 'upfront'),
					'default' => false,
					'tooltip' => __('Standardmäßig sind die Auszüge auf 55 Wörter eingestellt. Dies kann viel zu viel sein und ein PHP-Hook muss gesetzt werden, um es zu ändern. Stattdessen kannst Du hier einen benutzerdefinierten Betrag festlegen, indem Du die Anzahl der Wörter angibst, die Du anzeigen möchtest.', 'upfront'),
					'toggle' => array(
						'true' => array(
							'show' => '#input-excerpts-length'
							),
						'false' => array(
							'hide' => '#input-excerpts-length'
							)
					),
				),

				'excerpts-length' => array(
					'type' => 'integer',
					'name' => 'excerpts-length',
					'label' => __('Auszugslänge', 'upfront'),
					'default' => '55',
					'tooltip' => __('Kontrolliere die Länge des Auszugs. Standardmäßig werden 55 Wörter angezeigt. Diese Einstellung ermöglicht es Dir, diese Einstellung nach Belieben zu reduzieren oder sogar zu verlängern, und kann sehr praktisch sein, um das Erscheinungsbild Deiner Archivseiten anzupassen.', 'upfront')
				),

				'column-layout-heading' => array(
					'name' => 'column-layout-heading',
					'type' => 'heading',
					'label' => __('Spaltenlayout', 'upfront')
				),

				'enable-column-layout' => array(
					'type' => 'checkbox',
					'name' => 'enable-column-layout',
					'label' => __('Spaltenlayout aktivieren', 'upfront'),
					'default' => false,
					'tooltip' => __('Aktiviere diese Option, um Artikel nebeneinander als Spalten anzuzeigen.', 'upfront'),
					'toggle'    => array(
						'true' => array(
							'show' => array(
								'#input-posts-per-row',
								'#input-post-gutter-width',
								'#input-post-bottom-margin'
							)
						),
						'false' => array(
							'hide' => array(
								'#input-posts-per-row',
								'#input-post-gutter-width',
								'#input-post-bottom-margin'
							)
						)
					)
				),

				'posts-per-row' => array(
					'type' => 'slider',
					'name' => 'posts-per-row',
					'label' => 'Beiträge pro Zeile',
					'slider-min' => 1,
					'slider-max' => 10,
					'slider-interval' => 1,
					'tooltip' => '',
					'default' => 2,
					'tooltip' => __('Wie viele Beiträge pro Zeile angezeigt werden sollen.', 'upfront'),
					'callback' => ''
				),

				'post-gutter-width' => array(
					'type' => 'slider',
					'name' => 'post-gutter-width', 
					'label' => 'Stegbreite',
					'slider-min' => 0,
					'slider-max' => 100,
					'slider-interval' => 1,
					'default' => 15,
					'unit' => 'px',
					'tooltip' => __('Der Betrag des horizontalen Abstands zwischen den Beiträgen.', 'upfront')
				)
			),

			'custom-fields' => array(),

			'meta' => array(
				'show-entry-meta-post-types' => array(
					'type' => 'multi-select',
					'name' => 'show-entry-meta-post-types',
					'label' => __('Eintrags-Meta-Anzeige (pro Beitragstyp)', 'upfront'),
					'tooltip' => __('Wähle aus, auf welchen Beitragstypen das Eintrags-Meta angezeigt werden soll.', 'upfront'),
					'options' => 'get_post_types()',
					'default' => array('post')
				),

				'entry-meta-above' => array(
					'type' => 'textarea',
					'label' => __('Meta über dem Inhalt', 'upfront'),
					'name' => 'entry-meta-above',
					'default' => __('Veröffentlicht am %date% von %author% &bull; %comments%', 'upfront')
				),

				'entry-utility-below' => array(
					'type' => 'textarea',
					'label' => __('Meta unter Inhalt', 'upfront'),
					'name' => 'entry-utility-below',
					'default' => __('Abgelegt unter: %categories%', 'upfront')
				),

				'date-format' => array(
					'type' => 'select',
					'name' => 'date-format',
					'label' => __('Datumsformat', 'upfront')
				),

				'time-format' => array(
					'type' => 'select',
					'name' => 'time-format',
					'label' => __('Zeitformat', 'upfront')
				),

				'comments-meta-heading' => array(
					'name' => 'comments-meta-heading',
					'type' => 'heading',
					'label' => __('Kommentare Meta', 'upfront')
				),

					'comment-format' => array(
						'type' => 'text',
						'label' => __('Kommentarformat &ndash; Mehr als 1 Kommentar', 'upfront'),
						'name' => 'comment-format',
						'default' => '%num% Kommentare',
						'tooltip' => __('Steuert, was die Variablen %comment% und %comment_no_link% im Eintragsmeta ausgeben, wenn der Eintrag <strong>mehr als 1 Kommentar</strong> enthält.', 'upfront')
					),

					'comment-format-1' => array(
						'type' => 'text',
						'label' => __('Kommentarformat &ndash; 1 Kommentar', 'upfront'),
						'name' => 'comment-format-1',
						'default' => '%num% Comment',
						'tooltip' => __('Steuert, was die Variablen %comment% und %comment_no_link% im Eintragsmeta ausgeben, wenn der Eintrag <strong>nur 1 Kommentar</strong> enthält.', 'upfront')
					),

					'comment-format-0' => array(
						'type' => 'text',
						'label' => __('Kommentarformat &ndash; 0 Kommentare', 'upfront'),
						'name' => 'comment-format-0',
						'default' => '%num% Kommentare',
						'tooltip' => __('Steuert, was die Variablen %comment% und %comment_no_link% im Eintragsmeta ausgeben, wenn der Eintrag <strong>0 Kommentare</strong> enthält.', 'upfront')

					),

					'respond-format' => array(
						'type' => 'text',
						'label' => __('Antwortformat', 'upfront'),
						'name' => 'respond-format',
						'default' => __('Hinterlasse einen Kommentar!', 'upfront'),
						'tooltip' => __('Bestimmt die Variable %reply% für das Eintragsmeta.', 'upfront')
					)
			),

			'comments' => array(
				'comments-area' => array(
					'name' => 'comments-area',
					'type' => 'heading',
					'label' => __('Kommentare Bereichsüberschrift', 'upfront')
				),

					'comments-area-heading' => array(
						'type' => 'text',
						'label' => __('Kommentare Bereich Überschriftenformat', 'upfront'),
						'name' => 'comments-area-heading',
						'default' => '%responses% zu <em>%title%</em>',
						'tooltip' => __('Überschrift vor allen Kommentaren.
						<br />
						<br /><strong>Verfügbare Variablen:</strong>
						<ul>
							<li>%responses%</li>
							<li>%title%</li>
						</ul>', 'upfront')
					),

					'comments-area-heading-responses-number' => array(
						'type' => 'text',
						'label' => __('Antwortformat &ndash; Mehr als 1 Kommentar', 'upfront'),
						'name' => 'comments-area-heading-responses-number',
						'default' => '%num% Antworten',
						'tooltip' => __('Steuert, was die Variable %answers% in der Überschrift des Kommentarbereichs ausgibt, wenn der Eintrag <strong>mehr als 1 Kommentar</strong> enthält.', 'upfront')
					),

					'comments-area-heading-responses-number-1' => array(
						'type' => 'text',
						'label' => __('Antwortformat &ndash; 1 Kommentar', 'upfront'),
						'name' => 'comments-area-heading-responses-number-1',
						'default' => __('Eine Antwort', 'upfront'),
						'tooltip' => __('Steuert, was die Variable %answers% in der Überschrift des Kommentarbereichs ausgibt, wenn der Eintrag <strong>nur 1 Kommentar</strong> enthält.', 'upfront')
					),

				'reply-area-heading' => array(
					'name' => 'reply-area-heading',
					'type' => 'heading',
					'label' => __('Antwortbereich', 'upfront')
				),

					'leave-reply' => array(
						'type' => 'text',
						'label' => __('Titel des Kommentarformulars', 'upfront'),
						'name' => 'leave-reply',
						'default' => __('Hinterlasse einen Kommentar', 'upfront'),
						'tooltip' => __('Dies ist der Text, der über dem Kommentarformular angezeigt wird.', 'upfront')
					),

					'leave-reply-to' => array(
						'type' => 'text',
						'label' => __('Antwortformular Titel', 'upfront'),
						'name' => 'leave-reply-to',
						'default' => __('Hinterlasse eine Antwort an %s', 'upfront'),
						'tooltip' => __('Der Titel des Kommentarformulars bei der Beantwortung eines Kommentars.', 'upfront')
					),

					'cancel-reply-link' => array(
						'type' => 'text',
						'label' => 'Kommentar verwerfen Text',
						'name' => 'cancel-reply-link',
						'default' => 'Kommentar verwerfen',
						'tooltip' => 'Der Text für die Schaltfläche Kommentar abbrechen.'
					),

					'label-submit-text' => array(
						'type' => 'text',
						'label' => __('Einreichen Text', 'upfront'),
						'name' => 'label-submit-text',
						'default' => __('Kommentar hinzufügen', 'upfront'),
						'tooltip' => __('Der Text der Schaltfläche "Senden".', 'upfront')
					)
			),

			'post-thumbnails' => array(

				'show-post-thumbnails' => array(
					'type' => 'checkbox',
					'name' => 'show-post-thumbnails',
					'label' => __('Ausgewählte Bilder anzeigen', 'upfront'),
					'default' => true,
					'toggle'    => array(
						'true' => array(
							'show' => array(
								'#input-featured-image-as-background'
							)
						),
						'false' => array(
							'hide' => array(
								'#input-featured-image-as-background'
							)
						)
					)
				),

				'featured-image-as-background' => array(
					'type' => 'checkbox',
					'name' => 'featured-image-as-background',
					'label' => __('Verwende das ausgewählte Bild als Hintergrund', 'upfront'),
					'default' => false,
					'toggle'    => array(
						'true' => array(
							'show' => array(
								'#input-featured-image-as-background-overlay',
							)
						),
						'false' => array(
							'hide' => array(
								'#input-featured-image-as-background-overlay'
							)
						),
					),
				),

				'featured-image-as-background-overlay' => array(
					'type' => 'colorpicker',
					'name' => 'featured-image-as-background-overlay',
					'label' => __('Ausgewähltes Bild Überlagerung', 'upfront'),
					'default' => '00000003',
				),

				'featured-image-as-background-overlay-hover' => array(
					'type' => 'colorpicker',
					'name' => 'featured-image-as-background-overlay-hover',
					'label' => __('Ausgewähltes Bild Überlagerung Hover', 'upfront'),
					'default' => '00000000',
				),

				'post-thumbnails-link' => array(
					'type' => 'select',
					'name' => 'post-thumbnails-link',
					'label' => __('Link Ausgewähltes Bild', 'upfront'),
					'default' => 'entry',
					'options' => array(
						'entry' => __('Eintrag (Standard)', 'upfront'),
						'media' => __('Anhang Seite', 'upfront'),
						'none' => __('Nichts', 'upfront'),
						'custom' => __('Benutzerdefiniert', 'upfront'),
					),
					'toggle'    => array(
						'custom' => array(
							'show' => array(
								'#input-post-thumbnails-custom-link',
								'#input-post-thumbnails-link-new-tab'
							)
						),
						'entry' => array(
							'show' => array(								
								'#input-post-thumbnails-link-new-tab'
							),
							'hide' => array(
								'#input-post-thumbnails-custom-link'
							)
						),
						'media' => array(
							'show' => array(								
								'#input-post-thumbnails-link-new-tab'
							),
							'hide' => array(
								'#input-post-thumbnails-custom-link'
							)
						),
						'none' => array(
							'hide' => array(
								'#input-post-thumbnails-custom-link',
								'#input-post-thumbnails-link-new-tab'
							)
						),
					),
					'tooltip' => __('Standardmäßig erstellt UpFront einen Link um das ausgewählte Bild, der auf den Beitrag verweist. Wähle stattdessen keinen Link oder einen Link zur Anhangsseite des Bildes.', 'upfront')
				),

				'post-thumbnails-custom-link' => array(
					'type' => 'text',
					'label' => __('Benutzerdefinierten Link', 'upfront'),
					'name' => 'post-thumbnails-custom-link',
					'default' => '',
				),

				'post-thumbnails-link-new-tab' => array(
					'type' => 'checkbox',
					'label' => __('In neuem Tab öffnen', 'upfront'),
					'name' => 'post-thumbnails-link-new-tab',
					'default' => '',
				),

				'post-thumbnail-position' => array(
					'type' => 'select',
					'name' => 'post-thumbnail-position',
					'label' => __('Bildposition', 'upfront'),
					'default' => 'left',
					'options' => array(
						'left' => __('Links vom Titel', 'upfront'),
						'right' => __('Rechts vom Titel', 'upfront'),
						'left-content' => __('Links vom Inhalt', 'upfront'),
						'right-content' => __('Rechts vom Inhalt', 'upfront'),
						'above-title' => __('Über dem Titel', 'upfront'),
						'above-content' => __('Über dem Inhalt', 'upfront'),
						'below-content' => __('Unter dem Inhalt', 'upfront')
					)
				),

				'use-entry-thumbnail-position' => array(
					'type' => 'checkbox',
					'name' => 'use-entry-thumbnail-position',
					'label' => __('Verwende pro Eintrag Position für ausgewähltes Bild', 'upfront'),
					'default' => true,
					'tooltip' => __('Im WordPress-Schreibbereich gibt es ein UpFront-Meta-Feld, mit dem Du die angezeigte Bildposition für den zu bearbeitenden Eintrag ändern kannst.<br /><br />Standardmäßig verwendet der Block diesen Wert, Du kannst dies jedoch deaktivieren. Damit wird immer die Miniaturansicht der Blöcke verwendet.', 'upfront')
				),

				'thumbnail-sizing-heading' => array(
					'name' => 'thumbnail-sizing-heading',
					'type' => 'heading',
					'label' => __('Ausgewählte Bildgröße', 'upfront')
				),

					'post-thumbnail-size' => array(
						'type' => 'slider',
						'name' => 'post-thumbnail-size',
						'label' => __('Ausgewählte Bildgröße (links/rechts)', 'upfront'),
						'default' => 125,
						'slider-min' => 20,
						'slider-max' => 400,
						'slider-interval' => 1,
						'tooltip' => __('Passe die Größe der ausgewählten Bildgrößen an. Dies wird sowohl für die Breite als auch für die Höhe der Bilder verwendet.', 'upfront'),
						'unit' => 'px'
					),

					'post-thumbnail-height-ratio' => array(
						'type' => 'slider',
						'name' => 'post-thumbnail-height-ratio',
						'label' => __('Ausgewähltes Bildhöhenverhältnis (über Titel/Inhalt)', 'upfront'),
						'default' => 35,
						'slider-min' => 10,
						'slider-max' => 200,
						'slider-interval' => 5,
						'tooltip' => __('Passe die Höhe der Ausgewählten Bilder an, wenn Du sie auf den obigen Titel oder über die Inhaltspositionen einstellst. Dieser Wert steuert, wie viel Prozent die Höhe des Bildes in Bezug auf die Breite des Blocks beträgt.<br /><br />Beispiel: Wenn die Blockbreite 500 Pixel und das Verhältnis 50% beträgt, wird die Größe des Feature-Bilds bestimmt 500x250 Pixel groß sein.', 'upfront'),
						'unit' => '%'
					),

					'crop-post-thumbnails' => array(
						'type' => 'checkbox',
						'name' => 'crop-post-thumbnails',
						'label' => __('Ausgewählte Bilder zuschneiden', 'upfront'),
						'default' => true
					)
			)

		);
	}


	function modify_arguments($args = false) {

		global $pluginbuddy_loopbuddy;

		if ( class_exists('pluginbuddy_loopbuddy') && isset($pluginbuddy_loopbuddy) ) {

			//Remove the old tabs
			unset($this->tabs['mode']);
			unset($this->tabs['meta']);
			unset($this->tabs['display']);
			unset($this->tabs['query-filters']);
			unset($this->tabs['post-thumbnails']);

			unset($this->inputs['mode']);
			unset($this->inputs['meta']);
			unset($this->inputs['display']);
			unset($this->inputs['query-filters']);
			unset($this->inputs['post-thumbnails']);

			//Add in new tabs
			$this->tabs['loopbuddy'] = 'LoopBuddy';

			$this->inputs['loopbuddy'] = array(
				'loopbuddy-query' => array(
					'type' => 'select',
					'name' => 'loopbuddy-query',
					'label' => __('LoopBuddy Query', 'upfront'),
					'options' => 'get_loopbuddy_queries()',
					'tooltip' => __('Wähle rechts eine LoopBuddy-Abfrage aus. Abfragen bestimmen, welche Inhalte (Beiträge, Seiten usw.) angezeigt werden. Du kannst Abfragen im WordPress-Administrator unter LoopBuddy ändern/hinzufügen.', 'upfront'),
					'default' => ''
				),

				'loopbuddy-layout' => array(
					'type' => 'select',
					'name' => 'loopbuddy-layout',
					'label' => __('LoopBuddy Layout', 'upfront'),
					'options' => 'get_loopbuddy_layouts()',
					'tooltip' => __('Wähle rechts ein LoopBuddy-Layout aus. Layouts bestimmen, wie die Abfrage angezeigt wird. Dies schließt die Reihenfolge des Inhalts in Bezug auf Titel, Meta usw. ein. Du kannst Layouts im WordPress-Administrator unter LoopBuddy ändern/hinzufügen.', 'upfront'),
					'default' => ''
				)
			);

			$this->tab_notices = array(
				'loopbuddy' => sprintf( __('<strong>Obwohl wir hier die Möglichkeit haben, ein LoopBuddy-Layout auszuwählen und abzufragen, empfehlen wir Dir, LoopBuddy über das <a href="%s" target="_blank"> Optionsfeld</a> zu konfigurieren.</ Strong><br /><br />Die folgenden Optionen sind nützlicher, wenn Du zwei Inhaltsblöcke in einem Layout verwenden und diese separat konfigurieren möchtest. <strong>Hinweis:</strong> Du MUSST eine Abfrage ausgewählt haben, um auch ein LoopBuddy-Layout auswählen zu können.', 'upfront'), admin_url('admin.php?page=pluginbuddy_loopbuddy') )
			);

			return;

		}

		if ( class_exists('SWP_Query') ) {

			$this->inputs['display']['swp-heading'] = array(
					'name'  => 'swp-heading',
					'type'  => 'heading',
					'label' => 'SearchWP'
			);

			$this->inputs['display']['swp-engine'] = array(
				'type'    => 'select',
				'name'    => 'swp-engine',
				'label'   => __('SearchWP Engine', 'upfront'),
				'options' => 'get_swp_engines()',
				'tooltip' => __('Wenn Du die Ergebnisse einer ergänzten SearchWP-Engine anzeigen möchtest, wähle die Engine hier aus.', 'upfront'),
				'default' => ''
			);

		}

		$this->inputs['meta']['date-format']['options'] = array(
			'wordpress-default' => 'WordPress Standard',
			'F j, Y' => date('F j, Y'),
			'm/d/y' => date('m/d/y'),
			'd/m/y' => date('d/m/y'),
			'M j' => date('M j'),
			'M j, Y' => date('M j, Y'),
			'F j' => date('F j'),
			'F jS' => date('F jS'),
			'F jS, Y' => date('F jS, Y')
		);

		$this->inputs['meta']['time-format']['options'] = array(
			'wordpress-default' => 'WordPress Standard',
			'g:i A' => date('g:i A'),
			'g:i A T' => date('g:i A T'),
			'g:i:s A' => date('g:i:s A'),
			'G:i' => date('G:i'),
			'G:i T' => date('G:i T')
		);


		/**
		 *
		 * Custom Fields support
		 *
		 */


		$post_types = $custom_fields = array();

		if( !empty($this->block['settings']['mode']) && $this->block['settings']['mode'] == 'custom-query' ){

			if( isset($this->block['settings']['post-type']) )
				$post_types = $this->block['settings']['post-type'];
			else
				$post_types = array('post');

		}else{
			$post_types = get_post_types();
		}


		$custom_fields = UpFrontQuery::get_meta($post_types);		

		if(count($custom_fields)==0){

			if($this->block['settings']['mode'] == 'custom-query')
				$this->tab_notices['custom-fields'] = __('Der ausgewählte Beitragstyp enthält keine benutzerdefinierten Felder.', 'upfront');
			else
				$this->tab_notices['custom-fields'] = __('Es sind keine benutzerdefinierten Felder angezeigt.', 'upfront');

		}else{

			$inputs = array();

			foreach ($custom_fields as $post_type => $fields) {

				$heading = 'custom-fields-'.$post_type.'-heading';

				$inputs[$heading] = array(
					'name' => $heading,
					'type' => 'heading',
					'label' => 'Benutzerdefinierte Felder für: "' . $post_type . '".'
				);

				foreach ($fields as $field_name => $posts_total) {

					// Custom field name
					$name = 'custom-field-show-' . $post_type . '-' . $field_name;

					// Custom field position
					$label = 'custom-field-label-' . $post_type . '-' . $field_name;					

					// Custom field position
					$position = 'custom-field-position-' . $post_type . '-' . $field_name;

					// Custom field input
					$inputs[$name] = array(
						'type' => 'checkbox',
						'name' => $name,
						'label' => 'Zeige "' . $field_name .'"',
						'tooltip' => 'Überprüfe dies, um die Anzeige zuzulassen ' . $field_name,
						'default' => false,
						'toggle'    => array(
							'false' => array(
								'hide' => array(
									'#input-' . $position,
									'#input-' . $label
								)
							),
							'true' => array(
								'show' => array(
									'#input-' . $position,
									'#input-' . $label
								)
							)
						)
					);					

					// Custom field label input
					$inputs[$label] = array(
						'type' => 'text',
						'name' => $label,
						'label' => '"'.$field_name .'" label',
						'default' => '',
					);

					// Custom field position input
					$inputs[$position] = array(
						'type' => 'select',
						'name' => $position,
						'label' => '"'.$field_name .'" position',
						'default' => 'below',
						'options' => array(
							'above' => 'Über',
							'after-title' => 'Nach dem Titel',
							'below' => 'Unter'
						)
					);

				}
			}

			$this->inputs['custom-fields'] = $inputs;

		}

	}


	function get_pages() {

		$page_options = array( __('&ndash; Nicht holen &ndash;', 'upfront') );

		$page_select_query = get_pages();

		foreach ($page_select_query as $page)
			$page_options[$page->ID] = $page->post_title;

		return $page_options;

	}


	function get_categories() {

		if( isset($this->block['settings']['post-type']) )
			return UpFrontQuery::get_categories($this->block['settings']['post-type']);
		else
			return array();

	}

	function get_tags() {

		$tag_options = array();
		$tags_select_query = get_terms('post_tag');
		foreach ($tags_select_query as $tag)
			$tag_options[$tag->term_id] = $tag->name;
		$tag_options = (count($tag_options) == 0) ? array('text'	 => __('Keine Tags verfügbar', 'upfront') ) : $tag_options;
		return $tag_options;

	}


	function get_authors() {

		$author_options = array();

		$authors = get_users(array(
			'orderby' => 'post_count',
			'order' => 'desc',
			'who' => 'authors'
		));

		foreach ( $authors as $author )
			$author_options[$author->ID] = $author->display_name;

		return $author_options;

	}


	function get_post_types() {

		$post_type_options = array();

		$post_types = get_post_types(false, 'objects'); 

		foreach($post_types as $post_type_id => $post_type){

			//Make sure the post type is not an excluded post type.
			if(in_array($post_type_id, array('revision', 'nav_menu_item'))) 
				continue;

			$post_type_options[$post_type_id] = $post_type->labels->name;

		}

		return $post_type_options;

	}

	function get_taxonomies() {

		$taxonomy_options = array('&ndash; Standard: Kategorie &ndash;');

		$taxonomy_select_query=get_taxonomies(false, 'objects', 'or');


		foreach ($taxonomy_select_query as $taxonomy)
			$taxonomy_options[$taxonomy->name] = $taxonomy->label;


		return $taxonomy_options;

	}

	function get_post_status() {

		return get_post_stati();

	}


	function get_swp_engines() {

		$options = array( __('&ndash; Wähle eine Engine &ndash;', 'upfront') );

		if ( !function_exists('SWP') ) {
			return $options;
		}

		$searcbtp = SWP();

		if ( !is_array( $searcbtp->settings['engines']) ) {
			return $options;
		}

		foreach ( $searcbtp->settings['engines'] as $engine => $engine_settings ) {

			if ( empty( $engine_settings['searcbtp_engine_label'] ) ) {
				continue;
			}

			$options[$engine] = $engine_settings['searcbtp_engine_label'];

		}

		return $options;

	}


	function get_loopbuddy_queries() {

		$loopbuddy_options = get_option('pluginbuddy_loopbuddy');

		$queries = array(
			'' => __('&ndash; Standardabfrage verwenden &ndash;', 'upfront')
		);

		foreach ( $loopbuddy_options['queries'] as $query_id => $query ) {

			$queries[$query_id] = $query['title'];

		}

		return $queries;

	}


	function get_loopbuddy_layouts() {

		$loopbuddy_options = get_option('pluginbuddy_loopbuddy');

		$layouts = array(
			'' => __('&ndash; Standardlayout verwenden &ndash;', 'upfront')
		);

		foreach ( $loopbuddy_options['layouts'] as $layout_id => $layout ) {

			$layouts[$layout_id] = $layout['title'];

		}

		return $layouts;

	}
}
