<?php
/**
 * Asset manifest — block/index.asset.php
 *
 * WordPress legge questo file quando incontra "editorScript": "file:./index.js"
 * in block.json. Dichiara le dipendenze globali wp.* e la versione,
 * così WP sa che script attendere prima di caricare il nostro.
 *
 * Generato normalmente da @wordpress/scripts (webpack).
 * Qui è scritto a mano perché il plugin non usa un build step.
 *
 * @package e3pr0m-codesnippet
 */

return [
	'dependencies' => [
		'wp-blocks',        // registerBlockType
		'wp-element',       // createElement, Fragment
		'wp-block-editor',  // useBlockProps, InspectorControls
		'wp-components',    // PanelBody, TextControl, ToggleControl, SelectControl
		'wp-i18n',          // __()
	],
	'version' => '1.0.0',
];
