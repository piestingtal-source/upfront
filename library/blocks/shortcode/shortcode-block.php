<?php 

class UpFrontShortcodesBlock extends UpFrontBlockAPI {

    public $id;    
    public $name;
    public $options_class;
	public $description;
    public $categories;
	public function __construct() {
		$this->id            = 'shortcode-block';
		$this->name          = __( 'Shortcode Generator', 'upfront' );
		$this->options_class = 'UpFrontShortcodesBlockOptions';
		$this->description   = __( 'Allows to display various post fields, including post title, post content, modified date etc.', 'upfront' );
		$this->categories    = array( 'content','shop','formular' );
	}
    
			
	function setup_elements() {
		
		$this->register_block_element(array(
    		'id' => '.woocommerce-button',
			'name' => 'WooCommerce Button',
			'selector' => 'a.button.add_to_cart_button.product_type_simple',
			'states' => array(
				'Hover' => 'a.button.add_to_cart_button.product_type_simple:hover',
				'Clicked' => 'a.button.add_to_cart_button.product_type_simple:active'
			)
		));

		$this->register_block_element(array(
    		'id' => '.woocommerce-onsale',
			'name' => 'WooCommerce OnSale',
			'selector' => '.woocommerce span.onsale',
			'states' => array(
				'Hover' => '.woocommerce span.onsale:hover',
				'Clicked' => '.woocommerce span.onsale:active'
			)
		));

		$this->register_block_element(array(
    		'id' => '.woocommerce-image',
			'name' => 'WooCommerce Image',
			'selector' => 'img.attachment-shop_catalog.wp-post-image',
			'states' => array(
				'Hover' => 'img.attachment-shop_catalog.wp-post-image:hover',
				'Clicked' => 'img.attachment-shop_catalog.wp-post-image:active'
			)
		));

		$this->register_block_element(array(
    		'id' => '.woocommerce-product-title',
			'name' => 'WooCommerce Product Title',
			'selector' => '.woocommerce ul.products li.product h3',
			'states' => array(
				'Hover' => '.woocommerce ul.products li.product h3:hover',
				'Clicked' => '.woocommerce ul.products li.product h3:active'
			)
		));
		
		$this->register_block_element(array(
    		'id' => '.woocommerce-stars',
			'name' => 'WooCommerce Star Rating',
			'selector' => '.woocommerce .star-rating',
			'states' => array(
				'Hover' => '.woocommerce .star-rating:hover',
				'Clicked' => '.woocommerce .star-rating:active'
			)
		));
		
		$this->register_block_element(array(
    		'id' => '.woocommerce-ammount',
			'name' => 'WooCommerce Amount',
			'selector' => '.woocommerce span.amount',
			'states' => array(
				'Hover' => '.woocommerce span.amount:hover',
				'Clicked' => '.woocommerce span.amount:active'
			)
		));		
		
		$this->register_block_element(array(
    		'id' => '.woocommerce-price',
			'name' => 'WooCommerce Price',
			'selector' => '.woocommerce span.price',
			'states' => array(
				'Hover' => '.woocommerce span.price:hover',
				'Clicked' => '.woocommerce span.price:active'
			)
		));
		
		$this->register_block_element(array(
    		'id' => '.woocommerce-title',
			'name' => 'WooCommerce Title',
			'selector' => '.woocommerce div.product .product_title',
			'states' => array(
				'Hover' => '.woocommerce div.product .product_title:hover',
				'Clicked' => '.woocommerce div.product .product_title:active'
			)
		));
		
		$this->register_block_element(array(
    		'id' => '.woocommerce-description',
			'name' => 'WooCommerce Description',
			'selector' => '.woocommerce .block-type-content div.product div.summary div[itemprop="description"]',
			'states' => array(
				'Hover' => '.woocommerce .block-type-content div.product div.summary div[itemprop="description"]:hover',
				'Clicked' => '.woocommerce .block-type-content div.product div.summary div[itemprop="description"]:active'
			)
		));
		
		$this->register_block_element(array(
    		'id' => '.woocommerce-quantity',
			'name' => 'WooCommerce Quantity',
			'selector' => '.woocommerce .quantity .qty',
			'states' => array(
				'Hover' => '.woocommerce .quantity .qty:hover',
				'Clicked' => '.woocommerce .quantity .qty:active'
			)
		));
		
		$this->register_block_element(array(
    		'id' => '.woocommerce-cart-button',
			'name' => 'WooCommerce Cart Button',
			'selector' => '.woocommerce div.product form.cart .button',
			'states' => array(
				'Hover' => '.woocommerce div.product form.cart .button:hover',
				'Clicked' => '.woocommerce div.product form.cart .button:active'
			)
		));
		
		$this->register_block_element(array(
    		'id' => '.woocommerce-category',
			'name' => 'WooCommerce Category',
			'selector' => '.woocommerce span.posted_in',
			'states' => array(
				'Hover' => '.woocommerce span.posted_in:hover',
				'Clicked' => '.woocommerce span.posted_in:active'
			)
		));
		
		$this->register_block_element(array(
    		'id' => '.woocommerce-hyperlink',
			'name' => 'WooCommerce Link',
			'selector' => '.woocommerce a',
			'states' => array(
				'Hover' => '.woocommerce a:hover',
				'Clicked' => '.woocommerce a:active'
			)
		));
		
		$this->register_block_element(array(
    		'id' => '.woocommerce-paragraph',
			'name' => 'WooCommerce Body',
			'selector' => '.woocommerce p',
			'states' => array(
				'Hover' => '.woocommerce p:hover',
				'Clicked' => '.woocommerce p:active'
			)
		));
		
		$this->register_block_element(array(
    		'id' => '.woocommerce-active-tab',
			'name' => 'WooCommerce Active Tab',
			'selector' => '.woocommerce div.product .woocommerce-tabs ul.tabs li.active',
			'states' => array(
				'Hover' => '.woocommerce div.product .woocommerce-tabs ul.tabs li.active:hover',
				'Clicked' => '.woocommerce div.product .woocommerce-tabs ul.tabs li.active:active'
			)
		));
		
		$this->register_block_element(array(
    		'id' => '.woocommerce-tab',
			'name' => 'WooCommerce Tabs',
			'selector' => '.woocommerce div.product .woocommerce-tabs ul.tabs li',
			'states' => array(
				'Hover' => '.woocommerce div.product .woocommerce-tabs ul.tabs li:hover',
				'Clicked' => '.woocommerce div.product .woocommerce-tabs ul.tabs li:active'
			)
		));
		
        $this->register_block_element(array(
        	'id' => '.woocommerce-add-to-cart',
			'name' => 'WooCommerce Cart',
			'selector' => '.woocommerce #respond input#submit.alt, .woocommerce a.button.alt, .woocommerce button.button.alt, .woocommerce input.button.alt',
			'states' => array(
				'Hover' => '.woocommerce #respond input#submit.alt, .woocommerce a.button.alt, .woocommerce button.button.alt, .woocommerce input.button.alt:hover',
				'Clicked' => '.woocommerce #respond input#submit.alt, .woocommerce a.button.alt, .woocommerce button.button.alt, .woocommerce input.button.alt:active'
			)
		));
		
		$this->register_block_element(array(
    		'id' => 'form-headings',
			'name' => 'ContactForm7 Headings',
			'description' => 'ContactForm7 Headings',
			'selector' => '.wpcf7 p',
			'properties' => array('background', 'padding', 'fonts', 'corners', 'borders', 'box-shadow')
		));
        
    	$this->register_block_element(array(
			'id' => 'form-text',
			'name' => 'ContactForm7 Text Field',
			'description' => 'ContactForm7 Text Field',
			'selector' => 'input.wpcf7-form-control.wpcf7-text',
			'properties' => array('background', 'padding', 'fonts', 'corners', 'borders', 'box-shadow')
		));
		
		$this->register_block_element(array(
			'id' => 'form-email',
			'name' => 'ContactForm7 Email Field',
			'description' => 'ContactForm7 Email Field',
			'selector' => 'input.wpcf7-form-control.wpcf7-text.wpcf7-email.wpcf7-validates-as-email',
			'properties' => array('background', 'padding', 'fonts', 'corners', 'borders', 'box-shadow')
		));
		
		$this->register_block_element(array(
			'id' => 'form-url',
			'name' => 'ContactForm7 URL Field',
			'description' => 'ContactForm7 URL Field',
			'selector' => 'input.wpcf7-form-control.wpcf7-text.wpcf7-url.wpcf7-validates-as-url',
			'properties' => array('background', 'padding', 'fonts', 'corners', 'borders', 'box-shadow')
		));
		
		$this->register_block_element(array(
			'id' => 'form-number',
			'name' => 'ContactForm7 Number Field',
			'description' => 'ContactForm7 Number Field',
			'selector' => 'input.wpcf7-form-control.wpcf7-number.wpcf7-validates-as-number',
			'properties' => array('background', 'padding', 'fonts', 'corners', 'borders', 'box-shadow')
		));
		
		$this->register_block_element(array(
			'id' => 'form-range',
			'name' => 'ContactForm7 Range Field',
			'description' => 'ContactForm7 Range Field',
			'selector' => 'input.wpcf7-form-control.wpcf7-range.wpcf7-validates-as-number',
			'properties' => array('background', 'padding', 'fonts', 'corners', 'borders', 'box-shadow')
		));
		
		$this->register_block_element(array(
			'id' => 'form-date',
			'name' => 'ContactForm7 Date Field',
			'description' => 'ContactForm7 Date Field',
			'selector' => 'input.wpcf7-form-control.wpcf7-date.wpcf7-validates-as-date',
			'properties' => array('background', 'padding', 'fonts', 'corners', 'borders', 'box-shadow')
		));
		
		$this->register_block_element(array(
			'id' => 'form-textarea',
			'name' => 'ContactForm7 TextArea Field',
			'description' => 'ContactForm7 TextArea Field',
			'selector' => 'textarea.wpcf7-form-control.wpcf7-textarea',
			'properties' => array('background', 'padding', 'fonts', 'corners', 'borders', 'box-shadow')
		));
		
		$this->register_block_element(array(
			'id' => 'form-select',
			'name' => 'ContactForm7 Select Field',
			'description' => 'ContactForm7 Select Field',
			'selector' => 'select.wpcf7-form-control.wpcf7-select',
			'properties' => array('background', 'padding', 'fonts', 'corners', 'borders', 'box-shadow')
		));
		
		$this->register_block_element(array(
			'id' => 'form-radio',
			'name' => 'ContactForm7 Radio Field',
			'description' => 'ContactForm7 Radio Field',
			'selector' => 'span.wpcf7-form-control.wpcf7-radio',
			'properties' => array('background', 'padding', 'fonts', 'corners', 'borders', 'box-shadow')
		));
		
		$this->register_block_element(array(
			'id' => 'form-acceptance',
			'name' => 'ContactForm7 Acceptance Field',
			'description' => 'ContactForm7 Acceptance Field',
			'selector' => 'input.wpcf7-form-control.wpcf7-acceptance',
			'properties' => array('background', 'padding', 'fonts', 'corners', 'borders', 'box-shadow')
		));
		
		$this->register_block_element(array(
			'id' => 'form-quiz-label',
			'name' => 'ContactForm7 Quiz Label',
			'description' => 'ContactForm7 Quiz Label',
			'selector' => 'span.wpcf7-quiz-label',
			'properties' => array('background', 'padding', 'fonts', 'corners', 'borders', 'box-shadow')
		));
		
		$this->register_block_element(array(
			'id' => 'form-quiz-field',
			'name' => 'ContactForm7 Quiz Field',
			'description' => 'ContactForm7 Quiz Field',
			'selector' => 'input.wpcf7-form-control.wpcf7-quiz',
			'properties' => array('background', 'padding', 'fonts', 'corners', 'borders', 'box-shadow')
		));
		
		$this->register_block_element(array(
			'id' => 'form-captcha',
			'name' => 'ContactForm7 Captcha Field',
			'description' => 'ContactForm7 Captcha Field',
			'selector' => 'input.wpcf7-form-control.wpcf7-captchar',
			'properties' => array('background', 'padding', 'fonts', 'corners', 'borders', 'box-shadow')
		));
		
		$this->register_block_element(array(
			'id' => 'form-file',
			'name' => 'ContactForm7 File Field',
			'description' => 'ContactForm7 File Field',
			'selector' => 'input.wpcf7-form-control.wpcf7-file',
			'properties' => array('background', 'padding', 'fonts', 'corners', 'borders', 'box-shadow')
		));

		$this->register_block_element(array(
			'id' => 'form-submit',
			'name' => 'ContactForm7 Submit Field',
			'description' => 'ContactForm7 Submit Field',
			'selector' => 'input.wpcf7-form-control.wpcf7-submit',
			'properties' => array('background', 'padding', 'fonts', 'corners', 'borders', 'box-shadow')
		));

		$this->register_block_element(array(
		   'id' => 'gform_title',
		   'name' => 'GForm Title',
		   'selector' => '.gform_title',
		   'states' => array(
		   'Hover' => '.gform_title:hover',
		   'Clicked' => '.gform_title:selected'
		   )
		));

		$this->register_block_element(array(
			'id' => 'gfield_label',
			'name' => 'GForm Label',
			'selector' => '.gfield_label',
			'states' => array(
			'Hover' => '.gfield_label:hover',
			'Clicked' => '.gfield_label:selected'
			)
		));

		$this->register_block_element(array(
		   'id' => 'gfield_sub_label',
		   'name' => 'GForm Sub-Label',
		   'selector' => '.ginput_complex label, .gfield_time_hour label, .gfield_time_minute label, .gfield_time_ampm label, .gfield_date_month label, .gfield_date_day label, .gfield_date_year label, .instruction',
		   'states' => array(
		   'Hover' => '.ginput_complex label, .gfield_time_hour label, .gfield_time_minute label, .gfield_time_ampm label, .gfield_date_month label, .gfield_date_day label, .gfield_date_year label, .instruction:hover',
		   'Clicked' => '.ginput_complex label, .gfield_time_hour label, .gfield_time_minute label, .gfield_time_ampm label, .gfield_date_month label, .gfield_date_day label, .gfield_date_year label, .instruction:selected'
		   )
		));

		$this->register_block_element(array(
		   'id' => 'gfield_input',
		   'name' => 'GForm Input',
		   'selector' => '.ginput_complex input[type=text], .ginput_complex input[type=url], .ginput_complex input[type=email], .ginput_complex input[type=tel], .ginput_complex input[type=number], .ginput_complex input[type=password]',
		   'states' => array(
		   'Hover' => '.ginput_complex input[type=text], .ginput_complex input[type=url], .ginput_complex input[type=email], .ginput_complex input[type=tel], .ginput_complex input[type=number], .ginput_complex input[type=password]:hover',
		   'Clicked' => '.ginput_complex input[type=text], .ginput_complex input[type=url], .ginput_complex input[type=email], .ginput_complex input[type=tel], .ginput_complex input[type=number], .ginput_complex input[type=password]:selected'
		   )
		));

		$this->register_block_element(array(
		   'id' => 'gfield_input_med',
		   'name' => 'GForm Input Med',
		   'selector' => '.top_label input.medium, .top_label select.medium',
		   'states' => array(
		   'Hover' => '.top_label input.medium, .top_label select.medium:hover',
		   'Clicked' => '.top_label input.medium, .top_label select.medium:selected'
		   )
		));

		$this->register_block_element(array(
		   'id' => 'gfield_text_area',
		   'name' => 'GForm Text Area',
		   'selector' => 'textarea.medium',
		   'states' => array(
		   'Hover' => 'textarea.medium:hover',
		   'Clicked' => 'textarea.medium:selected'
		   )
		));

		$this->register_block_element(array(
		   'id' => 'gfield_submit',
		   'name' => 'GForm Submit',
		   'selector' => '.gform_footer input.button, .gform_footer input[type=submit], .gform_footer input[type=image],.gform_page_footer .button.gform_next_button, .gform_page_footer .button.gform_button',
		   'states' => array(
		   'Hover' => '.gform_footer input.button, .gform_footer input[type=submit], .gform_footer input[type=image],.gform_page_footer .button.gform_next_button, .gform_page_footer .button.gform_button:hover',
		   'Clicked' => '.gform_footer input.button, .gform_footer input[type=submit], .gform_footer input[type=image],.gform_page_footer .button.gform_next_button, .gform_page_footer .button.gform_button:selected'
		   )
		));

		$this->register_block_element(array(
		   'id' => 'gfield_progress_bar_title',
		   'name' => 'GForm Progress Bar Title',
		   'selector' => 'h3.gf_progressbar_title',
		   'states' => array(
		   'Hover' => 'h3.gf_progressbar_title:hover',
		   'Clicked' => 'h3.gf_progressbar_title:selected'
		   )
		));

		$this->register_block_element(array(
		   'id' => 'gfield_progress_bar_colour',
		   'name' => 'GForm Progress Bar Colour',
		   'selector' => '.percentbar_blue',
		   'states' => array(
		   'Hover' => '.percentbar_blue:hover',
		   'Clicked' => '.percentbar_blue:selected'
		   )
		));

		$this->register_block_element(array(
		   'id' => 'gfield_progress_bar',
		   'name' => 'GForm Progress Bar',
		   'selector' => '.gf_progressbar',
		   'states' => array(
		   'Hover' => '.gf_progressbar:hover',
		   'Clicked' => '.gf_progressbar:selected'
		   )
		));

		$this->register_block_element(array(
		   'id' => 'gfield_page_title',
		   'name' => 'GForm Page Title',
		   'selector' => '.gsection .gfield_label, h2.gsection_title, h3.gform_title',
		   'states' => array(
		   'Hover' => '.gsection .gfield_label, h2.gsection_title, h3.gform_title:hover',
		   'Clicked' => '.gsection .gfield_label, h2.gsection_title, h3.gform_title:selected'
		   )
		));

		$this->register_block_element(array(
		   'id' => 'gfield_checkbox',
		   'name' => 'GForm Checkbox',
		   'selector' => '.gfield_checkbox li input[type=checkbox],  .gfield_checkbox li input',
		   'states' => array(
		   'Hover' => '.gfield_checkbox li input[type=checkbox],  .gfield_checkbox li input:hover',
		   'Clicked' => '.gfield_checkbox li input[type=checkbox],  .gfield_checkbox li input:selected'
		   )
		));

		$this->register_block_element(array(
		   'id' => 'gfield_checkbox_label',
		   'name' => 'GForm Checkbox Label',
		   'selector' => '.gfield_checkbox li label',
		   'states' => array(
		   'Hover' => '.gfield_checkbox li label:hover',
		   'Clicked' => '.gfield_checkbox li label:selected'
		   )
		));

		$this->register_block_element(array(
		   'id' => 'gfield_radio',
		   'name' => 'GForm Radio',
		   'selector' => '.gfield_radio li input[type=radio],  .gfield_radio li input',
		   'states' => array(
		   'Hover' => '.gfield_radio li input[type=radio],  .gfield_radio li input:hover',
		   'Clicked' => '.gfield_radio li input[type=radio],  .gfield_radio li input:selected'
		   )
		));

		$this->register_block_element(array(
		   'id' => 'gfield_radio_label',
		   'name' => 'GForm Radio Label',
		   'selector' => '.gfield_radio li label',
		   'states' => array(
		   'Hover' => '.gfield_radio li label:hover',
		   'Clicked' => '.gfield_radio li label:selected'
		   )
		));

		$this->register_block_element(array(
		   'id' => 'gfield_form_description',
		   'name' => 'GForm Form Description',
		   'selector' => '.gform_description',
		   'states' => array(
		   'Hover' => '.gform_description:hover',
		   'Clicked' => '.gform_description:selected'
		   )
		));

		$this->register_block_element(array(
		   'id' => 'gfield_section',
		   'name' => 'GForm Form Section',
		   'selector' => '.gsection',
		   'states' => array(
		   'Hover' => '.gsection:hover',
		   'Clicked' => '.gsection:selected'
		   )
		));

		$this->register_block_element(array(
		   'id' => 'gfield_html',
		   'name' => 'GForm HTML Section',
		   'selector' => '.gfield_html',
		   'states' => array(
		   'Hover' => '.gfield_html:hover',
		   'Clicked' => '.gfield_html:selected'
		   )
		));

		$this->register_block_element(array(
		   'id' => 'gfield_upload',
		   'name' => 'GForm Upload',
		   'selector' => '.gfield_list td.gfield_list_cell input',
		   'states' => array(
		   'Hover' => '.gfield_list td.gfield_list_cell input:hover',
		   'Clicked' => '.gfield_list td.gfield_list_cell input:selected'
		   )
		));

		$this->register_block_element(array(
		   'id' => 'gfield_upload_icon',
		   'name' => 'GForm Upload Icon',
		   'selector' => 'img.add_list_item',
		   'states' => array(
		   'Hover' => 'img.add_list_item:hover',
		   'Clicked' => 'img.add_list_item:selected'
		   )
		));

		$this->register_block_element(array(
		   'id' => 'gfield_time',
		   'name' => 'GForm Time',
		   'selector' => '.gfield_time_hour input, .gfield_time_minute input, .gfield_date_month input, .gfield_date_day input, .gfield_date_year input',
		   'states' => array(
		   'Hover' => '.gfield_time_hour input, .gfield_time_minute input, .gfield_date_month input, .gfield_date_day input, .gfield_date_year input:hover',
		   'Clicked' => '.gfield_time_hour input, .gfield_time_minute input, .gfield_date_month input, .gfield_date_day input, .gfield_date_year input:selected'
		   )
		));

		$this->register_block_element(array(
		   'id' => 'gfield_time_amppm',
		   'name' => 'GForm Time AM/PM Selector',
		   'selector' => '.gfield_time_ampm ',
		   'states' => array(
		   'Hover' => '.gfield_time_ampm :hover',
		   'Clicked' => '.gfield_time_ampm :selected'
		   )
		));

		$this->register_block_element(array(
		   'id' => 'price_table_header',
		   'name' => 'Price Table Header',
		   'selector' => '.rpt_title',
		   'states' => array(
		   'Hover' => '.rpt_title:hover',
		   'Clicked' => '.rpt_title:selected'
		   )
		));

		$this->register_block_element(array(
		   'id' => 'price_table_recurrence',
		   'name' => 'Price Table Recurrence',
		   'selector' => '.rpt_recurrence',
		   'states' => array(
		   'Hover' => '.rpt_recurrence:hover',
		   'Clicked' => '.rpt_recurrence:selected'
		   )
		));

		$this->register_block_element(array(
		   'id' => 'price_table_currency',
		   'name' => 'Price Table Currency',
		   'selector' => '.rpt_currency',
		   'states' => array(
		   'Hover' => '.rpt_currency:hover',
		   'Clicked' => '.rpt_currency:selected'
		   )
		));

		$this->register_block_element(array(
		   'id' => 'price_table_price',
		   'name' => 'Price Table Price',
		   'selector' => '.rpt_price',
		   'states' => array(
		   'Hover' => '.rpt_price:hover',
		   'Clicked' => '.rpt_price:selected'
		   )
		));

		$this->register_block_element(array(
		   'id' => 'price_table_subtitle',
		   'name' => 'Price Table Subtitle',
		   'selector' => '.rpt_subtitle',
		   'states' => array(
		   'Hover' => '.rpt_subtitle:hover',
		   'Clicked' => '.rpt_subtitle:selected'
		   )
		));

		$this->register_block_element(array(
		   'id' => 'price_table_desc',
		   'name' => 'Price Table Description',
		   'selector' => '.rpt_description',
		   'states' => array(
		   'Hover' => '.rpt_description:hover',
		   'Clicked' => '.rpt_description:selected'
		   )
		));

		$this->register_block_element(array(
		   'id' => 'price_table_feature',
		   'name' => 'Price Table Feature',
		   'selector' => '.rpt_feature ',
		   'states' => array(
		   'Hover' => '.rpt_feature :hover',
		   'Clicked' => '.rpt_feature :selected'
		   )
		));

		$this->register_block_element(array(
		   'id' => 'price_table_feature_bg',
		   'name' => 'Price Table Feature Background',
		   'selector' => '.rpt_features ',
		   'states' => array(
		   'Hover' => '.rpt_features :hover',
		   'Clicked' => '.rpt_features :selected'
		   )
		));

		$this->register_block_element(array(
		   'id' => 'price_table_button',
		   'name' => 'Price Table Button',
		   'selector' => '.rpt_foot',
		   'states' => array(
		   'Hover' => '.rpt_foot:hover',
		   'Clicked' => '.rpt_foot:selected'
		   )
		));

		$this->register_block_element(array(
		   'id' => 'price_table_price_bg',
		   'name' => 'Price Table Price Background',
		   'selector' => '.rpt_head',
		   'states' => array(
		   'Hover' => '.rpt_head:hover',
		   'Clicked' => '.rpt_head:selected'
		   )
		));    

		$this->register_block_element(array(
		   'id' => 'price_table_recommended_img',
		   'name' => 'Price Table Recommended Image',
		   'selector' => 'img.rpt_recommended',
		   'states' => array(
		   'Hover' => 'img.rpt_recommended:hover',
		   'Clicked' => 'img.rpt_recommended:selected'
		   )
		));

		$this->register_block_element(array(
		   'id' => 'price_table_recommended_icon',
		   'name' => 'Price Table Recommended Icon',
		   'selector' => '.rpt_icon',
		   'states' => array(
		   'Hover' => '.rpt_icon:hover',
		   'Clicked' => '.rpt_icon:selected'
		   )
		)); 
	}

