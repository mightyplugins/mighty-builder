<?php

function mb_video_cb( $atts, $content ) {

	$default = array(
		'url' => '',
	);

	$default = apply_filters( 'mb_video_element_atts', $default );

	$atts = shortcode_atts( $default, $atts );



	$output = '<div class="embed-responsive embed-responsive-16by9">';

	if (isset($atts['url']) && !empty($atts['url'])) {
		$output .= wp_oembed_get($atts['url']);
	}

	$output .= '</div>';

	$output = apply_filters( 'mb_video_element_output', $output, $atts, $content );
	return $output;
}
add_shortcode( 'mb_video','mb_video_cb' );

if (class_exists('MB_Element')) {

	$video_map = array(
		'title' => 'Video',
		'subtitle' => 'text Element',
		'code' => 'mb_video',
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

	$video_map = apply_filters( 'mb_video_map', $video_map );

	MB_Element::add($video_map);
}