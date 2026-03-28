<?php
/**
 * Registrazione e caricamento degli asset — e3pr0m DevCard
 *
 * Gli script e gli stili vengono REGISTRATI sempre,
 * ma accodati (enqueued) solo quando la pagina contiene
 * effettivamente una devcard.
 *
 * @package e3pr0m-devcard
 */

defined( 'ABSPATH' ) || exit;

// =========================================
// FLAG — la pagina ha bisogno degli asset?
// =========================================

/**
 * Segna che questa pagina contiene almeno una devcard.
 * Può essere chiamata più volte: esegue l'enqueue una volta sola.
 */
function e3dc_mark_assets_needed(): void {
	static $marked = false;
	if ( $marked ) return;
	$marked = true;

	// Se wp_enqueue_scripts è già stato sparato, accodiamo subito.
	if ( did_action( 'wp_enqueue_scripts' ) ) {
		e3dc_do_enqueue();
	}
}

// =========================================
// REGISTRAZIONE (sempre, su wp_enqueue_scripts)
// =========================================

add_action( 'wp_enqueue_scripts', 'e3dc_register_assets' );

function e3dc_register_assets(): void {

	wp_register_style(
		'e3dc-style',
		E3DC_ASSETS . 'css/devcard.css',
		[],
		E3DC_VERSION
	);

	wp_register_script(
		'e3dc-script',
		E3DC_ASSETS . 'js/devcard.js',
		[],
		E3DC_VERSION,
		true
	);
}

// =========================================
// ACCODAMENTO (solo se la pagina ne ha bisogno)
// =========================================

/**
 * Accodamento effettivo degli asset.
 */
function e3dc_do_enqueue(): void {
	static $done = false;
	if ( $done ) return;
	$done = true;

	wp_enqueue_style( 'e3dc-style' );
	wp_enqueue_script( 'e3dc-script' );
}

// =========================================
// EDITOR GUTENBERG — stili nel backend
// =========================================

add_action( 'enqueue_block_editor_assets', 'e3dc_enqueue_editor_assets' );

function e3dc_enqueue_editor_assets(): void {
	wp_enqueue_style(
		'e3dc-style-editor',
		E3DC_ASSETS . 'css/devcard.css',
		[],
		E3DC_VERSION
	);
}
