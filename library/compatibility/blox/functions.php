<?php
add_action( 'edit_form_after_editor', 'blox_meta_upfront_save_post_template_bypass' );
function blox_meta_upfront_save_post_template_bypass() {
	return upfront_meta_upfront_save_post_template_bypass();
}
function blox_register_admin_meta_box( $class ) {
	return upfront_register_admin_meta_box( $class );
}
function blox_register_block( $class, $block_type_url = false ) {
	return upfront_register_block( $class, $block_type_url );
}
function blox_register_visual_editor_box( $class ) {
	return upfront_register_visual_editor_box( $class );
}
function blox_register_visual_editor_box_callback( $class ) {
	return upfront_register_visual_editor_box_callback( $class );
}
function blox_register_visual_editor_panel( $class ) {
	return upfront_register_visual_editor_panel( $class );
}
function blox_register_visual_editor_panel_callback( $class ) {
	return upfront_register_visual_editor_panel_callback( $class );
}
function blox_maybe_unserialize( $string ) {
	return upfront_maybe_unserialize( $string );
}
function blox_maybe_serialize( $data ) {
	return upfront_maybe_serialize( $data );
}
function blox_url() {
	return upfront_url();
}
function blox_cache_url() {
	return upfront_cache_url();
}
function blox_get( $name, $array = false, $default = null, $fix_data_type = false ) {
	return upfront_get( $name, $array, $default, $fix_data_type );
}
function blox_post( $name, $default = null ) {
	return upfront_post( $name, $default );
}
function blox_format_url_ssl( $url ) {
	return upfront_format_url_ssl( $url );
}
function blox_get_current_url() {
	return upfront_get_current_url();
}
function blox_change_to_unix_path( $path ) {
	return upfront_change_to_unix_path( $path );
}
function blox_fix_data_type( $data ) {
	return upfront_fix_data_type( $data );
}
function blox_thumbnail() {
	return upfront_thumbnail();
}
function blox_resize_image( $url, $width = null, $height = null, $crop = true, $single = true, $upscale = true  ) {
	return upfront_resize_image( $url, $width, $height, $crop, $single, $upscale );
}
function blox_is_ie( $version_check = false ) {
	return upfront_is_ie( $version_check );
}
function blox_parse_php( $content ) {
	return upfront_parse_php( $content );
}
function blox_in_numeric_range( $check, $begin, $end, $allow_equals = true ) {
	return upfront_in_numeric_range( $check, $begin, $end, $allow_equals );
}
function blox_remove_from_array( array &$array, $value ) {
	return upfront_remove_from_array( $array, $value );
}
function blox_array_insert( array &$array, array $insert, $position ) {
	return upfront_array_insert( $array, $insert, $position );
}
function blox_array_key_neighbors( $array, $findKey, $valueOnly = true ) {
	return upfront_array_key_neighbors( $array, $findKey, $valueOnly );
}
function blox_array_map_recursive(  $callback, $array  ) {
	return upfront_array_map_recursive(  $callback, $array  );
}
function blox_array_merge_recursive_simple() {
	return upfront_array_merge_recursive_simple();
}
function blox_array_merge_recursive_simple_recurse( $array, $array1 ) {
	return upfront_array_merge_recursive_simple_recurse( $array, $array1 );
}
function blox_format_color( $color, $pound_sign = true ) {
	return upfront_format_color( $color, $pound_sign );
}
function blox_get_browser() {
	return upfront_get_browser();
}
function blox_str_replace_json( $search, $replace, $subject ) {
	return upfront_str_replace_json( $search, $replace, $subject );
}
function blox_preg_replace_json( $pattern, $replace, $subject ) {
	return upfront_preg_replace_json( $pattern, $replace, $subject );
}
function blox_get_search_form( $placeholder = null ) {
	return upfront_get_search_form( $placeholder );
}
function blox_human_bytes( $size ) {
	return upfront_human_bytes( $size );
}