<?php
declare(strict_types=1);
/**
 * Sanitization callbacks for all Customizer settings
 *
 * @package MedSpaStarter
 */

function medspastarter_sanitize_checkbox( mixed $value ): bool {
	return (bool) $value;
}

function medspastarter_sanitize_select( string $value, WP_Customize_Setting $setting ): string {
	$control = $setting->manager->get_control( $setting->id );
	$choices = ( $control instanceof WP_Customize_Control ) ? $control->choices : [];
	return array_key_exists( $value, $choices ) ? $value : (string) $setting->default;
}

function medspastarter_sanitize_range( mixed $value, WP_Customize_Setting $setting ): int {
	$control  = $setting->manager->get_control( $setting->id );
	$attrs    = ( $control instanceof WP_Customize_Control ) ? $control->input_attrs : [];
	$min      = isset( $attrs['min'] ) ? (int) $attrs['min'] : 0;
	$max      = isset( $attrs['max'] ) ? (int) $attrs['max'] : 100;
	return max( $min, min( $max, (int) $value ) );
}

function medspastarter_sanitize_integer( mixed $value ): int {
	return (int) $value;
}

function medspastarter_sanitize_hex_color( string $value ): string {
	return sanitize_hex_color( $value ) ?? '';
}

function medspastarter_sanitize_url( string $value ): string {
	return esc_url_raw( $value );
}

function medspastarter_sanitize_textarea( string $value ): string {
	return sanitize_textarea_field( $value );
}

function medspastarter_sanitize_html( string $value ): string {
	return wp_kses_post( $value );
}

function medspastarter_sanitize_nohtml( string $value ): string {
	return wp_strip_all_tags( $value );
}
