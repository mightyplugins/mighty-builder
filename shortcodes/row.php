<?php

function mb_row_cb( $atts, $content ) {

	$default = array(
		'bg_color' => '',
		'bg_img' => '',
		'bg_repeat' => '',
		'bg_position' => '',
		'bg_attachment' => '',
		'bg_size' => '',
		'id' => '',
		'class' => '',

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

	$atts_default = apply_filters( 'mb_row_element_atts', $default );

	$atts = shortcode_atts( $atts_default, $atts );

	$style = '';
	$style_md = '';
	$style_sm = '';
	$style_xs = '';

	$style .= (isset($atts['bg_color']) && !empty($atts['bg_color'])) ? 'background-color:'.$atts['bg_color'].';' : '';
	$style .= (isset($atts['bg_img']) && !empty($atts['bg_img'])) ? 'background-image: url('.$atts['bg_img'].');' : '';
	$style .= (isset($atts['bg_repeat']) && !empty($atts['bg_repeat'])) ? 'background-repeat: '.$atts['bg_repeat'].';' : '';
	$style .= (isset($atts['bg_position']) && !empty($atts['bg_position'])) ? 'background-position: '.$atts['bg_position'].';' : '';
	$style .= (isset($atts['bg_attachment']) && !empty($atts['bg_attachment'])) ? 'background-attachment: '.$atts['bg_attachment'].';' : '';
	$style .= (isset($atts['bg_size']) && !empty($atts['bg_size'])) ? 'background-size: '.$atts['bg_size'].';' : '';

	$style .= (isset($atts['padding_top']) && !empty($atts['padding_top'])) ? 'padding-top:'.$atts['padding_top'].';' : '';
	$style .= (isset($atts['padding_bottom']) && !empty($atts['padding_bottom'])) ? 'padding-bottom:'.$atts['padding_bottom'].';' : '';
	$style .= (isset($atts['padding_left']) && !empty($atts['padding_left'])) ? 'padding-left:'.$atts['padding_left'].';' : '';
	$style .= (isset($atts['padding_right']) && !empty($atts['padding_right'])) ? 'padding-right:'.$atts['padding_right'].';' : '';

	$style .= (isset($atts['margin_top']) && !empty($atts['margin_top'])) ? 'margin-top:'.$atts['margin_top'].';' : '';
	$style .= (isset($atts['margin_bottom']) && !empty($atts['margin_bottom'])) ? 'margin-bottom:'.$atts['margin_bottom'].';' : '';
	$style .= (isset($atts['margin_left']) && !empty($atts['margin_left'])) ? 'margin-left:'.$atts['margin_left'].';' : '';
	$style .= (isset($atts['margin_right']) && !empty($atts['margin_right'])) ? 'margin-right:'.$atts['margin_right'].';' : '';

	$style_md .= (isset($atts['md_padding_top']) && !empty($atts['md_padding_top'])) ? 'padding-top:'.$atts['md_padding_top'].';' : '';
	$style_md .= (isset($atts['md_padding_bottom']) && !empty($atts['md_padding_bottom'])) ? 'padding-bottom:'.$atts['md_padding_bottom'].';' : '';
	$style_md .= (isset($atts['md_padding_left']) && !empty($atts['md_padding_left'])) ? 'padding-left:'.$atts['md_padding_left'].';' : '';
	$style_md .= (isset($atts['md_padding_right']) && !empty($atts['md_padding_right'])) ? 'padding-right:'.$atts['md_padding_right'].';' : '';

	$style_md .= (isset($atts['md_margin_top']) && !empty($atts['md_margin_top'])) ? 'margin-top:'.$atts['md_margin_top'].';' : '';
	$style_md .= (isset($atts['md_margin_bottom']) && !empty($atts['md_margin_bottom'])) ? 'margin-bottom:'.$atts['md_margin_bottom'].';' : '';
	$style_md .= (isset($atts['md_margin_left']) && !empty($atts['md_margin_left'])) ? 'margin-left:'.$atts['md_margin_left'].';' : '';
	$style_md .= (isset($atts['md_margin_right']) && !empty($atts['md_margin_right'])) ? 'margin-right:'.$atts['md_margin_right'].';' : '';

	$style_sm .= (isset($atts['sm_padding_top']) && !empty($atts['sm_padding_top'])) ? 'padding-top:'.$atts['sm_padding_top'].';' : '';
	$style_sm .= (isset($atts['sm_padding_bottom']) && !empty($atts['sm_padding_bottom'])) ? 'padding-bottom:'.$atts['sm_padding_bottom'].';' : '';
	$style_sm .= (isset($atts['sm_padding_left']) && !empty($atts['sm_padding_left'])) ? 'padding-left:'.$atts['sm_padding_left'].';' : '';
	$style_sm .= (isset($atts['sm_padding_right']) && !empty($atts['sm_padding_right'])) ? 'padding-right:'.$atts['sm_padding_right'].';' : '';

	$style_sm .= (isset($atts['sm_margin_top']) && !empty($atts['sm_margin_top'])) ? 'margin-top:'.$atts['sm_margin_top'].';' : '';
	$style_sm .= (isset($atts['sm_margin_bottom']) && !empty($atts['sm_margin_bottom'])) ? 'margin-bottom:'.$atts['sm_margin_bottom'].';' : '';
	$style_sm .= (isset($atts['sm_margin_left']) && !empty($atts['sm_margin_left'])) ? 'margin-left:'.$atts['sm_margin_left'].';' : '';
	$style_sm .= (isset($atts['sm_margin_right']) && !empty($atts['sm_margin_right'])) ? 'margin-right:'.$atts['sm_margin_right'].';' : '';

	$style_xs .= (isset($atts['xs_padding_top']) && !empty($atts['xs_padding_top'])) ? 'padding-top:'.$atts['xs_padding_top'].';' : '';
	$style_xs .= (isset($atts['xs_padding_bottom']) && !empty($atts['xs_padding_bottom'])) ? 'padding-bottom:'.$atts['xs_padding_bottom'].';' : '';
	$style_xs .= (isset($atts['xs_padding_left']) && !empty($atts['xs_padding_left'])) ? 'padding-left:'.$atts['xs_padding_left'].';' : '';
	$style_xs .= (isset($atts['xs_padding_right']) && !empty($atts['xs_padding_right'])) ? 'padding-right:'.$atts['xs_padding_right'].';' : '';

	$style_xs .= (isset($atts['xs_margin_top']) && !empty($atts['xs_margin_top'])) ? 'margin-top:'.$atts['xs_margin_top'].';' : '';
	$style_xs .= (isset($atts['xs_margin_bottom']) && !empty($atts['xs_margin_bottom'])) ? 'margin-bottom:'.$atts['xs_margin_bottom'].';' : '';
	$style_xs .= (isset($atts['xs_margin_left']) && !empty($atts['xs_margin_left'])) ? 'margin-left:'.$atts['xs_margin_left'].';' : '';
	$style_xs .= (isset($atts['xs_margin_right']) && !empty($atts['xs_margin_right'])) ? 'margin-right:'.$atts['xs_margin_right'].';' : '';

	$cls = 'mb_row_'.rand(99, 99999);

	$class = array();

	$class[] = $cls;

	if (isset($atts['class']) && !empty($atts['class'])) {
		$class[] = $atts['class'];
	}

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
	<div class="row <?php echo esc_attr( implode(' ', $class) ); ?>"  id="<?php echo esc_attr($atts['id']); ?>">
		<?php echo do_shortcode( $content ); ?>
	</div>
	
	<?php

	$output =  ob_get_clean();

	$output = apply_filters( 'mb_row_element_output', $output, $atts, $content );

	return $output;
}
add_shortcode( 'mb_row','mb_row_cb' );



if (class_exists('MB_Element')) {

	$row_map = array(
		'title' => 'row',
		'subtitle' => 'row Element',
		'code' => 'mb_row',
		'hascontent' => true,
		'layout' => true,
		'icon' => '',
		'options' => array(
			array(
				'id' => 'id',
				'label'    => __( 'Row ID', 'mytheme' ),
				'type'     => 'text',
				'default' => '',
                'tab' => 'Settings'
			),
			array(
				'id' => 'class',
				'label'    => __( 'Row Extra Class', 'mytheme' ),
				'type'     => 'text',
				'default' => '',
                'tab' => 'Settings'
			),
			array(
				'id' => 'bg_color',
				'label'    => __( 'Background Color', 'mytheme' ),
				'type'     => 'color',
				'default' => '',
				'tab' => 'Background'
			),
			array(
				'id' => 'bg_img',
				'label'    => __( 'Image', 'mytheme' ),
				'subtitle'    => __( 'Background image', 'mytheme' ),
				'type'     => 'image',
				'default' => '',
				'tab' => 'Background'
			),
			array(
				'id' => 'bg_repeat',
				'label'    => __( 'Repeat', 'mytheme' ),
				'subtitle'    => __( 'Background Repeat', 'mytheme' ),
				'type'     => 'select',
				'default' => '',
                'choices' => array(
                    'repeat' => 'Repeat',
                    'no-repeat' => 'No Repeat',
                    'repeat-x' => 'Repeat Horizontally',
                    'repeat-y' => 'Repeat Vertically',
                ),
                'tab' => 'Background'
			),
			array(
				'id' => 'bg_size',
				'label'    => __( 'Size', 'mytheme' ),
				'subtitle'    => __( 'Background Size', 'mytheme' ),
				'type'     => 'select',
				'default' => '',
                'choices' => array(
                    'auto' => 'Auto',
                    'cover' => 'Cover',
                    'contain' => 'Contain',
                ),
                'tab' => 'Background'
			),
			array(
				'id' => 'bg_position',
				'label'    => __( 'Position', 'mytheme' ),
				'subtitle'    => __( 'Background Position', 'mytheme' ),
				'type'     => 'select',
				'default' => '',
                'choices' => array(
                    'left top' => 'Left Top',
					'left center' => 'Left Center',
					'left bottom' => 'Left Bottom',
					'right top' => 'Right Top',
					'right center' => 'Right Center',
					'right bottom' => 'Right Bottom',
					'center top' => 'Center Top',
					'center center' => 'Center Center',
					'center bottom' => 'Center Bottom',
                ),
                'tab' => 'Background'
			),
			array(
				'id' => 'bg_attachment',
				'label'    => __( 'Attachment', 'mytheme' ),
				'subtitle'    => __( 'Background Attachment', 'mytheme' ),
				'type'     => 'select',
				'default' => '',
                'choices' => array(
                    'scroll' => 'Scroll',
                    'fixed' => 'Fixed',
                ),
                'tab' => 'Background'
			),

			array(
				'id' => 'padding_top',
				'label'    => __( 'Padding Top', 'mytheme' ),
				'type'     => 'dimension',
				'default' => '',
				'choices' => array( 'units' => array( 'px', 'em', 'rem' ) ),
				'tab' => 'Spacing',
				'responsive' => true,
			),
			array(
				'id' => 'padding_bottom',
				'label'    => __( 'Padding Bottom', 'mytheme' ),
				'type'     => 'dimension',
				'default' => '',
				'choices' => array( 'units' => array( 'px', 'em', 'rem' ) ),
				'tab' => 'Spacing',
				'responsive' => true,
			),
			array(
				'id' => 'padding_left',
				'label'    => __( 'Padding Left', 'mytheme' ),
				'type'     => 'dimension',
				'default' => '',
				'choices' => array( 'units' => array( 'px', 'em', 'rem' ) ),
				'tab' => 'Spacing',
				'responsive' => true,
			),
			array(
				'id' => 'padding_right',
				'label'    => __( 'Padding Right', 'mytheme' ),
				'type'     => 'dimension',
				'default' => '',
				'choices' => array( 'units' => array( 'px', 'em', 'rem' ) ),
				'tab' => 'Spacing',
				'responsive' => true,
			),
			array(
				'id' => 'margin_top',
				'label'    => __( 'Margin Top', 'mytheme' ),
				'type'     => 'dimension',
				'default' => '',
				'responsive' => true,
				'tab' => 'Spacing'
			),
			array(
				'id' => 'margin_bottom',
				'label'    => __( 'Margin Bottom', 'mytheme' ),
				'type'     => 'dimension',
				'default' => '',
				'responsive' => true,
				'tab' => 'Spacing'
			),
			array(
				'id' => 'margin_left',
				'label'    => __( 'Margin Left', 'mytheme' ),
				'type'     => 'dimension',
				'default' => '',
				'responsive' => true,
				'tab' => 'Spacing'
			),
			array(
				'id' => 'margin_right',
				'label'    => __( 'Margin Right', 'mytheme' ),
				'type'     => 'dimension',
				'default' => '',
				'responsive' => true,
				'tab' => 'Spacing'
			),
		)
	);

	$row_map = apply_filters( 'mb_row_map', $row_map );

	MB_Element::add($row_map);
}