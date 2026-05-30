<?php
/**
 * Archive template — categories, tags, author, date, custom post types
 *
 * @package GenericStarter
 */

get_header();
?>

<main id="main" class="site-main flex-1" role="main">
	<div class="section-container py-12 md:py-16">

		<?php if ( have_posts() ) : ?>

		<?php genericstarter_breadcrumbs(); ?>

		<div class="content-area <?php echo genericstarter_has_sidebar() ? 'lg:grid lg:grid-cols-[1fr_300px] lg:gap-12' : ''; ?>">

			<div class="posts-wrap">
				<div class="<?php echo esc_attr( genericstarter_blog_grid_class() ); ?>">
					<?php while ( have_posts() ) :
						the_post();
						get_template_part( 'template-parts/content', 'excerpt' );
					endwhile; ?>
				</div>

				<?php do_action( 'genericstarter_pagination' ); ?>
			</div>

			<?php get_sidebar(); ?>

		</div>

		<?php else : ?>
			<?php get_template_part( 'template-parts/content', 'none' ); ?>
		<?php endif; ?>

	</div>
</main>

<?php
get_footer();
