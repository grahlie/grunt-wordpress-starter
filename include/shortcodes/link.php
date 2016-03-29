<?php
/**
 * Link shortcode
 *
 * @package grahlie
 */

function grahlie_link_shortcode( $atts, $content = null ){

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
            <a href="' . $href . '" ' . $target . ' class="grahlieLink' . $class . '' . $size . '"' . $background . '>' . $content_output . '</a>';
    }

    return $output;
}
add_shortcode( 'grahlieLink', 'grahlie_link_shortcode' );
add_shortcode( 'grahlieLank', 'grahlie_link_shortcode' );
