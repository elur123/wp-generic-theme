<?php
declare(strict_types=1);
/**
 * Customizer — Booking section
 *
 * @package MedSpaStarter
 */

function medspastarter_customizer_booking( WP_Customize_Manager $wp_customize ): void {

	$wp_customize->add_section( 'medspastarter_booking', [
		'title'       => esc_html__( 'Booking', 'medspastarter' ),
		'description' => esc_html__( 'Configure the appointment booking CTA shown throughout the site.', 'medspastarter' ),
		'priority'    => 100,
	] );

	// Booking URL
	$wp_customize->add_setting( 'booking_url', [
		'default'           => '#book',
		'sanitize_callback' => 'medspastarter_sanitize_url',
		'transport'         => 'postMessage',
	] );
	$wp_customize->add_control( 'booking_url', [
		'label'       => esc_html__( 'Booking Page URL', 'medspastarter' ),
		'description' => esc_html__( 'Link to your booking page, calendar embed, or contact form.', 'medspastarter' ),
		'section'     => 'medspastarter_booking',
		'type'        => 'url',
	] );

	// CTA button text
	$wp_customize->add_setting( 'booking_cta_text', [
		'default'           => __( 'Book Consultation', 'medspastarter' ),
		'sanitize_callback' => 'medspastarter_sanitize_nohtml',
		'transport'         => 'refresh',
	] );
	$wp_customize->add_control( 'booking_cta_text', [
		'label'   => esc_html__( 'CTA Button Label', 'medspastarter' ),
		'section' => 'medspastarter_booking',
		'type'    => 'text',
	] );

	// Show CTA in header
	$wp_customize->add_setting( 'show_booking_header', [
		'default'           => true,
		'sanitize_callback' => 'medspastarter_sanitize_checkbox',
		'transport'         => 'refresh',
	] );
	$wp_customize->add_control( 'show_booking_header', [
		'label'   => esc_html__( 'Show booking button in header', 'medspastarter' ),
		'section' => 'medspastarter_booking',
		'type'    => 'checkbox',
	] );
}
