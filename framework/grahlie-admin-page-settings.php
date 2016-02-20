<?php
function grahlie_admin_page() {
	$grahlie_options = get_option('grahlie_framework_options'); ?>

	<?php if(isset($_GET['tab'])){
		$tab = $_GET[ 'tab' ];
	} else {
		$tab = 'theme_options';
	} ?>

<?php print_r(get_option('grahlie_framework_values')); ?>

	<div id="grahlie-messages">
		<?php if(isset($_GET['activated'])){ ?>
			<div class="grahlie-updated" id="active">
				<span class="message-icon"><i class="fa fa-heart"></i></span>
				<p class="message-text"><?php _e($grahlie_options['theme_name'] .' is activated', 'grahlie'); ?></p>
			</div>
		<?php } ?>
		<div class="grahlie-success" id="message">
			<span class="message-icon"><i class="fa fa-check"></i></span>
			<p class="message-text"></p>
		</div>
	</div>
	<div id="grahlie-framework">
		<form method="post" action="<?php echo site_url() .'/wp-admin/admin-ajax.php'; ?>" enctype="multipart/form-data">
			<header>
				<a class="author_logo" href="<?php echo $grahlie_options['theme_authorURI']; ?>" alt="<?php echo $grahlie_options['theme_author']; ?>" target="_blank">
					<?php $logo_svg = file_get_contents(GRAHLIE_URL . "/images/grahlie.svg"); ?>
					<?php echo $logo_svg; ?>
				</a>
				<h1 class="theme_logo">
					<?php echo $grahlie_options['theme_name']; ?>
				</h1>
				<span>v.<?php echo $grahlie_options['theme_version']; ?></span>
			</header>
			<div class="main">
				<div class="tabs">
					<ul>
						<?php foreach ($grahlie_options['grahlie_framework'] as $page) : ?>
							<li><a href="?page=grahlieframework&tab=<?php echo $page['id']; ?>" class="<?php echo $tab == $page['id'] ? 'active' : ''; ?>"><?php echo $page['title']; ?></a></li>
						<?php endforeach; ?>
					</ul>
				</div>
				<div class="content">
					<?php foreach ($grahlie_options['grahlie_framework'] as $page) : ?>
						<?php if($tab==$page['id']) : ?>
							<div id="<?php echo $page['id']; ?>">
								<h1><?php _e($page['title'], 'grahlie'); ?></h1>
								<p><?php if(isset($page['desc'])) _e($page['desc'], 'grahlie'); ?></p>
								<hr />
								<?php foreach ($page as $item) { ?>
									<?php if(is_array($item)) : ?>
										<div class="content-settings clearfix">
											<div class="info">
												<h3><?php _e($item['title'], 'grahlie'); ?></h3>
												<p class="desc"><?php if(isset($item['desc'])) _e($item['desc'], 'grahlie'); ?></p>
											</div>
											<div class="input">
												<?php echo grahlie_create_input($item); ?>
											</div>
										</div>
									<?php endif; ?>
								<?php } ?>
							</div>
						<?php endif; ?>
					<?php endforeach; ?>
				</div>
				<div class="footer">
					<input type="hidden" name="action" value="grahlie_framework_save" />
					<input type="hidden" name="grahlie_noncename" id="grahlie_noncename" value="<?php echo wp_create_nonce('grahlie_framework_options'); ?>" />
					<input type="button" value="<?php _e( 'Reset Options', 'grahlie' ); ?>" class="grahlie-button" id="reset-button" />
					<input type="submit" value="<?php _e( 'Save All Changes', 'grahlie' ); ?>" class="grahlie-button-primary right" id="save-button" />
				</div>
			</div>
		</form>
	</div>
<?php } 

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
