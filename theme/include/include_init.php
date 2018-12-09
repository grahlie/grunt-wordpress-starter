<?php
/**
 * Grahlie include init, setup files required for theme
 *
 * Loop for require all theme files
 * Remove different useless stuff from wordpress core
 * Emojis
 * RSS
 * wlwmanifest
 * feed
 * pingback header
 *
 * @package grahlie
 */
$incdir = get_template_directory() . '/include/';
grahlie_require_files($incdir);

function grahlie_require_files($incdir) {
    $files = scandir( $incdir );

    foreach ($files as $value) {

        if($value != '.' && $value != '..' && $value != 'include_init.php') {
            $path = $incdir . $value . '/';

            if( is_dir($path) ) {

                $folder = scandir( $path ); 

                foreach( $folder as $file ) {
            
                    $file = pathinfo($file);
                    if( $file['extension'] === 'php' ) {
                        require $path . $file['basename'];
                    }
                }

            } else {
                $path = pathinfo($path);
                if( $path['extension'] === 'php' ) {
                    require $path . $path['basename'];
                }
            }

        } else {
            continue;
        }
    }
}

?>