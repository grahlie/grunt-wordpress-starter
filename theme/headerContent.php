<?php
/**
 * The headerContent file, containing the head of the pages
 * @package grahlie
 */

if ( get_the_post_thumbnail($post->ID) ) { ?>
    
    <div class="header-image" style="background-image: url('<?php the_post_thumbnail_url('featured-image'); ?>');">
        <div class="site-branding">
            <?php echo grahlie_use_logotype(); ?>
        </div>

        <nav id="site-navigation" class="main-navigation" role="navigation">
            <input type="checkbox" id="nav-trigger">
            <label for="nav-trigger" id="navopen"></label>

            <?php echo grahlie_show_language_switcher(); ?>
            <?php wp_nav_menu( array( 'theme_location' => 'primary', 'menu_id' => 'primary-menu', 'menu_class' => 'navigation', 'container' => '' ) ); ?>
        </nav>
    </div>

<?php } else { ?>

    <div class="header-content">
        <div class="site-branding">
            <?php echo grahlie_use_logotype(); ?>
        </div>

        <nav id="site-navigation" class="main-navigation" role="navigation">
            <input type="checkbox" id="nav-trigger">
            <label for="nav-trigger" id="navopen"></label>

            <?php echo grahlie_show_language_switcher(); ?>
            <?php wp_nav_menu( array( 'theme_location' => 'primary', 'menu_id' => 'primary-menu', 'menu_class' => 'navigation', 'container' => '' ) ); ?>
        </nav>

        <?php echo grahlie_intro_header_text(); ?>
    </div>

<?php } ?>
