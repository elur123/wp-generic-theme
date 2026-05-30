<?php
/**
 * Site footer — outputs <footer> through </html>
 *
 * @package GenericStarter
 */

/**
 * genericstarter_footer_before
 * @hooked genericstarter_back_to_top_output
 * @hooked genericstarter_search_overlay_output
 */
do_action( 'genericstarter_footer_before' ); ?>

<footer id="colophon" <?php genericstarter_schema( 'footer' ); ?>
	class="site-footer mt-auto bg-neutral-900 text-neutral-300 dark:bg-neutral-950"
	aria-label="<?php esc_attr_e( 'Footer', 'genericstarter' ); ?>">

	<!-- Widget area (4-column grid) -->
	<?php if ( is_active_sidebar( 'sidebar-2' ) || is_active_sidebar( 'sidebar-3' ) || is_active_sidebar( 'sidebar-4' ) || is_active_sidebar( 'sidebar-5' ) ) : ?>
	<div class="footer-widgets border-b border-neutral-800">
		<div class="section-wide py-16">
			<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-10">
				<?php foreach ( range( 2, 5 ) as $i ) : ?>
					<?php if ( is_active_sidebar( 'sidebar-' . $i ) ) : ?>
					<div class="footer-widget-column">
						<?php dynamic_sidebar( 'sidebar-' . $i ); ?>
					</div>
					<?php endif; ?>
				<?php endforeach; ?>
			</div>
		</div>
	</div>
	<?php endif; ?>

	<!-- Footer bottom bar -->
	<div class="footer-bottom">
		<div class="section-wide py-6 flex flex-col sm:flex-row items-center justify-between gap-4">

			<!-- Copyright -->
			<p class="text-sm text-neutral-500 dark:text-neutral-400 text-center sm:text-start">
				<?php
				/**
				 * genericstarter_footer_credits
				 * @hooked genericstarter_footer_credits_output
				 */
				do_action( 'genericstarter_footer_credits' ); ?>
			</p>

			<!-- Footer social icons -->
			<?php
			$social_urls = get_theme_mod( 'footer_social_icons', '' );
			if ( $social_urls ) : ?>
			<div class="footer-social flex items-center gap-1">
				<?php genericstarter_social_icons( 'footer_social_icons' ); ?>
			</div>
			<?php endif; ?>

			<!-- Footer navigation -->
			<?php if ( has_nav_menu( 'menu-3' ) ) : ?>
			<nav aria-label="<?php esc_attr_e( 'Footer navigation', 'genericstarter' ); ?>">
				<?php wp_nav_menu( [
					'theme_location' => 'menu-3',
					'container'      => false,
					'menu_class'     => 'flex flex-wrap items-center gap-x-4 gap-y-1',
					'depth'          => 1,
					'fallback_cb'    => false,
					'link_before'    => '<span class="text-sm text-neutral-500 dark:text-neutral-400 hover:text-white transition-colors">',
					'link_after'     => '</span>',
				] ); ?>
			</nav>
			<?php endif; ?>

		</div>
	</div>

</footer><!-- #colophon -->

</div><!-- #page -->

<?php wp_footer(); ?>
</body>
</html>
