<?php

function mb_alert_cb( $atts, $content ) {

	$default = array(
		'alert_type' => 'info',
		'close_btn' => 0,
	);

	$atts_default = apply_filters( 'mb_alert_element_atts', $default );

	$atts = shortcode_atts( $atts_default, $atts );


	$output = '';

	ob_start();

	?>
	<div class="alert alert-<?php echo esc_attr( $atts['alert_type'] ); ?>" role="alert">
		<?php
			if ( (int) $atts['close_btn'] ) {
				echo '<button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">×</span></button>';
			}
			echo do_shortcode( $content );
		?>
	</div>
	
	<?php

	$output =  ob_get_clean();

	$output = apply_filters( 'mb_alert_element_output', $output, $atts, $content );

	return $output;
}
add_shortcode( 'mb_alert','mb_alert_cb' );



if (class_exists('MB_Element')) {

	$alert_map = array(
		'title' => 'Alert',
		'subtitle' => 'Alert Element',
		'code' => 'mb_alert',
		'hascontent' => true,
		'icon' => 'mb mb-alert',
		'color' => '#39cbaa',
		'options' => array(
			array(
				'id' => 'alert_content',
				'label'    => __( 'Content', 'mytheme' ),
				'subtitle'    => __( 'Content you like to show.', 'mytheme' ),
				'type'     => 'editor',
				'default' => '',
				'roll' => 'content'
			),
			array(
				'id' => 'alert_type',
				'label'    => __( 'Type', 'mytheme' ),
				'subtitle'    => __( 'Select alert type', 'mytheme' ),
				'type'     => 'select',
				'default' => 'info',
				'choices' => array(
					'success' => __('Success', 'mytheme'),
					'info' => __('Info', 'mytheme'),
					'warning' => __('Warning', 'mytheme'),
					'danger' => __('Danger', 'mytheme'),
				)
			),
			array(
				'id' => 'close_btn',
				'label'    => __( 'Close Button', 'mytheme' ),
				'subtitle'    => __( 'Show/Hide alert close button', 'mytheme' ),
				'type'     => 'radio',
				'default' => '0',
				'choices' => array(
					'0' => __('Hide', 'mytheme'),
					'1' => __('Show', 'mytheme'),
				)
			)
		)
	);

	$alert_map = apply_filters( 'mb_alert_map', $alert_map );

	MB_Element::add($alert_map);
}