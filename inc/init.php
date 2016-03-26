<?php
$incdir = get_template_directory() . '/inc/';

/**
 * Grahlie functions files
 */
require $incdir . 'options/theme-settings.php';
require $incdir . 'options/frontpage-settings.php';

/**
 * Shortcodes for theme
 */
require $incdir . 'shortcodes/shortcodes_init.php';

/**
 * Wordpress core theme options
 */
require $incdir . 'theme/custom-header.php';
require $incdir . 'theme/template-tags.php';
require $incdir . 'theme/customizer.php';
// require $incdir . 'theme/jetpack.php';


?>
