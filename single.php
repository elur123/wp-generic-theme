<?php
/**
 * Single post template
 *
 * @package MedSpaStarter
 */

get_header();
?>

<main id="main" class="site-main flex-1 overflow-x-hidden" role="main">
	<div class="section-container py-12 md:py-16">
		<div class="content-area <?php echo medspastarter_has_sidebar() ? 'lg:grid lg:grid-cols-[1fr_300px] lg:gap-12' : ''; ?>">

			<div class="post-wrap">
				<?php while ( have_posts() ) :
					the_post();
					get_template_part( 'template-parts/content' );
					medspastarter_post_navigation();
					comments_template();
				endwhile; ?>
			</div>

			<?php get_sidebar(); ?>

		</div>
	</div>
</main>

<?php
get_footer();
