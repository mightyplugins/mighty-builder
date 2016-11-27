<?php

function mb_img_cb( $atts, $content ) {

	$default = array(
		'size' => 'full',
		'alt' => '',
		'src' => '',
		'link_to' => 'none',
		'link' => 'none'
	);

	$atts_default = apply_filters( 'mb_image_element_atts', $default );

	$atts = shortcode_atts( $atts_default, $atts );

	$style = '';

	// $style .= (isset($atts['color']) && !empty($atts['color'])) ? 'color:'.$atts['color'].';' : '';
	

	if ($atts['size'] != 'full') {
		$image_id = mb_get_image_id_by_url($atts['src']);

		if ($image_id) {
			$image = wp_get_attachment_image_src( $image_id, $atts['size'] );

			if (isset($image[0]) && !empty($image[0])) {
				$atts['src'] = $image[0];
			}
		}
		
	}


	$output = '';

	ob_start();
	if ($atts['link_to'] == 'self'):
		?>
		<a href="<?php echo esc_url( $atts['src'] ); ?>">
		<?php
	elseif ($atts['link_to'] == 'link'):
		?>
		<a href="<?php echo esc_url( $atts['link'] ); ?>">
		<?php
	endif;
	?>
	<img src="<?php echo esc_url( $atts['src'] ); ?>" alt="<?php echo esc_attr( $atts['alt'] ); ?>">
	
	<?php
	if ($atts['link_to'] == 'self' || $atts['link_to'] == 'link'):
		?>
		</a>
		<?php
	endif;

	$output =  ob_get_clean();

	$output = apply_filters( 'mb_image_element_output', $output, $atts, $content );

	return $output;
}
add_shortcode( 'mb_img','mb_img_cb' );



if (class_exists('CTPB_Element')) {

	$sizes = array('full' => 'Full');

	$_sizes = get_intermediate_image_sizes();
	if (!empty($_sizes)) {
		foreach ($_sizes as $_size) {
			$tmp_size = str_replace('_', ' ', $_size);
			$tmp_size = str_replace('-', ' ', $tmp_size);
			$sizes[$_size] = ucwords($tmp_size);
		}
	}

	$image_map = array(
		'title' => 'Single Image',
		'subtitle' => 'Single Image Element',
		'code' => 'mb_img',
		'icon' => 'fa fa-font',
		'options' => array(
			array(
				'id' => 'src',
				'label'    => __( 'Background Color', 'mytheme' ),
				'subtitle'    => __( 'Lorem ipsum dolor sit amet', 'mytheme' ),
				'type'     => 'image',
				'default' => '',
			),
			array(
				'id' => 'alt',
				'label'    => __( 'Background Color', 'mytheme' ),
				'subtitle'    => __( 'Lorem ipsum dolor sit amet', 'mytheme' ),
				'type'     => 'text',
				'default' => '',
			),
			array(
				'id' => 'size',
				'label'    => __( 'Background Color', 'mytheme' ),
				'subtitle'    => __( 'Lorem ipsum dolor sit amet', 'mytheme' ),
				'type'     => 'select',
				'default' => 'full',
				'choices' => $sizes
			),
			array(
				'id' => 'link_to',
				'label'    => __( 'Background Color', 'mytheme' ),
				'subtitle'    => __( 'Lorem ipsum dolor sit amet', 'mytheme' ),
				'type'     => 'select',
				'default' => 'none',
				'choices' => array(
					'none' => __( 'None', 'mytheme' ),
					'self' => __( 'Self', 'mytheme' ),
					'link' => __( 'Custom Link', 'mytheme' ),
				)
			),
			array(
				'id' => 'link',
				'label'    => __( 'Background Color', 'mytheme' ),
				'subtitle'    => __( 'Lorem ipsum dolor sit amet', 'mytheme' ),
				'type'     => 'url',
				'default' => '',
			),
		)
	);

	$image_map = apply_filters( 'mb_text_map', $image_map );

	CTPB_Element::add($image_map);
}