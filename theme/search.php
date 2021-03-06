<?php
/**
 * The template for displaying search results pages.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#search-result
 *
 * @package grahlie
 */

get_header(); ?>

<section id="content">
	<main id="main" role="main">

	<?php
	if ( have_posts() ) { ?>

		<header class="page-header">
			<h1 class="page-title"><?php printf( esc_html__( 'Search Results for: %s', 'grahlie' ), '<span>' . get_search_query() . '</span>' ); ?></h1>
		</header>

		<?php
		while (have_posts()) {
			the_post();

			get_template_part( 'template/content', 'search' );

		}

		grahlie_post_nav();

	} else {

		get_template_part( 'template/content', 'none' );

	} ?>

	</main>
</section>

<?php get_sidebar(); ?>
<?php get_footer(); ?>
