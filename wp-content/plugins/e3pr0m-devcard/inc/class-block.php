<?php
/**
 * Blocco Gutenberg — e3pr0m/devcard
 *
 * Registra il blocco server-side usando block.json.
 * Il render è delegato a e3dc_render_card() definita
 * in class-shortcode.php — HTML identico per blocco e shortcode.
 *
 * @package e3pr0m-devcard
 */

defined( 'ABSPATH' ) || exit;

// =========================================
// REGISTRAZIONE BLOCCO
// =========================================

add_action( 'init', 'e3dc_register_block' );

function e3dc_register_block(): void {
	register_block_type(
		E3DC_DIR . 'block/block.json',
		[
			'render_callback' => 'e3dc_block_render',
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
 * @param string $content Contenuto interno (non utilizzato).
 * @return string         HTML della devcard.
 */
function e3dc_block_render( array $attrs, string $content = '' ): string {

	e3dc_mark_assets_needed();

	return e3dc_render_card(
		name:     $attrs['name']     ?? '',
		role:     $attrs['role']     ?? '',
		bio:      $attrs['bio']      ?? '',
		avatar:   $attrs['avatar']   ?? '',
		github:   $attrs['github']   ?? '',
		twitter:  $attrs['twitter']  ?? '',
		linkedin: $attrs['linkedin'] ?? '',
		website:  $attrs['website']  ?? '',
	);
}
