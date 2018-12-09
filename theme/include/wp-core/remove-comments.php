<?php
/*
 * Disable support for comments and trackbacks in post types.
 */
function disable_comments_post_types_support()
{
    $post_types = get_post_types();
    foreach ($post_types as $post_type) {
        if (post_type_supports($post_type, 'comments')) {
            remove_post_type_support($post_type, 'comments');
            remove_post_type_support($post_type, 'trackbacks');
        }
    }
}

/*
 * Redirect any user trying to access comments page.
 */
function disable_comments_admin_menu_redirect()
{
    global $pagenow;
    if ('edit-comments.php' === $pagenow) {
        wp_redirect(admin_url());
        exit;
    }
}

/*
 * Close comments on the front-end. 
 */
function disable_comments_status()
{
    return false;
}

/*
 * Hide existing comments.
 */
function disable_comments_hide_existing_comments($comments)
{
    $comments = array();
    return $comments;
}

/*
 * Remove comments page in menu.
 */
function remove_from_admin_menu()
{
    remove_menu_page('edit-comments.php');
    remove_meta_box('dashboard_recent_comments', 'dashboard', 'normal');
}

function remove_comments() 
{
    remove_action('admin_bar_menu', 'wp_admin_bar_comments_menu', 60);

    add_action('admin_init', 'disable_comments_post_types_support');
    add_action('admin_init', 'disable_comments_admin_menu_redirect');

    add_action('admin_menu', 'remove_from_admin_menu');

    add_filter('comments_open', 'disable_comments_status', 20, 2);
    add_filter('pings_open', 'disable_comments_status', 20, 2);
    add_filter('comments_array', 'disable_comments_hide_existing_comments', 10, 2);
}
add_action('init', 'remove_comments');