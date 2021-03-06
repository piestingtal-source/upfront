<?php
class UpFrontGoogleFonts extends UpFrontWebFontProvider {


	public $id 					= 'google';
	public $name 				= 'Google Web Fonts';
	public $webfont_provider 	= 'google';
	public $load_with_ajax 		= true;


	public $sorting_options = array(
		'popularity' 	=> 'Popularität',
		'trending' 		=> 'Im Trend',
		'alpha' 		=> 'Alphabetisch',
		'date' 			=> 'Datum hinzugefügt',
		'style' 		=> 'Style'
	);

	protected $api_url = UPFRONT_API_URL . 'googlefonts';

	// ToDo: arrange backuplocation
    protected $backup_api_url = UPFRONT_API_URL . 'googlefonts';


	public function query_fonts($sortby = 'date', $retry = false) {

		$url 			= $this->api_url . '/' . $sortby;
		$fonts_query 	= wp_remote_get($url);

		if ( is_wp_error($fonts_query) ) {
			return;
		}

		$data = wp_remote_retrieve_body( $fonts_query );

		// Check for error
		if ( is_wp_error( $data ) ) {
			return;
		}

		return json_decode($data, true);

	}
}
