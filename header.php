<?php
/**
 * The header for our theme.
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package grahlie
 */

?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="shortcut icon" href="<?php echo get_template_directory_uri(); ?>/images/favicon.ico" />
<title>
<?php
	global $page, $paged;
	wp_title( '|', true, 'right');
	bloginfo( 'name' );
?>
</title>

<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>

<header id="pageHeader" class="site-header" role="banner">
	<?php require_once( 'headerContent.php' ); ?>
</header>

<div id="pageContent" class="site">
