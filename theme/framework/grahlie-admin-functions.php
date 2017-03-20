<?php
/**
 * Core functions for the framework
 *
 * Check if theme is activated, then redirect to settings page
 * Add framework pages
 * Create output for framework pages, creating "subfields" in framework
 * Save settings
 * Reset settings
 * Upload file
 * Remove file
 * Fetch wordpress pages
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

/**
 * Add a Page to the Framework
 */
function grahlie_add_framework_page( $title, $data ) {
    if( !is_array($data) ) return false;
    
    // Get current Framework pages
    $grahlie_options   = get_option('grahlie_framework_options');
    $grahlie_framework = array();

    if( is_array($grahlie_options['grahlie_framework']) ) $grahlie_framework = $grahlie_options['grahlie_framework'];
    
    // Add new page
    $grahlie_framework[$title] = $data;
    
    // Save
    $grahlie_options['grahlie_framework'] = $grahlie_framework;
    update_option('grahlie_framework_options', $grahlie_options);
}

/** 
 * Function for creating output on framework page
 */
function grahlie_create_output($item){
	$output = '<div class="content-settings clearfix '. $item['id'] .'"><div class="info"><h3>' . __($item['title'], 'grahlie') . '</h3>';

	if(isset($item['desc'])) {
		$output .= '<p class="desc">' . __($item['desc'], 'grahlie') . '</p>';
	}

	$output .= '</div><div class="input">';
	$output .= grahlie_create_input($item, null);
	$output .= '</div></div>';

	// Creating fields for subfields
	if($item['sync'] && is_array($item['sync'])) {
		foreach ($item['sync'] as $sync_item) {
			$output .= '<div class="content-settings clearfix '. $sync_item['id'] .'"><div class="info"><h3>' . __($sync_item['title'], 'grahlie') . '</h3>';

			if(isset($sync_item['desc'])) {
				$output .= '<p class="desc">' . __($sync_item['desc'], 'grahlie') . '</p>';
			}
			
			$output .= '</div><div class="input">';
            $output .= grahlie_create_input($sync_item, $item);
            $output .= '</div></div>';
        } 
	}

	return $output;
}

/** 
 * Save button function
 */
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

/**
 * Reset button function 
 */
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


/**
 * Upload button function
 */
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

/**
 * Remove button function
 */
function grahlie_remove_file(){
	$response['error']   = false;
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

/**
 * Function for fetching pages from wordpress
 */
function grahlie_get_pages() {
	$response['error']   = false;
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
