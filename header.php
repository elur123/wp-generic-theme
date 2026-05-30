<?php
/**
 * Site header — outputs <html> through </header>
 *
 * @package GenericStarter
 */
?>
<!doctype html>
<html <?php language_attributes(); ?> class="">
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="profile" href="https://gmpg.org/xfn/11">
	<?php wp_head(); ?>
</head>

<body <?php body_class( 'bg-neutral-50 dark:bg-neutral-900' ); ?> <?php genericstarter_schema( 'body' ); ?>>
<?php wp_body_open(); ?>

<div id="page" class="site min-h-screen flex flex-col">

	<a class="screen-reader-text" href="#main"><?php esc_html_e( 'Skip to content', 'genericstarter' ); ?></a>

	<?php
	/**
	 * genericstarter_top_bar
	 * @hooked genericstarter_top_bar_output — phone, email, location strip
	 */
	do_action( 'genericstarter_top_bar' ); ?>

	<header id="masthead" <?php genericstarter_schema( 'header' ); ?>
		class="site-header <?php echo get_theme_mod( 'sticky_header', true ) ? 'sticky top-0' : 'relative'; ?> z-50 bg-white/95 backdrop-blur-sm shadow-nav dark:bg-neutral-900/95 dark:border-b dark:border-neutral-800">

		<?php
		$header_layout = get_theme_mod( 'header_layout', 'default' );

		// ── Shared: branding ─────────────────────────────────────────────────
		ob_start(); ?>
		<div class="site-branding flex items-center gap-3 min-w-0">
			<?php if ( has_custom_logo() ) : ?>
				<div class="site-logo shrink-0"><?php the_custom_logo(); ?></div>
			<?php endif; ?>
			<?php if ( get_theme_mod( 'show_header_text', true ) ) : ?>
			<div class="site-meta">
				<a href="<?php echo esc_url( home_url( '/' ) ); ?>"
					class="site-title font-heading font-bold text-neutral-900 dark:text-white no-underline hover:text-primary transition-colors"
					<?php genericstarter_schema( 'site-title' ); ?> rel="home">
					<?php bloginfo( 'name' ); ?>
				</a>
				<?php $description = get_bloginfo( 'description', 'display' );
				if ( $description || is_customize_preview() ) : ?>
				<p class="site-description text-xs text-neutral-700/60 dark:text-neutral-400 hidden sm:block">
					<?php echo esc_html( $description ); ?>
				</p>
				<?php endif; ?>
			</div>
			<?php endif; ?>
		</div>
		<?php $branding = ob_get_clean();

		// ── Shared: actions (search, dark mode, CTA, hamburger) ──────────────
		ob_start();
		do_action( 'genericstarter_search_toggle' );
		do_action( 'genericstarter_dark_mode_toggle' );
		echo '<button class="menu-toggle lg:hidden p-2 rounded-full text-neutral-700 hover:bg-neutral-100 dark:text-neutral-300 dark:hover:bg-neutral-800 transition-colors"'
			. ' aria-controls="mobile-menu" aria-expanded="false"'
			. ' aria-label="' . esc_attr__( 'Toggle navigation', 'genericstarter' ) . '">';
		genericstarter_icon( 'bars-3', 'w-6 h-6' );
		echo '</button>';
		$actions = ob_get_clean();

		// ── Nav schema + aria attrs (reused in each layout) ──────────────────
		ob_start(); genericstarter_schema( 'menu' ); $nav_schema = ob_get_clean();
		$nav_attrs = $nav_schema . ' aria-label="' . esc_attr__( 'Primary navigation', 'genericstarter' ) . '"';
		?>

		<?php if ( 'centered-logo' === $header_layout ) : ?>

		<!-- Layout: centered logo — nav left · logo absolute center · actions right -->
		<div class="section-wide relative">

			<!-- Mobile row: branding left · actions right -->
			<div class="flex items-center justify-between py-2 md:py-3 gap-4 min-h-[4rem] md:min-h-[5rem] lg:hidden">
				<?php echo $branding; // phpcs:ignore ?>
				<div class="header-actions flex items-center gap-1">
					<?php echo $actions; // phpcs:ignore ?>
				</div>
			</div>

			<!-- Desktop row: nav left · logo absolute center · actions right -->
			<div class="hidden lg:flex items-center justify-between py-2 md:py-3 min-h-[4rem] md:min-h-[5rem]">
				<nav id="main-navigation" <?php echo $nav_attrs; // phpcs:ignore ?>
					class="main-navigation">
					<?php do_action( 'genericstarter_primary_menu' ); ?>
				</nav>
				<div class="absolute left-1/2 -translate-x-1/2">
					<?php echo $branding; // phpcs:ignore ?>
				</div>
				<div class="header-actions flex items-center gap-1">
					<?php echo $actions; // phpcs:ignore ?>
				</div>
			</div>

		</div><!-- /.section-wide -->

		<?php elseif ( 'logo-left' === $header_layout ) : ?>

		<!-- Layout: logo left, menu + actions grouped right -->
		<div class="section-wide">

			<!-- Mobile row: branding left · actions right -->
			<div class="flex items-center justify-between py-2 md:py-3 gap-4 min-h-[4rem] md:min-h-[5rem] lg:hidden">
				<?php echo $branding; // phpcs:ignore ?>
				<div class="header-actions flex items-center gap-1">
					<?php echo $actions; // phpcs:ignore ?>
				</div>
			</div>

			<!-- Desktop row: branding left · nav + actions grouped right -->
			<div class="hidden lg:flex items-center justify-between py-2 md:py-3 gap-4 min-h-[4rem] md:min-h-[5rem]">
				<?php echo $branding; // phpcs:ignore ?>
				<div class="flex items-center gap-2">
					<nav id="main-navigation" <?php echo $nav_attrs; // phpcs:ignore ?>
						class="main-navigation">
						<?php do_action( 'genericstarter_primary_menu' ); ?>
					</nav>
					<div class="header-actions flex items-center gap-1">
						<?php echo $actions; // phpcs:ignore ?>
					</div>
				</div>
			</div>

		</div><!-- /.section-wide -->

		<?php elseif ( 'split-menu' === $header_layout ) : ?>

		<!-- Layout: split menu — left half · logo absolute center · right half -->
		<div class="section-wide relative">

			<!-- Mobile row: branding left · hamburger right -->
			<div class="flex items-center justify-between py-2 md:py-3 gap-4 min-h-[4rem] md:min-h-[5rem] lg:hidden">
				<?php echo $branding; // phpcs:ignore ?>
				<button class="menu-toggle p-2 rounded-full text-neutral-700 hover:bg-neutral-100 dark:text-neutral-300 dark:hover:bg-neutral-800 transition-colors"
					aria-controls="mobile-menu"
					aria-expanded="false"
					aria-label="<?php esc_attr_e( 'Toggle navigation', 'genericstarter' ); ?>">
					<?php genericstarter_icon( 'bars-3', 'w-6 h-6' ); ?>
				</button>
			</div>

			<!-- Desktop row: left nav · logo absolute center · right nav -->
			<div class="hidden lg:flex items-center justify-between py-2 md:py-3 min-h-[4rem] md:min-h-[5rem]">
				<nav id="main-navigation-left" <?php echo $nav_schema; // phpcs:ignore ?>
					aria-label="<?php esc_attr_e( 'Primary navigation left', 'genericstarter' ); ?>"
					class="main-navigation">
					<?php genericstarter_split_nav_menu( 'left' ); ?>
				</nav>

				<div class="absolute left-1/2 -translate-x-1/2">
					<?php echo $branding; // phpcs:ignore ?>
				</div>

				<nav id="main-navigation-right"
					aria-label="<?php esc_attr_e( 'Primary navigation right', 'genericstarter' ); ?>"
					class="main-navigation">
					<?php genericstarter_split_nav_menu( 'right' ); ?>
				</nav>
			</div>

		</div><!-- /.section-wide -->

		<?php else : ?>

		<!-- Layout: default — logo left, menu center (flex-1), actions right -->
		<div class="section-wide flex items-center justify-between min-h-[4rem] md:min-h-[5rem] py-2 md:py-3 gap-4">

			<?php echo $branding; // phpcs:ignore ?>

			<nav id="main-navigation" <?php echo $nav_attrs; // phpcs:ignore ?>
				class="main-navigation flex-1">
				<?php do_action( 'genericstarter_primary_menu' ); ?>
			</nav>

			<div class="header-actions flex items-center gap-1">
				<?php echo $actions; // phpcs:ignore ?>
			</div>

		</div><!-- /.section-wide -->

		<?php endif; ?>

	</header><!-- #masthead -->

