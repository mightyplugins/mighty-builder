<?php

function mb_section_cb( $atts, $content ) {

	$default = array(
		'padding_top' => '50px',
		'padding_bottom' => '50px',
		'padding_left' => '0',
		'padding_right' => '0',
		'bg_color' => '',
		'bg_img' => '',
		'bg_repeat' => '',
		'bg_position' => '',
		'bg_attachment' => '',
		'bg_size' => '',
		'container' => 'fullwidth', // boxed, fluid, fullwidth
		'id' => '',
		'class' => '',
		'force_fullwidth' => 0,
	);

	$atts_default = apply_filters( 'mb_section_element_atts', $default );

	$atts = shortcode_atts( $atts_default, $atts );

	$style = '';

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


	$section_style = '';
	$container_style = '';

	if (isset($atts['container']) && $atts['container'] == 'boxed') {
		$container_style = $style;
	} else {
		$section_style = $style;
	}

	$container_class = 'container';

	if (isset($atts['container']) && $atts['container'] == 'fluid') {
		$container_class = 'container-fluid';
	}

	$sec_cls = array();

	$sec_cls[] = $atts['class'];

	if (((int) $atts['force_fullwidth']) && ($atts['container'] == 'fluid' || $atts['container'] == 'fullwidth')) {
		$sec_cls[] = 'mb-force-fullwidth';
	}

	$output = '';

	ob_start();

	?>
	<section class="<?php echo esc_attr(implode(' ', $sec_cls)); ?>" style="<?php echo esc_attr($section_style); ?>" id="<?php echo esc_attr($atts['id']); ?>">
		<div class="<?php echo esc_attr($container_class); ?>" style="<?php echo esc_attr($container_style); ?>">
			<?php echo do_shortcode( $content ); ?>
		</div>
	</section>
	
	<?php

	$output =  ob_get_clean();

	$output = apply_filters( 'mb_section_element_output', $output, $atts, $content );

	return $output;
}
add_shortcode( 'mb_section','mb_section_cb' );



if (class_exists('MB_Element')) {

	$section_map = array(
		'title' => 'Section',
		'subtitle' => 'Section Element',
		'code' => 'mb_section',
		'hascontent' => true,
		'layout' => true,
		'icon' => '',
		'options' => array(
			array(
				'id' => 'container',
				'label'    => __( 'Container', 'mytheme' ),
				'subtitle'    => __( 'Lorem ipsum dolor sit amet', 'mytheme' ),
				'type'     => 'radio_button',
				'default' => 'fullwidth',
                'choices' => array(
                    'boxed' => 'Boxed',
                    'fluid' => 'Fluid',
                    'fullwidth' => 'Fullwidth'
                ),
                'tab' => 'Settings'
			),
			array(
				'id' => 'force_fullwidth',
				'label'    => __( 'Force Fullwidth', 'mytheme' ),
				'subtitle'    => __( 'Only work with only Fluid and Fullwidth Container', 'mytheme' ),
				'type'     => 'radio',
				'default' => '0',
				'choices' => array(
					'0' => __('Disable', 'mytheme'),
					'1' => __('Enable', 'mytheme'),
				),
				'tab' => 'Settings'
			),
			array(
				'id' => 'id',
				'label'    => __( 'Section ID', 'mytheme' ),
				'subtitle'    => __( 'Lorem ipsum dolor sit amet', 'mytheme' ),
				'type'     => 'text',
				'default' => '',
                'tab' => 'Settings'
			),
			array(
				'id' => 'class',
				'label'    => __( 'Extra Class', 'mytheme' ),
				'subtitle'    => __( 'Lorem ipsum dolor sit amet', 'mytheme' ),
				'type'     => 'text',
				'default' => '',
                'tab' => 'Settings'
			),
			array(
				'id' => 'bg_color',
				'label'    => __( 'Color', 'mytheme' ),
				'subtitle'    => __( 'Lorem ipsum dolor sit amet', 'mytheme' ),
				'type'     => 'color',
				'default' => '',
				'tab' => 'Background'
			),
			array(
				'id' => 'bg_img',
				'label'    => __( 'Image', 'mytheme' ),
				'subtitle'    => __( 'Select image', 'mytheme' ),
				'type'     => 'image',
				'default' => '',
				'tab' => 'Background'
			),
			array(
				'id' => 'bg_repeat',
				'label'    => __( 'Repeat', 'mytheme' ),
				'subtitle'    => __( 'Lorem ipsum dolor sit amet', 'mytheme' ),
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
				'subtitle'    => __( 'Lorem ipsum dolor sit amet', 'mytheme' ),
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
				'subtitle'    => __( 'Lorem ipsum dolor sit amet', 'mytheme' ),
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
				'subtitle'    => __( 'Lorem ipsum dolor sit amet', 'mytheme' ),
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
				'default' => '50px',
				'choices' => array( 'units' => array( 'px', 'em', 'rem' ) ),
				'tab' => 'Spacing'
			),
			array(
				'id' => 'padding_bottom',
				'label'    => __( 'Padding Bottom', 'mytheme' ),
				'type'     => 'dimension',
				'default' => '50px',
				'choices' => array( 'units' => array( 'px', 'em', 'rem' ) ),
				'tab' => 'Spacing'
			),
			array(
				'id' => 'padding_left',
				'label'    => __( 'Padding Left', 'mytheme' ),
				'type'     => 'dimension',
				'default' => '',
				'choices' => array( 'units' => array( 'px', 'em', 'rem' ) ),
				'tab' => 'Spacing'
			),
			array(
				'id' => 'padding_right',
				'label'    => __( 'Padding Right', 'mytheme' ),
				'type'     => 'dimension',
				'default' => '',
				'choices' => array( 'units' => array( 'px', 'em', 'rem' ) ),
				'tab' => 'Spacing'
			)
		)
	);

	$section_map = apply_filters( 'mb_section_map', $section_map );

	MB_Element::add($section_map);
}