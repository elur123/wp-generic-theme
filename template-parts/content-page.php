<?php
/**
 * Page content
 *
 * Used by page.php.
 *
 * @package MedSpaStarter
 */
?>

<?php medspastarter_breadcrumbs(); ?>

<article id="post-<?php the_ID(); ?>" <?php post_class( 'page-entry' ); ?>>

	<?php medspastarter_post_thumbnail(); ?>

	<div class="entry-content">
		<?php
		the_content();
		wp_link_pages( [
			'before' => '<div class="page-links flex flex-wrap items-center gap-2 mt-8">'
				. '<span class="text-sm font-semibold">' . esc_html__( 'Pages:', 'medspastarter' ) . '</span>',
			'after'  => '</div>',
		] );
		?>
	</div>

</article>
