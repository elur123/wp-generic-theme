<?php
declare(strict_types=1);
/**
 * Customizer — Dark Mode section
 *
 * @package MedSpaStarter
 */

function medspastarter_customizer_dark_mode( WP_Customize_Manager $wp_customize ): void {

	$wp_customize->add_section( 'medspastarter_dark_mode', [
		'title'       => esc_html__( 'Dark Mode', 'medspastarter' ),
		'description' => esc_html__( 'Dark mode is powered by Tailwind\'s `dark:` class strategy. The toggle button appears in the header when enabled.', 'medspastarter' ),
		'priority'    => 90,
	] );

	// Enable dark mode toggle in UI
	$wp_customize->add_setting( 'enable_dark_mode', [
		'default'           => true,
		'sanitize_callback' => 'medspastarter_sanitize_checkbox',
		'transport'         => 'refresh',
	] );
	$wp_customize->add_control( 'enable_dark_mode', [
		'label'   => esc_html__( 'Enable dark mode toggle button', 'medspastarter' ),
		'section' => 'medspastarter_dark_mode',
		'type'    => 'checkbox',
	] );

	// Default colour scheme
	$wp_customize->add_setting( 'dark_mode_default', [
		'default'           => 'system',
		'sanitize_callback' => 'medspastarter_sanitize_select',
		'transport'         => 'refresh',
	] );
	$wp_customize->add_control( 'dark_mode_default', [
		'label'       => esc_html__( 'Default Colour Scheme', 'medspastarter' ),
		'description' => esc_html__( '"System" respects the visitor\'s OS preference.', 'medspastarter' ),
		'section'     => 'medspastarter_dark_mode',
		'type'        => 'select',
		'choices'     => [
			'system' => esc_html__( 'System preference', 'medspastarter' ),
			'light'  => esc_html__( 'Always light', 'medspastarter' ),
			'dark'   => esc_html__( 'Always dark', 'medspastarter' ),
		],
	] );
}
