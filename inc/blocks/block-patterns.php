<?php
declare(strict_types=1);
/**
 * Register block pattern categories.
 * Individual patterns auto-register from the patterns/ directory (WordPress 6.0+).
 *
 * @package MedSpaStarter
 */

function medspastarter_register_pattern_categories(): void {
	register_block_pattern_category( 'medspastarter', [
		'label'       => __( 'MedSpa Starter', 'medspastarter' ),
		'description' => __( 'Patterns for medical spa and wellness clinic websites.', 'medspastarter' ),
	] );
}
add_action( 'init', 'medspastarter_register_pattern_categories' );
