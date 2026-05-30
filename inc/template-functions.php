<?php
declare(strict_types=1);
/**
 * Filters and helper functions used across templates
 *
 * @package GenericStarter
 */

// ─── Excerpt ───────────────────────────────────────────────────────────────

add_filter( 'excerpt_length', function (): int {
	return (int) get_theme_mod( 'excerpt_length', 28 );
}, 999 );

add_filter( 'excerpt_more', function (): string {
	return '&hellip;';
} );

// ─── Body classes ──────────────────────────────────────────────────────────

add_filter( 'body_class', function ( array $classes ): array {
	if ( ! is_singular() ) {
		$classes[] = 'archive-view';
	}

	if ( genericstarter_has_sidebar() ) {
		$classes[] = 'has-sidebar';
	} else {
		$classes[] = 'no-sidebar';
	}

	if ( is_home() && ! is_front_page() ) {
		$classes[] = 'blog-listing';
	}

	return $classes;
} );

// ─── Pingback URL ──────────────────────────────────────────────────────────

add_action( 'wp_head', function (): void {
	if ( is_singular() && pings_open() ) {
		printf( '<link rel="pingback" href="%s">', esc_url( get_bloginfo( 'pingback_url' ) ) );
	}
} );

// ─── Sidebar helpers ───────────────────────────────────────────────────────

function genericstarter_has_sidebar(): bool {
	if ( is_404() || is_search() ) {
		return false;
	}

	if ( is_singular( 'post' ) && ! get_theme_mod( 'sidebar_posts', true ) ) {
		return false;
	}

	if ( is_page() && ! get_theme_mod( 'sidebar_pages', false ) ) {
		return false;
	}

	return is_active_sidebar( 'sidebar-1' );
}

// ─── Blog layout helpers ───────────────────────────────────────────────────

function genericstarter_blog_columns(): int {
	return (int) get_theme_mod( 'blog_columns', 3 );
}

function genericstarter_blog_grid_class(): string {
	if ( 'list' === get_theme_mod( 'blog_layout', 'grid' ) ) {
		return 'flex flex-col gap-6';
	}

	$cols = genericstarter_blog_columns();
	return match( $cols ) {
		2       => 'grid grid-cols-1 sm:grid-cols-2 gap-8',
		3       => 'grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8',
		4       => 'grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6',
		default => 'grid grid-cols-1 gap-8',
	};
}

// ─── Schema.org output ─────────────────────────────────────────────────────

function genericstarter_schema( string $context ): void {
	$schemas = [
		'body'         => 'itemscope itemtype="https://schema.org/Organization"',
		'header'       => 'itemscope itemtype="https://schema.org/WPHeader"',
		'footer'       => 'itemscope itemtype="https://schema.org/WPFooter"',
		'menu'         => 'itemscope itemtype="https://schema.org/SiteNavigationElement"',
		'site-title'   => 'itemprop="name"',
		'entry-title'  => 'itemprop="headline"',
		'author'       => 'itemprop="author" itemscope itemtype="https://schema.org/Person"',
		'image'        => 'itemprop="image"',
		'time'         => 'itemprop="datePublished"',
		'article'      => 'itemscope itemtype="https://schema.org/Article"',
	];

	if ( isset( $schemas[ $context ] ) ) {
		echo $schemas[ $context ]; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	}
}

// ─── Pagination ────────────────────────────────────────────────────────────

function genericstarter_pagination(): void {
	global $wp_query;

	$total = (int) $wp_query->max_num_pages;

	if ( $total < 2 ) {
		return;
	}

	$current = max( 1, get_query_var( 'paged' ) );

	echo paginate_links( [ // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		'base'      => str_replace( PHP_INT_MAX, '%#%', esc_url( get_pagenum_link( PHP_INT_MAX ) ) ),
		'format'    => '?paged=%#%',
		'current'   => $current,
		'total'     => $total,
		'prev_text' => genericstarter_get_icon( 'chevron-left', 'w-4 h-4' ),
		'next_text' => genericstarter_get_icon( 'chevron-right', 'w-4 h-4' ),
		'before_page_number' => '<span>',
		'after_page_number'  => '</span>',
		'type'      => 'plain',
	] );
}

// ─── Social icons ──────────────────────────────────────────────────────────

