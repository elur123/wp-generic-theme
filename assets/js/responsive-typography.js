/**
 * Responsive font sizes for core blocks
 *
 * Adds a "Responsive Font Size" panel (Mobile / Tablet / Desktop) to the
 * paragraph and heading block inspectors. Each value is stored on the block and
 * rendered as scoped media-query CSS — in the editor (live, via the device
 * preview switcher) and on the front end (via the render_block PHP filter).
 *
 * Breakpoints mirror the theme's Tailwind defaults:
 *   Mobile  = base (no media query)
 *   Tablet  = min-width: 768px  (md)
 *   Desktop = min-width: 1024px (lg)
 *
 * Vanilla JS against the global `wp` (no JSX / build step).
 *
 * @package GenericStarter
 */
( function ( wp ) {
	'use strict';

	if ( ! wp || ! wp.hooks || ! wp.element ) {
		return;
	}

	var el                         = wp.element.createElement;
	var Fragment                   = wp.element.Fragment;
	var __                         = wp.i18n.__;
	var addFilter                  = wp.hooks.addFilter;
	var InspectorControls          = wp.blockEditor.InspectorControls;
	var PanelBody                  = wp.components.PanelBody;
	var TextControl                = wp.components.TextControl;
	var createHigherOrderComponent = wp.compose.createHigherOrderComponent;

	// Blocks that get the responsive controls. Extend as needed.
	var TARGET_BLOCKS = [ 'core/paragraph', 'core/heading' ];

	var BREAKPOINTS = [
		{ key: 'mobile',  label: __( 'Mobile (base, all screens)', 'genericstarter' ) },
		{ key: 'tablet',  label: __( 'Tablet (≥ 768px)', 'genericstarter' ) },
		{ key: 'desktop', label: __( 'Desktop (≥ 1024px)', 'genericstarter' ) },
	];

	function isTarget( name ) {
		return TARGET_BLOCKS.indexOf( name ) !== -1;
	}

	function hasAnySize( sizes ) {
		return !! ( sizes && ( sizes.mobile || sizes.tablet || sizes.desktop ) );
	}

	function uid() {
		return 'mfs' + Math.random().toString( 36 ).slice( 2, 9 );
	}

	/** Normalize a value the same way the PHP sanitizer does (bare number → px). */
	function normalizeSize( value ) {
		value = ( value || '' ).trim();
		if ( ! value ) {
			return '';
		}
		if ( /^[0-9]*\.?[0-9]+$/.test( value ) ) {
			return value + 'px';
		}
		if ( /^[0-9]*\.?[0-9]+(px|rem|em|vw|vh|vmin|vmax|pt|%)$/.test( value ) ) {
			return value;
		}
		return '';
	}

	/** Build the scoped media-query CSS used for the editor preview. */
	function buildCss( id, sizes ) {
		if ( ! id || ! sizes ) {
			return '';
		}
		var sel     = '.mfs-' + id;
		var mobile  = normalizeSize( sizes.mobile );
		var tablet  = normalizeSize( sizes.tablet );
		var desktop = normalizeSize( sizes.desktop );
		var css     = '';
		// !important to override core's preset font-size classes and inline sizes.
		if ( mobile ) {
			css += sel + '{font-size:' + mobile + ' !important;}';
		}
		if ( tablet ) {
			css += '@media(min-width:768px){' + sel + '{font-size:' + tablet + ' !important;}}';
		}
		if ( desktop ) {
			css += '@media(min-width:1024px){' + sel + '{font-size:' + desktop + ' !important;}}';
		}
		return css;
	}

	// 1. Register the two attributes on the target blocks.
	addFilter(
		'blocks.registerBlockType',
		'genericstarter/responsive-type-attributes',
		function ( settings, name ) {
			if ( ! isTarget( name ) ) {
				return settings;
			}
			settings.attributes = Object.assign( {}, settings.attributes, {
				mspFontSize: { type: 'object', default: {} },
				mspFsId:     { type: 'string', default: '' },
			} );
			return settings;
		}
	);

	// 2. Inspector panel + live editor preview.
	var withControls = createHigherOrderComponent( function ( BlockEdit ) {
		return function ( props ) {
			if ( ! isTarget( props.name ) ) {
				return el( BlockEdit, props );
			}

			var sizes = props.attributes.mspFontSize || {};

			function setSize( key, value ) {
				var next = Object.assign( {}, sizes );
				value = ( value || '' ).trim();
				if ( value ) {
					next[ key ] = value;
				} else {
					delete next[ key ];
				}
				var update = { mspFontSize: next };
				// Assign a stable id the first time a value is set.
				if ( hasAnySize( next ) && ! props.attributes.mspFsId ) {
					update.mspFsId = uid();
				}
				props.setAttributes( update );
			}

			var fields = BREAKPOINTS.map( function ( bp ) {
				return el( TextControl, {
					key: bp.key,
					label: bp.label,
					value: sizes[ bp.key ] || '',
					placeholder: __( 'e.g. 1.25rem, 20px, 4vw', 'genericstarter' ),
					onChange: function ( v ) { setSize( bp.key, v ); },
					__nextHasNoMarginBottom: true,
				} );
			} );

			var previewCss = buildCss( props.attributes.mspFsId, sizes );

			return el( Fragment, {},
				el( BlockEdit, props ),
				el( InspectorControls, {},
					el( PanelBody, {
						title: __( 'Responsive Font Size', 'genericstarter' ),
						initialOpen: false,
					},
						el( 'p', {
							style: { marginTop: 0, color: '#757575', fontSize: '12px' },
						}, __( 'Leave blank to inherit. Overrides the default Size above. Switch the editor device preview to test.', 'genericstarter' ) ),
						fields
					)
				),
				previewCss ? el( 'style', {}, previewCss ) : null
			);
		};
	}, 'withMspResponsiveType' );
	addFilter( 'editor.BlockEdit', 'genericstarter/responsive-type-controls', withControls );

	// 3. Add the scoping class to the block wrapper in the editor canvas.
	var withClass = createHigherOrderComponent( function ( BlockListBlock ) {
		return function ( props ) {
			if ( ! isTarget( props.name )
				|| ! props.attributes.mspFsId
				|| ! hasAnySize( props.attributes.mspFontSize ) ) {
				return el( BlockListBlock, props );
			}
			var className = ( props.className || '' ) + ' mfs-' + props.attributes.mspFsId;
			return el( BlockListBlock, Object.assign( {}, props, { className: className } ) );
		};
	}, 'withMspResponsiveTypeClass' );
	addFilter( 'editor.BlockListBlock', 'genericstarter/responsive-type-listclass', withClass );

	// 4. Persist the scoping class into the saved block markup.
	addFilter(
		'blocks.getSaveContent.extraProps',
		'genericstarter/responsive-type-saveprops',
		function ( extraProps, blockType, attributes ) {
			if ( ! isTarget( blockType.name ) ) {
				return extraProps;
			}
			if ( attributes.mspFsId && hasAnySize( attributes.mspFontSize ) ) {
				extraProps.className = ( extraProps.className || '' ) + ' mfs-' + attributes.mspFsId;
			}
			return extraProps;
		}
	);

}( window.wp ) );
