<?php

function ct_line_cb( $atts, $content ) {

	$default = array(
		'color' => '',
		'margin_top' => '20px',
		'margin_bottom' => '20px',
	);

	$default = apply_filters( 'ctf_pb_line_element_atts', $default );

	$atts = shortcode_atts( $default, $atts );

	$style = '';

	$style .= (isset($atts['color']) && !empty($atts['color'])) ? 'border-top-color:'.$atts['color'].';' : '';
	$style .= (isset($atts['margin_top']) && !empty($atts['margin_top'])) ? 'margin-top:'.$atts['margin_top'].';' : '';
	$style .= (isset($atts['margin_bottom']) && !empty($atts['margin_bottom'])) ? 'margin-bottom:'.$atts['margin_bottom'].';' : '';




	ob_start();
	?>
	<hr class="ct-line" style="<?php echo esc_attr($style); ?>">
	<?php

	$output =  ob_get_clean();

	$output = apply_filters( 'ctf_pb_line_element_output', $output, $atts, $content );
	return $output;
}
add_shortcode( 'ct_line','ct_line_cb' );

if (class_exists('CTPB_Element')) {

	$line_map = array(
		'title' => 'Line',
		'subtitle' => 'text Element',
		'code' => 'ct_line',
		'icon' => 'fa fa-info',
		'options' => array(
			array(
				'id' => 'color',
				'label'    => __( 'Color', 'mytheme' ),
				'subtitle'    => __( 'Lorem ipsum dolor sit amet', 'mytheme' ),
				'type'     => 'color_rgba',
				'default' => '',
			),
			array(
				'id' => 'margin_top',
				'label'    => __( 'Margin Top', 'mytheme' ),
				'subtitle'    => __( 'Lorem ipsum dolor sit amet', 'mytheme' ),
				'type'     => 'dimension',
				'default' => '20px',
				'choices' => array( 'units' => array( 'px', 'em', 'rem' ) )
			),
			array(
				'id' => 'margin_bottom',
				'label'    => __( 'Margin Bottom', 'mytheme' ),
				'subtitle'    => __( 'Lorem ipsum dolor sit amet', 'mytheme' ),
				'type'     => 'dimension',
				'default' => '20px',
				'choices' => array( 'units' => array( 'px', 'em', 'rem' ) )
			)
		)
	);

	$line_map = apply_filters( 'ctf_pb_line_map', $line_map );

	CTPB_Element::add($line_map);
}