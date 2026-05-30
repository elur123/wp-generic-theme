<?php
/**
 * 404 error page
 *
 * @package GenericStarter
 */

get_header();
?>

<main id="main" class="site-main flex-1" role="main">
	<div class="section-container py-20 md:py-28 text-center">

		<p class="text-8xl md:text-9xl font-heading font-bold text-primary/20 select-none" aria-hidden="true">404</p>

		<h1 class="text-2xl md:text-3xl font-bold mt-4 mb-3">
			<?php esc_html_e( 'Page Not Found', 'genericstarter' ); ?>
		</h1>

		<p class="text-neutral-700/70 dark:text-neutral-400 max-w-md mx-auto mb-8">
			<?php esc_html_e( 'The page you are looking for may have been moved, deleted, or might never have existed.', 'genericstarter' ); ?>
		</p>

		<div class="flex flex-col sm:flex-row items-center justify-center gap-3 mb-12">
			<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="btn-primary">
				<?php esc_html_e( 'Return Home', 'genericstarter' ); ?>
			</a>
			<a href="<?php echo esc_url( get_post_type_archive_link( 'post' ) ?: home_url( '/blog/' ) ); ?>" class="btn-ghost">
				<?php esc_html_e( 'Browse Articles', 'genericstarter' ); ?>
			</a>
		</div>

		<div class="max-w-sm mx-auto">
			<p class="text-sm text-neutral-700/60 dark:text-neutral-500 mb-3">
				<?php esc_html_e( 'Or search for what you need:', 'genericstarter' ); ?>
			</p>
			<?php get_search_form(); ?>
		</div>

	</div>
</main>

<?php
get_footer();
