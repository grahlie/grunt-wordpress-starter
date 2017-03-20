<?php
/**
 * The main template file.
 *
 * @package grahlie
 */

get_header(); ?>
<section id="content">
	<main id="main" role="main">
	<?php

	if (have_posts()) {

		while (have_posts()) {
			the_post();
			get_template_part( 'template/content', get_post_format() );
		}

		grahlie_post_nav();

	} else { 
		get_template_part( 'template/content', 'none' );

	}
	
	?>
	</main>
</section>
<?php 

if( is_home() ) { 
	get_sidebar(); 
}

get_footer();

?>