	function content($block) {
	
		$shortcodeproduct 	= parent::get_setting($block, 'shortcode-product-type', ' ');
		$wcshortcode 		= parent::get_setting($block, 'wc-shortcode-type', ' ');
		$wcproduct 			= parent::get_setting($block, 'wc-product-count', '12');
		$wccolumn 			= parent::get_setting($block, 'wc-column-count', '4');
		$wccategory 		= parent::get_setting($block, 'wc-category', ' ');
		$wcorderby 			= parent::get_setting($block, 'wc-order-by', 'menu_order');
		$wcorder 			= parent::get_setting($block, 'wc-order', 'asc');
		$gfshortcode 		= parent::get_setting($block, 'gravityform-shortcode', 'none');
		$gftitle 			= parent::get_setting($block, 'gravityform-title', true);
		$gfdescription 		= parent::get_setting($block, 'gravityform-description', true);
		$gfdescription 		= parent::get_setting($block, 'gravityform-description', true);
		$gfajax 			= parent::get_setting($block, 'gravityform-ajax', false);
		$priceshortcode 	= parent::get_setting($block, 'price-shortcode', 'none');
		        		
		$block_width 		= UpFrontBlocksData::get_block_width($block);
		$block_height 		= UpFrontBlocksData::get_block_height($block);
		
		$converted_title 	= ($gftitle) ? 'true' : 'false';
		$converted_desc 	= ($gfdescription) ? 'true' : 'false';
		$converted_ajax 	= ($gfajax) ? 'true' : 'false';
		
				
		if($shortcodeproduct == 'woo'){
			if($wcshortcode == 'none'){
				echo '<p>Please check your settings</p>';
			}else{
				echo do_shortcode( '['. $wcshortcode .' per_page="'. $wcproduct .'" columns="'. $wccolumn .'" category="'. $wccategory .'" orderby="'. $wcorderby .'" order="'. $wcorder .'"]' );
			}
		}elseif($shortcodeproduct == 'cf7'){
			
			$cf7shortcode = parent::get_setting($block, 'contactform7-shortcode', '');
			
			if( defined('WPCF7_REQUIRED_WP_VERSION') ){
				
				if( $cf7shortcode == null ) {
					echo '<h2 style="color:red;font-weight:700;text-transform:uppercase;border:solid 1px red;padding:5px 10px;background-color:pink;">You need to enter your forms name into the block</h2>';
				}else{
					echo do_shortcode( '[contact-form-7 title="'. $cf7shortcode .' "]' );
				}

			}else{
				
				echo '<h2 style="color:red;font-weight:700;text-transform:uppercase;border:solid 1px red;padding:5px 10px;background-color:pink;">You need to have the Contact Form 7 Plugin installed and activated</h2>'; 
			}
		
		}elseif($shortcodeproduct == 'gravity'){
			
			echo do_shortcode( '[gravityform id='.$gfshortcode.' title='.$converted_title.' description='.$converted_desc.' ajax='.$converted_ajax.']');
			
		}elseif($shortcodeproduct == 'price') {

			echo do_shortcode( '[rpt name="'.$priceshortcode.'"]');
			
		}else{
			
			echo '<p>Please check your settings</p>';
		
		}
	}
	
}

