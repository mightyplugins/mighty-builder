<?php

function ct_heading_cb( $atts, $content ) {

	$default = array(
		'padding_top' => '',
		'padding_bottom' => '',
		'padding_left' => '',
		'padding_right' => '',
		'color' => '',
		'tag' => 'h1',
		'haeding_font-family' => '',
		'haeding_font-weight' => '',
		'haeding_font-size' => '',
		'haeding_line-height' => '',
	);

	$atts_default = apply_filters( 'ctf_pb_heading_element_atts', $default );

	$atts = shortcode_atts( $atts_default, $atts );

	$style = '';

	$style .= (isset($atts['color']) && !empty($atts['color'])) ? 'color:'.$atts['color'].';' : '';

	$style .= (isset($atts['padding_top']) && !empty($atts['padding_top'])) ? 'padding-top:'.$atts['padding_top'].';' : '';
	$style .= (isset($atts['padding_bottom']) && !empty($atts['padding_bottom'])) ? 'padding-bottom:'.$atts['padding_bottom'].';' : '';
	$style .= (isset($atts['padding_left']) && !empty($atts['padding_left'])) ? 'padding-left:'.$atts['padding_left'].';' : '';
	$style .= (isset($atts['padding_right']) && !empty($atts['padding_right'])) ? 'padding-right:'.$atts['padding_right'].';' : '';

	$style .= (isset($atts['haeding_font-family']) && !empty($atts['haeding_font-family'])) ? 'font-family:'.$atts['haeding_font-family'].';' : '';
	$style .= (isset($atts['haeding_font-weight']) && !empty($atts['haeding_font-weight'])) ? 'font-weight:'.$atts['haeding_font-weight'].';' : '';
	$style .= (isset($atts['haeding_font-size']) && !empty($atts['haeding_font-size'])) ? 'font-size:'.$atts['haeding_font-size'].';' : '';
	$style .= (isset($atts['haeding_line-height']) && !empty($atts['haeding_line-height'])) ? 'line-height:'.$atts['haeding_line-height'].';' : '';


	$output = '';

	ob_start();

	?>
	<<?php echo esc_attr( $atts['tag'] ); ?> style="<?php echo esc_attr($style); ?>"><?php echo do_shortcode( $content ); ?></<?php echo esc_attr( $atts['tag'] ); ?>>
	
	<?php

	$output =  ob_get_clean();

	$output = apply_filters( 'ctf_pb_heading_element_output', $output, $atts, $content );

	return $output;
}
add_shortcode( 'ct_heading','ct_heading_cb' );



if (class_exists('CTPB_Element')) {

	$heading_map = array(
		'title' => 'Heading',
		'subtitle' => 'text Element',
		'code' => 'ct_heading',
		'hascontent' => true,
		'icon' => 'fa fa-header',
		'options' => array(
			array(
				'id' => 'heading_content',
				'label'    => __( 'Content', 'mytheme' ),
				'subtitle'    => __( 'Lorem ipsum dolor sit amet', 'mytheme' ),
				'type'     => 'text',
				'default' => '',
				'roll' => 'content'
			),
			array(
				'id' => 'color',
				'label'    => __( 'Text Color', 'mytheme' ),
				'subtitle'    => __( 'Lorem ipsum dolor sit amet', 'mytheme' ),
				'type'     => 'color',
				'default' => '',
			),
			array(
				'id' => 'tag',
				'label'    => __( 'Heading Tag', 'mytheme' ),
				'subtitle'    => __( 'Lorem ipsum dolor sit amet', 'mytheme' ),
				'type'     => 'select',
				'default' => 'h1',
				'choices' => array(
                    'h1' => 'Heading 1',
                    'h2' => 'Heading 2',
                    'h3' => 'Heading 3',
                    'h4' => 'Heading 4',
                    'h5' => 'Heading 5',
                    'h6' => 'Heading 6',
                    'p' => 'Paragraph',
                )
			),
			array(
                'id' => 'haeding',
                'label'    => __( 'Google Font Input', 'mytheme' ),
                'subtitle'    => __( 'Lorem ipsum dolor sit amet', 'mytheme' ),
                'type'     => 'google_font',
                'default' => array(),
            )
		)
	);

	$heading_map = apply_filters( 'ctf_pb_text_map', $heading_map );

	CTPB_Element::add($heading_map);
}