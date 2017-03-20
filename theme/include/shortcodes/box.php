<?php
/**
 * Box shortcode
 *
 * @package grahlie
 */

function grahlie_box_shortcode( $atts, $content = null ){

    extract( shortcode_atts ( array(
        'klass'      => '',
        'storlek'    => '',
        'size'       => '',
        'id'         => '',
        'bakgrund'   => '',
        'background' => '',
        'sidor'      => '',
        'pages'      => '',
        'titel'      => '',
        'title'      => '',
        'bild'       => '',
        'image'      => ''
    ), $atts ));

    if( $klass != '' ) {
        $klass = ' ' . $klass;
        $class = $klass;
    }

    if( $storlek != '' ) {
        $storlek = ' size' . $storlek;
        $size    = $storlek;
    }

    if( $bakgrund != '' ) {
        $bakgrund   = 'style="background-image: url(' . $bakgrund . ')"';
        $background = $bakgrund;
    }

    if( $sidor != '' ) {
        $output = grahlie_use_pages($class, $title, $image);
    }

    if( $content != null ){
        $content_output = do_shortcode($content);
    }


    if( $sidor == '') {
        $output = '
            <div class="grahlieBox' . $class . '' . $size . '"' . $background . '>
                ' . $content_output . '
            </div>
        ';
    }

    return $output;
}
add_shortcode( 'grahlieBox', 'grahlie_box_shortcode' );
add_shortcode( 'grahlieBox2', 'grahlie_box_shortcode' );
add_shortcode( 'grahlieBox3', 'grahlie_box_shortcode' );
