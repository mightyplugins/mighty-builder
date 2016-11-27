<?php

function mb_progressbar_cb( $atts, $content ) {

	$default = array(
		'size' => '50',
		'title' => '',
	);

	$default = apply_filters( 'mb_progressbar_element_atts', $default );

	$atts = shortcode_atts( $default, $atts );

	$style = '';

	$style .= (isset($atts['size']) && !empty($atts['size'])) ? 'width:'.$atts['size'].'%;' : '';




	ob_start();
	?>
	<div class="ct-progress-bar">
		<?php if(isset($atts['title']) && !empty($atts['title'])): ?>
			<strong><?php echo esc_html( $atts['title'] ); ?></strong>
		<?php endif; ?>
		<div class="progress">
			<div class="progress-bar" role="progressbar" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100" style="<?php echo esc_attr($style); ?>">
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

if (class_exists('CTPB_Element')) {

	$progressbar_map = array(
		'title' => 'Progress Bar',
		'subtitle' => 'text Element',
		'code' => 'mb_progressbar',
		'icon' => 'fa fa-info',
		'options' => array(
			array(
				'id' => 'title',
				'label'    => __( 'Title', 'mytheme' ),
				'subtitle'    => __( 'Lorem ipsum dolor sit amet', 'mytheme' ),
				'type'     => 'text',
			),
			array(
				'id' => 'size',
				'label'    => __( 'Size', 'mytheme' ),
				'subtitle'    => __( 'Lorem ipsum dolor sit amet', 'mytheme' ),
				'type'     => 'number',
				'default' => '50',
			)
		)
	);

	$progressbar_map = apply_filters( 'mb_progressbar_map', $progressbar_map );

	CTPB_Element::add($progressbar_map);
}