/**
 * e3pr0m Code Snippet — block/index.js
 *
 * Registra il blocco Gutenberg lato editor.
 * Scritto senza build step: usa i globali wp.* già presenti
 * nell'editor (caricati come dipendenze in index.asset.php).
 *
 * Struttura del componente edit:
 *  - InspectorControls  → pannello laterale (lang, title, showLines, copyable)
 *  - Anteprima inline   → barra + textarea stilizzata come il frontend
 *
 * Il componente save() ritorna null: il render è server-side (PHP).
 *
 * @package e3pr0m-codesnippet
 */

( function ( blocks, element, blockEditor, components, i18n ) {

	'use strict';

	const { registerBlockType }                        = blocks;
	const { createElement: el, Fragment, useState }   = element;
	const { useBlockProps, InspectorControls }         = blockEditor;
	const {
		PanelBody,
		TextControl,
		TextareaControl,
		ToggleControl,
		SelectControl,
	} = components;
	const { __ } = i18n;

	// =========================================
	// LINGUE — deve rispecchiare e3cs_allowed_languages() in PHP
	// =========================================

	const LANGUAGES = [
		{ label: 'Plaintext',   value: 'plaintext'   },
		{ label: 'HTML',        value: 'html'         },
		{ label: 'CSS',         value: 'css'          },
		{ label: 'SCSS',        value: 'scss'         },
		{ label: 'JavaScript',  value: 'js'           },
		{ label: 'TypeScript',  value: 'ts'           },
		{ label: 'PHP',         value: 'php'          },
		{ label: 'Python',      value: 'python'       },
		{ label: 'Bash / Shell',value: 'bash'         },
		{ label: 'SQL',         value: 'sql'          },
		{ label: 'JSON',        value: 'json'         },
		{ label: 'YAML',        value: 'yaml'         },
		{ label: 'Markdown',    value: 'markdown'     },
		{ label: 'XML / SVG',   value: 'xml'          },
	];

	// =========================================
	// STILI INLINE — anteprima editor coerente col frontend
	// Usati solo nel contesto editor (non servono sul frontend).
	// =========================================

	const styles = {
		wrapper: {
			background:   '#0d0d0d',
			border:       '1px solid #1f1f1f',
			borderRadius: '6px',
			overflow:     'hidden',
			fontFamily:   "'JetBrains Mono', 'Fira Code', monospace",
			fontSize:     '0.875rem',
		},
		bar: {
			display:        'flex',
			alignItems:     'center',
			gap:            '0.5rem',
			padding:        '0.5rem 1rem',
			background:     '#141414',
			borderBottom:   '1px solid #1f1f1f',
		},
		lang: {
			fontSize:      '0.75rem',
			color:         '#00ff99',
			letterSpacing: '0.05em',
		},
		title: {
			fontSize:   '0.75rem',
			color:      '#666',
			flex:       1,
			overflow:   'hidden',
			whiteSpace: 'nowrap',
			textOverflow: 'ellipsis',
		},
		copyBadge: {
			marginLeft:   'auto',
			fontSize:     '0.75rem',
			color:        '#666',
			border:       '1px solid #1f1f1f',
			borderRadius: '4px',
			padding:      '2px 10px',
			background:   'transparent',
			cursor:       'default',
		},
		textarea: {
			display:    'block',
			width:      '100%',
			margin:     0,
			padding:    '1.25rem 1rem',
			background: '#0d0d0d',
			color:      '#e0e0e0',
			border:     'none',
			outline:    'none',
			resize:     'vertical',
			fontFamily: 'inherit',
			fontSize:   'inherit',
			lineHeight: 1.7,
			minHeight:  '120px',
			boxSizing:  'border-box',
		},
		placeholder: {
			color:      '#666',
			fontStyle:  'italic',
			padding:    '1.25rem 1rem',
		},
	};

	// =========================================
	// BLOCK EDIT COMPONENT
	// =========================================

	function Edit( { attributes, setAttributes } ) {
		const { content, lang, title, showLines, copyable } = attributes;

		const blockProps = useBlockProps( { style: styles.wrapper } );

		// Calcola le righe della textarea in base al contenuto
		const rows = Math.max( 6, ( content.match( /\n/g ) || [] ).length + 2 );

		return el(
			Fragment, null,

			// -----------------------------------------
			// INSPECTOR CONTROLS (pannello laterale)
			// -----------------------------------------
			el( InspectorControls, null,
				el( PanelBody,
					{
						title: __( 'Impostazioni snippet', 'e3pr0m-codesnippet' ),
						initialOpen: true,
					},

					// Linguaggio
					el( SelectControl, {
						label:    __( 'Linguaggio', 'e3pr0m-codesnippet' ),
						value:    lang,
						options:  LANGUAGES,
						onChange: ( val ) => setAttributes( { lang: val } ),
					} ),

					// Titolo
					el( TextControl, {
						label:       __( 'Titolo (opzionale)', 'e3pr0m-codesnippet' ),
						value:       title,
						placeholder: 'es. functions.php',
						onChange:    ( val ) => setAttributes( { title: val } ),
					} ),

					// Numeri di riga
					el( ToggleControl, {
						label:    __( 'Numeri di riga', 'e3pr0m-codesnippet' ),
						checked:  showLines,
						onChange: ( val ) => setAttributes( { showLines: val } ),
					} ),

					// Pulsante copia
					el( ToggleControl, {
						label:    __( 'Pulsante copia', 'e3pr0m-codesnippet' ),
						checked:  copyable,
						onChange: ( val ) => setAttributes( { copyable: val } ),
					} ),
				)
			),

			// -----------------------------------------
			// ANTEPRIMA INLINE (blocco nel canvas editor)
			// -----------------------------------------
			el( 'div', blockProps,

				// Barra superiore
				( title || copyable ) && el( 'div', { style: styles.bar },
					el( 'span', { style: styles.lang }, lang ),
					title && el( 'span', { style: styles.title }, title ),
					copyable && el( 'span', { style: styles.copyBadge }, 'Copia' ),
				),

				// Area di testo del codice
				el( 'textarea', {
					style:       styles.textarea,
					rows:        rows,
					value:       content,
					placeholder: __( 'Incolla qui il tuo codice…', 'e3pr0m-codesnippet' ),
					spellCheck:  false,
					autoComplete:'off',
					onChange: ( e ) => setAttributes( { content: e.target.value } ),
				} ),
			)
		);
	}

	// =========================================
	// REGISTRAZIONE
	// =========================================

	registerBlockType( 'e3pr0m/codesnippet', {
		edit: Edit,

		// Render server-side → save ritorna sempre null.
		// WordPress si aspetta null esplicito, non undefined.
		save: function () {
			return null;
		},
	} );

} )(
	window.wp.blocks,
	window.wp.element,
	window.wp.blockEditor,
	window.wp.components,
	window.wp.i18n,
);
