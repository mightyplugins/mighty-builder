<?php

if (!function_exists('mb_icon_cb')):

function mb_icon_cb( $atts, $content ) {

	$default = array(
		'icon' => 'fa fa-cogs',
		'size' => '25px',
		'color' => '',
		'background_color' => '',
		'border_radius' => '',
		'width' => '',
		'height' => '',
		'icon_align' => ''
	);

	$default = apply_filters( 'mb_icon_element_atts', $default );

	$atts = shortcode_atts( $default, $atts );

	$style = '';

	$style .= (isset($atts['color']) && !empty($atts['color'])) ? 'color:'.$atts['color'].';' : '';
	$style .= (isset($atts['size']) && !empty($atts['size'])) ? 'font-size:'.$atts['size'].';' : '';
	$style .= (isset($atts['background_color']) && !empty($atts['background_color'])) ? 'background-color:'.$atts['background_color'].';' : '';
	$style .= (isset($atts['border_radius']) && !empty($atts['border_radius'])) ? 'border-radius:'.$atts['border_radius'].';' : '';
	$style .= (isset($atts['width']) && !empty($atts['width'])) ? 'width:'.$atts['width'].';' : '';
	$style .= (isset($atts['height']) && !empty($atts['height'])) ? 'height:'.$atts['height'].';' : '';
	$style .= (isset($atts['height']) && !empty($atts['height'])) ? 'line-height:'.$atts['height'].';' : '';

	$style_cont = '';
	$style_cont .= (isset($atts['icon_align']) && !empty($atts['icon_align'])) ? 'text-align:'.$atts['icon_align'].';' : '';



	ob_start();
	?>
	<div class="ct-icon" style="<?php echo esc_attr($style_cont); ?>"><i style="<?php echo esc_attr($style); ?>" class="<?php echo esc_attr( $atts['icon'] ); ?>"></i></div>
	<?php

	$output =  ob_get_clean();

	$output = apply_filters( 'mb_icon_element_output', $output, $atts, $content );
	return $output;
}
add_shortcode( 'mb_icon','mb_icon_cb' );

endif;

if (class_exists('MB_Element')) {

	$icon_map = array(
		'title' => 'Icon',
		'subtitle' => 'Icon Element',
		'code' => 'mb_icon',
		'icon' => 'mb mb-icon',
		'color' => '#5bda97',
		'options' => array(
			array(
				'id' => 'icon',
				'label'    => __( 'Icon', 'mighty-builder' ),
				'subtitle'    => __( 'Select an icon', 'mighty-builder' ),
				'type'     => 'icon',
				'default' => 'fa fa-cogs',
			),
			array(
				'id' => 'size',
				'label'    => __( 'Size', 'mighty-builder' ),
				'subtitle'    => __( 'Icon size', 'mighty-builder' ),
				'type'     => 'dimension',
				'default' => '25px',
				'choices' => array( 'units' => array( 'px', 'em', 'rem' ) )
			),
			array(
				'id' => 'icon_align',
				'label'    => __( 'Align', 'mighty-builder' ),
				'subtitle'    => __( 'Icon alignment', 'mighty-builder' ),
				'type'     => 'text_align',
				'default' => '',
				'choices' => array( 'justify' => '0' )
			),
			array(
				'id' => 'color',
				'label'    => __( 'Color', 'mighty-builder' ),
				'subtitle'    => __( 'Icon color', 'mighty-builder' ),
				'type'     => 'color',
				'default' => '',
			),
			array(
				'id' => 'background_color',
				'label'    => __( 'Background Color', 'mighty-builder' ),
				'subtitle'    => __( 'Icon background color', 'mighty-builder' ),
				'type'     => 'color',
				'default' => '',
			),
			array(
				'id' => 'width',
				'label'    => __( 'Width', 'mighty-builder' ),
				'subtitle'    => __( 'Icon width', 'mighty-builder' ),
				'type'     => 'dimension',
				'default' => '',
				'choices' => array( 'units' => array( 'px', '%' ) )
			),
			array(
				'id' => 'height',
				'label'    => __( 'Height', 'mighty-builder' ),
				'subtitle'    => __( 'Icon height', 'mighty-builder' ),
				'type'     => 'dimension',
				'default' => '',
				'choices' => array( 'units' => array( 'px', '%' ) )
			),
			array(
				'id' => 'border_radius',
				'label'    => __( 'Border Radius', 'mighty-builder' ),
				'subtitle'    => __( 'Border radius of icon', 'mighty-builder' ),
				'type'     => 'dimension',
				'default' => '',
				'choices' => array( 'units' => array( 'px', '%' ) )
			),
		)
	);

	$icon_map = apply_filters( 'mb_icon_map', $icon_map );

	MB_Element::add($icon_map);
}