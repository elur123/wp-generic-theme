<?php
declare(strict_types=1);
/**
 * Customizer — General section
 *
 * @package GenericStarter
 */

function genericstarter_customizer_general( WP_Customize_Manager $wp_customize ): void {

	$wp_customize->add_section( 'genericstarter_general', [
		'title'    => esc_html__( 'General', 'genericstarter' ),
		'priority' => 30,
	] );

	// Back-to-top button
	$wp_customize->add_setting( 'has_back_to_top', [
		'default'           => true,
		'sanitize_callback' => 'genericstarter_sanitize_checkbox',
		'transport'         => 'refresh',
	] );
	$wp_customize->add_control( 'has_back_to_top', [
		'label'   => esc_html__( 'Show back-to-top button', 'genericstarter' ),
		'section' => 'genericstarter_general',
		'type'    => 'checkbox',
	] );

	// Search toggle
	$wp_customize->add_setting( 'has_search', [
		'default'           => true,
		'sanitize_callback' => 'genericstarter_sanitize_checkbox',
		'transport'         => 'refresh',
	] );
	$wp_customize->add_control( 'has_search', [
		'label'   => esc_html__( 'Show search button in header', 'genericstarter' ),
		'section' => 'genericstarter_general',
		'type'    => 'checkbox',
	] );

	// Breadcrumbs
	$wp_customize->add_setting( 'has_breadcrumbs', [
		'default'           => true,
		'sanitize_callback' => 'genericstarter_sanitize_checkbox',
		'transport'         => 'refresh',
	] );
	$wp_customize->add_control( 'has_breadcrumbs', [
		'label'   => esc_html__( 'Show breadcrumbs on inner pages', 'genericstarter' ),
		'section' => 'genericstarter_general',
		'type'    => 'checkbox',
	] );
}
