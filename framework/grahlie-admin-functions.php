<?php
/**
 * Core functions for other files in the framework
 */

/**
 * Checking if the theme has been activated
 */
function grahlie_theme_activated() {
    global $pagenow;

    if(is_admin() && isset($_GET['activated']) && $pagenow == 'themes.php') {
        return true;
    }

    return false;
}

function grahlie_framework_save() {
	$response['error'] = false;
	$response['message'] = '';
	
	if(!isset($_POST['grahlie_noncename']) || !wp_verify_nonce($_REQUEST['grahlie_noncename'], 'grahlie_framework_options')) :
		$response['error'] = true;
		$response['message'] = __('You do not have permission to update this page', 'grahlie');
		echo json_encode($response);
		die;
	endif;

	$grahlie_values = get_option('grahlie_framework_values');
	foreach ($_POST['grahlie_framework_values'] as $id => $value) {
		$grahlie_values[$id] = $value;
	}

	update_option('grahlie_framework_values', $grahlie_values);
	$response['message'] = __( 'Settings saved', 'grahlie' );

    echo json_encode($response);
	die;
}
add_action( 'wp_ajax_grahlie_framework_save', 'grahlie_framework_save' );

function grahlie_framework_reset() {
	$response['error'] = false;
	$response['message'] = '';

	if(!isset($_POST['nonce']) || !wp_verify_nonce($_REQUEST['nonce'], 'grahlie_framework_options')) :
		$response['error'] = true;
		$response['message'] = __('You do not have permission to update this page', 'grahlie');
		echo json_encode($response);
		die;
	endif;

	update_option('grahlie_framework_values', array());
	$response['message'] = __('Settings deleted', 'grahlie');

	echo json_encode($response);
	die;
}
add_action('wp_ajax_grahlie_framework_reset', 'grahlie_framework_reset');


// Upload file function
function grahlie_upload_file() {
	$response['error'] = false;
	$response['message'] = '';

	$wp_upload_dir 	= wp_upload_dir();
	$uploadfile 	= $wp_upload_dir['path'] .'/'. basename($_FILES['uploadedfile']['name']);

	if(move_uploaded_file($_FILES['uploadedfile']['tmp_name'], $uploadfile)) :
		$grahlie_values = get_option('grahlie_framework_values');
		$grahlie_values[$_POST['id']] = $wp_upload_dir['url'] .'/'. basename($_FILES['uploadedfile']['name']);
		update_option('grahlie_framework_values', $grahlie_values);
		$response['message'] = __('Your file have been uploaded', 'grahlie');
	else :
		$response['error'] = true;
		$response['message'] = __('You do not have permission to update this page', 'grahlie');
	endif;
		
	echo json_encode($response);
	die;
	
}
add_action('wp_ajax_grahlie_upload_file', 'grahlie_upload_file');

// Remove file function
function grahlie_remove_file(){
	$response['error'] = false;
	$response['message'] = '';

	$grahlie_values = get_option('grahlie_framework_values');

	if(isset($grahlie_values[$_POST['id']])):
		unset($grahlie_values[$_POST['id']]);
		update_option('grahlie_framework_values', $grahlie_values);
		$response['message'] = 'Your file have been succesfully removed';
	endif;
	
	echo json_encode($response);
	die;
}
add_action('wp_ajax_grahlie_remove_file', 'grahlie_remove_file');

// Get pages function
function grahlie_get_pages() {
	$response['error'] = false;
	$response['message'] = '';

	$pages = get_pages();

	foreach ($pages as $key => $page) {
		$response['name'][$key] = $page->post_title;
		$response['id'][$key] 	= $page->ID;
	}

	echo json_encode($response);
	die;
}
add_action('wp_ajax_grahlie_get_pages', 'grahlie_get_pages');
