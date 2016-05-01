<?php
/**
 * Kolumn shortcode
 *
 * @package grahlie
 */
function grahlie_column_shortcode($atts, $content = null) {
    extract( shortcode_atts( array(
            'klass' => ''
            , 'id' => ''
            ,'storlek' => '6'
            ,'ordning' => ''
    ), $atts ) );
    
    if ( $klass != '' ) {
        $class = $klass;
    }

    if ( $storlek != '' ) {
        $size = 'size' . $storlek;
    }

    if( $ordning != '' ) {
        $order = 'order' . $ordning;
    }

    if ( $id != '' ) {
        $id = 'id="'.$id.'"';
    }

    if ( $content != null) {
        $content_output = do_shortcode($content);
    }

    $output = '<div ' . $id .' class="column ' . $size . ' ' . $order . ' ' . $class . '">' . $content_output . '</div>';

    return $output;
}

add_shortcode( 'grahlieKolumn', 'grahlie_column_shortcode' );
add_shortcode( 'grahlieKolumn2', 'grahlie_column_shortcode' );
add_shortcode( 'grahlieKolumn3', 'grahlie_column_shortcode' );