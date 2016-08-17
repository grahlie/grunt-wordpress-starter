<?php
/**
 * Button shortcode
 *
 * @package grahlie
 */

function grahlie_button_shortcode( $atts, $content = null ){

    extract( shortcode_atts ( array(
        'class'      => '',
        'klass'      => '',
        'storlek'    => '',
        'size'       => '',
        'id'         => '',
        'href'       => '',
        'adress'     => ''
    ), $atts ));

    if( $klass != '' ) {
        $klass = ' ' . $klass;
        $class = $klass;
    }

    if( $storlek != '' ) {
        $storlek = ' size' . $storlek;
        $size    = $storlek;
    }

    if( $adress != '' ) {
        $href = $adress;

        $parseUrl = parse_url($href);

        if(empty($parseUrl['scheme'])){
            $href = get_site_url() . '/' . $href;
        } else {
            $target = 'target="_blank"';
        }
    } 

    if( $content != null ){
        $content_output = do_shortcode($content);
    }


    if( $pages == '') {
        $output = '
            <a href="' . $href . '" ' . $target . ' class="btn ' . $class . '' . $size . '"' . $background . '>' . $content_output . '</a>';
    }

    return $output;
}
add_shortcode( 'grahlieButton', 'grahlie_button_shortcode' );
add_shortcode( 'grahlieKnapp', 'grahlie_button_shortcode' );
