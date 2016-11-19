<?php

function ct_space_cb( $atts, $content ) {

	$default = array(
		'size' => '25px',
	);

	$default = apply_filters( 'ctf_pb_space_element_atts', $default );

	$atts = shortcode_atts( $default, $atts );

	$style = '';

	$style .= (isset($atts['size']) && !empty($atts['size'])) ? 'height:'.$atts['size'].';' : '';




	ob_start();
	?>
	<div class="ct-space" style="<?php echo esc_attr($style); ?>"></div>
	<?php

	$output =  ob_get_clean();

	$output = apply_filters( 'ctf_pb_space_element_output', $output, $atts, $content );
	return $output;
}
add_shortcode( 'ct_space','ct_space_cb' );

if (class_exists('CTPB_Element')) {

	$space_map = array(
		'title' => 'Space',
		'subtitle' => 'text Element',
		'code' => 'ct_space',
		'icon' => 'fa fa-info',
		'options' => array(
			array(
				'id' => 'size',
				'label'    => __( 'Size', 'mytheme' ),
				'subtitle'    => __( 'Lorem ipsum dolor sit amet', 'mytheme' ),
				'type'     => 'dimension',
				'default' => '25px',
				'choices' => array( 'units' => array( 'px', 'em', 'rem' ) )
			)
		)
	);

	$space_map = apply_filters( 'ctf_pb_space_map', $space_map );

	CTPB_Element::add($space_map);
}