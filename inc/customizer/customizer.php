<?php
declare(strict_types=1);
/**
 * Customizer master loader
 *
 * Loads sanitization functions, custom controls, and section definitions.
 * Each section file exposes one function: medspastarter_customizer_<name>().
 *
 * @package MedSpaStarter
 */

$_customizer_dir = get_template_directory() . '/inc/customizer';

require_once $_customizer_dir . '/sanitization-functions.php';
require_once $_customizer_dir . '/controls/range-control.php';

// Section loaders — required here so their functions are available when
// medspastarter_customize_register() runs on the customize_register hook.
require_once $_customizer_dir . '/sections/general.php';
require_once $_customizer_dir . '/sections/colors.php';
require_once $_customizer_dir . '/sections/header.php';
require_once $_customizer_dir . '/sections/footer.php';
require_once $_customizer_dir . '/sections/blog.php';
require_once $_customizer_dir . '/sections/layout.php';
require_once $_customizer_dir . '/sections/dark-mode.php';
require_once $_customizer_dir . '/sections/booking.php';

unset( $_customizer_dir );

add_action( 'customize_register', 'medspastarter_customize_register' );

function medspastarter_customize_register( WP_Customize_Manager $wp_customize ): void {
	// Remove WordPress built-in Colors section (added by custom-header/custom-background supports)
	$wp_customize->remove_section( 'colors' );

	// Site identity tweaks — move description into our section
	$wp_customize->get_setting( 'blogname' )->transport        = 'postMessage';
	$wp_customize->get_setting( 'blogdescription' )->transport = 'postMessage';

	// Logo width — after Logo control (priority 8), before Site Title (priority 10)
	$wp_customize->add_setting( 'logo_width', [
		'default'           => 160,
		'sanitize_callback' => 'absint',
		'transport'         => 'postMessage',
	] );
	$wp_customize->add_control(
		new Medspastarter_Range_Control( $wp_customize, 'logo_width', [
			'label'       => esc_html__( 'Logo Width (px)', 'medspastarter' ),
			'description' => esc_html__( 'Maximum width of the logo image.', 'medspastarter' ),
			'section'     => 'title_tagline',
			'priority'    => 9,
			'input_attrs' => [
				'min'  => 60,
				'max'  => 300,
				'step' => 5,
			],
		] )
	);

	// "Display Site Title and Tagline" — after Tagline (priority 20) in Site Identity
	$wp_customize->add_setting( 'show_header_text', [
		'default'           => true,
		'sanitize_callback' => 'medspastarter_sanitize_checkbox',
		'transport'         => 'postMessage',
	] );
	$wp_customize->add_control( 'show_header_text', [
		'label'    => esc_html__( 'Display Site Title and Tagline', 'medspastarter' ),
		'section'  => 'title_tagline',
		'type'     => 'checkbox',
		'priority' => 25,
	] );

	// Register all sections
	medspastarter_customizer_general( $wp_customize );
	medspastarter_customizer_colors( $wp_customize );
	medspastarter_customizer_header( $wp_customize );
	medspastarter_customizer_footer( $wp_customize );
	medspastarter_customizer_blog( $wp_customize );
	medspastarter_customizer_layout( $wp_customize );
	medspastarter_customizer_dark_mode( $wp_customize );
	medspastarter_customizer_booking( $wp_customize );
}

// Inline CSS for colour overrides — output on every page load
add_action( 'wp_head', 'medspastarter_output_color_css', 99 );

// Inline CSS for layout max-width overrides — output on every page load
add_action( 'wp_head', 'medspastarter_output_layout_css', 99 );

function medspastarter_output_layout_css(): void {
	$defaults = [
		'header_max_width' => 1200,
		'body_max_width'   => 980,
		'footer_max_width' => 1200,
	];

	$mods    = [];
	$changed = false;
	foreach ( $defaults as $key => $default ) {
		$mods[ $key ] = (int) get_theme_mod( $key, $default );
		if ( $mods[ $key ] !== $default ) {
			$changed = true;
		}
	}

	if ( ! $changed ) {
		return;
	}

	$css = '';
	if ( $mods['header_max_width'] !== $defaults['header_max_width'] )
		$css .= '.top-bar .section-wide,.site-header .section-wide{max-width:' . $mods['header_max_width'] . 'px;}';
	if ( $mods['body_max_width'] !== $defaults['body_max_width'] )
		$css .= '.section-container{max-width:' . $mods['body_max_width'] . 'px;}';
	if ( $mods['footer_max_width'] !== $defaults['footer_max_width'] )
		$css .= '.site-footer .section-wide{max-width:' . $mods['footer_max_width'] . 'px;}';

	if ( $css ) {
		echo '<style id="medspastarter-layout-overrides">' . $css . '</style>' . "\n"; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	}
}

// Inline CSS for logo width — output on every page load
add_action( 'wp_head', 'medspastarter_output_logo_css', 100 );

function medspastarter_output_logo_css(): void {
	$width = absint( get_theme_mod( 'logo_width', 160 ) );
	echo '<style id="medspastarter-logo-width">.site-logo a{display:block;line-height:0;}.custom-logo{display:block;max-width:' . $width . 'px;width:100%;height:auto;}</style>' . "\n"; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
}

// Live preview JS — loaded in the preview iframe, handles postMessage updates
add_action( 'customize_preview_init', 'medspastarter_customize_preview_js' );

function medspastarter_customize_preview_js(): void {
	$js_path = get_template_directory() . '/assets/js/customize-preview.js';
	wp_enqueue_script(
		'medspastarter-customize-preview',
		get_template_directory_uri() . '/assets/js/customize-preview.js',
		[ 'customize-preview' ],
		file_exists( $js_path ) ? (string) filemtime( $js_path ) : MEDSPASTARTER_VERSION,
		true
	);
}
