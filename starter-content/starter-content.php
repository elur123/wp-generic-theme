<?php
declare(strict_types=1);
/**
 * Starter content definitions
 *
 * Registered via add_theme_support( 'starter-content', ... ) in after_setup_theme.
 * WordPress imports this content when the user activates the theme for the first time
 * and clicks "Get started" in the Customizer.
 *
 * @package MedSpaStarter
 */

function medspastarter_starter_content(): array {
	return [
		// ── Attachments ─────────────────────────────────────────────────────
		'attachments' => [],

		// ── Posts / Pages ───────────────────────────────────────────────────
		'posts' => [
			'front'   => [
				'post_type'    => 'page',
				'post_title'   => _x( 'Home', 'Theme starter content', 'medspastarter' ),
				'post_content' => '<!-- wp:paragraph --><p>' . _x( 'Welcome to your medical spa. Edit this page in the block editor and add any of the 15 included patterns from the pattern inserter.', 'Theme starter content', 'medspastarter' ) . '</p><!-- /wp:paragraph -->',
			],
			'blog'    => [
				'post_type'  => 'page',
				'post_title' => _x( 'Blog', 'Theme starter content', 'medspastarter' ),
			],
			'about'   => [
				'post_type'  => 'page',
				'post_title' => _x( 'About Us', 'Theme starter content', 'medspastarter' ),
			],
			'services' => [
				'post_type'  => 'page',
				'post_title' => _x( 'Services', 'Theme starter content', 'medspastarter' ),
			],
			'contact' => [
				'post_type'  => 'page',
				'post_title' => _x( 'Contact', 'Theme starter content', 'medspastarter' ),
			],
		],

		// ── Options ─────────────────────────────────────────────────────────
		'options' => [
			'show_on_front'  => 'page',
			'page_on_front'  => '{{front}}',
			'page_for_posts' => '{{blog}}',
		],

		// ── Theme mods (Customizer defaults) ────────────────────────────────
		'theme_mods' => [
			'booking_cta_text'  => _x( 'Book Consultation', 'Theme starter content', 'medspastarter' ),
			'booking_url'       => '#book',
			'enable_dark_mode'  => true,
			'has_back_to_top'   => true,
			'has_search'        => true,
			'blog_columns'      => 3,
			'sidebar_posts'     => true,
			'sidebar_pages'     => false,
			'excerpt_length'    => 28,
		],

		// ── Nav menus ────────────────────────────────────────────────────────
		'nav_menus' => [
			'menu-1' => [
				'name'  => _x( 'Primary Navigation', 'Theme starter content', 'medspastarter' ),
				'items' => [
					'link_home'     => [ 'type' => 'custom', 'title' => _x( 'Home', 'Theme starter content', 'medspastarter' ), 'url' => home_url( '/' ) ],
					'page_about'    => [ 'type' => 'post_type', 'object' => 'page', 'object_id' => '{{about}}' ],
					'page_services' => [ 'type' => 'post_type', 'object' => 'page', 'object_id' => '{{services}}' ],
					'page_blog'     => [ 'type' => 'post_type', 'object' => 'page', 'object_id' => '{{blog}}' ],
					'page_contact'  => [ 'type' => 'post_type', 'object' => 'page', 'object_id' => '{{contact}}' ],
				],
			],
			'menu-3' => [
				'name'  => _x( 'Footer Navigation', 'Theme starter content', 'medspastarter' ),
				'items' => [
					'page_about'    => [ 'type' => 'post_type', 'object' => 'page', 'object_id' => '{{about}}' ],
					'page_services' => [ 'type' => 'post_type', 'object' => 'page', 'object_id' => '{{services}}' ],
					'page_contact'  => [ 'type' => 'post_type', 'object' => 'page', 'object_id' => '{{contact}}' ],
				],
			],
		],

		// ── Widgets ─────────────────────────────────────────────────────────
		'widgets' => [
			'sidebar-1' => [
				[ 'search', [] ],
				[ 'recent-posts', [ 'title' => _x( 'Recent Posts', 'Theme starter content', 'medspastarter' ), 'number' => 5 ] ],
				[ 'categories', [ 'title' => _x( 'Categories', 'Theme starter content', 'medspastarter' ) ] ],
			],
			'sidebar-2' => [
				[ 'text', [
					'title' => _x( 'Our Clinic', 'Theme starter content', 'medspastarter' ),
					'text'  => _x( 'A premium medical spa experience, blending science and luxury for transformative results.', 'Theme starter content', 'medspastarter' ),
				] ],
			],
			'sidebar-3' => [
				[ 'nav_menu', [ 'title' => _x( 'Quick Links', 'Theme starter content', 'medspastarter' ) ] ],
			],
			'sidebar-4' => [
				[ 'text', [
					'title' => _x( 'Contact Us', 'Theme starter content', 'medspastarter' ),
					'text'  => _x( 'Phone: (555) 123-4567<br>Email: hello@example.com', 'Theme starter content', 'medspastarter' ),
				] ],
			],
		],
	];
}
