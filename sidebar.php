<?php
/**
 * Sidebar widget area
 *
 * @package GenericStarter
 */

if ( ! is_active_sidebar( 'sidebar-1' ) ) {
	return;
}
?>

<aside id="secondary" class="widget-area mt-10 lg:mt-0" role="complementary" aria-label="<?php esc_attr_e( 'Sidebar', 'genericstarter' ); ?>">
	<div class="sidebar-inner sticky top-24 space-y-8">
		<?php dynamic_sidebar( 'sidebar-1' ); ?>
	</div>
</aside>
