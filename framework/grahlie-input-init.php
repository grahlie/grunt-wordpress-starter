<?php
/**
 * Core functions for other files in the framework
 */

// Fixa första funktionen med standard array med options
function grahlie_create_framework_page(){
    $framework_init = array(
        0 => array(
            'title'         => 'Theme Options',
            'desc'          => 'Your standard settings for your site.',
            'id'            => 'theme_options',
            0 => array(
                'title'     => 'Use logotype',
                'desc'      => 'If you want to use logotype in header check this box.',
                'type'      => 'checkbox',
                'id'        => 'use_logotype'
            ),
            1 => array(
                'title'     => 'Logotype',
                'desc'      => 'Upload your logotype here.',
                'type'      => 'file',
                'id'        => 'logotype_file',
                'val'       => 'Upload image'
            ),
            2 => array(
                'title'     => 'Google Analytics',
                'desc'      => 'Your Google analytics code.',
                'type'      => 'text',
                'id'        => 'google_analytics'
            )
        ),
        1 => array(
            'title'         => 'Frontpage Options',
            'desc'          => 'Settings for your frontpage.',
            'id'            => 'frontpage_options',
            0 => array(
                'title'     => 'Showcase pages on firstpage',
                'desc'      => 'Check this box if you want pages to show up on frontpage.',
                'type'      => 'checkbox',
                'id'        => 'use_pages',
                'sync'      => 'use_pages_count',
            ),
            1 => array (
                'title'     => 'How many pages',
                'desc'      => 'Choose how many pages you want on frontpage.',
                'type'      => 'radio',
                'id'        => 'use_pages_count',
                'sync'     => 'use_pages_count_select',
                'options'   => array (
                    '1'     => 'one',
                    '2'     => 'two',
                    '3'     => 'three',
                    '4'     => 'four'
                )
            ),
            2 => array (
                'title'     => 'Which pages to show',
                'desc'      => 'Choose which pages to show on frontpage',
                'type'      => 'select',
                'id'        => 'use_pages_count_select',
                'sync'    => 'use_pages_count'
            )
        )
    );

    return $framework_init;
}
