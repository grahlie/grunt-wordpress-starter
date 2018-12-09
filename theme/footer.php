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
    </div>
</footer>

<?php wp_footer(); ?>

<?php echo grahlie_use_analytics(); ?>

</body>
</html>
