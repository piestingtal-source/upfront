<?php 

class UpFrontContentSliderBlock extends UpFrontBlockAPI {

    public $id;    
    public $name;
    public $options_class;
	public $description;
    public $categories;
	function __construct(){

		$this->id = 'content-slider-block';	
		$this->name = __('Content Slider', 'upfront');
		$this->options_class = 'UpFrontContentSliderBlockOptions';
		$this->description 	= __('Der Content Slider verwandelt Deine Beiträge in einen Slider in verschiedenen Stilen.', 'upfront');
		$this->categories = array('content','galerie');

	}
    
			
	function setup_elements() {
		
		$this->register_block_element(array(
			'id' => 'slide',
			'name' => 'Slide',
			'selector' => '.owl-item'
		));

		$this->register_block_element(array(
			'id' => 'slide-p',
			'name' => 'Slide Text',
			'selector' => '.owl-item p'
		));

		$this->register_block_element(array(
			'id' => 'slide-a',
			'name' => 'Slide Link',
			'selector' => '.owl-item a'
		));

		$this->register_block_element(array(
			'id' => 'slide-h1',
			'name' => 'Slide H1',
			'selector' => '.owl-item h1'
		));

		$this->register_block_element(array(
			'id' => 'slide-h2',
			'name' => 'Slide H2',
			'selector' => '.owl-item h2'
		));

		$this->register_block_element(array(
			'id' => 'slide-h3',
			'name' => 'Slide H3',
			'selector' => '.owl-item h3'
		));

		$this->register_block_element(array(
			'id' => 'slide-h4',
			'name' => 'Slide H4',
			'selector' => '.owl-item h4'
		));

		$this->register_block_element(array(
			'id' => 'slide-h5',
			'name' => 'Slide H5',
			'selector' => '.owl-item h5'
		));

		$this->register_block_element(array(
			'id' => 'slide-ul',
			'name' => 'Slide UL',
			'selector' => '.owl-item ul'
		));


		$this->register_block_element(array(
			'id' => 'slide-li',
			'name' => 'Slide LI',
			'selector' => '.owl-item li'
		));

		$this->register_block_element(array(
			'id' => 'dots',
			'name' => 'Punkte',
			'selector' => '.owl-dots'
		));

		$this->register_block_element(array(
			'id' => 'dots-item',
			'name' => 'Punkte Element',
			'selector' => '.owl-dots .owl-dot'
		));

		$this->register_block_element(array(
			'id' => 'nav',
			'name' => 'Navigation',
			'selector' => '.owl-nav'
		));

		$this->register_block_element(array(
			'id' => 'nav-item',
			'name' => 'Navigationselement',
			'selector' => '.owl-nav div'
		));

		$this->register_block_element(array(
			'id' => 'nav-item-next',
			'name' => 'Navigation Weiter',
			'selector' => '.owl-nav div.owl-next'
		));

		$this->register_block_element(array(
			'id' => 'nav-item-prev',
			'name' => 'Navigation Zurück',
			'selector' => '.owl-nav div.owl-prev'
		));

		

	}

	public static function enqueue_action($block_id, $block) {

		wp_enqueue_style('upfront-content-slider-owl-carousel-css', upfront_url() . '/library/blocks/content-slider/css/owl.carousel.min.css');
		wp_enqueue_style('upfront-content-slider-owl-theme-css', upfront_url() . '/library/blocks/content-slider/css/owl.theme.default.min.css');
		//wp_enqueue_style('upfront-content-slider-owl-theme-green-css', plugin_dir_url( __FILE__ ).'css/owl.theme.green.min.css');
		wp_enqueue_script('upfront-content-slider-slider-js', upfront_url() . '/library/blocks/content-slider/js/owl.carousel.min.js', array('jquery'), '1.0', false);
	}

