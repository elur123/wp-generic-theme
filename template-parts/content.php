<?php
/**
 * Full single post content
 *
 * Used by single.php.
 *
 * @package GenericStarter
 */
?>

<?php genericstarter_breadcrumbs(); ?>

<article id="post-<?php the_ID(); ?>" <?php post_class( 'entry' ); ?> <?php genericstarter_schema( 'article' ); ?>>

	<?php genericstarter_post_thumbnail( 'genericstarter-hero' ); ?>

	<header class="entry-header mb-6">

		<?php genericstarter_category_badge(); ?>

		<h1 class="entry-title font-heading text-3xl md:text-4xl font-bold mt-3 mb-4 text-neutral-900 dark:text-neutral-50" <?php genericstarter_schema( 'entry-title' ); ?>>
			<?php the_title(); ?>
		</h1>

		<div class="post-meta mt-3">
			<?php
			genericstarter_posted_on();
			genericstarter_posted_by();
			genericstarter_posted_in();
			genericstarter_reading_time();
			?>
		</div>

		<?php genericstarter_edit_link(); ?>
	</header>

	<div class="entry-content" <?php genericstarter_schema( 'article' ); ?>>
		<?php
		the_content(
			sprintf(
				wp_kses(
					/* translators: %s: post title */
					__( 'Continue reading<span class="screen-reader-text"> "%s"</span>', 'genericstarter' ),
					[ 'span' => [ 'class' => [] ] ]
				),
				wp_kses_post( get_the_title() )
			)
		);

		wp_link_pages( [
			'before' => '<div class="page-links flex flex-wrap items-center gap-2 mt-8 pt-6 border-t border-neutral-200 dark:border-neutral-700">'
				. '<span class="text-sm font-semibold">' . esc_html__( 'Pages:', 'genericstarter' ) . '</span>',
			'after'  => '</div>',
		] );
		?>
	</div>

	<footer class="entry-footer mt-8">
		<?php genericstarter_tagged_in(); ?>
	</footer>

	<!-- Author box -->
	<?php if ( get_the_author_meta( 'description' ) ) : ?>
	<div class="author-box mt-10 p-6 bg-neutral-100 dark:bg-neutral-800 rounded-2xl flex gap-4">
		<div class="author-avatar shrink-0">
			<?php echo get_avatar( get_the_author_meta( 'ID' ), 64, '', '', [ 'class' => 'rounded-full' ] ); ?>
		</div>
		<div class="author-info">
			<p class="font-semibold text-neutral-900 dark:text-neutral-50">
				<a href="<?php echo esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ); ?>" class="hover:text-primary">
					<?php the_author(); ?>
				</a>
			</p>
			<p class="text-sm text-neutral-700/70 dark:text-neutral-400 mt-1 leading-relaxed">
				<?php echo wp_kses_post( get_the_author_meta( 'description' ) ); ?>
			</p>
		</div>
	</div>
	<?php endif; ?>

</article>
