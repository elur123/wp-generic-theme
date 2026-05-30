<?php
declare(strict_types=1);
/**
 * Customizer — Colors section
 *
 * Stored values are output as CSS custom properties in wp_head so they can
 * override the compiled Tailwind defaults at runtime.
 *
 * @package GenericStarter
 */

function genericstarter_customizer_colors( WP_Customize_Manager $wp_customize ): void {

	$wp_customize->add_section( 'genericstarter_colors', [
		'title'    => esc_html__( 'Colors', 'genericstarter' ),
		'priority' => 40,
	] );

	$color_settings = [
		// Brand
		'color_primary'   => [ '#f25f5a', esc_html__( 'Primary (Coral)', 'genericstarter' ) ],
		'color_secondary' => [ '#C9A96E', esc_html__( 'Secondary (Gold)', 'genericstarter' ) ],
		// Text
		'color_text'      => [ '#3A3D3C', esc_html__( 'Body Text', 'genericstarter' ) ],
		'color_heading'   => [ '#1A1C1B', esc_html__( 'Heading Text', 'genericstarter' ) ],
		'color_border'    => [ '#E5E7E6', esc_html__( 'Border / Divider', 'genericstarter' ) ],
		// Section backgrounds
		'color_bg_topbar' => [ '#f25f5a', esc_html__( 'Top Bar Background', 'genericstarter' ) ],
		'color_bg_header' => [ '#FFFFFF', esc_html__( 'Header Background', 'genericstarter' ) ],
		'color_bg_body'   => [ '#FAFAFA', esc_html__( 'Body Background', 'genericstarter' ) ],
		'color_bg_footer' => [ '#1A1C1B', esc_html__( 'Footer Background', 'genericstarter' ) ],
	];

	foreach ( $color_settings as $id => [ $default, $label ] ) {
		$wp_customize->add_setting( $id, [
			'default'           => $default,
			'sanitize_callback' => 'genericstarter_sanitize_hex_color',
			'transport'         => 'postMessage',
		] );
		$wp_customize->add_control(
			new WP_Customize_Color_Control( $wp_customize, $id, [
				'label'   => $label,
				'section' => 'genericstarter_colors',
			] )
		);
	}

}

function genericstarter_output_color_css(): void {
	$defaults = [
		'color_primary'   => '#f25f5a',
		'color_secondary' => '#C9A96E',
		'color_text'      => '#3A3D3C',
		'color_heading'   => '#1A1C1B',
		'color_border'    => '#E5E7E6',
		'color_bg_topbar' => '#f25f5a',
		'color_bg_header' => '#FFFFFF',
		'color_bg_body'   => '#FAFAFA',
		'color_bg_footer' => '#1A1C1B',
	];

	$mods    = [];
	$changed = false;
	foreach ( $defaults as $key => $default ) {
		$mods[ $key ] = get_theme_mod( $key, $default );
		if ( $mods[ $key ] !== $default ) {
			$changed = true;
		}
	}

	if ( ! $changed ) {
		return;
	}

	$css = '';

	// CSS custom property overrides (brand + text tokens)
	$vars = '';
	if ( $mods['color_primary']   !== $defaults['color_primary'] )   $vars .= '--color-primary:'   . sanitize_hex_color( $mods['color_primary'] ) . ';';
	if ( $mods['color_secondary'] !== $defaults['color_secondary'] )  $vars .= '--color-secondary:' . sanitize_hex_color( $mods['color_secondary'] ) . ';';
	if ( $mods['color_text']      !== $defaults['color_text'] )       $vars .= '--color-text:'      . sanitize_hex_color( $mods['color_text'] ) . ';';
	if ( $mods['color_heading']   !== $defaults['color_heading'] )    $vars .= '--color-heading:'   . sanitize_hex_color( $mods['color_heading'] ) . ';';
	if ( $mods['color_border']    !== $defaults['color_border'] )     $vars .= '--color-border:'    . sanitize_hex_color( $mods['color_border'] ) . ';';
	if ( $vars ) {
		$css .= ':root{' . $vars . '}';
	}

	// Direct background overrides — unlayered so they beat Tailwind's @layer utilities
	if ( $mods['color_bg_topbar'] !== $defaults['color_bg_topbar'] ) $css .= '.top-bar{background-color:' . sanitize_hex_color( $mods['color_bg_topbar'] ) . ';}';
	if ( $mods['color_bg_header'] !== $defaults['color_bg_header'] ) $css .= '.site-header{background-color:' . sanitize_hex_color( $mods['color_bg_header'] ) . ';}';
	if ( $mods['color_bg_body']   !== $defaults['color_bg_body'] )   $css .= 'body{background-color:' . sanitize_hex_color( $mods['color_bg_body'] ) . ';}';
	if ( $mods['color_bg_footer'] !== $defaults['color_bg_footer'] ) $css .= '.site-footer{background-color:' . sanitize_hex_color( $mods['color_bg_footer'] ) . ';}';

	if ( $css ) {
		echo '<style id="genericstarter-color-overrides">' . $css . '</style>' . "\n"; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	}
}
