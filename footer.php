<?php
/**
 * Footer page
 * @package grahlie
 */

?>
</div>

<footer id="pageFooter" class="site-footer" role="contentinfo">
<<<<<<< HEAD
	<div class="footer-content row">
=======
    <div class="footer-content row">
>>>>>>> e3a98e4e072177902c463f5f7620d9a4342f6572
        <div class="size8">
            <?php dynamic_sidebar('footer'); ?>
        </div>
        <p class="grahlieLogo size4">
            &copy; <?php bloginfo( 'name' ); ?> -
<<<<<<< HEAD
            <?php printf( esc_html__( 'Skapad av %1$s', 'grahlie' ), '<a href="http://grahlie.se" rel="designer"></a>'); ?>
=======
            <?php printf( esc_html__( 'Developed by %1$s', 'grahlie' ), '<a href="http://grahlie.se" rel="designer"></a>'); ?>
>>>>>>> e3a98e4e072177902c463f5f7620d9a4342f6572
        </p>
    </div>
</footer>

<?php wp_footer(); ?>

<?php echo grahlie_use_analytics(); ?>

</body>
</html>
