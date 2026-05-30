<?php
/**
 * No content / empty state
 *
 * Used when no posts are found.
 *
 * @package GenericStarter
 */
?>

<section class="no-results py-16 text-center">

	<div class="mb-6 flex justify-center">
		<?php genericstarter_icon( 'magnifying-glass', 'w-16 h-16 text-neutral-200 dark:text-neutral-700' ); ?>
	</div>

	<h2 class="text-xl font-bold mb-3">
		<?php esc_html_e( 'Nothing found', 'genericstarter' ); ?>
	</h2>

	<?php if ( is_home() && current_user_can( 'publish_posts' ) ) : ?>

		<p class="text-neutral-700/60 dark:text-neutral-400 mb-6">
			<?php
			printf(
				wp_kses(
					/* translators: %s: link to create post */
					__( 'Ready to publish your first post? <a href="%s">Get started here</a>.', 'genericstarter' ),
					[ 'a' => [ 'href' => [] ] ]
				),
				esc_url( admin_url( 'post-new.php' ) )
			);
			?>
		</p>

	<?php elseif ( is_search() ) : ?>

		<p class="text-neutral-700/60 dark:text-neutral-400 mb-6 max-w-sm mx-auto">
			<?php esc_html_e( 'Sorry, no results matched your search. Try different keywords or browse below.', 'genericstarter' ); ?>
		</p>
		<div class="max-w-xs mx-auto">
			<?php get_search_form(); ?>
		</div>

	<?php else : ?>

		<p class="text-neutral-700/60 dark:text-neutral-400 mb-6">
			<?php esc_html_e( 'It seems we can&rsquo;t find what you&rsquo;re looking for. Perhaps searching can help.', 'genericstarter' ); ?>
		</p>
		<div class="max-w-xs mx-auto">
			<?php get_search_form(); ?>
		</div>

	<?php endif; ?>

</section>
