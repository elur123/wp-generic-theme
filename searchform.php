<?php
/**
 * Search form
 *
 * @package MedSpaStarter
 */

$unique_id = wp_unique_id( 'search-form-' );
?>
<form role="search" method="get" class="search-form" action="<?php echo esc_url( home_url( '/' ) ); ?>">
	<label for="<?php echo esc_attr( $unique_id ); ?>" class="sr-only">
		<?php esc_html_e( 'Search for:', 'medspastarter' ); ?>
	</label>
	<input
		type="search"
		id="<?php echo esc_attr( $unique_id ); ?>"
		class=""
		placeholder="<?php echo esc_attr_x( 'Search treatments, articles&hellip;', 'placeholder', 'medspastarter' ); ?>"
		value="<?php echo get_search_query(); ?>"
		name="s"
	>
	<button type="submit" class="btn-primary py-2.5 px-4" aria-label="<?php esc_attr_e( 'Submit search', 'medspastarter' ); ?>">
		<?php medspastarter_icon( 'magnifying-glass', 'w-4 h-4' ); ?>
	</button>
</form>