function genericstarter_social_icons(
	string $setting_key,
	string $link_class = 'p-1.5 rounded-full text-neutral-700/60 hover:text-primary transition-colors dark:text-neutral-400 dark:hover:text-primary-light',
	string $icon_class = 'w-5 h-5'
): void {
	$urls_string = get_theme_mod( $setting_key, '' );

	if ( ! $urls_string ) {
		return;
	}

	// Accept comma-separated or one-per-line URLs
	$urls = array_filter( array_map( 'trim', preg_split( '/[\r\n,]+/', $urls_string ) ) );

	foreach ( $urls as $url ) {
		$host   = wp_parse_url( $url, PHP_URL_HOST ) ?? '';
		$host   = strtolower( str_ireplace( 'www.', '', $host ) );
		$domain = explode( '.', $host )[0];

		// Normalise t.me → telegram
		if ( 't.me' === $host ) {
			$domain = 'telegram';
		}

		$icon = in_array( $domain, [ 'facebook', 'instagram', 'linkedin', 'youtube', 'tiktok', 'twitter-x' ], true )
			? $domain
			: 'globe';

		echo '<a href="' . esc_url( $url ) . '" class="social-link ' . esc_attr( $link_class ) . '"'
			. ' aria-label="' . esc_attr( ucfirst( $domain ) ) . '" rel="noopener noreferrer" target="_blank">';
		genericstarter_icon( $icon, $icon_class );
		echo '</a>';
	}
}

// ─── Split nav menu ────────────────────────────────────────────────────────

/**
 * Render one half of the primary menu (used by the split-menu header layout).
 *
 * Grabs all items from menu-1, splits top-level items in half, then uses a
 * temporary wp_nav_menu_objects filter to restrict rendering to that half.
 *
 * @param string $side 'left' | 'right'
 */
function genericstarter_split_nav_menu( string $side ): void {
	$locations = get_nav_menu_locations();
	if ( empty( $locations['menu-1'] ) ) {
		return;
	}

	$menu = wp_get_nav_menu_object( $locations['menu-1'] );
	if ( ! $menu ) {
		return;
	}

	$all_items = wp_get_nav_menu_items( $menu->term_id, [ 'orderby' => 'menu_order', 'order' => 'ASC' ] );
	if ( ! $all_items ) {
		return;
	}

	// Top-level items only for splitting
	$top_items = array_values( array_filter( $all_items, fn( $i ) => '0' == $i->menu_item_parent ) );
	$half      = (int) ceil( count( $top_items ) / 2 );

	$chosen = 'left' === $side
		? array_slice( $top_items, 0, $half )
		: array_slice( $top_items, $half );

	$include_ids = array_map( fn( $i ) => $i->ID, $chosen );

	// Pull in direct children of the included top-level items
	foreach ( $all_items as $item ) {
		if ( in_array( (int) $item->menu_item_parent, $include_ids, true ) ) {
			$include_ids[] = $item->ID;
		}
	}

	// Temporarily filter the rendered items to this half only
	$filter = static function ( array $items ) use ( $include_ids ): array {
		return array_values( array_filter( $items, fn( $i ) => in_array( $i->ID, $include_ids, true ) ) );
	};

	add_filter( 'wp_nav_menu_objects', $filter, 99 );

	wp_nav_menu( [
		'theme_location' => 'menu-1',
		'menu_class'     => 'nav-menu hidden lg:flex lg:items-center lg:gap-6',
		'container'      => false,
		'fallback_cb'    => false,
		'items_wrap'     => '<ul id="%1$s" class="%2$s">%3$s</ul>',
	] );

	remove_filter( 'wp_nav_menu_objects', $filter, 99 );
}

// ─── Nav menu sub-toggle ───────────────────────────────────────────────────

add_filter( 'nav_menu_item_args', function ( stdClass $args, WP_Post $item, int $depth ): stdClass {
	if ( isset( $args->show_toggles )
		&& ( in_array( 'menu-item-has-children', $item->classes, true )
			|| in_array( 'page_item_has_children', $item->classes, true ) )
	) {
		$args->after = '<button class="sub-menu-toggle p-1 ml-1 rounded hover:bg-neutral-100 dark:hover:bg-neutral-700"'
			. ' aria-expanded="false" aria-label="' . esc_attr__( 'Toggle sub-menu', 'genericstarter' ) . '">'
			. genericstarter_get_icon( 'chevron-down', 'w-4 h-4' )
			. '</button>';
	}
	return $args;
}, 10, 3 );
