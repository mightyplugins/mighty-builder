<?php

function mb_row_cb( $atts, $content ) {

	$default = array(
		'padding_top' => '',
		'padding_bottom' => '',
		'bg_color' => '',
	);

	$atts_default = apply_filters( 'mb_row_element_atts', $default );

	$atts = shortcode_atts( $atts_default, $atts );

	$style = '';

	$style .= (isset($atts['bg_color']) && !empty($atts['bg_color'])) ? 'background-color:'.$atts['bg_color'].';' : '';

	$style .= (isset($atts['padding_top']) && !empty($atts['padding_top'])) ? 'padding-top:'.$atts['padding_top'].';' : '';
	$style .= (isset($atts['padding_bottom']) && !empty($atts['padding_bottom'])) ? 'padding-bottom:'.$atts['padding_bottom'].';' : '';


	$output = '';

	ob_start();

	?>
	<div class="row" style="<?php echo esc_attr($style); ?>">
		<?php echo do_shortcode( $content ); ?>
	</div>
	
	<?php

	$output =  ob_get_clean();

	$output = apply_filters( 'mb_row_element_output', $output, $atts, $content );

	return $output;
}
add_shortcode( 'mb_row','mb_row_cb' );



if (class_exists('MB_Element')) {

	$row_map = array(
		'title' => 'row',
		'subtitle' => 'row Element',
		'code' => 'mb_row',
		'hascontent' => true,
		'layout' => true,
		'icon' => '',
		'options' => array(
			array(
				'id' => 'bg_color',
				'label'    => __( 'Background Color', 'mytheme' ),
				'subtitle'    => __( 'Lorem ipsum dolor sit amet', 'mytheme' ),
				'type'     => 'color',
				'default' => '',
			)
		)
	);

	$row_map = apply_filters( 'mb_row_map', $row_map );

	MB_Element::add($row_map);
}