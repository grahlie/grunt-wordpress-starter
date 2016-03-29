<?php

// This should not be a single file
// Make more grahlie like

/**
 * Contains functions used on frontend of the theme
 * Author: grahlie
 */

/**
 * Filters wp_title to print a neat <title> tag based on what is being viewed.
 */
function grahlie_pretty_title( $title, $sep ) {
    if ( is_feed() ) {
        return $title;
    }

    global $page, $paged;

    // Add the blog name
    $title .= get_bloginfo( 'name', 'display' );

    // Add the blog description for the home/front page.
    $site_description = get_bloginfo( 'description', 'display' );
    if ( $site_description && ( is_home() || is_front_page() ) ) {
        $title .= " $sep $site_description";
    }

    // Add a page number if necessary:
    if ( ( $paged >= 2 || $page >= 2 ) && ! is_404() ) {
        $title .= " $sep " . sprintf( __( 'Page %s', 'grahlie' ), max( $paged, $page ) );
    }

    return $title;
}
add_filter( 'wp_title', 'grahlie_pretty_title', 10, 2 );

/**
 * Display navigation to next/previous post when applicable.
 */
if ( ! function_exists( 'grahlie_post_nav' ) ) :
function grahlie_post_nav() {
    $previous = ( is_attachment() ) ? get_post( get_post()->post_parent ) : get_adjacent_post( false, '', true );
    $next     = get_adjacent_post( false, '', false );

    if ( ! $next && ! $previous ) {
        return;
    }
    ?>
    <nav class="navigation post-navigation" role="navigation">
        <div class="nav-links">
            <?php
                // Lägg till font awesome ikoner för pilarna istället
                previous_post_link( '<div class="nav-previous">%link</div>', _x( '<span class="meta-nav">&larr;</span>&nbsp;%title', 'Previous post link', 'grahlie' ) );
                next_post_link(     '<div class="nav-next">%link</div>',     _x( '%title&nbsp;<span class="meta-nav">&rarr;</span>', 'Next post link',     'grahlie' ) );
            ?>
        </div>
    </nav>
    <?php
}
endif;



/**
 * Custom template tags for this theme.
 *
 * Eventually, some of the functionality here could be replaced by core features.
 *
 * @package grahlie
 */

if ( ! function_exists( 'grahlie_posted_on' ) ) :
/**
 * Prints HTML with meta information for the current post-date/time and author.
 */
function grahlie_posted_on() {
	$time_string = '<time class="entry-date published updated" datetime="%1$s">%2$s</time>';

	if ( get_the_time( 'U' ) !== get_the_modified_time( 'U' ) ) {
		$time_string = '<time class="entry-date published" datetime="%3$s">%4$s</time>';
	}

	$time_string = sprintf( $time_string,
		esc_attr( get_the_date( 'c' ) ),
		esc_html( get_the_date( 'H:i - j M, Y' ) ),
		esc_attr( get_the_modified_date( 'c' ) ),
		esc_html( get_the_modified_date( 'H:i - j M, Y' ) )
	);

	$posted_on = sprintf(
		esc_html_x( 'Posted on %s', 'post date', 'grahlie' ),
		$time_string
	);

	$byline = sprintf(
		esc_html_x( 'by %s', 'post author', 'grahlie' ),
		'<span class="author vcard"><a class="url fn n" href="' . esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ) . '">' . esc_html( get_the_author() ) . '</a></span>'
	);

	echo '<span class="posted-on">' . $posted_on . '</span><span class="byline"> ' . $byline . '</span>'; // WPCS: XSS OK.

}
endif;

if ( ! function_exists( 'grahlie_entry_footer' ) ) :
/**
 * Prints HTML with meta information for the categories, tags and comments.
 */
function grahlie_entry_footer() {
	// Hide category and tag text for pages.
	if ( 'post' === get_post_type() ) {
		/* translators: used between list items, there is a space after the comma */
		$categories_list = get_the_category_list( esc_html__( ', ', 'grahlie' ) );
		if ( $categories_list && grahlie_categorized_blog() ) {
			printf( '<span class="cat-links">' . esc_html__( 'Posted in %1$s ', 'grahlie' ) . '</span>', $categories_list ); // WPCS: XSS OK.
		}

		/* translators: used between list items, there is a space after the comma */
		$tags_list = get_the_tag_list( '', esc_html__( ', ', 'grahlie' ) );
		if ( $tags_list ) {
			printf( '<span class="tags-links">' . esc_html__( 'Tagged %1$s', 'grahlie' ) . '</span>', $tags_list ); // WPCS: XSS OK.
		}
	}

	if ( ! is_single() && ! post_password_required() && ( comments_open() || get_comments_number() ) ) {
		echo '<span class="comments-link">';
		comments_popup_link( esc_html__( 'Leave a comment', 'grahlie' ), esc_html__( '1 Comment', 'grahlie' ), esc_html__( '% Comments', 'grahlie' ) );
		echo '</span>';
	}

	edit_post_link(
		sprintf(
			esc_html__( 'Edit %s', 'grahlie' ),
			the_title( '', '', false )
		),
		'<span class="edit-link">',
		'</span>'
	);
}
endif;

/**
 * Returns true if a blog has more than 1 category.
 *
 * @return bool
 */
function grahlie_categorized_blog() {
	if ( false === ( $all_the_cool_cats = get_transient( 'grahlie_categories' ) ) ) {
		// Create an array of all the categories that are attached to posts.
		$all_the_cool_cats = get_categories( array(
			'fields'     => 'ids',
			'hide_empty' => 1,
			// We only need to know if there is more than one category.
			'number'     => 2,
		) );

		// Count the number of categories that are attached to the posts.
		$all_the_cool_cats = count( $all_the_cool_cats );

		set_transient( 'grahlie_categories', $all_the_cool_cats );
	}

	if ( $all_the_cool_cats > 1 ) {
		return true;
	} else {
		return false;
	}
}

/**
 * Flush out the transients used in grahlie_categorized_blog.
 */
function grahlie_category_transient_flusher() {
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}
	// Like, beat it. Dig?
	delete_transient( 'grahlie_categories' );
}
add_action( 'edit_category', 'grahlie_category_transient_flusher' );
add_action( 'save_post',     'grahlie_category_transient_flusher' );