	function content($block) {

		// Content
		$post_type 			= ( !empty($block['settings']['post-type']) ) ? $block['settings']['post-type']: 'post';
		$categories 		= ( !empty($block['settings']['categories']) ) ? $block['settings']['categories']: array();
		$order_by 			= ( !empty($block['settings']['order-by']) ) ? $block['settings']['order-by']: 'date';
		$order 				= ( !empty($block['settings']['order']) ) ? $block['settings']['order']: 'desc';
		$onlyShowTitle 		= ( !empty($block['settings']['only-title']) ) ? true: false;
		$linkTitle 			= ( !empty($block['settings']['link-title']) ) ? true: false;
		$onlyShowFeatured 	= ( !empty($block['settings']['only-featured']) ) ? true: false;
		$onlyShowExcerpt 	= ( !empty($block['settings']['only-excerpt']) ) ? true: false;
		$showLink 			= ( !empty($block['settings']['show-link']) ) ? true: false;
		$showLinkText		= ( !empty($block['settings']['show-link-text']) ) ? $block['settings']['show-link-text']: 'Show more';

		
		global $post;

		$args 	= array ( 
					'post_type' 		=> $post_type,
					//'posts_per_page' 	=> $number,
					'orderby' 			=> $order_by,
					'order' 			=> $order 
				);

		if(count($categories) > 0) {
			$args['tax_query'] = array(
				array(
						'taxonomy' 	=> 'category',
						'field' 	=> 'id',
						'terms' 	=> $categories 
					)
			);
		}

		$content_slider_query = new WP_Query( $args );


		$result = '<div id="content-slider-'.$block['id'].'" class="owl-carousel owl-theme">';

		while ( $content_slider_query->have_posts() ) : $content_slider_query->the_post();

			setup_postdata( $post );			

			if( !empty($block['settings']['item-width']) ){
				$itemTag = '<div class="item" style="width:'.$block['settings']['item-width'].'px">';
			}else{
				$itemTag = '<div class="item">';
			}

			if($onlyShowTitle){
				if($linkTitle){
					$result .= '<h3><a href="'.get_permalink().'">'.get_the_title().'</a></h3>';
				}else{
					$result .= '<h3>'.get_the_title().'</h3>';
				}
			}else{

				if($onlyShowFeatured && has_post_thumbnail()){
					$result .= $itemTag;
					$result .= get_the_post_thumbnail( 
						$post->ID, 
						'content-slider-thumb', 
						array( 
							'class' => "img-responsive",
							'alt' 	=> get_the_title(),
							'title' => get_the_title(),
						)
					);
					$result .= '</div>';
				
				}elseif (!$onlyShowFeatured && has_post_thumbnail() ) {
					
					$result .= $itemTag;
					$result .= get_the_post_thumbnail( 
						$post->ID, 
						'content-slider-thumb', 
						array( 
							'class' => "img-responsive",
							'alt' 	=> get_the_title(),
							'title' => get_the_title(),
						)
					);


					$result .= '<h3>'.get_the_title().'</h3>';
					if($onlyShowExcerpt){
						$result .= do_shortcode('<p>'.get_the_excerpt().'</p>');
					}else{
						$result .= do_shortcode('<p>'.get_the_content().'</p>');
					}

					if($showLink){
						$result .= '<a href='.get_the_permalink().'>' . $showLinkText . '</a>';
					}

					$result .= '</div>';
				
				}else{
					if($onlyShowExcerpt){
						$result .= $itemTag.do_shortcode('<p>'.get_the_excerpt().'</p>').'</div>';
					}else{
						$result .= $itemTag.do_shortcode('<p>'.get_the_content().'</p>').'</div>';
					}

					if($showLink){
						$result .= '<a href='.get_the_permalink().'>' . $showLinkText . '</a>';
					}
					
				}
			}


		endwhile;
		wp_reset_postdata();

		echo $result;
	}

