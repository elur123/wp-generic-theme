<?php
declare(strict_types=1);
/**
 * Customizer — Layout section
 *
 * @package MedSpaStarter
 */

function medspastarter_customizer_layout( WP_Customize_Manager $wp_customize ): void {

	$wp_customize->add_section( 'medspastarter_layout', [
		'title'    => esc_html__( 'Layout', 'medspastarter' ),
		'priority' => 80,
	] );

	// Header max-width
	$wp_customize->add_setting( 'header_max_width', [
		'default'           => 1200,
		'sanitize_callback' => 'medspastarter_sanitize_range',
		'transport'         => 'postMessage',
	] );
	$wp_customize->add_control(
		new Medspastarter_Range_Control( $wp_customize, 'header_max_width', [
			'label'       => esc_html__( 'Header Max Width (px)', 'medspastarter' ),
			'description' => esc_html__( 'Maximum width of the header navigation container.', 'medspastarter' ),
			'section'     => 'medspastarter_layout',
			'input_attrs' => [ 'min' => 600, 'max' => 1600, 'step' => 20 ],
		] )
	);

	// Body max-width
	$wp_customize->add_setting( 'body_max_width', [
		'default'           => 980,
		'sanitize_callback' => 'medspastarter_sanitize_range',
		'transport'         => 'postMessage',
	] );
	$wp_customize->add_control(
		new Medspastarter_Range_Control( $wp_customize, 'body_max_width', [
			'label'       => esc_html__( 'Content Max Width (px)', 'medspastarter' ),
			'description' => esc_html__( 'Maximum width of the main content area.', 'medspastarter' ),
			'section'     => 'medspastarter_layout',
			'input_attrs' => [ 'min' => 600, 'max' => 1600, 'step' => 20 ],
		] )
	);

	// Footer max-width
	$wp_customize->add_setting( 'footer_max_width', [
		'default'           => 1200,
		'sanitize_callback' => 'medspastarter_sanitize_range',
		'transport'         => 'postMessage',
	] );
	$wp_customize->add_control(
		new Medspastarter_Range_Control( $wp_customize, 'footer_max_width', [
			'label'       => esc_html__( 'Footer Max Width (px)', 'medspastarter' ),
			'description' => esc_html__( 'Maximum width of the footer content container.', 'medspastarter' ),
			'section'     => 'medspastarter_layout',
			'input_attrs' => [ 'min' => 600, 'max' => 1600, 'step' => 20 ],
		] )
	);

	// Sidebar on posts
	$wp_customize->add_setting( 'sidebar_posts', [
		'default'           => true,
		'sanitize_callback' => 'medspastarter_sanitize_checkbox',
		'transport'         => 'refresh',
	] );
	$wp_customize->add_control( 'sidebar_posts', [
		'label'   => esc_html__( 'Show sidebar on single posts', 'medspastarter' ),
		'section' => 'medspastarter_layout',
		'type'    => 'checkbox',
	] );

	// Sidebar on pages
	$wp_customize->add_setting( 'sidebar_pages', [
		'default'           => false,
		'sanitize_callback' => 'medspastarter_sanitize_checkbox',
		'transport'         => 'refresh',
	] );
	$wp_customize->add_control( 'sidebar_pages', [
		'label'   => esc_html__( 'Show sidebar on pages', 'medspastarter' ),
		'section' => 'medspastarter_layout',
		'type'    => 'checkbox',
	] );

	// Sidebar position
	$wp_customize->add_setting( 'sidebar_position', [
		'default'           => 'right',
		'sanitize_callback' => 'medspastarter_sanitize_select',
		'transport'         => 'refresh',
	] );
	$wp_customize->add_control( 'sidebar_position', [
		'label'   => esc_html__( 'Sidebar Position', 'medspastarter' ),
		'section' => 'medspastarter_layout',
		'type'    => 'select',
		'choices' => [
			'right' => esc_html__( 'Right', 'medspastarter' ),
			'left'  => esc_html__( 'Left', 'medspastarter' ),
		],
	] );
}
