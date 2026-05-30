<?php
declare( strict_types=1 );
/**
 * Responsive font sizes for core blocks
 *
 * Enqueues the editor extension (assets/js/responsive-typography.js) and, on the
 * front end, renders the per-breakpoint font sizes stored on paragraph/heading
 * blocks as scoped media-query CSS.
 *
 * @package GenericStarter
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Load the block-editor extension.
 */
function genericstarter_responsive_typography_editor_assets(): void {
	$path = get_template_directory() . '/assets/js/responsive-typography.js';
	$ver  = file_exists( $path ) ? (string) filemtime( $path ) : GENERICSTARTER_VERSION;

	wp_enqueue_script(
		'genericstarter-responsive-typography',
		get_template_directory_uri() . '/assets/js/responsive-typography.js',
		[ 'wp-blocks', 'wp-element', 'wp-block-editor', 'wp-components', 'wp-hooks', 'wp-compose', 'wp-i18n' ],
		$ver,
		true
	);
}
add_action( 'enqueue_block_editor_assets', 'genericstarter_responsive_typography_editor_assets' );

/**
 * Validate a CSS length value before it is printed into a <style> tag.
 *
 * Only a bare number followed by an allowed unit is accepted; anything else
 * (including function calls or stray characters) is rejected to prevent CSS
 * injection through the block attribute.
 *
 * @return string The value if safe, otherwise an empty string.
 */
function genericstarter_rt_safe_size( $value ): string {
	$value = trim( (string) $value );
	if ( '' === $value ) {
		return '';
	}
	// Bare number → treat as pixels.
	if ( preg_match( '/^[0-9]*\.?[0-9]+$/', $value ) ) {
		return $value . 'px';
	}
	// Number with an allowed unit.
	if ( preg_match( '/^[0-9]*\.?[0-9]+(px|rem|em|vw|vh|vmin|vmax|pt|%)$/', $value ) ) {
		return $value;
	}
	return '';
}

/**
 * Inject scoped responsive font-size CSS in front of the block markup.
 */
function genericstarter_responsive_typography_render( string $block_content, array $block ): string {
	$attrs = $block['attrs'] ?? [];

	if ( empty( $attrs['mspFsId'] ) || empty( $attrs['mspFontSize'] ) || ! is_array( $attrs['mspFontSize'] ) ) {
		return $block_content;
	}

	$id = preg_replace( '/[^A-Za-z0-9_-]/', '', (string) $attrs['mspFsId'] );
	if ( '' === $id ) {
		return $block_content;
	}

	$sizes   = $attrs['mspFontSize'];
	$sel     = '.mfs-' . $id;
	$mobile  = genericstarter_rt_safe_size( $sizes['mobile']  ?? '' );
	$tablet  = genericstarter_rt_safe_size( $sizes['tablet']  ?? '' );
	$desktop = genericstarter_rt_safe_size( $sizes['desktop'] ?? '' );

	// !important so these beat core's preset font-size classes (which are
	// emitted with !important) and any custom inline font-size on the block.
	$css = '';
	if ( '' !== $mobile ) {
		$css .= $sel . '{font-size:' . $mobile . ' !important;}';
	}
	if ( '' !== $tablet ) {
		$css .= '@media(min-width:768px){' . $sel . '{font-size:' . $tablet . ' !important;}}';
	}
	if ( '' !== $desktop ) {
		$css .= '@media(min-width:1024px){' . $sel . '{font-size:' . $desktop . ' !important;}}';
	}

	if ( '' === $css ) {
		return $block_content;
	}

	return '<style>' . $css . '</style>' . $block_content;
}
add_filter( 'render_block', 'genericstarter_responsive_typography_render', 10, 2 );
