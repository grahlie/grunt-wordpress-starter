<?php
/**
 * Core functions for other files in the framework
 */

// Fixa första funktionen med standard array med options
function grahlie_create_framework_page(){
	$pages = get_pages();

	$framework_init = array(
		0 => array(
			'title' => 'Theme Options',
			'desc'	=> 'Your standard settings for your site.',
			'id'	=> 'theme_options',
			0 => array(
				'title' 	=> 'Use logotype',
				'desc'		=> 'If you want to use logotype in header check this box.',
				'type'		=> 'checkbox',
				'id'			=> 'use_logotype'
			),
			1 => array(
				'title' 	=> 'Logotype',
				'desc'  	=> 'Upload your logotype here.',
				'type'  	=> 'file',
				'id'    	=> 'logotype_file',
				'val'			=> 'Upload image'
			),
			2 => array(
				'title' 	=> 'Google Analytics',
				'desc'		=> 'Your Google analytics code.',
				'type'		=> 'text',
				'id'			=> 'google_analytics'
			)
		),
		1 => array(
			'title' => 'Frontpage Options',
			'desc'  => 'Settings for your frontpage.',
			'id'	=> 'test_page',
			0 => array(
				'title' 	=> 'Showcase pages on firstpage',
				'desc'		=> 'Check this box if you want pages to show up on frontpage.',
				'type'		=> 'checkbox',
				'id'			=> 'use_pages'
			),
			1 => array (
				'title'		=> 'How many pages',
				'desc'		=> 'Choose how many pages you want on frontpage.',
				'type' 		=> 'radio',
				'id' 			=> 'use_pages_count',
				'options' => array (
					'option1' => 'one',
					'option2' => 'two',
					'option3' => 'three',
					'option4' => 'four'
				)
			)
		)
	);

	return $framework_init;
}

/**
 * Creates different input types for page-settings
 */
