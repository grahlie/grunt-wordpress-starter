<?php
/**
 * grahlie functions and definitions.
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package grahlie
 */

if ( ! function_exists( 'grahlie_setup' ) ) :
function grahlie_setup() {
	load_theme_textdomain( 'grahlie', get_template_directory() . '/languages' );

	/*
	 * Enable support for Post Thumbnails on posts and pages.
	 */
	add_theme_support( 'post-thumbnails' );

	// This theme uses wp_nav_menu() in one location.
	register_nav_menus( array(
		'primary' => esc_html__( 'Primary', 'grahlie' ),
	) );

	/*
	 * Switch default core markup for search form, comment form, and comments to output valid HTML5.
	 */
	add_theme_support( 'html5', array(
		'search-form',
		'comment-form',
		'comment-list',
		'gallery',
		'caption',
	) );

	/*
	 * Enable support for Post Formats.
	 */
	add_theme_support( 'post-formats', array(
		'aside',
		'image',
		'video',
		'quote',
		'link',
	) );

	// Set up the WordPress core custom background feature.
	add_theme_support( 'custom-background', apply_filters( 'grahlie_custom_background_args', array(
		'default-color' => 'ffffff',
		'default-image' => '',
	) ) );
}
endif;
add_action( 'after_setup_theme', 'grahlie_setup' );

/**
 * Remove different useless stuff from wordpress core
 * Emojis
 * RSS
 * wlwmanifest
 * feed
 * pingback header
 *
 */
function disable_emojicons_tinymce( $plugins ) {
  if ( is_array( $plugins ) ) {
    return array_diff( $plugins, array( 'wpemoji' ) );
  } else {
    return array();
  }
}

function disable_x_pingback( $headers ) {
	unset( $headers['X-Pingback'] );
	return $headers;
}

function disable_meta() {
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
	add_filter( 'tiny_mce_plugins', 'disable_emojicons_tinymce' );
	add_filter( 'wp_headers', 'disable_x_pingback' );
}
add_action( 'init', 'disable_meta' );

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

/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 */
function grahlie_content_width() {
	$GLOBALS['content_width'] = apply_filters( 'grahlie_content_width', 1200 );
}
add_action( 'after_setup_theme', 'grahlie_content_width', 0 );

/**
 * Register widget area.
 */
function grahlie_widgets_init() {
	register_sidebar( array(
		'name'          => esc_html__( 'Sidebar', 'grahlie' ),
		'id'            => 'sidebar-1',
		'description'   => '',
		'before_widget' => '<section id="%1$s" class="widget %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h2 class="widget-title">',
		'after_title'   => '</h2>',
	) );
}
add_action( 'widgets_init', 'grahlie_widgets_init' );

/**
 * Enqueue scripts and styles.
 */
function grahlie_scripts() {
	wp_enqueue_style( 'grahlie-style', get_stylesheet_uri() );

	//wp_enqueue_script('jquery', 'https://cdnjs.cloudflare.com/ajax/libs/jquery/3.0.0-alpha1/jquery.js');
	wp_enqueue_script('grahlie-script', get_template_directory_uri() . '/js/scripts.min.js', array(), true);

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'grahlie_scripts' );

/**
 * Require other files
 */
require get_template_directory() . '/inc/shortcodes.php';
require get_template_directory() . '/inc/custom-header.php';
require get_template_directory() . '/inc/template-tags.php';
require get_template_directory() . '/inc/extras.php';
require get_template_directory() . '/inc/customizer.php';
require get_template_directory() . '/inc/jetpack.php';
