<?php

/** 
 *
 * Create Theme option page for admin settings page
 * And Functions for outputting these settings
 */

// Create page
add_action('admin_init', 'grahlie_multisite_settings');
function grahlie_multisite_settings()
{
    $multisite_options['title'] = 'Multisite Options';
    $multisite_options['desc'] = 'Settings for your multisite.';
    $multisite_options['id'] = 'multisite_options';

    $multisite_options[] = array(
        'title' => 'Show language switcher',
        'desc'  => 'If you using multisite for different languages',
        'type'  => 'checkbox',
        'id'    => 'use_language_switcher',
    );

    $multisite_options[] = array(
        'title' => 'Default language',
        'desc'  => 'Write down the WordPress locale you want to use, separated with comma. <br /> More info on <a href="https://make.wordpress.org/polyglots/teams/" target="_blank">Locales</a>',
        'type'  => 'text',
        'id'    => 'default_lang_language_switcher',
    );

    $multisite_options[] = array(
        'title' => 'Other languages',
        'desc'  => 'Write down the WordPress locale you want to use, separated with comma. <br /> More info on <a href="https://make.wordpress.org/polyglots/teams/" target="_blank">Locales</a>',
        'type'  => 'text',
        'id'    => 'lang_language_switcher',
    );

    grahlie_add_framework_page('Multisite Options', $multisite_options);
}

/**
 * Output the language switcher
 */
function grahlie_show_language_switcher()
{
    $grahlie_values = get_option('grahlie_framework_values');
    $output         = '';

    if (array_key_exists('use_language_switcher', $grahlie_values) && $grahlie_values['use_language_switcher'] != '') {
        $output = '<div id="language_switcher" class="dropdown_navigation">';
        $output .= '<a id="language_switcher_picker"><i class="material-icons">language</i></a>';

        $output .= '<div id="language_swithcer_values" class="dropdown_container">';
        $values = explode(',', $grahlie_values['lang_language_switcher']);
        foreach ($values as $key => $value) {
            $trimmed_value = trim($value);
            $display_value = trim(explode('_', $value)[0]);
            $href_value    = trim(explode('_', $value)[0]);

            if ($trimmed_value == $grahlie_values['default_lang_language_switcher']) {
                $output .= '<a href="/" class="language_' . $display_value . '">' . $display_value . '</a>';
            } else {
                $output .= '<a href="/' . $href_value . '" class="language_' . $display_value . '">' . $display_value . '</a>';
            }
        }
        $output .= '</div>';

        $output .= '</div>';
    }
    return $output;
}
