<?php

function ct_video_cb( $atts, $content ) {

	$default = array(
		'url' => '',
	);

	$default = apply_filters( 'ctf_pb_video_element_atts', $default );

	$atts = shortcode_atts( $default, $atts );



	$output = '<div class="embed-responsive embed-responsive-16by9">';

	if (isset($atts['url']) && !empty($atts['url'])) {
		$output .= wp_oembed_get($atts['url']);
	}

	$output .= '</div>';

	$output = apply_filters( 'ctf_pb_video_element_output', $output, $atts, $content );
	return $output;
}
add_shortcode( 'ct_video','ct_video_cb' );

if (class_exists('CTPB_Element')) {

	$video_map = array(
		'title' => 'Video',
		'subtitle' => 'text Element',
		'code' => 'ct_video',
		'icon' => 'fa fa-info',
		'options' => array(
			array(
				'id' => 'url',
				'label'    => __( 'URL', 'mytheme' ),
				'subtitle'    => __( 'Lorem ipsum dolor sit amet', 'mytheme' ),
				'type'     => 'url',
				'default' => '',
			)
		)
	);

	$video_map = apply_filters( 'ctf_pb_video_map', $video_map );

	CTPB_Element::add($video_map);
}