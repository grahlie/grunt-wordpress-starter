<?php
/**
 * The template for displaying all pages.
 *
 * @package grahlie
 */

get_header(); ?>

<section id="content" class="page-content">
	<main id="main" class="site-main" role="main">

		<?php while (have_posts()) {
			
			the_post();

			get_template_part( 'template/content', 'page' );

		} ?>

	</main>
</section>

<?php get_footer(); ?>
