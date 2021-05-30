<?php 

class UpFrontSliderRevolution extends UpFrontBlockAPI {

    public $id;
    public $name;
    public $options_class;
    public $categories;


    public function __construct(){

    	$this->id 				= 'slider-revolution';    
	    $this->name 			= 'Slider Revolution';
	    $this->options_class 	= 'UpFrontSliderRevolutionOptions';
	    $this->categories 		= array('content','slider', 'media');

    }
    
			
	function setup_elements() {

	}

	function content($block) {
				
		$alias = parent::get_setting($block, 'alias', '');		
		echo do_shortcode('[rev_slider alias="'.$alias.'"][/rev_slider]');
		
	}

	public static function dynamic_css($block_id, $block = false) {
		$css = '#block-' . $block['id'] . ' rs-fullwidth-wrap { position: inherit; }';
		$css .= '#block-' . $block['id'] . ' rs-module-wrap { position: inherit; }';
		return $css;
	}
	
}
class UpFrontSliderRevolutionOptions extends UpFrontBlockOptionsAPI {

	public $tabs;
	public $sets;
	public $inputs;

	function __construct($block_type_object){

		parent::__construct($block_type_object);

		$this->tabs = array(
			'general' => 'General'
		);

		$this->sets = array(

		);

		$this->inputs = array(
			'general' => array(
				'alias' => array(
					'type' => 'select',
					'name' => 'alias',
					'label' => 'Select Slider',
					'options' => 'get_slides()',
					'tooltip' => '',
				),
			)
		);

	}

	public function modify_arguments($args = false) {
	}
	
	public function get_slides() {


		$slider = new RevSlider();		
		$sliders = array('no-slide' => 'Select an slider');		

		if( method_exists('RevSlider', 'getAllSliderForAdminMenu') ){
			
			$data = $slider->getAllSliderForAdminMenu();
			foreach ($data as $key => $params) {
				$sliders[ $params['alias'] ] = $params['title'];
			}

		}elseif( method_exists('RevSlider', 'get_slider_for_admin_menu') ){
			
			$data = $slider->get_slider_for_admin_menu();
			foreach ($data as $key => $params) {
				$sliders[ $params['alias'] ] = $params['title'];
			}
		
		}else{			
			return;
		}


		return $sliders;
	}
}