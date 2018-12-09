<?php

/** 
 *
 * Create Theme option page for admin settings page
 * And Functions for outputting these settings
 */

// Create page
add_action('admin_init', 'grahlie_blog_settings');
function grahlie_blog_settings()
{
    $blog_options['title'] = 'Blog Options';
    $blog_options['desc'] = 'Settings for your blog page.';
    $blog_options['id'] = 'blog_options';

    $blog_options[] = array(
        'title' => 'Excerpt length',
        'desc'  => 'How long should "Excerpt" length be? used in the list of all blog posts.',
        'type'  => 'text',
        'id'    => 'blog_excerpt_length',
    );

    $blog_options[] = array(
        'title' => 'Show date on blog post',
        'desc'  => 'If you want to show date on blog post check this box.',
        'type'  => 'checkbox',
        'id'    => 'blog_show_date',
    );
    
    $blog_options[] = array(
        'title' => 'Show author on blog post',
        'desc'  => 'If you want to show author on blog post check this box.',
        'type'  => 'checkbox',
        'id'    => 'blog_show_author',
    );

    grahlie_add_framework_page('Blog Options', $blog_options);
}

function grahlie_blog_excerpt_length()
{
    $grahlie_values = get_option('grahlie_framework_values');
    $output = 55;

    if (array_key_exists('blog_excerpt_length', $grahlie_values) && $grahlie_values['blog_excerpt_length'] != '') {
        $output = intval($grahlie_values['blog_excerpt_length']);
    }
    
    return $output;
}

function grahlie_blog_show_date()
{
    $grahlie_values = get_option('grahlie_framework_values');

    if (array_key_exists('blog_show_date', $grahlie_values) && $grahlie_values['blog_show_date'] == 'on') {
        return true;
    } else {
        return false;
    }
}

function grahlie_blog_show_author()
{
    $grahlie_values = get_option('grahlie_framework_values');

    if (array_key_exists('blog_show_author', $grahlie_values) && $grahlie_values['blog_show_author'] == 'on') {
        return true;
    } else {
        return false;
    }
}