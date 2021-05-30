<?php 

class UpFrontPostSliderBlock extends UpFrontBlockAPI {

    public $id;    
    public $name;
    public $options_class;
	public $description;
    public $categories;

	function __construct(){

		$this->id = 'post-slider-block';	
		$this->name = 'Post Slider';		
		$this->options_class = 'UpFrontPostSliderBlockOptions';
		$this->description = __('Erstelle einen Beitrags-Slider.', 'upfront');
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
			'name' => 'Slide text',
			'selector' => '.owl-item p'
		));

		$this->register_block_element(array(
			'id' => 'slide-a',
			'name' => 'Slide link',
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
			'name' => 'Dots',
			'selector' => '.owl-dots'
		));

		$this->register_block_element(array(
			'id' => 'dots-item',
			'name' => 'Dots Item',
			'selector' => '.owl-dots .owl-dot'
		));
	}

	public static function enqueue_action($block_id, $block) {

		wp_enqueue_style('upfront-post-slider-transitions-css', upfront_url() . '/library/blocks/post-slider/css/owl.transitions.css');
		wp_enqueue_style('upfront-post-slider-slider-css', upfront_url() . '/library/blocks/post-slider/css/owl.carousel.css');
		wp_enqueue_style('upfront-post-slider-awesome-css', upfront_url() . '/library/blocks/post-slider/css/font-awesome.css');
		wp_enqueue_style('upfront-post-slider-theme-css', upfront_url() . '/library/blocks/post-slider/css/owl.theme.css');
		wp_enqueue_script('upfront-post-slider-slider-js', upfront_url() . '/library/blocks/post-slider/js/owl.carousel.js', array('jquery'), '1.0', false);
	}

	function content($block) {
		
		$post_type 			= ($block['settings']['post-type']) ? $block['settings']['post-type']: 'post';
		$slider_style 		= ($block['settings']['slider-style']) ? $block['settings']['slider-style']: 'style1';
		$order_by 			= ($block['settings']['order-by']) ? $block['settings']['order-by']: 'date';
		$order 				= ($block['settings']['order']) ? $block['settings']['order']: 'desc';
		$categories 		= ($block['settings']['categories']) ? $block['settings']['categories']: array();
		$auto_play 			= ($block['settings']['auto_play']) ? $block['settings']['auto_play']: 'true';
		$show_items 		= ($block['settings']['show_items']) ? $block['settings']['show_items']: 3;
		$show_pagination	= ($block['settings']['show_pagination']) ? $block['settings']['show_pagination']: 'true';


		global $post;

		$psrndn = rand(1,1000);
		$args 	= array ( 
					'post_type' 		=> $post_type,
					'posts_per_page' 	=> $number,
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

		$tppostslider_query = new WP_Query( $args );

		$result='';

		if($slider_style=="style1"){

			$result = '
			<script type="text/javascript">
				jQuery(document).ready(function($) {
					$("#tppost-main-slider-'.$psrndn.'").owlCarousel({
					autoPlay: '.$auto_play.',
					stopOnHover: true,
					items : '.$show_items.',
					itemsDesktop : [1199,3],
					itemsDesktopSmall : [979,3],
					navigation : false,
					navigationText : ["‹","›"],
					paginationNumbers: false,
					pagination: '.$show_pagination.',
					});
				});
			</script>';
			$result.='
			<style type="text/css">
				.pps_single_slider_items-'.$psrndn.' {
					border-bottom: medium none;
					box-shadow: none;
					margin: 0 10px;
					transition: all 0.4s ease-in-out 0s;
				}
				.pps_single_slider_items-'.$psrndn.' .pps_single_slider_items_post_images-'.$psrndn.'{
					position: relative;
					overflow: hidden;
				}
				.pps_single_slider_items-'.$psrndn.' .pps_single_slider_items_post_images-'.$psrndn.':before{
					content: "";
					width: 100%;
					height: 100%;
					position: absolute;
					top: 0;
					left: 0;
					background: rgba(0, 0, 0, 0);
					transition: all 0.4s linear 0s;
				}
				.pps_single_slider_items-'.$psrndn.':hover .pps_single_slider_items_post_images-'.$psrndn.':before{
					background: rgba(0, 0, 0, 0.6);
				}
				.pps_single_slider_items-'.$psrndn.' .pps_single_slider_items_post_images-'.$psrndn.' img{
					width: 100%;
					height: auto;
				}
				.pps_single_slider_items-'.$psrndn.' img {
				  border-radius: 0;
				  box-shadow: none;
				}
				.pps_single_slider_items-'.$psrndn.' .pps_single_slider_items_category-'.$psrndn.' {
					width: 100%;
					font-size: 16px;
					color: #fff;
					line-height: 11px;
					text-align: center;
					text-transform: capitalize;
					padding: 11px 0;
					background: #ff9412;
					position: absolute;
					bottom: 0;
					left: -100%;
					transition: all 0.5s ease-in-out 0s;
				}
				.pps_single_slider_items-'.$psrndn.':hover .pps_single_slider_items_category-'.$psrndn.'{
					left: 0;
				}
				.pps_single_slider_items-'.$psrndn.' .pps_single_slider_item_reviews-'.$psrndn.'{
					padding: 20px 20px;
					background: #fff;
					position: relative;
				}
				.pps_single_slider_items-'.$psrndn.' .pps_single_slider_item_post_title-'.$psrndn.'{
					margin: 0;
				}
				.pps_single_slider_item_reviews-'.$psrndn.' h3.pps_single_slider_item_post_title-'.$psrndn.' {
				  font-size: 15px;
				}
				.pps_single_slider_items-'.$psrndn.' .pps_single_slider_item_post_title-'.$psrndn.' a{
					border-bottom: medium none;
					color: #ff9412;
					display: inline-block;
					font-size: 15px;
					font-weight: normal;
					letter-spacing: 2px;
					margin-bottom: 25px;
					text-decoration: none;
					transition: all 0.3s linear 0s;
					box-shadow: none;
				}
				.pps_single_slider_items-'.$psrndn.' .pps_single_slider_item_post_title-'.$psrndn.' a:hover{
					text-decoration: none;
					color: #555;
				}
				.pps_single_slider_items-'.$psrndn.' .pps_single_slider_item_description-'.$psrndn.'{
					font-size: 15px;
					color: #555;
					line-height: 26px;
				}
				.pps_single_slider_items-'.$psrndn.' .pps_single_slider_items_category-'.$psrndn.' > a {
				  border: medium none;
				  box-shadow: none;
				  color: #000;
				  margin-right: 8px;
				  text-decoration: none;
				}
				.pps_single_slider_items-'.$psrndn.' .pps_single_slider_items_category-'.$psrndn.' > a:hover {
				  color: #fff;
				}
				.pps_single_slider_item_reviews-'.$psrndn.' .pps_single_slider_admin_description-'.$psrndn.'{
					margin-top: 20px;
				}
				.pps_single_slider_admin_description-'.$psrndn.' span{
					display: inline-block;
					font-size: 14px;
				}
				.pps_single_slider_admin_description-'.$psrndn.' span i{
					margin-right: 5px;
					color: #999;
				}
				.pps_single_slider_admin_description-'.$psrndn.' span a{
					color: #999;
					text-transform: uppercase;
				}
				.pps_single_slider_admin_description-'.$psrndn.' span a:hover{
					text-decoration: none;
					color: #ff9412;
				}
				.pps_single_slider_admin_description-'.$psrndn.' span.comments{
					float: right;
				}
				@media only screen and (max-width: 359px) {
					.pps_single_slider_items-'.$psrndn.' .pps_single_slider_items_category-'.$psrndn.'{ font-size: 13px; }
				}
			</style>';
			$result.='<div class="upfront-post-slider-area'.$psrndn.'">';
			$result.='<div id="tppost-main-slider-'.$psrndn.'" class="owl-carousel tppost-main-slider">';
			// Creating a new side loop
			while ( $tppostslider_query->have_posts() ) : $tppostslider_query->the_post();
				
				$catid = get_the_ID();
				$cats = get_the_category($catid);
				
				setup_postdata( $post );
				$excerpt = get_the_excerpt();

				$result.='
				<div class="pps_single_slider_items-'.$psrndn.' pps_single_slider_items">
					<div class="pps_single_slider_items_post_images-'.$psrndn.'">';
						if ( has_post_thumbnail() ) {
							$result .= '<div class="tps-slider-thumb">';
							$result .= '<a href="'.esc_url(get_the_permalink()).'">'.get_the_post_thumbnail( $post->ID, 'post-slider-thumb', array( 'class' => "img-responsive" ) ).'</a>';
							$result .= '</div>';
						}
						$result.='<div class="pps_single_slider_items_category-'.$psrndn.'">';
						foreach ( $cats as $cat ){
							$result.='<a href="'.get_category_link($cat->cat_ID).'">'.$cat->name.'</a>';
							
						}
						
						$result.='</div>';
					$result.='</div>
					<div class="pps_single_slider_item_reviews pps_single_slider_item_reviews-'.$psrndn.'">
						<h3 class="pps_single_slider_item_post_title-'.$psrndn.'"><a href="'.esc_url(get_the_permalink()).'">'.esc_attr(get_the_title()).'</a></h3>
						<div class="pps_single_slider_item_description pps_single_slider_item_description-'.$psrndn.'">'.do_shortcode(get_the_content()).'
						</div>
						<div class="pps_single_slider_admin_description pps_single_slider_admin_description-'.$psrndn.'">
							<span><i class="fa fa-user"></i> <a href="'.get_author_posts_url( get_the_author_meta( 'ID' ), get_the_author_meta( 'user_nicename' ) ).'">'.get_the_author().'</a></span>
						</div>
					</div>
				</div>

				';

					
					
			endwhile;
			wp_reset_postdata();
						
			$result .='</div></div><div class="clearfix"></div>';
	
			echo $result; 
		}elseif($slider_style=="style2"){
			$result = '
			<script type="text/javascript">
				jQuery(document).ready(function($) {
					$("#tppost-main-slider-'.$psrndn.'").owlCarousel({
					autoPlay: '.$auto_play.',
					stopOnHover: true,
					items : '.$show_items.',
					itemsDesktop : [1199,3],
					itemsDesktopSmall : [979,3],
					navigation : false,
					navigationText : ["‹","›"],
					paginationNumbers: false,
					pagination: '.$show_pagination.',
					});
				});
			</script>';
			$result.='
			<style type="text/css">
				.post_slider_'.$psrndn.'_style_two{
					padding: 0 15px;
				}
				.post_slider_'.$psrndn.'_style_two .post_slider_'.$psrndn.'_style_img{
					position: relative;
				}
				.post_slider_'.$psrndn.'_style_two .post_slider_'.$psrndn.'_style_img > a{
					display:block;
				}
				.post_slider_'.$psrndn.'_style_two .post_slider_'.$psrndn.'_style_img img{
					border-radius: 0;
					box-shadow: none;
					height: auto;
					width: 100%;
				}
				.post_slider_'.$psrndn.'_style_two .post_slider_'.$psrndn.'_style_img:hover:before{
					content: "";
					position: absolute;
					width: 100%;
					height:100%;
					background-color: rgba(220, 0, 90, 0.6);
				}
				.post_slider_'.$psrndn.'_style_two .post_slider_'.$psrndn.'_style_img:hover:after{
					opacity: 1;
					transform: scale(1);
				}
				.post_slider_'.$psrndn.'_style_two .post_slider_'.$psrndn.'_style_title{
					margin-bottom: 10px;
					margin-top: 10px;
				}
				.post_slider_'.$psrndn.'_style_two .post_slider_'.$psrndn.'_style_title > a{
					color:#222;
					display: block;
					font-size: 17px;
					font-weight: 600;
					text-transform: uppercase;
					text-decoration:none;
					border-bottom:none;
					box-shadow: none;
				}
				.post_slider_'.$psrndn.'_style_two .post_slider_'.$psrndn.'_style_title > a:hover{
					text-decoration: none;
					color:#dc005a;
				}
				.post_slider_'.$psrndn.'_style_two .post_slider_'.$psrndn.'_style_bar{
					padding: 0;
					list-style: none;
				}
				.post_slider_'.$psrndn.'_style_two .post_slider_'.$psrndn.'_style_bar > li{
					display: inline-block;
					margin: 0 15px 0 0;
				}
				.post_slider_'.$psrndn.'_style_two .post_slider_'.$psrndn.'_style_post_date,
				.post_slider_'.$psrndn.'_style_two .post_slider_'.$psrndn.'_style_post_author,
				.post_slider_'.$psrndn.'_style_two .post_slider_'.$psrndn.'_style_post_author > a{
					color:#8f8f8f;
					font-size: 12px;
					margin-right: 16px;
					text-transform: uppercase;
					font-style: italic;
					text-decoration:none;
				}
				.post_slider_'.$psrndn.'_style_two .post_slider_'.$psrndn.'_style_post_date > i,
				.post_slider_'.$psrndn.'_style_two .post_slider_'.$psrndn.'_style_post_author > i{
					margin-right: 5px;
				}
				.post_slider_'.$psrndn.'_style_two .post_slider_'.$psrndn.'_style_post_author > a:hover{
					color:#dc005a;
				}
				.post_slider_'.$psrndn.'_style_two .post_slider_'.$psrndn.'_style_post_description{
					color:#8f8f8f;
					font-size: 14px;
					line-height: 24px;
					padding-top: 5px;
				}
				.post_slider_'.$psrndn.'_style_two .post_slider_'.$psrndn.'_style_post_description:before{
					content: "";
					display: block;
					border-top: 4px solid #dc005a;
					padding-bottom: 12px;
					width: 50px;
				}
			</style>';
			$result.='<div class="upfront-post-slider-area'.$psrndn.'">';
			$result.='<div id="tppost-main-slider-'.$psrndn.'" class="owl-carousel tppost-main-slider">';
			// Creating a new side loop
			while ( $tppostslider_query->have_posts() ) : $tppostslider_query->the_post();
				
				$catid = get_the_ID();
				$cats = get_the_category($catid);
				
				setup_postdata( $post );
				$excerpt = get_the_excerpt();

				$result.='
				<div class="post_slider_'.$psrndn.'_style_two">
						<div class="post_slider_'.$psrndn.'_style_img">';
						if ( has_post_thumbnail() ) {
							$result .= '<div class="tps-slider-thumb-style2">';
							$result .= '<a href="'.esc_url(get_the_permalink()).'">'.get_the_post_thumbnail( $post->ID, 'post-slider-thumb', array( 'class' => "img-responsive" ) ).'</a>';
							$result .= '</div>';
						}
						$result.='</div>
					<h5 class="post_slider_'.$psrndn.'_style_title">
						<a href="'.esc_url(get_the_permalink()).'">'.esc_attr(get_the_title()).'</a>
					</h5>
					<ul class="post_slider_'.$psrndn.'_style_bar">
						<li class="post_slider_'.$psrndn.'_style_post_date">
						<i class="fa fa-calendar"></i> '.get_the_date('Y-m-d').'</li>
						<li class="post_slider_'.$psrndn.'_style_post_author">
						<i class="fa fa-user"></i>
						<a href="'.get_author_posts_url( get_the_author_meta( 'ID' ), get_the_author_meta( 'user_nicename' ) ).'">'.get_the_author().'</a></li>
					</ul>'.do_shortcode(get_the_content()).'
				</div>';
					
			endwhile;
			wp_reset_postdata();
						
			$result .='</div></div><div class="clearfix"></div>';
	
			echo $result; 
		}elseif($slider_style=="style3"){
			$result = '
			<script type="text/javascript">
				jQuery(document).ready(function($) {
					$("#tppost-main-slider-'.$psrndn.'").owlCarousel({
					autoPlay: '.$auto_play.',
					stopOnHover: true,
					items : '.$show_items.',
					itemsDesktop : [1199,3],
					itemsDesktopSmall : [979,3],
					navigation : false,
					navigationText : ["‹","›"],
					paginationNumbers: false,
					pagination: '.$show_pagination.',
					});
				});
			</script>';
			$result.='
			<style type="text/css">
				.post_slider_'.$psrndn.'_style3{
					border: 1px solid #eee;
					padding: 20px;
					margin: 0 15px;
					position: relative;
				}
				.post_slider_'.$psrndn.'_style3:before{
					content: "";
					border-top:1px solid transparent;
					position: absolute;
					top:0;
					left:0;
					width: 100%;
					transition:all 0.3s ease-in-out 0s;
				}
				.post_slider_'.$psrndn.'_style3:hover:before{
					border-top: 1px solid #3398db;
				}
				.post_slider_'.$psrndn.'_style3:hover{
					border-top: 1px solid #3398db;
				}
				.post_slider_'.$psrndn.'_style3 .post_slider_'.$psrndn.'_style3_img > img{
					width: 100%;
					height:auto;
				}
				.post_slider_'.$psrndn.'_style3 .post_slider_'.$psrndn.'_style3_title > a{
					font-size: 20px;
					text-transform: capitalize;
					color:#333;
					transition:all 0.3s ease-in-out 0s;
					text-decoration:none;
					border-bottom:none;
					box-shadow: none;
				}
				.post_slider_'.$psrndn.'_style3 .post_slider_'.$psrndn.'_style3_title > a:hover{
					text-decoration: none;
					color:#3398db;
					text-decoration:none;
				}
				.tps-slider-thumb-style3 a img {
				  border-radius: 0;
				  box-shadow: none;
				}
				.post_slider_'.$psrndn.'_style3 .post_slider_'.$psrndn.'_style3_bars{
					padding: 0;
					list-style: none;
					overflow: hidden;
				}
				.post_slider_'.$psrndn.'_style3 .post_slider_'.$psrndn.'_style3_bars > li{
					border-right: 1px solid #999;
					display: inline-block;
					float: left;
					margin: 0;
					padding: 0 10px;
				}
				.post_slider_'.$psrndn.'_style3 .post_slider_'.$psrndn.'_style3_bars > li:first-child{
					padding: 0 10px 0 0;
				}
				.post_slider_'.$psrndn.'_style3 .post_slider_'.$psrndn.'_style3_bars > li:last-child{
					border: 0px none;
				}
				.post_slider_'.$psrndn.'_style3 .post_slider_'.$psrndn.'_style3_dates,
				.post_slider_'.$psrndn.'_style3 .post_slider_'.$psrndn.'_style3_autors,
				.post_slider_'.$psrndn.'_style3 .comment{
					color:#3398db;
					text-transform: uppercase;
					font-size: 11px;
				}
				.post_slider_'.$psrndn.'_style3 .post_slider_'.$psrndn.'_style3_autors > a,
				.post_slider_'.$psrndn.'_style3 .comment > a,
				.post_slider_'.$psrndn.'_style3 .comment > i{
					color:#999;
					transition:all 0.3s ease-in-out 0s;
				}
				.post_slider_'.$psrndn.'_style3 .post_slider_'.$psrndn.'_style3_autors > a:hover,
				.post_slider_'.$psrndn.'_style3 .comment > a:hover{
					text-decoration: none;
					color:#333;
				}
				.post_slider_'.$psrndn.'_style3 .comment > i{
					margin-right: 8px;
					font-size: 15px;
				}
				.post_slider_'.$psrndn.'_style3 .post_slider_'.$psrndn.'_style3_p_description{
					line-height: 1.7;
					color:#666;
					font-size: 13px;
					margin-bottom: 20px;
				}
				.post_slider_'.$psrndn.'_style3 .post_slider_'.$psrndn.'_style3_p_readmores{
					display: inline-block;
					padding: 10px 35px;
					background: #3398db;
					color: #ffffff;
					border-radius: 5px;
					font-size: 15px;
					font-weight: 900;
					letter-spacing: 1px;
					line-height: 20px;
					margin-bottom: 5px;
					text-transform: uppercase;
					transition:all 0.3s ease-in-out 0s;
					text-decoration:none;
				}
				.post_slider_'.$psrndn.'_style3 .post_slider_'.$psrndn.'_style3_p_readmores:hover{
					text-decoration: none;
					color:#fff;
					background: #333;
				}
				@media only screen and (max-width: 360px) {
					.post_slider_'.$psrndn.'_style3_bars > li:last-child{
						margin-top: 8px;
						padding: 0;
					}
				}
			</style>';
			$result.='<div class="upfront-post-slider-area'.$psrndn.'">';
			$result.='<div id="tppost-main-slider-'.$psrndn.'" class="owl-carousel tppost-main-slider">';
			// Creating a new side loop
			while ( $tppostslider_query->have_posts() ) : $tppostslider_query->the_post();
				
				$catid = get_the_ID();
				$cats = get_the_category($catid);
				
				setup_postdata( $post );
				$excerpt = get_the_excerpt();

			$result.='
			<div class="post_slider_'.$psrndn.'_style3">
				<div class="post_slider_'.$psrndn.'_style3_img">';
					if ( has_post_thumbnail() ) {
						$result .= '<div class="tps-slider-thumb-style3">';
						$result .= '<a href="'.esc_url(get_the_permalink()).'">'.get_the_post_thumbnail( $post->ID, 'post-slider-thumb', array( 'class' => "img-responsive" ) ).'</a>';
						$result .= '</div>';
					}
				$result.='</div>
				<h5 class="post_slider_'.$psrndn.'_style3_title"><a href="'.esc_url(get_the_permalink()).'">'.esc_attr(get_the_title()).'</a></h5>
				<ul class="post_slider_'.$psrndn.'_style3_bars">
					<li class="post_slider_'.$psrndn.'_style3_dates">'.get_the_date('Y-m-d').'</li>
					<li class="post_slider_'.$psrndn.'_style3_autors"><a href="'.get_author_posts_url( get_the_author_meta( 'ID' ), get_the_author_meta( 'user_nicename' ) ).'">'.get_the_author().'</a></li>
				</ul>'.do_shortcode(get_the_content()).'
				<a href="'.esc_url(get_the_permalink()).'" class="post_slider_'.$psrndn.'_style3_p_readmores">Mehr</a>
			</div>';

			endwhile;
			wp_reset_postdata();

			$result .='</div></div><div class="clearfix"></div>';
			echo $result; 
		}elseif($slider_style=="style4"){
			$result = '
			<script type="text/javascript">
				jQuery(document).ready(function($) {
					$("#tppost-main-slider-'.$psrndn.'").owlCarousel({
					autoPlay: '.$auto_play.',
					stopOnHover: true,
					items : '.$show_items.',
					itemsDesktop : [1199,3],
					itemsDesktopSmall : [979,3],
					navigation : false,
					nav : true,
					navigationText : ["‹","›"],
					navText : ["<",">"],
					paginationNumbers: false,
					pagination: '.$show_pagination.',
					});
				});
			</script>';
			$result.='
			<style type="text/css">
				.post_slider_'.$psrndn.'_style_four{
					padding: 0 15px;
				}
				.post_slider_'.$psrndn.'_style_four .post_slider_'.$psrndn.'_style_img{
					position: relative;
				}
				.post_slider_'.$psrndn.'_style_four .post_slider_'.$psrndn.'_style_img > a{
					display:block;
				}
				.post_slider_'.$psrndn.'_style_four .post_slider_'.$psrndn.'_style_img img{
					border-radius: 0;
					box-shadow: none;
					height: auto;
					width: 100%;
				}
				.post_slider_'.$psrndn.'_style_four .post_slider_'.$psrndn.'_style_img:hover:before{
					content: "";
					position: absolute;
					width: 100%;
					height:100%;
					background-color: rgba(220, 0, 90, 0.6);
				}
				.post_slider_'.$psrndn.'_style_four .post_slider_'.$psrndn.'_style_img:hover:after{
					opacity: 1;
					transform: scale(1);
				}
				.post_slider_'.$psrndn.'_style_four .post_slider_'.$psrndn.'_style_title{
					margin-bottom: 10px;
					margin-top: 10px;
				}
				.post_slider_'.$psrndn.'_style_four .post_slider_'.$psrndn.'_style_title > a{
					color:#222;
					display: block;
					font-size: 17px;
					font-weight: 600;
					text-transform: uppercase;
					text-decoration:none;
					border-bottom:none;
					box-shadow: none;
				}
				.post_slider_'.$psrndn.'_style_four .post_slider_'.$psrndn.'_style_title > a:hover{
					text-decoration: none;
					color:#dc005a;
				}
				.post_slider_'.$psrndn.'_style_four .post_slider_'.$psrndn.'_style_bar{
					padding: 0;
					list-style: none;
				}
				.post_slider_'.$psrndn.'_style_four .post_slider_'.$psrndn.'_style_bar > li{
					display: inline-block;
					margin: 0 15px 0 0;
				}
				.post_slider_'.$psrndn.'_style_four .post_slider_'.$psrndn.'_style_post_date,
				.post_slider_'.$psrndn.'_style_four .post_slider_'.$psrndn.'_style_post_author,
				.post_slider_'.$psrndn.'_style_four .post_slider_'.$psrndn.'_style_post_author > a{
					color:#8f8f8f;
					font-size: 12px;
					margin-right: 16px;
					text-transform: uppercase;
					font-style: italic;
					text-decoration:none;
				}
				.post_slider_'.$psrndn.'_style_four .post_slider_'.$psrndn.'_style_post_date > i,
				.post_slider_'.$psrndn.'_style_four .post_slider_'.$psrndn.'_style_post_author > i{
					margin-right: 5px;
				}
				.post_slider_'.$psrndn.'_style_four .post_slider_'.$psrndn.'_style_post_author > a:hover{
					color:#dc005a;
				}
				.post_slider_'.$psrndn.'_style_four .post_slider_'.$psrndn.'_style_post_description{
					color:#8f8f8f;
					font-size: 14px;
					line-height: 24px;
					padding-top: 5px;
				}
				.post_slider_'.$psrndn.'_style_four .post_slider_'.$psrndn.'_style_post_description:before{
					content: "";
					display: block;
					border-top: 4px solid #dc005a;
					padding-bottom: 12px;
					width: 50px;
				}
			</style>';
			$result.='<div class="upfront-post-slider-area'.$psrndn.'">';
			$result.='<div id="tppost-main-slider-'.$psrndn.'" class="owl-carousel tppost-main-slider">';
			// Creating a new side loop
			while ( $tppostslider_query->have_posts() ) : $tppostslider_query->the_post();

				$catid = get_the_ID();
				$cats = get_the_category($catid);
				setup_postdata( $post );
				$excerpt = get_the_excerpt();

				$clases = 'presentacion';
				foreach ( $cats as $cat ){
							$clases .= ' ' . $cat->name;
						}
				$result .= '<div class="'.$clases.'">'.do_shortcode(get_the_content()).'</div>';

			endwhile;
			wp_reset_postdata();

			$result .='</div></div><div class="clearfix"></div>';
			echo $result;
		}elseif($slider_style=="style5"){
			
			$result = '
			<script type="text/javascript">
				jQuery(document).ready(function($) {
					$("#tppost-main-slider-'.$psrndn.'").owlCarousel({
					autoPlay: '.$auto_play.',
					stopOnHover: true,
					items : '.$show_items.',
					itemsDesktop : [1199,3],
					itemsDesktopSmall : [979,3],
					navigation : true,
					navigationText : ["<",">"],
					paginationNumbers: false,
					pagination: '.$show_pagination.',
					});
				});
			</script>';
			$result.='
			<style type="text/css">
				.post_slider_'.$psrndn.'_style5{
					border: 1px solid #eee;
					padding: 0px;
					margin: 0 15px;
					position: relative;
				}
				.post_slider_'.$psrndn.'_style5:before{
					content: "";
					border-top:1px solid transparent;
					position: absolute;
					top:0;
					left:0;
					width: 100%;
					transition:all 0.3s ease-in-out 0s;
				}
				.post_slider_'.$psrndn.'_style5:hover:before{
					border-top: 1px solid #3398db;
				}
				.post_slider_'.$psrndn.'_style5:hover{
					border-top: 1px solid #3398db;
				}
				.post_slider_'.$psrndn.'_style5 .post_slider_'.$psrndn.'_style5_img > img{
					width: 100%;
					height:auto;
				}
				.post_slider_'.$psrndn.'_style5 .post_slider_'.$psrndn.'_style5_title > a{
					font-size: 20px;
					text-transform: capitalize;
					color:#333;
					transition:all 0.3s ease-in-out 0s;
					text-decoration:none;
					border-bottom:none;
					box-shadow: none;
				}
				.post_slider_'.$psrndn.'_style5 .post_slider_'.$psrndn.'_style5_title > a:hover{
					text-decoration: none;
					color:#3398db;
					text-decoration:none;
				}
				.tps-slider-thumb-style3 a img {
				  border-radius: 0;
				  box-shadow: none;
				}
				.post_slider_'.$psrndn.'_style5 .post_slider_'.$psrndn.'_style5_bars{
					padding: 0;
					list-style: none;
					overflow: hidden;
				}
				.post_slider_'.$psrndn.'_style5 .post_slider_'.$psrndn.'_style5_bars > li{
					border-right: 1px solid #999;
					display: inline-block;
					float: left;
					margin: 0;
					padding: 0 10px;
				}
				.post_slider_'.$psrndn.'_style5 .post_slider_'.$psrndn.'_style5_bars > li:first-child{
					padding: 0 10px 0 0;
				}
				.post_slider_'.$psrndn.'_style5 .post_slider_'.$psrndn.'_style5_bars > li:last-child{
					border: 0px none;
				}
				.post_slider_'.$psrndn.'_style5 .post_slider_'.$psrndn.'_style5_dates,
				.post_slider_'.$psrndn.'_style5 .post_slider_'.$psrndn.'_style5_autors,
				.post_slider_'.$psrndn.'_style5 .comment{
					color:#3398db;
					text-transform: uppercase;
					font-size: 11px;
				}
				.post_slider_'.$psrndn.'_style5 .post_slider_'.$psrndn.'_style5_autors > a,
				.post_slider_'.$psrndn.'_style5 .comment > a,
				.post_slider_'.$psrndn.'_style5 .comment > i{
					color:#999;
					transition:all 0.3s ease-in-out 0s;
				}
				.post_slider_'.$psrndn.'_style5 .post_slider_'.$psrndn.'_style5_autors > a:hover,
				.post_slider_'.$psrndn.'_style5 .comment > a:hover{
					text-decoration: none;
					color:#333;
				}
				.post_slider_'.$psrndn.'_style5 .comment > i{
					margin-right: 8px;
					font-size: 15px;
				}
				.post_slider_'.$psrndn.'_style5 .post_slider_'.$psrndn.'_style5_p_description{
					line-height: 1.7;
					color:#666;
					font-size: 13px;
					margin-bottom: 20px;
				}
				.post_slider_'.$psrndn.'_style5 .post_slider_'.$psrndn.'_style5_p_readmores{
					display: inline-block;
					padding: 10px 35px;
					background: #3398db;
					color: #ffffff;
					border-radius: 5px;
					font-size: 15px;
					font-weight: 900;
					letter-spacing: 1px;
					line-height: 20px;
					margin-bottom: 5px;
					text-transform: uppercase;
					transition:all 0.3s ease-in-out 0s;
					text-decoration:none;
				}
				.post_slider_'.$psrndn.'_style5 .post_slider_'.$psrndn.'_style5_p_readmores:hover{
					text-decoration: none;
					color:#fff;
					background: #333;
				}
				.tps-slider-thumb-style5{
				}
				.tps-slider-thumb-style5 a{
					display: block;
					width: 100%;
					height: 235px;
					background-repeat: no-repeat;
					overflow: hidden;
					border-bottom: 4px solid #48c7e7;
					margin-bottom: 35px;

				}
				.post_slider_'.$psrndn.'_style5_title{
					text-align: center;
    				margin-bottom: 20px;
				}
				.tps-slider-post-content_style5{
					margin: auto;
					padding-left: 15%;
					padding-right: 15%;
					margin-bottom: 15px;
					text-align: center;
					border-bottom: 1px solid #eee;
					padding-bottom: 15px;
					min-height: 90px;
				}
				.tps-slider-post-link_style5{
					text-align: center;
				}
				div.owl-item > div.post_slider_'.$psrndn.'_style5 div.tps-slider-post-link_style5 > a.post_slider_'.$psrndn.'_style5_p_readmores{
					text-align: center;
					background: transparent;
					font-size: 16px !important;
					color: #48c7e7;
				}
				.owl-theme .owl-controls .owl-buttons div{
					color: #000;
					display: inline-block;
					zoom: 1;
					margin: 5px;
					padding: 3px 10px;
					font-size: 25px;
					-webkit-border-radius: 30px;
					-moz-border-radius: 30px;
					border-radius: 0;
					background: #fafafa;
					filter: Alpha(Opacity=50);
					opacity: 0.5;
					border: 1px solid #eee;
					margin-top: 50px;
				}
				@media only screen and (max-width: 360px) {
					.post_slider_'.$psrndn.'_style5_bars > li:last-child{
						margin-top: 8px;
						padding: 0;
					}
				}
			</style>';
			$result.='<div class="upfront-post-slider-area upfront-post-slider-area'.$psrndn.'">';
			$result.='<div id="tppost-main-slider-'.$psrndn.'" class="owl-carousel tppost-main-slider">';
			// Creating a new side loop

			while ( $tppostslider_query->have_posts() ) : $tppostslider_query->the_post();
				
				$catid = get_the_ID();
				$cats = get_the_category($catid);
				
				setup_postdata( $post );
				$excerpt = get_the_excerpt();

			$result.='
			<div class="post_slider_'.$psrndn.'_style5">
				<div class="post_slider_'.$psrndn.'_style5_img">';
					if ( has_post_thumbnail() ) {
						$postIMG = get_the_post_thumbnail_url(); 
						$result .= '<div class="tps-slider-thumb-style5">';						
						$result .= '<a href="'.esc_url(get_the_permalink()).'" style="background-image:url('.$postIMG.')"></a>';
						$result .= '</div>';
					}
				$result.='</div>
				<h5 class="post_slider_'.$psrndn.'_style5_title"><a href="'.esc_url(get_the_permalink()).'">'.esc_attr(get_the_title()).'</a></h5>';
				$result .= '<div class="tps-slider-post-content_style5">'.$this->custom_excerpt_post($excerpt,15).'</div>';
				$result .= '<div class="tps-slider-post-link_style5">'.
							'<a href="'.esc_url(get_the_permalink()).'" class="post_slider_'.$psrndn.'_style5_p_readmores">Weiterlesen ></a>'
							.'</div>';

				 
			$result .= '</div>';

			endwhile;
			wp_reset_postdata();

			$result .='</div></div><div class="clearfix"></div>';
			echo $result; 
		}elseif($slider_style=="style6"){

			$result = '
			<script type="text/javascript">
				jQuery(document).ready(function($) {
					$("#tppost-main-slider-'.$psrndn.'").owlCarousel({
					autoPlay: '.$auto_play.',
					stopOnHover: true,
					items : '.$show_items.',
					itemsDesktop : [1199,3],
					itemsDesktopSmall : [979,3],
					navigation : true,
					navigationText : ["<",">"],
					paginationNumbers: false,
					pagination: '.$show_pagination.',
					});
				});
			</script>';
			$result.='
			<style type="text/css">
				.post_slider_'.$psrndn.'_style6{
					border: 0;
					padding: 0px;
					margin: auto;
					position: relative;
					max-width: 190px;
				}
				.post_slider_'.$psrndn.'_style6:before{
					content: "";
					border-top:1px solid transparent;
					position: absolute;
					top:0;
					left:0;
					width: 100%;
					transition:all 0.3s ease-in-out 0s;
				}
				.post_slider_'.$psrndn.'_style6:hover:before{
					border-top: 1px solid #3398db;
				}
				.post_slider_'.$psrndn.'_style6:hover{
					border-top: 1px solid #3398db;
				}
				.post_slider_'.$psrndn.'_style6 .post_slider_'.$psrndn.'_style6_img > img{
					width: 100%;
					height:auto;
				}
				.post_slider_'.$psrndn.'_style6 .post_slider_'.$psrndn.'_style6_title > a{
					font-size: 15px;
					text-transform: capitalize;
					color:#333;
					transition:all 0.3s ease-in-out 0s;
					text-decoration:none;
					border-bottom:none;
					box-shadow: none;
				}
				.post_slider_'.$psrndn.'_style6 .post_slider_'.$psrndn.'_style6_title > a:hover{
					text-decoration: none;
					color:#3398db;
					text-decoration:none;
				}
				.tps-slider-thumb-style3 a img {
				  border-radius: 0;
				  box-shadow: none;
				}
				.post_slider_'.$psrndn.'_style6 .post_slider_'.$psrndn.'_style6_bars{
					padding: 0;
					list-style: none;
					overflow: hidden;
				}
				.post_slider_'.$psrndn.'_style6 .post_slider_'.$psrndn.'_style6_bars > li{
					border-right: 1px solid #999;
					display: inline-block;
					float: left;
					margin: 0;
					padding: 0 10px;
				}
				.post_slider_'.$psrndn.'_style6 .post_slider_'.$psrndn.'_style6_bars > li:first-child{
					padding: 0 10px 0 0;
				}
				.post_slider_'.$psrndn.'_style6 .post_slider_'.$psrndn.'_style6_bars > li:last-child{
					border: 0px none;
				}
				.post_slider_'.$psrndn.'_style6 .post_slider_'.$psrndn.'_style6_dates,
				.post_slider_'.$psrndn.'_style6 .post_slider_'.$psrndn.'_style6_autors,
				.post_slider_'.$psrndn.'_style6 .comment{
					color:#3398db;
					text-transform: uppercase;
					font-size: 11px;
				}
				.post_slider_'.$psrndn.'_style6 .post_slider_'.$psrndn.'_style6_autors > a,
				.post_slider_'.$psrndn.'_style6 .comment > a,
				.post_slider_'.$psrndn.'_style6 .comment > i{
					color:#999;
					transition:all 0.3s ease-in-out 0s;
				}
				.post_slider_'.$psrndn.'_style6 .post_slider_'.$psrndn.'_style6_autors > a:hover,
				.post_slider_'.$psrndn.'_style6 .comment > a:hover{
					text-decoration: none;
					color:#333;
				}
				.post_slider_'.$psrndn.'_style6 .comment > i{
					margin-right: 8px;
					font-size: 15px;
				}
				.post_slider_'.$psrndn.'_style6 .post_slider_'.$psrndn.'_style6_p_description{
					line-height: 1.7;
					color:#666;
					font-size: 13px;
					margin-bottom: 20px;
				}
				.post_slider_'.$psrndn.'_style6 .post_slider_'.$psrndn.'_style6_p_readmores{
					display: inline-block;
					padding: 10px 35px;
					background: #3398db;
					color: #ffffff;
					border-radius: 5px;
					font-size: 15px;
					font-weight: 900;
					letter-spacing: 1px;
					line-height: 20px;
					margin-bottom: 5px;
					text-transform: uppercase;
					transition:all 0.3s ease-in-out 0s;
					text-decoration:none;
				}
				.post_slider_'.$psrndn.'_style6 .post_slider_'.$psrndn.'_style6_p_readmores:hover{
					text-decoration: none;
					color:#fff;
					background: #333;
				}
				.tps-slider-thumb-style6{
					min-height: 250px;
				}
				.tps-slider-thumb-style6 a{
					display: block;
					width: 100%;
					height: 250px;
					background-repeat: no-repeat;
					overflow: hidden;
					margin-bottom: 35px;

				}
				.post_slider_'.$psrndn.'_style6_title{
					text-align: center;
    				margin-bottom: 20px;
				}
				.tps-slider-post-content_style6{
					margin: auto;
					padding-left: 15%;
					padding-right: 15%;
					margin-bottom: 15px;
					text-align: center;
					border-bottom: 1px solid #eee;
					padding-bottom: 15px;
					min-height: 90px;
				}
				.tps-slider-post-link_style6{
					text-align: center;
				}
				div.owl-item > div.post_slider_'.$psrndn.'_style6 div.tps-slider-post-link_style6 > a.post_slider_'.$psrndn.'_style6_p_readmores{
					text-align: center;
					background: transparent;
					font-size: 16px !important;
					color: #48c7e7;
				}
				.upfront-post-slider-area'.$psrndn.'{
					padding-right: 100px;
					padding-left: 100px;
				}
				.owl-theme .owl-controls .owl-buttons div{
					color: #000;
					display: inline-block;
					zoom: 1;
					margin: 5px;
					padding: 3px 10px;
					font-size: 25px;
					-webkit-border-radius: 30px;
					-moz-border-radius: 30px;
					border-radius: 0;
					background: #fafafa;
					filter: Alpha(Opacity=50);
					opacity: 0.5;
					border: 1px solid #eee;
					margin-top: 50px;
				}
				@media only screen and (max-width: 360px) {
					.post_slider_'.$psrndn.'_style6_bars > li:last-child{
						margin-top: 8px;
						padding: 0;
					}
				}
			</style>';
			$result.='<div class="upfront-post-slider-area upfront-post-slider-area'.$psrndn.'">';
			$result.='<div id="tppost-main-slider-'.$psrndn.'" class="owl-carousel tppost-main-slider">';
			// Creating a new side loop

			while ( $tppostslider_query->have_posts() ) : $tppostslider_query->the_post();
				
				$catid = get_the_ID();
				$cats = get_the_category($catid);
				
				setup_postdata( $post );
				$excerpt = get_the_excerpt();

			$result.='
			<div class="post_slider_'.$psrndn.'_style6">
				<div class="post_slider_'.$psrndn.'_style6_img">';
					if ( has_post_thumbnail() ) {
						$postIMG =get_the_post_thumbnail_url(); 
						$result .= '<div class="tps-slider-thumb-style6">';						
						$result .= '<a href="'.esc_url(get_the_permalink()).'" style="background-image:url('.$postIMG.');background-size: cover;"></a>';
						$result .= '</div>';
					}
				$result.='</div>
				<h5 class="post_slider_'.$psrndn.'_style6_title"><a href="'.esc_url(get_the_permalink()).'">'.esc_attr(get_the_title()).'</a></h5>';

				 
			$result .= '</div>';

			endwhile;
			wp_reset_postdata();

			$result .='</div></div><div class="clearfix"></div>';
			echo $result; 
		}else{
			echo 'Nothing Found !!';
		}

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

class UpFrontPostSliderBlockOptions extends UpFrontBlockOptionsAPI {

	public $tabs = array(
		'content-tab' 	=> 'Content'
	);


	public $inputs = array(

		'content-tab' => array(
			'post-type' => array(
				'type' => 'select',
				'name' => 'post-type',
				'label' => 'Welcher Content',
				'options' => array(
					'none' 		=> 'Choose your product',
					'woo' 		=> 'WooCommerce',
					'cf7' 		=> 'Contact Form 7',
					'gravity' 	=> 'Gravity Forms',
					'price' 	=> 'Price Tables'
				),
				'default' => 'post',
				'tooltip' => '',		
				'options' => 'get_post_types()',
				'callback' => ''
			),

			'slider-style' => array(
				'type' => 'select',
				'name' => 'slider-style',
				'label' => 'Stil',
				'default' => 'style1',
				'options' => array(
					'style1' => 'Stil 1',
					'style2' => 'Stil 2',
					'style3' => 'Stil 3',
					'style4' => 'Stil 4',
					'style5' => 'Stil 5',
					'style6' => 'Stil 6',
				)
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
					'type' => 'Post-Typ',
					'menu_order' => 'Benutzerdefiniert'
				)
			),
			
			'order' => array(
				'type' => 'select',
				'name' => 'order',
				'label' => 'Sortierung',
				'tooltip' => '',
				'options' => array(
					'desc' => 'Absteigend',
					'asc' => 'Aufsteigend',
				)
			),

			'auto_play' => array(
				'type' => 'select',
				'name' => 'auto_play',
				'label' => 'Autoplay',
				'tooltip' => '',
				'options' => array(
					'true' => 'Ja',
					'false' => 'Nein',
				)
			),

			'show_items' => array(
				'type' => 'integer',
				'default' => 3,
				'name' => 'show_items',
				'label' => 'Elemente anzeigen',
				'tooltip' => '',				
			),

			'show_pagination' => array(
				'type' => 'select',		
				'name' => 'show_pagination',
				'label' => 'Paginierung anzeigen',
				'tooltip' => '',
				'options' => array(
					'true' => 'Ja',
					'false' => 'Nein',
				)				
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