<?php

function ct_text_cb( $atts, $content ) {

	$default = array(
		'padding_top' => '',
		'padding_bottom' => '',
		'color' => '',
	);

	$atts_default = apply_filters( 'ctf_pb_text_element_atts', $default );

	$atts = shortcode_atts( $atts_default, $atts );

	$style = '';

	$style .= (isset($atts['color']) && !empty($atts['color'])) ? 'color:'.$atts['color'].';' : '';


	$output = '';

	ob_start();

	?>
	<div class="text" style="<?php echo esc_attr($style); ?>">
		<?php echo do_shortcode( $content ); ?>
	</div>
	
	<?php

	$output =  ob_get_clean();

	$output = apply_filters( 'ctf_pb_text_element_output', $output, $atts, $content );

	return $output;
}
add_shortcode( 'ct_text','ct_text_cb' );



if (class_exists('CTPB_Element')) {

	$text_map = array(
		'title' => 'Text',
		'subtitle' => 'text Element',
		'code' => 'ct_text',
		'hascontent' => true,
		'icon' => 'fa fa-font',
		'options' => array(
			array(
				'id' => 'text_content',
				'label'    => __( 'Content', 'mytheme' ),
				'subtitle'    => __( 'Content you like to show.', 'mytheme' ),
				'type'     => 'editor',
				'default' => '',
				'roll' => 'content'
			)
		)
	);

	$text_map = apply_filters( 'ctf_pb_text_map', $text_map );

	CTPB_Element::add($text_map);
}