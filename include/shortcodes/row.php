<?php
/**
 * Rad shortcode
 *
 * @package grahlie
 */
function grahlie_row_shortcode( $atts, $content = null ) {
    extract( shortcode_atts( array(
            'klass' => ''
            , 'id' => ''
            , 'hojd' => ''
            , 'bredd' => ''
    ), $atts ) );

    if ( $klass != '' ) {
        $class = $klass;
    }


    if ( $id != '' ) {
        $id = 'id="' . $id . '"';
    }

    if ( $klass != '' ) {
        $class = $klass;
    }

    if ( $content != null) {
        $content_output = do_shortcode($content);
    }

    if( $hojd != '' ) {
        $style .= ' min-height: ' . $hojd . 'px;';
    }
    
    $output = '<div ' . $id . 'class="row ' . $class . '" ' . $style . '>' . $content_output . '</div>';

    return $output;    
}

add_shortcode( 'grahlieRad', 'grahlie_row_shortcode' );
add_shortcode( 'grahlieRad2', 'grahlie_row_shortcode' );
add_shortcode( 'grahlieRad3', 'grahlie_row_shortcode' );