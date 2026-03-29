/**
 * Editor block registration — e3pr0m/devcard
 *
 * Scritto in ES5 puro: nessun build step necessario.
 * Il blocco è server-side rendered (PHP); qui registriamo
 * solo l'interfaccia di editing nell'editor Gutenberg.
 */
( function () {
	'use strict';

	var registerBlockType = wp.blocks.registerBlockType;
	var el                = wp.element.createElement;
	var __                = wp.i18n.__;
	var InspectorControls = wp.blockEditor.InspectorControls;
	var PanelBody         = wp.components.PanelBody;
	var TextControl       = wp.components.TextControl;
	var TextareaControl   = wp.components.TextareaControl;
	var ToggleControl     = wp.components.ToggleControl;
	var ServerSideRender  = wp.serverSideRender;

	registerBlockType( 'e3pr0m/devcard', {

		edit: function ( props ) {
			var a   = props.attributes;
			var set = props.setAttributes;

			return [
				el( InspectorControls, { key: 'inspector' },
					el( PanelBody, {
						title:       __( 'DevCard', 'e3pr0m-devcard' ),
						initialOpen: true,
					},
						el( TextControl, {
							label:    __( 'Nome', 'e3pr0m-devcard' ),
							value:    a.name,
							onChange: function ( v ) { set( { name: v } ); },
						} ),
						el( TextControl, {
							label:    __( 'Ruolo', 'e3pr0m-devcard' ),
							value:    a.role,
							onChange: function ( v ) { set( { role: v } ); },
						} ),
						el( TextareaControl, {
							label:    __( 'Bio', 'e3pr0m-devcard' ),
							value:    a.bio,
							onChange: function ( v ) { set( { bio: v } ); },
						} ),
						el( TextControl, {
							label:    __( 'Avatar URL', 'e3pr0m-devcard' ),
							value:    a.avatar,
							onChange: function ( v ) { set( { avatar: v } ); },
						} ),
						el( TextControl, {
							label:    __( 'GitHub username', 'e3pr0m-devcard' ),
							value:    a.github,
							onChange: function ( v ) { set( { github: v } ); },
						} ),
						el( TextControl, {
							label:    __( 'LinkedIn username', 'e3pr0m-devcard' ),
							value:    a.linkedin,
							onChange: function ( v ) { set( { linkedin: v } ); },
						} ),
						el( TextControl, {
							label:    __( 'X / Twitter username', 'e3pr0m-devcard' ),
							value:    a.twitter,
							onChange: function ( v ) { set( { twitter: v } ); },
						} ),
						el( TextControl, {
							label:    __( 'Sito web (URL)', 'e3pr0m-devcard' ),
							value:    a.website,
							onChange: function ( v ) { set( { website: v } ); },
						} ),
						el( TextControl, {
							label:    __( 'Email', 'e3pr0m-devcard' ),
							value:    a.email,
							onChange: function ( v ) { set( { email: v } ); },
						} ),
						el( TextControl, {
							label:    __( 'Posizione (es. Roma, IT)', 'e3pr0m-devcard' ),
							value:    a.location,
							onChange: function ( v ) { set( { location: v } ); },
						} ),
						el( TextControl, {
							label:    __( 'Skill (separate da virgola)', 'e3pr0m-devcard' ),
							value:    a.skills,
							onChange: function ( v ) { set( { skills: v } ); },
						} ),
						el( ToggleControl, {
							label:    __( 'Disponibile per progetti', 'e3pr0m-devcard' ),
							checked:  a.available,
							onChange: function ( v ) { set( { available: v } ); },
						} )
					)
				),
				el( ServerSideRender, {
					key:        'preview',
					block:      'e3pr0m/devcard',
					attributes: a,
				} ),
			];
		},

		save: function () {
			return null; // server-side rendered
		},

	} );

}() );
