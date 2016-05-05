<?php
/**
 * The main template file.
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * E.g., it puts together the home page when no home.php file exists.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package grahlie
 */

get_header(); ?>
<section id="content">
	<main id="main" role="main">

	<?php if (have_posts()) { ?>

		<?php while (have_posts()) { ?>

			<?php the_post(); ?>

			<?php get_template_part( 'template/content', get_post_format() ); ?>

		<?php } ?>

		<?php grahlie_post_nav(); ?>

	<?php } else { ?>

		<?php get_template_part( 'template/content', 'none' ); ?>

	<?php } ?>

	</main>
</section>
<?php 

if(is_home()) { 
	get_sidebar(); 
}

get_footer();

?>
