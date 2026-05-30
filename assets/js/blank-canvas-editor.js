/**
 * Blank Canvas template — editor enhancements
 *
 * 1. Auto-hides the "Blank Canvas Options" meta box unless the page is using
 *    the Blank Canvas template (template selected in the Page sidebar panel).
 * 2. Live-previews the full-height + radial-gradient settings on the editor
 *    canvas as the meta box fields change, without saving/reloading.
 *
 * Vanilla JS (no build step). Reads template state from wp.data.
 *
 * @package MedSpaStarter
 */
( function ( wp ) {
	'use strict';

	if ( ! wp || ! wp.data ) {
		return;
	}

	var TEMPLATE = 'page-templates/blank-canvas.php';
	var BOX_ID   = 'medspastarter-blank-canvas';

	function isBlankCanvas() {
		var editor = wp.data.select( 'core/editor' );
		if ( ! editor ) {
			return false;
		}
		return editor.getEditedPostAttribute( 'template' ) === TEMPLATE;
	}

	/** The writing canvas — an iframe in WP 6.x, inline on older/fallback. */
	function getCanvasWrapper() {
		var iframe = document.querySelector( 'iframe[name="editor-canvas"]' );
		var doc    = iframe && iframe.contentDocument ? iframe.contentDocument : document;
		return doc.querySelector( '.editor-styles-wrapper' );
	}

	function fieldVal( selector ) {
		return document.querySelector( selector );
	}

	function applyPreview() {
		var wrap = getCanvasWrapper();
		if ( ! wrap ) {
			return;
		}

		// Not the Blank Canvas template → strip any preview styling.
		if ( ! isBlankCanvas() ) {
			wrap.style.backgroundImage = '';
			wrap.style.minHeight       = '';
			wrap.style.display         = '';
			wrap.style.flexDirection   = '';
			wrap.style.justifyContent  = '';
			wrap.style.alignItems      = '';
			wrap.style.textAlign       = '';
			return;
		}

		var fullHeight = fieldVal( 'input[name="medspastarter_bc_full_height"]' );
		wrap.style.minHeight = fullHeight && fullHeight.checked ? '100dvh' : '';

		var center = fieldVal( 'input[name="medspastarter_bc_center"]' );
		if ( center && center.checked ) {
			wrap.style.display        = 'flex';
			wrap.style.flexDirection  = 'column';
			wrap.style.justifyContent = 'center';
			wrap.style.alignItems     = ''; // keep stretch so children stay responsive
			wrap.style.textAlign      = 'center';
		} else {
			wrap.style.display        = '';
			wrap.style.flexDirection  = '';
			wrap.style.justifyContent = '';
			wrap.style.alignItems     = '';
			wrap.style.textAlign      = '';
		}

		var gradient = fieldVal( 'input[name="medspastarter_bc_gradient"]' );
		if ( gradient && gradient.checked ) {
			var colorEl = fieldVal( '#medspastarter_bc_gradient_color' );
			var yEl     = fieldVal( '#medspastarter_bc_gradient_y' );
			var color   = colorEl && colorEl.value ? colorEl.value : '#f25f5a';
			var posY    = yEl ? parseInt( yEl.value, 10 ) : 50;

			wrap.style.backgroundImage =
				'radial-gradient(60% 60% at 50% ' + posY + '%, ' + color + ', transparent 70%)';
		} else {
			wrap.style.backgroundImage = '';
		}
	}

	function toggleMetaBox() {
		var box = document.getElementById( BOX_ID );
		if ( box ) {
			box.style.display = isBlankCanvas() ? '' : 'none';
		}
	}

	// React to template changes (sidebar panel) and other store updates.
	var lastTemplate = null;
	wp.data.subscribe( function () {
		var editor = wp.data.select( 'core/editor' );
		var tpl    = editor ? editor.getEditedPostAttribute( 'template' ) : null;
		if ( tpl !== lastTemplate ) {
			lastTemplate = tpl;
			toggleMetaBox();
		}
		applyPreview();
	} );

	// React to meta box field edits (checkboxes, color picker, range slider).
	document.addEventListener( 'input', function ( e ) {
		if ( e.target.closest && e.target.closest( '#' + BOX_ID ) ) {
			applyPreview();
		}
	} );
	document.addEventListener( 'change', function ( e ) {
		if ( e.target.closest && e.target.closest( '#' + BOX_ID ) ) {
			applyPreview();
		}
	} );

	// Initial pass once the DOM is ready.
	if ( document.readyState !== 'loading' ) {
		toggleMetaBox();
	} else {
		document.addEventListener( 'DOMContentLoaded', toggleMetaBox );
	}
}( window.wp ) );
