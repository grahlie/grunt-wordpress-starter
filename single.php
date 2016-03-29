<?php
/**
 * The template for displaying all single posts.
 *
 * @package grahlie
 */

get_header(); ?>

<div id="content" class="post-content">
	<main id="main" class="site-main" role="main">

	<?php while ( have_posts() ) {
		the_post();

		get_template_part( 'template/content', get_post_format() );

		the_post_navigation();

		if ( comments_open() || get_comments_number() ) {
			comments_template();
		}

	} ?>

	</main>
</div>

<?php get_sidebar(); ?>
<?php get_footer(); ?>
