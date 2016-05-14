<?php
/**
 * The template for displaying 404 pages (not found).
 *
 * @link https://codex.wordpress.org/Creating_an_Error_404_Page
 *
 * @package grahlie
 */

get_header(); ?>

<section id="content">
	<main id="main" role="main">

		<div class="error-404 not-found">
			<header class="page-header">
				<h1 class="page-title"><?php esc_html_e( 'Oops! That page can&rsquo;t be found.', 'grahlie' ); ?></h1>
			</header>

			<div class="error-content">
				<p><?php esc_html_e( 'It looks like nothing was found at this location. Maybe try one of the links below or a search?', 'grahlie' ); ?></p>
			</div>
		</div>

	</main>
</section>

<?php get_footer(); ?>
