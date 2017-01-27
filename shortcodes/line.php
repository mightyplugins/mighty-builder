<?php

if (!function_exists('mb_line_cb')):

function mb_line_cb( $atts, $content ) {

	$default = array(
		'color' => '',
		'width' => '100%',
		'height' => '1px',
		'align' => 'center',
		'margin_top' => '20px',
		'margin_bottom' => '20px',
	);

	$default = apply_filters( 'mb_line_element_atts', $default );

	$atts = shortcode_atts( $default, $atts );

	$style = '';
	$style_container = '';

	$style .= (isset($atts['color']) && !empty($atts['color'])) ? 'border-top-color:'.$atts['color'].';' : '';
	$style .= (isset($atts['width']) && !empty($atts['width'])) ? 'width:'.$atts['width'].';' : '';
	$style .= (isset($atts['height']) && !empty($atts['height'])) ? 'border-top-width:'.$atts['height'].';' : '';

	$style_container .= (isset($atts['align']) && !empty($atts['align'])) ? 'text-align:'.$atts['align'].';' : '';
	$style_container .= (isset($atts['height']) && !empty($atts['height'])) ? 'line-height:'.$atts['height'].';' : '';
	$style_container .= (isset($atts['margin_top']) && !empty($atts['margin_top'])) ? 'margin-top:'.$atts['margin_top'].';' : '';
	$style_container .= (isset($atts['margin_bottom']) && !empty($atts['margin_bottom'])) ? 'margin-bottom:'.$atts['margin_bottom'].';' : '';




	ob_start();
	?>
	<div class="ct-line-container" style="<?php echo esc_attr($style_container); ?>">
		<hr class="ct-line" style="<?php echo esc_attr($style); ?>">
	</div>
	<?php

	$output =  ob_get_clean();

	$output = apply_filters( 'mb_line_element_output', $output, $atts, $content );
	return $output;
}
add_shortcode( 'mb_line','mb_line_cb' );

endif;

if (class_exists('MB_Element')) {

	$line_map = array(
		'title' => 'Line',
		'subtitle' => 'Line Element',
		'code' => 'mb_line',
		'icon' => 'mb mb-line',
		'color' => '#ff6e6e',
		'options' => array(
			array(
				'id' => 'color',
				'label'    => __( 'Color', 'mighty-builder' ),
				'subtitle'    => __( 'Line color', 'mighty-builder' ),
				'type'     => 'color_rgba',
				'default' => '',
			),
			array(
				'id' => 'width',
				'label'    => __( 'Width', 'mighty-builder' ),
				'type'     => 'dimension',
				'default' => '100%',
				'choices' => array( 'units' => array( 'px', 'em', 'rem', '%' ) )
			),
			array(
				'id' => 'height',
				'label'    => __( 'Height', 'mighty-builder' ),
				'type'     => 'dimension',
				'default' => '1px',
				'choices' => array( 'units' => array( 'px', 'em', 'rem', '%' ) )
			),
			array(
				'id' => 'align',
				'label'    => __( 'Align', 'mighty-builder' ),
				'subtitle'    => __( 'Line alignment', 'mighty-builder' ),
				'type'     => 'text_align',
				'default' => 'center',
				'choices' => array( 'justify' => '0' )
			),
			array(
				'id' => 'margin_top',
				'label'    => __( 'Margin Top', 'mighty-builder' ),
				'type'     => 'dimension',
				'default' => '20px',
				'choices' => array( 'units' => array( 'px', 'em', 'rem' ) )
			),
			array(
				'id' => 'margin_bottom',
				'label'    => __( 'Margin Bottom', 'mighty-builder' ),
				'type'     => 'dimension',
				'default' => '20px',
				'choices' => array( 'units' => array( 'px', 'em', 'rem' ) )
			)
		)
	);

	$line_map = apply_filters( 'mb_line_map', $line_map );

	MB_Element::add($line_map);
}