class UpFrontShortcodesBlockOptions extends UpFrontBlockOptionsAPI {

	public $tabs = array(
		'content-tab' 	=> 'Content',
		'woo-tab' 		=> 'WooCommerce',
		'cf7-tab' 		=> 'Contact Form 7',
		'gravity-tab' 	=> 'Gravity Forms',
		'price-tab' 	=> 'Pricing Table',
	);


	public $inputs = array(

		'content-tab' => array(
			'shortcode-product-type' => array(
				'type' => 'select',
				'name' => 'shortcode-product-type',
				'label' => 'Which Product',
				'options' => array(
					'none' 		=> 'Choose your product',
					'woo' 		=> 'WooCommerce',
					'cf7' 		=> 'Contact Form 7',
					'gravity' 	=> 'Gravity Forms',
					'price' 	=> 'Price Tables'
				),
			'toggle' => array(
				'none' => array(
					'show' => array (),
					'hide' => array(
						'#sub-tab-woo-tab',
						'#sub-tab-cf7-tab',
						'#sub-tab-gravity-tab',
						'#sub-tab-price-tab',
					),

				),
				'price' => array(
					'hide' => array (
						'#sub-tab-cf7-tab',
						'#sub-tab-gravity-tab',
						'#sub-tab-woo-tab',
					),
					'show' => array(
						'#sub-tab-price-tab',
					),
				),
				'woo' => array(
					'hide' => array (
						'#sub-tab-price-tab',
						'#sub-tab-cf7-tab',
						'#sub-tab-gravity-tab',
					),
					'show' => array(
						'#sub-tab-woo-tab',
					),
				),
				'cf7' => array(
					'show' => array (
						'#sub-tab-cf7-tab',
					),
					'hide' => array(
						'#sub-tab-woo-tab',
						'#sub-tab-gravity-tab',
						'#sub-tab-price-tab',
					),
				),
				'gravity' => array(
					'show' => array (
						'#sub-tab-gravity-tab',
					),
					'hide' => array(
						'#sub-tab-woo-tab',
						'#sub-tab-cf7-tab',
						'#sub-tab-price-tab',
					),
				),
			),
			'default' => '',
			'tooltip' => '',
		),
		),
		'woo-tab' => array(
			'wc-shortcode-type' => array(
				'type' => 'select',
				'name' => 'wc-shortcode-type',
				'label' => 'WooCommerce Content',
				'options' => array(
					'' => 'Choose your content',
					'recent_products' => 'Recent Products',
					'featured_products' => 'Featured Products',
					'product_category' => 'Product Category',
					'sale_products' => 'Sale Products',
					'best_selling_products' => 'Best Selling Products',
					'top_rated_products' => 'Top Rated Products'
				),
				'toggle' => array(
					'none' => array(
						'hide' => '#sub-tab-woo-tab-content #input-wc-category',
						'show' => array(),
					),
					'product_category' => array(
						'show' => '#sub-tab-woo-tab-content #input-wc-category',
						'hide' => array(),
					),
					'recent_products' => array(
						'hide' => '#sub-tab-woo-tab-content #input-wc-category',
						'show' => array(),
					),
					'featured_products' => array(
						'hide' => '#sub-tab-woo-tab-content #input-wc-category',
						'show' => array(),
					),
					'sale_products' => array(
						'hide' => '#sub-tab-woo-tab-content #input-wc-category',
						'show' => array(),
					),
					'best_selling_products' => array(
						'hide' => '#sub-tab-woo-tab-content #input-wc-category',
						'show' => array(),
					),
					'top_rated_products' => array(
						'hide' => '#sub-tab-woo-tab-content #input-wc-category',
						'show' => array(),
					),
				),
				'default' => '',
				'tooltip' => 'Choose your WooCommerce content to display',
			),
			'wc-category' => array(
				'type' => 'select',
				'name' => 'wc-category',
				'label' => 'Category Slug',
				'default' => '',
				'tooltip' => 'Choose your category to display.<br />Tip: You can find the category slug in the WooCommerce Category Admin panel'
			),
			'wc-product-count' => array(
				'type' => 'integer',
				'name' => 'wc-product-count',
				'label' => 'Product Qty',
				'default' => '12',
				'tooltip' => 'Choose how many products to display per page.'
			),
			'wc-column-count' => array(
				'type' => 'integer',
				'name' => 'wc-column-count',
				'label' => 'Columns',
				'default' => '4',
				'tooltip' => 'Choose how many columns.'
			),
			'wc-order-by' => array (
				'type' => 'select',
				'name' => 'wc-order-by',
				'label' => 'Order by',
				'default' => 'menu_order',
				'options' => array (
					'menu-order' => 'Menu Order',
					'title' => 'Title',
					'date' => 'Date',
					'rand' => 'Random',
					'id' => 'ID'
				),
			),
			'wc-order' => array (
				'type' => 'select',
				'name' => 'wc-order',
				'label' => 'Order',
				'default' => 'asc',
				'options' => array (
					'asc' => 'Ascending',
					'desc' => 'Descending'
				),
			),
		),
		'cf7-tab' => array(
			'contactform7-shortcode' => array(
				'type' => 'select',
				'name' => 'contactform7-shortcode',
				'label' => 'Form Name',
				'default' => ' ',
				'tooltip' => 'Choose the name of your form',
			),
		),
		'gravity-tab' => array(
			'gravityform-shortcode' => array(
				'type' => 'select',
				'name' => 'gravityform-shortcode',
				'label' => 'Form Name',
				'default' => ' ',
				'tooltip' => 'Choose the name of your form',
			),
			'gravityform-title' => array(
				'type' => 'checkbox',
				'name' => 'gravityform-title',
				'label' => 'Display Form Title',
				'default' => True,
				'tooltip' => 'Choose to have your forms title display.'
			),
			'gravityform-description' => array(
				'type' => 'checkbox',
				'name' => 'gravityform-description',
				'label' => 'Display Form Description',
				'default' => True,
				'tooltip' => 'Choose to have your forms description display.'
			),
			'gravityform-ajax' => array(
				'type' => 'checkbox',
				'name' => 'gravityform-ajax',
				'label' => 'Use Ajax for Form Submissions?',
				'default' => false,
				'tooltip' => 'Choose to have your forms submitted by Ajax.'
			),
		),
		'price-tab' => array(
			'price-heading' => array(
				'type' => 'heading',
				'name' => 'price-heading',
				'label' => 'You can download Responsive Price Table from the <a href="https://wordpress.org/plugins/dk-pricr-responsive-pricing-table/" target="_blank">WordPress repository</a>',
			),
			'price-shortcode' => array(
				'type' => 'select',
				'name' => 'price-shortcode',
				'label' => 'Pricing Tables',
				'default' => ' ',
				'tooltip' => 'Choose the name of your tables',
			),
		),
	);
	
