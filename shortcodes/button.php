<?php

function mb_btn_cb( $atts, $content ) {

	$default = array(
		'btn_type' => 'info',
		'size' => '',
	);

	$atts_default = apply_filters( 'mb_btn_element_atts', $default );

	$atts = shortcode_atts( $atts_default, $atts );


	$output = '';

	ob_start();

	?>
	<a class="btn btn-<?php echo esc_attr( $atts['btn_type'] ); ?> <?php echo esc_attr( $atts['size'] ); ?>" role="button">
		<?php echo do_shortcode( $content ); ?>
	</a>
	
	<?php

	$output =  ob_get_clean();

	$output = apply_filters( 'mb_btn_element_output', $output, $atts, $content );

	return $output;
}
add_shortcode( 'mb_btn','mb_btn_cb' );



if (class_exists('CTPB_Element')) {

	$btn_map = array(
		'title' => 'Button',
		'subtitle' => 'btn Element',
		'code' => 'mb_btn',
		'hascontent' => true,
		'icon' => 'fa fa-font',
		'options' => array(
			array(
				'id' => 'btn_content',
				'label'    => __( 'Text', 'mytheme' ),
				'subtitle'    => __( 'Content you like to show.', 'mytheme' ),
				'type'     => 'text',
				'default' => '',
				'roll' => 'content'
			),
			array(
				'id' => 'btn_type',
				'label'    => __( 'Type', 'mytheme' ),
				'subtitle'    => __( 'Content you like to show.', 'mytheme' ),
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
				'subtitle'    => __( 'Content you like to show.', 'mytheme' ),
				'type'     => 'select',
				'default' => '',
				'choices' => array(
					'btn-xs' => __('Extra Small', 'mytheme'),
					'btn-sm' => __('Small', 'mytheme'),
					'btn-nm' => __('Normal', 'mytheme'),
					'btn-lg' => __('Large', 'mytheme'),
				)
			)
		)
	);

	$btn_map = apply_filters( 'mb_btn_map', $btn_map );

	CTPB_Element::add($btn_map);
}