<?php

if (!function_exists('mb_accordions_cb')):

function mb_accordions_cb( $atts, $content ) {

	$default = array(
		'class' => ''
	);

	$default = apply_filters( 'mb_accordions_element_atts', $default );

	$atts = shortcode_atts( $default, $atts );

	$style = '';

	$ext_cls = array();

	if(isset($atts['class']) && !empty($atts['class'])){
		$ext_cls[] = $atts['class'];
	}
	
	$parent = 'mb-accordion-'.rand(99, 99999);

	$content = mb_accordion_extra_atts($content, 'parent="'.$parent.'"', 0);

	ob_start();
	?>
	<div class="panel-group mb-accordions <?php echo esc_attr( implode(' ', $ext_cls) ); ?>" id="<?php echo esc_attr( $parent ); ?>" role="tablist" aria-multiselectable="true">
		<?php echo do_shortcode( $content ); ?>
	</div>
	<?php
	$output = ob_get_clean();

	$output = apply_filters( 'mb_accordions_element_output', $output, $atts, $content );
	return $output;
}
add_shortcode( 'mb_accordions','mb_accordions_cb' );

endif;

if (!function_exists('mb_accordion_cb')):

function mb_accordion_cb( $atts, $content ) {

	$default = array(
		'title' => '',
		'id' => '',
		'active' => 0,
		'parent' => ''
	);

	$default = apply_filters( 'mb_accordion_element_atts', $default );

	$atts = shortcode_atts( $default, $atts );

	if (empty($atts['id'])) {
		$atts['id'] = sanitize_title($atts['title']).'-'.rand(99, 99999);
	}

	$ext_panel_cls = array();

	if (isset($atts['active']) && (int) $atts['active']) {
		$ext_panel_cls[] = 'in';
	}

	$style = '';

	ob_start();
	?>
	<div class="panel panel-default">
		<div class="panel-heading" role="tab" id="headingOne">
			<h4 class="panel-title">
				<a role="button" data-toggle="collapse" data-parent="#<?php echo esc_attr( $atts['parent'] ); ?>" href="#<?php echo esc_attr( $atts['id'] ); ?>" aria-expanded="true" aria-controls="<?php echo esc_attr( $atts['id'] ); ?>"><?php echo esc_html( $atts['title'] ); ?></a>
			</h4>
		</div>
		<div id="<?php echo esc_attr( $atts['id'] ); ?>" class="panel-collapse collapse <?php echo esc_attr( implode(' ', $ext_panel_cls) ); ?>" role="tabpanel" aria-labelledby="headingOne">
			<div class="panel-body"><?php echo wpautop(do_shortcode( $content )); ?></div>
		</div>
	</div>
	<?php
	$output = ob_get_clean();

	$output = apply_filters( 'mb_accordion_element_output', $output, $atts, $content );
	return $output;
}
add_shortcode( 'mb_accordion','mb_accordion_cb' );

endif;

if (!function_exists('mb_accordion_extra_atts')):

function mb_accordion_extra_atts( $content, $attr, $active = 0 ) {
    global $shortcode_tags;
 
    if ( false === strpos( $content, '[' ) ) {
        return $content;
    }
 
    if (empty($shortcode_tags) || !is_array($shortcode_tags))
        return $content;
 
    // Find all registered tag names in $content.
    preg_match_all( '@\[([^<>&/\[\]\x00-\x20=]++)@', $content, $matches );
    $tagnames = array_intersect( array_keys( $shortcode_tags ), $matches[1] );

 
    if ( empty( $tagnames ) ) {
        return $content;
    }
 
    $content = do_shortcodes_in_html_tags( $content, $ignore_html, $tagnames );


 
    $pattern = get_shortcode_regex( $tagnames );
    preg_match_all("/$pattern/", $content, $tabs);

    $new_content = '';

    foreach ($tabs[0] as $key => $tab) {
    	preg_match("/$pattern/", $tab, $tab_item);

    	if ($active == $key) {
    		$new_sc = '['.$tab_item[2].' '.$tab_item[3].' '.$attr.' active="1"]'.$tab_item[5].'[/'.$tab_item[2].']';
    	} else {
    		$new_sc = '['.$tab_item[2].' '.$tab_item[3].' '.$attr.']'.$tab_item[5].'[/'.$tab_item[2].']';
    	}
    	
    	$new_content .= $new_sc;
    }
    
 
    return $new_content;
}

endif;

if (class_exists('MB_Element')) {

	$tabs_map = array(
		'title' => 'Accordions',
		'subtitle' => 'Accordions Element',
		'code' => 'mb_accordions',
		'icon' => 'mb mb-accordions',
		'color' => '#00bfa5',
		'child' => 'mb_accordion',
		'hascontent' => true,
		'options' => array(
			array(
				'id' => 'class',
				'label'    => __( 'Extra Class', 'mighty-builder' ),
				'type'     => 'text',
				'default' => '',
			),
		)
	);

	$tabs_map = apply_filters( 'mb_accordions_map', $tabs_map );

	MB_Element::add($tabs_map);

	$tab_map = array(
		'title' => 'Accordion Item',
		'subtitle' => 'Test Shortcode Subtitle',
		'code' => 'mb_accordion',
		'parent' => 'mb_accordions',
		'icon' => 'mb mb-accordion',
		'color' => '#1de9b6',
		'hascontent' => true,
		'options' => array(
			array(
				'id' => 'title',
				'label'    => __( 'Title', 'mighty-builder' ),
				'subtitle'    => __( 'Tab title', 'mighty-builder' ),
				'type'     => 'text',
				'default' => 'Test Text',
			),
			array(
				'id' => 'tab_content',
				'label'    => __( 'Content', 'mighty-builder' ),
				'type'     => 'editor',
				'default' => 'Test Text',
				'roll' => 'content'
			),
		)
	);

	MB_Element::add($tab_map);
}

