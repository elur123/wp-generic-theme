<?php
declare(strict_types=1);
/**
 * Customizer — Footer section
 *
 * @package MedSpaStarter
 */

function medspastarter_customizer_footer( WP_Customize_Manager $wp_customize ): void {

	$wp_customize->add_section( 'medspastarter_footer', [
		'title'    => esc_html__( 'Footer', 'medspastarter' ),
		'priority' => 60,
	] );

	// Footer credits text
	$wp_customize->add_setting( 'footer_credits_text', [
		'default'           => '',
		'sanitize_callback' => 'medspastarter_sanitize_html',
		'transport'         => 'refresh',
	] );
	$wp_customize->add_control( 'footer_credits_text', [
		'label'       => esc_html__( 'Footer Credits', 'medspastarter' ),
		'description' => esc_html__( 'Override the default copyright text. HTML allowed. Leave empty for automatic year + site name.', 'medspastarter' ),
		'section'     => 'medspastarter_footer',
		'type'        => 'textarea',
	] );

	// Social links — footer
	$wp_customize->add_setting( 'social_links_footer', [
		'default'           => '',
		'sanitize_callback' => 'medspastarter_sanitize_textarea',
		'transport'         => 'refresh',
	] );
	$wp_customize->add_control( 'social_links_footer', [
		'label'       => esc_html__( 'Social Icon URLs', 'medspastarter' ),
		'description' => esc_html__( 'Paste one URL per line or comma-separated. Supports: Facebook, Instagram, LinkedIn, YouTube, TikTok, Twitter/X.', 'medspastarter' ),
		'section'     => 'medspastarter_footer',
		'type'        => 'textarea',
	] );
}
