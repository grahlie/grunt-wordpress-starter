<?php
/**
 * Template part for displaying page content in page.php.
 *
 * @package grahlie
 */

?>

<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<?php the_content(); ?>
</div>
