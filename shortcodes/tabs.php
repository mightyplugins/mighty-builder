<?php

if (!function_exists('mb_tabs_cb')):

function mb_tabs_cb( $atts, $content ) {

	$default = array(
		'nav_pos' => 'top',
		'fade' => 0,
		'class' => ''
	);

	$default = apply_filters( 'mb_tabs_element_atts', $default );

	$atts = shortcode_atts( $default, $atts );

	$style = '';

	$ext_cls = array();

	if(isset($atts['nav_pos']) && $atts['nav_pos'] == 'bottom'){
		$ext_cls[] = 'bottom-nav';
	}

	if(isset($atts['class']) && !empty($atts['class'])){
		$ext_cls[] = $atts['class'];
	}
	

	if (isset($atts['fade']) && (int) $atts['fade']) {
		$content = mb_tab_extra_atts($content, 'fade="1"', 0);
	} else {
		$content = mb_tab_extra_atts($content, '', 0);
	}

	ob_start();
	?>
	<div class="mb-tabs <?php echo esc_attr( implode(' ', $ext_cls) ); ?>">
		<?php if(isset($atts['nav_pos']) && $atts['nav_pos'] != 'bottom'): ?>
			<ul class="nav nav-tabs" role="tablist">
				<?php echo mb_tab_title($content); ?>
			</ul>
		<?php endif; ?>
		<div class="tab-content">
			<?php echo do_shortcode( $content ); ?>
		</div>
		<?php if(isset($atts['nav_pos']) && $atts['nav_pos'] == 'bottom'): ?>
			<ul class="nav nav-tabs" role="tablist">
				<?php echo mb_tab_title($content); ?>
			</ul>
		<?php endif; ?>
	</div>
	<?php
	$output = ob_get_clean();

	$output = apply_filters( 'mb_tabs_element_output', $output, $atts, $content );
	return $output;
}
add_shortcode( 'mb_tabs','mb_tabs_cb' );

endif;


if (!function_exists('mb_tab_cb')):

function mb_tab_cb( $atts, $content ) {

	$default = array(
		'title' => '',
		'tab_id' => '',
		'fade' => 0,
		'active' => 0
	);

	$default = apply_filters( 'mb_tab_element_atts', $default );

	$atts = shortcode_atts( $default, $atts );

	if (empty($atts['tab_id'])) {
		$atts['tab_id'] = sanitize_title($atts['title']);
	}

	$ext_cls = array();

	if (isset($atts['active']) && (int) $atts['active']) {
		$ext_cls[] = 'active';
	}

	if (isset($atts['fade']) && (int) $atts['fade']) {
		$ext_cls[] = 'fade';
	}

	if (isset($atts['fade']) && (int) $atts['fade'] && (int) $atts['active']) {
		$ext_cls[] = 'in';
	}

	$style = '';

	ob_start();
	?>
	<div role="tabpanel" class="tab-pane <?php echo esc_attr( implode(' ', $ext_cls) ); ?>" id="<?php echo esc_attr( $atts['tab_id'] ); ?>">
		<?php echo wpautop(do_shortcode( $content )); ?>
	</div>
	<?php
	$output = ob_get_clean();

	$output = apply_filters( 'mb_tab_element_output', $output, $atts, $content );
	return $output;
}
add_shortcode( 'mb_tab','mb_tab_cb' );

endif;

if (!function_exists('mb_tab_title')):

function mb_tab_title( $content, $active = 0 )
{
	global $shortcode_tags;

	if ( false === strpos( $content, '[' ) ) {
        return $content;
    }

    if (empty($shortcode_tags) || !is_array($shortcode_tags)){
        return $content;
    }

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
    	$atts = shortcode_parse_atts($tab_item[3]);

    	$title = (isset($atts['title']) && !empty($atts['title'])) ? $atts['title'] : '';
		$tab_id = (isset($atts['tab_id']) && !empty($atts['tab_id'])) ? $atts['tab_id'] : '-'.rand(99, 99999);

		if ($active == $key) {
			$new_content .= '<li role="presentation" class="active"><a href="#'.esc_attr( $tab_id ).'" aria-controls="home" role="tab" data-toggle="tab">'.esc_html($title).'</a></li>';
		} else {
			$new_content .= '<li role="presentation"><a href="#'.esc_attr( $tab_id ).'" aria-controls="home" role="tab" data-toggle="tab">'.esc_html($title).'</a></li>';
		}

		
    }

    return $new_content;
}

endif;

if (!function_exists('mb_tab_extra_atts')):

function mb_tab_extra_atts( $content, $attr, $active = 0 ) {
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
    	$atts = shortcode_parse_atts($tab_item[3]);

    	if(!isset($atts['tab_id']) || empty($atts['tab_id'])){
    		$tab_id = sanitize_title($atts['title']).'-'.rand(99, 99999);

    		$attr .= ' tab_id="'.$tab_id.'"';
    	}

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
		'title' => 'Tabs',
		'subtitle' => 'Tabs Element',
		'code' => 'mb_tabs',
		'icon' => 'mb mb-tabs',
		'color' => '#ffab00',
		'child' => 'mb_tab',
		'hascontent' => true,
		'options' => array(
			array(
				'id' => 'nav_pos',
				'label'    => __( 'Nav Position', 'mighty-builder' ),
				'subtitle'    => __( 'Tab navigation position', 'mighty-builder' ),
				'type'     => 'select',
				'default' => 'top',
                'choices' => array(
                    'top' => 'Top',
                    'bottom' => 'Bottom',
                ),
			),
			array(
				'id' => 'fade',
				'label'    => __( 'Fade Animation', 'mighty-builder' ),
				'type'     => 'radio',
				'default' => '0',
				'choices' => array(
					'0' => __('Disable', 'mighty-builder'),
					'1' => __('Enable', 'mighty-builder'),
				),
			),
			array(
				'id' => 'class',
				'label'    => __( 'Extra Class', 'mighty-builder' ),
				'type'     => 'text',
				'default' => '',
			),
		)
	);

	$tabs_map = apply_filters( 'mb_tabs_map', $tabs_map );

	MB_Element::add($tabs_map);

	$tab_map = array(
		'title' => 'Tab Item',
		'subtitle' => 'Test Shortcode Subtitle',
		'code' => 'mb_tab',
		'parent' => 'mb_tabs',
		'icon' => 'mb mb-tab',
		'color' => '#ffc400',
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
			array(
				'id' => 'tab_id',
				'label'    => __( 'Tab ID', 'mighty-builder' ),
				'subtitle'    => __( 'This tab id', 'mighty-builder' ),
				'type'     => 'text',
				'default' => '',
			),
		)
	);

	MB_Element::add($tab_map);
}

