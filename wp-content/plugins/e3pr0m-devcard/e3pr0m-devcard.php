<?php
/**
 * Plugin Name:       e3pr0m DevCard
 * Plugin URI:        https://www.e3pr0m.com
 * Description:       Widget "Chi sono" — mostra una card sviluppatore con avatar, ruolo, bio e link social. Disponibile come shortcode e blocco Gutenberg.
 * Version:           1.0.0
 * Requires at least: 6.0
 * Requires PHP:      8.2
 * Author:            e3pr0m
 * Author URI:        https://www.e3pr0m.com
 * License:           GPL-2.0-or-later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       e3pr0m-devcard
 * Domain Path:       /languages
 *
 * @package e3pr0m-devcard
 */

defined( 'ABSPATH' ) || exit;

// =========================================
// COSTANTI
// =========================================

define( 'E3DC_VERSION', '1.0.0' );
define( 'E3DC_DIR',     plugin_dir_path( __FILE__ ) );
define( 'E3DC_URI',     plugin_dir_url( __FILE__ ) );
define( 'E3DC_ASSETS',  E3DC_URI  . 'assets/' );
define( 'E3DC_INC',     E3DC_DIR  . 'inc/' );

// =========================================
// CARICAMENTO TRADUZIONI
// =========================================

add_action( 'init', function () {
	load_plugin_textdomain(
		'e3pr0m-devcard',
		false,
		dirname( plugin_basename( __FILE__ ) ) . '/languages'
	);
} ); 

// =========================================
// INCLUDE — ordine importante:
//   1. enqueue  (registra gli asset)
//   2. shortcode
//   3. block
// =========================================

require_once E3DC_INC . 'enqueue.php';
require_once E3DC_INC . 'class-shortcode.php';
require_once E3DC_INC . 'class-block.php';

// =========================================
// HOOK DI ATTIVAZIONE / DISATTIVAZIONE
// =========================================

register_activation_hook( __FILE__, 'e3dc_on_activate' );
register_deactivation_hook( __FILE__, 'e3dc_on_deactivate' );

/**
 * Azioni all'attivazione del plugin.
 */
function e3dc_on_activate(): void {
	update_option( 'e3dc_version', E3DC_VERSION );
}

/**
 * Azioni alla disattivazione del plugin.
 */
function e3dc_on_deactivate(): void {
	// Nessuna azione distruttiva.
}
