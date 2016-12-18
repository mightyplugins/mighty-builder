<?php

function mb_btn_cb( $atts, $content ) {

	$default = array(
		'btn_type' => 'info',
		'size' => '',
		'align' => '',
	);

	$atts_default = apply_filters( 'mb_btn_element_atts', $default );

	$atts = shortcode_atts( $atts_default, $atts );


	$style_container = '';

	$style_container .= (isset($atts['align']) && !empty($atts['align'])) ? 'text-align:'.$atts['align'].';' : '';


	$output = '';

	ob_start();

	?>
	<div class="mb-btn-container" style="<?php echo esc_attr($style_container); ?>">
		<a class="btn btn-<?php echo esc_attr( $atts['btn_type'] ); ?> <?php echo esc_attr( $atts['size'] ); ?>" role="button">
			<?php echo do_shortcode( $content ); ?>
		</a>
	</div>
	
	<?php

	$output =  ob_get_clean();

	$output = apply_filters( 'mb_btn_element_output', $output, $atts, $content );

	return $output;
}
add_shortcode( 'mb_btn','mb_btn_cb' );



if (function_exists('mb_add_map')) {

	$btn_map = array(
		'title' => 'Button',
		'subtitle' => 'Button Element',
		'code' => 'mb_btn',
		'hascontent' => true,
		'icon' => 'fa fa-square-o',
		'color' => '#6ec3ff',
		'options' => array(
			array(
				'id' => 'btn_content',
				'label'    => __( 'Text', 'mytheme' ),
				'subtitle'    => __( 'Button text', 'mytheme' ),
				'type'     => 'text',
				'default' => '',
				'roll' => 'content'
			),
			array(
				'id' => 'btn_type',
				'label'    => __( 'Type', 'mytheme' ),
				'subtitle'    => __( 'Select button style', 'mytheme' ),
				'type'     => 'select',
				'default' => 'default',
				'choices' => array(
					'default' => __('Default', 'mytheme'),
					'primary' => __('Primary', 'mytheme'),
					'success' => __('Success', 'mytheme'),
					'info' => __('Info', 'mytheme'),
					'warning' => __('Warning', 'mytheme'),
					'danger' => __('Danger', 'mytheme'),
					'link' => __('Link', 'mytheme'),
				)
			),
			array(
				'id' => 'size',
				'label'    => __( 'Size', 'mytheme' ),
				'subtitle'    => __( 'Select button size', 'mytheme' ),
				'type'     => 'select',
				'default' => 'btn-nm',
				'choices' => array(
					'btn-xs' => __('Extra Small', 'mytheme'),
					'btn-sm' => __('Small', 'mytheme'),
					'btn-nm' => __('Normal', 'mytheme'),
					'btn-lg' => __('Large', 'mytheme'),
				)
			),
			array(
				'id' => 'align',
				'label'    => __( 'Align', 'mytheme' ),
				'subtitle'    => __( 'Icon alignment', 'mytheme' ),
				'type'     => 'text_align',
				'default' => 'left',
				'choices' => array( 'justify' => '0' )
			),
		)
	);
	
	mb_add_map($btn_map);
}