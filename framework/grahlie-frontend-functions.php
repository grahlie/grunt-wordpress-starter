<?php
/**
 * Contains functions used on frontend of the theme
 * Author: grahlie
 */

/**
 * Filters wp_title to print a neat <title> tag based on what is being viewed.
 */
function grahlie_pretty_title( $title, $sep ) {
	if ( is_feed() ) {
		return $title;
	}

	global $page, $paged;

	// Add the blog name
	$title .= get_bloginfo( 'name', 'display' );

	// Add the blog description for the home/front page.
	$site_description = get_bloginfo( 'description', 'display' );
	if ( $site_description && ( is_home() || is_front_page() ) ) {
		$title .= " $sep $site_description";
	}

	// Add a page number if necessary:
	if ( ( $paged >= 2 || $page >= 2 ) && ! is_404() ) {
		$title .= " $sep " . sprintf( __( 'Page %s', 'grahlie' ), max( $paged, $page ) );
	}

	return $title;
}
add_filter( 'wp_title', 'grahlie_pretty_title', 10, 2 );

/**
 * Display navigation to next/previous post when applicable.
 */
if ( ! function_exists( 'grahlie_post_nav' ) ) :
function grahlie_post_nav() {
	$previous = ( is_attachment() ) ? get_post( get_post()->post_parent ) : get_adjacent_post( false, '', true );
	$next     = get_adjacent_post( false, '', false );

	if ( ! $next && ! $previous ) {
		return;
	}
	?>
	<nav class="navigation post-navigation" role="navigation">
		<div class="nav-links">
			<?php
				// Lägg till font awesome ikoner för pilarna istället
				previous_post_link( '<div class="nav-previous">%link</div>', _x( '<span class="meta-nav">&larr;</span>&nbsp;%title', 'Previous post link', 'grahlie' ) );
				next_post_link(     '<div class="nav-next">%link</div>',     _x( '%title&nbsp;<span class="meta-nav">&rarr;</span>', 'Next post link',     'grahlie' ) );
			?>
		</div>
	</nav>
	<?php
}
endif;

/**
 * Print out featured pages on firtpage
 FIXA TILL DEN HÄR SÅ DEN ÄR MER ALLMÄN
 */
if(!function_exists('grahlie_featured_page')):
function grahlie_featured_page($page){
	foreach ($page as $name) {
		$name 		= get_page_by_path($name);
		$name_id 	= $name->ID;
		$name 		= get_the_title($name_id);
		$permalink 	= get_permalink($name_id);
		$content 	= get_page($name_id);
		$image 		= wp_get_attachment_image_src( get_post_thumbnail_id($name_id), array( 2000,2000 ), false);

		$output = '
			<a href="'.$permalink.'">
				<article style="background-image: url('.$image[0].');">
					<h3>'.$name.'</h3>
				</article>
			</a>
		';
		echo $output;
	}
}
endif;