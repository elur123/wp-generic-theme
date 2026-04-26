<?php
/**
 * Search results template
 *
 * @package MedSpaStarter
 */

get_header();
?>

<main id="main" class="site-main flex-1 overflow-x-hidden" role="main">
	<div class="section-container py-12 md:py-16">

		<header class="page-header mb-10 pb-8 border-b border-neutral-200 dark:border-neutral-700">
			<h1 class="page-title text-2xl md:text-3xl font-bold mb-3">
				<?php
				printf(
					/* translators: %s: search term */
					esc_html__( 'Results for: %s', 'medspastarter' ),
					'<span class="text-primary">' . get_search_query() . '</span>'
				); ?>
			</h1>
			<div class="mt-4 max-w-sm">
				<?php get_search_form(); ?>
			</div>
		</header>

		<?php if ( have_posts() ) : ?>

		<div class="<?php echo esc_attr( medspastarter_blog_grid_class() ); ?>">
			<?php while ( have_posts() ) :
				the_post();
				get_template_part( 'template-parts/content', 'search' );
			endwhile; ?>
		</div>

		<?php do_action( 'medspastarter_pagination' ); ?>

		<?php else : ?>
			<?php get_template_part( 'template-parts/content', 'none' ); ?>
		<?php endif; ?>

	</div>
</main>

<?php
get_footer();
