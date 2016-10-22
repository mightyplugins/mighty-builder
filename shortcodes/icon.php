<?php

function ct_icon_cb( $atts ) {
	$atts = shortcode_atts( array(
		'icon' => 'values'
	), $atts );

	return ;
}
add_shortcode( 'ct_icon','ct_icon_cb' );