	public function modify_arguments($args = false){
		
		$wcatTerms 	= get_terms('product_cat',array('hide_empty'=>false));
		$options 	= array( 'none' => 'Choose Your Category');
		
		foreach($wcatTerms as $wcatTerm) {
			$options[$wcatTerm->slug] = $wcatTerm->name;
		}
		
		$this->inputs['woo-tab']['wc-category']['options'] = $options;

		if (class_exists('WPCF7_ContactForm')) {
			
			$forms 	= WPCF7_ContactForm::find();
			$options = array( 'none' => 'Choose Your Form');

			foreach ( $forms as $form ) {
				$options[$form->title] = $form->title;
			}
			
			$this->inputs['cf7-tab']['contactform7-shortcode']['options'] = $options;
		
		}else{
			$this->inputs['cf7-tab']['contactform7-shortcode']['options'] = array('none'=>'Contact Form 7 is not installed');
		}

		if (class_exists('GFAPI')) {

			$forms 	= RGFormsModel::get_forms();
			$options = array('none' => 'Choose Your Form');
		
			foreach ( $forms as $form ) {
				$options[$form->id] = $form->title;
			}

			$this->inputs['gravity-tab']['gravityform-shortcode']['options'] = $options;
		
		} else {
			$this->inputs['gravity-tab']['gravityform-shortcode']['options'] = array('none'=>'Gravity Forms is not installed');
		}

		if (function_exists('create_rpt_pricing_table_type')) {
			
			$args 		= array( 'post_type' => 'rpt_pricing_table', 'posts_per_page' => -1 );
			$myposts 	= get_posts( $args );
			$options 	= array('none' => 'Choose Your Price Table');

			foreach ( $myposts as $post ) { 
				$options[$post->post_name] = $post->post_title;
			}
			
			$this->inputs['price-tab']['price-shortcode']['options'] = $options;

		}else{
			$this->inputs['price-tab']['price-shortcode']['options'] = array('none'=>'Responsive Price Table is not installed');
		}
	}
}