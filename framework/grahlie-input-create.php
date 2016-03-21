<?php
/**
 * Creates different input types for page-settings
 */
function grahlie_create_input($item){
    $grahlie_values   = get_option('grahlie_framework_values');
    $name             = 'grahlie_framework_values['.$item['id'].']';

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

        if( array_key_exists( 'sync', $item) && $item['sync'] != '' ) { ?>
            
            <script>
                jQuery(document).ready(function($){
                    if ( $("#<?php echo $item['id']; ?>").is(":checked") ) {
                        $(".<?php echo $item['sync']; ?>").show();
                    } else {
                        $(".<?php echo $item['sync']; ?>").hide();
                    }

                    $(".<?php echo $item['id']; ?> input").click(function () {
                        $(".<?php echo $item['sync']; ?>").toggle();
                        $(".<?php echo $item['sync']; ?> input").prop('checked', false);
                    });
                });
            </script>

        <?php }
    }

    // Radio
    if( $item['type'] == 'radio' && array_key_exists( 'options', $item ) ){
        $i = 1;

        echo '<input name="' . $name . '" type="hidden" value="0">';
        foreach($item['options'] as $key => $value){
            if( array_key_exists($item['id'], $grahlie_values) && $key == $grahlie_values[$item['id']] ) {
                $val = 'checked="checked"';
            } else {
                $val = '';
            }

            echo '<label class="input_radio" for="'. $item['id'] .'_'. $i .'"><input type="radio" id="' . $item['id'] .'_'. $i .'" name="' . $name . '" value="' . $key . '" '. $val .'>' . __($value, 'grahlie') .'</label>';
            $i++;
        }

        if( array_key_exists( 'sync', $item) && $item['sync'] != '' ) { ;
            
            if ( $grahlie_values[$item['id']]) { ?>
                <script>
                    jQuery(document).ready(function($){
                        $(".<?php echo $item['sync']; ?>").show();
                    });
                </script>
            <?php } else { ?>
                <script>
                    jQuery(document).ready(function($){
                        $(".<?php echo $item['sync']; ?>").hide();
                    });
                </script>
            <?php } ?>

            <script>
                jQuery(document).ready(function($){
                    $(".<?php echo $item['id']; ?> input").click(function () {
                        $(".<?php echo $item['sync']; ?>").show();
                    });
                });
            </script>

        <?php }
    }

    // Select
    if( $item['type'] == 'select' ){

        if( array_key_exists( 'options', $item ) ) {

            echo '<select id="'.$item['id'].'" name="'.$name.'">';
            foreach ($item['options'] as $key => $value) {
                $val = '';
                if(isset($item['id']) && $grahlie_values[$item['id']] == $key){
                    $val = 'selected="selected"';
                }
                echo '<option value="'.$key.'" '.$val.'>'.__($value, 'grahlie').'</option>';
            }
            echo '</select>';

        } else if( array_key_exists( 'sync', $item) && $item['sync'] != '' ) { 
            $sync = $grahlie_values[$item['sync']];

            for ($i=1; $i <= $sync; $i++) {
                if ($grahlie_values[$item['id']][$i] != '') {
                    echo '<select id="' . $item['id'] . '_' . $i .'" name="' . $name . '[' . $i . ']">';

                        $pages = get_pages(); 
                        foreach ( $pages as $page ) {

                            if($grahlie_values[$item['id']][$i] == $page->ID) {
                                echo '<option value="' . $page->ID . '" selected>' . $page->post_title . '</option>';
                            } else {
                                echo '<option value="' . $page->ID . '">' . $page->post_title . '</option>';
                            }
                        }

                    echo '</select>';
                }
            }

            ?>

            <script>
                jQuery(document).ready(function($){
                    $(".<?php echo $item['sync']; ?> input").click(function () {
                        var val = $(this).val(),
                            data    = new FormData();

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
                                for(var i = 1; i <= val; i++) {
                                    var parent = "<?php echo $item['id']; ?>",
                                        name   = "<?php echo $name; ?>",
                                        id     = "<?php echo $item['id']; ?>_" + i;

                                    if($("#" + id).length === 0) {
                                        $("." + parent + " .input").append('<select id="' + id + '" name="' + name + '[' + i + ']"></select>');
                                    }

                                    $("#" + id).append('<option value="">Select page</option>');

                                    for(var j = 0; j < data.id.length; j++) {
                                        $("#" + id).append('<option value="' + data.id[j] + '">' + data.name[j] + '</option>');
                                    }
                                }
                            },
                            error: function(data){
                                $("#grahlie-messages #message p").html('error');
                                $("#grahlie-messages").css("display", "block");
                            }
                        });
                    });

                });
            </script>

            <?php

        }
    }

    // File input
    if($item['type'] == 'file'){
        $wp_upload_dir  = wp_upload_dir();

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
