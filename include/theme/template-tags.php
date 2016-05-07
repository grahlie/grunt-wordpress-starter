<?php

// This should not be a single file
// Make more grahlie like

/**
 * Filters wp_title to print a neat <title> tag based on what is being viewed.
 */
function grahlie_pretty_title( $sep ) {
    global $page, $post;

    // Add the blog name
    $blogname = get_bloginfo( 'name' );

    // Add the blog description for the home/front page.
    $site_description = get_bloginfo( 'description', 'display' );
    if ( $site_description && ( is_home() || is_front_page() ) ) {
        $title = "$blogname $sep $site_description";
    } elseif( is_single() ) {
        $title = "$post->post_title $sep $blogname";
    } elseif( is_page() && !is_front_page() ) {
        $title = "$post->post_title $sep $blogname";
    } else {
        $title = "$blogname";
    }

    return $title;
}
add_filter( 'wp_title', 'grahlie_pretty_title', 10, 2 );

/**
 * Display navigation to next/previous post when applicable.
 */
function grahlie_post_nav() {
    $previous = ( is_attachment() ) ? get_post( get_post()->post_parent ) : get_adjacent_post( false, '', true );
    $next     = get_adjacent_post( false, '', false );

    if ( !$next && !$previous ) {
        return;
    }
    ?>
    <nav class="post-navigation" role="navigation">
            <?php
                // Lägg till font awesome ikoner för pilarna istället
                if( is_single() ) {
                    previous_post_link( '<div class="nav-previous">%link</div>', _x( '< %title', 'Previous post link', 'grahlie' ) );
                    next_post_link(     '<div class="nav-next">%link</div>',     _x( '%title >', 'Next post link',     'grahlie' ) );
                } else {
                    $paginate_args = array(
                        'prev_text' => __('< Previous', 'grahlie'),
                        'next_text' => __('Next >', 'grahlie'),
                        'mid_size'  => 2,
                        'end_size'  => 2
                    );
                    echo paginate_links( $paginate_args ); 
                }
            ?>
    </nav>
    <?php
}



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

    if ( 'post' === get_post_type() ) {
        $categories_list = get_the_category_list( esc_html__( ', ', 'grahlie' ) );
        if ( $categories_list && grahlie_categorized_blog() ) {
            $category = sprintf( esc_html__( 'Posted in %1$s ', 'grahlie' ), $categories_list );
        }
    }

	echo '<span class="posted-on">' . $posted_on . '</span><span class="byline"> ' . $byline . '</span><span class="cat-links"> ' . $category . '</span>';

}
endif;

if ( ! function_exists( 'grahlie_entry_footer' ) ) :
/**
 * Prints HTML with meta information for the categories, tags and comments.
 */
function grahlie_entry_footer() {
    $output = '';

	if ( ! is_single() && ! post_password_required() && ( comments_open() || get_comments_number() ) ) {
		$output .= '<span class="comments-link">';
		comments_popup_link( esc_html__( 'Leave a comment', 'grahlie' ), esc_html__( '1 Comment', 'grahlie' ), esc_html__( '% Comments', 'grahlie' ) );
		$output .= '</span>';
	}

    if (current_user_can( 'manage_options' ) ) {
        $edit_link = get_edit_post_link();
        $output .= '<a href="' . $edit_link . '" class="btn btn-secondary edit-link">Edit ' . get_the_title() . '</a>';
    }

    return $output;
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