	public static function dynamic_js($block_id, $block = false) {

		if ( !$block )
			$block = UpFrontBlocksData::get_block($block_id);

		// Settings
		$carouselParams = '';

		// Items
		$carouselParams .= 'items:' . ( !empty($block['settings']['items']) && $block['settings']['items'] > 0 ? $block['settings']['items'] : '3' ) . ', ';

		// Margin
		$carouselParams .= 'margin:' . ( !empty($block['settings']['margin']) ? $block['settings']['margin'] : '0' ) . ', ';

		// Loop
		$carouselParams .= 'loop:' . ( !empty($block['settings']['loop']) ? $block['settings']['loop'] : 'false' ) . ', ';

		// Center
		$carouselParams .= 'center:' . ( !empty($block['settings']['center']) ? $block['settings']['center'] : 'false' ) . ', ';

		// mouse-drag
		$carouselParams .= 'mouseDrag:' . ( !empty($block['settings']['mouse-drag']) ? $block['settings']['mouse-drag'] : 'true' ) . ', ';

		// touch-drag
		$carouselParams .= 'touchDrag:' . ( !empty($block['settings']['touch-drag']) ? $block['settings']['touch-drag'] : 'true' ) . ', ';

		// pull-drag
		$carouselParams .= 'pullDrag:' . ( !empty($block['settings']['pull-drag']) ? $block['settings']['pull-drag'] : 'true' ) . ', ';

		// pull-drag
		$carouselParams .= 'freeDrag:' . ( !empty($block['settings']['free-drag']) ? $block['settings']['free-drag'] : 'false' ) . ', ';

		// stagePadding
		$carouselParams .= 'stagePadding:' . ( !empty($block['settings']['stage-padding']) ? $block['settings']['stage-padding'] : '0' ) . ', ';

		// merge
		$carouselParams .= 'merge:'. ( !empty($block['settings']['merge']) ? $block['settings']['merge']: 'false' ) . ', ';
		
		// mergeFit
		$carouselParams .= 'mergeFit:'. ( !empty($block['settings']['merge-fit']) ? $block['settings']['merge-fit']: 'true' ) . ', ';
		
		// autoWidth
		$carouselParams .= 'autoWidth:'. ( !empty($block['settings']['auto-width']) ? 'true': 'false' ) . ', ';
		
		// startPosition
		$carouselParams .= 'startPosition:'. ( !empty($block['settings']['start-position']) ? $block['settings']['start-position']: '0' ) . ', ';
		
		// startPosition
		$carouselParams .= 'URLhashListener:'. ( !empty($block['settings']['url-hash-listener']) ? $block['settings']['url-hash-listener']: '0' ) . ', ';
		
		// nav
		$carouselParams .= 'nav:'. ( !empty($block['settings']['nav']) ? $block['settings']['nav']: 'false' ) . ', ';

		// rewind
		$carouselParams .= 'rewind:'. ( !empty($block['settings']['rewind']) ? $block['settings']['rewind']: 'true' ) . ', ';

		// navText
		if( !empty($block['settings']['nav-text-next']) || !empty($block['settings']['nav-text-prev']) ){
			
			$navText_next	= ( !empty($block['settings']['nav-text-next']) ) ? $block['settings']['nav-text-next']: '>';
			$navText_prev	= ( !empty($block['settings']['nav-text-prev']) ) ? $block['settings']['nav-text-prev']: '<';
			$carouselParams .= 'navText:["'.$navText_prev.'","'.$navText_next.'"],';
		}else{
			$carouselParams .= 'navText:["Zurück","Weiter"],';			
		}

		// navElement
		$carouselParams .= 'navElement:'. ( !empty($block['settings']['nav-element']) ? $block['settings']['nav-element']: '"div"' ) . ', ';

		// slideBy
		$carouselParams .= 'slideBy:'. ( !empty($block['settings']['slide-by']) ? $block['settings']['slide-by']: '1' ) . ', ';

		// slideTransition
		$carouselParams .= 'slideTransition:'. ( !empty($block['settings']['slide-transition']) ? $block['settings']['slide-transition']: '``' ) . ', ';

		// dots
		$carouselParams .= 'dots:'. (!empty($block['settings']['dots']) ? $block['settings']['dots'] : 'true') . ', ';
		
		// dotsEach
		$carouselParams .= 'dotsEach:'. (!empty($block['settings']['dots-each']) ? $block['settings']['dots-each'] : 'false') . ', ';

		// dotsData
		$carouselParams .= 'dotsData:'. (!empty($block['settings']['dots-data']) ? $block['settings']['dots-data'] : 'false') . ', ';
		
		// lazyLoad
		$carouselParams .= 'lazyLoad:'. (!empty($block['settings']['lazy-load']) ? $block['settings']['lazy-load'] : 'false') . ', ';

		// lazyLoadEager
		$carouselParams .= 'lazyLoadEager:'. (!empty($block['settings']['lazy-load-eager']) ? $block['settings']['lazy-load-eager'] : '0') . ', ';
		
		// autoPlay
		$carouselParams .= 'autoPlay:'. (!empty($block['settings']['autoplay']) ? 'true' : 'false') . ', ';
		
		// autoplayTimeout
		$carouselParams .= 'autoplayTimeout:'. (!empty($block['settings']['autoplay-timeout']) ? $block['settings']['autoplay-timeout'] : '5000') . ', ';
		
		// autoplayHoverPause
		$carouselParams 	.= 'autoplayHoverPause:'. (!empty($block['settings']['autoplay-hover-pause']) ? $block['settings']['autoplay-hover-pause'] : 'false') . ', ';

		// smartSpeed
		$carouselParams 	.= 'smartSpeed:'. (!empty($block['settings']['smart-speed']) ? $block['settings']['smart-speed'] : '250') . ', ';		

		// fluidSpeed
		$carouselParams 	.= 'fluidSpeed:'. (!empty($block['settings']['fluid-speed']) ? $block['settings']['fluid-speed'] : 'Number') . ', ';		

		// autoplaySpeed
		$carouselParams 	.= 'autoplaySpeed:'. (!empty($block['settings']['autoplay-speed']) ? $block['settings']['autoplay-speed'] : 'false') . ', ';

		// navSpeed
		$carouselParams 	.= 'navSpeed:'. (!empty($block['settings']['nav-speed']) ? $block['settings']['nav-speed'] : 'false') . ', ';		

		// dotsSpeed
		$carouselParams 	.= 'dotsSpeed:'. (!empty($block['settings']['dots-speed']) ? $block['settings']['dots-speed'] : 'false') . ', ';		

		// dragEndSpeed
		$carouselParams 	.= 'dragEndSpeed:'. (!empty($block['settings']['dragend-speed']) ? $block['settings']['dragend-speed'] : 'false') . ', ';	

		// callbacks
		$carouselParams 	.= 'callbacks:'. (!empty($block['settings']['callbacks']) ? $block['settings']['callbacks'] : 'false') . ', ';
		
		// responsive
		//$carouselParams 	.= 'responsive:{ 0:{ items: 1 }, 480:{ items: 1 }, 640:{ items: 2 }, 1200:{ items: 3 }  }' . ', ';
				
		// responsiveRefreshRate
		$carouselParams 	.= 'responsiveRefreshRate:'. (!empty($block['settings']['responsive-refresh-rate']) ? $block['settings']['responsive-refresh-rate'] : '200') . ', ';
		
		// responsiveBaseElement
		$carouselParams 	.= 'responsiveBaseElement:'. (!empty($block['settings']['responsive-base-element']) ? $block['settings']['responsive-base-element'] : 'window') . ', ';
		
		// video
		$carouselParams 	.= 'video:'. (!empty($block['settings']['video']) ? $block['settings']['video'] : 'false') . ', ';

		// videoHeight
		$carouselParams 	.= 'videoHeight:'. (!empty($block['settings']['video-height']) ? $block['settings']['video-height'] : 'false') . ', ';
		
		// videoWidth
		$carouselParams 	.= 'videoWidth:'. (!empty($block['settings']['video-width']) ? $block['settings']['video-width'] : 'false') . ', ';

		// animateOut
		$carouselParams 	.= 'animateOut:'. (!empty($block['settings']['animate-out']) ? $block['settings']['animate-out'] : 'false') . ', ';

		// animateIn
		$carouselParams 	.= 'animateIn:'. (!empty($block['settings']['animate-in']) ? $block['settings']['animate-in'] : 'false') . ', ';
		
		// fallbackEasing
		$carouselParams 	.= 'fallbackEasing:'. (!empty($block['settings']['fallback-easing']) ? $block['settings']['fallback-easing'] : '"swing"') . ', ';
		
		// info
		$carouselParams 	.= 'info:'. (!empty($block['settings']['info']) ? $block['settings']['info'] : 'false') . ', ';
		
		// nestedItemSelector
		$carouselParams 	.= 'nestedItemSelector:'. (!empty($block['settings']['nested-item-selector']) ? $block['settings']['nested-item-selector'] : 'false') . ', ';
		
		// itemElement
		$carouselParams 	.= 'itemElement:'. (!empty($block['settings']['item-element']) ? $block['settings']['item-element'] : '"div"') . ', ';
		
		// stageElement
		$carouselParams .= 'stageElement:'. (!empty($block['settings']['stage-element']) ? $block['settings']['stage-element'] : '"div"') . ', ';
		
		// navContainer
		$carouselParams .= 'navContainer:'. (!empty($block['settings']['nav-container']) ? $block['settings']['nav-container'] : 'false') . ', ';
		
		// dotsContainer
		$carouselParams .= 'dotsContainer:'. (!empty($block['settings']['dots-container']) ? $block['settings']['dots-container'] : 'false') . ', ';
		
		// checkVisible
		$carouselParams .= 'checkVisible:'. (!empty($block['settings']['check-visible']) ? $block['settings']['check-visible'] : 'true');
			 
		$js = 'jQuery(document).ready(function($){';
		$js .= 'window.carousel_'.$block['id'].' = jQuery("#content-slider-'.$block['id'].'.owl-carousel").owlCarousel({';
		$js .= $carouselParams;
		$js .= '});});';

		return $js;
	}

