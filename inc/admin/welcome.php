<?php
declare(strict_types=1);
/**
 * Admin welcome page & first-run notice
 *
 * @package GenericStarter
 */

add_action( 'admin_menu', 'genericstarter_welcome_menu' );
add_action( 'admin_notices', 'genericstarter_welcome_notice' );
add_action( 'switch_theme', 'genericstarter_on_theme_activate' );

function genericstarter_on_theme_activate(): void {
	set_transient( 'genericstarter_activated', true, WEEK_IN_SECONDS );
}

function genericstarter_welcome_menu(): void {
	add_theme_page(
		esc_html__( 'Generic Starter — Welcome', 'genericstarter' ),
		esc_html__( 'Theme: Welcome', 'genericstarter' ),
		'manage_options',
		'genericstarter-welcome',
		'genericstarter_welcome_page'
	);
}

function genericstarter_welcome_notice(): void {
	if ( ! get_transient( 'genericstarter_activated' ) ) {
		return;
	}
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}
	$welcome_url = admin_url( 'themes.php?page=genericstarter-welcome' );
	?>
	<div class="notice notice-info is-dismissible">
		<p>
			<?php
			printf(
				wp_kses(
					/* translators: %s: link to welcome page */
					__( '<strong>Generic Starter</strong> is now active. Visit the <a href="%s">Welcome page</a> to get started.', 'genericstarter' ),
					[ 'strong' => [], 'a' => [ 'href' => [] ] ]
				),
				esc_url( $welcome_url )
			);
			?>
		</p>
	</div>
	<?php
}

function genericstarter_welcome_page(): void {
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}

	$customizer_url     = admin_url( 'customize.php' );
	?>
	<div class="wrap" style="max-width:860px;">

		<div style="background:#fff;border:1px solid #e2e8e0;border-radius:8px;padding:40px 48px;margin-top:24px;">
			<div style="display:flex;align-items:center;gap:16px;margin-bottom:8px;">
				<div style="background:#f25f5a;color:#fff;border-radius:50%;width:48px;height:48px;display:flex;align-items:center;justify-content:center;font-size:22px;flex-shrink:0;">✦</div>
				<div>
					<h1 style="margin:0;font-size:1.7rem;"><?php esc_html_e( 'Welcome to Generic Starter', 'genericstarter' ); ?></h1>
					<p style="margin:4px 0 0;color:#6b7280;"><?php esc_html_e( 'A modern, flexible WordPress starter theme built with TailwindCSS v4.', 'genericstarter' ); ?></p>
				</div>
			</div>
		</div>

		<!-- Quick Start -->
		<div style="background:#fff;border:1px solid #e2e8e0;border-radius:8px;padding:32px 48px;margin-top:16px;">
			<h2 style="margin-top:0;font-size:1.15rem;"><?php esc_html_e( 'Quick Start', 'genericstarter' ); ?></h2>
			<ol style="line-height:2;color:#374151;padding-left:20px;">
				<li><?php printf( wp_kses( __( 'Open the <a href="%s">Customizer</a> to set your colors, header, footer, and layout.', 'genericstarter' ), [ 'a' => [ 'href' => [] ] ] ), esc_url( $customizer_url ) ); ?></li>
				<li><?php esc_html_e( 'Upload your logo via Appearance → Customize → Site Identity.', 'genericstarter' ); ?></li>
				<li><?php esc_html_e( 'Create your menus under Appearance → Menus and assign them to the Primary, Top Bar, and Footer locations.', 'genericstarter' ); ?></li>
				<li><?php esc_html_e( 'For development, run "npm install" then "npm run dev" inside the theme folder.', 'genericstarter' ); ?></li>
			</ol>
		</div>

		<!-- Features grid -->
		<div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;margin-top:16px;">

			<?php
			$features = [
				[
					'icon'  => '🎨',
					'title' => __( 'Customizer', 'genericstarter' ),
					'desc'  => __( 'Colors, header, footer, dark mode, blog, and layout — all configurable without code.', 'genericstarter' ),
					'link'  => $customizer_url,
					'cta'   => __( 'Open Customizer', 'genericstarter' ),
				],
				[
					'icon'  => '🌙',
					'title' => __( 'Dark Mode', 'genericstarter' ),
					'desc'  => __( 'Class-based dark mode persisted in localStorage. Enable/disable and set default in Customizer → Dark Mode.', 'genericstarter' ),
					'link'  => $customizer_url . '&autofocus[section]=genericstarter_dark_mode',
					'cta'   => __( 'Dark Mode Settings', 'genericstarter' ),
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
					esc_html__( 'Generic Starter v%1$s · WordPress %2$s · PHP %3$s', 'genericstarter' ),
					esc_html( $theme->get( 'Version' ) ),
					esc_html( get_bloginfo( 'version' ) ),
					esc_html( PHP_MAJOR_VERSION . '.' . PHP_MINOR_VERSION )
				);
				?>
			</div>
			<a href="<?php echo esc_url( $customizer_url ); ?>" class="button button-primary">
				<?php esc_html_e( 'Customize Your Site', 'genericstarter' ); ?>
			</a>
		</div>

	</div>
	<?php
}
