<?php
declare(strict_types=1);
/**
 * Customizer — Header section
 *
 * @package MedSpaStarter
 */

function medspastarter_customizer_header( WP_Customize_Manager $wp_customize ): void {

	$wp_customize->add_section( 'medspastarter_header', [
		'title'    => esc_html__( 'Header', 'medspastarter' ),
		'priority' => 50,
	] );

	// Header layout
	$wp_customize->add_setting( 'header_layout', [
		'default'           => 'default',
		'sanitize_callback' => 'medspastarter_sanitize_select',
		'transport'         => 'refresh',
	] );
	$wp_customize->add_control( 'header_layout', [
		'label'   => esc_html__( 'Header Layout', 'medspastarter' ),
		'section' => 'medspastarter_header',
		'type'    => 'select',
		'choices' => [
			'default'       => esc_html__( 'Default — Logo left, menu center, actions right', 'medspastarter' ),
			'centered-logo' => esc_html__( 'Centered logo — Menu left, logo center, actions right', 'medspastarter' ),
			'logo-left'     => esc_html__( 'Compact — Logo left, menu + actions right', 'medspastarter' ),
			'split-menu'    => esc_html__( 'Split menu — Half menu · Logo center · Half menu (no actions)', 'medspastarter' ),
		],
	] );

	// Show top bar
	$wp_customize->add_setting( 'show_top_bar', [
		'default'           => true,
		'sanitize_callback' => 'medspastarter_sanitize_checkbox',
		'transport'         => 'refresh',
	] );
	$wp_customize->add_control( 'show_top_bar', [
		'label'   => esc_html__( 'Show top bar', 'medspastarter' ),
		'section' => 'medspastarter_header',
		'type'    => 'checkbox',
	] );

	// Sticky header
	$wp_customize->add_setting( 'sticky_header', [
		'default'           => true,
		'sanitize_callback' => 'medspastarter_sanitize_checkbox',
		'transport'         => 'refresh',
	] );
	$wp_customize->add_control( 'sticky_header', [
		'label'   => esc_html__( 'Sticky header on scroll', 'medspastarter' ),
		'section' => 'medspastarter_header',
		'type'    => 'checkbox',
	] );

	// Transparent header on hero
	$wp_customize->add_setting( 'transparent_header', [
		'default'           => false,
		'sanitize_callback' => 'medspastarter_sanitize_checkbox',
		'transport'         => 'refresh',
	] );
	$wp_customize->add_control( 'transparent_header', [
		'label'       => esc_html__( 'Transparent header over hero images', 'medspastarter' ),
		'description' => esc_html__( 'Only applies on the front page when a hero pattern is present.', 'medspastarter' ),
		'section'     => 'medspastarter_header',
		'type'        => 'checkbox',
	] );

	// Phone number (top bar)
	$wp_customize->add_setting( 'phone_number', [
		'default'           => '',
		'sanitize_callback' => 'medspastarter_sanitize_nohtml',
		'transport'         => 'refresh',
	] );
	$wp_customize->add_control( 'phone_number', [
		'label'       => esc_html__( 'Phone Number', 'medspastarter' ),
		'description' => esc_html__( 'Displayed in the top bar and used for the tel: link.', 'medspastarter' ),
		'section'     => 'medspastarter_header',
		'type'        => 'text',
	] );

	// Email (top bar)
	$wp_customize->add_setting( 'email_address', [
		'default'           => '',
		'sanitize_callback' => 'medspastarter_sanitize_nohtml',
		'transport'         => 'refresh',
	] );
	$wp_customize->add_control( 'email_address', [
		'label'   => esc_html__( 'Email Address', 'medspastarter' ),
		'section' => 'medspastarter_header',
		'type'    => 'text',
	] );

	// Clinic location (top bar)
	$wp_customize->add_setting( 'clinic_location', [
		'default'           => '',
		'sanitize_callback' => 'medspastarter_sanitize_nohtml',
		'transport'         => 'refresh',
	] );
	$wp_customize->add_control( 'clinic_location', [
		'label'       => esc_html__( 'Clinic Location', 'medspastarter' ),
		'description' => esc_html__( 'Short address shown in the top bar, e.g. "Beverly Hills, CA"', 'medspastarter' ),
		'section'     => 'medspastarter_header',
		'type'        => 'text',
	] );

	// Social links — top bar
	$wp_customize->add_setting( 'social_links_topbar', [
		'default'           => '',
		'sanitize_callback' => 'medspastarter_sanitize_textarea',
		'transport'         => 'refresh',
	] );
	$wp_customize->add_control( 'social_links_topbar', [
		'label'       => esc_html__( 'Top Bar Social Icons', 'medspastarter' ),
		'description' => esc_html__( 'Paste one social profile URL per line (or comma-separated). The theme auto-detects Facebook, Instagram, LinkedIn, YouTube, TikTok, and Twitter/X from the URL.', 'medspastarter' ),
		'section'     => 'medspastarter_header',
		'type'        => 'textarea',
	] );
}
