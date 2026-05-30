<?php
declare(strict_types=1);
/**
 * Jetpack compatibility
 *
 * Only loaded when Jetpack is active (guarded by class_exists in functions.php).
 *
 * @package GenericStarter
 */

// Declare Jetpack feature support
add_action( 'after_setup_theme', 'genericstarter_jetpack_setup' );

function genericstarter_jetpack_setup(): void {
	// Infinite scroll — uses the theme's loop markup
	add_theme_support( 'infinite-scroll', [
		'container' => 'posts-grid',
		'render'    => 'genericstarter_infinite_scroll_render',
		'footer'    => 'page',
	] );

	// Featured content
	add_theme_support( 'featured-content', [
		'filter'     => 'genericstarter_get_featured_posts',
		'max_posts'  => 6,
		'post_types' => [ 'post' ],
	] );

	// Responsive videos
	add_theme_support( 'jetpack-responsive-videos' );

	// Site logo
	add_theme_support( 'site-logo', [
		'size' => 'full',
	] );
}

function genericstarter_infinite_scroll_render(): void {
	while ( have_posts() ) {
		the_post();
		get_template_part( 'template-parts/content', 'excerpt' );
	}
}

/** @return WP_Post[] */
function genericstarter_get_featured_posts(): array {
	return apply_filters( 'genericstarter_get_featured_posts', [] ); // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedHooknameFound
}

// Disable Jetpack's default open graph tags — Yoast/RankMath handle these
add_filter( 'jetpack_enable_open_graph', '__return_false' );
