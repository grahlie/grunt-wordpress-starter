<?php
$incdir = get_template_directory() . '/include/';

/**
 * Grahlie framework setup files
 */
require $incdir . 'settings/theme-settings.php';
require $incdir . 'settings/frontpage-settings.php';

/**
 * Grahlie shortcodes
 */
require $incdir . 'shortcodes/shortcodes_init.php';

/**
 * Theme options
 */
require $incdir . 'theme/template-tags.php';
require $incdir . 'theme/customizer.php';
// require $incdir . 'theme/jetpack.php';

/**
 *
 * Remove different useless stuff from wordpress core
 * Emojis
 * RSS
 * wlwmanifest
 * feed
 * pingback header
 *
 * @package grahlie
 */
function remove_emojicons_tinymce( $plugins ) {
  if ( is_array( $plugins ) ) {
    return array_diff( $plugins, array( 'wpemoji' ) );
  } else {
    return array();
  }
}

function remove_x_pingback( $headers ) {
    unset( $headers['X-Pingback'] );
    return $headers;
}

function remove_meta() {
    // Clean up <head>
    remove_action( 'wp_head', 'rsd_link' );
    remove_action( 'wp_head', 'wlwmanifest_link' );
    remove_action( 'wp_head', 'wp_shortlink_wp_head' );
    remove_action( 'wp_head', 'wp_generator' );
    remove_action( 'wp_head', 'start_post_rel_link' );
    remove_action( 'wp_head', 'index_rel_link' );
    remove_action( 'wp_head', 'adjacent_posts_rel_link_wp_head' );
    remove_action( 'wp_head', 'feed_links', 2 );
    remove_action( 'wp_head', 'feed_links_extra', 3 );
    remove_action( 'do_feed_rdf', 'do_feed_rdf', 10, 1 );
    remove_action( 'do_feed_rss', 'do_feed_rss', 10, 1 );
    remove_action( 'do_feed_rss2', 'do_feed_rss2', 10, 1 );
    remove_action( 'do_feed_atom', 'do_feed_atom', 10, 1 ); 

    // disable emoji
    remove_action( 'admin_print_styles', 'print_emoji_styles' );
    remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
    remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
    remove_action( 'wp_print_styles', 'print_emoji_styles' );
    remove_filter( 'wp_mail', 'wp_staticize_emoji_for_email' );
    remove_filter( 'the_content_feed', 'wp_staticize_emoji' );
    remove_filter( 'comment_text_rss', 'wp_staticize_emoji' );

    // Run disable functions
    add_filter( 'tiny_mce_plugins', 'remove_emojicons_tinymce' );
    add_filter( 'wp_headers', 'remove_x_pingback' );
}
add_action( 'init', 'remove_meta' );

/**
 * Remove version on static files
 */
function remove_staticfiles_version( $src ) {
    if( strpos( $src, '?ver=' ) ) {
        $src = remove_query_arg( 'ver', $src);
    }

    return $src;
}
add_filter( 'style_loader_src', 'remove_staticfiles_version', 1000 );
add_filter( 'script_loader_src', 'remove_staticfiles_version', 1000 );


?>
