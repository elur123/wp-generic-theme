<?php
declare(strict_types=1);
/**
 * Theme setup: supports, menus, widget areas
 *
 * @package GenericStarter
 */

if ( ! function_exists( 'genericstarter_setup' ) ) :
	function genericstarter_setup(): void {
		load_theme_textdomain( 'genericstarter', get_template_directory() . '/languages' );

		add_theme_support( 'automatic-feed-links' );
		add_theme_support( 'title-tag' );
		add_theme_support( 'post-thumbnails' );
		add_theme_support( 'responsive-embeds' );
		add_theme_support( 'editor-styles' );
		add_theme_support( 'html5', [
			'search-form',
			'comment-form',
			'comment-list',
			'gallery',
			'caption',
			'style',
			'script',
		] );
		add_theme_support( 'customize-selective-refresh-widgets' );
		add_theme_support( 'custom-logo', [
			'height'      => 80,
			'width'       => 200,
			'flex-width'  => true,
			'flex-height' => true,
		] );
		add_theme_support( 'custom-header', [ 'header-text' => false, 'video' => false ] );
		add_post_type_support( 'page', 'excerpt' );

		add_image_size( 'genericstarter-card', 600, 400, true );
		add_image_size( 'genericstarter-hero', 1200, 600, true );

		register_nav_menus( [
			'menu-1' => esc_html__( 'Primary Navigation', 'genericstarter' ),
			'menu-2' => esc_html__( 'Top Bar Navigation', 'genericstarter' ),
			'menu-3' => esc_html__( 'Footer Navigation', 'genericstarter' ),
		] );
	}
endif;
add_action( 'after_setup_theme', 'genericstarter_setup' );

function genericstarter_widgets_init(): void {
	$widget_defaults = [
		'before_widget' => '<section id="%1$s" class="widget %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h3 class="widget-title font-heading text-neutral-900 dark:text-neutral-50 text-lg font-bold mb-4">',
		'after_title'   => '</h3>',
	];

	register_sidebar( array_merge( $widget_defaults, [
		'name'        => esc_html__( 'Sidebar', 'genericstarter' ),
		'id'          => 'sidebar-1',
		'description' => esc_html__( 'Primary sidebar — shown on posts and pages when layout allows.', 'genericstarter' ),
	] ) );

	foreach ( range( 2, 5 ) as $i ) {
		register_sidebar( array_merge( $widget_defaults, [
			/* translators: %d: footer column number */
			'name'        => sprintf( esc_html__( 'Footer Column %d', 'genericstarter' ), $i - 1 ),
			'id'          => 'sidebar-' . $i,
			'description' => sprintf( esc_html__( 'Footer widget column %d.', 'genericstarter' ), $i - 1 ),
		] ) );
	}
}
add_action( 'widgets_init', 'genericstarter_widgets_init' );
