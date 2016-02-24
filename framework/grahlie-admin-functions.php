<?php
/**
 * Core functions for other files in the framework
 */

// Fixa första funktionen med standard array med options
function grahlie_create_framework_page(){
	$framework_init = array(
		0 => array(
			'title' 		=> 'Theme Options',
			'desc'			=> 'Your standard settings for your site.',
			'id'			=> 'theme_options',
			0 => array(
				'title' 	=> 'Use logotype',
				'desc'		=> 'If you want to use logotype in header check this box.',
				'type'		=> 'checkbox',
				'id'		=> 'use_logotype'
			),
			1 => array(
				'title' 	=> 'Logotype',
				'desc'  	=> 'Upload your logotype here.',
				'type'  	=> 'file',
				'id'    	=> 'logotype_file',
				'val'		=> 'Upload image'
			),
			2 => array(
				'title' 	=> 'Google Analytics',
				'desc'		=> 'Your Google analytics code.',
				'type'		=> 'text',
				'id'		=> 'google_analytics'
			)
		),
		1 => array(
			'title' 		=> 'Frontpage Options',
			'desc'  		=> 'Settings for your frontpage.',
			'id'			=> 'frontpage_options',
			0 => array(
				'title' 	=> 'Showcase pages on firstpage',
				'desc'		=> 'Check this box if you want pages to show up on frontpage.',
				'type'		=> 'checkbox',
				'id'	 	=> 'use_pages'
			),
			1 => array (
				'title'		=> 'How many pages',
				'desc'		=> 'Choose how many pages you want on frontpage.',
				'type' 		=> 'radio_select',
				'id' 		=> 'use_pages_count',
				'options'   => array (
					'1' 	=> 'one',
					'2' 	=> 'two',
					'3' 	=> 'three',
					'4' 	=> 'four'
				)
			),
			2 => array (
				'title'		=> 'select',
				'desc'		=> 'Choose how many pages you want on frontpage.',
				'type' 		=> 'select',
				'id' 		=> 'test',
				'options'   => array (
					'1' 	=> 'one',
					'2' 	=> 'two',
					'3' 	=> 'three',
					'4' 	=> 'four'
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
	$name 			= 'grahlie_framework_values['.$item['id'].']';
	$pages 			= get_pages();

	// Text input
	if( $item['type'] == 'text' ){
		if( isset( $grahlie_values[$item['id']] ) ) {
			$val = 'value="' . $grahlie_values[$item['id']] . '"';
		}

		echo '<input type="text" id="' . $item['id'] . '" name="' . $name . '" ' . $val . '/>';
	}

	// Textarea
	if( $item['type'] == 'textarea' ){
		$val = '';

		if( isset( $grahlie_values[$item['id']] ) ) {
			$val = $grahlie_values[$item['id']];
		}

		echo '<textarea id="' . $item['id'] . '" name="' . $name . '">' . stripslashes($val) . '</textarea>';
	}

	// Checkbox
	if( $item['type'] == 'checkbox' ){
		$val = '';

		// Check another function for these 3 options
		if( array_key_exists($item['id'], $grahlie_values) && $grahlie_values[$item['id']] == 'on') {
			$val = ' checked="checked"';
		} else {
			$val = '';
		}

		echo '<input type="hidden" name="' . $name . '" value="off" />';
		echo '<input type="checkbox" id="' . $item['id'] . '" name="' . $name . '" value="on" ' . $val . ' /> ';
	}

	// Radio
	if( $item['type'] == 'radio' && array_key_exists( 'options', $item ) ){
		$i = 1;

		foreach($item['options'] as $key => $value){
			if( array_key_exists($item['id'], $grahlie_values) && $key == $grahlie_values[$item['id']] ) {
				$val = 'checked="checked"';
			} else {
				$val = '';
			}

 			echo '<label class="input_radio" for="'. $item['id'] .'_'. $i .'"><input type="radio" id="' . $item['id'] .'_'. $i .'" name="' . $name . '" value="' . $key . '" '. $val .'>' . __($value, 'grahlie') .'</label>';
 			$i++;
		}
	}

	/**
	 * Radio Select
	 * function for creating a select after radio
	*/
	if( $item['type'] == 'radio_select' && array_key_exists( 'options', $item ) ){
		$i = 1;
		$output = '<div class="input">';

		foreach($item['options'] as $key => $value){
			if( array_key_exists($item['id'], $grahlie_values) && $key == $grahlie_values[$item['id']] ) {
				$val = 'checked="checked"';
			} else {
				$val = '';
			}

 			$output .= '<label class="input_radio" for="'. $item['id'] .'_'. $i .'"><input type="radio" id="' . $item['id'] .'_'. $i .'" name="' . $name . '" value="' . $key . '" '. $val .'>' . __($value, 'grahlie') .'</label>';
 			$i++;
		}

		$output .= '</div>';

		// This doesnt work FIX
		$select_name = 'grahlie_framework_values[' . $item['id'] .'_select]';
		$output .= '<div id="radio_select"><div class="info"><h3>Which pages to show</h3><p class="desc">Select the pages you want to show up.</p></div><div class="input"></div></div>';

		foreach ( $pages as $key => $page ) {
			$option[$key] = $page->post_title;
		}

		// check if value then run js function
		?>
		<script type='text/javascript'>
			jQuery(document).ready(function($){
				$(".input_radio").click(function () {
					var val     = $(this).find("input:checked").val(),
						data 	= new FormData();

					for (var i = 0; i < val; i++) {
						var id 	 = "<?php echo $item['id']; ?>_select_",
							name = "grahlie_framework_values[<?php echo $item['id']; ?>_select_" + i + "]";

						if($("#" + id + i).length === 0) {
	                		$("#radio_select .input").append('<select id="' + id + i +'" name="' + name + '"></select>');
	                	}

	                	// check to remove also
	                }

					data.append("action", "grahlie_get_pages");
					$.ajax({
						url: "<?php echo site_url(); ?>/wp-admin/admin-ajax.php", 
						type: "POST", 
						data: data,
						cache: false,
						processData: false, 
						contentType: false,
						dataType: "json",

						success: function(data){
							// check this for a key
							data.list.forEach(function(page){

								for (var i = 0; i < val; i++) {
									console.log(i);
									$('#' + id + i).append('<option value="' + i + '">' + page + '</option>');
		            			}

							});
						},
						error: function(data){
							$("#grahlie-messages #message p").html('error');
							$("#grahlie-messages").css("display", "block");
						}
					});
				});
			});

			// js function for running from start
		</script>
		<?php

		return $output;
	}

	// Select
	if( $item['type'] == 'select' && array_key_exists( 'options', $item ) ){
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
		if( isset($grahlie_values[$item['id']]) && $grahlie_values[$item['id']] != '') {
			$type = substr($grahlie_values[$item['id']], strrpos($grahlie_values[$item['id']], '.') +1);

			if($type == 'jpg' || $type == 'png' || $type == 'jpeg' || $type == 'gif') {
				$image = '<img class="upload-img" src="'.$grahlie_values[$item['id']].'" />';
			} else {
				$image = $grahlie_values[$item['id']];
			}
		} else {
			$display = 'style="dispaly: none"';
		}
		?>

		<div id="upload_<?php echo $item['id']; ?>_preview"><?php echo $image; ?></div>

		<input type="file" id="upload_<?php echo $item['id']; ?>" name="fileupload"  style="display: none;"/>
		<input id="upload_<?php echo $item['id']; ?>_button" type="button" class="grahlie-button-primary" value="<?php _e($item['val'], 'grahlie') ?>" />
		<input id="delete_<?php echo $item['id']; ?>_button" type="button" class="grahlie-button" value="<?php _e('Remove', 'grahlie') ?>" <?php echo $display; ?> />
		
		<script type='text/javascript'>
			jQuery(document).ready(function($){
				$("#grahlie-framework #upload_<?php echo $item['id']; ?>_button").click(function(){
					$("#upload_<?php echo $item['id']; ?>").trigger("click");

					var button = $(this);
					var buttonVal = button.val();

					$("#upload_<?php echo $item['id']; ?>").change(function(event){
						var file = event.target.files[0];

						var data = new FormData();
						data.append("uploadedfile", file);
						data.append("action", "grahlie_upload_file");
						data.append("id", "<?php echo $item['id']; ?>");

						$(button).val("<?php _e('Uploading file', 'grahlie'); ?>");

						$.ajax({
							url: "<?php echo site_url(); ?>/wp-admin/admin-ajax.php", 
							type: "POST", 
							data: data,
							cache: false,
							processData: false, 
							contentType: false,
							dataType: "json",

							success: function(data){
								var name = file.name;
								var type = name.split('.').pop();

								$(button).val(buttonVal);

								$("#grahlie-messages #message p").html(data.message);
								$("#grahlie-messages").css("display", "block");

								if(type && type == 'jpg' || type == 'png' || type == 'jpeg' || type == 'gif') {
									$("#upload_<?php echo $item['id']; ?>_preview").html('<img class="upload-img" src="<?php echo $wp_upload_dir["url"]; ?>/' + name + '" alt="' + name + '" />');
								} else {
									$("#upload_<?php echo $item['id']; ?>_preview").text("<?php echo $wp_upload_dir['url']; ?>/" + name);
								}
								$("#delete_<?php echo $item['id']; ?>_button").css("display", "inline-block");
							},
							error: function(data){
								$("#grahlie-messages #message p").html(data.message);
								$("#grahlie-messages").css("display", "block");
							}
						});
					});
				});

				$("#grahlie-framework #delete_<?php echo $item['id']; ?>_button").click(function(){
					var button = $(this);
					var buttonVal = button.val();
					
					$(button).val("<?php _e('Removing file', 'grahlie'); ?>");
					$.ajax({
						url: "<?php echo site_url(); ?>/wp-admin/admin-ajax.php",
						type: "POST",
						data: {action: "grahlie_remove_file", id: "<?php echo $item['id']; ?>"},
						dataType: "json",

						success: function(data){
							$(button).val(buttonVal);
							$("#grahlie-messages #message p").html(data.message);
							$("#grahlie-messages").css("display", "block");
							$("#delete_<?php echo $item['id']; ?>_button").css("display", "none");
							$("#upload_<?php echo $item['id']; ?>_preview").html("");
						}
					});
					return false;
				});
			});
		</script>
	<?php }
}

function grahlie_add_input($item) {

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