function grahlie_create_input($item){
	$grahlie_values = get_option('grahlie_framework_values');
	$name = 'grahlie_framework_values['.$item['id'].']';

	// Text input
	if($item['type']=='text'){
		if(isset($grahlie_values[$item['id']])) $val = 'value="'.$grahlie_values[$item['id']].'"';
		echo '<input type="text" id="'.$item['id'].'" name="'.$name.'" '.$val.'/>';
	}

	// Textarea
	if($item['type']=='textarea'){
		$val = '';
		if(isset($grahlie_values[$item['id']])) $val = $grahlie_values[$item['id']];
		echo '<textarea id="'.$item['id'].'" name="'.$name.'">'. stripslashes($val).'</textarea>';
	}

	// Checkbox
	if($item['type']=='checkbox'){
		$val = '';
		// Check another function for these 3 options
		if(array_key_exists('val', $item) && $item['val'] == 'on') $val = ' checked="yes"';
		if(array_key_exists($item['id'], $grahlie_values) && $grahlie_values[$item['id']] == 'on') $val = ' checked="checked"';
		if(array_key_exists($item['id'], $grahlie_values) && $grahlie_values[$item['id']] != 'on') $val = '';

		echo '<input type="hidden" name="'.$name.'" value="off" />';
		echo '<input type="checkbox" id="'. $item['id'] .'" name="'.$name.'" value="on"'. $val .' /> ';
	}

	// Radio
	if($item['type']=='radio' && array_key_exists('options', $item)){
		$i = 1;
		foreach($item['options'] as $key => $value){
			echo '<br>'.$grahlie_values[$item['id']];
			echo '<br>' .$key . '<br>';
			// if( array_key_exists($item['id'], $grahlie_values) ) {
				if($key == $grahlie_values[$item['id']]) $val = ' checked="checked"';
			// } else {
			// 	if(array_key_exists('val', $item) && $item['val'] == $key) $val = ' checked="checked"';
			// }

 			echo '<label for="'. $item['id'] .'_'. $i .'"><input type="radio" id="' . $item['id'] .'_'. $i .'" name="' . $name . '" value="' . $key . '" '. $val .'>' . __($value, 'grahlie') .'</label>';
 			$i++;
		}
		// if($item['val'] === '' || empty($item['val'])) $item['val'] = 1;

		// // maybe do this better later on restricted to 4 now
		// for ($i=1; $i <= 4 ; $i++) { 
		// 	echo '<label for="'. $i .'"><input type="radio" id="' . $i .'" name="' . $name . '" value="' . $i . '">' . $i .'</label>';
		// }
	}

	// Select
	if($item['type']=='select' && array_key_exists('options', $item)){
		print_r($item['options']);
		echo '<select id="'.$item['id'].'" name="'.$name.'">';
		foreach ($item['options'] as $key => $value) {
			$val = '';
			if(isset($item['id']) && $grahlie_values[$item['id']] == $key){
				$val = 'selected="selected"';
			}
			echo '<option value="'.$key.'" '.$val.'>'.__($value, 'grahlie').'</option>';
		}
		echo '</select>';
	}

	// File input
	if($item['type']=='file'){
		$wp_upload_dir 	= wp_upload_dir();

		// Preview uploaded image
		if(isset($grahlie_values[$item['id']])&&$grahlie_values[$item['id']]!=''){
			$type = substr($grahlie_values[$item['id']], strrpos($grahlie_values[$item['id']], '.') +1);

			if($type == 'jpg' || $type == 'png' || $type == 'jpeg' || $type == 'gif') :
				$image = '<img class="upload-img" src="'.$grahlie_values[$item['id']].'" />';
			else :
				$image = $grahlie_values[$item['id']];
			endif;
		} ?>
		<div id="upload_<?php echo $item['id']; ?>_preview"><?php echo $image; ?></div>

		<input type="file" id="upload_<?php echo $item['id']; ?>" name="fileupload"  style="display: none;"/>
		<input id="upload_<?php echo $item['id']; ?>_button" type="button" class="grahlie-button-primary" value="<?php _e($item['val'], 'grahlie') ?>" />
		<input id="delete_<?php echo $item['id']; ?>_button" type="button" class="grahlie-button" value="<?php _e('Remove', 'grahlie') ?>" <?php if(!isset($grahlie_values[$item['id']])&&$grahlie_values[$item['id']]==''){?> style="display: none;" <?php } ?> />
		
		<script type='text/javascript'>
			jQuery(document).ready(function($){
				$('#grahlie-framework #upload_<?php echo $item['id']; ?>_button').click(function(){
					$('#upload_<?php echo $item['id']; ?>').trigger('click');
					var button = $(this);
					var buttonVal = button.val();

					$('#upload_<?php echo $item['id']; ?>').change(function(event){
						var file = event.target.files[0];

						var data = new FormData();
						data.append('uploadedfile', file);
						data.append('action', 'grahlie_upload_file');
						data.append('id', '<?php echo $item['id']; ?>');

						$(button).val('Uploading file');
						$.ajax({
							url: '<?php echo site_url(); ?>/wp-admin/admin-ajax.php', 
							type: 'POST', 
							data: data,
							cache: false,
							processData: false, 
							contentType: false,
							dataType: 'json',

							success: function(data){
								var name = file['name'];
								var type = name.split('.').pop();

								$(button).val(buttonVal);

								$('#grahlie-messages #message p').html(data.message);
								$('#grahlie-messages').css('display', 'block');

								if(type && type == 'jpg' || type == 'png' || type == 'jpeg' || type == 'gif') {
									$('#upload_<?php echo $item['id']; ?>_preview').html('<img class="upload-img" src="<?php echo $wp_upload_dir['url']; ?>/' + name + '" alt="' + name + '" />');
								} else {
									$('#upload_<?php echo $item['id']; ?>_preview').text('<?php echo $wp_upload_dir['url']; ?>/' + name);
								}
								$('#delete_<?php echo $item['id']; ?>_button').css('display', 'inline-block');
							},
							error: function(data){
								$('#grahlie-messages #message p').html(data.message);
								$('#grahlie-messages').css('display', 'block');
							}
						});
					});
				});

				$('#grahlie-framework #delete_<?php echo $item['id']; ?>_button').click(function(){
					var button = $(this);
					var buttonVal = button.val();
					
					$(button).val('Removing file');
					$.ajax({
						url: '<?php echo site_url(); ?>/wp-admin/admin-ajax.php',
						type: 'POST',
						data: {action: 'grahlie_remove_file', id: '<?php echo $item['id']; ?>'},
						dataType: 'json',

						success: function(data){
							$(button).val(buttonVal);
							$('#grahlie-messages #message p').html(data.message);
							$('#grahlie-messages').css('display', 'block');
							$('#delete_<?php echo $item['id']; ?>_button').css('display', 'none');
							$('#upload_<?php echo $item['id']; ?>_preview').html('');
						}
					});
					return false;
				});
			});
		</script>
	<?php }
}

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
