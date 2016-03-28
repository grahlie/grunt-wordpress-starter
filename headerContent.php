<?php
/**
 * The headerContent file, containing the head of the pages
 * @package grahlie
 */

if ( get_header_image() ) { ?>

    <div class="header-image" style="background-image: url('<?php header_image(); ?>');">
        <div class="site-branding">
            <?php echo grahlie_use_logotype(); ?>
        </div>

        <nav id="site-navigation" class="main-navigation" role="navigation">
            <input type="checkbox" id="nav-trigger">
            <label for="nav-trigger" id="navopen"></label>

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

            <?php wp_nav_menu( array( 'theme_location' => 'primary', 'menu_id' => 'primary-menu', 'menu_class' => 'navigation', 'container' => '' ) ); ?>
        </nav>
    </div>

<?php } ?>

<!-- slider here later on -->
