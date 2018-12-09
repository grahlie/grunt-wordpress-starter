<?php
/**
 * Template part for displaying posts.
 *
 * @package grahlie
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
    <header>
        <?php
            if ( is_single() ) {
                the_title( '<h1 class="entry-title">', '</h1>' );
            } else {
                the_title( '<h2 class="entry-title"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h2>' );
            }

        if ( 'post' === get_post_type() ) { ?>
            <div class="entry-meta">
                <?php grahlie_posted_on(); ?>
            </div>
        <?php } ?>

    </header>

    <div class="entry-content">
        <?php
            if( is_single() ) {
                the_content();
            } else {
                the_excerpt();
            }
        ?>
    </div>

    <footer class="entry-footer">
        <?php echo grahlie_entry_footer(); ?>
    </footer>
</article>
