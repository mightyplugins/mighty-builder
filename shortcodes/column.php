<?php

function mb_column_cb( $atts, $content ) {

	$default = array(
		'padding_top' => '',
		'padding_bottom' => '',
		'padding_left' => '',
		'padding_right' => '',
		'bg_color' => '',
		'col' => 12, 
	);

	$atts_default = apply_filters( 'mb_column_element_atts', $default );

	$atts = shortcode_atts( $atts_default, $atts );

	$style = '';

	$style .= (isset($atts['bg_color']) && !empty($atts['bg_color'])) ? 'background-color:'.$atts['bg_color'].';' : '';

	$style .= (isset($atts['padding_top']) && !empty($atts['padding_top'])) ? 'padding-top:'.$atts['padding_top'].';' : '';
	$style .= (isset($atts['padding_bottom']) && !empty($atts['padding_bottom'])) ? 'padding-bottom:'.$atts['padding_bottom'].';' : '';
	$style .= (isset($atts['padding_left']) && !empty($atts['padding_left'])) ? 'padding-left:'.$atts['padding_left'].';' : '';
	$style .= (isset($atts['padding_right']) && !empty($atts['padding_right'])) ? 'padding-right:'.$atts['padding_right'].';' : '';

	$column_class = array();


	if ( isset($atts['col'])  && !empty($atts['col']) ) {
		$column_class[] = 'col-md-'.$atts['col'];
	}


	$output = '';

	ob_start();

	?>
	<div class="<?php echo implode(' ', $column_class); ?>" style="<?php echo esc_attr($style); ?>">
		<?php echo do_shortcode( $content ); ?>
	</div>
	
	<?php

	$output =  ob_get_clean();

	$output = apply_filters( 'mb_column_element_output', $output, $atts, $content );

	return $output;
}
add_shortcode( 'mb_col','mb_column_cb' );



if (class_exists('MB_Element')) {

	$column_map = array(
		'title' => 'column',
		'subtitle' => 'column Element',
		'code' => 'mb_col',
		'hascontent' => true,
		'layout' => true,
		'icon' => '',
		'options' => array(
			array(
				'id' => 'bg_color',
				'label'    => __( 'Background Color', 'mytheme' ),
				'subtitle'    => __( 'Lorem ipsum dolor sit amet', 'mytheme' ),
				'type'     => 'color',
				'default' => '',
			),
			array(
				'id' => 'padding_top',
				'label'    => __( 'Padding Top', 'mytheme' ),
				'subtitle'    => __( 'Lorem ipsum dolor sit amet', 'mytheme' ),
				'type'     => 'dimension',
				'default' => '',
			),
			array(
				'id' => 'padding_bottom',
				'label'    => __( 'Padding Bottom', 'mytheme' ),
				'subtitle'    => __( 'Lorem ipsum dolor sit amet', 'mytheme' ),
				'type'     => 'dimension',
				'default' => '',
			),
			array(
				'id' => 'col',
				'label'    => __( 'Background Color', 'mytheme' ),
				'subtitle'    => __( 'Lorem ipsum dolor sit amet', 'mytheme' ),
				'type'     => 'readonly',
			)
		)
	);

	$column_map = apply_filters( 'mb_column_map', $column_map );

	MB_Element::add($column_map);
}