<?php
declare(strict_types=1);
/**
 * Action hook registrations — callbacks that fire inside templates
 *
 * All markup is in functions here, NOT in the template files themselves.
 *
 * @package GenericStarter
 */

// ─── Header ────────────────────────────────────────────────────────────────

add_action( 'genericstarter_primary_menu',    'genericstarter_primary_menu_output' );
add_action( 'genericstarter_dark_mode_toggle','genericstarter_dark_mode_toggle_output' );
add_action( 'genericstarter_search_toggle',   'genericstarter_search_toggle_output' );
add_action( 'genericstarter_top_bar',         'genericstarter_top_bar_output' );

// ─── Footer ────────────────────────────────────────────────────────────────

add_action( 'genericstarter_footer_before',   'genericstarter_back_to_top_output' );
add_action( 'genericstarter_footer_before',   'genericstarter_search_overlay_output' );
add_action( 'genericstarter_footer_credits',  'genericstarter_footer_credits_output' );

// ─── Content ───────────────────────────────────────────────────────────────

add_action( 'genericstarter_pagination',      'genericstarter_pagination_output' );

// ─── Schema.org JSON-LD ────────────────────────────────────────────────────

add_action( 'wp_head', 'genericstarter_schema_jsonld_output', 2 );

// ══════════════════════════════════════════════════════════════════════
// Callbacks
// ══════════════════════════════════════════════════════════════════════

function genericstarter_primary_menu_output(): void {
	wp_nav_menu( [
		'theme_location'  => 'menu-1',
		'menu_id'         => 'primary-menu',
		'menu_class'      => 'nav-menu hidden lg:flex lg:items-center lg:gap-6',
		'container'       => false,
		'fallback_cb'     => false,
		'items_wrap'      => '<ul id="%1$s" class="%2$s">%3$s</ul>',
		'show_toggles'    => true,
	] );
}

function genericstarter_dark_mode_toggle_output(): void {
	if ( ! get_theme_mod( 'enable_dark_mode', true ) ) {
		return;
	}
	echo '<button data-dark-toggle aria-pressed="false" aria-label="' . esc_attr__( 'Toggle dark mode', 'genericstarter' ) . '"'
		. ' class="p-2 rounded-full text-neutral-700/60 hover:text-primary hover:bg-primary-light transition-colors dark:text-neutral-400 dark:hover:text-primary-light dark:hover:bg-neutral-800">';
	echo '<span class="dark:hidden">' . genericstarter_get_icon( 'moon', 'w-5 h-5' ) . '</span>';
	echo '<span class="hidden dark:block">' . genericstarter_get_icon( 'sun', 'w-5 h-5' ) . '</span>';
	echo '</button>';
}

function genericstarter_search_toggle_output(): void {
	if ( ! get_theme_mod( 'has_search', true ) ) {
		return;
	}
	echo '<button data-search-open aria-label="' . esc_attr__( 'Open search', 'genericstarter' ) . '"'
		. ' class="p-2 rounded-full text-neutral-700/60 hover:text-primary hover:bg-primary-light transition-colors dark:text-neutral-400 dark:hover:text-primary-light dark:hover:bg-neutral-800">';
	genericstarter_icon( 'magnifying-glass', 'w-5 h-5' );
	echo '</button>';
}

function genericstarter_top_bar_output(): void {
	if ( ! get_theme_mod( 'show_top_bar', true ) ) {
		return;
	}

	$phone    = get_theme_mod( 'phone_number', '' );
	$email    = get_theme_mod( 'email_address', '' );
	$location = get_theme_mod( 'clinic_location', '' );
	$social   = get_theme_mod( 'social_links_topbar', '' );

	if ( ! $phone && ! $email && ! $location && ! $social ) {
		return;
	}
	?>
	<div class="top-bar bg-primary text-white text-sm py-2 dark:bg-primary-dark">
		<div class="section-wide flex items-center justify-between gap-4">

			<!-- Left: contact info -->
			<div class="flex items-center gap-x-5 gap-y-1 flex-wrap">
				<?php if ( $location ) : ?>
				<span class="flex items-center gap-1.5 text-white/80">
					<?php genericstarter_icon( 'map-pin', 'w-4 h-4 shrink-0' ); ?>
					<?php echo esc_html( $location ); ?>
				</span>
				<?php endif; ?>
				<?php if ( $email ) : ?>
				<a href="mailto:<?php echo esc_attr( $email ); ?>"
				   class="flex items-center gap-1.5 text-white/80 hover:text-white transition-colors no-underline">
					<?php genericstarter_icon( 'envelope', 'w-4 h-4 shrink-0' ); ?>
					<?php echo esc_html( $email ); ?>
				</a>
				<?php endif; ?>
				<?php if ( $phone ) : ?>
				<a href="tel:<?php echo esc_attr( preg_replace( '/[^+\d]/', '', $phone ) ); ?>"
				   class="flex items-center gap-1.5 text-white/80 hover:text-white transition-colors no-underline">
					<?php genericstarter_icon( 'phone', 'w-4 h-4 shrink-0' ); ?>
					<?php echo esc_html( $phone ); ?>
				</a>
				<?php endif; ?>
			</div>

			<!-- Right: social icons -->
			<?php if ( $social ) : ?>
			<div class="flex items-center gap-0.5 shrink-0">
				<?php genericstarter_social_icons(
					'social_links_topbar',
					'p-1 rounded text-white/70 hover:text-white transition-colors',
					'w-4 h-4'
				); ?>
			</div>
			<?php endif; ?>

		</div>
	</div>
	<?php
}

