<?php

function ct_img_cb( $atts, $content ) {

	$default = array(
		'size' => '',
		'alt' => '',
		'src' => '',
	);

	$atts_default = apply_filters( 'ctf_pb_image_element_atts', $default );

	$atts = shortcode_atts( $atts_default, $atts );

	$style = '';

	// $style .= (isset($atts['color']) && !empty($atts['color'])) ? 'color:'.$atts['color'].';' : '';


	$output = '';

	ob_start();

	?>
	<img src="<?php echo esc_url( $atts['src'] ); ?>" alt="<?php echo esc_attr( $atts['alt'] ); ?>">
	
	<?php

	$output =  ob_get_clean();

	$output = apply_filters( 'ctf_pb_image_element_output', $output, $atts, $content );

	return $output;
}
add_shortcode( 'ct_img','ct_img_cb' );



if (class_exists('CTPB_Element')) {

	$image_map = array(
		'title' => 'Single Image',
		'subtitle' => 'Single Image Element',
		'code' => 'ct_img',
		'icon' => 'fa fa-font',
		'options' => array(
			array(
				'id' => 'src',
				'label'    => __( 'Background Color', 'mytheme' ),
				'subtitle'    => __( 'Lorem ipsum dolor sit amet', 'mytheme' ),
				'type'     => 'image',
				'default' => '',
			)
		)
	);

	$image_map = apply_filters( 'ctf_pb_text_map', $image_map );

	CTPB_Element::add($image_map);
}