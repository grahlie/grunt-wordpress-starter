<?php
/**
 * Grahlie include init, setup files required for theme
 *
 * Loop for require all theme files
 * Remove different useless stuff from wordpress core
 * Emojis
 * RSS
 * wlwmanifest
 * feed
 * pingback header
 *
 * @package grahlie
 */
$incdir = get_template_directory() . '/include/';
grahlie_require_files($incdir);

function grahlie_require_files($incdir) {
    $files = scandir( $incdir );

    foreach ($files as $value) {

        if($value != '.' && $value != '..' && $value != 'include_init.php') {
            $path = $incdir . $value . '/';

            if( is_dir($path) ) {

                $folder = scandir( $path ); 

                foreach( $folder as $file ) {
            
                    $file = pathinfo($file);
                    if( $file['extension'] === 'php' ) {
                        require $path . $file['basename'];
                    }
                }

            } else {
                $path = pathinfo($path);
                if( $path['extension'] === 'php' ) {
                    require $path . $path['basename'];
                }
            }

        } else {
            continue;
        }
    }
}

/**
 *
 * Remove Emojis
 */
function remove_emojicons_tinymce( $plugins ) {
  if ( is_array( $plugins ) ) {
    return array_diff( $plugins, array( 'wpemoji' ) );
  } else {
    return array();
  }
}

/**
 * Remove pingback
 */
function remove_x_pingback( $headers ) {
    unset( $headers['X-Pingback'] );
    return $headers;
}

/**
 * Remove version on static files
 */
function remove_staticfiles_version( $src ) {
    if( strpos( $src, '?ver=' ) ) {
        $src = remove_query_arg( 'ver', $src);
    }

    return $src;
}

/**
 * Remove empty html tags function
 */
function remove_empty_tags($string = null){
    $pattern = '/<[^\/>]*>([\s]?)*<\/[^>]*>/';

    $string = preg_replace($pattern, '', $string);

    return $string;
}

/*
 * Initiate all remove functions above
 */
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

    // Remove actions
    remove_action( 'admin_print_styles', 'print_emoji_styles' );
    remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
    remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
    remove_action( 'wp_print_styles', 'print_emoji_styles' );

    // Remove filters
    remove_filter( 'wp_mail', 'wp_staticize_emoji_for_email' );
    remove_filter( 'the_content_feed', 'wp_staticize_emoji' );
    remove_filter( 'comment_text_rss', 'wp_staticize_emoji' );
    remove_filter( 'the_content', 'wpautop' );

    // Run disable functions
    add_filter( 'tiny_mce_plugins', 'remove_emojicons_tinymce' );
    add_filter( 'wp_headers', 'remove_x_pingback' );
    add_filter( 'style_loader_src', 'remove_staticfiles_version', 1000 );
    add_filter( 'script_loader_src', 'remove_staticfiles_version', 1000 );
    add_filter( 'the_content', 'wpautop' , 99);
    add_filter( 'the_content', 'shortcode_unautop',100 );
}
add_action( 'init', 'remove_meta' );


?>
