<?php
/**
 * Shortcodes 
 */

/* 
 * a nice shortcode for implementing a slider
 * google map shortcode
 * shortcode extension for contact form
 *
 */

/**
 * Remove empty html tags function
 */
function remove_empty_tags($string = null){
    $pattern = '/<[^\/>]*>([\s]?)*<\/[^>]*>/';

    $string = preg_replace($pattern, '', $string);

    return $string;
}

/**
 * Loop for require all shortcode files
 */
$shortcode_files = scandir($incdir . 'shortcodes');

foreach ($shortcode_files as $value) {
    if($value === 'shortcodes_init.php') {
        continue;
    }

    $shortcode_path = pathinfo($incdir . 'shortcodes/' . $value);
    if($shortcode_path['extension'] === 'php') {
        require $incdir . '/shortcodes/' . $value;
    }

}

?>
