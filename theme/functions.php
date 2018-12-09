<?php
/**
 * Grahlie functions and definitions.
 *
 * @package grahlie
 */

if ( ! function_exists( 'grahlie_setup' ) ) :
function grahlie_setup() {
    load_theme_textdomain( 'grahlie', get_template_directory() . '/languages' );

    /**
     * Add image size for theme
     */
    add_image_size('featured-image', 1200, 500);
    add_image_size('startsida-bild', 700, 450);

    function grahlie_custom_image_size( $sizes ) {
        return array_merge( $sizes, array(
            'startsida-bild' => __( 'Bild på startsida', 'grahlie' ),
            'featured-image' => __( 'Featured bild', 'grahlie')
        ));
    }
    add_filter('image_size_names_choose', 'grahlie_custom_image_size');


    /**
     * Initiate widgets
     */	
    function grahlie_create_sidebar() {
        register_sidebar( array(
            'name' 			=> __('Fotinnehåll', 'grahlie'),
            'id'     		=> 'footer',
            'before_widget' => '<div class="Footer-widget">',
            'after_widget'  => '</div>',
            'before_title'  => '',
            'after_title'   => '',
        ));
    }
    add_filter('widgets_init', 'grahlie_create_sidebar');

    /**
     * Register widget area.
     */
    function grahlie_widgets_init()
    {
        register_sidebar(array(
            'name'          => esc_html__('Sidebar', 'grahlie'),
            'id'            => 'sidebar-1',
            'description'   => '',
            'before_widget' => '<section id="%1$s" class="widget %2$s">',
            'after_widget'  => '</section>',
            'before_title'  => '<h2 class="widget-title">',
            'after_title'   => '</h2>',
        ));
    }
    add_action('widgets_init', 'grahlie_widgets_init');

    register_nav_menus( array(
        'primary' => esc_html__( 'Primary', 'grahlie' ),
    ) );

    /*
    * Enable support for Post Thumbnails on posts and pages.
    */
    add_theme_support('post-thumbnails');

    /*
    * Switch default core markup for search form, comment form, and comments to output valid HTML5.
    */
    add_theme_support( 'html5', array(
        'search-form',
        'gallery',
        'caption',
    ));

    /*
    * Enable support for Post Formats.
    */
    add_theme_support( 'post-formats', array(
        'aside',
        'image',
        'video',
        'quote',
        'link',
    ));
}
endif;
add_action( 'after_setup_theme', 'grahlie_setup' );

/**
 * Enqueue scripts and styles.
 */
function grahlie_scripts() {
    wp_enqueue_style( 'grahlie-style', get_stylesheet_uri() );
    wp_enqueue_style('MDI', 'https://fonts.googleapis.com/icon?family=Material+Icons');

    wp_enqueue_script('jquery', 'https://cdnjs.cloudflare.com/ajax/libs/jquery/3.0.0-alpha1/jquery.js');
    wp_enqueue_script('grahlie-script', get_template_directory_uri() . '/js/scripts.min.js', array('jquery'), true);
}
add_action( 'wp_enqueue_scripts', 'grahlie_scripts' );

/**
 * Include Grahlie special edition files here
 */
$tempdir = get_template_directory();
require $tempdir . '/framework/init.php';
require $tempdir . '/include/include_init.php';
