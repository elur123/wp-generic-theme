/* global wp */
/**
 * Customizer live-preview handlers (postMessage transport)
 *
 * Only settings that can be updated purely via DOM manipulation belong here.
 * Everything else uses transport:'refresh' so WordPress reloads the preview iframe.
 */
( function () {
	'use strict';

	if ( typeof wp === 'undefined' || ! wp.customize ) {
		return;
	}

	// ── Helpers ──────────────────────────────────────────────────────────────

	/**
	 * Get or create the inline <style> block used for colour overrides.
	 * @returns {HTMLStyleElement}
	 */
	function getColorStyle() {
		var el = document.getElementById( 'medspastarter-color-overrides' );
		if ( ! el ) {
			el = document.createElement( 'style' );
			el.id = 'medspastarter-color-overrides';
			document.head.appendChild( el );
		}
		return el;
	}

	/**
	 * Set (or update) a single CSS custom property inside :root.
	 * @param {string} property  CSS property name, e.g. '--color-primary'
	 * @param {string} value     New value, e.g. '#2D7D7A'
	 */
	function setCSSProp( property, value ) {
		document.documentElement.style.setProperty( property, value );

		// Also keep the override <style> in sync for Customizer refresh parity
		var style = getColorStyle();
		var current = style.textContent || '';
		var re = new RegExp( property.replace( /[-]/g, '\\$&' ) + '\\s*:[^;]+;' );
		if ( re.test( current ) ) {
			style.textContent = current.replace( re, property + ':' + value + ';' );
		} else {
			// Append to existing :root block, or create one
			if ( /^:root\s*\{/.test( current ) ) {
				style.textContent = current.replace( /\}$/, property + ':' + value + ';}' );
			} else {
				style.textContent = ':root{' + property + ':' + value + ';}';
			}
		}
	}

	// ── Colour settings (instant DOM update via CSS custom properties) ────────

	var colorMap = {
		color_primary:   '--color-primary',
		color_secondary: '--color-secondary',
		color_text:      '--color-text',
		color_heading:   '--color-heading',
		color_border:    '--color-border',
	};

	Object.keys( colorMap ).forEach( function ( setting ) {
		wp.customize( setting, function ( value ) {
			value.bind( function ( newval ) {
				if ( newval ) {
					setCSSProp( colorMap[ setting ], newval );
				}
			} );
		} );
	} );

	// ── Background colour settings (direct element style) ───────────────────

	var bgMap = {
		color_bg_topbar: '.top-bar',
		color_bg_header: '.site-header',
		color_bg_footer: '.site-footer',
	};

	Object.keys( bgMap ).forEach( function ( setting ) {
		wp.customize( setting, function ( value ) {
			value.bind( function ( newval ) {
				document.querySelectorAll( bgMap[ setting ] ).forEach( function ( el ) {
					el.style.backgroundColor = newval || '';
				} );
			} );
		} );
	} );

	wp.customize( 'color_bg_body', function ( value ) {
		value.bind( function ( newval ) {
			document.body.style.backgroundColor = newval || '';
		} );
	} );

	// ── Layout max-width settings ─────────────────────────────────────────────

	wp.customize( 'header_max_width', function ( value ) {
		value.bind( function ( newval ) {
			document.querySelectorAll( '.top-bar .section-wide, .site-header .section-wide' ).forEach( function ( el ) {
				el.style.maxWidth = newval + 'px';
			} );
		} );
	} );

	wp.customize( 'body_max_width', function ( value ) {
		value.bind( function ( newval ) {
			document.querySelectorAll( '.section-container' ).forEach( function ( el ) {
				el.style.maxWidth = newval + 'px';
			} );
		} );
	} );

	wp.customize( 'footer_max_width', function ( value ) {
		value.bind( function ( newval ) {
			document.querySelectorAll( '.site-footer .section-wide' ).forEach( function ( el ) {
				el.style.maxWidth = newval + 'px';
			} );
		} );
	} );

	// ── Booking URL (update href on the header CTA anchor) ───────────────────

	wp.customize( 'booking_url', function ( value ) {
		value.bind( function ( newval ) {
			document.querySelectorAll( '[data-booking-cta]' ).forEach( function ( el ) {
				el.href = newval;
			} );
		} );
	} );

	// ── Site Identity ─────────────────────────────────────────────────────────

	wp.customize( 'logo_width', function ( value ) {
		value.bind( function ( newval ) {
			var px = parseInt( newval, 10 ) + 'px';
			var style = document.getElementById( 'medspastarter-logo-width' );
			if ( style ) {
				style.textContent = '.site-logo a{display:block;line-height:0;}.custom-logo{display:block;max-width:' + px + ';width:100%;height:auto;}';
			}
		} );
	} );

	wp.customize( 'blogname', function ( value ) {
		value.bind( function ( newval ) {
			var el = document.querySelector( '.site-title' );
			if ( el ) { el.textContent = newval; }
		} );
	} );

	wp.customize( 'blogdescription', function ( value ) {
		value.bind( function ( newval ) {
			var el = document.querySelector( '.site-description' );
			if ( el ) { el.textContent = newval; }
		} );
	} );

	wp.customize( 'show_header_text', function ( value ) {
		value.bind( function ( newval ) {
			var meta = document.querySelector( '.site-meta' );
			if ( meta ) {
				meta.style.display = newval ? '' : 'none';
			}
		} );
	} );

} )();
