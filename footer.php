<?php
/**
 * Footer page
 * @package grahlie
 */

?>
</div>

<footer id="pageFooter" class="site-footer" role="contentinfo">
    <div class="footer-content row">
        <div class="size8">
            <?php dynamic_sidebar('footer'); ?>
        </div>
        <p class="grahlieLogo size4">
            &copy; <?php bloginfo( 'name' ); ?> -
            <?php printf( esc_html__( 'Developed by %1$s', 'grahlie' ), '<a href="http://grahlie.se" rel="designer"></a>'); ?>
        </p>
    </div>
</footer>

<?php wp_footer(); ?>

<?php echo grahlie_use_analytics(); ?>

</body>
</html>
