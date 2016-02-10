<?php
/**
 * The template for displaying archive pages.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package grahlie
 */

get_header(); ?>

<section id="content" class="post-content">
	<main id="main" class="site-main" role="main">

		<?php
		if ( have_posts() ) { ?>

			<header class="page-header">
				<?php
					the_archive_title( '<h1 class="page-title">', '</h1>' );
					the_archive_description( '<div class="taxonomy-description">', '</div>' );
				?>
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
<?php
get_sidebar();
get_footer();
