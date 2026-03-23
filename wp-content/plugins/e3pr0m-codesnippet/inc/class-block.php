<?php
/**
 * Blocco Gutenberg — e3pr0m/codesnippet
 *
 * Registra il blocco server-side usando block.json.
 * Il render è delegato a e3cs_render_snippet() definita
 * in class-shortcode.php — HTML identico per blocco e shortcode.
 *
 * @package e3pr0m-codesnippet
 */

defined( 'ABSPATH' ) || exit;

// =========================================
// REGISTRAZIONE BLOCCO
// =========================================

add_action( 'init', 'e3cs_register_block' );

function e3cs_register_block(): void {
	// register_block_type() legge block.json e registra automaticamente
	// editorScript, style ed editorStyle dichiarati nel manifest.
	register_block_type(
		E3CS_DIR . 'block/block.json',
		[
			'render_callback' => 'e3cs_block_render',
		]
	);
}

// =========================================
// RENDER CALLBACK
// =========================================

/**
 * Render server-side del blocco.
 *
 * WordPress passa gli attributi definiti in block.json
 * già castati al tipo corretto (string, bool…).
 *
 * @param array  $attrs   Attributi del blocco.
 * @param string $content Contenuto interno (non usato — il codice
 *                        è nell'attributo 'content').
 * @return string HTML del widget snippet.
 */
function e3cs_block_render( array $attrs, string $content = '' ): string {

	e3cs_mark_assets_needed();

	return e3cs_render_snippet(
		content:    $attrs['content']   ?? '',
		lang:       $attrs['lang']      ?? 'plaintext',
		title:      $attrs['title']     ?? '',
		show_lines: $attrs['showLines'] ?? true,
		copyable:   $attrs['copyable']  ?? true,
	);
}
