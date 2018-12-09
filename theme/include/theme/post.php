<?php
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
                // Add icons for arrow instead &%ICONS%&
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
 * Prints HTML with meta information for the current post-date/time and author.
 */
function grahlie_posted_on() {
    // Add icons for posted_on, byline, category instead &%ICONS%&
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

    $category = '';
    if ( 'post' === get_post_type() ) {
        $categories_list = get_the_category_list( esc_html__( ', ', 'grahlie' ) );
        if ( $categories_list && grahlie_categorized_blog() ) {
            $categoryContent = sprintf( esc_html__( 'Posted in %1$s ', 'grahlie' ), $categories_list );
            $category = '<span class="cat-links">' . $categoryContent . '</span>';
        }
    }

    echo '<span class="posted-on">' . $posted_on . '</span><span class="byline"> ' . $byline . '</span> ' . $category;
}

/**
 * Prints HTML with meta information for the categories, tags and comments.
 */
function grahlie_entry_footer() {
    $output = '';

    if (current_user_can( 'manage_options' ) ) {
        $edit_link = get_edit_post_link();
        $output .= '<a href="' . $edit_link . '" class="btn btn-secondary edit-link">Edit ' . get_the_title() . '</a>';
    }

    return $output;
}