<!-- Mobile drawer backdrop -->
<div id="mobile-drawer-overlay"
	class="fixed inset-0 z-40 bg-neutral-900/60 backdrop-blur-sm lg:hidden
	       opacity-0 pointer-events-none transition-opacity duration-300"></div>

<!-- Mobile drawer panel -->
<nav id="mobile-menu"
	aria-label="<?php esc_attr_e( 'Mobile navigation', 'genericstarter' ); ?>"
	class="fixed inset-y-0 right-0 z-50 w-80 max-w-[85vw] flex flex-col lg:hidden
	       bg-white dark:bg-neutral-900 shadow-2xl overflow-hidden
	       translate-x-full transition-transform duration-300 ease-in-out">

	<!-- Drawer header -->
	<div class="flex items-center justify-between px-5 py-4 border-b border-neutral-100 dark:border-neutral-800 shrink-0">
		<span class="font-heading font-bold text-lg text-neutral-900 dark:text-white">
			<?php esc_html_e( 'Menu', 'genericstarter' ); ?>
		</span>
		<button class="drawer-close p-2 rounded-full text-neutral-500 hover:text-neutral-900 hover:bg-neutral-100 dark:text-neutral-400 dark:hover:text-white dark:hover:bg-neutral-800 transition-colors"
			aria-label="<?php esc_attr_e( 'Close menu', 'genericstarter' ); ?>">
			<?php genericstarter_icon( 'x-mark', 'w-5 h-5' ); ?>
		</button>
	</div>

	<!-- Nav links -->
	<div class="flex-1 px-3 py-3 overflow-y-auto">
		<?php
		wp_nav_menu( [
			'theme_location' => 'menu-1',
			'menu_id'        => 'mobile-menu-list',
			'menu_class'     => 'nav-menu space-y-0.5',
			'container'      => false,
			'fallback_cb'    => false,
		] );
		?>
	</div>

</nav>