	function custom_excerpt_post($text, $limit = 20){
		$excerpt = explode(' ', $text, $limit);

		if (count($excerpt)>=$limit) {
			
			array_pop($excerpt);
			$excerpt = implode(" ",$excerpt).'...';
		
		} else {
			$excerpt = implode(" ",$excerpt);

		}	
		$excerpt = preg_replace('`[[^]]*]`','',$excerpt);
		return $excerpt;
	}
	
}
class UpFrontContentSliderBlockOptions extends UpFrontBlockOptionsAPI {

	public $tabs = array(
		'content-tab' 	=> 'Inhalt',
		'slider-tab' 	=> 'Einstellungen',
	);


	public $inputs = array(

		'content-tab' => array(
			'post-type' => array(
				'type' => 'select',
				'name' => 'post-type',
				'label' => 'Welches Produkt',
				'options' => array(
					'none' 		=> 'Wähle Dein Produkt',
					'woo' 		=> 'WooCommerce',
					'cf7' 		=> 'Contact Form 7',
					'gravity' 	=> 'Gravity Forms',
					'price' 	=> 'Preistabellen'
				),
				'default' => 'post',
				'tooltip' => '',		
				'options' => 'get_post_types()',
				'callback' => ''
			),

			'categories' => array(
				'type' => 'multi-select',
				'name' => 'categories',
				'label' => 'Kategorien',
				'tooltip' => '',
				'options' => 'get_categories()'
			),

			'order-by' => array(
				'type' => 'select',
				'name' => 'order-by',
				'label' => 'Sortieren nach',
				'tooltip' => '',
				'options' => array(
					'date' => 'Datum',
					'title' => 'Titel',
					'rand' => 'Zufällig',
					'comment_count' => 'Anzahl Kommentare',
					'ID' => 'ID',
					'author' => 'Autor',
					'type' => 'Beitragstyp',
					'menu_order' => 'Sonder-Sortierung'
				)
			),
			
			'order' => array(
				'type' => 'select',
				'name' => 'order',
				'label' => 'Sortieren',
				'tooltip' => '',
				'options' => array(
					'desc' => 'Absteigend',
					'asc' => 'Aufsteigend',
				)
			),

			'only-title' => array(
				'type' 		=> 'checkbox',
				'default' 	=> false,
				'name' 		=> 'only-title',
				'label' 	=> 'Nur Titel anzeigen',
				'tooltip' 	=> 'Nur Titel anzeigen',
				'toggle'    => array(
					'false' => array(
						'hide' => array(
							'#input-link-title'
						)
					),
					'true' => array(
						'show' => array(
							'#input-link-title'
						)
					)
				)
			),

			'link-title' => array(
				'type' 		=> 'checkbox',
				'default' 	=> false,
				'name' 		=> 'link-title',
				'label' 	=> 'Titel als Link',
				'tooltip' 	=> 'Titel als Link',				
			),

			'only-featured' => array(
				'type' 		=> 'checkbox',
				'default' 	=> false,
				'name' 		=> 'only-featured',
				'label' 	=> 'Nur empfohlenes Bild anzeigen',
				'tooltip' 	=> 'Nur dem Imhalt empfohlenes Bild anzeigen',
			),

			'only-excerpt' => array(
				'type' 		=> 'checkbox',
				'default' 	=> false,
				'name' 		=> 'only-excerpt',
				'label' 	=> 'Nur Auszug zeigen',
				'tooltip' 	=> 'Nur Auszug zeigen',
			),
			'show-link' => array(
				'type' 		=> 'checkbox',
				'default' 	=> true,
				'name' 		=> 'show-link',
				'label' 	=> 'Link anzeigen',
				'tooltip' 	=> 'Inhaltslink anzeigen',
			),
			'show-link-text' => array(
				'type' 		=> 'text',
				'default' 	=> 'Show more',
				'name' 		=> 'show-link-text',
				'label' 	=> 'Linktext',
				'tooltip' 	=> 'Text für Link',
			),
		),
		'slider-tab' => array(

			'items' => array(
				'type' 		=> 'integer',
				'default' 	=> 3,
				'name' 		=> 'items',
				'label' 	=> 'Items to show',
				'tooltip' 	=> 'The number of items you want to see on the screen.',				
			),

			'margin' => array(
				'type' 		=> 'integer',
				'default' 	=> 0,
				'name' 		=> 'margin',
				'label' 	=> 'Item right margin',
				'tooltip' 	=> 'margin-right(px) on item.',
			),

			'loop' => array(
				'type' 		=> 'checkbox',
				'default' 	=> false,
				'name' 		=> 'loop',
				'label' 	=> 'Loop',
				'tooltip' 	=> 'Infinity loop. Duplicate last and first items to get loop illusion.',
			),

			'center' => array(
				'type' 		=> 'checkbox',
				'default' 	=> false,
				'name' 		=> 'center',
				'label' 	=> 'Center',
				'tooltip' 	=> 'Center item. Works well with even an odd number of items.',
			),

			'mouse-drag' => array(
				'type' 		=> 'checkbox',
				'default' 	=> false,
				'name' 		=> 'mouse-drag',
				'label' 	=> 'Mouse Drag',
				'tooltip' 	=> 'Mouse drag enabled.',
			),

			'touch-drag' => array(
				'type' 		=> 'checkbox',
				'default' 	=> false,
				'name' 		=> 'touch-drag',
				'label' 	=> 'Touch Drag',
				'tooltip' 	=> 'Touch drag enabled.',
			),
			
			'pull-drag' => array(
				'type' 		=> 'checkbox',
				'default' 	=> false,
				'name' 		=> 'pull-drag',
				'label' 	=> 'Pull Drag',
				'tooltip' 	=> 'Stage pull to edge.',
			),

			'free-drag' => array(
				'type' 		=> 'checkbox',
				'default' 	=> false,
				'name' 		=> 'free-drag',
				'label' 	=> 'Free Drag',
				'tooltip' 	=> 'Item pull to edge.',
			),

			'stage-padding' => array(
				'type' 		=> 'integer',
				'default' 	=> 0,
				'name' 		=> 'stage-padding',
				'label' 	=> 'Stage Padding',
				'tooltip' 	=> 'Padding left and right on stage (can see neighbours).',
			),

			'merge' => array(
				'type' 		=> 'checkbox',
				'default' 	=> false,
				'name' 		=> 'merge',
				'label' 	=> 'Merge',
				'tooltip' 	=> 'Merge items. Looking for data-merge=\'{number}\' inside item..',
			),

			'merge-fit' => array(
				'type' 		=> 'checkbox',
				'default' 	=> false,
				'name' 		=> 'merge-fit',
				'label' 	=> 'Merge Fit',
				'tooltip' 	=> 'Fit merged items if screen is smaller than items value.',
			),

			'auto-width' => array(
				'type' 		=> 'checkbox',
				'default' 	=> false,
				'name' 		=> 'auto-width',
				'label' 	=> 'Auto Width',
				'tooltip' 	=> 'Set non grid content. Try using width style on divs.',
			),

			'item-width' => array(
				'type' 		=> 'integer',
				'default' 	=> 800,
				'name' 		=> 'item-width',
				'label' 	=> 'Item Width',
				'tooltip' 	=> 'Number in px. Require Auto Width',
			),
			
			'start-position' => array(
				'type' 		=> 'integer',
				'default' 	=> 0,
				'name' 		=> 'start-position',
				'label' 	=> 'Start Position',
				'tooltip' 	=> 'Start position',
			),

			'url-hash-listener' => array(
				'type' 		=> 'checkbox',
				'default' 	=> false,
				'name' 		=> 'url-hash-listener',
				'label' 	=> 'URL hash Listener',
				'tooltip' 	=> 'Listen to url hash changes. data-hash on items is required.',
			),

			'nav' => array(
				'type' 		=> 'checkbox',
				'default' 	=> false,
				'name' 		=> 'nav',
				'label' 	=> 'Show next/prev buttons.',
				'tooltip' 	=> 'Show next/prev buttons.',
			),

			'rewind' => array(
				'type' 		=> 'checkbox',
				'default' 	=> false,
				'name' 		=> 'rewind',
				'label' 	=> 'Rewind',
				'tooltip' 	=> 'Go backwards when the boundary has reached.',
			),

			'nav-text-next' => array(
				'type' 		=> 'text',
				'default' 	=> null,
				'name' 		=> 'nav-text-next',
				'label' 	=> '"Weiter" text',
				'tooltip' 	=> 'HTML allowed.',
			),

			'nav-text-prev' => array(
				'type' 		=> 'text',
				'default' 	=> null,
				'name' 		=> 'nav-text-prev',
				'label' 	=> '"Zurück" text',
				'tooltip' 	=> 'HTML allowed.',
			),

			'nav-element' => array(
				'type' 		=> 'text',
				'default' 	=> 'div',
				'name' 		=> 'nav-element',
				'label' 	=> 'Nav element',
				'tooltip' 	=> 'DOM element type for a single directional navigation link.',
			),

			'slide-by' => array(
				'type' 		=> 'integer',
				'default' 	=> 1,
				'name' 		=> 'slide-by',
				'label' 	=> 'Slide By',
				'tooltip' 	=> 'Navigation slide by x. \'page\' string can be set to slide by page.',
			),

			'slide-transition' => array(
				'type' 		=> 'text',
				'default' 	=> null,
				'name' 		=> 'slide-transition',
				'label' 	=> 'Slide Transition',
				'tooltip' 	=> 'You can define the transition for the stage you want to use eg. linear.',
			),

			'dots' => array(
				'type' 		=> 'checkbox',
				'default' 	=> false,
				'name' 		=> 'dots',
				'label' 	=> 'Dots',
				'tooltip' 	=> 'Show dots navigation.',
			),

			'dots-each' => array(
				'type' 		=> 'integer',
				'default' 	=> 0,
				'name' 		=> 'dots-each',
				'label' 	=> 'Show dots each x item.',
				'tooltip' 	=> 'Show dots each x item.',
			),

			'dots-data' => array(
				'type' 		=> 'checkbox',
				'default' 	=> false,
				'name' 		=> 'dots-data',
				'label' 	=> 'Dots Data',
				'tooltip' 	=> 'Used by data-dot content.',
			),

			'lazy-load' => array(
				'type' 		=> 'checkbox',
				'default' 	=> false,
				'name' 		=> 'lazy-load',
				'label' 	=> 'Lazy Load',
				'tooltip' 	=> 'Lazy load images. data-src and data-src-retina for highres. Also load images into background inline style if element is not <img>',
			),

			'lazy-load-eager' => array(
				'type' 		=> 'integer',
				'default' 	=> 0,
				'name' 		=> 'lazy-load-eager',
				'label' 	=> 'Lazy Load Eager',
				'tooltip' 	=> 'Eagerly pre-loads images to the right (and left when loop is enabled) based on how many items you want to preload.',
			),

			'autoplay' => array(
				'type' 		=> 'checkbox',
				'default' 	=> false,
				'name' 		=> 'autoplay',
				'label' 	=> 'Auto Play',
				'tooltip' 	=> 'Autoplay',
			),

			'autoplay-timeout' => array(
				'type' 		=> 'integer',
				'default' 	=> 5000,
				'name' 		=> 'autoplay-timeout',
				'label' 	=> 'Auto Play Timeout',
				'tooltip' 	=> 'Autoplay interval timeout.',
			),

			'autoplay-hover-pause' => array(
				'type' 		=> 'checkbox',
				'default' 	=> false,
				'name' 		=> 'autoplay-hover-pause',
				'label' 	=> 'Pause on Hover',
				'tooltip' 	=> 'Pause on mouse hover.',
			),
			'autoplay-speed' => array(
				'type' 		=> 'integer',
				'default' 	=> 5000,
				'name' 		=> 'autoplay-speed',
				'label' 	=> 'Autoplay Speed.',
				'tooltip' 	=> 'Autoplay speed.',
			),
			/*
			'smartSpeed' => array(
				'type' 		=> 'integer',
				'default' 	=> 250,
				'name' 		=> 'smartSpeed',
				'label' 	=> 'Smart Speed',
				'tooltip' 	=> 'Speed Calculate. More info to come..',
			),

			'fluidSpeed' => array(
				'type' 		=> 'checkbox',
				'default' 	=> false,
				'name' 		=> 'fluidSpeed',
				'label' 	=> 'Fluid Speed',
				'tooltip' 	=> 'Speed Calculate. More info to come..',
			),

			'autoplaySpeed' => array(
				'type' 		=> 'checkbox',
				'default' 	=> false,
				'name' 		=> 'autoplaySpeed',
				'label' 	=> 'Autoplay speed.',
				'tooltip' 	=> 'Autoplay speed.',
			),

			'navSpeed' => array(
				'type' 		=> 'checkbox',
				'default' 	=> false,
				'name' 		=> 'navSpeed',
				'label' 	=> 'Nav speed.',
				'tooltip' 	=> 'Navigation speed.',
			),

			'dotsSpeed' => array(
				'type' 		=> 'checkbox',
				'default' 	=> false,
				'name' 		=> 'dotsSpeed',
				'label' 	=> 'Dots speed.',
				'tooltip' 	=> 'Pagination speed.',
			),

			'dragEndSpeed' => array(
				'type' 		=> 'checkbox',
				'default' 	=> false,
				'name' 		=> 'dragEndSpeed',
				'label' 	=> 'Drag End speed.',
				'tooltip' 	=> 'Drag end speed.',
			),
			*/

			'callbacks' => array(
				'type' 		=> 'checkbox',
				'default' 	=> false,
				'name' 		=> 'callbacks',
				'label' 	=> 'Callback.',
				'tooltip' 	=> 'Enable callback events.',
			),

			/*
				To DO
			'responsive' => array(
				'type' 		=> 'object',
				'default' 	=> '',
				'name' 		=> 'responsive',
				'label' 	=> 'Responsive',
				'tooltip' 	=> 'Object containing responsive options. Can be set to false to remove responsive capabilities.',
			),*/

			'responsive-refresh-rate' => array(
				'type' 		=> 'integer',
				'default' 	=> 200,
				'name' 		=> 'responsive-refresh-rate',
				'label' 	=> 'Responsive Refresh Rate',
				'tooltip' 	=> 'Responsive refresh rate.',
			),
			/*
				To Do
			'responsiveBaseElement' => array(
				'type' 		=> 'DOM element ',
				'default' 	=> 200,
				'name' 		=> 'responsiveBaseElement',
				'label' 	=> 'Responsive Base Element',
				'tooltip' 	=> 'Responsive refresh rate.',
			),
			*/


			'video' => array(
				'type' 		=> 'checkbox',
				'default' 	=> false,
				'name' 		=> 'video',
				'label' 	=> 'Video.',
				'tooltip' 	=> 'Enable fetching YouTube/Vimeo/Vzaar videos.',
			),

			'video-height' => array(
				'type' 		=> 'checkbox',
				'default' 	=> false,
				'name' 		=> 'video-height',
				'label' 	=> 'Video height.',
				'tooltip' 	=> 'Set height for videos.',
			),

			'video-width' => array(
				'type' 		=> 'checkbox',
				'default' 	=> false,
				'name' 		=> 'video-width',
				'label' 	=> 'Video width.',
				'tooltip' 	=> 'Set width for videos.',
			),

			'animate-out' => array(
				'type' 		=> 'text',
				'default' 	=> null,
				'name' 		=> 'animate-out',
				'label' 	=> 'AnimateOut Class',
				'tooltip' 	=> 'Class for CSS3 animation out.',
			),

			'animate-in' => array(
				'type' 		=> 'text',
				'default' 	=> null,
				'name' 		=> 'animate-in',
				'label' 	=> 'AnimateIn Class',
				'tooltip' 	=> 'Class for CSS3 animation in.',
			),

			'fallback-easing' => array(
				'type' 		=> 'text',
				'default' 	=> null,
				'name' 		=> 'fallback-easing',
				'label' 	=> 'Fallback Easing',
				'tooltip' 	=> 'Easing for CSS2 $.animate.',
			),
			/*

				To Do
			'info' => array(
				'type' 		=> 'function',
				'default' 	=> null,
				'name' 		=> 'info',
				'label' 	=> 'Fallback Easing',
				'tooltip' 	=> 'Callback to retrieve basic information (current item/pages/widths). Info function second parameter is Owl DOM object reference.',
			),
			*/

			'nested-item-selector' => array(
				'type' 		=> 'text',
				'default' 	=> null,
				'name' 		=> 'nested-item-selector',
				'label' 	=> 'Nested Item Selector',
				'tooltip' 	=> 'Use it if owl items are deep nested inside some generated content. E.g \'youritem\'. Dont use dot before class name.',
			),

			'item-element' => array(
				'type' 		=> 'text',
				'default' 	=> 'div',
				'name' 		=> 'item-element',
				'label' 	=> 'Item-Element',
				'tooltip' 	=> 'DOM-Elementtyp für owl-item.',
			),

			'stage-element' => array(
				'type' 		=> 'text',
				'default' 	=> 'div',
				'name' 		=> 'stage-element',
				'label' 	=> 'Stage-Element',
				'tooltip' 	=> 'DOM-Elementtyp für owl-stage.',
			),

			'nav-container' => array(
				'type' 		=> 'text',
				'default' 	=> null,
				'name' 		=> 'nav-container',
				'label' 	=> 'Navigationscontainer',
				'tooltip' 	=> 'Stelle Deinen eigenen Container für nav ein.',
			),

			'dots-container' => array(
				'type' 		=> 'text',
				'default' 	=> null,
				'name' 		=> 'dots-container',
				'label' 	=> 'Punktebehälter',
				'tooltip' 	=> 'Stelle Deinen eigenen Container für nav ein.',
			),

			'check-visible' => array(
				'type' 		=> 'checkbox',
				'default' 	=> false,
				'name' 		=> 'check-visible',
				'label' 	=> 'Überprüfe Sichtbarkeit.',
				'tooltip' 	=> 'Wenn Du weist, dass das Karussell immer sichtbar ist, kannst Du "checkVisibility" auf "false" setzen, um zu verhindern, dass das Browser-Layout das $element.is(\':visible\') erzwingt.',
			),
		),

	);

	function get_categories() {
		
		$category_options 			= array();		
		$categories_select_query 	= get_categories();
		
		foreach ($categories_select_query as $category)
			$category_options[$category->term_id] = $category->name;

		return $category_options;	
	}

	function get_tags() {
		
		$tag_options = array();
		$tags_select_query = get_terms('post_tag');
		foreach ($tags_select_query as $tag)
			$tag_options[$tag->term_id] = $tag->name;
		$tag_options = (count($tag_options) == 0) ? array('text'	 => 'Keine Tags verfügbar') : $tag_options;
		return $tag_options;
	}

	function get_post_types() {
		
		$post_type_options 	= array();
		$post_types 		= get_post_types(false, 'objects'); 
			
		foreach($post_types as $post_type_id => $post_type){
			
			//Make sure the post type is not an excluded post type.
			if(in_array($post_type_id, array('revision', 'nav_menu_item'))) 
				continue;
			
			$post_type_options[$post_type_id] = $post_type->labels->name;
		
		}
		
		return $post_type_options;
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
}