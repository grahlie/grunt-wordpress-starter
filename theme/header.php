<?php
/**
 * The header file, containing the <head> parts
 * @package grahlie
 */

?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1">
<?php echo grahlie_use_favicon(); ?>
<title>
<?php
    echo grahlie_pretty_title( '-' );
?>
</title>

<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>

<header id="pageHeader" class="site-header" role="banner">
	<?php require_once( 'headerContent.php' ); ?>
</header>

<div id="pageContent" class="site">
