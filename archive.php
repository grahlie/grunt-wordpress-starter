<?php
/**
 * The template for displaying archive pages.
 *
 * @package grahlie
 */

get_header(); ?>

<section id="content" class="post-content">
	<main id="main" class="site-main" role="main">

		<?php if ( have_posts() ) { ?>

			<header class="page-header">
				<?php the_archive_title( '<h1 class="page-title">', '</h1>' ); ?>
			</header>

			<?php

			while ( have_posts() ) {
				the_post();

				get_template_part( 'template/content', get_post_format() );

			}

			the_posts_navigation();

		} else {

			get_template_part( 'template/content', 'none' );

		} ?>

	</main>
</section>

<?php get_sidebar(); get_footer();
