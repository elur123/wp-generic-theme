<?php
declare(strict_types=1);
/**
 * Customizer — General section
 *
 * @package MedSpaStarter
 */

function medspastarter_customizer_general( WP_Customize_Manager $wp_customize ): void {

	$wp_customize->add_section( 'medspastarter_general', [
		'title'    => esc_html__( 'General', 'medspastarter' ),
		'priority' => 30,
	] );

	// Back-to-top button
	$wp_customize->add_setting( 'has_back_to_top', [
		'default'           => true,
		'sanitize_callback' => 'medspastarter_sanitize_checkbox',
		'transport'         => 'refresh',
	] );
	$wp_customize->add_control( 'has_back_to_top', [
		'label'   => esc_html__( 'Show back-to-top button', 'medspastarter' ),
		'section' => 'medspastarter_general',
		'type'    => 'checkbox',
	] );

	// Search toggle
	$wp_customize->add_setting( 'has_search', [
		'default'           => true,
		'sanitize_callback' => 'medspastarter_sanitize_checkbox',
		'transport'         => 'refresh',
	] );
	$wp_customize->add_control( 'has_search', [
		'label'   => esc_html__( 'Show search button in header', 'medspastarter' ),
		'section' => 'medspastarter_general',
		'type'    => 'checkbox',
	] );

	// Breadcrumbs
	$wp_customize->add_setting( 'has_breadcrumbs', [
		'default'           => true,
		'sanitize_callback' => 'medspastarter_sanitize_checkbox',
		'transport'         => 'refresh',
	] );
	$wp_customize->add_control( 'has_breadcrumbs', [
		'label'   => esc_html__( 'Show breadcrumbs on inner pages', 'medspastarter' ),
		'section' => 'medspastarter_general',
		'type'    => 'checkbox',
	] );
}
