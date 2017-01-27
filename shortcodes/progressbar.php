<?php

if (!function_exists('mb_progressbar_cb')):

function mb_progressbar_cb( $atts, $content ) {

	$default = array(
		'size' => '50',
		'title' => '',
		'type' => '',
		'striped' => 0,
		'animated' => 0,
	);

	$default = apply_filters( 'mb_progressbar_element_atts', $default );

	$atts = shortcode_atts( $default, $atts );

	$style = '';

	$style .= (isset($atts['size']) && !empty($atts['size'])) ? 'width:'.$atts['size'].'%;' : '';


	$bar_class = array();

	$bar_class[] = (!empty($atts['type'])) ? 'progress-bar-'.$atts['type'] : '';
	$bar_class[] = (!empty($atts['striped']) && (int) $atts['striped']) ? 'progress-bar-striped' : '';
	$bar_class[] = (!empty($atts['animated']) && (int) $atts['animated']) ? 'active' : '';




	ob_start();
	?>
	<div class="ct-progress-bar">
		<?php if(isset($atts['title']) && !empty($atts['title'])): ?>
			<strong><?php echo esc_html( $atts['title'] ); ?></strong>
		<?php endif; ?>
		<div class="progress">
			<div class="progress-bar <?php echo esc_attr(implode(' ', $bar_class)); ?>" role="progressbar" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100" style="<?php echo esc_attr($style); ?>">
				<span class="sr-only"><?php echo esc_html( $atts['size'] ); ?>%</span>
			</div>
		</div>
	</div>
	<?php

	$output =  ob_get_clean();

	$output = apply_filters( 'mb_progressbar_element_output', $output, $atts, $content );
	return $output;
}
add_shortcode( 'mb_progressbar','mb_progressbar_cb' );

endif;

if (class_exists('MB_Element')) {

	$progressbar_map = array(
		'title' => 'Progress Bar',
		'subtitle' => 'Progress Bar Element',
		'code' => 'mb_progressbar',
		'icon' => 'mb mb-progressbar',
		'color' => '#976eff',
		'options' => array(
			array(
				'id' => 'title',
				'label'    => __( 'Title', 'mighty-builder' ),
				'subtitle'    => __( 'Title of prgress bar', 'mighty-builder' ),
				'type'     => 'text',
			),
			array(
				'id' => 'size',
				'label'    => __( 'Size', 'mighty-builder' ),
				'subtitle'    => __( 'Prgress bar size as % (max value 100)', 'mighty-builder' ),
				'type'     => 'number',
				'default' => '50',
				'choices' => array(
					'min' => 0,
					'max' => 100,
					'step' => 1,
				)
			),
			array(
				'id' => 'type',
				'label'    => __( 'Type', 'mighty-builder' ),
				'subtitle'    => __( 'Select progress bar style', 'mighty-builder' ),
				'type'     => 'select',
				'default' => 'default',
				'choices' => array(
					'default' => __('Default', 'mighty-builder'),
					'success' => __('Success', 'mighty-builder'),
					'info' => __('Info', 'mighty-builder'),
					'warning' => __('Warning', 'mighty-builder'),
					'danger' => __('Danger', 'mighty-builder'),
				)
			),
			array(
				'id' => 'striped',
				'label'    => __( 'Striped', 'mighty-builder' ),
				'subtitle'    => __( 'Enable/Disable striped progress bar', 'mighty-builder' ),
				'type'     => 'radio',
				'default' => '0',
				'choices' => array(
					'0' => __('Disable', 'mighty-builder'),
					'1' => __('Enable', 'mighty-builder'),
				)
			),
			array(
				'id' => 'animated',
				'label'    => __( 'Animated', 'mighty-builder' ),
				'subtitle'    => __( 'Enable/Disable progress bar animation', 'mighty-builder' ),
				'type'     => 'radio',
				'default' => '0',
				'choices' => array(
					'0' => __('Disable', 'mighty-builder'),
					'1' => __('Enable', 'mighty-builder'),
				)
			)
		)
	);

	$progressbar_map = apply_filters( 'mb_progressbar_map', $progressbar_map );

	MB_Element::add($progressbar_map);
}