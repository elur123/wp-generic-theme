<?php
declare( strict_types=1 );
/**
 * Blank Canvas page template — per-page settings meta box
 *
 * Adds a "Blank Canvas Options" panel to the page editor letting each page
 * using the Blank Canvas template control:
 *   - Full viewport height (lock the page to exactly 100dvh)
 *   - A secondary radial-gradient background, with a slider for its Y position
 *
 * Values are read back in page-templates/blank-canvas.php.
 *
 * @package MedSpaStarter
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Register the meta box on the page edit screen.
 */
function medspastarter_bc_add_meta_box(): void {
	add_meta_box(
		'medspastarter-blank-canvas',
		esc_html__( 'Blank Canvas Options', 'medspastarter' ),
		'medspastarter_bc_render_meta_box',
		'page',
		'side',
		'default'
	);
}
add_action( 'add_meta_boxes_page', 'medspastarter_bc_add_meta_box' );

/**
 * Render the meta box fields.
 */
function medspastarter_bc_render_meta_box( WP_Post $post ): void {
	wp_nonce_field( 'medspastarter_bc_save', 'medspastarter_bc_nonce' );

	$full_height = get_post_meta( $post->ID, '_medspastarter_bc_full_height', true ) === '1';
	$center      = get_post_meta( $post->ID, '_medspastarter_bc_center', true ) === '1';
	$gradient_on = get_post_meta( $post->ID, '_medspastarter_bc_gradient', true ) === '1';
	$color       = get_post_meta( $post->ID, '_medspastarter_bc_gradient_color', true );
	$color       = $color !== '' ? $color : '#f25f5a';
	$pos_y       = get_post_meta( $post->ID, '_medspastarter_bc_gradient_y', true );
	$pos_y       = $pos_y !== '' ? (int) $pos_y : 50;
	?>
	<p class="description" style="margin-top:0;">
		<?php esc_html_e( 'These options only take effect when this page uses the "Blank Canvas (No Header/Footer)" template.', 'medspastarter' ); ?>
	</p>

	<p>
		<label>
			<input type="checkbox" name="medspastarter_bc_full_height" value="1" <?php checked( $full_height ); ?>>
			<?php esc_html_e( 'Full viewport height (lock to 100% of the screen, no scroll)', 'medspastarter' ); ?>
		</label>
	</p>

	<p>
		<label>
			<input type="checkbox" name="medspastarter_bc_center" value="1" <?php checked( $center ); ?>>
			<?php esc_html_e( 'Center content vertically & horizontally on the page', 'medspastarter' ); ?>
		</label>
	</p>

	<hr>

	<p>
		<label>
			<input type="checkbox" name="medspastarter_bc_gradient" value="1" <?php checked( $gradient_on ); ?>>
			<?php esc_html_e( 'Add a secondary radial-gradient background', 'medspastarter' ); ?>
		</label>
	</p>

	<p>
		<label for="medspastarter_bc_gradient_color" style="display:block;margin-bottom:4px;font-weight:600;">
			<?php esc_html_e( 'Gradient color', 'medspastarter' ); ?>
		</label>
		<input
			type="text"
			id="medspastarter_bc_gradient_color"
			name="medspastarter_bc_gradient_color"
			class="medspastarter-bc-color"
			value="<?php echo esc_attr( $color ); ?>"
			data-default-color="#f25f5a"
		>
	</p>

	<p>
		<label for="medspastarter_bc_gradient_y" style="display:block;margin-bottom:4px;font-weight:600;">
			<?php esc_html_e( 'Gradient vertical position', 'medspastarter' ); ?>
			<span class="medspastarter-bc-y-out"><?php echo esc_html( (string) $pos_y ); ?></span>%
		</label>
		<input
			type="range"
			id="medspastarter_bc_gradient_y"
			name="medspastarter_bc_gradient_y"
			min="0" max="100" step="1"
			value="<?php echo esc_attr( (string) $pos_y ); ?>"
			style="width:100%;"
			oninput="this.closest('p').querySelector('.medspastarter-bc-y-out').textContent=this.value;"
		>
		<span class="description"><?php esc_html_e( '0% = top, 50% = center, 100% = bottom.', 'medspastarter' ); ?></span>
	</p>
	<?php
}

/**
 * Persist the meta box values.
 */
function medspastarter_bc_save_meta( int $post_id ): void {
	if ( ! isset( $_POST['medspastarter_bc_nonce'] )
		|| ! wp_verify_nonce( sanitize_key( wp_unslash( $_POST['medspastarter_bc_nonce'] ) ), 'medspastarter_bc_save' ) ) {
		return;
	}
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}
	if ( ! current_user_can( 'edit_page', $post_id ) ) {
		return;
	}

	update_post_meta(
		$post_id,
		'_medspastarter_bc_full_height',
		isset( $_POST['medspastarter_bc_full_height'] ) ? '1' : ''
	);
	update_post_meta(
		$post_id,
		'_medspastarter_bc_center',
		isset( $_POST['medspastarter_bc_center'] ) ? '1' : ''
	);
	update_post_meta(
		$post_id,
		'_medspastarter_bc_gradient',
		isset( $_POST['medspastarter_bc_gradient'] ) ? '1' : ''
	);

	$color = isset( $_POST['medspastarter_bc_gradient_color'] )
		? sanitize_hex_color( wp_unslash( $_POST['medspastarter_bc_gradient_color'] ) )
		: '';
	update_post_meta( $post_id, '_medspastarter_bc_gradient_color', $color ?? '' );

	$pos_y = isset( $_POST['medspastarter_bc_gradient_y'] )
		? max( 0, min( 100, (int) $_POST['medspastarter_bc_gradient_y'] ) )
		: 50;
	update_post_meta( $post_id, '_medspastarter_bc_gradient_y', (string) $pos_y );
}
add_action( 'save_post_page', 'medspastarter_bc_save_meta' );

/**
 * Load the WordPress color picker on the page editor.
 */
function medspastarter_bc_admin_assets( string $hook ): void {
	if ( ! in_array( $hook, [ 'post.php', 'post-new.php' ], true ) ) {
		return;
	}
	$screen = get_current_screen();
	if ( ! $screen || 'page' !== $screen->post_type ) {
		return;
	}

	wp_enqueue_style( 'wp-color-picker' );
	wp_enqueue_script( 'wp-color-picker' );
	wp_add_inline_script(
		'wp-color-picker',
		"jQuery(function($){ $('.medspastarter-bc-color').wpColorPicker(); });"
	);
}
add_action( 'admin_enqueue_scripts', 'medspastarter_bc_admin_assets' );

/**
 * Load the editor script that auto-hides the meta box and live-previews changes.
 */
function medspastarter_bc_editor_assets(): void {
	$screen = get_current_screen();
	if ( ! $screen || 'page' !== $screen->post_type ) {
		return;
	}

	wp_enqueue_script(
		'medspastarter-blank-canvas-editor',
		get_template_directory_uri() . '/assets/js/blank-canvas-editor.js',
		[ 'wp-data' ],
		MEDSPASTARTER_VERSION,
		true
	);
}
add_action( 'enqueue_block_editor_assets', 'medspastarter_bc_editor_assets' );
