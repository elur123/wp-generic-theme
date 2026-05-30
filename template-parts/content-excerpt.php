<?php
/**
 * Post card — used in archive/blog loop
 *
 * Used by index.php, archive.php.
 *
 * @package GenericStarter
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class( 'card group flex flex-col' ); ?>>

	<?php if ( get_theme_mod( 'show_post_thumbnail', true ) ) : ?>
	<?php genericstarter_post_thumbnail( 'genericstarter-card' ); ?>
	<?php endif; ?>

	<div class="card-body flex flex-col flex-1 p-5">

		<div class="mb-2">
			<?php genericstarter_category_badge(); ?>
		</div>

		<h2 class="entry-title text-lg font-bold font-heading leading-snug mb-2">
			<a href="<?php the_permalink(); ?>"
				class="text-neutral-900 dark:text-neutral-50 no-underline hover:text-primary dark:hover:text-primary-light transition-colors"
				rel="bookmark">
				<?php the_title(); ?>
			</a>
		</h2>

		<?php if ( get_theme_mod( 'show_post_date', true ) || get_theme_mod( 'show_post_author', true ) ) : ?>
		<div class="post-meta mb-3 text-sm">
			<?php if ( get_theme_mod( 'show_post_date', true ) ) : ?>
				<?php genericstarter_posted_on(); ?>
			<?php endif; ?>
			<?php if ( get_theme_mod( 'show_post_author', true ) ) : ?>
				<?php genericstarter_posted_by(); ?>
			<?php endif; ?>
		</div>
		<?php endif; ?>

		<p class="entry-summary text-sm text-neutral-700/70 dark:text-neutral-400 leading-relaxed flex-1">
			<?php the_excerpt(); ?>
		</p>

		<div class="card-footer mt-4 pt-4 border-t border-neutral-100 dark:border-neutral-700">
			<a href="<?php the_permalink(); ?>" class="inline-flex items-center gap-1.5 text-sm font-semibold text-primary hover:text-primary-dark dark:text-primary-light dark:hover:text-white transition-colors no-underline">
				<?php esc_html_e( 'Read more', 'genericstarter' ); ?>
				<?php genericstarter_icon( 'arrow-right', 'w-4 h-4' ); ?>
			</a>
		</div>

	</div>

</article>
