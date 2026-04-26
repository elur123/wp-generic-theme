<?php
declare(strict_types=1);
/**
 * Admin welcome page & first-run notice
 *
 * @package MedSpaStarter
 */

add_action( 'admin_menu', 'medspastarter_welcome_menu' );
add_action( 'admin_notices', 'medspastarter_welcome_notice' );
add_action( 'switch_theme', 'medspastarter_on_theme_activate' );

function medspastarter_on_theme_activate(): void {
	set_transient( 'medspastarter_activated', true, WEEK_IN_SECONDS );
}

function medspastarter_welcome_menu(): void {
	add_theme_page(
		esc_html__( 'MedSpa Starter — Welcome', 'medspastarter' ),
		esc_html__( 'Theme: Welcome', 'medspastarter' ),
		'manage_options',
		'medspastarter-welcome',
		'medspastarter_welcome_page'
	);
}

function medspastarter_welcome_notice(): void {
	if ( ! get_transient( 'medspastarter_activated' ) ) {
		return;
	}
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}
	$welcome_url = admin_url( 'themes.php?page=medspastarter-welcome' );
	?>
	<div class="notice notice-info is-dismissible">
		<p>
			<?php
			printf(
				wp_kses(
					/* translators: %s: link to welcome page */
					__( '<strong>MedSpa Starter</strong> is now active. Visit the <a href="%s">Welcome page</a> to get started.', 'medspastarter' ),
					[ 'strong' => [], 'a' => [ 'href' => [] ] ]
				),
				esc_url( $welcome_url )
			);
			?>
		</p>
	</div>
	<?php
}

