<?php
declare(strict_types=1);
/**
 * MedSpa Starter — theme bootstrap
 *
 * @package MedSpaStarter
 */

if ( ! defined( 'MEDSPASTARTER_VERSION' ) ) {
	define( 'MEDSPASTARTER_VERSION', wp_get_theme()->get( 'Version' ) );
}

require_once get_template_directory() . '/inc/setup.php';
require_once get_template_directory() . '/inc/enqueue.php';
require_once get_template_directory() . '/inc/svg-icons.php';
require_once get_template_directory() . '/inc/template-tags.php';
require_once get_template_directory() . '/inc/template-functions.php';
require_once get_template_directory() . '/inc/template-hooks.php';
require_once get_template_directory() . '/inc/blocks/block-patterns.php';
require_once get_template_directory() . '/inc/blocks/responsive-typography.php';
require_once get_template_directory() . '/inc/customizer/customizer.php';
require_once get_template_directory() . '/starter-content/starter-content.php';

if ( is_admin() ) {
	require_once get_template_directory() . '/inc/admin/demo-importer.php';
	require_once get_template_directory() . '/inc/admin/welcome.php';
	require_once get_template_directory() . '/inc/admin/blank-canvas-meta.php';
}

if ( class_exists( 'WooCommerce' ) ) {
	require_once get_template_directory() . '/inc/woocommerce.php';
}

if ( class_exists( 'Jetpack' ) ) {
	require_once get_template_directory() . '/inc/jetpack.php';
}