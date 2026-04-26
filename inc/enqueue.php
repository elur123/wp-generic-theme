<?php
declare(strict_types=1);
/**
 * Script and style enqueuing
 *
 * @package MedSpaStarter
 */

function medspastarter_scripts(): void {
	$dir = get_template_directory();
	$uri = get_template_directory_uri();

	// Google Fonts
	wp_enqueue_style(
		'medspastarter-google-fonts',
		'https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&family=Playfair+Display:ital,wght@0,400;0,700;1,400&display=swap',
		[],
		null
	);

	// Main stylesheet (Vite extracts CSS from app.js → build/css/app.css)
	$css_path = $dir . '/build/css/app.css';
	wp_enqueue_style(
		'medspastarter-style',
		$uri . '/build/css/app.css',
		[ 'medspastarter-google-fonts' ],
		file_exists( $css_path ) ? (string) filemtime( $css_path ) : MEDSPASTARTER_VERSION
	);

	// Main JS bundle (compiled from src/js/app.js via Vite)
	$js_path = $dir . '/build/js/app.js';
	wp_enqueue_script(
		'medspastarter-app',
		$uri . '/build/js/app.js',
		[],
		file_exists( $js_path ) ? (string) filemtime( $js_path ) : MEDSPASTARTER_VERSION,
		true
	);

	// Pass theme options to JS (read by src/js/components/*.js)
	wp_localize_script( 'medspastarter-app', 'medspastarter_options', [
		'dark_mode'        => (bool) get_theme_mod( 'enable_dark_mode', true ),
		'dark_mode_default'=> get_theme_mod( 'dark_mode_default', 'system' ),
		'back_to_top'      => (bool) get_theme_mod( 'has_back_to_top', true ),
		'has_search'       => (bool) get_theme_mod( 'has_search', true ),
		'sticky_header'    => (bool) get_theme_mod( 'sticky_header', true ),
		'booking_url'      => esc_url( get_theme_mod( 'booking_url', '#book' ) ),
		'booking_cta_text' => esc_html( get_theme_mod( 'booking_cta_text', __( 'Book Consultation', 'medspastarter' ) ) ),
	] );

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'medspastarter_scripts' );

function medspastarter_editor_styles(): void {
	$uri     = get_template_directory_uri();
	$dir     = get_template_directory();
	$css_path = $dir . '/build/css/editor.css';

	add_editor_style( [
		'https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&family=Playfair+Display:ital,wght@0,400;0,700;1,400&display=swap',
		$uri . '/build/css/editor.css',
	] );
}
add_action( 'after_setup_theme', 'medspastarter_editor_styles' );
