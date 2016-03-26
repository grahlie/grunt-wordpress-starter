<?php

/** 
 *
 * Create Theme option page for admin settings page
 * And Functions for outputting these settings
 */

// Create page
add_action('admin_init', 'grahlie_theme_settings');
function grahlie_theme_settings(){
    $theme_options['title'] = 'Theme Options';
    $theme_options['desc']  = 'Your overall settings for your site. Logotype, favicon and also tracking code for Google Analytics';
    $theme_options['id']    = 'theme_options';

    $theme_options[] = array(
        'title'     => 'Use logotype',
        'desc'      => 'If you want to use logotype in header check this box.',
        'type'      => 'checkbox',
        'id'        => 'use_logotype'
    );
                                
    $theme_options[] = array(
        'title'     => 'Logotype',
        'desc'      => 'Upload your logotype here.',
        'type'      => 'file',
        'id'        => 'logotype_file',
        'val'       => 'Upload image'    
    );
                                
    $theme_options[] = array(
        'title'     => 'Custom Favicon Upload',
        'desc'      => 'Upload a 16x16 png as your favicon',
        'type'      => 'file',
        'id'        => 'favicon_file',
        'val'       => 'Upload Image'
    );
                                
    $theme_options[] = array(
        'title'     => 'Contact Form Email Address',
        'desc'      => 'Enter the email where you\'d like to receive emails.',
        'type'      => 'text',
        'id'        => 'contact_email'
    );
                                
    $theme_options[] = array(
        'title'     => 'Google Analytics',
        'desc'      => 'Your Google analytics code.',
        'type'      => 'text',
        'id'        => 'google_analytics'
    );
                                
                                
    grahlie_add_framework_page( 'Theme Options', $theme_options );
}

// Frontend functions


?>
