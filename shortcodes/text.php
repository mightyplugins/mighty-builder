<?php

if (!function_exists('mb_text_cb')):

function mb_text_cb( $atts, $content ) {

	$default = array(
		'padding_top'		=> '',
		'padding_bottom'	=> '',
		'padding_left'		=> '',
		'padding_right'		=> '',
		'background_color'	=> '',
		'color'				=> '',
		'align'				=> '',
		'font_size'			=> '',
	);

	$atts_default = apply_filters( 'mb_text_element_atts', $default );

	$atts = shortcode_atts( $atts_default, $atts );



	$style = '';

	$style .= (isset($atts['color']) && !empty($atts['color'])) ? 'color:'.$atts['color'].';' : '';
	$style .= (isset($atts['align']) && !empty($atts['align'])) ? 'text-align:'.$atts['align'].';' : '';
	$style .= (isset($atts['padding_top']) && !empty($atts['padding_top'])) ? 'padding-top:'.$atts['padding_top'].';' : '';
	$style .= (isset($atts['padding_bottom']) && !empty($atts['padding_bottom'])) ? 'padding-bottom:'.$atts['padding_bottom'].';' : '';
	$style .= (isset($atts['padding_left']) && !empty($atts['padding_left'])) ? 'padding-left:'.$atts['padding_left'].';' : '';
	$style .= (isset($atts['padding_right']) && !empty($atts['padding_right'])) ? 'padding-right:'.$atts['padding_right'].';' : '';
	$style .= (isset($atts['font_size']) && !empty($atts['font_size'])) ? 'font-size:'.$atts['font_size'].';' : '';
	$style .= (isset($atts['background_color']) && !empty($atts['background_color'])) ? 'background-color:'.$atts['background_color'].';' : '';


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

endif;



if (class_exists('MB_Element')) {

	$text_map = array(
		'title' => 'Text',
		'subtitle' => 'Text Element',
		'code' => 'mb_text',
		'hascontent' => true,
		'icon' => 'mb mb-text',
		'color' => '#ff6eec',
		'options' => array(
			array(
				'id' => 'text_content',
				'label'    => __( 'Content', 'mighty-builder' ),
				'subtitle'    => __( 'Content you like to show.', 'mighty-builder' ),
				'type'     => 'editor',
				'default' => '',
				'roll' => 'content',
				'tab' => 'Genaral'
			),
			array(
				'id' => 'font_size',
				'label'    => __( 'Font Size', 'mighty-builder' ),
				'subtitle'    => __( 'Text font size', 'mighty-builder' ),
				'type'     => 'dimension',
				'default' => '',
				'tab' => 'Style'
			),
			array(
				'id' => 'align',
				'label'    => __( 'Text Align', 'mighty-builder' ),
				'type'     => 'text_align',
				'default' => '',
				'tab' => 'Style'
			),
			array(
				'id' => 'color',
				'label'    => __( 'Text Color', 'mighty-builder' ),
				'type'     => 'color',
				'default' => '',
				'tab' => 'Style'
			),
			array(
				'id' => 'background_color',
				'label'    => __( 'Background Color', 'mighty-builder' ),
				'type'     => 'color',
				'default' => '',
				'tab' => 'Style'
			),
			array(
				'id' => 'padding_left',
				'label'    => __( 'Padding Left', 'mighty-builder' ),
				'type'     => 'dimension',
				'default' => '',
				'tab' => 'Spacing'
			),
			array(
				'id' => 'padding_right',
				'label'    => __( 'Padding Right', 'mighty-builder' ),
				'type'     => 'dimension',
				'default' => '',
				'tab' => 'Spacing'
			),
			array(
				'id' => 'padding_top',
				'label'    => __( 'Padding Top', 'mighty-builder' ),
				'type'     => 'dimension',
				'default' => '',
				'tab' => 'Spacing'
			),
			array(
				'id' => 'padding_bottom',
				'label'    => __( 'Padding Bottom', 'mighty-builder' ),
				'type'     => 'dimension',
				'default' => '',
				'tab' => 'Spacing'
			),
			
		)
	);

	$text_map = apply_filters( 'mb_text_map', $text_map );

	MB_Element::add($text_map);
}