<?php

function mb_text_cb( $atts, $content ) {

	$default = array(
		'padding_top' => '',
		'padding_bottom' => '',
		'color' => '',
		'align' => ''
	);

	$atts_default = apply_filters( 'mb_text_element_atts', $default );

	$atts = shortcode_atts( $atts_default, $atts );



	$style = '';

	$style .= (isset($atts['color']) && !empty($atts['color'])) ? 'color:'.$atts['color'].';' : '';
	$style .= (isset($atts['align']) && !empty($atts['align'])) ? 'text-align:'.$atts['align'].';' : '';


	$output = '';

	ob_start();

	?>
	<div class="text" style="<?php echo esc_attr($style); ?>">
		<?php echo do_shortcode( $content ); ?>
	</div>
	
	<?php

	$output =  ob_get_clean();

	$output = apply_filters( 'mb_text_element_output', $output, $atts, $content );

	return $output;
}
add_shortcode( 'mb_text','mb_text_cb' );



if (class_exists('CTPB_Element')) {

	$text_map = array(
		'title' => 'Text',
		'subtitle' => 'text Element',
		'code' => 'mb_text',
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
			),
			array(
				'id' => 'align',
				'label'    => __( 'Text Align', 'mytheme' ),
				'subtitle'    => __( 'Content you like to show.', 'mytheme' ),
				'type'     => 'text_align',
				'default' => '',
			)
		)
	);

	$text_map = apply_filters( 'mb_text_map', $text_map );

	CTPB_Element::add($text_map);
}