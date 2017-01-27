<?php
if ( class_exists('MB_Element') && defined( 'WPCF7_PLUGIN' ) ) {

	$wpcf7_contact_forms = get_posts( 'post_type="wpcf7_contact_form"&numberposts=-1' );

	$contact_forms = array();
	$cf7_default = '';
	if ( $wpcf7_contact_forms ) {
		foreach ( $wpcf7_contact_forms as $key => $wpcf7_contact_form ) {
			if (!$key) {
				$cf7_default = $wpcf7_contact_form->ID;
			}
			$contact_forms[ $wpcf7_contact_form->ID ] = $wpcf7_contact_form->post_title;
		}
	}

	$wpcf7_map = array(
		'title' => 'Contact Form 7',
		'subtitle' => 'Contact Forms',
		'code' => 'contact-form-7',
		'icon' => 'mb mb-cf7',
		'color' => '#039be5',
		'options' => array(
			array(
				'id' => 'id',
				'label'    => __( 'Contact Form', 'mighty-builder' ),
				'subtitle'    => __( 'Select a contact form', 'mighty-builder' ),
				'type'     => 'select',
				'default' => $cf7_default,
				'choices' => $contact_forms
			),
		)
	);

	$wpcf7_map = apply_filters( 'mb_cf7_map', $wpcf7_map );

	MB_Element::add($wpcf7_map);
}

if (class_exists('MB_Element') && class_exists('MP_Portfolio')){

	$mp_portfolio_map = array(
		'title' => 'Portfolio Gallery',
		'subtitle' => 'Mighty portfolio gallery',
		'code' => 'mp_portfolio',
		'icon' => 'mb mb-portfolio',
		'color' => '#9cdb58',
		'options' => array(
			array(
				'id' => 'count',
				'label'    => __( 'Number of project', 'mighty-builder' ),
				'subtitle'    => __( 'Select a contact form', 'mighty-builder' ),
				'type'     => 'number',
				'default' => '12',
			),
			array(
				'id' => 'filters_align',
				'label'    => __( 'Filters Align', 'mighty-builder' ),
				'type'     => 'text_align',
				'default' => 'left',
			),
			array(
				'id' => 'show_filters',
				'label'    => __( 'Show Filters', 'mighty-builder' ),
				'subtitle'    => __( 'Only work with only Fluid and Fullwidth Container', 'mighty-builder' ),
				'type'     => 'radio',
				'default' => 'yes',
				'choices' => array(
					'no' => __('Disable', 'mighty-builder'),
					'yes' => __('Enable', 'mighty-builder'),
				),
			),
			array(
				'id' => 'show_paginate',
				'label'    => __( 'Show Paginate', 'mighty-builder' ),
				'subtitle'    => __( 'Only work with only Fluid and Fullwidth Container', 'mighty-builder' ),
				'type'     => 'radio',
				'default' => 'yes',
				'choices' => array(
					'no' => __('Disable', 'mighty-builder'),
					'yes' => __('Enable', 'mighty-builder'),
				),
			),
		)
	);

	$mp_portfolio_map = apply_filters( 'mb_cf7_map', $mp_portfolio_map );

	MB_Element::add($mp_portfolio_map);
}