<?php

if (!function_exists('mb_heading_cb')):

function mb_heading_cb( $atts, $content ) {

	global $mb_google_fonts;

	$default = array(
		'padding_top' => '',
		'padding_bottom' => '',
		'padding_left' => '',
		'padding_right' => '',
		'color' => '',
		'align' => '',
		'tag' => 'h1',
		'haeding_font-family' => '',
		'haeding_font-weight' => '',

		'font_size' => '',
		'md_font_size' => '',
		'sm_font_size' => '',
		'xs_font_size' => '',

		'line_height' => '',
		'md_line_height' => '',
		'sm_line_height' => '',
		'xs_line_height' => '',

		'padding_top' => '',
		'md_padding_top' => '',
		'sm_padding_top' => '',
		'xs_padding_top' => '',

		'padding_bottom' => '',
		'md_padding_bottom' => '',
		'sm_padding_bottom' => '',
		'xs_padding_bottom' => '',

		'padding_left' => '',
		'md_padding_left' => '',
		'sm_padding_left' => '',
		'xs_padding_left' => '',

		'padding_right' => '',
		'md_padding_right' => '',
		'sm_padding_right' => '',
		'xs_padding_right' => '',

		'margin_top' => '',
		'md_margin_top' => '',
		'sm_margin_top' => '',
		'xs_margin_top' => '',

		'margin_bottom' => '',
		'md_margin_bottom' => '',
		'sm_margin_bottom' => '',
		'xs_margin_bottom' => '',

		'margin_left' => '',
		'md_margin_left' => '',
		'sm_margin_left' => '',
		'xs_margin_left' => '',

		'margin_right' => '',
		'md_margin_right' => '',
		'sm_margin_right' => '',
		'xs_margin_right' => '',
	);

	$atts_default = apply_filters( 'mb_heading_element_atts', $default );

	$atts = shortcode_atts( $atts_default, $atts );

	$all_fonts = CTF_Help::get_google_font_json();

	$all_fonts = json_decode($all_fonts, true);

	$font_weights = $all_fonts[$atts['haeding_font-family']];


	if (!isset($mb_google_fonts[$atts['haeding_font-family']]) || !is_array($mb_google_fonts[$atts['haeding_font-family']])) {
		$mb_google_fonts[$atts['haeding_font-family']] = array();
	}

	if (isset($atts['haeding_font-weight']) && !empty($atts['haeding_font-weight']) && isset($mb_google_fonts[$atts['haeding_font-family']]) && !in_array($atts['haeding_font-weight'], $mb_google_fonts[$atts['haeding_font-family']]) ) {
		$mb_google_fonts[$atts['haeding_font-family']][] = $atts['haeding_font-weight'];
	}



	$style = '';
	$style_md = '';
	$style_sm = '';
	$style_xs = '';

	$style .= (isset($atts['color']) && !empty($atts['color'])) ? 'color:'.$atts['color'].';' : '';
	$style .= (isset($atts['align']) && !empty($atts['align'])) ? 'text-align:'.$atts['align'].';' : '';

	$style .= (isset($atts['padding_top']) && !empty($atts['padding_top'])) ? 'padding-top:'.$atts['padding_top'].';' : '';
	$style .= (isset($atts['padding_bottom']) && !empty($atts['padding_bottom'])) ? 'padding-bottom:'.$atts['padding_bottom'].';' : '';
	$style .= (isset($atts['padding_left']) && !empty($atts['padding_left'])) ? 'padding-left:'.$atts['padding_left'].';' : '';
	$style .= (isset($atts['padding_right']) && !empty($atts['padding_right'])) ? 'padding-right:'.$atts['padding_right'].';' : '';

	$style .= (isset($atts['margin_top']) && !empty($atts['margin_top'])) ? 'margin-top:'.$atts['margin_top'].';' : '';
	$style .= (isset($atts['margin_bottom']) && !empty($atts['margin_bottom'])) ? 'margin-bottom:'.$atts['margin_bottom'].';' : '';
	$style .= (isset($atts['margin_left']) && !empty($atts['margin_left'])) ? 'margin-left:'.$atts['margin_left'].';' : '';
	$style .= (isset($atts['margin_right']) && !empty($atts['margin_right'])) ? 'margin-right:'.$atts['margin_right'].';' : '';

	$style .= (isset($atts['haeding_font-family']) && !empty($atts['haeding_font-family'])) ? 'font-family:'.$atts['haeding_font-family'].';' : '';
	$style .= (isset($atts['haeding_font-weight']) && !empty($atts['haeding_font-weight'])) ? 'font-weight:'.$atts['haeding_font-weight'].';' : '';
	$style .= (isset($atts['font_size']) && !empty($atts['font_size'])) ? 'font-size:'.$atts['font_size'].';' : '';
	$style .= (isset($atts['line_height']) && !empty($atts['line_height'])) ? 'line-height:'.$atts['line_height'].';' : '';
	$style .= (isset($atts['line_height']) && !empty($atts['line_height'])) ? 'line-height:'.$atts['line_height'].';' : '';

	$style_md .= (isset($atts['md_font_size']) && !empty($atts['md_font_size'])) ? 'font-size:'.$atts['md_font_size'].';' : '';
	$style_md .= (isset($atts['md_line_height']) && !empty($atts['md_line_height'])) ? 'line-height:'.$atts['md_line_height'].';' : '';

	$style_md .= (isset($atts['md_padding_top']) && !empty($atts['md_padding_top'])) ? 'padding-top:'.$atts['md_padding_top'].';' : '';
	$style_md .= (isset($atts['md_padding_bottom']) && !empty($atts['md_padding_bottom'])) ? 'padding-bottom:'.$atts['md_padding_bottom'].';' : '';
	$style_md .= (isset($atts['md_padding_left']) && !empty($atts['md_padding_left'])) ? 'padding-left:'.$atts['md_padding_left'].';' : '';
	$style_md .= (isset($atts['md_padding_right']) && !empty($atts['md_padding_right'])) ? 'padding-right:'.$atts['md_padding_right'].';' : '';

	$style_md .= (isset($atts['md_margin_top']) && !empty($atts['md_margin_top'])) ? 'margin-top:'.$atts['md_margin_top'].';' : '';
	$style_md .= (isset($atts['md_margin_bottom']) && !empty($atts['md_margin_bottom'])) ? 'margin-bottom:'.$atts['md_margin_bottom'].';' : '';
	$style_md .= (isset($atts['md_margin_left']) && !empty($atts['md_margin_left'])) ? 'margin-left:'.$atts['md_margin_left'].';' : '';
	$style_md .= (isset($atts['md_margin_right']) && !empty($atts['md_margin_right'])) ? 'margin-right:'.$atts['md_margin_right'].';' : '';

	$style_sm .= (isset($atts['sm_font_size']) && !empty($atts['sm_font_size'])) ? 'font-size:'.$atts['sm_font_size'].';' : '';
	$style_sm .= (isset($atts['sm_line_height']) && !empty($atts['sm_line_height'])) ? 'line-height:'.$atts['sm_line_height'].';' : '';

	$style_sm .= (isset($atts['sm_padding_top']) && !empty($atts['sm_padding_top'])) ? 'padding-top:'.$atts['sm_padding_top'].';' : '';
	$style_sm .= (isset($atts['sm_padding_bottom']) && !empty($atts['sm_padding_bottom'])) ? 'padding-bottom:'.$atts['sm_padding_bottom'].';' : '';
	$style_sm .= (isset($atts['sm_padding_left']) && !empty($atts['sm_padding_left'])) ? 'padding-left:'.$atts['sm_padding_left'].';' : '';
	$style_sm .= (isset($atts['sm_padding_right']) && !empty($atts['sm_padding_right'])) ? 'padding-right:'.$atts['sm_padding_right'].';' : '';

	$style_sm .= (isset($atts['sm_margin_top']) && !empty($atts['sm_margin_top'])) ? 'margin-top:'.$atts['sm_margin_top'].';' : '';
	$style_sm .= (isset($atts['sm_margin_bottom']) && !empty($atts['sm_margin_bottom'])) ? 'margin-bottom:'.$atts['sm_margin_bottom'].';' : '';
	$style_sm .= (isset($atts['sm_margin_left']) && !empty($atts['sm_margin_left'])) ? 'margin-left:'.$atts['sm_margin_left'].';' : '';
	$style_sm .= (isset($atts['sm_margin_right']) && !empty($atts['sm_margin_right'])) ? 'margin-right:'.$atts['sm_margin_right'].';' : '';

	$style_xs .= (isset($atts['xs_font_size']) && !empty($atts['xs_font_size'])) ? 'font-size:'.$atts['xs_font_size'].';' : '';
	$style_xs .= (isset($atts['xs_line_height']) && !empty($atts['xs_line_height'])) ? 'line-height:'.$atts['xs_line_height'].';' : '';

	$style_xs .= (isset($atts['xs_padding_top']) && !empty($atts['xs_padding_top'])) ? 'padding-top:'.$atts['xs_padding_top'].';' : '';
	$style_xs .= (isset($atts['xs_padding_bottom']) && !empty($atts['xs_padding_bottom'])) ? 'padding-bottom:'.$atts['xs_padding_bottom'].';' : '';
	$style_xs .= (isset($atts['xs_padding_left']) && !empty($atts['xs_padding_left'])) ? 'padding-left:'.$atts['xs_padding_left'].';' : '';
	$style_xs .= (isset($atts['xs_padding_right']) && !empty($atts['xs_padding_right'])) ? 'padding-right:'.$atts['xs_padding_right'].';' : '';

	$style_xs .= (isset($atts['xs_margin_top']) && !empty($atts['xs_margin_top'])) ? 'margin-top:'.$atts['xs_margin_top'].';' : '';
	$style_xs .= (isset($atts['xs_margin_bottom']) && !empty($atts['xs_margin_bottom'])) ? 'margin-bottom:'.$atts['xs_margin_bottom'].';' : '';
	$style_xs .= (isset($atts['xs_margin_left']) && !empty($atts['xs_margin_left'])) ? 'margin-left:'.$atts['xs_margin_left'].';' : '';
	$style_xs .= (isset($atts['xs_margin_right']) && !empty($atts['xs_margin_right'])) ? 'margin-right:'.$atts['xs_margin_right'].';' : '';

	$cls = 'heading_'.rand(99, 99999);


	$output = '';

	ob_start();

	?>
	<style type="text/css">
		.<?php echo esc_attr( $cls ); ?>{
			<?php echo esc_attr($style); ?>
		}
		@media (min-width: 992px) and (max-width: 1199px) {
			.<?php echo esc_attr( $cls ); ?>{
				<?php echo esc_attr($style_md); ?>
			}
		}
		@media (min-width: 768px) and (max-width: 991px ){
			.<?php echo esc_attr( $cls ); ?>{
				<?php echo esc_attr($style_sm); ?>
			}
		}
		@media (max-width: 767px) {
			.<?php echo esc_attr( $cls ); ?>{
				<?php echo esc_attr($style_xs); ?>
			}
		}
	</style>
	<<?php echo esc_attr( $atts['tag'] ); ?> class="<?php echo esc_attr($cls); ?>"><?php echo do_shortcode( $content ); ?></<?php echo esc_attr( $atts['tag'] ); ?>>
	
	<?php

	$output =  ob_get_clean();

	$output = apply_filters( 'mb_heading_element_output', $output, $atts, $content );

	return $output;
}
add_shortcode( 'mb_heading','mb_heading_cb' );

