<?php
/*
 * Remove stuff from WordPress core
 * 
 * Pingback
 * Version on staticfiles
 * Empty HTML tags
 */

/**
 * Remove pingback
 */
function remove_x_pingback($headers)
{
    unset($headers['X-Pingback']);
    return $headers;
}

/**
 * Remove version on static files
 */
function remove_staticfiles_version($src)
{
    if (strpos($src, '?ver=')) {
        $src = remove_query_arg('ver', $src);
    }

    return $src;
}

/*
 * Initiate all remove functions above
 */
function remove_meta() {
    // Clean up <head>
    remove_action('wp_head', 'rsd_link');
    remove_action('wp_head', 'wlwmanifest_link');
    remove_action('wp_head', 'wp_shortlink_wp_head');
    remove_action('wp_head', 'wp_generator');
    remove_action('wp_head', 'start_post_rel_link');
    remove_action('wp_head', 'index_rel_link');
    remove_action('wp_head', 'adjacent_posts_rel_link_wp_head');
    remove_action('wp_head', 'feed_links', 2);
    remove_action('wp_head', 'feed_links_extra', 3);

    // Feed
    remove_action('do_feed_rdf', 'do_feed_rdf', 10, 1);
    remove_action('do_feed_rss', 'do_feed_rss', 10, 1);
    remove_action('do_feed_rss2', 'do_feed_rss2', 10, 1);
    remove_action('do_feed_atom', 'do_feed_atom', 10, 1); 

    // Remove filters
    remove_filter( 'the_content', 'wpautop');

    // Run disable functions
    add_filter( 'wp_headers', 'remove_x_pingback');
    add_filter( 'style_loader_src', 'remove_staticfiles_version', 1000);
    add_filter( 'script_loader_src', 'remove_staticfiles_version', 1000);
    add_filter( 'the_content', 'wpautop' , 99);
    add_filter( 'the_content', 'shortcode_unautop',100);
}
add_action( 'init', 'remove_meta' );

?>