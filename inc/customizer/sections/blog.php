<?php
declare(strict_types=1);
/**
 * Customizer — Blog section
 *
 * @package GenericStarter
 */

function genericstarter_customizer_blog( WP_Customize_Manager $wp_customize ): void {

	$wp_customize->add_section( 'genericstarter_blog', [
		'title'    => esc_html__( 'Blog', 'genericstarter' ),
		'priority' => 70,
	] );

	// Blog layout
	$wp_customize->add_setting( 'blog_layout', [
		'default'           => 'grid',
		'sanitize_callback' => 'genericstarter_sanitize_select',
		'transport'         => 'refresh',
	] );
	$wp_customize->add_control( 'blog_layout', [
		'label'   => esc_html__( 'Post Layout', 'genericstarter' ),
		'section' => 'genericstarter_blog',
		'type'    => 'select',
		'choices' => [
			'grid' => esc_html__( 'Grid', 'genericstarter' ),
			'list' => esc_html__( 'List', 'genericstarter' ),
		],
	] );

	// Blog columns (only relevant when layout = grid)
	$wp_customize->add_setting( 'blog_columns', [
		'default'           => 3,
		'sanitize_callback' => 'genericstarter_sanitize_range',
		'transport'         => 'refresh',
	] );
	$wp_customize->add_control(
		new Genericstarter_Range_Control( $wp_customize, 'blog_columns', [
			'label'       => esc_html__( 'Grid Columns', 'genericstarter' ),
			'description' => esc_html__( 'Number of columns in grid layout (2–4).', 'genericstarter' ),
			'section'     => 'genericstarter_blog',
			'input_attrs' => [ 'min' => 2, 'max' => 4, 'step' => 1 ],
		] )
	);

	// Excerpt length
	$wp_customize->add_setting( 'excerpt_length', [
		'default'           => 28,
		'sanitize_callback' => 'genericstarter_sanitize_range',
		'transport'         => 'refresh',
	] );
	$wp_customize->add_control(
		new Genericstarter_Range_Control( $wp_customize, 'excerpt_length', [
			'label'       => esc_html__( 'Excerpt Length (words)', 'genericstarter' ),
			'section'     => 'genericstarter_blog',
			'input_attrs' => [ 'min' => 10, 'max' => 80, 'step' => 2 ],
		] )
	);

	// Show post thumbnail
	$wp_customize->add_setting( 'show_post_thumbnail', [
		'default'           => true,
		'sanitize_callback' => 'genericstarter_sanitize_checkbox',
		'transport'         => 'refresh',
	] );
	$wp_customize->add_control( 'show_post_thumbnail', [
		'label'   => esc_html__( 'Show featured image on post cards', 'genericstarter' ),
		'section' => 'genericstarter_blog',
		'type'    => 'checkbox',
	] );

	// Show post author
	$wp_customize->add_setting( 'show_post_author', [
		'default'           => true,
		'sanitize_callback' => 'genericstarter_sanitize_checkbox',
		'transport'         => 'refresh',
	] );
	$wp_customize->add_control( 'show_post_author', [
		'label'   => esc_html__( 'Show author name on post cards', 'genericstarter' ),
		'section' => 'genericstarter_blog',
		'type'    => 'checkbox',
	] );

	// Show post date
	$wp_customize->add_setting( 'show_post_date', [
		'default'           => true,
		'sanitize_callback' => 'genericstarter_sanitize_checkbox',
		'transport'         => 'refresh',
	] );
	$wp_customize->add_control( 'show_post_date', [
		'label'   => esc_html__( 'Show publication date on post cards', 'genericstarter' ),
		'section' => 'genericstarter_blog',
		'type'    => 'checkbox',
	] );
}
