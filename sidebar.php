<?php
/**
 * The sidebar containing the main widget area.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package grahlie
 */

if ( ! is_active_sidebar( 'sidebar-1' ) ) {
	return;
}
?>

<aside id="secondary" class="widget-area" role="complementary">
    <form role="search" method="get" class="search-form" action="//localhost:3000/">
        <label>
            <input type="search" class="search-field" placeholder="Search …" value="" name="s" title="Search for:">
        </label>
        <input type="submit" class="search-submit" value="Search">
    </form>
	<?php dynamic_sidebar( 'sidebar-1' ); ?>
</aside>
