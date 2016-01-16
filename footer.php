<?php
/**
 * Footer page
 *
 * @package grahlie
 */

?>

	<footer id="colophon" class="site-footer" role="contentinfo">
		<div class="site-info">
            <p class="grahlieLogo">
                &copy; <?php bloginfo( 'name' ); ?> -
                <?php printf( esc_html__( 'Developed by %1$s', 'grahlie' ), '<a href="http://grahlie.se" rel="designer"><img src="' . get_template_directory_uri() . '/images/logotype_black.png" alt="grahlie" /></a>'); ?>
            </p>
        </div><!-- .site-info -->
	</footer><!-- #colophon -->
</div><!-- #page -->

<?php wp_footer(); ?>

</body>
</html>
