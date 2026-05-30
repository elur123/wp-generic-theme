<?php
declare(strict_types=1);
/**
 * Customizer — Layout section
 *
 * @package GenericStarter
 */

function genericstarter_customizer_layout( WP_Customize_Manager $wp_customize ): void {

	$wp_customize->add_section( 'genericstarter_layout', [
		'title'    => esc_html__( 'Layout', 'genericstarter' ),
		'priority' => 80,
	] );

	// Header max-width
	$wp_customize->add_setting( 'header_max_width', [
		'default'           => 1200,
		'sanitize_callback' => 'genericstarter_sanitize_range',
		'transport'         => 'postMessage',
	] );
	$wp_customize->add_control(
		new Genericstarter_Range_Control( $wp_customize, 'header_max_width', [
			'label'       => esc_html__( 'Header Max Width (px)', 'genericstarter' ),
			'description' => esc_html__( 'Maximum width of the header navigation container.', 'genericstarter' ),
			'section'     => 'genericstarter_layout',
			'input_attrs' => [ 'min' => 600, 'max' => 1600, 'step' => 20 ],
		] )
	);

	// Body max-width
	$wp_customize->add_setting( 'body_max_width', [
		'default'           => 980,
		'sanitize_callback' => 'genericstarter_sanitize_range',
		'transport'         => 'postMessage',
	] );
	$wp_customize->add_control(
		new Genericstarter_Range_Control( $wp_customize, 'body_max_width', [
			'label'       => esc_html__( 'Content Max Width (px)', 'genericstarter' ),
			'description' => esc_html__( 'Maximum width of the main content area.', 'genericstarter' ),
			'section'     => 'genericstarter_layout',
			'input_attrs' => [ 'min' => 600, 'max' => 1600, 'step' => 20 ],
		] )
	);

	// Footer max-width
	$wp_customize->add_setting( 'footer_max_width', [
		'default'           => 1200,
		'sanitize_callback' => 'genericstarter_sanitize_range',
		'transport'         => 'postMessage',
	] );
	$wp_customize->add_control(
		new Genericstarter_Range_Control( $wp_customize, 'footer_max_width', [
			'label'       => esc_html__( 'Footer Max Width (px)', 'genericstarter' ),
			'description' => esc_html__( 'Maximum width of the footer content container.', 'genericstarter' ),
			'section'     => 'genericstarter_layout',
			'input_attrs' => [ 'min' => 600, 'max' => 1600, 'step' => 20 ],
		] )
	);

	// Sidebar on posts
	$wp_customize->add_setting( 'sidebar_posts', [
		'default'           => true,
		'sanitize_callback' => 'genericstarter_sanitize_checkbox',
		'transport'         => 'refresh',
	] );
	$wp_customize->add_control( 'sidebar_posts', [
		'label'   => esc_html__( 'Show sidebar on single posts', 'genericstarter' ),
		'section' => 'genericstarter_layout',
		'type'    => 'checkbox',
	] );

	// Sidebar on pages
	$wp_customize->add_setting( 'sidebar_pages', [
		'default'           => false,
		'sanitize_callback' => 'genericstarter_sanitize_checkbox',
		'transport'         => 'refresh',
	] );
	$wp_customize->add_control( 'sidebar_pages', [
		'label'   => esc_html__( 'Show sidebar on pages', 'genericstarter' ),
		'section' => 'genericstarter_layout',
		'type'    => 'checkbox',
	] );

	// Sidebar position
	$wp_customize->add_setting( 'sidebar_position', [
		'default'           => 'right',
		'sanitize_callback' => 'genericstarter_sanitize_select',
		'transport'         => 'refresh',
	] );
	$wp_customize->add_control( 'sidebar_position', [
		'label'   => esc_html__( 'Sidebar Position', 'genericstarter' ),
		'section' => 'genericstarter_layout',
		'type'    => 'select',
		'choices' => [
			'right' => esc_html__( 'Right', 'genericstarter' ),
			'left'  => esc_html__( 'Left', 'genericstarter' ),
		],
	] );
}