endif;

if (function_exists('mb_add_map')) {

	$heading_map = array(
		'title' => 'Heading',
		'subtitle' => 'Heading Element',
		'code' => 'mb_heading',
		'hascontent' => true,
		'icon' => 'mb mb-heading',
		'color' => '#52d92e',
		'options' => array(
			array(
				'id' => 'heading_content',
				'label'    => __( 'Text', 'mighty-builder' ),
				'subtitle'    => __( 'Heading Text', 'mighty-builder' ),
				'type'     => 'text',
				'default' => '',
				'roll' => 'content',
				'tab' => 'Content'
			),
			array(
				'id' => 'color',
				'label'    => __( 'Text Color', 'mighty-builder' ),
				'subtitle'    => __( 'Heading Text Color', 'mighty-builder' ),
				'type'     => 'color',
				'default' => '',
				'tab' => 'Style'
			),
			array(
				'id' => 'tag',
				'label'    => __( 'Heading Tag', 'mighty-builder' ),
				'subtitle'    => __( 'Select Heading Type', 'mighty-builder' ),
				'type'     => 'select',
				'default' => 'h1',
				'choices' => array(
                    'h1' => 'Heading 1',
                    'h2' => 'Heading 2',
                    'h3' => 'Heading 3',
                    'h4' => 'Heading 4',
                    'h5' => 'Heading 5',
                    'h6' => 'Heading 6',
                    'p' => 'Paragraph',
                ),
                'tab' => 'Content'
			),
			array(
                'id' => 'haeding',
                'label'    => __( 'Font', 'mighty-builder' ),
                'subtitle'    => __( 'Select font family.', 'mighty-builder' ),
                'type'     => 'google_font',
                'default' => array(),
                'choices' => array(
                	'font-size' => false,
                	'line-height' => false
                ),
                'tab' => 'Style'
            ),
            array(
				'id' => 'font_size',
				'label'    => __( 'Font Size', 'mighty-builder' ),
				'subtitle'    => __( 'Heading font size', 'mighty-builder' ),
				'type'     => 'dimension',
				'default' => '',
				'responsive' => true,
				'tab' => 'Style'
			),
			array(
				'id' => 'line_height',
				'label'    => __( 'Line Height', 'mighty-builder' ),
				'subtitle'    => __( 'Heading text lign height', 'mighty-builder' ),
				'type'     => 'dimension',
				'default' => '',
				'responsive' => true,
				'tab' => 'Style'
			),
			array(
				'id' => 'align',
				'label'    => __( 'Heading Align', 'mighty-builder' ),
				'subtitle'    => __( 'Text align. Default: left', 'mighty-builder' ),
				'type'     => 'text_align',
				'default' => '',
				'choices' => array( 'justify' => '0' ),
				'tab' => 'Style'
			),
			array(
				'id' => 'padding_top',
				'label'    => __( 'Padding Top', 'mighty-builder' ),
				'type'     => 'dimension',
				'default' => '',
				'responsive' => true,
				'tab' => 'Spacing'
			),
			array(
				'id' => 'padding_bottom',
				'label'    => __( 'Padding Bottom', 'mighty-builder' ),
				'type'     => 'dimension',
				'default' => '',
				'responsive' => true,
				'tab' => 'Spacing'
			),
			array(
				'id' => 'padding_left',
				'label'    => __( 'Padding Left', 'mighty-builder' ),
				'type'     => 'dimension',
				'default' => '',
				'responsive' => true,
				'tab' => 'Spacing'
			),
			array(
				'id' => 'padding_right',
				'label'    => __( 'Padding Right', 'mighty-builder' ),
				'type'     => 'dimension',
				'default' => '',
				'responsive' => true,
				'tab' => 'Spacing'
			),

			array(
				'id' => 'margin_top',
				'label'    => __( 'Margin Top', 'mighty-builder' ),
				'type'     => 'dimension',
				'default' => '',
				'responsive' => true,
				'tab' => 'Spacing'
			),
			array(
				'id' => 'margin_bottom',
				'label'    => __( 'Margin Bottom', 'mighty-builder' ),
				'type'     => 'dimension',
				'default' => '',
				'responsive' => true,
				'tab' => 'Spacing'
			),
			array(
				'id' => 'margin_left',
				'label'    => __( 'Margin Left', 'mighty-builder' ),
				'type'     => 'dimension',
				'default' => '',
				'responsive' => true,
				'tab' => 'Spacing'
			),
			array(
				'id' => 'margin_right',
				'label'    => __( 'Margin Right', 'mighty-builder' ),
				'type'     => 'dimension',
				'default' => '',
				'responsive' => true,
				'tab' => 'Spacing'
			),
            
		)
	);


	mb_add_map($heading_map);
}