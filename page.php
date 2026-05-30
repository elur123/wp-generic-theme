<?php
/**
 * Page template
 *
 * @package GenericStarter
 */

get_header();
?>

<main id="main" class="site-main flex-1 overflow-x-hidden" role="main">
	<div class="section-container">
		<div class="content-area <?php echo genericstarter_has_sidebar() ? 'lg:grid lg:grid-cols-[1fr_300px] lg:gap-12' : ''; ?>">

			<div class="page-wrap">
				<?php while ( have_posts() ) :
					the_post();
					get_template_part( 'template-parts/content', 'page' );

					if ( comments_open() || get_comments_number() ) :
						get_comments_template();
					endif;
				endwhile; ?>
			</div>

		</div>
	</div>
</main>

<?php
get_footer();
