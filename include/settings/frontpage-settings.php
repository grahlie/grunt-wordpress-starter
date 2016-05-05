<?php

/** 
 *
 * Create Theme option page for admin settings page
 * And Functions for outputting these settings
 */

// Create page
add_action('admin_init', 'grahlie_frontpage_settings');
function grahlie_frontpage_settings(){
    $frontpage_options['title'] = 'Frontpage Options';
    $frontpage_options['desc']  = 'Settings for your frontpage.';
    $frontpage_options['id']    = 'frontpage_options';
                                
    $frontpage_options[] = array(
        'title'     => 'Showcase pages on firstpage',
        'desc'      => 'Check this box if you want pages to show up on frontpage.',
        'type'      => 'checkbox',
        'id'        => 'use_pages',
        'sync'      => array(
            0 => array (
                'title'     => 'How many pages',
                'desc'      => 'Choose how many pages you want on frontpage.',
                'type'      => 'radio',
                'id'        => 'use_pages_count',
                'options'   => array (
                    '1'     => 'one',
                    '2'     => 'two',
                    '3'     => 'three',
                    '4'     => 'four'
                )
            ),
            1 => array (
                'title'     => 'Which pages to show',
                'desc'      => 'Choose which pages to show on frontpage',
                'type'      => 'select',
                'id'        => 'use_pages_select',
                'wppage'    => 'yes'
            )
        )
    );
                                
                                
    grahlie_add_framework_page( 'Frontpage Options', $frontpage_options );
}

/**
 * Output pages defined in framework
 */
function grahlie_use_pages($class = null){

    $grahlie_values = get_option( 'grahlie_framework_values' );
    $output = '';

    if( array_key_exists('use_pages', $grahlie_values) && $grahlie_values['use_pages'] == 'on' && array_key_exists('use_pages_count', $grahlie_values) ){
        
        for($i=1; $i <= $grahlie_values['use_pages_count']; $i++) {

            $page  = get_post($grahlie_values['use_pages_select'][$i]);
            $thumb = get_the_post_thumbnail($page->ID);
            $size  = 'size' . 12/$grahlie_values['use_pages_count'];
            $id    = get_post_class()[0];
            $type  = get_post_class()[1];
            $class .= ' ' . $id . ' ' . $type . ' column';

            $output .= '
                <div id="' . $id . '" class="grahlieBox' . $class . ' ' . $size . '">
                ' . $thumb . '
                    <h2>' . $page->post_title . '</h2>
                    <p>' . $page->post_excerpt . '</p>
                    <a href="' . $page->post_name .'">Läs mer</a>
                </div>';
        }

    }

    return $output;
}

?>
