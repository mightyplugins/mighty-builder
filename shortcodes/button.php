<?php

if (!function_exists('mb_btn_cb')):

function mb_btn_cb( $atts, $content ) {

	$default = array(
		'btn_type' => 'info',
		'size' => '',
		'align' => '',
		'link' => '',

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

	$atts_default = apply_filters( 'mb_btn_element_atts', $default );

	$atts = shortcode_atts( $atts_default, $atts );


	$style_container = '';

	$style_container .= (isset($atts['align']) && !empty($atts['align'])) ? 'text-align:'.$atts['align'].';' : '';

	$style_container .= (isset($atts['margin_top']) && !empty($atts['margin_top'])) ? 'margin-top:'.$atts['margin_top'].';' : '';
	$style_container .= (isset($atts['margin_bottom']) && !empty($atts['margin_bottom'])) ? 'margin-bottom:'.$atts['margin_bottom'].';' : '';
	$style_container .= (isset($atts['margin_left']) && !empty($atts['margin_left'])) ? 'margin-left:'.$atts['margin_left'].';' : '';
	$style_container .= (isset($atts['margin_right']) && !empty($atts['margin_right'])) ? 'margin-right:'.$atts['margin_right'].';' : '';

	$style_container_md .= (isset($atts['md_margin_top']) && !empty($atts['md_margin_top'])) ? 'margin-top:'.$atts['md_margin_top'].';' : '';
	$style_container_md .= (isset($atts['md_margin_bottom']) && !empty($atts['md_margin_bottom'])) ? 'margin-bottom:'.$atts['md_margin_bottom'].';' : '';
	$style_container_md .= (isset($atts['md_margin_left']) && !empty($atts['md_margin_left'])) ? 'margin-left:'.$atts['md_margin_left'].';' : '';
	$style_container_md .= (isset($atts['md_margin_right']) && !empty($atts['md_margin_right'])) ? 'margin-right:'.$atts['md_margin_right'].';' : '';


	$style_container_sm .= (isset($atts['sm_margin_top']) && !empty($atts['sm_margin_top'])) ? 'margin-top:'.$atts['sm_margin_top'].';' : '';
	$style_container_sm .= (isset($atts['sm_margin_bottom']) && !empty($atts['sm_margin_bottom'])) ? 'margin-bottom:'.$atts['sm_margin_bottom'].';' : '';
	$style_container_sm .= (isset($atts['sm_margin_left']) && !empty($atts['sm_margin_left'])) ? 'margin-left:'.$atts['sm_margin_left'].';' : '';
	$style_container_sm .= (isset($atts['sm_margin_right']) && !empty($atts['sm_margin_right'])) ? 'margin-right:'.$atts['sm_margin_right'].';' : '';

	$style_container_xs .= (isset($atts['xs_margin_top']) && !empty($atts['xs_margin_top'])) ? 'margin-top:'.$atts['xs_margin_top'].';' : '';
	$style_container_xs .= (isset($atts['xs_margin_bottom']) && !empty($atts['xs_margin_bottom'])) ? 'margin-bottom:'.$atts['xs_margin_bottom'].';' : '';
	$style_container_xs .= (isset($atts['xs_margin_left']) && !empty($atts['xs_margin_left'])) ? 'margin-left:'.$atts['xs_margin_left'].';' : '';
	$style_container_xs .= (isset($atts['xs_margin_right']) && !empty($atts['xs_margin_right'])) ? 'margin-right:'.$atts['xs_margin_right'].';' : '';

	$cls = 'btn_cont_'.rand(99, 99999);


	$output = '';

	ob_start();

	?>
	<style type="text/css">
		.<?php echo esc_attr( $cls ); ?>{
			<?php echo esc_attr($style_container); ?>
		}
		@media (min-width: 992px) and (max-width: 1199px) {
			.<?php echo esc_attr( $cls ); ?>{
				<?php echo esc_attr($style_container_md); ?>
			}
		}
		@media (min-width: 768px) and (max-width: 991px ){
			.<?php echo esc_attr( $cls ); ?>{
				<?php echo esc_attr($style_container_sm); ?>
			}
		}
		@media (max-width: 767px) {
			.<?php echo esc_attr( $cls ); ?>{
				<?php echo esc_attr($style_container_xs); ?>
			}
		}
		
		
	</style>
	<div class="mb-btn-container <?php echo esc_attr($cls); ?>">
		<a href="<?php echo esc_url( $atts['link'] ); ?>" class="btn btn-<?php echo esc_attr( $atts['btn_type'] ); ?> <?php echo esc_attr( $atts['size'] ); ?>" role="button">
			<?php echo do_shortcode( $content ); ?>
		</a>
	</div>
	
	<?php

	$output =  ob_get_clean();

	$output = apply_filters( 'mb_btn_element_output', $output, $atts, $content );

	return $output;
}
add_shortcode( 'mb_btn','mb_btn_cb' );

endif;


if (function_exists('mb_add_map')) {

	$btn_map = array(
		'title' => 'Button',
		'subtitle' => 'Button Element',
		'code' => 'mb_btn',
		'hascontent' => true,
		'icon' => 'mb mb-button',
		'color' => '#6ec3ff',
		'options' => array(
			array(
				'id' => 'btn_content',
				'label'    => __( 'Text', 'mighty-builder' ),
				'subtitle'    => __( 'Button text', 'mighty-builder' ),
				'type'     => 'text',
				'default' => '',
				'roll' => 'content',
				'tab' => 'General'
			),
			array(
				'id' => 'link',
				'label'    => __( 'Link/URL', 'mighty-builder' ),
				'subtitle'    => __( 'Button link', 'mighty-builder' ),
				'type'     => 'text',
				'default' => '',
				'tab' => 'General'
			),
			array(
				'id' => 'btn_type',
				'label'    => __( 'Type', 'mighty-builder' ),
				'subtitle'    => __( 'Select button style', 'mighty-builder' ),
				'type'     => 'select',
				'default' => 'default',
				'choices' => array(
					'default' => __('Default', 'mighty-builder'),
					'primary' => __('Primary', 'mighty-builder'),
					'success' => __('Success', 'mighty-builder'),
					'info' => __('Info', 'mighty-builder'),
					'warning' => __('Warning', 'mighty-builder'),
					'danger' => __('Danger', 'mighty-builder'),
					'link' => __('Link', 'mighty-builder'),
				),
				'tab' => 'General'
			),
			array(
				'id' => 'size',
				'label'    => __( 'Size', 'mighty-builder' ),
				'subtitle'    => __( 'Select button size', 'mighty-builder' ),
				'type'     => 'select',
				'default' => 'btn-nm',
				'choices' => array(
					'btn-xs' => __('Extra Small', 'mighty-builder'),
					'btn-sm' => __('Small', 'mighty-builder'),
					'btn-nm' => __('Normal', 'mighty-builder'),
					'btn-lg' => __('Large', 'mighty-builder'),
				),
				'tab' => 'General'
			),
			array(
				'id' => 'align',
				'label'    => __( 'Align', 'mighty-builder' ),
				'subtitle'    => __( 'Icon alignment', 'mighty-builder' ),
				'type'     => 'text_align',
				'default' => 'left',
				'choices' => array( 'justify' => '0' ),
				'tab' => 'General'
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
	
	mb_add_map($btn_map);
}