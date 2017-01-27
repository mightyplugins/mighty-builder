<?php

if (!function_exists('mb_oembed_cb')):

function mb_oembed_cb( $atts, $content ) {

	$default = array(
		'url' => '',
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

	$default = apply_filters( 'mb_video_element_atts', $default );

	$atts = shortcode_atts( $default, $atts );

	$style .= (isset($atts['margin_top']) && !empty($atts['margin_top'])) ? 'margin-top:'.$atts['margin_top'].';' : '';
	$style .= (isset($atts['margin_bottom']) && !empty($atts['margin_bottom'])) ? 'margin-bottom:'.$atts['margin_bottom'].';' : '';
	$style .= (isset($atts['margin_left']) && !empty($atts['margin_left'])) ? 'margin-left:'.$atts['margin_left'].';' : '';
	$style .= (isset($atts['margin_right']) && !empty($atts['margin_right'])) ? 'margin-right:'.$atts['margin_right'].';' : '';

	$style_md .= (isset($atts['md_margin_top']) && !empty($atts['md_margin_top'])) ? 'margin-top:'.$atts['md_margin_top'].';' : '';
	$style_md .= (isset($atts['md_margin_bottom']) && !empty($atts['md_margin_bottom'])) ? 'margin-bottom:'.$atts['md_margin_bottom'].';' : '';
	$style_md .= (isset($atts['md_margin_left']) && !empty($atts['md_margin_left'])) ? 'margin-left:'.$atts['md_margin_left'].';' : '';
	$style_md .= (isset($atts['md_margin_right']) && !empty($atts['md_margin_right'])) ? 'margin-right:'.$atts['md_margin_right'].';' : '';


	$style_sm .= (isset($atts['sm_margin_top']) && !empty($atts['sm_margin_top'])) ? 'margin-top:'.$atts['sm_margin_top'].';' : '';
	$style_sm .= (isset($atts['sm_margin_bottom']) && !empty($atts['sm_margin_bottom'])) ? 'margin-bottom:'.$atts['sm_margin_bottom'].';' : '';
	$style_sm .= (isset($atts['sm_margin_left']) && !empty($atts['sm_margin_left'])) ? 'margin-left:'.$atts['sm_margin_left'].';' : '';
	$style_sm .= (isset($atts['sm_margin_right']) && !empty($atts['sm_margin_right'])) ? 'margin-right:'.$atts['sm_margin_right'].';' : '';

	$style_xs .= (isset($atts['xs_margin_top']) && !empty($atts['xs_margin_top'])) ? 'margin-top:'.$atts['xs_margin_top'].';' : '';
	$style_xs .= (isset($atts['xs_margin_bottom']) && !empty($atts['xs_margin_bottom'])) ? 'margin-bottom:'.$atts['xs_margin_bottom'].';' : '';
	$style_xs .= (isset($atts['xs_margin_left']) && !empty($atts['xs_margin_left'])) ? 'margin-left:'.$atts['xs_margin_left'].';' : '';
	$style_xs .= (isset($atts['xs_margin_right']) && !empty($atts['xs_margin_right'])) ? 'margin-right:'.$atts['xs_margin_right'].';' : '';

	$cls = 'video_'.rand(99, 99999);

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
	<div class="embed-responsive embed-responsive-16by9 <?php echo esc_attr($cls); ?>">
		<?php
			if (isset($atts['url']) && !empty($atts['url'])) {
				echo wp_oembed_get($atts['url']);
			}
		?>
	</div>
	<?php

	$output = ob_get_clean();

	$output = apply_filters( 'mb_video_element_output', $output, $atts, $content );
	return $output;
}
add_shortcode( 'mb_oembed','mb_oembed_cb' );

endif;

if (class_exists('MB_Element')) {

	$video_map = array(
		'title' => 'Embed',
		'subtitle' => 'Video, Image, etc with oEmbed',
		'code' => 'mb_oembed',
		'icon' => 'mb mb-video',
		'color' => '#4ad2da',
		'options' => array(
			array(
				'id' => 'url',
				'label'    => __( 'URL', 'mighty-builder' ),
				'subtitle'    => __( 'Lorem ipsum dolor sit amet', 'mighty-builder' ),
				'type'     => 'url',
				'default' => '',
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

	$video_map = apply_filters( 'mb_video_map', $video_map );

	MB_Element::add($video_map);
}