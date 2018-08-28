<?php
/**
 * Add excerpt for pages, used for showcase pages on frontpage
 */
function grahlie_page_excerpt() {
    add_post_type_support( 'page', 'excerpt' );
}
add_action( 'init', 'grahlie_page_excerpt' );

/**
 * Adds a pretty "Read more" link to custom post excerpts.
 */
function grahlie_excerpt_link( $output ) {
    $output = '...';
    $output .= '<div class="excerpt-btn"><a href="' . get_the_permalink() . '" class="btn btn-primary">' . __('Read more', 'grahlie') . '</a></div>';

    return $output;
}
add_filter( 'excerpt_more', 'grahlie_excerpt_link' );

/**
 * Filter the except length.
 */
function grahlie_excerpt_length( $length ) {
    return 55;
}
add_filter( 'excerpt_length', 'grahlie_excerpt_length', 999 );

function grahlie_content_excerpt_filter( $content ) {
    return substr( $content, 0, 145 );
}
?>