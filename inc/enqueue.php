<?php
declare(strict_types=1);
/**
 * Script and style enqueuing
 *
 * @package GenericStarter
 */

function genericstarter_scripts(): void {
	$dir = get_template_directory();
	$uri = get_template_directory_uri();

	// Google Fonts
	wp_enqueue_style(
		'genericstarter-google-fonts',
		'https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,400;0,500;0,600;0,700;1,400&family=Mulish:wght@400;500;600;700&display=swap',
		[],
		null
	);

	// Main stylesheet (Vite extracts CSS from app.js → build/css/app.css)
	$css_path = $dir . '/build/css/app.css';
	wp_enqueue_style(
		'genericstarter-style',
		$uri . '/build/css/app.css',
		[ 'genericstarter-google-fonts' ],
		file_exists( $css_path ) ? (string) filemtime( $css_path ) : GENERICSTARTER_VERSION
	);

	// Main JS bundle (compiled from src/js/app.js via Vite)
	$js_path = $dir . '/build/js/app.js';
	wp_enqueue_script(
		'genericstarter-app',
		$uri . '/build/js/app.js',
		[],
		file_exists( $js_path ) ? (string) filemtime( $js_path ) : GENERICSTARTER_VERSION,
		true
	);

	// Pass theme options to JS (read by src/js/components/*.js)
	wp_localize_script( 'genericstarter-app', 'genericstarter_options', [
		'dark_mode'        => (bool) get_theme_mod( 'enable_dark_mode', true ),
		'dark_mode_default'=> get_theme_mod( 'dark_mode_default', 'system' ),
		'back_to_top'      => (bool) get_theme_mod( 'has_back_to_top', true ),
		'has_search'       => (bool) get_theme_mod( 'has_search', true ),
		'sticky_header'    => (bool) get_theme_mod( 'sticky_header', true ),
	] );

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'genericstarter_scripts' );

function genericstarter_editor_styles(): void {
	$uri     = get_template_directory_uri();
	$dir     = get_template_directory();
	$css_path = $dir . '/build/css/editor.css';

	add_editor_style( [
		'https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,400;0,500;0,600;0,700;1,400&family=Mulish:wght@400;500;600;700&display=swap',
		$uri . '/build/css/editor.css',
	] );
}
add_action( 'after_setup_theme', 'genericstarter_editor_styles' );
