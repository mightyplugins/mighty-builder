<?php

function mb_section_cb( $atts, $content ) {

	$default = array(
		'padding_top' => '50px',
		'padding_bottom' => '50px',
		'padding_left' => '0',
		'padding_right' => '0',
		'bg_color' => '',
		'container' => 'fullwidth', // boxed, fluid, fullwidth
	);

	$atts_default = apply_filters( 'mb_section_element_atts', $default );

	$atts = shortcode_atts( $atts_default, $atts );

	$style = '';

	$style .= (isset($atts['bg_color']) && !empty($atts['bg_color'])) ? 'background-color:'.$atts['bg_color'].';' : '';

	$style .= (isset($atts['padding_top']) && !empty($atts['padding_top'])) ? 'padding-top:'.$atts['padding_top'].';' : '';
	$style .= (isset($atts['padding_bottom']) && !empty($atts['padding_bottom'])) ? 'padding-bottom:'.$atts['padding_bottom'].';' : '';
	$style .= (isset($atts['padding_left']) && !empty($atts['padding_left'])) ? 'padding-left:'.$atts['padding_left'].';' : '';
	$style .= (isset($atts['padding_right']) && !empty($atts['padding_right'])) ? 'padding-right:'.$atts['padding_right'].';' : '';


	$section_style = '';
	$container_style = '';

	if (isset($atts['container']) && $atts['container'] == 'boxed') {
		$container_style = $style;
	} else {
		$section_style = $style;
	}

	$container_class = 'container';

	if (isset($atts['container']) && $atts['container'] == 'fluid') {
		$container_class = 'container-fluid';
	}

	$output = '';

	ob_start();

	?>
	<section style="<?php echo esc_attr($section_style); ?>">
		<div class="<?php echo esc_attr($container_class); ?>" style="<?php echo esc_attr($container_style); ?>">
			<?php echo do_shortcode( $content ); ?>
		</div>
	</section>
	
	<?php

	$output =  ob_get_clean();

	$output = apply_filters( 'mb_section_element_output', $output, $atts, $content );

	return $output;
}
add_shortcode( 'mb_section','mb_section_cb' );



if (class_exists('MB_Element')) {

	$section_map = array(
		'title' => 'Section',
		'subtitle' => 'Section Element',
		'code' => 'mb_section',
		'hascontent' => true,
		'layout' => true,
		'icon' => '',
		'options' => array(
			array(
				'id' => 'container',
				'label'    => __( 'Container', 'mytheme' ),
				'subtitle'    => __( 'Lorem ipsum dolor sit amet', 'mytheme' ),
				'type'     => 'radio_button',
				'default' => 'fullwidth',
                'choices' => array(
                    'boxed' => 'Boxed',
                    'fluid' => 'Fluid',
                    'fullwidth' => 'Fullwidth'
                )
			),
			array(
				'id' => 'bg_color',
				'label'    => __( 'Background Color', 'mytheme' ),
				'subtitle'    => __( 'Lorem ipsum dolor sit amet', 'mytheme' ),
				'type'     => 'color',
				'default' => '',
			)
		)
	);

	$section_map = apply_filters( 'mb_section_map', $section_map );

	MB_Element::add($section_map);
}