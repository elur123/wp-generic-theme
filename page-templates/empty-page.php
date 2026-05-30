<?php
/**
 * Template Name: Empty Page
 * Template Post Type: page
 *
 * Full-width blank canvas — header + footer only, no content wrapping.
 * Ideal for custom Gutenberg block layouts.
 *
 * @package GenericStarter
 */

get_header();
?>

<main id="main" class="site-main flex-1" role="main">
	<?php while ( have_posts() ) :
		the_post();
		the_content();
	endwhile; ?>
</main>

<?php
get_footer();
