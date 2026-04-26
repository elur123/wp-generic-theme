<?php
declare(strict_types=1);
/**
 * WooCommerce compatibility
 *
 * Only loaded when WooCommerce is active (guarded by class_exists in functions.php).
 *
 * @package MedSpaStarter
 */

// Remove default WooCommerce wrappers — we supply our own in the templates
remove_action( 'woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10 );
remove_action( 'woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end', 10 );

add_action( 'woocommerce_before_main_content', 'medspastarter_woocommerce_wrapper_start', 10 );
add_action( 'woocommerce_after_main_content',  'medspastarter_woocommerce_wrapper_end', 10 );

function medspastarter_woocommerce_wrapper_start(): void {
	echo '<main id="primary" class="site-main section-container py-12">';
}

function medspastarter_woocommerce_wrapper_end(): void {
	echo '</main>';
}

// Declare WooCommerce theme support
add_action( 'after_setup_theme', 'medspastarter_woocommerce_setup' );

function medspastarter_woocommerce_setup(): void {
	add_theme_support( 'woocommerce', [
		'thumbnail_image_width' => 600,
		'single_image_width'    => 900,
		'product_grid'          => [
			'default_rows'    => 3,
			'min_rows'        => 1,
			'default_columns' => 3,
			'min_columns'     => 1,
			'max_columns'     => 4,
		],
	] );
	add_theme_support( 'wc-product-gallery-zoom' );
	add_theme_support( 'wc-product-gallery-lightbox' );
	add_theme_support( 'wc-product-gallery-slider' );
}

// Style WooCommerce form fields to match the theme
add_filter( 'woocommerce_form_field_args', 'medspastarter_woocommerce_form_field_args', 10, 3 );

function medspastarter_woocommerce_form_field_args( array $args, string $key, mixed $value ): array {
	$args['input_class'][] = 'w-full rounded-lg border border-neutral-200 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-primary/40 dark:bg-neutral-800 dark:border-neutral-700 dark:text-neutral-100';
	return $args;
}
