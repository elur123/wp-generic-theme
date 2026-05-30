<?php
/**
 * Template Name: Blank Canvas (No Header/Footer)
 * Template Post Type: page
 *
 * Standalone full-page template with no site header, footer, or nav chrome —
 * just the page content on a bare body. Intended for custom landing experiences
 * such as splash screens, coming-soon, or maintenance pages.
 *
 * It does NOT call get_header()/get_footer(), but still fires wp_head(),
 * wp_body_open(), and wp_footer() so theme styles, scripts, and dark mode load.
 *
 * @package MedSpaStarter
 */

declare( strict_types=1 );

$medspastarter_bc_id          = get_queried_object_id();
$medspastarter_bc_full_height = get_post_meta( $medspastarter_bc_id, '_medspastarter_bc_full_height', true ) === '1';
$medspastarter_bc_center      = get_post_meta( $medspastarter_bc_id, '_medspastarter_bc_center', true ) === '1';
$medspastarter_bc_gradient    = get_post_meta( $medspastarter_bc_id, '_medspastarter_bc_gradient', true ) === '1';

// Lock to the viewport when full-height is on; otherwise grow with content.
$medspastarter_bc_height_class = $medspastarter_bc_full_height
	? 'h-[100dvh] overflow-hidden'
	: 'min-h-screen';

// Vertically center content when enabled; otherwise stack normally.
// We deliberately do NOT use items-center / grid place-content here: those size
// the child to its max-content width, so a wide child (e.g. a multi-column
// group) overflows and can't shrink on small screens. Keeping the default
// align-items:stretch lets children fill the width and stay responsive, while
// WordPress's own constrained layout + text-center handle horizontal centering.
$medspastarter_bc_layout_class = $medspastarter_bc_center
	? 'flex flex-col justify-center text-center'
	: 'flex flex-col';

// Build the secondary radial-gradient background, if enabled.
$medspastarter_bc_style = '';
if ( $medspastarter_bc_gradient ) {
	$medspastarter_bc_color = get_post_meta( $medspastarter_bc_id, '_medspastarter_bc_gradient_color', true );
	$medspastarter_bc_color = $medspastarter_bc_color !== '' ? $medspastarter_bc_color : '#f25f5a';

	$medspastarter_bc_y = get_post_meta( $medspastarter_bc_id, '_medspastarter_bc_gradient_y', true );
	$medspastarter_bc_y = $medspastarter_bc_y !== '' ? (int) $medspastarter_bc_y : 50;

	$medspastarter_bc_style = sprintf(
		'background-image:radial-gradient(60%% 60%% at 50%% %d%%, %s, transparent 70%%);',
		$medspastarter_bc_y,
		$medspastarter_bc_color
	);
}
?>
<!doctype html>
<html <?php language_attributes(); ?> class="">
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<?php wp_head(); ?>
</head>

<body <?php body_class( 'bg-neutral-50 dark:bg-neutral-900' ); ?>>
<?php wp_body_open(); ?>

<?php if ( $medspastarter_bc_style ) : ?>
	<!-- Secondary radial-gradient layer — fixed to the viewport, behind content. -->
	<div class="msp-bc-gradient fixed inset-0 z-0 pointer-events-none"
		style="<?php echo esc_attr( $medspastarter_bc_style ); ?>"
		aria-hidden="true"></div>
<?php endif; ?>

<main id="main"
	class="site-main relative z-10 <?php echo esc_attr( $medspastarter_bc_height_class ); ?> <?php echo esc_attr( $medspastarter_bc_layout_class ); ?>"
	role="main">
	<?php while ( have_posts() ) :
		the_post();
		the_content();
	endwhile; ?>
</main>

<?php wp_footer(); ?>
</body>
</html>
