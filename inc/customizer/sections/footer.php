<?php
declare(strict_types=1);
/**
 * Customizer — Footer section
 *
 * @package GenericStarter
 */

function genericstarter_customizer_footer( WP_Customize_Manager $wp_customize ): void {

	$wp_customize->add_section( 'genericstarter_footer', [
		'title'    => esc_html__( 'Footer', 'genericstarter' ),
		'priority' => 60,
	] );

	// Footer credits text
	$wp_customize->add_setting( 'footer_credits_text', [
		'default'           => '',
		'sanitize_callback' => 'genericstarter_sanitize_html',
		'transport'         => 'refresh',
	] );
	$wp_customize->add_control( 'footer_credits_text', [
		'label'       => esc_html__( 'Footer Credits', 'genericstarter' ),
		'description' => esc_html__( 'Override the default copyright text. HTML allowed. Leave empty for automatic year + site name.', 'genericstarter' ),
		'section'     => 'genericstarter_footer',
		'type'        => 'textarea',
	] );

}
