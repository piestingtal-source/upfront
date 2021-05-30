<!DOCTYPE HTML>
<html <?php language_attributes(); ?>>

<head>

<meta charset="<?php echo get_bloginfo('charset'); ?>" />
<link rel="profile" href="http://gmpg.org/xfn/11" />


<?php

$GLOBALS['wp_media_view_strings'] = array();
$GLOBALS['wp_media_view_settings'] = array();

/* Beginne in einigen Fällen mit kludge, um das Problem mit der Lokalisierung von WP-Zeichenfolgen zu beheben.  Siehe: https://core.trac.wordpress.org/ticket/24724 */
function upfront_media_view_settings_grabber( $settings ) {

	$GLOBALS['wp_media_view_settings'] = $settings;

	return $settings;

}
add_filter( 'media_view_settings', 'upfront_media_view_settings_grabber' );


function upfront_media_view_strings_grabber($strings) {

	$strings['attachmentsList'] = '';
	$GLOBALS['wp_media_view_strings'] = $strings;

	return $strings;

}
add_filter('media_view_strings', 'upfront_media_view_strings_grabber');

wp_enqueue_media();

$GLOBALS['wp_media_view_strings']['settings'] = $GLOBALS['wp_media_view_settings'];

//wp_enqueue_script( 'media-editor', upfront_url() . '/library/visual-editor/scripts/deps/media-editor.js', array( 'jquery' ) );
wp_localize_script( 'media-editor', '_wpMediaViewsL10n', $GLOBALS['wp_media_view_strings'] );

//wp_register_script( 'media-views', upfront_url() . '/library/visual-editor/scripts/deps/media-views.js' );
wp_localize_script( 'media-views', '_wpMediaViewsL10n', $GLOBALS['wp_media_view_strings'] );
/* Ende Kludge */

wp_enqueue_style('open-sans');

wp_print_styles();
?>

<style type="text/css">
	.media-modal {
		top: 0;
		left: 0;
		right: 0;
		bottom: 0;
	}

	.media-modal-close {
		display: none;
	}

	.media-frame-router {
		top: 5px;
	}

	.media-frame-content {
		top: 39px;
	}
	.media-frame-actions-heading{
		display: none;
	}
	h2.media-attachments-filter-heading{}
	label[for='media-attachment-date-filters']{
		float: left;
	    padding: 0;
	    margin-top: 25px;
	}
	#media-attachment-date-filters{
		float: left;
	    margin-top: 15px;
	    margin-left: 10px;
	}
</style>

</head>
<body>
<?php
wp_print_media_templates();


wp_print_scripts();
?>
</body>
</html>