<?php
/**
 * 1.2.9
 *
 * CSS grid 
 * Add "legacy" grid system to wrappers
 */

add_action('upfront_do_upgrade_129', 'upfront_do_upgrade_129');
function upfront_do_upgrade_129() {

    $all_wrappers = UpFrontWrappersData::get_all_wrappers();

    foreach ( $all_wrappers as $wrapper_id => $wrapper) {
        
        if( !isset($wrapper['settings']['grid-system']) ){            
            
            $wrapper['settings']['grid-system'] = 'legacy';
            UpFrontWrappersData::update_wrapper( $wrapper_id, $wrapper );

        }

    }


    if( UpFrontSkinOption::get('grid-system') === '' ){
        UpFrontSkinOption::set('grid-system', 'css-grid');
    }
}