function genericstarter_back_to_top_output(): void {
	if ( ! get_theme_mod( 'has_back_to_top', true ) ) {
		return;
	}
	echo '<button id="back-to-top" aria-label="' . esc_attr__( 'Back to top', 'genericstarter' ) . '">';
	genericstarter_icon( 'arrow-up', 'w-5 h-5' );
	echo '</button>';
}

function genericstarter_search_overlay_output(): void {
	if ( ! get_theme_mod( 'has_search', true ) ) {
		return;
	}
	?>
	<div id="search-overlay" role="dialog" aria-modal="true" aria-label="<?php esc_attr_e( 'Search', 'genericstarter' ); ?>">
		<div class="search-inner">
			<p class="text-white text-xl font-heading mb-4"><?php esc_html_e( 'What are you looking for?', 'genericstarter' ); ?></p>
			<?php get_search_form(); ?>
			<button data-search-close class="mt-4 text-white/60 hover:text-white text-sm transition-colors"><?php esc_html_e( 'Close (Esc)', 'genericstarter' ); ?></button>
		</div>
	</div>
	<?php
}

function genericstarter_footer_credits_output(): void {
	$credits = get_theme_mod( 'footer_credits_text', '' );

	if ( $credits ) {
		echo wp_kses_post( $credits );
		return;
	}

	$site_name = get_bloginfo( 'name' );
	$year      = gmdate( 'Y' );

	printf(
		'&copy; %1$s <a href="%2$s">%3$s</a>. %4$s',
		esc_html( $year ),
		esc_url( home_url( '/' ) ),
		esc_html( $site_name ),
		esc_html__( 'All rights reserved.', 'genericstarter' )
	);
}

function genericstarter_pagination_output(): void {
	echo '<nav class="pagination-nav" aria-label="' . esc_attr__( 'Posts pagination', 'genericstarter' ) . '">';
	genericstarter_pagination();
	echo '</nav>';
}

function genericstarter_schema_jsonld_output(): void {
	$schema = [
		'@context' => 'https://schema.org',
		'@type'    => 'Organization',
		'name'     => get_bloginfo( 'name' ),
		'url'      => home_url( '/' ),
	];

	$phone    = get_theme_mod( 'phone_number', '' );
	$email    = get_theme_mod( 'email_address', '' );
	$location = get_theme_mod( 'clinic_location', '' );

	if ( $phone )    $schema['telephone'] = $phone;
	if ( $email )    $schema['email']     = $email;
	if ( $location ) {
		$schema['address'] = [
			'@type'         => 'PostalAddress',
			'streetAddress' => $location,
		];
	}

	if ( has_site_icon() ) {
		$schema['logo'] = [
			'@type' => 'ImageObject',
			'url'   => get_site_icon_url( 512 ),
		];
	}

	// Per-page overrides
	if ( is_singular() ) {
		$post_schema = [
			'@context'        => 'https://schema.org',
			'@type'           => 'Article',
			'headline'        => get_the_title(),
			'datePublished'   => get_the_date( 'c' ),
			'dateModified'    => get_the_modified_date( 'c' ),
			'author'          => [
				'@type' => 'Person',
				'name'  => get_the_author(),
			],
		];

		echo '<script type="application/ld+json">'
			. wp_json_encode( $post_schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE )
			. '</script>' . "\n"; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	}

	echo '<script type="application/ld+json">'
		. wp_json_encode( $schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE )
		. '</script>' . "\n"; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
}
