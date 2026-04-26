<?php
/**
 * Search result item
 *
 * Used by search.php.
 *
 * @package MedSpaStarter
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class( 'card flex flex-col sm:flex-row overflow-hidden' ); ?>>

	<?php if ( has_post_thumbnail() ) : ?>
	<a href="<?php the_permalink(); ?>" class="shrink-0 block sm:w-40 md:w-52 overflow-hidden" tabindex="-1" aria-hidden="true">
		<?php the_post_thumbnail( 'thumbnail', [
			'class' => 'w-full h-full object-cover aspect-video sm:aspect-auto transition-transform duration-500 hover:scale-105',
		] ); ?>
	</a>
	<?php endif; ?>

	<div class="p-5 flex flex-col flex-1">

		<div class="mb-1.5">
			<span class="text-xs font-semibold uppercase tracking-wide text-neutral-700/50 dark:text-neutral-500">
				<?php echo esc_html( get_post_type_object( get_post_type() )->labels->singular_name ?? '' ); ?>
			</span>
		</div>

		<h2 class="entry-title text-lg font-bold font-heading leading-snug mb-2">
			<a href="<?php the_permalink(); ?>" class="text-neutral-900 dark:text-neutral-50 no-underline hover:text-primary dark:hover:text-primary-light transition-colors" rel="bookmark">
				<?php the_title(); ?>
			</a>
		</h2>

		<div class="post-meta mb-2 text-sm">
			<?php medspastarter_posted_on(); ?>
		</div>

		<p class="text-sm text-neutral-700/70 dark:text-neutral-400 leading-relaxed">
			<?php the_excerpt(); ?>
		</p>

	</div>

</article>
