<?php
/**
 * Registrazione e caricamento degli asset
 *
 * Gli script e gli stili vengono REGISTRATI sempre,
 * ma accodati (enqueued) solo quando la pagina contiene
 * effettivamente uno snippet — tramite il flag e3cs_needs_assets().
 *
 * @package e3pr0m-codesnippet
 */

defined( 'ABSPATH' ) || exit;

// =========================================
// FLAG — la pagina ha bisogno degli asset?
// =========================================

/**
 * Stato interno: true se almeno uno shortcode o blocco
 * è stato trovato nella pagina corrente.
 */
function e3cs_mark_assets_needed(): void {
	static $marked = false;
	if ( $marked ) return;
	$marked = true;

	// Se siamo già oltre wp_enqueue_scripts, accodiamo subito.
	if ( did_action( 'wp_enqueue_scripts' ) ) {
		e3cs_do_enqueue();
	}
}

// =========================================
// REGISTRAZIONE (sempre, su wp_enqueue_scripts)
// =========================================

add_action( 'wp_enqueue_scripts', 'e3cs_register_assets' );

function e3cs_register_assets(): void {

	// --- Prism.js core (vendor locale) ---
	wp_register_style(
		'e3cs-prism',
		E3CS_ASSETS . 'vendor/prism/prism.min.css',
		[],
		E3CS_VERSION
	);

	wp_register_script(
		'e3cs-prism',
		E3CS_ASSETS . 'vendor/prism/prism.min.js',
		[],
		E3CS_VERSION,
		true
	);

	// --- Stile del blocco/shortcode ---
	wp_register_style(
		'e3cs-style',
		E3CS_ASSETS . 'css/codesnippet.css',
		[ 'e3cs-prism' ],
		E3CS_VERSION
	);

	// --- JS: copy-to-clipboard ---
	wp_register_script(
		'e3cs-script',
		E3CS_ASSETS . 'js/codesnippet.js',
		[ 'e3cs-prism' ],
		E3CS_VERSION,
		true
	);

	// Stringa localizzata per il pulsante copia (traduzione + feedback)
	wp_localize_script( 'e3cs-script', 'e3csData', [
		'copyLabel'    => __( 'Copia', 'e3pr0m-codesnippet' ),
		'copiedLabel'  => __( 'Copiato!', 'e3pr0m-codesnippet' ),
	] );
}

// =========================================
// ACCODAMENTO (solo se la pagina ne ha bisogno)
// =========================================

/**
 * Accodamento effettivo degli asset.
 * Chiamato da e3cs_mark_assets_needed() oppure
 * dall'hook wp_enqueue_scripts se il blocco è presente.
 */
function e3cs_do_enqueue(): void {
	static $done = false;
	if ( $done ) return;
	$done = true;

	wp_enqueue_style( 'e3cs-style' );
	wp_enqueue_script( 'e3cs-script' );
}

// =========================================
// EDITOR GUTENBERG — stili nel backend
// =========================================

add_action( 'enqueue_block_editor_assets', 'e3cs_enqueue_editor_assets' );

function e3cs_enqueue_editor_assets(): void {

	// Prism nel backend: serve per anteprima live nell'editor
	wp_enqueue_style(
		'e3cs-prism-editor',
		E3CS_ASSETS . 'vendor/prism/prism.min.css',
		[],
		E3CS_VERSION
	);

	wp_enqueue_style(
		'e3cs-style-editor',
		E3CS_ASSETS . 'css/codesnippet.css',
		[ 'e3cs-prism-editor' ],
		E3CS_VERSION
	);
}