function medspastarter_welcome_page(): void {
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}

	$customizer_url     = admin_url( 'customize.php' );
	$importer_url       = admin_url( 'tools.php?page=medspastarter-demo-importer' );
	$editor_url         = admin_url( 'site-editor.php' );
	$patterns_url       = admin_url( 'site-editor.php?path=%2Fpatterns' );
	?>
	<div class="wrap" style="max-width:860px;">

		<div style="background:#fff;border:1px solid #e2e8e0;border-radius:8px;padding:40px 48px;margin-top:24px;">
			<div style="display:flex;align-items:center;gap:16px;margin-bottom:8px;">
				<div style="background:#f25f5a;color:#fff;border-radius:50%;width:48px;height:48px;display:flex;align-items:center;justify-content:center;font-size:22px;flex-shrink:0;">✦</div>
				<div>
					<h1 style="margin:0;font-size:1.7rem;"><?php esc_html_e( 'Welcome to MedSpa Starter', 'medspastarter' ); ?></h1>
					<p style="margin:4px 0 0;color:#6b7280;"><?php esc_html_e( 'A purpose-built WordPress theme for medical spas and wellness clinics.', 'medspastarter' ); ?></p>
				</div>
			</div>
		</div>

		<!-- Quick Start -->
		<div style="background:#fff;border:1px solid #e2e8e0;border-radius:8px;padding:32px 48px;margin-top:16px;">
			<h2 style="margin-top:0;font-size:1.15rem;"><?php esc_html_e( 'Quick Start', 'medspastarter' ); ?></h2>
			<ol style="line-height:2;color:#374151;padding-left:20px;">
				<li><?php printf( wp_kses( __( '<a href="%s">Import demo content</a> to populate the site with sample pages, menus, and settings.', 'medspastarter' ), [ 'a' => [ 'href' => [] ] ] ), esc_url( $importer_url ) ); ?></li>
				<li><?php printf( wp_kses( __( 'Open the <a href="%s">Customizer</a> to set your clinic name, phone, email, colors, and booking link.', 'medspastarter' ), [ 'a' => [ 'href' => [] ] ] ), esc_url( $customizer_url ) ); ?></li>
				<li><?php printf( wp_kses( __( 'Browse the <a href="%s">15 block patterns</a> in the editor (Patterns → MedSpa Starter) and insert them on your pages.', 'medspastarter' ), [ 'a' => [ 'href' => [] ] ] ), esc_url( $patterns_url ) ); ?></li>
				<li><?php esc_html_e( 'Upload your logo via Appearance → Customize → Site Identity.', 'medspastarter' ); ?></li>
				<li><?php esc_html_e( 'Add your real clinic phone, email, and location in Customizer → Header.', 'medspastarter' ); ?></li>
			</ol>
		</div>

		<!-- Features grid -->
		<div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;margin-top:16px;">

			<?php
			$features = [
				[
					'icon'  => '🎨',
					'title' => __( 'Customizer', 'medspastarter' ),
					'desc'  => __( 'Colors, header, footer, dark mode, booking CTA, sidebar layout — all configurable without code.', 'medspastarter' ),
					'link'  => $customizer_url,
					'cta'   => __( 'Open Customizer', 'medspastarter' ),
				],
				[
					'icon'  => '🧩',
					'title' => __( '15 Block Patterns', 'medspastarter' ),
					'desc'  => __( 'Hero, services grid, before/after gallery, team bios, testimonials, pricing, FAQ, booking CTA, and more.', 'medspastarter' ),
					'link'  => $patterns_url,
					'cta'   => __( 'Browse Patterns', 'medspastarter' ),
				],
				[
					'icon'  => '🌙',
					'title' => __( 'Dark Mode', 'medspastarter' ),
					'desc'  => __( 'Class-based dark mode persisted in localStorage. Enable/disable and set default in Customizer → Dark Mode.', 'medspastarter' ),
					'link'  => $customizer_url . '&autofocus[section]=medspastarter_dark_mode',
					'cta'   => __( 'Dark Mode Settings', 'medspastarter' ),
				],
				[
					'icon'  => '📥',
					'title' => __( 'Demo Content', 'medspastarter' ),
					'desc'  => __( 'Import a fully built Serenova Med Spa demo with pages, menus, and default theme settings in one click.', 'medspastarter' ),
					'link'  => $importer_url,
					'cta'   => __( 'Import Demo', 'medspastarter' ),
				],
			];

			foreach ( $features as $f ) :
			?>
			<div style="background:#fff;border:1px solid #e2e8e0;border-radius:8px;padding:28px 32px;">
				<div style="font-size:1.8rem;margin-bottom:12px;"><?php echo esc_html( $f['icon'] ); ?></div>
				<h3 style="margin:0 0 8px;font-size:1rem;"><?php echo esc_html( $f['title'] ); ?></h3>
				<p style="margin:0 0 16px;color:#6b7280;font-size:0.9rem;line-height:1.6;"><?php echo esc_html( $f['desc'] ); ?></p>
				<a href="<?php echo esc_url( $f['link'] ); ?>" class="button button-secondary"><?php echo esc_html( $f['cta'] ); ?></a>
			</div>
			<?php endforeach; ?>

		</div>

		<!-- Theme info -->
		<div style="background:#f9fafb;border:1px solid #e2e8e0;border-radius:8px;padding:20px 32px;margin-top:16px;display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:12px;">
			<div style="color:#6b7280;font-size:0.85rem;">
				<?php
				$theme = wp_get_theme();
				printf(
					/* translators: 1: theme version 2: WordPress version 3: PHP version */
					esc_html__( 'MedSpa Starter v%1$s · WordPress %2$s · PHP %3$s', 'medspastarter' ),
					esc_html( $theme->get( 'Version' ) ),
					esc_html( get_bloginfo( 'version' ) ),
					esc_html( PHP_MAJOR_VERSION . '.' . PHP_MINOR_VERSION )
				);
				?>
			</div>
			<a href="<?php echo esc_url( $customizer_url ); ?>" class="button button-primary">
				<?php esc_html_e( 'Customize Your Site', 'medspastarter' ); ?>
			</a>
		</div>

	</div>
	<?php
}
