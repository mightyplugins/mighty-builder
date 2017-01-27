<?php

if (!function_exists('mb_countup_cb')):

function mb_countup_cb( $atts, $content ) {

	$default = array(
		'num' => '100',
		'prefix' => '',
		'suffix' => '',
		'label' => '',
		'icon' => '',
		'color' => '',
		'icon_color' => '',
	);

	$default = apply_filters( 'mb_countup_element_atts', $default );

	$atts = shortcode_atts( $default, $atts );

	$style = '';
	$style_icon = '';

	$style .= (isset($atts['color']) && !empty($atts['color'])) ? 'color:'.$atts['color'].';' : '';

	$style_icon .= (isset($atts['icon_color']) && !empty($atts['icon_color'])) ? 'color:'.$atts['icon_color'].';' : '';




	ob_start();
	?>
	<div class="ct-countup" style="<?php echo esc_attr($style); ?>">
		<?php if(isset($atts['icon']) && !empty($atts['icon'])): ?><div class="ct-countup-icon" style="<?php echo esc_attr($style_icon); ?>"><i class="<?php echo esc_attr( $atts['icon'] ); ?>"></i></div><?php endif; ?>
		<div class="ct-counter-wrap">
			<?php if(isset($atts['prefix']) && !empty($atts['prefix'])): ?><span><?php echo esc_html( $atts['prefix'] ); ?></span><?php endif; ?><span class="ct-counter"><?php echo esc_html( $atts['num'] ); ?></span><?php if(isset($atts['suffix']) && !empty($atts['suffix'])): ?><span><?php echo esc_html( $atts['suffix'] ); ?></span><?php endif; ?>
		</div>
		<?php if(isset($atts['label']) && !empty($atts['label'])): ?><div class="ct-countup-label"><?php echo esc_html( $atts['label'] ); ?></div><?php endif; ?>
	</div>
	<?php

	$output =  ob_get_clean();

	$output = apply_filters( 'mb_countup_element_output', $output, $atts, $content );
	return $output;
}
add_shortcode( 'mb_countup','mb_countup_cb' );

endif;

if (class_exists('MB_Element')) {

	$countup_map = array(
		'title' => 'Count Up',
		'subtitle' => 'Count Up Element',
		'code' => 'mb_countup',
		'icon' => 'mb mb-countup',
		'color' => '#ff9800',
		'options' => array(
			array(
				'id' => 'num',
				'label'    => __( 'Number', 'mighty-builder' ),
				'subtitle'    => __( 'Lorem ipsum dolor sit amet', 'mighty-builder' ),
				'type'     => 'number',
				'default' => '100',
				'tab' => 'Content'
			),
			array(
				'id' => 'prefix',
				'label'    => __( 'Prefix', 'mighty-builder' ),
				'subtitle'    => __( 'Lorem ipsum dolor sit amet', 'mighty-builder' ),
				'type'     => 'text',
				'default' => '',
				'tab' => 'Content'
			),
			array(
				'id' => 'suffix',
				'label'    => __( 'Suffix', 'mighty-builder' ),
				'subtitle'    => __( 'Lorem ipsum dolor sit amet', 'mighty-builder' ),
				'type'     => 'text',
				'default' => '',
				'tab' => 'Content'
			),
			array(
				'id' => 'icon',
				'label'    => __( 'Icon', 'mighty-builder' ),
				'subtitle'    => __( 'Lorem ipsum dolor sit amet', 'mighty-builder' ),
				'type'     => 'icon',
				'default' => '',
				'tab' => 'Content'
			),
			array(
				'id' => 'label',
				'label'    => __( 'Label', 'mighty-builder' ),
				'subtitle'    => __( 'Lorem ipsum dolor sit amet', 'mighty-builder' ),
				'type'     => 'text',
				'default' => '',
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
				'id' => 'icon_color',
				'label'    => __( 'Icon Color', 'mighty-builder' ),
				'subtitle'    => __( 'Heading Text Color', 'mighty-builder' ),
				'type'     => 'color',
				'default' => '',
				'tab' => 'Style'
			),
		)
	);

	$countup_map = apply_filters( 'mb_countup_map', $countup_map );

	MB_Element::add($countup_map);
}