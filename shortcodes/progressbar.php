<?php

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

if (class_exists('MB_Element')) {

	$progressbar_map = array(
		'title' => 'Progress Bar',
		'subtitle' => 'Progress Bar Element',
		'code' => 'mb_progressbar',
		'icon' => 'fa fa-tasks',
		'color' => '#976eff',
		'options' => array(
			array(
				'id' => 'title',
				'label'    => __( 'Title', 'mytheme' ),
				'subtitle'    => __( 'Title of prgress bar', 'mytheme' ),
				'type'     => 'text',
			),
			array(
				'id' => 'size',
				'label'    => __( 'Size', 'mytheme' ),
				'subtitle'    => __( 'Prgress bar size as % (max value 100)', 'mytheme' ),
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
				'label'    => __( 'Type', 'mytheme' ),
				'subtitle'    => __( 'Select progress bar style', 'mytheme' ),
				'type'     => 'select',
				'default' => 'default',
				'choices' => array(
					'default' => __('Default', 'mytheme'),
					'success' => __('Success', 'mytheme'),
					'info' => __('Info', 'mytheme'),
					'warning' => __('Warning', 'mytheme'),
					'danger' => __('Danger', 'mytheme'),
				)
			),
			array(
				'id' => 'striped',
				'label'    => __( 'Striped', 'mytheme' ),
				'subtitle'    => __( 'Enable/Disable striped progress bar', 'mytheme' ),
				'type'     => 'radio',
				'default' => '0',
				'choices' => array(
					'0' => __('Disable', 'mytheme'),
					'1' => __('Enable', 'mytheme'),
				)
			),
			array(
				'id' => 'animated',
				'label'    => __( 'Animated', 'mytheme' ),
				'subtitle'    => __( 'Enable/Disable progress bar animation', 'mytheme' ),
				'type'     => 'radio',
				'default' => '0',
				'choices' => array(
					'0' => __('Disable', 'mytheme'),
					'1' => __('Enable', 'mytheme'),
				)
			)
		)
	);

	$progressbar_map = apply_filters( 'mb_progressbar_map', $progressbar_map );

	MB_Element::add($progressbar_map);
}