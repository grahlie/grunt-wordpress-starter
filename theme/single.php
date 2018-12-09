<?php
/**
 * The template for displaying all single posts.
 *
 * @package grahlie
 */

get_header(); ?>

<section id="content">
	<main id="main" role="main">

	<?php while ( have_posts() ) {
		the_post();

		get_template_part( 'template/content', get_post_format() );

		grahlie_post_nav();

	} ?>

	</main>
</section>

<?php get_sidebar(); ?>
<?php get_footer(); ?>
