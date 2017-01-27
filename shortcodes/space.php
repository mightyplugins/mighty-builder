<?php

if (!function_exists('mb_space_cb')):

function mb_space_cb( $atts, $content ) {

	$default = array(
		'size' => '25px',
	);

	$default = apply_filters( 'mb_space_element_atts', $default );

	$atts = shortcode_atts( $default, $atts );

	$style = '';

	$style .= (isset($atts['size']) && !empty($atts['size'])) ? 'height:'.$atts['size'].';' : '';




	ob_start();
	?>
	<div class="ct-space" style="<?php echo esc_attr($style); ?>"></div>
	<?php

	$output =  ob_get_clean();

	$output = apply_filters( 'mb_space_element_output', $output, $atts, $content );
	return $output;
}
add_shortcode( 'mb_space','mb_space_cb' );

endif;

if (class_exists('MB_Element')) {

	$space_map = array(
		'title' => 'Space',
		'subtitle' => 'Space Element',
		'code' => 'mb_space',
		'icon' => 'mb mb-space',
		'color' => '#6063cd',
		'options' => array(
			array(
				'id' => 'size',
				'label'    => __( 'Size', 'mighty-builder' ),
				'subtitle'    => __( 'Space size', 'mighty-builder' ),
				'type'     => 'dimension',
				'default' => '25px',
				'choices' => array( 'units' => array( 'px', 'em', 'rem' ) )
			)
		)
	);

	$space_map = apply_filters( 'mb_space_map', $space_map );

	MB_Element::add($space_map);
}