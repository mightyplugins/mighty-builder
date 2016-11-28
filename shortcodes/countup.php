<?php

function mb_countup_cb( $atts, $content ) {

	$default = array(
		'num' => '100',
		'prefix' => '',
		'suffix' => ''
	);

	$default = apply_filters( 'mb_countup_element_atts', $default );

	$atts = shortcode_atts( $default, $atts );

	$style = '';

	$style .= (isset($atts['size']) && !empty($atts['size'])) ? 'height:'.$atts['size'].';' : '';




	ob_start();
	?>
	<div class="ct-countup" style="<?php echo esc_attr($style); ?>">
		<?php if(isset($atts['prefix']) && !empty($atts['prefix'])): ?><span><?php echo esc_html( $atts['prefix'] ); ?></span><?php endif; ?><span class="ct-counter"><?php echo esc_html( $atts['num'] ); ?></span><?php if(isset($atts['suffix']) && !empty($atts['suffix'])): ?><span><?php echo esc_html( $atts['suffix'] ); ?></span><?php endif; ?>
	</div>
	<?php

	$output =  ob_get_clean();

	$output = apply_filters( 'mb_countup_element_output', $output, $atts, $content );
	return $output;
}
add_shortcode( 'mb_countup','mb_countup_cb' );

if (class_exists('MB_Element')) {

	$countup_map = array(
		'title' => 'CountUp',
		'subtitle' => 'text Element',
		'code' => 'mb_countup',
		'icon' => 'fa fa-info',
		'color' => '#ff9800',
		'options' => array(
			array(
				'id' => 'num',
				'label'    => __( 'Number', 'mytheme' ),
				'subtitle'    => __( 'Lorem ipsum dolor sit amet', 'mytheme' ),
				'type'     => 'number',
				'default' => '100',
			),
			array(
				'id' => 'prefix',
				'label'    => __( 'Prefix', 'mytheme' ),
				'subtitle'    => __( 'Lorem ipsum dolor sit amet', 'mytheme' ),
				'type'     => 'text',
				'default' => '',
			),
			array(
				'id' => 'suffix',
				'label'    => __( 'Suffix', 'mytheme' ),
				'subtitle'    => __( 'Lorem ipsum dolor sit amet', 'mytheme' ),
				'type'     => 'text',
				'default' => '',
			)
		)
	);

	$countup_map = apply_filters( 'mb_countup_map', $countup_map );

	MB_Element::add($countup_map);
}