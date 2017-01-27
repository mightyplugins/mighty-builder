<?php

function mb_img_cb( $atts, $content ) {

	$default = array(
		'size' => 'full',
		'alt' => '',
		'title' => '',
		'src' => '',
		'link_to' => 'none',
		'link' => 'none',
		'align' => ''
	);

	$atts_default = apply_filters( 'mb_image_element_atts', $default );

	$atts = shortcode_atts( $atts_default, $atts );

	$style = '';

	$style .= (isset($atts['align']) && !empty($atts['align'])) ? 'text-align:'.$atts['align'].';' : '';
	

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
	?>
	<div style="<?php echo esc_attr($style); ?>">
	<?php
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
	<img src="<?php echo esc_url( $atts['src'] ); ?>" alt="<?php echo esc_attr( $atts['alt'] ); ?>" title="<?php echo esc_attr( $atts['title'] ); ?>">
	
	<?php
	if ($atts['link_to'] == 'self' || $atts['link_to'] == 'link'):
		?>
		</a>
		<?php
	endif;
	?>
	</div>
	<?php

	$output =  ob_get_clean();

	$output = apply_filters( 'mb_image_element_output', $output, $atts, $content );

	return $output;
}
add_shortcode( 'mb_img','mb_img_cb' );



if (class_exists('MB_Element')) {

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
		'icon' => 'mb mb-image',
		'color' => '#6e97ff',
		'options' => array(
			array(
				'id' => 'src',
				'label'    => __( 'Image', 'mytheme' ),
				'subtitle'    => __( 'Select image', 'mytheme' ),
				'type'     => 'image',
				'default' => '',
			),
			array(
				'id' => 'alt',
				'label'    => __( 'Image ALT', 'mytheme' ),
				'subtitle'    => __( 'Value for image alt attribute.', 'mytheme' ),
				'type'     => 'text',
				'default' => '',
			),
			array(
				'id' => 'title',
				'label'    => __( 'Image Title', 'mytheme' ),
				'subtitle'    => __( 'Value for image title attribute.', 'mytheme' ),
				'type'     => 'text',
				'default' => '',
			),
			array(
				'id' => 'size',
				'label'    => __( 'Image Size', 'mytheme' ),
				'subtitle'    => __( 'Size of this image.', 'mytheme' ),
				'type'     => 'select',
				'default' => 'full',
				'choices' => $sizes
			),
			array(
				'id' => 'align',
				'label'    => __( 'Image Align', 'mytheme' ),
				'subtitle'    => __( 'Image align. Default: left', 'mytheme' ),
				'type'     => 'text_align',
				'default' => '',
				'choices' => array( 'justify' => '0' ),
			),
			array(
				'id' => 'link_to',
				'label'    => __( 'Link To', 'mytheme' ),
				'subtitle'    => __( 'Set link of this image.', 'mytheme' ),
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
				'label'    => __( 'Custom Link', 'mytheme' ),
				'subtitle'    => __( 'This option only work if \'Link To\' set as \'Custom Link\'.', 'mytheme' ),
				'type'     => 'url',
				'default' => '',
			),
		)
	);

	$image_map = apply_filters( 'mb_text_map', $image_map );

	MB_Element::add($image_map);
}