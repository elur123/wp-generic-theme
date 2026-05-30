<?php
/**
 * Blog index / main loop fallback
 *
 * @package GenericStarter
 */

get_header();
?>

<main id="main" class="site-main flex-1 overflow-x-hidden" role="main">
	<div class="section-container py-12 md:py-16">

		<div class="content-area <?php echo genericstarter_has_sidebar() ? 'lg:grid lg:grid-cols-[1fr_300px] lg:gap-12' : ''; ?>">

			<div class="posts-wrap">
				<?php if ( have_posts() ) : ?>

				<div class="<?php echo esc_attr( genericstarter_blog_grid_class() ); ?>">
					<?php while ( have_posts() ) :
						the_post();
						get_template_part( 'template-parts/content', 'excerpt' );
					endwhile; ?>
				</div>

				<?php
				/**
				 * genericstarter_pagination
				 * @hooked genericstarter_pagination_output
				 */
				do_action( 'genericstarter_pagination' ); ?>

				<?php else : ?>
					<?php get_template_part( 'template-parts/content', 'none' ); ?>
				<?php endif; ?>
			</div>

			<?php get_sidebar(); ?>

		</div>
	</div>
</main>

<?php
get_footer();
