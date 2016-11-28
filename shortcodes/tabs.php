<?php

function mb_tabs_cb( $atts, $content ) {

	$default = array(
	);

	$default = apply_filters( 'mb_tabs_element_atts', $default );

	$atts = shortcode_atts( $default, $atts );

	$style = '';
	

	ob_start();
	?>
	<div class="ct-tabs">
		<ul class="nav nav-tabs" role="tablist">
			<?php preg_replace_callback( '/mb_tab\s([^\]\#]+)/i', 'mb_the_tab_title' , $content ); ?>
		</ul>
		<div class="tab-content">
			<?php echo do_shortcode( $content ); ?>
		</div>
	</div>
	<?php
	$output = ob_get_clean();

	$output = apply_filters( 'mb_tabs_element_output', $output, $atts, $content );
	return $output;
}
add_shortcode( 'mb_tabs','mb_tabs_cb' );

function mb_tab_cb( $atts, $content ) {

	$default = array(
		'title' => '',
		'tab_id' => '',
	);

	$default = apply_filters( 'mb_tab_element_atts', $default );

	$atts = shortcode_atts( $default, $atts );

	if (empty($atts['tab_id'])) {
		$atts['tab_id'] = sanitize_title($atts['title']);
	}

	$style = '';

	ob_start();
	?>
	<div role="tabpanel" class="tab-pane" id="<?php echo esc_attr( $atts['tab_id'] ); ?>">
		<?php echo wpautop(do_shortcode( $content )); ?>
	</div>
	<?php
	$output = ob_get_clean();

	$output = apply_filters( 'mb_tab_element_output', $output, $atts, $content );
	return $output;
}
add_shortcode( 'mb_tab','mb_tab_cb' );

function mb_the_tab_title($data)
{
	if (!empty($data[0])) {
		$atts = shortcode_parse_atts($data[0]);

		$title = (isset($atts['title']) && !empty($atts['title'])) ? $atts['title'] : '';
		$tab_id = (isset($atts['tab_id']) && !empty($atts['tab_id'])) ? $atts['tab_id'] : sanitize_title($atts['title']);

		?>
		<li role="presentation"><a href="#<?php echo esc_attr( $tab_id ); ?>" aria-controls="home" role="tab" data-toggle="tab"><?php echo esc_html($title); ?></a></li>
		<?php
	}

	return $data[0];
}

if (class_exists('MB_Element')) {

	$tabs_map = array(
		'title' => 'Tabs',
		'subtitle' => 'text Element',
		'code' => 'mb_tabs',
		'icon' => 'fa fa-info',
		'child' => 'mb_tab',
		'hascontent' => true,
		'options' => array(
			array(
				'id' => 'size',
				'label'    => __( 'Size', 'mytheme' ),
				'subtitle'    => __( 'Lorem ipsum dolor sit amet', 'mytheme' ),
				'type'     => 'dimension',
				'default' => '25px',
				'choices' => array( 'units' => array( 'px', 'em', 'rem' ) )
			)
		)
	);

	$tabs_map = apply_filters( 'mb_tabs_map', $tabs_map );

	MB_Element::add($tabs_map);

	$tab_map = array(
		'title' => 'Tab Item',
		'subtitle' => 'Test Shortcode Subtitle',
		'code' => 'mb_tab',
		'parent' => 'mb_tabs',
		'icon' => 'fa fa-info',
		'hascontent' => true,
		'options' => array(
			array(
				'id' => 'title',
				'label'    => __( 'Title', 'mytheme' ),
				'subtitle'    => __( 'Lorem ipsum dolor sit amet', 'mytheme' ),
				'type'     => 'text',
				'default' => 'Test Text',
			),
			array(
				'id' => 'tab_content',
				'label'    => __( 'Content', 'mytheme' ),
				'subtitle'    => __( 'Lorem ipsum dolor sit amet', 'mytheme' ),
				'type'     => 'editor',
				'default' => 'Test Text',
				'roll' => 'content'
			),
			array(
				'id' => 'tab_id',
				'label'    => __( 'Tab ID', 'mytheme' ),
				'subtitle'    => __( 'Lorem ipsum dolor sit amet', 'mytheme' ),
				'type'     => 'text',
				'default' => '',
			),
		)
	);

	MB_Element::add($tab_map);
}

