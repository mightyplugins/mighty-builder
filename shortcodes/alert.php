<?php

if (!function_exists('mb_alert_cb')):

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

endif;


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
				'label'    => __( 'Content', 'mighty-builder' ),
				'subtitle'    => __( 'Content you like to show.', 'mighty-builder' ),
				'type'     => 'editor',
				'default' => '',
				'roll' => 'content'
			),
			array(
				'id' => 'alert_type',
				'label'    => __( 'Type', 'mighty-builder' ),
				'subtitle'    => __( 'Select alert type', 'mighty-builder' ),
				'type'     => 'select',
				'default' => 'info',
				'choices' => array(
					'success' => __('Success', 'mighty-builder'),
					'info' => __('Info', 'mighty-builder'),
					'warning' => __('Warning', 'mighty-builder'),
					'danger' => __('Danger', 'mighty-builder'),
				)
			),
			array(
				'id' => 'close_btn',
				'label'    => __( 'Close Button', 'mighty-builder' ),
				'subtitle'    => __( 'Show/Hide alert close button', 'mighty-builder' ),
				'type'     => 'radio',
				'default' => '0',
				'choices' => array(
					'0' => __('Hide', 'mighty-builder'),
					'1' => __('Show', 'mighty-builder'),
				)
			)
		)
	);

	$alert_map = apply_filters( 'mb_alert_map', $alert_map );

	MB_Element::add($alert_map);
}